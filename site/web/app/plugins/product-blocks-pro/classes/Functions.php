<?php
namespace WOPB_PRO;

defined('ABSPATH') || exit;

class Functions{

    // is Free Plugin Ready
    public function is_wopb_free_ready() {
        $activate = get_option('active_plugins', array());
        if (is_multisite()) {
            $active_plugins = array_merge($active_plugins, array_keys(get_site_option( 'active_sitewide_plugins', array() )));
        }
        if (file_exists(WP_PLUGIN_DIR.'/product-blocks/product-blocks.php') && in_array('product-blocks/product-blocks.php', $activate) ) {
            return true;
        } else {
            return false;
        }
        return true;
    }

    public function is_simple_preorder() {
        $settings = wopb_function()->get_setting();
        if (isset($settings['wopb_preorder']) && $settings['wopb_preorder'] == 'true') {
            global $product;
            $type = $product->get_type();
            if ($type == 'simple' && !wopb_pro_function()->is_preorder_closed($product) && $product->get_meta('_wopb_preorder_simple')) {
                return true;
            }
        }
        return false;
    }

    public function is_simple_backorder() {
        $settings = wopb_function()->get_setting();
        if (isset($settings['wopb_backorder']) && $settings['wopb_backorder'] == 'true') {
            global $product;
            $type = $product->get_type();
            if ($type == 'simple' && !$product->managing_stock() && $product->is_on_backorder() && !wopb_pro_function()->is_backorder_closed($product)) {
                return  true;
            }
        }
        return false;
    }

    /**
     * Preorder Close Check
     *
     * @return BOOLEAN
     * @since v.2.1.9
     */
    public function is_preorder_closed($product)
    {
        $preorder_available_duration = date('Y-m-d h:i a', strtotime($product->get_meta('_wopb_preorder_date')));
        if ($product->get_meta('_wopb_preorder_date') && current_time('Y-m-d h:i') > $preorder_available_duration) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Backorder Close Check
     *
     * @return BOOLEAN
     * @since v.2.1.9
     */
    public function is_backorder_closed($product)
    {
        $backorder_available_duration = date('Y-m-d h:i a', strtotime($product->get_meta('_wopb_backorder_date')));
        if ($product->get_meta('_wopb_backorder_date') && current_time('Y-m-d h:i') > $backorder_available_duration) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check Product Partial Payment OR Not
     *
     * @return NUMBER
     * @since v.1.0.8
     */
    public function is_partial_payment($product) {
        $settings = wopb_function()->get_setting();
        if(isset($settings['wopb_partial_payment']) && $settings['wopb_partial_payment'] == 'true' && $product->get_meta('_wopb_partial_payment_enable')) {
            return true;
        }else {
            return false;
        }
    }
}