<?php
class Blitz_Cache_Purge {
    private Blitz_Cache_Cache $cache;
    private ?Blitz_Cache_Cloudflare $cloudflare = null;

    public function __construct() {
        $this->cache = new Blitz_Cache_Cache();

        $cf_options = Blitz_Cache_Options::get_cloudflare();
        if (!empty($cf_options['api_token']) && $cf_options['connection_status'] === 'connected') {
            $this->cloudflare = new Blitz_Cache_Cloudflare();
        }
    }

    public function on_post_save(int $post_id, \WP_Post $post): void {
        // Don't purge on autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Don't purge revisions
        if (wp_is_post_revision($post_id)) {
            return;
        }

        // Only published posts
        if ($post->post_status !== 'publish') {
            return;
        }

        $urls_to_purge = $this->get_related_urls($post_id, $post);
        $urls_to_purge = apply_filters('blitz_cache_purge_urls', $urls_to_purge, $post_id, $post);

        foreach ($urls_to_purge as $url) {
            $this->cache->purge_url($url);
        }

        // Cloudflare purge
        if ($this->cloudflare) {
            $this->cloudflare->purge_urls($urls_to_purge);
        }

        $this->update_purge_stats();
    }

    public function on_post_delete(int $post_id): void {
        $post = get_post($post_id);
        if (!$post) return;

        $urls_to_purge = $this->get_related_urls($post_id, $post);

        foreach ($urls_to_purge as $url) {
            $this->cache->purge_url($url);
        }

        if ($this->cloudflare) {
            $this->cloudflare->purge_urls($urls_to_purge);
        }
    }

    public function on_comment_change(int $comment_id): void {
        $comment = get_comment($comment_id);
        if (!$comment) return;

        $post_url = get_permalink($comment->comment_post_ID);
        if ($post_url) {
            $this->cache->purge_url($post_url);

            if ($this->cloudflare) {
                $this->cloudflare->purge_urls([$post_url]);
            }
        }
    }

    public function purge_all(): void {
        $this->cache->purge_all();

        if ($this->cloudflare) {
            $this->cloudflare->purge_all();
        }

        $this->update_purge_stats();

        do_action('blitz_cache_after_purge');
    }

    public function purge_url(string $url): void {
        $this->cache->purge_url($url);

        if ($this->cloudflare) {
            $this->cloudflare->purge_urls([$url]);
        }
    }

    private function get_related_urls(int $post_id, \WP_Post $post): array {
        $urls = [];

        // The post itself
        $permalink = get_permalink($post_id);
        if ($permalink) {
            $urls[] = $permalink;
        }

        // Home page
        $urls[] = home_url('/');

        // Blog page (if different from home)
        $blog_page_id = get_option('page_for_posts');
        if ($blog_page_id) {
            $urls[] = get_permalink($blog_page_id);
        }

        // Post type archive
        $post_type = get_post_type($post_id);
        $archive_link = get_post_type_archive_link($post_type);
        if ($archive_link) {
            $urls[] = $archive_link;
        }

        // Category archives
        $categories = get_the_category($post_id);
        foreach ($categories as $cat) {
            $urls[] = get_category_link($cat->term_id);
        }

        // Tag archives
        $tags = get_the_tags($post_id);
        if ($tags) {
            foreach ($tags as $tag) {
                $urls[] = get_tag_link($tag->term_id);
            }
        }

        // Author archive
        $urls[] = get_author_posts_url($post->post_author);

        // Date archives
        $urls[] = get_year_link(get_the_date('Y', $post_id));
        $urls[] = get_month_link(get_the_date('Y', $post_id), get_the_date('m', $post_id));
        $urls[] = get_day_link(get_the_date('Y', $post_id), get_the_date('m', $post_id), get_the_date('d', $post_id));

        // Feed URLs
        $urls[] = get_feed_link();
        $urls[] = get_feed_link('rdf');
        $urls[] = get_feed_link('atom');

        return array_unique(array_filter($urls));
    }

    private function update_purge_stats(): void {
        $stats_file = BLITZ_CACHE_CACHE_DIR . 'stats.json';
        $stats = json_decode(file_get_contents($stats_file), true) ?: [];
        $stats['last_purge'] = time();
        file_put_contents($stats_file, wp_json_encode($stats));
    }
}
