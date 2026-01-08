<?php
class Blitz_Cache_Dashboard_Widget {
    public function register_widget(): void {
        wp_add_dashboard_widget(
            'blitz_cache_dashboard_widget',
            __('Blitz Cache', 'blitz-cache'),
            [$this, 'render_widget']
        );
    }

    public function render_widget(): void {
        $cache = new Blitz_Cache_Cache();
        $stats = $cache->get_stats();

        $hits = $stats['hits'] ?? 0;
        $misses = $stats['misses'] ?? 0;
        $total_requests = $hits + $misses;
        $hit_ratio = $total_requests > 0 ? round(($hits / $total_requests) * 100, 1) : 0;

        echo '<div class="blitz-cache-widget">';

        // Cache Status
        $options = Blitz_Cache_Options::get();
        $is_enabled = !empty($options['page_cache_enabled']);
        echo '<p>';
        echo '<strong>' . esc_html__('Status:', 'blitz-cache') . '</strong> ';
        if ($is_enabled) {
            echo '<span style="color: green;">' . esc_html__('Active', 'blitz-cache') . '</span>';
        } else {
            echo '<span style="color: red;">' . esc_html__('Inactive', 'blitz-cache') . '</span>';
        }
        echo '</p>';

        // Quick Stats
        echo '<div class="blitz-cache-stats">';
        echo '<div class="blitz-cache-stat">';
        echo '<strong>' . esc_html__('Cached Pages:', 'blitz-cache') . '</strong> ' . esc_html($stats['cached_pages'] ?? 0);
        echo '</div>';

        echo '<div class="blitz-cache-stat">';
        echo '<strong>' . esc_html__('Hit Ratio:', 'blitz-cache') . '</strong> ' . esc_html($hit_ratio) . '%';
        echo '</div>';

        echo '<div class="blitz-cache-stat">';
        $size_mb = round(($stats['cache_size'] ?? 0) / 1024 / 1024, 2);
        echo '<strong>' . esc_html__('Cache Size:', 'blitz-cache') . '</strong> ' . esc_html($size_mb) . ' MB';
        echo '</div>';
        echo '</div>';

        // Quick Actions
        echo '<p>';
        echo '<button type="button" class="button button-primary" onclick="blitzCache.purgeAll()">' . esc_html__('Purge All', 'blitz-cache') . '</button>';
        echo ' <button type="button" class="button" onclick="blitzCache.warmup()">' . esc_html__('Warm Cache', 'blitz-cache') . '</button>';
        echo '</p>';

        // Cloudflare Status
        $cf_options = Blitz_Cache_Options::get_cloudflare();
        if (!empty($cf_options['connection_status'])) {
            echo '<p>';
            echo '<strong>' . esc_html__('Cloudflare:', 'blitz-cache') . '</strong> ';
            if ($cf_options['connection_status'] === 'connected') {
                echo '<span style="color: green;">' . esc_html__('Connected', 'blitz-cache') . '</span>';
            } elseif ($cf_options['connection_status'] === 'error') {
                echo '<span style="color: red;">' . esc_html__('Error', 'blitz-cache') . '</span>';
            } else {
                echo '<span style="color: gray;">' . esc_html__('Disconnected', 'blitz-cache') . '</span>';
            }
            echo '</p>';
        }

        // View Settings Link
        echo '<p>';
        echo '<a href="' . esc_url(admin_url('admin.php?page=blitz-cache')) . '">' . esc_html__('View Settings', 'blitz-cache') . '</a>';
        echo '</p>';

        echo '</div>';
    }
}
