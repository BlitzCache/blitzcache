<?php
class Blitz_Cache_Options {
    private static ?array $settings = null;
    private static ?array $cloudflare = null;

    public static function get(string $key = ''): mixed {
        if (self::$settings === null) {
            self::$settings = get_option('blitz_cache_settings', []);
        }

        if ($key === '') {
            return self::$settings;
        }

        return self::$settings[$key] ?? null;
    }

    public static function set(array $settings): bool {
        self::$settings = array_merge(self::$settings ?? [], $settings);
        return update_option('blitz_cache_settings', self::$settings);
    }

    public static function get_cloudflare(string $key = ''): mixed {
        if (self::$cloudflare === null) {
            self::$cloudflare = get_option('blitz_cache_cloudflare', []);

            // Decrypt token
            if (!empty(self::$cloudflare['api_token'])) {
                self::$cloudflare['api_token'] = self::decrypt(self::$cloudflare['api_token']);
            }
        }

        if ($key === '') {
            return self::$cloudflare;
        }

        return self::$cloudflare[$key] ?? null;
    }

    public static function set_cloudflare(array $settings): bool {
        // Encrypt token before saving
        if (!empty($settings['api_token'])) {
            $settings['api_token'] = self::encrypt($settings['api_token']);
        }

        self::$cloudflare = array_merge(self::$cloudflare ?? [], $settings);
        return update_option('blitz_cache_cloudflare', self::$cloudflare);
    }

    private static function encrypt(string $data): string {
        if (!function_exists('openssl_encrypt')) {
            return base64_encode($data);
        }

        $key = wp_salt('auth');
        $iv = openssl_random_pseudo_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);

        return base64_encode($iv . $encrypted);
    }

    private static function decrypt(string $data): string {
        if (!function_exists('openssl_decrypt')) {
            return base64_decode($data);
        }

        $key = wp_salt('auth');
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);

        return openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv) ?: '';
    }

    public static function get_defaults(): array {
        return [
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
    }

    public static function reset(): void {
        self::$settings = self::get_defaults();
        update_option('blitz_cache_settings', self::$settings);
    }
}
