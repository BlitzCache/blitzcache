<?php
class Blitz_Cache_Admin_Bar {
    public function add_menu(\WP_Admin_Bar $wp_admin_bar): void {
        // Only show for users who can edit posts
        if (!current_user_can('edit_posts')) {
            return;
        }

        // Add main menu item
        $wp_admin_bar->add_menu([
            'id' => 'blitz-cache',
            'title' => '<span class="dashicons dashicons-performance" style="font-family: dashicons"></span> Blitz Cache',
            'href' => admin_url('admin.php?page=blitz-cache'),
        ]);

        // Add purge this page option
        $current_url = $this->get_current_url();
        $wp_admin_bar->add_menu([
            'id' => 'blitz-cache-purge-page',
            'parent' => 'blitz-cache',
            'title' => __('Purge This Page', 'blitz-cache'),
            'href' => '#',
            'meta' => [
                'onclick' => 'blitzCache.purgeUrl("' . esc_js($current_url) . '"); return false;',
            ],
        ]);

        // Add purge all option
        $wp_admin_bar->add_menu([
            'id' => 'blitz-cache-purge-all',
            'parent' => 'blitz-cache',
            'title' => __('Purge All Cache', 'blitz-cache'),
            'href' => '#',
            'meta' => [
                'onclick' => 'blitzCache.purgeAll(); return false;',
            ],
        ]);
    }

    private function get_current_url(): string {
        $protocol = is_ssl() ? 'https://' : 'http://';
        return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
}
