<?php
class Blitz_Cache_WooCommerce {
    public function init(Blitz_Cache_Loader $loader): void {
        // Add WooCommerce-specific exclusions
        add_filter('blitz_cache_should_cache', [$this, 'should_cache']);
        add_filter('blitz_cache_excluded_urls', [$this, 'excluded_urls']);

        // Smart purge on product/order changes
        $loader->add_action('woocommerce_update_product', $this, 'on_product_update');
        $loader->add_action('woocommerce_product_set_stock', $this, 'on_stock_change');
        $loader->add_action('woocommerce_variation_set_stock', $this, 'on_stock_change');
    }

    public function should_cache(bool $should_cache): bool {
        if (!$should_cache) {
            return false;
        }

        // Don't cache cart, checkout, account pages
        if (function_exists('is_cart') && is_cart()) {
            return false;
        }
        if (function_exists('is_checkout') && is_checkout()) {
            return false;
        }
        if (function_exists('is_account_page') && is_account_page()) {
            return false;
        }

        return true;
    }

    public function excluded_urls(array $urls): array {
        $woo_urls = [
            '/cart/*',
            '/checkout/*',
            '/my-account/*',
            '/*add-to-cart=*',
            '/*remove_item=*',
        ];

        return array_merge($urls, $woo_urls);
    }

    public function on_product_update(int $product_id): void {
        $purge = new Blitz_Cache_Purge();

        // Purge product page
        $purge->purge_url(get_permalink($product_id));

        // Purge shop page
        $shop_page_id = wc_get_page_id('shop');
        if ($shop_page_id) {
            $purge->purge_url(get_permalink($shop_page_id));
        }

        // Purge product categories
        $terms = get_the_terms($product_id, 'product_cat');
        if ($terms && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $purge->purge_url(get_term_link($term));
            }
        }
    }

    public function on_stock_change($product): void {
        $product_id = $product instanceof WC_Product ? $product->get_id() : $product;
        $this->on_product_update($product_id);
    }
}
