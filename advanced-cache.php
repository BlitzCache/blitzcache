<?php
/**
 * Blitz Cache - Advanced Cache Dropin
 *
 * This file is copied to wp-content/advanced-cache.php during activation.
 * It intercepts requests before WordPress fully loads.
 *
 * @package Blitz_Cache
 * @version 1.0.0
 */

// Identifier for Blitz Cache
define('BLITZ_CACHE_DROPIN', true);

// Abort if WP_CACHE is not enabled
if (!defined('WP_CACHE') || !WP_CACHE) {
    return;
}

// Get cache directory
$cache_dir = defined('WP_CONTENT_DIR')
    ? WP_CONTENT_DIR . '/cache/blitz-cache/'
    : dirname(__DIR__) . '/cache/blitz-cache/';

// Check if plugin is properly installed
if (!file_exists($cache_dir . 'meta.json')) {
    return;
}

// Get settings
$settings_option = 'blitz_cache_settings';
$settings = [];

// Try to read from object cache first (if available)
if (function_exists('wp_cache_get')) {
    $settings = wp_cache_get($settings_option, 'options');
}

// Fallback to direct DB read
if (empty($settings)) {
    // We can't use WordPress functions yet, so read directly
    // This is only used for the initial check - full settings loaded later
    $settings = [
        'page_cache_enabled' => true,
        'cache_logged_in' => false,
    ];
}

// Quick checks before attempting to serve cache
if (empty($settings['page_cache_enabled'])) {
    return;
}

// Don't cache CLI requests
if (php_sapi_name() === 'cli') {
    return;
}

// Only GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    return;
}

// Check for WordPress logged-in cookies
if (!$settings['cache_logged_in']) {
    foreach ($_COOKIE as $name => $value) {
        if (strpos($name, 'wordpress_logged_in_') === 0) {
            return;
        }
    }
}

// Check for WooCommerce cart cookies
$excluded_cookies = ['woocommerce_cart_hash', 'woocommerce_items_in_cart'];
foreach ($excluded_cookies as $cookie) {
    if (isset($_COOKIE[$cookie]) && $_COOKIE[$cookie]) {
        return;
    }
}

// Build cache key
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$cache_key = md5($url);

// Check for cached file
$cache_file = $cache_dir . 'pages/' . $cache_key . '.html';
$cache_file_gz = $cache_file . '.gz';

// Try to serve gzipped version
if (file_exists($cache_file_gz) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'] ?? '', 'gzip') !== false) {
    // Check expiry via meta
    $meta_file = $cache_dir . 'meta.json';
    if (file_exists($meta_file)) {
        $meta = json_decode(file_get_contents($meta_file), true);
        if (isset($meta[$cache_key]) && time() < $meta[$cache_key]['expires']) {
            header('Content-Type: text/html; charset=UTF-8');
            header('Content-Encoding: gzip');
            header('X-Blitz-Cache: HIT (gzip, dropin)');
            header('Vary: Accept-Encoding');
            readfile($cache_file_gz);
            exit;
        }
    }
}

// Try regular HTML
if (file_exists($cache_file)) {
    $meta_file = $cache_dir . 'meta.json';
    if (file_exists($meta_file)) {
        $meta = json_decode(file_get_contents($meta_file), true);
        if (isset($meta[$cache_key]) && time() < $meta[$cache_key]['expires']) {
            header('Content-Type: text/html; charset=UTF-8');
            header('X-Blitz-Cache: HIT (dropin)');
            readfile($cache_file);
            exit;
        }
    }
}

// No cache hit - continue loading WordPress
// The plugin will handle caching the response
