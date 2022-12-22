<?php
/**
 * Preorder Addons Core.
 *
 * @package WOPB_PRO\Preorder
 * @since v.1.0.4
 */

namespace WOPB_PRO;

use WC_Order_Item_Product;

defined('ABSPATH') || exit;

/**
 * Preorder class.
 */
class Preorder
{

    /**
     * Setup class.
     *
     * @since v.1.0.4
     */

    public function __construct()
    {
        // Custom Option in Variable Product
        add_action('woocommerce_variation_options', [$this, 'wopb_add_preorder_variable_checkbox'], 10, 3);
        add_filter('product_type_options', [$this, 'wopb_add_custom_product_type'], 5);

        //custom field in variable product
        add_action('woocommerce_product_after_variable_attributes', [$this, 'wopb_pre_order_custom_field_variation'], 10, 3);
        add_action('woocommerce_save_product_variation', [$this, 'wopb_pre_order_save_variation_data'], 10, 2);

        // Pre-order field save in woocomerce product page in admin panel
        add_action('woocommerce_process_product_meta', [$this, 'pre_order_woocommerce_fields_save'], 10, 2);

        //woocommerce add column to order table in admin panel
        add_filter('manage_edit-shop_order_columns', [$this, 'add_column_in_order_listing_page'], 10, 1);
        add_action('manage_shop_order_posts_custom_column', [$this, 'set_order_type_column_value'], 10, 2);

        //show pre order content in woocommerce single product
        add_action('woocommerce_get_price_html', [$this, 'wopb_show_pre_order_content_in_single_product'], 50, 2);
        add_filter('woocommerce_available_variation', [$this, 'wopb_show_pre_order_variation_content_in_single_product'], 10, 3);

        //check product Pre-order end or not
        add_action('woocommerce_is_purchasable', [$this, 'is_purchasable_products'], 1, 2);

        //validate pre order item when add to cart
        add_filter('woocommerce_add_to_cart', [$this, 'is_validate_order_item'], 10, 6);

        // WooCommerce cart item name
        add_filter('woocommerce_cart_item_name', [$this, 'wopb_woocommerce_cart_item_name'], 10, 4);

        // WooCommerce order item meta data
        add_filter('woocommerce_order_item_get_formatted_meta_data', [$this, 'woocommerce_re_format_order_item_meta'], 10, 4);

        // WooCommerce checkout order line meta modify
        add_filter('woocommerce_checkout_create_order_line_item', [$this, 'wopb_add_pre_order_meta_to_item'], 10, 4);


        // Filter hook for change add to cart button text in single product 
        add_filter('woocommerce_product_single_add_to_cart_text', [$this, 'add_to_cart_button_text']);
        add_filter('woocommerce_product_add_to_cart_text', [$this, 'add_to_cart_button_text']);

        // Productx tab in woocomerce product page in admin panel
        add_filter('woocommerce_product_data_tabs', [$this, 'wopb_productx_tab_data'], 10, 1);
        add_action('woocommerce_product_data_panels', [$this,'wopb_productx_custom_field_simple']);

        // Add Script for Addons
        add_action('wp_enqueue_scripts', [$this, 'add_preorder_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'add_preorder_scripts']);

        //Before Single Product
        add_action('woocommerce_before_single_product', [$this,'autoconvert_product']);
    }

    /**
	 * Auto Convert Product Pre-order to Simple
     * 
     * @since v.1.0.4
	 * @return NULL
	 */
    public function autoconvert_product($admin_product = null) {
        global $product;
        if($admin_product){
            $product = $admin_product;
        }
        $type = $product->get_type();
        if ($this->is_simple_preorder($product) && $type == 'simple' && $this->is_auto_convert_available($product) && wopb_pro_function()->is_preorder_closed($product)) {
             $product->update_meta_data('_wopb_preorder_simple', '');
             $product->save();
        }elseif ($type == 'variable'){
            foreach ($product->get_available_variations() as $variation){
                $variation = wc_get_product($variation['variation_id']);
                if ($this->is_variable_preorder($variation) && $this->is_auto_convert_available($variation) && wopb_pro_function()->is_preorder_closed($variation)) {
                    $variation->update_meta_data('_wopb_preorder_variable', '');
                    $variation->save();
                }
            }
        }
    }

    /**
	 * Pre-Order JS & CSS Script
     * 
     * @since v.1.0.4
	 * @return NULL
	 */
    public function add_preorder_scripts() {
        wp_enqueue_style('wopb-preorder-style', WOPB_PRO_URL . 'addons/preorder/css/preorder.css', array(), WOPB_PRO_VER);
        wp_enqueue_script('wopb-preorder-script', WOPB_PRO_URL . 'addons/preorder/js/preorder.js', array('jquery'), WOPB_PRO_VER, true);
    }

    /**
	 * Pre-Order Custom Field
     * 
     * @since v.1.0.4
	 * @return NULL
	 */
    public function wopb_productx_custom_field_simple(){
        $html = '';
        global $post;
        $html .= '<div class="panel woocommerce_options_panel hidden" id="productx_tab_data">';
            $html .= '<div class="wopb-productx-options-tab-wrap">';
                if (wopb_function()->is_lc_active()) {
                    $html .= '<div class="wopb-woocommerce-preorder-field-group" id="wopb-woocommerce-preorder-field-group">';
                        $html .= $this->generate_field($post->ID, '');
                    $html .= '</div>';
                    $html .= '<div id="wopb-preorder-select-instruction">';
                        $html .= '<h2 class="wopb-preorder-select-instruction-title">';
                            $html .= '<span class="dashicons dashicons-warning"></span>';
                            $html .= __('For giving pre-order information please select Pre-Order Checkbox', 'product-blocks');
                        $html .= '</h2>';
                    $html .= '</div>';
                } else {
                    $html .= '<div id="wopb-preorder-field-group-pro-instruction">';
                        $html .= '<a href="https://www.wpxpo.com/productx" target="_blank">';
                            $html .= '<img style="max-width: 100%;" src="'.WOPB_URL.'/assets/img/addons/preorder-pro.png'.'">';
                        $html .= '</a>';
                    $html .= '</div>';
                }
            $html .= '</div>';
        $html .= '</div>';

        echo $html;
    }

    /**
     * Productx tab in woocomerce product page in admin panel
     *
     * @return ARRAY
     * @since v.1.0.4
     */
    public function wopb_productx_tab_data( $product_data_tabs ) {
        $product_data_tabs['productx'] = array(
            'label'  => __( 'Preorder', 'product-blocks' ),
            'class'  => array( 'show_if_simple', 'hidden' ),
            'target' => 'productx_tab_data',
        );
        return $product_data_tabs;
    }

    /**
     * Pre Order Option Added
     *
     * @return ARRAY
     * @since v.1.0.4
     */
    public function wopb_add_custom_product_type( $product_type_options ) {
        $product_type_options['wopb_preorder_simple'] = array(
            'id' => '_wopb_preorder_simple',
            'wrapper_class' => 'show_if_simple wopb_preorder_simple',
            'label' => __('Pre-Order', 'product-blocks'),
            'description' => __('If you want to set pre-order click here', 'product-blocks'),
            'default' => 'no',
        );
        return $product_type_options;
    }

    /**
     * Change Add to Cart Button Text
     *
     * @return NULL
     * @since v.1.0.4
     */
    public function add_to_cart_button_text($text)
    {
        global $product;
        if ($this->is_simple_preorder($product) && !wopb_pro_function()->is_preorder_closed($product) && wopb_function()->get_setting('preorder_add_to_cart_button_text')) {
            return wopb_function()->get_setting('preorder_add_to_cart_button_text');
        } else {
            return $text;
        }
    }


    /**
     * Preorder Addons Intitial Setup Action
     *
     * @return NULL
     * @since v.1.0.4
     */
    public function initial_setup()
    {
        $initial_data = array(
            'preorder_heading' => 'yes',
            'preorder_button_text' => __('Pre-order', 'product-blocks'),
            'preorder_add_to_cart_button_text' => __('Pre-order Now', 'product-blocks'),
            'preorder_message_text' => 'Available On',
            'preorder_coming_soon_text' => 'Coming Soon',
        );
        foreach ($initial_data as $key => $val) {
            wopb_function()->set_setting($key, $val);
        }
    }


    /**
     * Save input field when product save in admin panel
     *
     * @param INT | Product ID
     * @return NULL
     * @since v.1.0.4
     */
    public function pre_order_woocommerce_fields_save($post_id)
    {
        $product = wc_get_product($post_id);

        if (isset($_POST['_wopb_preorder_simple'])) {
            $product->update_meta_data('_wopb_preorder_simple', 'yes');
            $product->update_meta_data('_wopb_preorder_date', $_POST['_wopb_preorder_date']);
            $product->update_meta_data('_wopb_max_preorder', $_POST['_wopb_max_preorder']);
            $product->update_meta_data('_wopb_preorder_message', $_POST['_wopb_preorder_message']);
            $product->update_meta_data('_wopb_preorder_coming_soon', $_POST['_wopb_preorder_coming_soon']);

            $product->update_meta_data('_wopb_preorder_auto_convert', $_POST['_wopb_preorder_auto_convert'] ?? '');
            $product->update_meta_data('_wopb_preorder_price_manage', $_POST['_wopb_preorder_price_manage'] ?? '');

            $product->update_meta_data('_wopb_preorder_price_type', $_POST['_wopb_preorder_price_type']);
            $product->update_meta_data('_wopb_preorder_price', $_POST['_wopb_preorder_price']);
            if ($_POST['_wopb_preorder_price_manage'] && isset($_POST['_wopb_preorder_price_type'])) {
                if ($_POST['_wopb_preorder_price_type'] == 'fixed' && $_POST['_wopb_preorder_price']) {
                    $product->set_sale_price($_POST['_wopb_preorder_price']);
                } else if ($_POST['_wopb_preorder_price_type'] == 'percentage' && $_POST['_wopb_preorder_price']) {
                    $product->set_sale_price($product->get_regular_price() - (($product->get_regular_price() * $_POST['_wopb_preorder_price']) / 100));
                }
            }
        } else {
            $product->update_meta_data('_wopb_preorder_simple', '');
        }
        $product->save();
    }

    /**
     * Preorder Menu Item in WooCommerce
     *
     * @return NULL
     * @since v.1.0.4
     */
    public function wopb_add_preorder_variable_checkbox($loop, $variation_data, $variation)
    {
        $variable_product = wc_get_product($variation->ID);
        if (wopb_function()->is_lc_active()) {
            $is_variable_preorder = $variable_product->get_meta('_wopb_preorder_variable');
            echo '<label>' . __('Pre-Order', 'product-blocks');
            echo '<input type="checkbox" id="_wopb_preorder_variable[' . esc_attr($loop) . ']" class="_wopb_preorder_variable" name="_wopb_preorder_variable[' . esc_attr($loop) . ']"' . ($is_variable_preorder ? 'checked' : '') . '>';
            echo wc_help_tip(__('Enable pre-order for giving pre-order information', 'product-blocks'));
            echo '</label>';
        }
    }

    /**
     * Pre Order Custom Field in Variation
     *
     * @return NULL
     * @since v.1.0.4
     */
    public function generate_field($post_id, $loop = '') {
        $html = '';
        $loop = $loop !== '' ? '['.$loop.']' : '';
        $product = wc_get_product($post_id);
        $this->autoconvert_product($product);

        $preorder_message = $product->get_meta('_wopb_preorder_message');
        $default_preorder_message = wopb_function()->get_setting('preorder_message_text');
        $coming_soon_message = $product->get_meta('_wopb_preorder_coming_soon');
        $default_coming_soon_message = wopb_function()->get_setting('preorder_coming_soon_text');

        $html .= '<h4 class="wopb-woocommerce-preorder-title">' . __('ProductX Pre-order information', 'product-blocks') . '</h4>';
        ob_start();
        woocommerce_wp_text_input([
            'id' => '_wopb_max_preorder'.$loop,
            'class' => 'wopb_required',
            'label' => __('Available Quantity', 'product-blocks'),
            'type' => 'number',
            'value' => $product->get_meta('_wopb_max_preorder'),
            'desc_tip' => true,
            'description' => __("Enter the maximum amount of products available for pre-order", 'product-blocks')
        ]);

        woocommerce_wp_text_input([
            'id' => '_wopb_preorder_date'.$loop,
            'class' => 'wopb_preorder_duration',
            'label' => __('Availability Date', 'product-blocks'),
            'type' => 'datetime-local',
            'value' => $product->get_meta('_wopb_preorder_date'),
            'desc_tip' => true,
            'description' => __("Message indicating date and time of the pre-order product availability", 'product-blocks'),
        ]);

        woocommerce_wp_text_input([
            'id' => '_wopb_preorder_message'.$loop,
            'class' => 'wopb_preorder_duration',
            'label' => __('Availability Message', 'product-blocks'),
            'type' => 'text',
            'value' => $preorder_message ? $preorder_message : $default_preorder_message,
            'desc_tip' => true,
            'description' => __("Message indicating date and time of the pre-order product availability", 'product-blocks'),
        ]);

        woocommerce_wp_text_input([
            'id' => '_wopb_preorder_coming_soon'.$loop,
            'class' => 'wopb_required',
            'label' => __('Pre-Release Message', 'product-blocks'),
            'type' => 'text',
            'value' => $coming_soon_message ? $coming_soon_message : $default_coming_soon_message,
            'desc_tip' => true,
            'description' => __("Enter a message if the pre-ordered product availability date and time are not given", 'product-blocks'),
            'custom_attributes' => [
                "required" => "required"
            ]
        ]);

        woocommerce_wp_checkbox([
            'id' => '_wopb_preorder_auto_convert'.$loop,
            'label' => __('Auto Convert', 'product-blocks'),
            'type' => 'checkbox',
            'value' => $product->get_meta('_wopb_preorder_auto_convert'),
            'description' => __("Enable conversion to default product after the pre-Order date and time is Over", "product-blocks"),
        ]);

        woocommerce_wp_checkbox([
            'id' => '_wopb_preorder_price_manage'.$loop,
            'class' => '_wopb_preorder_price_manage',
            'label' => __('Manage Discount', 'product-blocks'),
            'type' => 'checkbox',
            'value' => $product->get_meta('_wopb_preorder_price_manage'),
            'description' => __("Allow discounted prices for pre-order items"),
        ]);

        woocommerce_wp_select([
            'id' => '_wopb_preorder_price_type'.$loop,
            'class' => 'wopb_preorder_manage_price_depend_required w-50',
            'label' => __('Discount Type', 'product-blocks'),
            'options' => array(
                '' => __('Select Type', 'product-blocks'),
                'fixed' => __('Fixed', 'product-blocks'),
                'percentage' => __('Percentage', 'product-blocks'),
            ),
            'value' => $product->get_meta('_wopb_preorder_price_type'),
            'desc_tip' => true,
            'description' => __("Set the discount pricing type", 'product-blocks'),
        ]);

        woocommerce_wp_text_input([
            'id' => '_wopb_preorder_price'.$loop,
            'class' => 'wopb_preorder_manage_price_depend_required',
            'label' => __('Discounted Price/Percentage', 'product-blocks'),
            'type' => 'number',
            'value' => $product->get_meta('_wopb_preorder_price'),
            'desc_tip' => true,
            'description' => __("Set the discounted price for the pre-order products", 'product-blocks'),
        ]);
        $html .= ob_get_clean();

        return $html;
    }
    
    /**
     * Show Pre-Order Variation Fields
     *
     * @return NULL
     * @since v.1.0.4
     */
    public function wopb_pre_order_custom_field_variation($loop, $variation_data, $variation)
    {
        if (wopb_function()->is_lc_active()) {
            $html = '<div class="wopb-woocommerce-variable-preorder-field-group">';
                $html .= $this->generate_field($variation->ID, $loop);
            $html .= '</div>';
            echo $html;
        }
    }


    /**
     * Show Pre-Order Variation Save
     *
     * @return NULL
     * @since v.1.0.4
     */
    public function wopb_pre_order_save_variation_data($variation_id, $a)
    {
        $product = wc_get_product($variation_id);

        if (isset($_POST['_wopb_preorder_variable'][$a])) {
            $product->update_meta_data('_wopb_preorder_variable', 'yes');
            $product->update_meta_data('_wopb_max_preorder', $_POST['_wopb_max_preorder'][$a]);
            $product->update_meta_data('_wopb_preorder_date', $_POST['_wopb_preorder_date'][$a]);
            $product->update_meta_data('_wopb_preorder_message', $_POST['_wopb_preorder_message'][$a]);
            $product->update_meta_data('_wopb_preorder_coming_soon', $_POST['_wopb_preorder_coming_soon'][$a]);

            $product->update_meta_data('_wopb_preorder_auto_convert', $_POST['_wopb_preorder_auto_convert'][$a] ?? '');
            $product->update_meta_data('_wopb_preorder_price_manage', $_POST['_wopb_preorder_price_manage'][$a] ?? '');

            $product->update_meta_data('_wopb_preorder_price_type', $_POST['_wopb_preorder_price_type'][$a]);
            $product->update_meta_data('_wopb_preorder_price', $_POST['_wopb_preorder_price'][$a]);
            if ($_POST['_wopb_preorder_price_manage'][$a] && isset($_POST['_wopb_preorder_price_type'][$a])) {
                if ($_POST['_wopb_preorder_price_type'][$a] == 'fixed' && $_POST['_wopb_preorder_price'][$a]) {
                    $product->set_sale_price($_POST['_wopb_preorder_price'][$a]);
                } else if ($_POST['_wopb_preorder_price_type'][$a] == 'percentage' && $_POST['_wopb_preorder_price'][$a]) {
                    $product->set_sale_price($product->get_regular_price() - (($product->get_regular_price() * $_POST['_wopb_preorder_price'][$a]) / 100));
                }
            }
        } else {
            $product->update_meta_data('_wopb_preorder_variable', '');
        }
        $product->save();
    }

    
    /**
     * Show Pre-Order Single Content in Single Product
     *
     * @return STRING
     * @since v.1.0.4
     */
    public function wopb_show_pre_order_content_in_single_product($price, $product)
    {
        if (!is_product()) return $price;
        if (!$product) return $price;
        if (!$this->is_simple_preorder($product)) return $price;

        $html = '';
        if($this->is_simple_preorder($product)){
            if (wopb_pro_function()->is_preorder_closed($product) && !$this->is_auto_convert_available($product) && $price) {
                $html = '<h3 class="wopb-preorder-closed">'.__('Pre-Order Closed', 'product-blocks').'</h3>';
            }else {
                //pre order message
                $preorder_available_date = $product->get_meta('_wopb_preorder_date');
                $html = $this->preorder_message($preorder_available_date, $product, $html);

                //remaining qty
                $html = $this->remaining_item($product, $html);

                if ($product->get_meta('_wopb_preorder_date')) {
                    //countdown for available date and time
                    $html = $this->countdown($preorder_available_date, $html);
                }

            }

            $html = $price . $html;
            $html = "<div class='wopb-single-product-preorder'>{$html}</div>";
            echo $html;
        }else{
            return $price;
        }

        return $price;

    }


    /**
     * Show Pre-Order Vaiation Content in Single Product
     *
     * @return STRING
     * @since v.1.0.4
     */
    public function wopb_show_pre_order_variation_content_in_single_product($data, $product, $variation)
    {
        if ($product->is_type('variable')) {
            $variation_id = $variation->get_id();
            $html = '';

            if ($this->is_variable_preorder($variation) && !wopb_pro_function()->is_preorder_closed($variation)) {
                //pre order message
                $preorder_available_date = $variation->get_meta('_wopb_preorder_date');
                $html = $this->preorder_message($preorder_available_date, $variation, $html);

                //remaining qty
                $html = $this->remaining_item($product, $html, $variation);

                if ($variation->get_meta('_wopb_preorder_date')) {
                    //countdown for available date and time
                    $html = $this->countdown($preorder_available_date, $html);
                }

                $html .= '<input type="hidden" class="wopb-single-variation-pre-order-text" value="' . wopb_function()->get_setting('preorder_add_to_cart_button_text') . '">';
                $data['variation_description'] .= $html . '<br>';
            }elseif ($this->is_variable_preorder($variation) && wopb_pro_function()->is_preorder_closed($variation) && !$this->is_auto_convert_available($variation)) {
                $html = '<h3 class="wopb-preorder-closed">'.__('Pre-Order Closed', 'product-blocks').'</h3>';
                $data['variation_description'] .= $html . '<br>';
            }

            return $data;
        }
    }

    
    /**
     * Show Pre-Order Message
     *
     * @return STRING
     * @since v.1.0.4
     */
    private function preorder_message($preorder_available_date, $product, $html)
    {
        $html = '';
        $preorder_message = $product->get_meta('_wopb_preorder_message') . ': ';
        $preorder_available_date_time_formatted = date('d M Y', strtotime($preorder_available_date)) . ' at '. date('h:i a', strtotime($preorder_available_date));

        if ($preorder_message && $preorder_available_date) {
            $html .= '<div class="wopb-singlepage-preorder-message">';
                $html .= '<span class="wopb-preorder-message">' . $preorder_message . '</span>';
                $html .= '<span class="wopb-preorder-duration">' . $preorder_available_date_time_formatted . '</span>';
            $html .= '</div>';
        } else {
            $html .= '<div class="wopb-singlepage-no-date">';
                $html .= '<span class="no-date-message">' . $product->get_meta('_wopb_preorder_coming_soon') . '</span>';
            $html .= '</div>';
        }
        return $html;
    }

    
    /**
     * Show pre order remining item in single product  details
     *
     * @return STRING
     * @since v.1.0.4
     */
    private function remaining_item($product, $html, $variation = null)
    {
        $remaining_items = $this->remaining_item_count($product, $variation);
        if ($remaining_items) {
            $html .= '<div class="wopb-singlepage-preorder-remaining-item">';
                $html .= '<span class="wopb-preorder-remaining-label">'.__('Remaining Item only: ', 'product-blocks').'</span>';
                $html .= '<span class="wopb-preorder-remaining-count">' . $remaining_items . '</span>';
            $html .= '</div>';
        }
        return $html;
    }


    /**
     * Show Pre-Order Remaining Item Count
     *
     * @return STRING
     * @since v.1.0.4
     */
    public function remaining_item_count($product, $variation = null) {
        if ($variation) {
            $allow_max_preorder = $variation->get_meta('_wopb_max_preorder');
        } else {
            $allow_max_preorder = $product->get_meta('_wopb_max_preorder');
        }

        $booked_order_count = $this->get_total_product_order_by_meta($product->get_Id(), $variation);
        $remaining_items = intval($allow_max_preorder) - intval($booked_order_count);
        return $remaining_items;
    }


    /**
     * Show Pre-Order Countdown in Single Product Details
     *
     * @return STRING
     * @since v.1.0.4
     */
    private function countdown($preorder_available_date, string $html) {
        $preorder_available_duration = date("Y-m-d H:i:s", strtotime($preorder_available_date));
        $current_date = new \DateTime(date('Y-m-d H:i:s', current_time('timestamp')));
        $duration = $current_date->diff(new \DateTime($preorder_available_duration));
        
        $html .= '<div class="wopb-singlepage-preorder-countdown">';
            $html .= '<div class="wopb-countdown-block">';
                $html .= '<div>';
                    $html .= '<span class="wopb-preorder-countdown-number"> ' . $duration->format('%D') . '</span>';
                    $html .= '<span class="wopb-preorder-countdown-text">'.__('Days', 'product-blocks').'</span> ';
                $html .= '</div>';
                $html .= '<span class="wopb-preorder-countdown-separator">:</span>';
            $html .= '</div>';
            $html .= '<div class="wopb-countdown-block">';
                $html .= '<div>';
                    $html .= '<span class="wopb-preorder-countdown-number"> ' . $duration->format('%H') . '</span>';
                    $html .= '<span class="wopb-preorder-countdown-text">'.__('Hours', 'product-blocks').'</span>';
                $html .= '</div>';
                $html .= '<span class="wopb-preorder-countdown-separator">:</span>';
            $html .= '</div>';
            $html .= '<div class="wopb-countdown-block">';
                $html .= '<div>';
                    $html .= '<span class="wopb-preorder-countdown-number"> ' . $duration->format('%I') . '</span>';
                    $html .= '<span class="wopb-preorder-countdown-text">'.__('Minutes', 'product-blocks').'</span>';
                $html .= '</div>';
                $html .= '<span class="wopb-preorder-countdown-separator">:</span>';
            $html .= '</div>';
            $html .= '<div class="wopb-countdown-block">';
                $html .= '<div>';
                    $html .= '<span class="wopb-preorder-countdown-number"> ' . $duration->format('%S') . '</span>';
                    $html .= '<span class="wopb-preorder-countdown-text">'.__('Seconds', 'product-blocks').'</span>';
                $html .= '</div>';
            $html .= '</div>';
        $html .= '</div>';

        return $html;
    }


    /**
     * Show Pre-order Label in Cart
     *
     * @return STRING
     * @since v.1.0.4
     */
    public function wopb_woocommerce_cart_item_name($name, $cart_item, $cart_item_key) {
        $product = $cart_item['data'];
        if ($cart_item['variation_id']) {
            $product = wc_get_product($cart_item['variation_id']);
        }
        if (($this->is_variable_preorder($product) || $this->is_simple_preorder($product)) && !wopb_pro_function()->is_preorder_closed($product)) {
            $preorder_available_date = $product->get_meta('_wopb_preorder_date');
            $preorder_message = $product->get_meta('_wopb_preorder_message') . ': ';
            $preorder_available_date_time_formatted = date('d M Y h:i a', strtotime($preorder_available_date));

            $pre_order_content = '<span class="wopb-cart-preorder-badge">' . wopb_function()->get_setting('preorder_button_text') . '</span>';
            if($preorder_available_date){
                $pre_order_content .= '<div class="wopb-cart-preorder-message">';
                $pre_order_content .= '<span class="wopb-preorder-message">' . $preorder_message . '</span>';
                $pre_order_content .= '<span class="wopb-preorder-duration">' . $preorder_available_date_time_formatted . '</span>';
                $pre_order_content .= '</div>';
            }
            return $name . $pre_order_content;
        }
        return $name;
    }


    /**
     * Add Meta When Checkout Order for Pre-Order
     *
     * @return STRING
     * @since v.1.0.4
     */
    public function wopb_add_pre_order_meta_to_item(WC_Order_Item_Product $item, $cart_item_key, $values, $order) {
        $cart_item = WC()->cart->get_cart()[$cart_item_key];
        $product = wc_get_product($item['product_id']);
        if ($cart_item['variation_id']) {
            $product = wc_get_product($cart_item['variation_id']);
        }
        if (($this->is_variable_preorder($product) || $this->is_simple_preorder($product)) && !wopb_pro_function()->is_preorder_closed($product)) {
            $item->update_meta_data('wopb_pre_order_item', 'yes');
        }
    }


    /**
     * Cart Page Pre Order Label Add
     *
     * @return HTML
     * @since v.1.0.4
     */
    public function woocommerce_re_format_order_item_meta($formatted_meta, $item) {
        foreach ($formatted_meta as $key => $meta) {
            if ($meta->key == 'wopb_pre_order_item') {
                $meta->display_key = '<span class="wopb-cart-preorder-badge">' . wopb_function()->get_setting('preorder_button_text') . '</span>';
            }
        }
        return $formatted_meta;
    }


    /**
     * Inserting "Order Type" Column Before Last Elements
     *
     * @return ARRAY
     * @since v.1.0.4
     */
    public function add_column_in_order_listing_page($columns) {
        $reordered_columns = [];
        foreach ($columns as $key => $column) {
            $reordered_columns[$key] = $column;
            if ($key == 'order_status') {
                $reordered_columns['wopb_order_page_order_type'] = __('Order Type', 'product-blocks');
            }
        }
        return $reordered_columns;
    }


    /**
     * Show Pre-Order Label to Order Table Column
     *
     * @return STRING
     * @since v.1.0.4
     */
    public function set_order_type_column_value($column) {
        global $the_order;
        if ($column == 'wopb_order_page_order_type') {
            $has_item = false;
            $items = $the_order->get_items();
            foreach ($the_order->get_items() as $item) {
                if ($item->get_meta('wopb_pre_order_item') == 'yes') {
                    $has_item = true;
                }
            }
            if ($has_item) {
                echo '<span class="wopb-cart-preorder-badge">'.wopb_function()->get_setting('preorder_button_text').'</span>';
            }
        }
    }

    
    /**
     * Get Total Order Number
     *
     * @return STRING
     * @since v.1.0.4
     */
    public function get_total_product_order_by_meta($product_id, $variation = null) {
        global $wpdb;
        $variation_statement = $variation ? " AND order_product.variation_id = ".intval($variation->get_Id()) : "";
        $result = $wpdb->get_results("
            SELECT sum(order_product.product_qty) as total_order
            FROM {$wpdb->prefix}wc_order_product_lookup as order_product
            INNER JOIN {$wpdb->prefix}wc_order_stats AS order_stat
                ON order_product.order_id = order_stat.order_id
            INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta
                ON order_product.order_item_id = order_item_meta.order_item_id AND order_item_meta.meta_key = 'wopb_pre_order_item'
            WHERE order_product.product_id = {intval($product_id)} {$variation_statement}
                AND order_stat.status NOT IN ('wc-cancelled', 'wc-refunded')
        ");
        return $result[0]->total_order;
    }


    /**
     * Check the Product Purchasable or Not
     *
     * @return STRING
     * @since v.1.0.4
     */
    public function is_purchasable_products($status, $product) {
        if ($this->is_simple_preorder($product) && wopb_pro_function()->is_preorder_closed($product) && !$this->is_auto_convert_available($product)) {
            return false;
        } elseif ($this->is_simple_preorder($product) && wopb_pro_function()->is_preorder_closed($product) && $this->is_auto_convert_available($product)) {
            $product->update_meta_data('_wopb_preorder_simple', '');
            $product->save();
        }
        return true;
    }

    public function is_simple_preorder($product)
    {
        if ($product->get_meta('_wopb_preorder_simple') == 'yes') {
            return true;
        } else {
            return false;
        }
    }

    public function is_variable_preorder($product)
    {
        if ($product->get_meta('_wopb_preorder_variable') == 'yes') {
            return true;
        } else {
            return false;
        }
    }

    public function is_preorder_closed($product)
    {
        $preorder_available_duration = date('Y-m-d h:i a', strtotime($product->get_meta('_wopb_preorder_date')));
        if ($product->get_meta('_wopb_preorder_date') && current_time('Y-m-d h:i') > $preorder_available_duration) {
            return true;
        } else {
            return false;
        }
    }

    public function is_auto_convert_available($product)
    {
        if ($product->get_meta('_wopb_preorder_auto_convert') == 'yes' && $product->get_meta('_wopb_preorder_date')) {
            return true;
        } else {
            return false;
        }
    }

    public function is_validate_order_item($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data) {
        $cart_item = WC()->cart->get_cart()[$cart_item_key];
        $product = wc_get_product($product_id);
        if ($variation_id) {
            $product = wc_get_product($variation_id);
        }

        if ( ($product->get_meta('_wopb_max_preorder') && $cart_item['quantity'] > $this->remaining_item_count($product)) && ($this->is_simple_preorder($product) || $this->is_variable_preorder($product) && !wopb_pro_function()->is_preorder_closed($product))) {
            throw new \Exception(esc_html__("Quantity Exceeded for Pre-Order Item.", 'product-blocks'));
        }
    }
}