<?php
/**
 * Stock Progress Bar Addons Core.
 * 
 * @package WOPB_PRO\Stock Progress Bar
 * @since v.1.0.5
 */

namespace WOPB_PRO;

defined('ABSPATH') || exit;

/**
 * Stock Progress Bar class.
 */
class StockProgressBar {

    /**
	 * Setup class.
	 *
	 * @since v.1.0.5
	 */
    public function __construct(){
        add_action('wp_enqueue_scripts', array($this, 'add_stock_progress_bar_scripts'));

        //show stock progress bar after in stock text in product details page
        add_filter( 'woocommerce_get_stock_html', [$this ,'wopb_woocommerce_get_stock_html'], 10, 2);
    }

    /**
	 * Stock Progress Bar JS Script Add
     * 
     * @since v.1.0.5
	 * @return NULL
	 */
    public function add_stock_progress_bar_scripts() {
        wp_enqueue_style('wopb-stock-progress-bar-style', WOPB_PRO_URL.'addons/stock_progress_bar/css/stock_progress_bar.css', array(), WOPB_PRO_VER);
        wp_enqueue_script('wopb-stock-progress-bar', WOPB_PRO_URL.'addons/stock_progress_bar/js/stock_progress_bar.js', array('jquery'), WOPB_PRO_VER, true);
    }

    /**
	 * Stock Progress Bar Addons Initial Setup Action
     * 
     * @since v.1.0.5
	 * @return NULL
	 */
    public function initial_setup(){
        // Set Default Value
        $initial_data = array(
            'stock_progress_bar_heading' => 'yes',
            'total_sell_count_text'      => 'Total Sold',
            'available_item_count_text'  => 'Available Item',
        );
        foreach ($initial_data as $key => $val) {
            wopb_function()->set_setting($key, $val);
        }
    }

    public function wopb_woocommerce_get_stock_html( $html, $product ){
        if(is_product() && $product->managing_stock()){
            if($product->get_parent_id()){
                $total_order_count = $this->total_order_count($product->get_parent_id(), $product->get_id());
            }else{
                $total_order_count = $this->total_order_count($product->get_id());
            }
            $available_stock = get_post_meta( $product->get_id(), '_stock', true );

            $total_stock_count = ($total_order_count ? $total_order_count : 0) + ($available_stock ? $available_stock : 0);
            $progress_bar_active_limit = $total_order_count ? round(($total_order_count * 100) / $total_stock_count) : 0;
            $html .= "<div class='wopb-stock-progress-bar-section'>";
                $html .= "<div class='wopb-stock-progress-title'>";
                    $html .= "<span class='wopb-stock-progress-sold-title'>" .__(wopb_function()->get_setting('total_sell_count_text')) .": <span class='wopb-stock-progress-count'>{$total_order_count}</span></span>";
                    $html .= "<span class='wopb-stock-progress-available-title'> " .__(wopb_function()->get_setting('available_item_count_text')) .": <span class='wopb-stock-progress-count'>{$available_stock}</span></span>";
                $html .= "</div>";
                $html .= "<div class='wopb-stock-progress-bar'>";
                    $html .= "<div class='wopb-stock-progress' data-order-progress = '{$progress_bar_active_limit}'></div>";
                $html .= "</div>";
            $html .= "</div>";
        }
        return $html;
    }

    public function total_order_count($product_id, $variation_id = null) {
        global $wpdb;
        $variation_statement = $variation_id ? " AND order_product.variation_id = ".intval($variation_id) : "";
        $result = $wpdb->get_results("
            SELECT sum(order_product.product_qty) as total_order_count
            FROM {$wpdb->prefix}wc_order_product_lookup as order_product
            INNER JOIN {$wpdb->prefix}wc_order_stats AS order_stat
                ON order_product.order_id = order_stat.order_id
            WHERE order_product.product_id = {$product_id} {$variation_statement}
                AND order_stat.status NOT IN ('wc-cancelled', 'wc-refunded')
        ");

        return $result[0]->total_order_count ?? 0;
    }
}