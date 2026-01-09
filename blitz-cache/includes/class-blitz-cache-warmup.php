<?php
class Blitz_Cache_Warmup {
    private array $options;

    public function __construct() {
        $this->options = Blitz_Cache_Options::get();
    }

    public function run(): void {
        if (empty($this->options['warmup_enabled'])) {
            return;
        }

        $urls = $this->get_urls();
        $urls = apply_filters('blitz_cache_warmup_urls', $urls);

        $batch_size = $this->options['warmup_batch_size'] ?? 5;
        $batches = array_chunk($urls, $batch_size);

        foreach ($batches as $batch) {
            foreach ($batch as $url) {
                $this->warm_url($url);
            }
            // Small delay between batches to avoid overloading server
            usleep(500000); // 0.5 seconds
        }

        $this->update_warmup_stats();

        do_action('blitz_cache_after_warmup', $urls);
    }

    public function warm_url(string $url): bool {
        $response = wp_remote_get($url, [
            'timeout' => 30,
            'sslverify' => false,
            'headers' => [
                'Cache-Control' => 'no-cache',
                'X-Blitz-Warmup' => '1',
            ],
        ]);

        return !is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200;
    }

    private function get_urls(): array {
        $source = $this->options['warmup_source'] ?? 'sitemap';

        switch ($source) {
            case 'sitemap':
                return $this->get_sitemap_urls();
            case 'menu':
                return $this->get_menu_urls();
            case 'custom':
                return apply_filters('blitz_cache_custom_warmup_urls', []);
            default:
                return $this->get_sitemap_urls();
        }
    }

    private function get_sitemap_urls(): array {
        $urls = [];

        // Try WordPress native sitemap first (WP 5.5+)
        $sitemap_url = home_url('/wp-sitemap.xml');
        $response = wp_remote_get($sitemap_url, ['timeout' => 10]);

        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
            $body = wp_remote_retrieve_body($response);
            $urls = $this->parse_sitemap_index($body);
        }

        // Fallback: Yoast SEO sitemap
        if (empty($urls)) {
            $sitemap_url = home_url('/sitemap_index.xml');
            $response = wp_remote_get($sitemap_url, ['timeout' => 10]);

            if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
                $body = wp_remote_retrieve_body($response);
                $urls = $this->parse_sitemap_index($body);
            }
        }

        // Fallback: generate from posts
        if (empty($urls)) {
            $urls = $this->generate_url_list();
        }

        return array_slice($urls, 0, 500); // Limit to 500 URLs
    }

    private function parse_sitemap_index(string $xml): array {
        $urls = [];

        // Suppress XML errors
        libxml_use_internal_errors(true);
        $sitemap = simplexml_load_string($xml);

        if ($sitemap === false) {
            return [];
        }

        // Check if this is a sitemap index or direct sitemap
        if (isset($sitemap->sitemap)) {
            // Sitemap index - fetch each child sitemap
            foreach ($sitemap->sitemap as $child) {
                $child_urls = $this->fetch_sitemap((string)$child->loc);
                $urls = array_merge($urls, $child_urls);
            }
        } elseif (isset($sitemap->url)) {
            // Direct sitemap
            foreach ($sitemap->url as $url) {
                $urls[] = (string)$url->loc;
            }
        }

        return $urls;
    }

    private function fetch_sitemap(string $url): array {
        $response = wp_remote_get($url, ['timeout' => 10]);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return [];
        }

        $body = wp_remote_retrieve_body($response);
        libxml_use_internal_errors(true);
        $sitemap = simplexml_load_string($body);

        if ($sitemap === false || !isset($sitemap->url)) {
            return [];
        }

        $urls = [];
        foreach ($sitemap->url as $url) {
            $urls[] = (string)$url->loc;
        }

        return $urls;
    }

    private function get_menu_urls(): array {
        $urls = [home_url('/')];

        $locations = get_nav_menu_locations();
        foreach ($locations as $location => $menu_id) {
            if (!$menu_id) continue;

            $items = wp_get_nav_menu_items($menu_id);
            if (!$items) continue;

            foreach ($items as $item) {
                if (!empty($item->url)) {
                    $urls[] = $item->url;
                }
            }
        }

        return array_unique($urls);
    }

    private function generate_url_list(): array {
        $urls = [home_url('/')];

        // Get published posts
        $posts = get_posts([
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 100,
            'orderby' => 'date',
            'order' => 'DESC',
        ]);

        foreach ($posts as $post) {
            $urls[] = get_permalink($post->ID);
        }

        // Get pages
        $pages = get_pages(['number' => 50]);
        foreach ($pages as $page) {
            $urls[] = get_permalink($page->ID);
        }

        // Categories
        $categories = get_categories(['number' => 20]);
        foreach ($categories as $cat) {
            $urls[] = get_category_link($cat->term_id);
        }

        return array_unique($urls);
    }

    private function update_warmup_stats(): void {
        $stats_file = BLITZ_CACHE_CACHE_DIR . 'stats.json';
        $stats = json_decode(file_get_contents($stats_file), true) ?: [];
        $stats['last_warmup'] = time();
        file_put_contents($stats_file, wp_json_encode($stats));
    }
}
