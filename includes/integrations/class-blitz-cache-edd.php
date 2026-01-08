<?php
class Blitz_Cache_EDD {
    public function init(Blitz_Cache_Loader $loader): void {
        add_filter('blitz_cache_should_cache', [$this, 'should_cache']);
        add_filter('blitz_cache_excluded_urls', [$this, 'excluded_urls']);

        $loader->add_action('edd_save_download', $this, 'on_download_update');
    }

    public function should_cache(bool $should_cache): bool {
        if (!$should_cache) return false;

        if (function_exists('edd_is_checkout') && edd_is_checkout()) {
            return false;
        }

        return true;
    }

    public function excluded_urls(array $urls): array {
        $edd_urls = [
            '/checkout/*',
            '/purchase-history/*',
            '/purchase-confirmation/*',
            '/*edd_action=*',
        ];

        return array_merge($urls, $edd_urls);
    }

    public function on_download_update(int $download_id): void {
        $purge = new Blitz_Cache_Purge();
        $purge->purge_url(get_permalink($download_id));

        // Purge archive
        $archive = get_post_type_archive_link('download');
        if ($archive) {
            $purge->purge_url($archive);
        }
    }
}
