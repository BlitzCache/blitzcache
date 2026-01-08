<?php
class Blitz_Cache_Deactivator {
    public static function deactivate(): void {
        // Clear scheduled events
        wp_clear_scheduled_hook('blitz_cache_warmup_cron');

        // Remove advanced-cache.php dropin
        $dropin = WP_CONTENT_DIR . '/advanced-cache.php';
        if (file_exists($dropin)) {
            $content = file_get_contents($dropin);
            if (strpos($content, 'BLITZ_CACHE') !== false) {
                unlink($dropin);
            }
        }

        // Disable WP_CACHE (optional - leave enabled for other cache plugins)
        // self::disable_wp_cache();

        // Flush rewrite rules
        flush_rewrite_rules();
    }
}
