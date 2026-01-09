<?php
class Blitz_Cache_Activator {
    public static function activate(): void {
        // Check requirements
        self::check_requirements();

        // Create cache directory
        self::create_cache_directory();

        // Install advanced-cache.php dropin
        self::install_dropin();

        // Set default options
        self::set_default_options();

        // Enable WP_CACHE constant
        self::enable_wp_cache();

        // Schedule warmup cron
        self::schedule_cron();

        // Trigger activation hook
        do_action('blitz_cache_activated');

        // Flush rewrite rules
        flush_rewrite_rules();
    }

    private static function check_requirements(): void {
        global $wp_version;

        if (version_compare(PHP_VERSION, BLITZ_CACHE_MIN_PHP, '<')) {
            deactivate_plugins(BLITZ_CACHE_PLUGIN_BASENAME);
            wp_die(sprintf(
                __('Blitz Cache requires PHP %s or higher. You are running PHP %s.', 'blitz-cache'),
                BLITZ_CACHE_MIN_PHP,
                PHP_VERSION
            ));
        }

        if (version_compare($wp_version, BLITZ_CACHE_MIN_WP, '<')) {
            deactivate_plugins(BLITZ_CACHE_PLUGIN_BASENAME);
            wp_die(sprintf(
                __('Blitz Cache requires WordPress %s or higher.', 'blitz-cache'),
                BLITZ_CACHE_MIN_WP
            ));
        }
    }

    private static function create_cache_directory(): void {
        $dirs = [
            BLITZ_CACHE_CACHE_DIR,
            BLITZ_CACHE_CACHE_DIR . 'pages/',
        ];

        foreach ($dirs as $dir) {
            if (!file_exists($dir)) {
                wp_mkdir_p($dir);
            }
        }

        // Security files
        $htaccess = BLITZ_CACHE_CACHE_DIR . '.htaccess';
        if (!file_exists($htaccess)) {
            $rules = <<<HTACCESS
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !\.html$
    RewriteCond %{REQUEST_FILENAME} !\.html\.gz$
    RewriteRule . - [F,L]
</IfModule>

<IfModule mod_authz_core.c>
    <FilesMatch "\.(json|php)$">
        Require all denied
    </FilesMatch>
</IfModule>
HTACCESS;
            file_put_contents($htaccess, $rules);
        }

        $index = BLITZ_CACHE_CACHE_DIR . 'index.php';
        if (!file_exists($index)) {
            file_put_contents($index, '<?php // Silence is golden');
        }

        // Initialize meta and stats files
        if (!file_exists(BLITZ_CACHE_CACHE_DIR . 'meta.json')) {
            file_put_contents(BLITZ_CACHE_CACHE_DIR . 'meta.json', '{}');
        }
        if (!file_exists(BLITZ_CACHE_CACHE_DIR . 'stats.json')) {
            file_put_contents(BLITZ_CACHE_CACHE_DIR . 'stats.json', json_encode([
                'hits' => 0,
                'misses' => 0,
                'cached_pages' => 0,
                'cache_size' => 0,
                'last_warmup' => 0,
                'last_purge' => 0,
                'period_start' => time(),
            ]));
        }
    }

    private static function install_dropin(): void {
        $source = BLITZ_CACHE_PLUGIN_DIR . 'advanced-cache.php';
        $dest = WP_CONTENT_DIR . '/advanced-cache.php';

        // Backup existing if not ours
        if (file_exists($dest)) {
            $content = file_get_contents($dest);
            if (strpos($content, 'BLITZ_CACHE') === false) {
                rename($dest, $dest . '.backup.' . time());
            }
        }

        copy($source, $dest);
    }

    private static function set_default_options(): void {
        $defaults = [
            'page_cache_enabled' => true,
            'page_cache_ttl' => 86400,
            'cache_logged_in' => false,
            'mobile_cache' => false,
            'browser_cache_enabled' => true,
            'css_js_ttl' => 2592000,
            'images_ttl' => 7776000,
            'gzip_enabled' => true,
            'html_minify_enabled' => true,
            'excluded_urls' => [],
            'excluded_cookies' => ['wordpress_logged_in_*', 'woocommerce_cart_hash', 'woocommerce_items_in_cart'],
            'excluded_user_agents' => [],
            'warmup_enabled' => true,
            'warmup_source' => 'sitemap',
            'warmup_interval' => 21600,
            'warmup_batch_size' => 5,
            'update_channel' => 'stable',
        ];

        if (!get_option('blitz_cache_settings')) {
            add_option('blitz_cache_settings', $defaults, '', 'no');
        }

        if (!get_option('blitz_cache_cloudflare')) {
            add_option('blitz_cache_cloudflare', [
                'api_token' => '',
                'zone_id' => '',
                'email' => '',
                'connection_status' => 'disconnected',
                'last_purge' => 0,
                'workers_enabled' => false,
                'workers_route' => '',
            ], '', 'no');
        }
    }

    private static function enable_wp_cache(): void {
        $config_file = ABSPATH . 'wp-config.php';

        if (!is_writable($config_file)) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-warning"><p>';
                echo esc_html__('Blitz Cache: Please add define(\'WP_CACHE\', true); to your wp-config.php', 'blitz-cache');
                echo '</p></div>';
            });
            return;
        }

        $config = file_get_contents($config_file);

        if (strpos($config, 'WP_CACHE') !== false) {
            // Already defined, try to set to true
            $config = preg_replace(
                "/define\s*\(\s*['\"]WP_CACHE['\"]\s*,\s*false\s*\)/",
                "define('WP_CACHE', true)",
                $config
            );
        } else {
            // Add after opening PHP tag
            $config = preg_replace(
                '/^<\?php/',
                "<?php\ndefine('WP_CACHE', true); // Added by Blitz Cache",
                $config
            );
        }

        file_put_contents($config_file, $config);
    }

    private static function schedule_cron(): void {
        if (!wp_next_scheduled('blitz_cache_warmup_cron')) {
            $options = get_option('blitz_cache_settings', []);
            $interval = $options['warmup_interval'] ?? 21600;
            wp_schedule_event(time(), 'blitz_cache_warmup', 'blitz_cache_warmup_cron');
        }

        // Register custom interval
        add_filter('cron_schedules', function($schedules) {
            $options = get_option('blitz_cache_settings', []);
            $interval = $options['warmup_interval'] ?? 21600;
            $schedules['blitz_cache_warmup'] = [
                'interval' => $interval,
                'display' => __('Blitz Cache Warmup Interval', 'blitz-cache'),
            ];
            return $schedules;
        });
    }
}
