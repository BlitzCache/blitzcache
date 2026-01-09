<?php
class Blitz_Cache_Cache {
    private string $cache_dir;
    private array $options;

    public function __construct() {
        $this->cache_dir = BLITZ_CACHE_CACHE_DIR . 'pages/';
        $this->options = Blitz_Cache_Options::get();
    }

    public function should_cache(): bool {
        // Filter hook for external control
        $should_cache = apply_filters('blitz_cache_should_cache', true);
        if (!$should_cache) {
            return false;
        }

        // Check if caching enabled
        if (empty($this->options['page_cache_enabled'])) {
            return false;
        }

        // Only cache GET requests
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            return false;
        }

        // Don't cache logged-in users (unless enabled)
        if (is_user_logged_in() && empty($this->options['cache_logged_in'])) {
            return false;
        }

        // Check for excluded cookies
        foreach ($this->options['excluded_cookies'] as $pattern) {
            foreach ($_COOKIE as $name => $value) {
                if (fnmatch($pattern, $name)) {
                    return false;
                }
            }
        }

        // Check for excluded URLs
        $current_url = $this->get_current_url();
        foreach ($this->options['excluded_urls'] as $pattern) {
            if (fnmatch($pattern, $current_url) || strpos($current_url, $pattern) !== false) {
                return false;
            }
        }

        // Check for excluded user agents
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        foreach ($this->options['excluded_user_agents'] as $pattern) {
            if (fnmatch($pattern, $user_agent)) {
                return false;
            }
        }

        // Don't cache POST data responses
        if (!empty($_POST)) {
            return false;
        }

        // Don't cache if there's a query string (unless it's allowed)
        if (!empty($_GET) && !apply_filters('blitz_cache_cache_query_strings', false)) {
            // Allow specific query params
            $allowed = apply_filters('blitz_cache_allowed_query_params', ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'fbclid', 'gclid']);
            $current_params = array_keys($_GET);
            $disallowed = array_diff($current_params, $allowed);
            if (!empty($disallowed)) {
                return false;
            }
        }

        return true;
    }

    public function get_cache_key(): string {
        $url = $this->get_current_url();

        // Optionally include mobile in cache key
        if (!empty($this->options['mobile_cache']) && wp_is_mobile()) {
            $url .= '|mobile';
        }

        return md5($url);
    }

    public function get_cached(string $key): ?string {
        $file = $this->cache_dir . $key . '.html';
        $file_gz = $file . '.gz';

        // Check if gzip version exists and client accepts
        if ($this->options['gzip_enabled'] &&
            file_exists($file_gz) &&
            strpos($_SERVER['HTTP_ACCEPT_ENCODING'] ?? '', 'gzip') !== false) {

            $meta = $this->get_meta($key);
            if ($meta && time() < $meta['expires']) {
                $this->record_hit();
                header('Content-Encoding: gzip');
                header('X-Blitz-Cache: HIT (gzip)');
                return file_get_contents($file_gz);
            }
        }

        // Fallback to regular HTML
        if (file_exists($file)) {
            $meta = $this->get_meta($key);
            if ($meta && time() < $meta['expires']) {
                $this->record_hit();
                header('X-Blitz-Cache: HIT');
                return file_get_contents($file);
            }
        }

        $this->record_miss();
        return null;
    }

    public function store(string $key, string $html): void {
        // Apply filters before storing
        $html = apply_filters('blitz_cache_html_before_store', $html);

        // Minify if enabled
        if (!empty($this->options['html_minify_enabled'])) {
            $minifier = new Blitz_Cache_Minify();
            $html = $minifier->minify($html);
        }

        // Add cache signature
        $html .= "\n<!-- Cached by Blitz Cache on " . gmdate('Y-m-d H:i:s') . " UTC -->";

        $file = $this->cache_dir . $key . '.html';

        // Store regular HTML
        file_put_contents($file, $html);

        // Store gzipped version
        if (!empty($this->options['gzip_enabled'])) {
            file_put_contents($file . '.gz', gzencode($html, 9));
        }

        // Update meta
        $this->set_meta($key, [
            'url' => $this->get_current_url(),
            'file' => $key . '.html',
            'created' => time(),
            'expires' => time() + ($this->options['page_cache_ttl'] ?? 86400),
            'mobile' => wp_is_mobile() && !empty($this->options['mobile_cache']),
        ]);

        // Update stats
        $this->update_cache_stats();

        // Action hook
        do_action('blitz_cache_after_store', $key, $html);
    }

    public function delete(string $key): void {
        $file = $this->cache_dir . $key . '.html';

        if (file_exists($file)) {
            unlink($file);
        }
        if (file_exists($file . '.gz')) {
            unlink($file . '.gz');
        }

        $this->delete_meta($key);
    }

    public function purge_url(string $url): void {
        $key = md5($url);
        $this->delete($key);

        // Also delete mobile version
        $this->delete(md5($url . '|mobile'));

        do_action('blitz_cache_after_purge_url', $url);
    }

    public function purge_all(): void {
        $files = glob($this->cache_dir . '*.html*');
        foreach ($files as $file) {
            unlink($file);
        }

        // Reset meta
        file_put_contents(BLITZ_CACHE_CACHE_DIR . 'meta.json', '{}');

        // Update stats
        $this->update_cache_stats();

        do_action('blitz_cache_after_purge');
    }

    private function get_meta(string $key): ?array {
        $meta_file = BLITZ_CACHE_CACHE_DIR . 'meta.json';
        if (!file_exists($meta_file)) {
            return null;
        }

        $meta = json_decode(file_get_contents($meta_file), true);
        return $meta[$key] ?? null;
    }

    private function set_meta(string $key, array $data): void {
        $meta_file = BLITZ_CACHE_CACHE_DIR . 'meta.json';
        $meta = [];

        if (file_exists($meta_file)) {
            $meta = json_decode(file_get_contents($meta_file), true) ?: [];
        }

        $meta[$key] = $data;
        file_put_contents($meta_file, wp_json_encode($meta, JSON_PRETTY_PRINT));
    }

    private function delete_meta(string $key): void {
        $meta_file = BLITZ_CACHE_CACHE_DIR . 'meta.json';

        if (!file_exists($meta_file)) {
            return;
        }

        $meta = json_decode(file_get_contents($meta_file), true) ?: [];
        unset($meta[$key]);
        file_put_contents($meta_file, wp_json_encode($meta, JSON_PRETTY_PRINT));
    }

    private function get_current_url(): string {
        $protocol = is_ssl() ? 'https://' : 'http://';
        return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    private function record_hit(): void {
        $stats_file = BLITZ_CACHE_CACHE_DIR . 'stats.json';
        $stats = json_decode(file_get_contents($stats_file), true) ?: [];
        $stats['hits'] = ($stats['hits'] ?? 0) + 1;
        file_put_contents($stats_file, wp_json_encode($stats));

        do_action('blitz_cache_hit');
    }

    private function record_miss(): void {
        $stats_file = BLITZ_CACHE_CACHE_DIR . 'stats.json';
        $stats = json_decode(file_get_contents($stats_file), true) ?: [];
        $stats['misses'] = ($stats['misses'] ?? 0) + 1;
        file_put_contents($stats_file, wp_json_encode($stats));

        do_action('blitz_cache_miss');
    }

    private function update_cache_stats(): void {
        $files = glob($this->cache_dir . '*.html');
        $size = 0;
        foreach ($files as $file) {
            $size += filesize($file);
        }

        $stats_file = BLITZ_CACHE_CACHE_DIR . 'stats.json';
        $stats = json_decode(file_get_contents($stats_file), true) ?: [];
        $stats['cached_pages'] = count($files);
        $stats['cache_size'] = $size;
        file_put_contents($stats_file, wp_json_encode($stats));
    }

    public function get_stats(): array {
        $stats_file = BLITZ_CACHE_CACHE_DIR . 'stats.json';
        if (!file_exists($stats_file)) {
            return [
                'hits' => 0,
                'misses' => 0,
                'cached_pages' => 0,
                'cache_size' => 0,
                'last_warmup' => 0,
                'last_purge' => 0,
                'period_start' => time(),
            ];
        }
        return json_decode(file_get_contents($stats_file), true);
    }
}
