<?php
/**
 * Backorder Addons Core.
 *
 * @package WOPB_PRO\Backorder
 * @since v.1.0.7
 */

namespace WOPB_PRO;

use WC_Order_Item_Product;

defined('ABSPATH') || exit;

/**
 * Backorder class.
 */
class Backorder
{
    /**
     * Setup class.
     *
     * @since v.1.0.7
     */

    public function __construct()
    {
        // Add Script for Addons
        add_action('wp_enqueue_scripts', [$this, 'add_backorder_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'add_backorder_scripts']);

        //Add Custom Field to Inventory Tab
        add_action( 'woocommerce_product_options_inventory_product_data', [$this, 'product_options_inventory_product_data'], 10, 2 );
        //Simple Product Data Save
        add_action('woocommerce_process_product_meta', [$this, 'process_product_meta'], 10, 2);

        //Add Custom Field in Variable Product
        add_action('woocommerce_product_after_variable_attributes', [$this, 'product_after_variable_attributes'], 10, 3);
        //Variable Product Data Save
        add_action('woocommerce_save_product_variation', [$this, 'save_product_variation'], 10, 2);

        //Add to Cart button text
        add_filter('woocommerce_product_single_add_to_cart_text', [$this, 'add_to_cart_button_text']);
        add_filter('woocommerce_product_add_to_cart_text', [$this, 'add_to_cart_button_text']);

        //Get Stock HTML
        add_filter( 'woocommerce_get_stock_html', [$this ,'get_stock_html'], 10, 2);

        //Validate Item When Add to Cart
        add_filter('woocommerce_add_to_cart', [$this, 'add_to_cart_validation'], 10, 6);
        add_filter( 'woocommerce_update_cart_validation', [$this ,'update_cart_validation'], 10, 4 );

        //Add to Cart Item Name
        add_filter('woocommerce_cart_item_name', [$this, 'cart_item_name'], 10, 4);

        // Checkout Order Line Meta Modify
        add_filter('woocommerce_checkout_create_order_line_item', [$this, 'checkout_create_order_line_item'], 10, 4);

        // Order Item Formatted Meta Data
        add_filter('woocommerce_order_item_get_formatted_meta_data', [$this, 'order_item_get_formatted_meta_data'], 10, 4);

        //add column to order table in admin panel
        add_filter('manage_edit-shop_order_columns', [$this, 'add_column_in_order_listing_page'], 10, 1);
        add_action('manage_shop_order_posts_custom_column', [$this, 'set_order_type_column_value'], 10, 2);
    }

    /**
	 * Backorder JS & CSS Script
     * 
     * @since v.1.0.7
	 * @return NULL
	 */
    public function add_backorder_scripts() {
        wp_enqueue_style('wopb-backorder-style', WOPB_PRO_URL . 'addons/backorder/css/backorder.css', array(), WOPB_PRO_VER);
        wp_enqueue_script('wopb-backorder-script', WOPB_PRO_URL . 'addons/backorder/js/backorder.js', array('jquery'), WOPB_PRO_VER, true);
    }

    /**
     * backorder Addons Initial Setup Action
     *
     * @return NULL
     * @since v.1.0.7
     */
    public function initial_setup()
    {
        $initial_data = array(
            'backorder_heading' => 'yes',
            'backorder_button_text' => __('Backorder', 'product-blocks-pro'),
            'backorder_add_to_cart_button_text' => __('Backorder Now', 'product-blocks-pro'),
            'backorder_message_text' => 'Available On',
        );
        foreach ($initial_data as $key => $val) {
            wopb_function()->set_setting($key, $val);
        }
    }

    /**
     * Backorder Custom Field to Inventory Tab
     *
     * @return HTML
     * @since v.1.0.7
     */
    public function product_options_inventory_product_data(){
        global $post, $product_object;
        if (wopb_function()->is_lc_active() && $product_object->is_type( 'simple' )) {
            $html = $this->generate_field($product_object);
            echo $html;
        }
    }

    /**
     * Backorder Custom Field to Variable Product
     *
     * @return HTML
     * @since v.1.0.7
     */
    public function product_after_variable_attributes($loop, $variation_data, $variation)
    {
        if (wopb_function()->is_lc_active()) {
            $product = wc_get_product($variation->ID);
            $html = $this->generate_field($product, $loop);
            echo $html;
        }
    }

    /**
     * Generate Custom Field
     *
     * @return HTML
     * @since v.1.0.7
     */
    public function generate_field($product, $loop = '') {
        $html = '';
        $loop = $loop !== '' ? '['.$loop.']' : '';

        $backorder_message = $product->get_meta('_wopb_backorder_message');
        $default_backorder_message = wopb_function()->get_setting('backorder_message_text');

        $html .= '<div class="wopb-backorder-field-group">';
            $html .= '<h4 class="wopb-backorder-title">' . __('ProductX Backorder Information', 'product-blocks-pro') . '</h4>';

            ob_start();
            woocommerce_wp_text_input([
                'id' => '_wopb_max_backorder'.$loop,
                'class' => 'wopb-required w-60',
                'label' => __('Available Quantity', 'product-blocks-pro'),
                'type' => 'number',
                'value' => $product->get_meta('_wopb_max_backorder'),
                'desc_tip' => true,
                'description' => __("Enter the maximum amount of products available for backorder", 'product-blocks-pro')
            ]);

            woocommerce_wp_text_input([
                'id' => '_wopb_backorder_date'.$loop,
                'class' => 'wopb-required w-60',
                'label' => __('Availability Date', 'product-blocks-pro'),
                'type' => 'datetime-local',
                'value' => $product->get_meta('_wopb_backorder_date'),
                'desc_tip' => true,
                'description' => __("Message indicating date and time of the backorder product availability", 'product-blocks-pro'),
            ]);

            woocommerce_wp_text_input([
                'id' => '_wopb_backorder_message'.$loop,
                'class' => 'wopb-required w-60',
                'label' => __('Availability Message', 'product-blocks-pro'),
                'type' => 'text',
                'value' => $backorder_message ? $backorder_message : $default_backorder_message,
                'desc_tip' => true,
                'description' => __("Message indicating date and time of the backorder product availability", 'product-blocks-pro'),
            ]);
            $html .= ob_get_clean();

        $html .= '</div>';

        return $html;
    }

    /**
     * Backorder Simple Product Custom Data Save
     *
     * @return NULL
     * @since v.1.0.7
     */
    public function process_product_meta($post_id)
    {
        $product = wc_get_product($post_id);
        if ($product->is_on_backorder()) {
            $product->update_meta_data('_wopb_max_backorder', $_POST['_wopb_max_backorder']);
            $product->update_meta_data('_wopb_backorder_date', $_POST['_wopb_backorder_date']);
            $product->update_meta_data('_wopb_backorder_message', $_POST['_wopb_backorder_message']);
            $product->save();
        }
    }

    /**
     * Backorder Variable Product Custom Data Save
     *
     * @return NULL
     * @since v.1.0.7
     */
    public function save_product_variation($variation_id, $a)
    {
        $product = wc_get_product($variation_id);
        if ($product->is_on_backorder()) {
            $product->update_meta_data('_wopb_backorder_date', $_POST['_wopb_backorder_date'][$a]);
            $product->update_meta_data('_wopb_max_backorder', $_POST['_wopb_max_backorder'][$a]);
            $product->update_meta_data('_wopb_backorder_message', $_POST['_wopb_backorder_message'][$a]);
            $product->save();
        }
    }

    /**
     * Change Add to Cart Button Text
     *
     * @return STRING
     * @since v.1.0.7
     */
    public function add_to_cart_button_text($text)
    {
        global $product;
        if ($product && $product->is_type('simple') && !$product->managing_stock() && $product->is_on_backorder() && !wopb_pro_function()->is_backorder_closed($product) && wopb_function()->get_setting('backorder_add_to_cart_button_text')) {
            return wopb_function()->get_setting('backorder_add_to_cart_button_text');
        } else {
            return $text;
        }
    }

    /**
     * Get Stock HTML in Product Details Page
     *
     * @return HTML
     * @since v.1.0.7
     */
    public function get_stock_html( $html, $product ){
        if(!$product->managing_stock() && $product->is_on_backorder() && !wopb_pro_function()->is_backorder_closed($product)){
            $available_date_time = date('d M Y', strtotime($product->get_meta('_wopb_backorder_date'))) . ' at '. date('h:i a', strtotime($product->get_meta('_wopb_backorder_date')));
            $remaining_items = $this->remaining_item_count($product);

            if($product->get_meta('_wopb_backorder_date')){
                $html = '<div class="wopb-singlepage-backorder-message">';
                    $html .= '<span class="wopb-backorder-message">' . $product->get_meta('_wopb_backorder_message') . ': </span>';
                    $html .= '<span class="wopb-backorder-duration">' . $available_date_time . '</span>';
                    $html .= '<input type="hidden" class="wopb-single-variation-backorder-text" value="' . wopb_function()->get_setting('backorder_add_to_cart_button_text') . '">';
                $html .= '</div>';
            }

            if ($remaining_items) {
                $html .= '<div class="wopb-singlepage-backorder-remaining-item">';
                    $html .= '<span class="wopb-backorder-remaining-label">'.__('Remaining Item only: ', 'product-blocks-pro').'</span>';
                    $html .= '<span class="wopb-backorder-remaining-count">' . $remaining_items . '</span>';
                $html .= '</div>';
            }
        }
        return $html;
    }

    /**
     * Show Backorder Label in Cart
     *
     * @return HTML
     * @since v.1.0.7
     */
    public function cart_item_name($name, $cart_item, $cart_item_key) {
        $product = $cart_item['data'];
        if ($cart_item['variation_id']) {
            $product = wc_get_product($cart_item['variation_id']);
        }
        if (!$product->managing_stock() && $product->is_on_backorder() && !wopb_pro_function()->is_backorder_closed($product)) {
            $backorder_available_date = $product->get_meta('_wopb_backorder_date');
            $backorder_message = $product->get_meta('_wopb_backorder_message') . ': ';
            $backorder_available_date_time_formatted = date('d M Y h:i a', strtotime($backorder_available_date));

            $html = '<span class="wopb-cart-backorder-badge">' . wopb_function()->get_setting('backorder_button_text') . '</span>';
            if($backorder_available_date){
                $html .= '<div class="wopb-cart-backorder-message">';
                $html .= '<span class="wopb-backorder-message">' . $backorder_message . '</span>';
                $html .= '<span class="wopb-backorder-duration">' . $backorder_available_date_time_formatted . '</span>';
                $html .= '</div>';
            }
            return $name . $html;
        }
        return $name;
    }

    /**
     * Add Meta When Checkout Order for Backorder
     *
     * @return NULL
     * @since v.1.0.7
     */
    public function checkout_create_order_line_item(WC_Order_Item_Product $item, $cart_item_key, $values, $order) {
        $cart_item = WC()->cart->get_cart()[$cart_item_key];
        $product = wc_get_product($item['product_id']);
        if ($cart_item['variation_id']) {
            $product = wc_get_product($cart_item['variation_id']);
        }
        if (!$product->managing_stock() && $product->is_on_backorder() && !wopb_pro_function()->is_backorder_closed($product)) {
            $item->update_meta_data('wopb_backorder_item', 'yes');
        }
    }

    /**
     * Backorder Label Add to Order Item
     *
     * @return HTML
     * @since v.1.0.7
     */
    public function order_item_get_formatted_meta_data($formatted_meta, $item) {
        foreach ($formatted_meta as $key => $meta) {
            if ($meta->key == 'wopb_backorder_item') {
                $meta->display_key = '<span class="wopb-cart-backorder-badge">' . wopb_function()->get_setting('backorder_button_text') . '</span>';
            }
        }
        return $formatted_meta;
    }

    /**
     * Inserting "Order Type" Column Before Last Elements
     *
     * @return ARRAY
     * @since v.1.0.7
     */
    public function add_column_in_order_listing_page($columns) {
        $reordered_columns = [];
        foreach ($columns as $key => $column) {
            $reordered_columns[$key] = $column;
            if ($key == 'order_status') {
                $reordered_columns['wopb_order_page_order_type'] = __('Order Type', 'product-blocks-pro');
            }
        }
        return $reordered_columns;
    }

    /**
     * Show Backorder Label to Order Table Column
     *
     * @return HTML
     * @since v.1.0.7
     */
    public function set_order_type_column_value($column) {
        global $the_order;
        if ($column == 'wopb_order_page_order_type') {
            $has_item = false;
            $items = $the_order->get_items();
            foreach ($the_order->get_items() as $item) {
                if ($item->get_meta('wopb_backorder_item') == 'yes') {
                    $has_item = true;
                }
            }
            if ($has_item) {
                echo '<span class="wopb-cart-backorder-badge">'.wopb_function()->get_setting('backorder_button_text').'</span>';
            }
        }
    }

    /**
     * Validate Maximum Quantity Add to Cart
     *
     * @return NULL
     * @since v.1.0.7
     */
    public function add_to_cart_validation($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data) {
        $cart_item = WC()->cart->get_cart()[$cart_item_key];
        if(!$this->cart_quantity_validation($cart_item['quantity'] ,$product_id, $variation_id)){
            throw new \Exception(esc_html__("Quantity Exceeded for Backorder Item.", 'product-blocks'));
        }
    }

    public function update_cart_validation( $passed, $cart_item_key, $values, $quantity )
    {
        if(!$this->cart_quantity_validation($quantity ,$values['product_id'], $values['variation_id'])){
            wc_add_notice( __( 'Quantity Exceeded for Backorder Item.', 'product-blocks' ), 'error' );
            $passed = false;
        }
        return $passed;
    }

    public function cart_quantity_validation($quantity, $product_id, $variation_id = null){
        if($variation_id){
            $product = wc_get_product($variation_id);
        }else{
            $product = wc_get_product($product_id);
        }

        if ( !$product->managing_stock() && $product->is_on_backorder() && !wopb_pro_function()->is_backorder_closed($product) && $quantity > $this->remaining_item_count($product) ) {
            return false;
        }

        return true;
    }

    /**
     * Get Remaining Quantity After Order
     *
     * @return NUMBER
     * @since v.1.0.7
     */
    public function remaining_item_count($product) {
        if($product->get_parent_id() && $product->get_parent_id() != 0){
            $parent_product = wc_get_product($product->get_parent_id());
            if($parent_product->is_type('variable')){
                $booked_order_count = $this->get_total_backorder($parent_product->get_Id(), $product->get_Id());
            }
        } else {
            $booked_order_count = $this->get_total_backorder($product->get_Id());
        }
        $allow_max_backorder = $product->get_meta('_wopb_max_backorder');
        $remaining_items = intval($allow_max_backorder) - intval($booked_order_count);
        return $remaining_items;
    }

    /**
     * Get Total Order Number
     *
     * @return NUMBER
     * @since v.1.0.7
     */
    public function get_total_backorder($product_id, $variation_id = null) {
        global $wpdb;
        $variation_statement = $variation_id ? " AND order_product.variation_id = ".$variation_id : "";
        $result = $wpdb->get_results("
            SELECT sum(order_product.product_qty) as total_order
            FROM {$wpdb->prefix}wc_order_product_lookup as order_product
            INNER JOIN {$wpdb->prefix}wc_order_stats AS order_stat
                ON order_product.order_id = order_stat.order_id
            INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta
                ON order_product.order_item_id = order_item_meta.order_item_id AND order_item_meta.meta_key = 'wopb_backorder_item'
            WHERE order_product.product_id = {intval($product_id)} {$variation_statement}
                AND order_stat.status NOT IN ('wc-cancelled', 'wc-refunded')
        ");
        return $result[0]->total_order;
    }
}