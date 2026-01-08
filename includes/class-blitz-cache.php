<?php
class Blitz_Cache {
    protected Blitz_Cache_Loader $loader;
    protected string $plugin_name = 'blitz-cache';
    protected string $version;

    public function __construct() {
        $this->version = BLITZ_CACHE_VERSION;
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_cache_hooks();
        $this->load_integrations();
    }

    private function load_dependencies(): void {
        $this->loader = new Blitz_Cache_Loader();
    }

    private function set_locale(): void {
        $i18n = new Blitz_Cache_I18n();
        $this->loader->add_action('plugins_loaded', $i18n, 'load_plugin_textdomain');
    }

    private function define_admin_hooks(): void {
        if (!is_admin()) return;

        $admin = new Blitz_Cache_Admin($this->plugin_name, $this->version);
        $admin_bar = new Blitz_Cache_Admin_Bar();
        $dashboard = new Blitz_Cache_Dashboard_Widget();

        // Admin menu & pages
        $this->loader->add_action('admin_menu', $admin, 'add_admin_menu');
        $this->loader->add_action('admin_enqueue_scripts', $admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $admin, 'enqueue_scripts');

        // Admin bar
        $this->loader->add_action('admin_bar_menu', $admin_bar, 'add_menu', 100);

        // Dashboard widget
        $this->loader->add_action('wp_dashboard_setup', $dashboard, 'register_widget');

        // AJAX handlers
        $this->loader->add_action('wp_ajax_blitz_cache_purge_all', $admin, 'ajax_purge_all');
        $this->loader->add_action('wp_ajax_blitz_cache_purge_url', $admin, 'ajax_purge_url');
        $this->loader->add_action('wp_ajax_blitz_cache_warmup', $admin, 'ajax_warmup');
        $this->loader->add_action('wp_ajax_blitz_cache_save_settings', $admin, 'ajax_save_settings');
        $this->loader->add_action('wp_ajax_blitz_cache_test_cloudflare', $admin, 'ajax_test_cloudflare');
        $this->loader->add_action('wp_ajax_blitz_cache_save_cloudflare', $admin, 'ajax_save_cloudflare');

        // GitHub updater
        $updater = new Blitz_Cache_Updater();
        $this->loader->add_filter('pre_set_site_transient_update_plugins', $updater, 'check_update');
        $this->loader->add_filter('plugins_api', $updater, 'plugin_info', 10, 3);
    }

    private function define_public_hooks(): void {
        // Browser cache headers
        $this->loader->add_action('send_headers', [$this, 'send_browser_cache_headers']);
    }

    private function define_cache_hooks(): void {
        $cache = new Blitz_Cache_Cache();
        $purge = new Blitz_Cache_Purge();
        $warmup = new Blitz_Cache_Warmup();

        // Auto-purge on content changes
        $this->loader->add_action('save_post', $purge, 'on_post_save', 10, 2);
        $this->loader->add_action('delete_post', $purge, 'on_post_delete');
        $this->loader->add_action('switch_theme', $purge, 'purge_all');
        $this->loader->add_action('customize_save_after', $purge, 'purge_all');
        $this->loader->add_action('update_option_blogname', $purge, 'purge_all');
        $this->loader->add_action('update_option_blogdescription', $purge, 'purge_all');
        $this->loader->add_action('update_option_permalink_structure', $purge, 'purge_all');

        // Scheduled warmup
        $this->loader->add_action('blitz_cache_warmup_cron', $warmup, 'run');

        // Comment changes
        $this->loader->add_action('comment_post', $purge, 'on_comment_change');
        $this->loader->add_action('edit_comment', $purge, 'on_comment_change');
        $this->loader->add_action('delete_comment', $purge, 'on_comment_change');
        $this->loader->add_action('wp_set_comment_status', $purge, 'on_comment_change');
    }

    private function load_integrations(): void {
        // WooCommerce
        if (class_exists('WooCommerce')) {
            $woo = new Blitz_Cache_WooCommerce();
            $woo->init($this->loader);
        }

        // Easy Digital Downloads
        if (class_exists('Easy_Digital_Downloads')) {
            $edd = new Blitz_Cache_EDD();
            $edd->init($this->loader);
        }

        // LearnDash
        if (class_exists('SFWD_LMS')) {
            $ld = new Blitz_Cache_LearnDash();
            $ld->init($this->loader);
        }
    }

    public function send_browser_cache_headers(): void {
        $options = Blitz_Cache_Options::get();
        if (!$options['browser_cache_enabled']) return;

        // Handled via .htaccess for static files
        // This is for dynamic PHP-served content fallback
    }

    public function run(): void {
        $this->loader->run();
    }
}
