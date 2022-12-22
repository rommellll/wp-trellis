<?php
/**
 * Call for Price Addons Core.
 * 
 * @package WOPB_PRO\Call for Price
 * @since v.1.0.8
 */

namespace WOPB_PRO;

defined('ABSPATH') || exit;

/**
 * Call for Price class.
 */
class CallForPrices {

    /**
	 * Setup class.
	 *
	 * @since v.1.0.8
	 */
    public function __construct(){
        add_action('wp_enqueue_scripts', array($this, 'add_call_for_price_scripts'));
        add_filter( 'woocommerce_empty_price_html', [$this, 'wopb_woocommerce_empty_price_html'], 10, 2 );
    }

    /**
	 * Call for Price JS Script Add
     * 
     * @since v.1.0.8
	 * @return NULL
	 */
    public function add_call_for_price_scripts() {
        wp_enqueue_style('wopb-call-for-price-style', WOPB_PRO_URL.'addons/call_for_price/css/call_for_price.css', array(), WOPB_PRO_VER);
    }

    /**
	 * Call for Price Addons Initial Setup Action
     * 
     * @since v.1.0.8
	 * @return NULL
	 */
    public function initial_setup(){
        // Set Default Value
        $initial_data = array(
            'call_for_price_heading' => 'yes',
            'call_for_price_text'    => 'Call for Price',
        );
        foreach ($initial_data as $key => $val) {
            wopb_function()->set_setting($key, $val);
        }
    }

    public function wopb_woocommerce_empty_price_html($price, $product){
        global $woocommerce_loop;
        if(is_product() && !@$woocommerce_loop['name']){
            $target = '';
            if(wopb_function()->get_setting('call_type') == 'phone'){
                $link = 'tel:'.wopb_function()->get_setting('call_link');
            }elseif (wopb_function()->get_setting('call_type') == 'skype'){
                $link = 'skype:'.wopb_function()->get_setting('call_link')."?call";
            }elseif (wopb_function()->get_setting('call_type') == 'whatsapp'){
                $link = 'https://api.whatsapp.com/send?phone='.wopb_function()->get_setting('call_link').'&text='.home_url($_SERVER['REQUEST_URI']);
                $target = '_blank';
            }
            if(isset($link)){
                $html = "<a target='$target' href='$link' class='wopb-calling-link'>".wopb_function()->get_setting('call_for_price_text')."</a>";
                add_filter( 'woocommerce_is_purchasable',[$this, 'wopb_remove_add_to_cart_form'], 10, 2 );
                return $html;
            }
        }
    }

    public function wopb_remove_add_to_cart_form( $is_purchasable, $product ) {
        return false;
    }
}