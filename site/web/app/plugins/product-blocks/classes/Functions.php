<?php
/**
 * Common Functions.
 * 
 * @package WOPB\Functions
 * @since v.1.0.0
 */

namespace WOPB;
use WOPB_PRO\Currency_Switcher_Action;

defined('ABSPATH') || exit;

/**
 * Functions class.
 */
class Functions{

	private $PLUGIN_NAME = 'ProductX';
	private $PLUGIN_SLUG = 'product-blocks';
	private $PLUGIN_VERSION = WOPB_VER;
    private $API_ENDPOINT = 'https://inside.wpxpo.com';

    /**
	 * Setup class.
	 *
	 * @since v.1.0.0
	 */
    public function __construct(){
        if (!isset($GLOBALS['wopb_settings'])) {
            $GLOBALS['wopb_settings'] = get_option('wopb_options');
        }
    }

    /**
	 * Set CSS in the Post Single Page
     * 
     * @since v.1.1.0
	 * @return NULL
	 */
    public function set_css_style($post_id, $shortcode = false){
        if( $post_id ){
			$upload_dir_url = wp_get_upload_dir();
			$upload_css_dir_url = trailingslashit( $upload_dir_url['basedir'] );
			$css_dir_path = $upload_css_dir_url . "product-blocks/wopb-css-{$post_id}.css";

            $css_dir_url = trailingslashit( $upload_dir_url['baseurl'] );
            if (is_ssl()) {
                $css_dir_url = str_replace('http://', 'https://', $css_dir_url);
            }

            // Reusable CSS
			$reusable_id = wopb_function()->reusable_id($post_id);
			foreach ( $reusable_id as $id ) {
				$reusable_dir_path = $upload_css_dir_url."product-blocks/wopb-css-{$id}.css";
				if (file_exists( $reusable_dir_path )) {
                    $css_url = $css_dir_url . "product-blocks/wopb-css-{$id}.css";
				    wp_enqueue_style( "wopb-post-{$id}", $css_url, array(), wopb_function()->get_setting('save_version'), 'all' );
				}else{
					$css = get_post_meta($id, '_wopb_css', true);
                    if( $css ) {
                        wp_enqueue_style("wopb-post-{$id}", $css, false, wopb_function()->get_setting('save_version'));
                    }
				}
            }

            if (isset($_GET['et_fb']) || (isset($_GET['action']) && sanitize_key($_GET['action']) == 'elementor') || $shortcode) {
                return wopb_function()->esc_inline(get_post_meta($post_id, '_wopb_css', true));
            } else {
                if ( file_exists( $css_dir_path ) ) {
                    $css_url = $css_dir_url . "product-blocks/wopb-css-{$post_id}.css";
                    wp_enqueue_style( "wopb-post-{$post_id}", $css_url, array(), wopb_function()->get_setting('save_version'), 'all' );
                } else {
                    $css = get_post_meta($post_id, '_wopb_css', true);
                    if( $css ) {
                        wp_enqueue_style("wopb-post-{$post_id}", $css, false, wopb_function()->get_setting('save_version'));
                    }
                }
            }
			
		}
    }

    /**
	 * Get Reusable ID List of Any Page
     * 
     * @since v.1.1.0
	 * @return ARRAY | Reusable ID Lists
	 */
    public function reusable_id($post_id){
        $reusable_id = array();
        if($post_id){
            $post = get_post($post_id);
            if ($post && has_blocks($post->post_content)) {
                $blocks = parse_blocks($post->post_content);
                foreach ($blocks as $key => $value) {
                    if(isset($value['attrs']['ref'])) {
                        $reusable_id[] = $value['attrs']['ref'];
                    }
                }
            }
        }
        return $reusable_id;
    }


    /**
	 * Deal HTML of the Single Products
     * 
     * @since v.1.1.0
	 * @return STRING | Deal HTML
	 */
    public function get_deals($product, $dealText = '') {
        $html = '';
        $arr = explode("|", $dealText);
        $time = current_time('timestamp');
        $sales = $product->get_sale_price();
		$time_to = strtotime($product->get_date_on_sale_to());

        if ($sales && $time_to > $time) {
            $html .= '<div class="wopb-product-deals" data-date="'.esc_attr(date('Y/m/d', $time_to)).'">';
                $html .= '<div class="wopb-deals-date">';
                    $html .= '<strong class="wopb-deals-counter-days">00</strong>';
                    $html .= '<span class="wopb-deals-periods">'.( isset($arr[0]) ? esc_html($arr[0]) : esc_html__("Days", "product-blocks") ).'</span>';
                $html .= '</div>';
                $html .= '<div class="wopb-deals-hour">';
                    $html .= '<strong class="wopb-deals-counter-hours">00</strong>';
                    $html .= '<span class="wopb-deals-periods">'.( isset($arr[1]) ? esc_html($arr[1]) : esc_html__("Hours", "product-blocks") ).'</span>';
                $html .= '</div>';
                $html .= '<div class="wopb-deals-minute">';
                    $html .= '<strong class="wopb-deals-counter-minutes">00</strong>';
                    $html .= '<span class="wopb-deals-periods">'.( isset($arr[2]) ? esc_html($arr[2]) : esc_html__("Minutes", "product-blocks") ).'</span>';
                $html .= '</div>';
                $html .= '<div class="wopb-deals-seconds">';
                    $html .= '<strong class="wopb-deals-counter-seconds">00</strong>';
                    $html .= '<span class="wopb-deals-periods">'.( isset($arr[3]) ? esc_html($arr[3]) : esc_html__("Seconds", "product-blocks") ).'</span>';
                $html .= '</div>';
            $html .= '</div>';
        }

        return $html;
    }


    /**
	 * Pro Link with Parameters
     * 
     * @since v.1.1.0
	 * @return STRING | Premium Link With Parameters
	 */
    public function get_premium_link( $url = '', $tag = 'go_premium' ) {
        $url = $url ? $url : 'https://www.wpxpo.com/productx/pricing/';
        $affiliate_id = apply_filters( 'wopb_affiliate_id', FALSE );

        $arg = array( 'utm_source' => $tag );
        if ( ! empty( $affiliate_id ) ) {
            $arg[ 'ref' ] = esc_attr( $affiliate_id );
        }
        return add_query_arg( $arg, $url );
        
    }

    /**
	 * Free Pro Check via Function
     * 
     * @since v.1.1.0
	 * @return BOOLEAN
	 */
    public function isPro(){
        return function_exists('wopb_pro_function');
    }


    /**
	 * Flip Image HTML
     * 
     * @since v.1.1.0
	 * @return STRING
	 */
    public function get_flip_image($post_id, $title, $size = 'full', $source = true) {
        $html = '';
        if (wopb_function()->get_setting('wopb_flipimage') == 'true') {
            if( wopb_function()->get_setting('flipimage_source') == 'feature' ) {
                $image_id = get_post_meta( $post_id, '_flip_image_id', true );
                if ($image_id) {
                    $html = $source ? '<img class="wopb-image-hover" alt="'.esc_attr($title).'" src="'.esc_url(wp_get_attachment_image_url( $image_id, $size )).'" />' : esc_url(wp_get_attachment_image_url( $image_id, $size ));
                }
            } else {
                $product = wc_get_product($post_id);
                $attachment_ids = $product->get_gallery_image_ids();
                if (isset($attachment_ids[0])) {
                    if ($attachment_ids[0]) {
                        $html = $source ? '<img class="wopb-image-hover" alt="'.esc_attr($title).'" src="'.esc_url(wp_get_attachment_image_url( $attachment_ids[0], $size )).'" />' : esc_url(wp_get_attachment_image_url( $attachment_ids[0], $size ));
                    }
                }
            }
        }
        return $html;
    }


    /**
	 * Compare HTML Template
     * 
     * @since v.1.1.0
	 * @return STRING
	 */
    public function get_compare($post_id, $compare_active, $layout , $position_left) {
        $html = '';
        
        $button = wopb_function()->get_setting('compare_text');
        $browse = wopb_function()->get_setting('compare_added_text');
        $compare_page = wopb_function()->get_setting('compare_page');
        $action_added = wopb_function()->get_setting('compare_action_added');

        $html .= '<a class="wopb-compare-btn" data-action="add" '.($compare_page && $action_added == 'redirect' ? 'data-redirect="'.esc_url(get_permalink($compare_page)).'"' : '').' data-postid="'.esc_attr($post_id).'">';
            $html .= '<span class="wopb-tooltip-text">';
            $html .= wopb_function()->svg_icon('compare');
            $html .= '<span class="'.( in_array($layout , $position_left) ?'wopb-tooltip-text-left':'wopb-tooltip-text-top').'"><span>'.esc_html($button).'</span><span>'.esc_html($browse).'</span></span>';
            $html .= '</span>';
        $html .= '</a>';
        
        return $html;
    }


    /**
	 * Wishlist HTML Template
     * 
     * @since v.1.1.0
	 * @return STRING
	 */
    public function get_wishlist_html($post_id, $wishlist_active, $layout , $position_left) {
        $html = '';
        
        $button = wopb_function()->get_setting('wishlist_button');
        $browse = wopb_function()->get_setting('wishlist_browse');
        $wishlist_page = wopb_function()->get_setting('wishlist_page');
        $action_added = wopb_function()->get_setting('wishlist_action_added');

            $html .= '<a class="wopb-wishlist-icon wopb-wishlist-add'.($wishlist_active ? ' wopb-wishlist-active' : '').'" data-action="add" '.($wishlist_page && $action_added == 'redirect' ? 'data-redirect="'.esc_url(get_permalink($wishlist_page)).'"' : '').' data-postid="'.esc_attr($post_id).'">';
            $html .= '<span class="wopb-tooltip-text">';    
            $html .= wopb_function()->svg_icon('wishlist');
                $html .= wopb_function()->svg_icon('wishlistFill');   
                    $html .= '<span class="'.( in_array($layout , $position_left) ?'wopb-tooltip-text-left':'wopb-tooltip-text-top').'"><span>'.esc_html($button).'</span><span>'.esc_html($browse).'</span></span>';
                $html .= '</span>';
            $html .= '</a>';
        
        return $html;
    }


    /**
	 * QuickView HTML
     * 
     * @since v.1.1.0
	 * @return STRING
	 */
    public function get_quickview($post, $post_id, $layout, $position_left, $tooltip = true) {
        $html = '';
        if ( wopb_function()->get_setting('quickview_mobile_disable') == 'yes' && wp_is_mobile() ) {} else {
            $html .= '<a data-list="'.esc_attr(implode(',', wopb_function()->get_ids($post))).'" data-postid="'.esc_attr($post_id).'" class="wopb-quickview-btn" href="#">';
                $quickview_text = wopb_function()->get_setting('quickview_text');
                if ($tooltip) {
                    $html .= '<span class="wopb-tooltip-text">';
                        $html .= wopb_function()->svg_icon('quickview');
                        if ($quickview_text) {
                            $html .= '<span class="'.( in_array($layout , $position_left) ?'wopb-tooltip-text-left':'wopb-tooltip-text-top').'">'.esc_html($quickview_text).'</span>';
                        }
                    $html .= '</span>';
                } else {
                    $html .= esc_html($quickview_text);
                }
            $html .= '</a>';
        }
        return $html;
    }

    /**
	 * Get ID from Post Objects
     * 
     * @since v.1.1.0
	 * @return ARRAY
	 */
    public function get_ids($obj) {
        $id = array();
        foreach ($obj->posts as $val) {
            $id[] = $val->ID;
        }
        return $id;
    }


    /**
	 * Wishlist ID
     * 
     * @since v.1.1.0
	 * @return ARRAY
	 */
    public function get_wishlist_id(){
        $wishlist_data = array();
        $user_id = get_current_user_id();
        $required_login = wopb_function()->get_setting('wishlist_require_login');

        if ($required_login == 'yes' && $user_id) {
            $user_data = get_user_meta($user_id, 'wopb_wishlist', true);
            $wishlist_data = $user_data ? $user_data : [];
        } else {
            $wishlist_data = isset($_COOKIE['wopb_wishlist']) ? maybe_unserialize(stripslashes_deep(sanitize_text_field($_COOKIE['wopb_wishlist']))) : array();
        }
        return $wishlist_data;
    }

    public function get_compare_id() {
        $data_id = isset($_COOKIE['wopb_compare']) ? maybe_unserialize(stripslashes_deep(sanitize_text_field($_COOKIE['wopb_compare']))) : array();
        return $data_id;
    }
    

    /**
	 * Get Image HTML
     * 
     * @since v.1.0.0
     * @param MIXED | Attachment ID (INTEGER), Size (STRING), Class (STRING), Alt Image Text (STRING), Type (STRING), Post ID(INTEGER)
	 * @return MIXED
	 */
    public function get_image($attach_id, $size = 'full', $class = '', $alt = '', $type = '', $post_id = 0){
        $alt = $alt ? ' alt="'.esc_attr($alt).'" ' : '';
        $class = $class ? ' class="'.esc_attr($class).'" ' : '';
        if( $this->isPro() ){
            if( ($type == 'flip' || $type == 'gallery') ){
                $html = '';
                $product = new \WC_product($post_id);
                $attachment_ids = $product->get_gallery_image_ids();
                if (count($attachment_ids) > 0) {
                    if ($type == 'flip') {
                        $html = '<img '.$class.$alt.' src="'.esc_url(wp_get_attachment_image_url( $attach_id, $size )).'"/>';
                        $html .= '<span class="image-flip"><img '.$class.$alt.' src="'.esc_url(wp_get_attachment_image_url( $attachment_ids[0], $size )).'"/></span>';
                        return $html;
                    } else {
                        $html = '<img '.$class.$alt.' src="'.esc_url(wp_get_attachment_image_url( $attach_id, $size )).'"/>';
                        $html .= '<span class="image-gallery">';
                        foreach ($attachment_ids as $attachment) {
                            $html .= '<img '.$class.$alt.' src="'.esc_url(wp_get_attachment_image_url( $attachment, $size )).'"/>';
                        }
                        $html .= '</span>';
                        return $html;
                    }
                }
            }
        } else {
            return '<img '.$class.$alt.' src="'.esc_url(wp_get_attachment_image_url( $attach_id, $size )).'" />';
        }
    }


    /**
	 * Get Option Settings
     * 
     * @since v.1.0.0
     * @param STRING | Key of the Option (STRING)
	 * @return MIXED
	 */
    public function get_setting($key = '') {
        $data = $GLOBALS['wopb_settings'];
        if ($key != '') {
            return isset($data[$key]) ? $data[$key] : '';
        } else {
            return $data;
        }
    }

    /**
	 * Set Option Settings
     * 
     * @since v.1.0.0
     * @param STRING | Key of the Option (STRING), Value (STRING)
	 * @return NULL
	 */
    public function set_setting($key = '', $val = '') {
        if($key != ''){
            $data = $GLOBALS['wopb_settings'];
            $data[$key] = $val;
            update_option('wopb_options', $data);
            $GLOBALS['wopb_settings'] = $data;
        }
    }
    

    /**
	 * Setup Initial Data Set
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public function init_set_data(){
        $data = get_option( 'wopb_options', array() );
        $init_data = array(
            'css_save_as' => 'wp_head',
            'preloader_style' => 'style1',
            'preloader_color' => '#ff5845',
            'container_width' => '1140',
            'hide_import_btn' => '',
            'editor_container'=> 'theme_default',
            'wopb_compare'    => 'true',
            'wopb_flipimage'  => 'true',
            'wopb_quickview'  => 'true',
            'wopb_templates'  => 'true',
            'wopb_wishlist'   => 'true',
            'wopb_builder'    => 'true',
            'wopb_variation_swatches' => 'true',
            'save_version' => rand(1, 1000)
        );
        if (empty($data)) {
            update_option('wopb_options', $init_data);
            $GLOBALS['wopb_settings'] = $init_data;
        } else {
            foreach ($init_data as $key => $single) {
                if (!isset($data[$key])) {
                    $data[$key] = $single;
                }
            }
            update_option('wopb_options', $data);
            $GLOBALS['wopb_settings'] = $data;
        }

        if (get_transient('wopb_initial_user_notice')) {
            set_transient( 'wopb_initial_user_notice', 'off', 5 * DAY_IN_SECONDS ); // 5 days notice
        }
    }


    /**
	 * WooCommerce Activaton Check.
     * 
     * @since v.1.0.0
     * @param INTEGER | Product ID (INTEGER), Word Limit (INTEGER)
	 * @return BOOLEAN | Excerpt with Limit
	 */
    public function is_wc_ready(){
        $active = is_multisite() ? array_keys(get_site_option('active_sitewide_plugins', array())) : (array)get_option('active_plugins', array());
        if (file_exists(WP_PLUGIN_DIR.'/woocommerce/woocommerce.php') && in_array('woocommerce/woocommerce.php', $active)) {
            return true;
        } else {
            return false;
        }
    }


    /**
	 * Add to Cart HTML
     * 
     * @since v.1.0.0
     * @param INTEGER | Product ID (INTEGER), Word Limit (INTEGER)
	 * @return STRING | Excerpt with Limit
	 */
    public function excerpt( $post_id, $limit = 55 ) {
        global $product;
        return wp_trim_words( $product->get_short_description() , $limit );
    }


    /**
	 * Add to Cart HTML
     * 
     * @since v.1.0.0
     * @param MIXED | Product Object (OBJECT), Cart Text (STRING)
	 * @return STRING | Add to cart HTML as String
	 */
    public function get_add_to_cart($product , $cart_text = '', $cart_active = '', $layout = '', $position_left = array(), $tooltip = true){

        $data = '';

        if ($this->isPro()) {
            $methods = get_class_methods(wopb_pro_function());
            if (in_array('is_simple_preorder', $methods)) {
                if (wopb_pro_function()->is_simple_preorder()) {
                    $cart_text = wopb_function()->get_setting('preorder_add_to_cart_button_text');
                }
            }
            if (in_array('is_simple_backorder', $methods)) {
                if (wopb_pro_function()->is_simple_backorder()) {
                    $cart_text = wopb_function()->get_setting('backorder_add_to_cart_button_text');
                }
            }
            if (in_array('is_partial_payment', $methods)) {
                if (wopb_pro_function()->is_partial_payment($product)) {
                    $cart_text = wopb_function()->get_setting('partial_payment_label_text');
                }
            }
        }

        $attributes = array(
            'aria-label'       => $product->add_to_cart_description(),
            'data-quantity'    => '1',
            'data-product_id'  => $product->get_id(),
            'data-product_sku' => $product->get_sku(),
            'rel'              => 'nofollow',
            'class'            => 'add_to_cart_button ajax_add_to_cart wopb-cart-normal',
        ); 
        
        $args = array(
            'quantity'   => '1',
            'attributes' => $attributes,
            'class'      => 'add_to_cart_button ajax_add_to_cart'
        );

        if ($layout) {
            $data .= '<span class="wopb-tooltip-text wopb-cart-action" data-postid="'.esc_attr($product->get_id()).'">';
                $inner_html = '';
                if ($tooltip) {
                    $inner_html .= wopb_function()->svg_icon('cart');
                    $inner_html .= '<span class="'.( in_array($layout , $position_left) ?'wopb-tooltip-text-left':'wopb-tooltip-text-top').'">'.esc_html($cart_text && $product->is_type('simple') ? $cart_text : $product->add_to_cart_text()).'</span>';
                } else {
                    $inner_html .= $cart_text && $product->is_type('simple') ? esc_html($cart_text) : esc_html( $product->add_to_cart_text() );
                }
                if($product->is_type('variable')) {
                    $data .= apply_filters(
                        'woocommerce_loop_product_link', // WPCS: XSS ok.
                        sprintf(
                            '<a href="%s" data-stock="%s" data-add-to-cart-text="%s" %s>%s</a>',
                            esc_url( $product->add_to_cart_url() ),
                            esc_attr( $product->get_stock_quantity() ),
                            $cart_text,
                            wc_implode_html_attributes( $attributes ),
                            $inner_html
                        ),
                        $product,
                        $args
                    );
                }else {
                    $data .= apply_filters(
                        'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
                        sprintf(
                            '<a href="%s" data-stock="%s" %s>%s</a>',
                            esc_url( $product->add_to_cart_url() ),
                            esc_attr( $product->get_stock_quantity() ),
                            wc_implode_html_attributes( $attributes ),
                            $inner_html
                        ),
                        $product,
                        $args
                    );
                }
                $data .= '<a href="'.esc_url(wc_get_cart_url()).'" class="wopb-cart-active">';
                    if ($tooltip) {
                        $data .= wopb_function()->svg_icon('viewCart');
                        $data .= '<span class="'.( in_array($layout , $position_left) ?'wopb-tooltip-text-left':'wopb-tooltip-text-top').'">'.($cart_active ? esc_html($cart_active) : esc_html__('View Cart', 'product-blocks')).'</span>';
                    } else {
                        $data .= $cart_active ? esc_html($cart_active) : esc_html__('View Cart', 'product-blocks');
                    }
                $data .= '</a>';
            $data .= '</span>';
        } else {
            $data = apply_filters(
                'woocommerce_loop_add_to_cart_link',
                sprintf(
                    '<a href="%s" data-stock="%s" %s>%s</a>',
                    esc_url( $product->add_to_cart_url() ),
                    esc_attr( $product->get_stock_quantity() ),
                    wc_implode_html_attributes( $attributes ),
                    $cart_text ? esc_html($cart_text) : esc_html( $product->add_to_cart_text() )
                ),
                $product,
                $args
            );
        }
        return '<div class="wopb-product-btn">'.$data.'</div>';
    }


    /**
	 * Slider Responsive Split.
     * 
     * @since v.1.0.0
     * @param MIXED | Category Slug (STRING), Number (INTGER), Type (STRING)
	 * @return STRING | String of the responsive
	 */
    public function slider_responsive_split($data) {
        if( is_string($data) ) {
            return $data.'-'.$data.'-2-1';
        } else {
            $data = (array)$data;
            return $data['lg'].'-'.$data['sm'].'-'.$data['xs'];
        }
    }


    /**
	 * Category Data of the Product.
     * 
     * @since v.1.0.0
     * @param MIXED | Category Slug (STRING), Number (INTGER), Type (STRING)
	 * @return ARRAY | Category Data as Array
	 */
    public function get_category_data($catSlug, $number = 40, $type = ''){
        $data = array();

        if($type == 'child'){
            $image = '';
            if( !empty($catSlug) ){
                foreach ($catSlug as $cat) {
                    $parent_term = get_term_by('slug', $cat, 'product_cat');
                    $term_data = get_terms( 'product_cat', array(
                        'hide_empty' => true,
                        'parent' => $parent_term->term_id
                    ));
                    if( !empty($term_data) ){
                        foreach ($term_data as $terms) {
                            $temp = array();
                            $image = '';
                            $thumbnail_id = get_term_meta( $terms->term_id, 'thumbnail_id', true ); 
                            if( $thumbnail_id ){
                                $image_src = array();
                                $image_sizes = wopb_function()->get_image_size();
                                foreach ($image_sizes as $key => $value) {
                                    $image_src[$key] = wp_get_attachment_image_src($thumbnail_id, $key, false)[0];
                                }
                                $image = $image_src;
                            }
                            $temp['url'] = get_term_link($terms);
                            $temp['term_id'] = $terms->term_id;
                            $temp['name'] = $terms->name;
                            $temp['slug'] = $terms->slug;
                            $temp['desc'] = $terms->description;
                            $temp['count'] = $terms->count;
                            $temp['image'] = $image;
                            $temp['image2'] = $number;
                            $data[] = $temp;
                        }
                    }
                }
            }
            return $data;
        }

        if( !empty($catSlug) ){
            foreach ($catSlug as $cat) {
                $image = '';
                $terms = get_term_by('slug', $cat, 'product_cat');
                $thumbnail_id = get_term_meta( $terms->term_id, 'thumbnail_id', true ); 
                if( $thumbnail_id ){
                    $image_src = array();
                    $image_sizes = wopb_function()->get_image_size();
                    foreach ($image_sizes as $key => $value) {
                        $image_src[$key] = wp_get_attachment_image_src($thumbnail_id, $key, false)[0];
                    }
                    $image = $image_src;
                }
                $temp['url'] = get_term_link($terms);
                $temp['term_id'] = $terms->term_id;
                $temp['name'] = $terms->name;
                $temp['slug'] = $terms->slug;
                $temp['desc'] = $terms->description;
                $temp['count'] = $terms->count;
                $temp['image'] = $image;
                $temp['image1'] = $image;
                $data[] = $temp;
            }
        }else{
            $query = array(
                'hide_empty' => true,
                'number' => $number
            );
            if($type == 'parent'){
                $query['parent'] = 0;     
            }
            $term_data = get_terms( 'product_cat', $query );
            if( !empty($term_data) ){
                foreach ($term_data as $terms) {
                    $temp = array();
                    $image = '';
                    $thumbnail_id = get_term_meta( $terms->term_id, 'thumbnail_id', true ); 
                    if( $thumbnail_id ){
                        $image_src = array();
                        $image_sizes = wopb_function()->get_image_size();
                        foreach ($image_sizes as $key => $value) {
                            $image_src[$key] = wp_get_attachment_image_src($thumbnail_id, $key, false)[0];
                        }
                        $image = $image_src;
                    }
                    $child_query['parent'] = $terms->term_id;
                    $sub_categories = get_terms( 'product_cat', $child_query );
                    $temp['url'] = get_term_link($terms);
                    $temp['term_id'] = $terms->term_id;
                    $temp['name'] = $terms->name;
                    $temp['slug'] = $terms->slug;
                    $temp['desc'] = $terms->description;
                    $temp['count'] = $terms->count;
                    $temp['image'] = $image;
                    $temp['image2'] = $number;
                    $temp['sub_categories'] = $sub_categories;
                    $data[] = $temp;
                }
            }
        }
        return $data;
    }


    /**
	 * Quick Query Builder
     * 
     * @since v.1.0.0
     * @param ARRAY | Query Parameters
	 * @return ARRAY | Query
	 */
    public function get_query($attr) {
        $archive_query = array();
        $builder = isset($attr['builder']) ? $attr['builder'] : '';
        if ($this->is_builder($builder) && !is_product() && !is_cart()) {
            if ($builder) {
                $str = explode('###', $builder);
                if (isset($str[0])) {
                    if ($str[0] == 'taxonomy') {
                        if (isset($str[1]) && isset($str[2])) {
                            $archive_query['tax_query'] = array(
                                array(
                                    'taxonomy' => $str[1],
                                    'field' => 'slug',
                                    'terms' => $str[2]
                                )
                            );
                        }
                    } else if ($str[0] == 'search') {
                        if (isset($str[1])) {
                            $archive_query['s'] = $str[1];
                        }
                    }
                }
            } else {
                global $wp_query;
                $archive_query = $wp_query->query_vars;
            }
            $archive_query['posts_per_page'] = isset($attr['queryNumber']) ? $attr['queryNumber'] : 3;
            $archive_query['paged'] = isset($attr['paged']) ? $attr['paged'] : 1;
            if (isset($attr['queryOffset']) && $attr['queryOffset'] ) {
                $offset = $this->get_offset($attr['queryOffset'], $archive_query);
                $archive_query = array_merge($archive_query, $offset);
            }

            if (isset($_GET['min_price'])) {
                $archive_query['meta_query'][] = array(
                    'key' => '_price',
                    'value' => sanitize_text_field($_GET['min_price']),
                    'compare' => '>=',
                    'type' => 'NUMERIC'
                );
            }
            if (isset($_GET['max_price'])) {
                $archive_query['meta_query'][] = array(
                    'key' => '_price',
                    'value' => sanitize_text_field($_GET['max_price']),
                    'compare' => '<=',
                    'type' => 'NUMERIC'
                );
            }

            //quick query for builder
            if (isset($attr['queryQuick'])) {
                if ($attr['queryQuick'] != '') {
                    $archive_query = wopb_function()->get_quick_query($attr, $archive_query);
                }
            }

            if ( ! empty( $_GET ) ) {
				foreach ( $_GET as $key => $value ) {
					if ( 0 === strpos( $key, 'filter_' ) ) {
						$attribute    = wc_sanitize_taxonomy_name( str_replace( 'filter_', '', $key ) );
						$taxonomy     = wc_attribute_taxonomy_name( $attribute );
						$filter_terms = ! empty( $value ) ? explode( ',', wc_clean( wp_unslash( $value ) ) ) : array();
						if( $key == 'filter_stock_status') {
						    $archive_query['meta_query[]'] = array(
                                'key' => '_stock_status',
                                'value' => explode( ',', wc_clean( wp_unslash( $_GET['filter_stock_status'] ) ) ),
                                'compare' => 'IN',
                            );
                        }else {
						    $archive_query['tax_query'] = array(
                                array(
                                    'taxonomy' => $taxonomy,
                                    'field' => 'slug',
                                    'terms' => $filter_terms,
                                )
                            );
                        }
					}
				}
			}
            return $archive_query;
        }

        $query_args = array(
            'posts_per_page'    => isset($attr['queryNumber']) ? $attr['queryNumber'] : 3,
            'post_type'         => isset($attr['queryType']) ? $attr['queryType'] : 'product',
            'orderby'           => isset($attr['queryOrderBy']) ? $attr['queryOrderBy'] : 'date',
            'order'             => isset($attr['queryOrder']) ? $attr['queryOrder'] : 'DESC',
            'post_status'       => 'publish',
            'paged'             => isset($attr['paged']) ? $attr['paged'] : 1,
        );

        if($query_args['post_type'] === 'product') {
            $query_args['tax_query'][] = [
                'taxonomy' => 'product_visibility',
                'field' => 'name',
                'terms'    => 'exclude-from-catalog',
                'operator' => 'NOT IN',
            ];
        }

        if ( isset($attr['queryOrderBy']) ) {
            switch ($attr['queryOrderBy']) {
                case 'new_old':
                    $query_args['order'] = 'DESC';
                    unset($query_args['orderby']);
                    break;

                case 'old_new':
                    $query_args['order'] = 'ASC';
                    unset($query_args['orderby']);
                    break;

                case 'title':
                    $query_args['orderby'] = 'title';
                    $query_args['order'] = 'ASC';
                    break;

                case 'title_reversed':
                    $query_args['orderby'] = 'title';
                    $query_args['order'] = 'DESC';
                    break;

                case 'price_low':
                    $query_args['meta_key'] = '_price';
                    $query_args['orderby'] = 'meta_value_num';
                    $query_args['order'] = 'ASC';
                    break;

                case 'price_high':
                    $query_args['meta_key'] = '_price';
                    $query_args['orderby'] = 'meta_value_num';
                    $query_args['order'] = 'DESC';
                    break;

                case 'popular':
                    $query_args['meta_key'] = 'total_sales';
                    $query_args['orderby'] = 'meta_value_num';
                    $query_args['order'] = 'DESC';
                    break;

                case 'popular_view':
                    $query_args['meta_key'] = '__product_views_count';
                    $query_args['orderby'] = 'meta_value_num';
                    $query_args['order'] = 'DESC';
                    break;

                case 'date':
                    unset($query_args['orderby']);
                    $query_args['order'] = 'DESC';
                    break;

                default:
                    break;
            }
        }

        if(isset($attr['queryOffset']) && $attr['queryOffset'] && !($query_args['paged'] > 1) ){
            $query_args['offset'] = isset($attr['queryOffset']) ? $attr['queryOffset'] : 0;
        }

        if(isset($attr['queryInclude']) && $attr['queryInclude']){
            $query_args['post__in'] = explode(',', $attr['queryInclude']);
        }

        if(isset($attr['queryExclude']) && $attr['queryExclude']){
            $query_args['post__not_in'] = explode(',', $attr['queryExclude']);
        }


        if ( isset($attr['queryStatus']) ) {
            switch ($attr['queryStatus']) {
                case 'featured':
                    $query_args['post__in'] = wc_get_featured_product_ids();
                    break;

                case 'onsale':
                    unset($query_args['meta_key']);
                    $query_args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
                    break;

                default:
                    break;
            }
        }

        if (isset($attr['queryQuick'])) {
            if ($attr['queryQuick'] != '') {
                $query_args = wopb_function()->get_quick_query($attr, $query_args);
            }
        }

        // Filter Action Taxonomy
        if (isset($attr['custom_action'])) {
            $query_args = wopb_function()->get_filter_query($attr, $query_args);
        } else {
            if ($attr['filterShow']) {
                $showCat = json_decode($attr['filterCat']);
                $showTag = json_decode($attr['filterTag']);

                $flag = $attr['filterType'] == 'product_cat' ? (empty($showCat) ? false : true) : (empty($showTag) ? false : true);

                if (strlen($attr['filterAction']) > 2 && $flag == false) {
                    $arr = json_decode($attr['filterAction']);
                    $attr['custom_action'] = 'custom_action#'.$arr[0];
                    $query_args = wopb_function()->get_filter_query($attr, $query_args);
                } else {
                    if ($attr['filterType'] == 'product_cat') {
                        $var = array('relation'=>'OR');
                        $showCat = isset($attr['queryCatAction']) ? $attr['queryCatAction'] : $showCat;
                        if(count($showCat)) {
                            $var[] = [
                                'taxonomy' => 'product_cat',
                                'field' => 'slug',
                                'terms' => $showCat,
                                'operator' => 'IN'
                            ];
                        }
                        $query_args['tax_query'] = $var;
                    } else {
                        if ($attr['filterType'] == 'product_tag') {
                            $var = array('relation'=>'OR');
                            $showTag = isset($attr['queryTagAction']) ? $attr['queryTagAction'] : $showTag;
                            if(count($showTag)) {
                                $var[] = [
                                    'taxonomy' => 'product_tag',
                                    'field' => 'slug',
                                    'terms' => $showTag,
                                    'operator' => 'IN'
                                ];
                            }
                            $query_args['tax_query'] = $var;
                        }
                    }
                }
            } else {
                $queryCat = json_decode($attr['queryCat']);
                if (!empty($queryCat)) {
                    $var = array('relation'=>'OR');
                    $var[] = [
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => $queryCat,
                        'operator' => 'IN'
                    ];
                    $query_args['tax_query'] = $var;
                }
            }
        }

        if(isset($attr['queryExcludeStock'])){
            $var = isset($query_args['tax_query']) ? $query_args['tax_query'] : array();
            if ($attr['queryExcludeStock']) {
                $var['relation'] = 'AND';
                $var[] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => array('outofstock'),
                    'operator' => 'NOT IN'
                );
                $query_args['tax_query'] = $var;
            }
        }

        if(isset($attr['product_filters'])) { // Product Filter Query(Feature)
            $product_filters = $attr['product_filters'];
            if(!empty($product_filters['search'])) {
               $query_args['filter_search_key'] = sanitize_text_field($product_filters['search']);
           }
           if(!empty($product_filters['price'])) {
               $min_price = sanitize_text_field($product_filters['price']['minPrice']);
               $max_price = sanitize_text_field($product_filters['price']['maxPrice']);
               if(wopb_function()->currency_switcher_data()) {
                   $min_price = wopb_function()->currency_switcher_data($min_price, 'default')['value'];
                   $max_price = wopb_function()->currency_switcher_data($max_price, 'default')['value'];
               }
                $query_args['meta_query'][] = [
                    'key' => '_price',
                    'value' => [ $min_price, $max_price ],
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC'
                ];
           }
           if(!empty($product_filters['status'])) {
                $query_args['meta_query'][] = [
                    'key' => '_stock_status',
                    'value' => $product_filters['status'],
                    'operator' => 'IN'
                ];
           }
           if(!empty($product_filters['rating'])) {
               $query_args['meta_query'][] = [
                    'key' => '_wc_average_rating',
                    'value' => $product_filters['rating'],
                    'type' => 'numeric',
                    'compare' => 'IN'
                ];
           }
           if(!empty($product_filters['product_taxonomy'])) {
               $taxonomies = $product_filters['product_taxonomy'];
               foreach ($taxonomies as $taxonomy) {
                    $query_args['tax_query'][] = [
                        'taxonomy' => $taxonomy['taxonomy'],
                        'field' => 'id',
                        'terms' => $taxonomy['term_ids'],
                        'operator' => 'IN'
                    ];
               }
           }
           $query_args['orderby'] = 'title';
           $query_args['order'] = 'ASC';
           if($product_filters['sorting']) {
               switch ($product_filters['sorting']) {
                   case 'default':
                       $query_args['orderby'] = 'title';
                       $query_args['order'] = 'ASC';
                       break;
                   case 'popular':
                       $query_args['meta_key'] = 'total_sales';
                       $query_args['orderby'] = 'meta_value_num';
                       $query_args['order'] = 'DESC';
                       break;

                   case 'latest':
                       $query_args['orderby'] = 'date ID';
                       $query_args['order'] = 'DESC';
                       break;

                   case 'rating':
                       $query_args['meta_key'] = '_wc_average_rating';
                       $query_args['orderby'] = 'meta_value meta_value_num';
                       $query_args['order'] = 'DESC';
                       break;

                   case 'price_low':
                       $query_args['meta_key'] = '_price';
                       $query_args['orderby'] = 'meta_value_num';
                       $query_args['order'] = 'ASC';
                       break;

                   case 'price_high':
                       $query_args['meta_key'] = '_price';
                       $query_args['orderby'] = 'meta_value_num';
                       $query_args['order'] = 'DESC';
                       break;

                   default:
                       break;
               }
           }
            add_filter( 'posts_where', [$this, 'custom_query_product_filter'], 1000,2 );
        }

        $query_args['wpnonce'] = wp_create_nonce( 'wopb-nonce' );
        
        return $query_args;
    }


    /**
	 * Filter Query Builder
     * 
     * @since v.1.1.0
	 * @return ARRAY | Return part of the filter query
	 */
    public function get_filter_query($prams, $args) {
        
        list($key, $value) = explode("#", $prams['custom_action']);

        unset($args['tax_query']);
        unset($args['post__not_in']);
        unset($args['post__in']);

        switch ($value) {
            case 'top_sale':
                $args['meta_key'] = 'total_sales';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
            case 'popular':
                $args['meta_key'] = '__product_views_count';
                $args['orderby'] = 'meta_value meta_value_num';
                $args['order'] = 'DESC';
                break;
            case 'on_sale':
                unset($args['meta_key']);
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
                $args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
                break;
            case 'most_rated':
                $args['meta_key'] = '_wc_review_count';
                $args['orderby'] = 'meta_value meta_value_num';
                $args['order'] = 'DESC';
                break;
            case 'top_rated':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby'] = 'meta_value meta_value_num';
                $args['order'] = 'DESC';
                break;
            case 'featured':
                $args['post__in'] = wc_get_featured_product_ids();
                break;
            case 'arrival':
                $args['order'] = 'DESC';
                break;
            default:
                # code...
                break;
        }
    
        return $args;
    }


    /**
	 * Quick Query Builder Attribute Builder
     * 
     * @since v.1.1.0
	 * @return ARRAY | Return part of the filter query
	 */
    public function get_quick_query($prams, $args) {
        switch ($prams['queryQuick']) {
            case 'sales_day_1':
                $args['post__in'] = wopb_function()->get_best_selling_products( date('Y-m-d H:i:s', strtotime("-1 days") ) );
                $args['order'] = 'DESC';
                break;
            case 'sales_day_7':
                $args['post__in'] = wopb_function()->get_best_selling_products( date('Y-m-d H:i:s', strtotime("-7 days") ) );
                $args['order'] = 'DESC';
                break;
            case 'sales_day_30':
                $args['post__in'] = wopb_function()->get_best_selling_products( date('Y-m-d H:i:s', strtotime("-1 days") ) );
                $args['order'] = 'DESC';
                break;
            case 'sales_day_90':
                $args['post__in'] = wopb_function()->get_best_selling_products( date('Y-m-d H:i:s', strtotime("-90 days") ) );
                $args['order'] = 'DESC';
                break;
            case 'sales_day_all':
                $args['meta_key'] = 'total_sales';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
            case 'view_day_all':
                $args['meta_key'] = '__product_views_count';
                $args['orderby'] = 'meta_value meta_value_num';
                $args['order'] = 'DESC';
                break;
            case 'most_rated':
                $args['meta_key'] = '_wc_review_count';
                $args['orderby'] = 'meta_value meta_value_num';
                $args['order'] = 'DESC';
                break;
            case 'top_rated':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby'] = 'meta_value meta_value_num';
                $args['order'] = 'DESC';
                break;
            case 'random_post':
                $args['orderby'] = 'rand';
                $args['order'] = 'ASC';
                break;
            case 'random_post_7_days':
                $args['orderby'] = 'rand';
                $args['order'] = 'ASC';
                $args['date_query'] = array( array( 'after' => '1 week ago') );
                break;
            case 'random_post_30_days':
                $args['orderby'] = 'rand';
                $args['order'] = 'ASC';
                $args['date_query'] = array( array( 'after' => '1 month ago') );
                break;
            case 'related_tag':
                global $post;
                if (isset($post->ID)) {
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'product_tag',
                            'terms'    => $this->get_terms_id($post->ID, 'product_tag'),
                            'field'    => 'term_id',
                        )
                    );
                    $args['post__not_in'] = array($post->ID);
                }
                break;
            case 'related_category':
                global $post;
                if (isset($post->ID)) {
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'product_cat',
                            'terms'    => $this->get_terms_id($post->ID, 'product_cat'),
                            'field'    => 'term_id',
                        )
                    );
                    $args['post__not_in'] = array($post->ID);
                }
                break;
            case 'related_cat_tag':
                global $post;
                if (isset($post->ID)) {
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'product_tag',
                            'terms'    => $this->get_terms_id($post->ID, 'product_tag'),
                            'field'    => 'term_id',
                        ),
                        array(
                            'taxonomy' => 'product_cat',
                            'terms'    => $this->get_terms_id($post->ID, 'product_cat'),
                            'field'    => 'term_id',
                        )
                    );
                    $args['post__not_in'] = array($post->ID);
                }
                break;
            case 'upsells':
                global $post;
                $backend = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';
                if ($backend != 'edit' && isset($post->ID)) {
                    if (!$product) {
                        $product = wc_get_product($post->ID);
                    }
                    if ($product) {
                        $upsells = $product->get_upsell_ids();
                        $args['ignore_sticky_posts'] = 1;
                        if ($upsells) {
                            $args['post__in'] = $upsells;
                            $args['post__not_in'] = array($post->ID);
                        } else {
                            $args['post__in'] = array(999999);
                        }
                    }
                }
                break;
            case 'crosssell':
                global $post;
                global $product;
                $backend = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';
                if ($backend != 'edit' && isset($post->ID)) {
                    if (!$product) {
                        $product = wc_get_product($post->ID);
                    }

                     if (wopb_function()->is_builder() && is_cart()) {
                         if(WC()->cart->get_cross_sells()) {
                            $args['post__in'] = WC()->cart->get_cross_sells();
                         }else {
                             $args['post_type'] = 'cart_cross_sell_empty';
                         }
                     }elseif ($product) {
                        $crosssell = $product->get_cross_sell_ids();
                        $args['ignore_sticky_posts'] = 1;
                        if ($crosssell) {
                            $args['post__in'] = $crosssell;
                            $args['post__not_in'] = array($post->ID);
                        } else {
                            $args['post__in'] = array(999999);
                        }
                    }
                }
                break;
            case 'recent_view':
                global $post;
                $viewed_products = ! empty( $_COOKIE['__wopb_recently_viewed'] ) ? (array) explode( '|', sanitize_text_field($_COOKIE['__wopb_recently_viewed']) ) : array();
                $args['ignore_sticky_posts'] = 1;
                if (!empty($viewed_products)) {
                    $args['post__in'] = $viewed_products;
                    $args['post__not_in'] = array($post->ID);
                } else {
                    $args['post__in'] = array(999999);
                }
            break;
        }
        return $args;
    }

    public function get_terms_id($id, $type) {
        $data = array();
        $arr = get_the_terms($id, $type);
        if (is_array($arr)) {
            foreach ($arr as $key => $val) {
                $data[] = $val->term_id;
            }
        }
        return $data;
    }


    /**
	 * Best Selling Product Raw Query
     * 
     * @since v.1.1.0
	 * @return ARRAY | Return Best Selling Products
	 */
    public function get_best_selling_products($date) {
        global $wpdb;
        return (array) $wpdb->get_results("
            SELECT p.ID as id, COUNT(oim2.meta_value) as count
            FROM {$wpdb->prefix}posts p
            INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim
                ON p.ID = oim.meta_value
            INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim2
                ON oim.order_item_id = oim2.order_item_id
            INNER JOIN {$wpdb->prefix}woocommerce_order_items oi
                ON oim.order_item_id = oi.order_item_id
            INNER JOIN {$wpdb->prefix}posts as o
                ON o.ID = oi.order_id
            WHERE p.post_type = 'product'
            AND p.post_status = 'publish'
            AND o.post_status IN ('wc-processing','wc-completed')
            AND o.post_date >= '$date'
            AND oim.meta_key = '_product_id'
            AND oim2.meta_key = '_qty'
            GROUP BY p.ID
            ORDER BY COUNT(oim2.meta_value) + 0 DESC
        ");
    }


    /**
	 * Get Number of the Page
     * 
     * @since v.1.0.0
     * @param MIXED | NUMBER of QUERY (ARRAY), NUMBER OF POST (INT)
	 * @return INTEGER | Number of Page
	 */
    public function get_page_number($attr, $post_number) {
        if($post_number > 0){
            $post_per_page = isset($attr['queryNumber']) ? $attr['queryNumber'] : 3;
            $pages = ceil($post_number/$post_per_page);
            return $pages ? $pages : 1;
        }else{
            return 1;
        }
    }


    /**
	 * List of Image Size
     * 
     * @since v.1.0.0
	 * @return ARRAY | Image Size Name and Slug 
	 */
    public function get_image_size() {
        $sizes = get_intermediate_image_sizes();
        $filter = array('full' => 'Full');
        foreach ($sizes as $value) {
            $filter[$value] = ucwords(str_replace(array('_', '-'), array(' ', ' '), $value));
        }
        return $filter;
    }


    /**
	 * Pagination HTML Generator
     *
     * @since v.1.0.0
     * @param STRING | PAGE, NAV TYPE, Pagination Text
	 * @return STRING | Pagination HTML as String
	 */
    public function pagination($pages = '', $paginationNav = 'textArrow', $paginationText = '', $attr = []) {
        $html = '';
        $showitems = 3;
        $paged = is_front_page() ? get_query_var('page') : get_query_var('paged');
        $paged = $paged ? $paged : 1;
        if($pages == '') {
            global $wp_query;
            $pages = $wp_query->max_num_pages;
            if(!$pages) {
                $pages = 1;
            }
        }
        $data = ($paged>=3?[($paged-1),$paged,$paged+1]:[1,2,3]);

        $paginationText = explode('|', $paginationText);

        $prev_text = isset($paginationText[0]) ? $paginationText[0] : esc_html__('Previous', 'product-blocks');
        $next_text = isset($paginationText[1]) ? $paginationText[1] : esc_html__('Next', 'product-blocks');
 
        if(1 != $pages) {
            $html .= '<ul class="wopb-pagination">';            
                $display_none = 'style="display:none"';
                if($pages > 4) {
                    $html .= '<li class="wopb-prev-page-numbers" '.($paged==1?$display_none:"").'><a href="'.esc_url($this->get_pagenum_link($paged-1, $attr)).'">'.wopb_function()->svg_icon('leftAngle2').' '.($paginationNav == 'textArrow' ? esc_html($prev_text) : "").'</a></li>';
                }
                if($pages > 4){
                    $html .= '<li class="wopb-first-pages" '.($paged<2?$display_none:"").' data-current="1"><a href="'.esc_url($this->get_pagenum_link(1, $attr)).'">1</a></li>';
                }
                if($pages > 4){
                    $html .= '<li class="wopb-first-dot" '.($paged<2? $display_none : "").'><a href="#">...</a></li>';
                }
                foreach ($data as $i) {
                    if($pages >= $i){
                        $html .= ($paged == $i) ? '<li class="wopb-center-item pagination-active" data-current="'.esc_attr($i).'"><a href="'.esc_url($this->get_pagenum_link($i, $attr)).'">'.esc_html($i).'</a></li>':'<li class="wopb-center-item" data-current="'.esc_attr($i).'"><a href="'.esc_url($this->get_pagenum_link($i, $attr)).'">'.esc_html($i).'</a></li>';
                    }
                }
                if($pages > 4){
                    $html .= '<li class="wopb-last-dot" '.($pages<=$paged+1?$display_none:"").'><a href="#">...</a></li>';
                }
                if($pages > 4){
                    $html .= '<li class="wopb-last-pages" '.($pages<=$paged+1?$display_none:"").' data-current="'.esc_attr($pages).'"><a href="'.esc_url($this->get_pagenum_link($pages, $attr)).'">'.esc_html($pages).'</a></li>';
                }
                if ($paged != $pages) {
                    $html .= '<li class="wopb-next-page-numbers"><a href="'.esc_url($this->get_pagenum_link($paged + 1, $attr)).'">'.($paginationNav == 'textArrow' ? esc_html($next_text) : "").wopb_function()->svg_icon('rightAngle2').'</a></li>';
                }
            $html .= '</ul>';
        }
        return $html;
    }


    /**
	 * Excerpt Word Cutter
     * 
     * @since v.1.0.0
     * @param INTEGER | Length of the Excerpt
	 * @return STRING | Return Excerpt of the Content
	 */
    public function excerpt_word($charlength = 200) {
        $html = '';
        $charlength++;
        $excerpt = get_the_excerpt();
        if ( mb_strlen( $excerpt ) > $charlength ) {
            $subex = mb_substr( $excerpt, 0, $charlength - 5 );
            $exwords = explode( ' ', $subex );
            $excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
            if ( $excut < 0 ) {
                $html = mb_substr( $subex, 0, $excut );
            } else {
                $html = $subex;
            }
            $html .= '...';
        } else {
            $html = $excerpt;
        }
        return $html;
    }


    /**
	 * Taxonomoy Data List.
     * 
     * @since v.1.0.0
     * @param STRING | Taxonomy Name
	 * @return ARRAY | Taxonomy Slug and Name as a ARRAY
	 */
    public function taxonomy( $prams = 'product_cat' ) {
        $data = array();
        $terms = get_terms( $prams, array(
            'hide_empty' => true,
        ));
        if( !empty($terms) ){
            foreach ($terms as $val) {
                $data[urldecode_deep($val->slug)] = $val->name;
            }
        }
        return $data;
    }

    /**
	 * Stock Status Data List.
     *
     * @since v.1.0.0
     * @param STRING | Taxonomy Name
	 * @return ARRAY | Taxonomy Slug and Name as a ARRAY
	 */
    public function get_stock_status_data($params = []) {
        $stock_status = array();
        foreach ( wc_get_product_stock_status_options() as $key => $status ) {
            $temp = [];
			$temp['key'] = $key;
			$temp['name'] = $status;
			$temp['count'] = $this->generate_stock_status_count_query($key, $params);
			$stock_status[] = $temp;
		}
        return $stock_status;
    }

    /**
	 * Generate calculate query by stock status.
	 * @return false|string
	 */
	private function generate_stock_status_count_query( $status, $params = [] ) {
		global $wpdb;
		$status = esc_sql( $status );
		$join = "INNER JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id ";
		$where = "WHERE posts.post_type = 'product' ";
		if(isset($params['taxonomy']) && isset($params['taxonomy_term_id'])) {
		    $join .= "INNER JOIN {$wpdb->term_relationships} as term_relationships ON posts.ID = term_relationships.object_id ";
		    $join .= "INNER JOIN {$wpdb->term_taxonomy} as term_taxonomy ON term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id ";
		    $where .= "AND term_taxonomy.taxonomy = '{$params['taxonomy']}' AND term_taxonomy.term_taxonomy_id = '{$params['taxonomy_term_id']}' ";
        }
		$result = "
			SELECT COUNT( DISTINCT posts.ID ) as status_count
			FROM {$wpdb->posts} as posts
			{$join}
            AND postmeta.meta_key = '_stock_status'
            AND postmeta.meta_value = '{$status}'
			{$where}
		";
		return $wpdb->get_row( $result )->status_count;
	}

    /**
	 * Product Taxonomy Data List.
     *
     * @since v.1.0.0
     * @param STRING | Taxonomy Name
	 * @return ARRAY | Taxonomy Slug and Name as a ARRAY
	 */
    public function get_product_taxonomies() {
        $product_taxonomies = array();
        $object_taxonomies =  array_diff(get_object_taxonomies('product'), ['product_type', 'product_visibility', 'product_shipping_class']);
        foreach ( $object_taxonomies as $key ) {
            $params = [
                'taxonomy' => $key,
                'product_visibility' => true,
            ];
            $taxonomy = get_taxonomy($key);
			$taxonomy->terms = $this->taxonomy_terms_tree($params);
            if($this->get_attribute_by_taxonomy($key)) {
                $taxonomy->attribute = $this->get_attribute_by_taxonomy($key);
            }
			$product_taxonomies[] = $taxonomy;
		}
        return $product_taxonomies;
    }

    public function taxonomy_terms_tree($params) {
        $taxonomy_terms = [];
        $terms = get_terms($params['taxonomy'],
                    array (
                        'hide_empty' => true,
                        'parent' => isset($params['parent_id']) ? $params['parent_id'] : 0,
                        'orderby' => 'name'
                    )
                );
        if(count($terms) > 0) {
            foreach ($terms as $term) {
                $query_args = array(
                    'posts_per_page' => -1,
                    'post_type' => 'product',
                    'post_status' => 'publish',
                );
                $query_args['tax_query'][] = array(
                    'taxonomy' => $params['taxonomy'],
                    'field' => 'term_id',
                    'terms'    => $term->term_id,
                );
                if(isset($params['product_visibility']) && $params['product_visibility'] == true) {
                    $query_args['tax_query'][] = array(
                        'taxonomy' => 'product_visibility',
                        'field' => 'name',
                        'terms' => 'exclude-from-catalog',
                        'operator' => 'NOT IN',
                    );
                }
                $term->count = count(wc_get_products($query_args));
                if(isset($params['product_visibility']) && $params['product_visibility'] == true && $term->count == 0) {
                    continue;
                }

                $params['parent_id'] = $term->term_id;
                if($this->taxonomy_terms_tree($params)) {
                    $term->child_terms = $this->taxonomy_terms_tree($params);
                }
                $taxonomy_terms[] = $term;
            }
        }
        return $taxonomy_terms;
    }

    public function get_attribute_by_taxonomy($taxonomy) {
        foreach ( wc_get_attribute_taxonomies() as $attribute ) {
            if(wc_attribute_taxonomy_name($attribute->attribute_name) == $taxonomy) {
                return $attribute;
            }
		}
    }

    /**
	 * Filter HTML Generator
     * 
     * @since v.1.0.0
     * @param STRING | TEXT, TYPE, CATEGORY, TAG
	 * @return STRING | Filter HTML as String
	 */
    public function filter($filterText = '', $filterType = '', $filterCat = '[]', $filterTag = '[]', $action = '[]', $actionText = '', $noAjax = false, $filterMobileText = '...', $filterMobile = true){
        $html = '';

        $filterData = [
            'top_sale' => 'Top Sale',
            'popular' => 'Popular',
            'on_sale' => 'On Sale',
            'most_rated' => 'Most Rated',
            'top_rated' => 'Top Rated',
            'featured' => 'Featured',
            'arrival' => 'New Arrival',
        ];

        $arr = explode("|", $actionText);
        if (count($arr) == 7) {
            foreach (array_keys($filterData) as $k => $v) {
                $filterData[$v] = $arr[$k];
            }
        }
        $count = $noAjax ? 1 : 0;
        
        $html .= '<ul '.($filterMobile ? 'class="wopb-flex-menu"' : '').' data-name="'.($filterMobileText ? $filterMobileText : '&nbsp;').'">';
            if($filterText && strlen($action) <= 2){
                $class = '';
                if ($count == 0) {
                    $count = 1;
                    $class = 'class="filter-active"';
                }
                $html .= '<li class="filter-item"><a '.$class.' data-taxonomy="" href="#">'.esc_html($filterText).'</a></li>';
            }
            if ($filterType == 'product_cat') {
                $cat = wopb_function()->taxonomy('product_cat');
                foreach (json_decode($filterCat) as $val) {
                    $class = '';
                    if ($count == 0) {
                        $count = 1;
                        $class = 'class="filter-active"';
                    }
                    $html .= '<li class="filter-item"><a '.$class.' data-taxonomy="'.esc_attr($val=='all'?'':$val).'" href="#">'.esc_html(isset($cat[$val]) ? $cat[$val] : $val).'</a></li>';
                }
            } else {
                $tag = wopb_function()->taxonomy('product_tag');
                foreach (json_decode($filterTag) as $val) {
                    $class = '';
                    if ($count == 0) {
                        $count = 1;
                        $class = 'class="filter-active"';
                    }
                    $html .= '<li class="filter-item"><a '.$class.' data-taxonomy="'.esc_attr($val=='all'?'':$val).'" href="#">'.esc_html(isset($tag[$val]) ? $tag[$val] : $val).'</a></li>';
                }
            }

            if (strlen($action) > 2) {
                foreach (json_decode($action) as $val) {
                    $class = '';
                    if ($count == 0) {
                        $count = 1;
                        $class = 'class="filter-active"';
                    }
                    $html .= '<li class="filter-item"><a '.$class.' data-taxonomy="custom_action#'.esc_attr($val).'" href="#">'.esc_html($filterData[$val]).'</a></li>';
                }
            }

        $html .= '</ul>';
        return $html;
    }


    /**
	 * Plugins Pro Version is Activated or not.
     * 
     * @since v.1.0.0
     * @param STRING | Name of the icon
	 * @return ARRAY | SVG icon URL and name
	 */
    public function svg_icon($icons = 'view'){
        $icon_lists = array(
            'eye' 			=> file_get_contents(WOPB_PATH.'assets/img/svg/eye.svg'),
            'user' 			=> file_get_contents(WOPB_PATH.'assets/img/svg/user.svg'),
            'calendar'      => file_get_contents(WOPB_PATH.'assets/img/svg/calendar.svg'),
            'comment'       => file_get_contents(WOPB_PATH.'assets/img/svg/comment.svg'),
            'book'  		=> file_get_contents(WOPB_PATH.'assets/img/svg/book.svg'),
            'tag'           => file_get_contents(WOPB_PATH.'assets/img/svg/tag.svg'),
            'clock'         => file_get_contents(WOPB_PATH.'assets/img/svg/clock.svg'),
            'leftAngle'     => file_get_contents(WOPB_PATH.'assets/img/svg/leftAngle.svg'),
            'rightAngle'    => file_get_contents(WOPB_PATH.'assets/img/svg/rightAngle.svg'),
            'leftAngle2'    => file_get_contents(WOPB_PATH.'assets/img/svg/leftAngle2.svg'),
            'rightAngle2'   => file_get_contents(WOPB_PATH.'assets/img/svg/rightAngle2.svg'),
            'leftArrowLg'   => file_get_contents(WOPB_PATH.'assets/img/svg/leftArrowLg.svg'),
            'refresh'       => file_get_contents(WOPB_PATH.'assets/img/svg/refresh.svg'),
            'rightArrowLg'  => file_get_contents(WOPB_PATH.'assets/img/svg/rightArrowLg.svg'),
            'wishlist'      => file_get_contents(WOPB_PATH.'assets/img/svg/wishlist.svg'),
            'wishlistFill'  => file_get_contents(WOPB_PATH.'assets/img/svg/wishlistFill.svg'),
            'compare'       => file_get_contents(WOPB_PATH.'assets/img/svg/compare.svg'),
            'cart'          => file_get_contents(WOPB_PATH.'assets/img/svg/cart.svg'),
            'quickview'     => file_get_contents(WOPB_PATH.'assets/img/svg/quickview.svg'),
            'viewCart'      => file_get_contents(WOPB_PATH.'assets/img/svg/viewCart.svg'),
            'share'         => file_get_contents(WOPB_PATH.'assets/img/blocks/builder/social_share/share.svg'),
            'facebook'      => file_get_contents(WOPB_PATH.'assets/img/blocks/builder/social_share/facebookIcon.svg'),
            'linkedin'      => file_get_contents(WOPB_PATH.'assets/img/blocks/builder/social_share/linkedinIcon.svg'),
            'mail'          => file_get_contents(WOPB_PATH.'assets/img/blocks/builder/social_share/mailIcon.svg'),
            'messenger'     => file_get_contents(WOPB_PATH.'assets/img/blocks/builder/social_share/messengerIcon.svg'),
            'reddit'        => file_get_contents(WOPB_PATH.'assets/img/blocks/builder/social_share/redditIcon.svg'),
            'twitter'       => file_get_contents(WOPB_PATH.'assets/img/blocks/builder/social_share/twitterIcon.svg'),
            'whatsapp'      => file_get_contents(WOPB_PATH.'assets/img/blocks/builder/social_share/whatsappIcon.svg'),
            'pinterest'     => file_get_contents(WOPB_PATH.'assets/img/blocks/builder/social_share/pinIcon.svg'),
            'skype'         => file_get_contents(WOPB_PATH.'assets/img/blocks/builder/social_share/skype.svg'),
        );
        return $icon_lists[ $icons ];
    }
    

    /**
	 * Plugins Pro Version is Activated or not.
     * 
     * @since v.1.0.0
	 * @return BOOLEAN
	 */
    public function isActive(){
        $active_plugins = (array) get_option( 'active_plugins', array() );
        if (file_exists(WP_PLUGIN_DIR.'/product-blocks-pro/product-blocks-pro.php')) {
            if ( ! empty( $active_plugins ) && in_array( 'product-blocks-pro/product-blocks-pro.php', $active_plugins ) ) {
                return true;
            } else {
                return false;
            }
		} else {
            return false;
        }
    }

    /**
	 * Check License Status
     * 
     * @since v.2.0.7
	 * @return BOOLEAN | Is pro license active or not
	 */
    public function is_lc_active() {
        if ($this->isPro()) {
            return get_option('edd_wopb_license_status') == 'valid' ? true : false;
        }
        if (get_transient( 'wopb_theme_enable' ) == 'integration') {
            return true;
        }
        return false;
    }


    /**
	 * All Pages as Array.
     * 
     * @since v.1.1.0
     * @param BOOLEAN | With empty return
	 * @return ARRAY | With Page Name and ID
	 */
    public function all_pages( $empty = false){
        $arr = $empty ? array('' => __('- Select -', 'product-blocks') ) : array();
        $pages = get_pages(); 
        foreach ( $pages as $page ) {
            $arr[$page->ID] = $page->post_title;
        }
        return $arr;
    }


    public function get_taxonomy_list($default = false) {
        $default_remove = $default ? array('product_cat', 'product_tag') : array('product_type', 'pa_color', 'pa_size');
        $taxonomy = get_taxonomies( array('object_type' => array('product')) );
        foreach ($taxonomy as $key => $val) {
            if( in_array($key, $default_remove) ){
                unset( $taxonomy[$key] );
            }
        }
        return array_keys($taxonomy);
    }
    
    public function in_string_part($part, $data, $isValue = false) {
        $return = false;
        foreach ($data as $val) {
            if (strpos($val, $part) !== false) {
                $return = $isValue ? $val : true;
                break;
            }
        }
        return $return;
    }

    // Template Conditions
    public function conditions( $type = 'return' ) {
        $page_id = '';
        $conditions = get_option('wopb_builder_conditions', array());
        $post_type = isset($_GET['post_type']) ? sanitize_key($_GET['post_type']) : '';

        //Archive Builder
        if (isset($conditions['archive'])) {
            if (!empty($conditions['archive'])) {
                foreach ($conditions['archive'] as $key => $val) {
                    if (!is_shop() && is_archive()) {
                        if (in_array('include/archive', $val)) {
                            if ('publish' == get_post_status($key)) {
                                $page_id = $key;
                            }
                        }
                        if (in_array('exclude/archive', $val)) {
                            if ('publish' == get_post_status($key)) {
                                $page_id = '';
                            }
                        }
                        if (is_product_category()) {
                            $taxonomy = get_queried_object();
                            if (in_array('include/archive/product_cat', $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = $key;
                                }
                            }
                            if (in_array('exclude/archive/product_cat', $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = '';
                                }
                            }
                            if ($this->in_string_part('include/archive/product_cat/'.$taxonomy->term_id, $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = $key;
                                }
                            }
                            if (in_array('include/archive/product_cat/'.$taxonomy->term_id, $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = $key;
                                }
                            }
                            if (in_array('exclude/archive/product_cat/'.$taxonomy->term_id, $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = '';
                                }
                            }
                            if ($this->in_string_part('any_child_of', $val)) {
                                if (in_array('include/archive/any_child_of_product_cat', $val)) {
                                    if ('publish' == get_post_status($key)) {
                                        $page_id = $key;
                                    }
                                }
                                if (in_array('exclude/archive/any_child_of_product_cat', $val)) {
                                    if ('publish' == get_post_status($key)) {
                                        $page_id = '';
                                    }
                                }
                                $data = $this->in_string_part('/archive/any_child_of_product_cat/', $val, true);
                                if ($data) {
                                    $data = explode("/",$data);
                                    if (isset($data[3]) && $data[3]) {
                                        if (term_is_ancestor_of($data[3], $taxonomy->term_id, 'product_cat')) {
                                            if ('publish' == get_post_status($key)) {
                                                $page_id = $data[0] == 'exclude' ? '' : $key;
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($this->in_string_part('child_of', $val)) {
                                    if (in_array('include/archive/child_of_product_cat', $val)) {
                                        if ('publish' == get_post_status($key)) {
                                            $page_id = $key;
                                        }
                                    }
                                    if (in_array('exclude/archive/child_of_product_cat', $val)) {
                                        if ('publish' == get_post_status($key)) {
                                            $page_id = '';
                                        }
                                    }
                                    if (in_array('include/archive/child_of_product_cat/'.$taxonomy->parent, $val)) {
                                        if ('publish' == get_post_status($key)) {
                                            $page_id = $key;
                                        }
                                    }
                                    if (in_array('exclude/archive/child_of_product_cat/'.$taxonomy->parent, $val)) {
                                        if ('publish' == get_post_status($key)) {
                                            $page_id = '';
                                        }
                                    }
                                }
                            }
                        } else if (is_product_tag()) {
                            if (in_array('include/archive/product_tag', $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = $key;
                                }
                            }
                            if (in_array('exclude/archive/product_tag', $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = '';
                                }
                            }
                            $taxonomy = get_queried_object();
                            if ($this->in_string_part('include/archive/product_tag/'.$taxonomy->term_id, $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = $key;
                                }
                            }
                            if (in_array('exclude/archive/product_tag/'.$taxonomy->term_id, $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = '';
                                }
                            }
                        } else if ($this->in_string_part('include/archive', $val)) {
                            $taxonomy_list = $this->get_taxonomy_list(true);
                            foreach ($taxonomy_list as $value) {
                                if (in_array('include/archive/'.$value, $val)) {
                                    if ('publish' == get_post_status($key)) {
                                        $page_id = $key;
                                    }
                                }
                                $taxonomy = get_queried_object();
                                if ($this->in_string_part('include/archive/'.$value.'/'.$taxonomy->term_id, $val)) {
                                    if ('publish' == get_post_status($key)) {
                                        $page_id = $key;
                                    }
                                }
                            }
                        }
                    } else if (is_cart()) {
                        if (in_array('filter/cart', $val)) {
                            if ('publish' == get_post_status($key)) {
                                $page_id = $key;
                            }
                        }
                    } else if (is_checkout() && is_wc_endpoint_url( 'order-received' )) {
                        if (in_array('filter/thankyou', $val)) {
                            if ('publish' == get_post_status($key)) {
                                $page_id = $key;
                            }
                        }
                    }else if (is_shop() && !is_search()) {
                        if (in_array('filter/shop', $val)) {
                            if ('publish' == get_post_status($key)) {
                                $page_id = $key;
                            }
                        }
                    } else if ($post_type == 'product' && is_search()) {
                        if (in_array('include/archive/search', $val)) {
                            if ('publish' == get_post_status($key)) {
                                $page_id = $key;
                            }
                        }
                    } else if (is_product()) {
                        if (in_array('include/allsingle', $val)) {
                            if ('publish' == get_post_status($key)) {
                                $page_id = $key;
                            }
                        } else {
                            foreach ($val as $value) {
                                $list = explode("/", $value);
                                if (isset($list[1]) && $list[1] == 'single') {
                                    if (isset($list[3])) {
                                        if ($list[2] == 'product_cat') {
                                            if (has_term($list[3], 'product_cat')) {
                                                if ('publish' == get_post_status($key)) {
                                                    $page_id = $key;
                                                }
                                            }
                                        } else if ($list[2] == 'product_tag') {
                                            if (has_term($list[3], 'product_tag')) {
                                                if ('publish' == get_post_status($key)) {
                                                    $page_id = $key;
                                                }
                                            }
                                        } else if ($list[1] == 'single' && $list[2] == 'product') {
                                            if (get_the_ID() == $list[3]) {
                                                if ('publish' == get_post_status($key)) {
                                                    $page_id = $key;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }  
        }

        /*
         * Front Page Builder
         */
        if (isset($conditions['home_page'])) {
            if (!empty($conditions['home_page'])) {
                foreach ($conditions['home_page'] as $key => $val) {
                    if (is_front_page() && !is_search()) {
                        if (is_array($val) && in_array('filter/home_page', $val)) {
                            if ('publish' == get_post_status($key)) {
                                $page_id = $key;
                            }
                        }
                    }
                }
            }
        }

        // Singular Builder
        if (isset($conditions['single_product']) && is_product()) {
            if (!empty($conditions['single_product'])) {
                $obj = get_queried_object();
                $tax_list = $this->get_taxonomy_list();
                foreach ($conditions['single_product'] as $key => $val) {
                    // All Taxonomy
                    if($this->in_string_part('/single_product/in_', $val)) {
                        foreach ($tax_list as $tax) {
                            if ($this->in_string_part('single_product/in_'.$tax.'_children', $val)) {
                                // In Taxonomy Children
                                if (in_array('include/single_product/in_'.$tax.'_children', $val)) {
                                    if ('publish' == get_post_status($key)) {
                                        $page_id = $key;
                                    }
                                }
                                if (in_array('exclude/single_product/in_'.$tax.'_children', $val)) {
                                    if ('publish' == get_post_status($key)) {
                                        $page_id = '';
                                    }
                                }
                                $data = $this->in_string_part('/single_product/in_'.$tax.'_children/', $val, true);
                                if ($data) {
                                    $data = explode("/", $data);
                                    if (isset($data[3]) && $data[3]) {
                                        if (is_object_in_term($obj->ID , $tax, $data[3] )) {
                                            if ('publish' == get_post_status($key)) {
                                                $page_id = $data[0] == 'exclude' ? '' : $key;
                                            }
                                        }
                                    }
                                }
                            } else {
                                // IN Taxonomy
                                if (in_array('include/single_product/in_'.$tax, $val)) {
                                    if ('publish' == get_post_status($key)) {
                                        $page_id = $key;
                                    }
                                }
                                if (in_array('exclude/single_product/in_'.$tax, $val)) {
                                    if ('publish' == get_post_status($key)) {
                                        $page_id = '';
                                    }
                                }
                                foreach ($val as $v) {
                                    if (strpos($v, '/single_product/in_'.$tax.'/') !== false) {
                                        if ($v) {
                                            $data = explode("/", $v);
                                            if (isset($data[3]) && $data[3]) {
                                                if (is_object_in_term($obj->ID , $tax, $data[3] )) {
                                                    if ('publish' == get_post_status($key)) {
                                                        $page_id = $data[0] == 'exclude' ? '' : $key;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // All Post Type
                    if (in_array('include/single_product/'.$obj->post_type, $val)) {
                        if ('publish' == get_post_status($key)) {
                            $page_id = $key;
                        }
                    }
                    if (in_array('exclude/single_product/'.$obj->post_type, $val)) {
                        if ('publish' == get_post_status($key)) {
                            $page_id = '';
                        }
                    }
                    if ($this->in_string_part('include/single_product/'.$obj->post_type.'/'.$obj->ID, $val)) {
                        if ('publish' == get_post_status($key)) {
                            $page_id = $key;
                        }
                    }
                    if ($this->in_string_part('exclude/single_product/'.$obj->post_type.'/'.$obj->ID, $val)) {
                        if ('publish' == get_post_status($key)) {
                            $page_id = '';
                        }
                    }
                }
            }
        }

        /*
         * Shop Builder
         */
        if (isset($conditions['shop'])) {
            if (!empty($conditions['shop'])) {
                foreach ($conditions['shop'] as $key => $val) {
                    if (is_shop() && !is_search()) {
                        if (is_array($val) && in_array('filter/shop', $val)) {
                            if ('publish' == get_post_status($key)) {
                                $page_id = $key;
                            }
                        }
                    }
                }
            }
        }

        /*
         * Cart Builder
         */
        if (isset($conditions['cart'])) {
            if (!empty($conditions['cart'])) {
                foreach ($conditions['cart'] as $key => $val) {
                    if (is_cart() && !is_search()) {
                        if (is_array($val) && in_array('filter/cart', $val)) {
                            if ('publish' == get_post_status($key)) {
                                $page_id = $key;
                            }
                        }
                    }
                }
            }
        }

        /*
         * Checkout Builder
         */
        if (isset($conditions['checkout'])) {
            if (!empty($conditions['checkout'])) {
                foreach ($conditions['checkout'] as $key => $val) {
                    if (is_checkout() && !(is_wc_endpoint_url() || is_wc_endpoint_url( 'order-pay' ) || is_wc_endpoint_url( 'order-received' ))) {
                        if (is_array($val) && in_array('filter/checkout', $val)) {
                            if ('publish' == get_post_status($key)) {
                                $page_id = $key;
                            }
                        }
                    }
                }
            }
        }

        /*
         * My Account Builder
         */
        if (isset($conditions['my_account'])) {
            if (!empty($conditions['my_account'])) {
                foreach ($conditions['my_account'] as $key => $val) {
                    if (is_account_page() && is_array($val) && in_array('filter/my_account', $val)) {
                        if ('publish' == get_post_status($key)) {
                            $page_id = $key;
                        }
                    }
                }
            }
        }

        /*
         * Thank You Builder
         */
        if (isset($conditions['thank_you'])) {
            if (!empty($conditions['thank_you'])) {
                foreach ($conditions['thank_you'] as $key => $val) {
                    if(is_checkout() && is_wc_endpoint_url( 'order-received' )) {
                        if (is_array($val) && in_array('filter/thank_you', $val)) {
                            if ('publish' == get_post_status($key)) {
                                $page_id = $key;
                            }
                        }
                    }
                }
            }
        }

        /*
         * Product Search Result Builder
         */
        if (isset($conditions['product_search'])) {
            if (!empty($conditions['product_search'])) {
                foreach ($conditions['product_search'] as $key => $val) {
                    if($post_type == 'product' && is_search()) {
                        if (is_array($val) && in_array('filter/product_search', $val)) {
                            if ('publish' == get_post_status($key)) {
                                $page_id = $key;
                            }
                        }
                    }
                }
            }
        }

        if ($type == 'return') {
            return $page_id;
        }
        if ($type == 'includes') {
            return $page_id ? WOPB_PATH.'addons/builder/Archive_Template.php' : '';
        }
    }

    /**
	 * ID for the Builder Post or Normal Post
     * 
     * @since v.2.3.1
	 * @return NUMBER | is Builder or not
	 */
    public function get_ID() {
        $id = $this->is_builder();
        return $id ? $id : (is_shop() ? wc_get_page_id('shop') : get_the_ID());
    }

    public function is_builder($builder = '') {
        $id = '';
        if ($builder) { 
            return true; 
        }
        $page_id = $this->conditions('return');
        if ($page_id && wopb_function()->get_setting('wopb_builder')) {
            $id = $page_id;
        }
        return $id;
    }


    /**
	 * Checking Statement of Archive Builder
     * 
     * @since v.2.3.1
	 * @return BOOLEAN | is Builder or not
	 */
    public function is_archive_builder($single = false) {
        if ($single) {
            $type = get_post_meta(get_the_ID(), '_wopb_builder_type', true);
            return $type == 'single-product' ? false : true;
        } else {
            return  get_post_type( get_the_ID() ) == 'wopb_builder' ? true : false;
        }
        
    }

    /**
	 * Escaping and Set Inline CSS
     * 
     * @since v.2.2.7
     * @param STRING | CSS
	 * @return STRING | CSS with Style
	 */
    public function esc_inline($css) {
        return '<style type="text/css">'.wp_strip_all_tags($css).'</style>';
    }

    public function get_builder_attr() {
        $builder_data = '';
        if (is_archive()) {
            $obj = get_queried_object();
            if (isset($obj->taxonomy)) {
                $builder_data = 'taxonomy###'.$obj->taxonomy.'###'.$obj->slug;
            }
        } else if (is_search()) {
            $builder_data = 'search###'.get_search_query(true);
        }
        return $builder_data ? 'data-builder="'.esc_attr($builder_data).'"' : '';
    }

    public function custom_query_product_filter($where, &$query) {
        global $wpdb;
        if(!empty($query->get('filter_search_key'))) {
            $where .= " AND {$wpdb->prefix}posts.post_title LIKE '%{$query->get('filter_search_key')}%' ";
        }
        return $where;
    }

    public function custom_join_product_filter( $join, &$query ) {
     global $wpdb;
     return $join;
    }

    public function get_pagenum_link ($page_num, $attr) {
        global $wp_rewrite;
        if(isset($attr['current_url'])) {
            $base = $attr['current_url'];
            if ( $page_num > 1 ) {
                $base = $base . user_trailingslashit( $wp_rewrite->pagination_base . '/' . $page_num, 'paged' );
            }
            return $base;
        }else {
            return get_pagenum_link($page_num);
        }
    }

     /**
	 * Array Sanitize Function
     *
     * @param ARRAY
	 * @return ARRAY | Array of Sanitize
	 */
    public function recursive_sanitize_text_field($array) {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $value = $this->recursive_sanitize_text_field($value);
            } else {
                $value = sanitize_text_field($value);
            }
        }
        return $array;
    }

    /**
	 * Get Add Default Condition Data
     *
     * @since v.2.3.9
	 * @return ARRAY | Default Data
	 */
    public function builder_data() {
        $archive_data = [
            [
                'label' => 'All Archive',
                'value' => '',
            ],
        ];
        $single_data = [
//            [
//                'label' => 'Front Page',
//                'value' => 'front_page',
//            ]
        ];

        $post_type = get_post_types( ['public' => true], 'objects' );
        foreach ($post_type as $key => $type) {
            // Post Type
            if ($type->name == 'product') {
                $single_temp = [
                    'label' => $type->label,
                    'value' => $type->name,
                    'search' => 'type###' . $type->name
                ];

                $archive_temp = [];

                // Taxonomy
                $taxonomy = get_object_taxonomies($type->name, 'objects');
                if (!empty($taxonomy)) {
                    $single_tax = $archive_tax = [];
                    $single_tax[] = $single_temp;

                    $archive_temp = [
                        'label' => $type->label . ' Archive',
                        'value' => $type->name . '_archive',
                    ];
                    // $archive_tax[] = $archive_temp;
                    $exclude_taxonomy = ['product_shipping_class'];
                    foreach ($taxonomy as $key => $val) {
                        if (in_array($key, $exclude_taxonomy)) {
                            continue;
                        }
                        if ($val->public) {
                            $single_tax[] = [
                                'label' => 'In ' . $val->label,
                                'value' => 'in_' . $val->name,
                                'search' => 'term###' . $val->name
                            ];
                            $archive_tax[] = [
                                'label' => $val->label,
                                'value' => $val->name,
                                'search' => 'term###' . $val->name
                            ];

                            if ($val->hierarchical) {
                                // Hierarchical
                                $single_tax[] = [
                                    'label' => 'In Child ' . $val->label,
                                    'value' => 'in_' . $val->name . '_children',
                                    'search' => 'term###' . $val->name
                                ];
                                $archive_tax[] = [
                                    'label' => 'Direct Child ' . $val->label . ' Of',
                                    'value' => 'child_of_' . $val->name,
                                    'search' => 'term###' . $val->name
                                ];
                                $archive_tax[] = [
                                    'label' => 'Any Child ' . $val->label . ' Of',
                                    'value' => 'any_child_of_' . $val->name,
                                    'search' => 'term###' . $val->name
                                ];
                            }
                        }
                    }
                    $single_temp['attr'] = $single_tax;
                    $archive_temp['attr'] = $archive_tax;
                }
                $single_data[] = $single_temp;
                if (!empty($archive_temp)) {
                    $archive_data[] = $archive_temp;
                }
            }
        }

        return ['single_product' => $single_data, 'archive' => $archive_data];
    }

    /**
	 * HTML for Popup
     *
	 * @return STRING
	 */
    public function pro_popup_html() { ?>
        <div class="wopb-popup-container wopb-unlock-popup-container popup-center">
            <div class="wopb-unlock-popup wopb-unlock-modal">
                <img src="<?php echo esc_url(WOPB_URL . '/assets/img/admin/dashboard/unlock-super-icon.svg'); ?>" alt="lock icon">
                <h4 class="wopb-md-heading wopb-mt25 wopb-mb25"><?php esc_html_e('Unlock 11+ Addons', 'product-blocks'); ?></h4>
                <div class="wopb-popup-desc">
                    <?php esc_html_e('We’re sorry, ProductX Addons are not available on ProductX  free. Please upgrade to a PRO plan to unlock the add  ons of your choice.', 'product-blocks'); ?>
                </div>
                <a href="<?php echo esc_url(wopb_function()->get_premium_link('', 'productx_dashboard_addons_popup')); ?>" class="wopb-popup-planbtn wopb-btn wopb-btn-warning wopb-mt25"><?php esc_html_e('Upgrade Plan', 'product-blocks'); ?></a>
                <button class="wopb-popup-close"></button>
            </div>
        </div>
    <?php }

    /**
     * Shop Builder Active Checking
     */
    public function wopb_shop_builder_check() {
        $shop_builder_active = false;
        
        $conditions = get_option('wopb_builder_conditions', array());
        if (isset($conditions['shop'])) {
            if (!empty($conditions['shop'])) {
                foreach ($conditions['shop'] as $key => $val) {
                    if (is_shop() && !is_search()) {
                        if (is_array($val) && in_array('filter/shop', $val)) {
                            if ('publish' == get_post_status($key)) {
                                $shop_builder_active = true ;
                            }
                        }
                    }
                }
            }
        }

        if (isset($conditions['archive'])) {
            if (!empty($conditions['archive'])) {
                foreach ($conditions['archive'] as $key => $val) {
                    if (is_shop() && !is_search()) {
                        if (in_array('filter/shop', $val)) {
                            if ('publish' == get_post_status($key)) {
                                $shop_builder_active = true ;
                            }
                        }
                    }
                }
            }
        }
        return $shop_builder_active;
    }

    public function get_blocks_settings() {
        $arr = array(
            'product_grid' => array(
                'label' => __('Product Grid Blocks', 'product-blocks'),
                'attr' => array(
                    'product_grid_1' => array(
                        'label' => __('Product Grid #1', 'product-blocks'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'assets/img/blocks/product-grid-1.svg'
                    ),
                    'product_grid_2' => array(
                        'label' => __('Product Grid #2', 'product-blocks'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid425',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'assets/img/blocks/product-grid-2.svg'
                    ),
                    'product_grid_3' => array(
                        'label' => __('Product Grid #3', 'product-blocks'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid424',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'assets/img/blocks/product-grid-3.svg'
                    ),
                    'product_grid_4' => array(
                        'label' => __('Product Grid #4', 'product-blocks'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid503',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'assets/img/blocks/product-grid-4.svg'
                    ),
                )
            ),
            'product_list' => array(
                'label' => __('Product List Blocks', 'product-blocks'),
                'attr' => array(
                    'product_list_1' => array(
                        'label' => __('Product List #1', 'product-blocks'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid426',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'assets/img/blocks/product-list-1.svg'
                    ),

                )
            ),
            'product_category' => array(
                'label' => __('Product Category', 'product-blocks'),
                'attr' => array(
                    'product_category_1' => array(
                        'label' => __('Product Category #1', 'product-blocks'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid427',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'assets/img/blocks/product-category-1.svg'
                    ),
                    'product_category_2' => array(
                        'label' => __('Product Category #2', 'product-blocks'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid504',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'assets/img/blocks/product-category-2.svg'
                    ),

                )
            ),
            'single_product_builder' => array(
                'label' => __('Single Product Builder', 'product-blocks'),
                'attr' => array(
                    'builder_product_title' => array(
                        'label' => __('Product Title', 'product-blocks'),
                        'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/builder/title.svg'
                    ),
                    'builder_product_short_description' => array(
                        'label' => __('Product Short Description', 'product-blocks'),
                        'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/builder/short_desc.svg'
                    ),
                    'builder_product_description' => array(
                        'label' => __('Product Description', 'product-blocks'),
                        'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/builder/description.svg'
                    ),
                    'builder_product_stock' => array(
                        'label' => __('Product Stock', 'product-blocks'),
                        'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/builder/stock.svg'
                    ),
                    'builder_product_price' => array(
                        'label' => __('Product Price', 'product-blocks'),
                        'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/builder/pricing.svg'
                    ),
                    'builder_product_rating' => array(
                        'label' => __('Product Rating', 'product-blocks'),
                        'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/builder/rating.svg'
                    ),
                    'builder_product_meta' => array(
                        'label' => __('Product Meta', 'product-blocks'),
                        'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/builder/meta.svg'
                    ),
                    'builder_product_breadcrumb' => array(
                        'label' => __('Product Breadcrumb', 'product-blocks'),
                        'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/builder/breadcrumb.svg'
                    ),
                    'builder_product_image' => array(
                        'label' => __('Product Image', 'product-blocks'),
                        'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/builder/image.svg'
                    ),
                    'builder_product_cart' => array(
                        'label' => __('Product Add To Cart', 'product-blocks'),
                        'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/builder/cart.svg'
                    ),
                    'builder_product_additional_info' => array(
                        'label' => __('Product Additional Info', 'product-blocks'),
                        'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/builder/info.svg'
                    ),
                    'builder_product_tab' => array(
                        'label' => __('Product Tab', 'product-blocks'),
                        'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/builder/tab.svg'
                    ),
                    'builder_product_review' => array(
                        'label' => __('Product Review', 'product-blocks'),
                        'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/builder/review.svg'
                    ),
                    'builder_social_share' => array(
                        'label' => __('Social Share', 'product-blocks'),
                        'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/builder/social_share/share.svg'
                    ),
                )
            ),
            'cart_builder' => array(
                'label' => __('Cart Builder', 'product-blocks'),
                'attr' => array(
                    'builder_cart_table' => array(
                        'label' => __('Cart Table', 'product-blocks'),
                        'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/cart_table.svg'
                    ),
                    'builder_cart_total' => array(
                        'label' => __('Cart Total', 'product-blocks'),
                        'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/cart_total.svg'
                    ),
                    'builder_free_shipping_progress_bar' => array(
                        'label' => __('Free Shipping Progress Bar', 'product-blocks'),
                        'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/freeshipping_progress_bar.svg'
                    ),
                )
            ),
           'checkout_builder' => array(
               'label' => __('Checkout Builder', 'product-blocks'),
               'attr' => array(
                   'builder_checkout_login' => array(
                       'label' => __('Checkout Login', 'product-blocks'),
                       'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                       'icon' => WOPB_URL.'/assets/img/blocks/builder/checkout_login.svg'
                   ),
                   'builder_checkout_billing' => array(
                       'label' => __('Billing Address', 'product-blocks'),
                       'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                       'icon' => WOPB_URL.'/assets/img/blocks/builder/checkout_billing_address.svg'
                   ),
                   'builder_checkout_shipping' => array(
                       'label' => __('Shipping Address', 'product-blocks'),
                       'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                       'icon' => WOPB_URL.'/assets/img/blocks/builder/checkout_shipping_address.svg'
                   ),
                   'builder_checkout_additional_information' => array(
                       'label' => __('Additional Information', 'product-blocks'),
                       'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                       'icon' => WOPB_URL.'/assets/img/blocks/builder/checkout_additional_information.svg'
                   ),
                   'builder_checkout_coupon' => array(
                       'label' => __('Coupon', 'product-blocks'),
                       'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                       'icon' => WOPB_URL.'/assets/img/blocks/builder/checkout_coupon.svg'
                   ),
                   'builder_checkout_payment_method' => array(
                       'label' => __('Payment Method', 'product-blocks'),
                       'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                       'icon' => WOPB_URL.'/assets/img/blocks/builder/checkout_payment_method.svg'
                   ),
                   'builder_checkout_order_review' => array(
                       'label' => __('Order Review', 'product-blocks'),
                       'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                       'icon' => WOPB_URL.'/assets/img/blocks/builder/checkout_order_review.svg'
                   ),
               )
           ),
            'other' => array(
                'label' => __('Others Product Blocks', 'product-blocks'),
                'attr' => array(
                    'heading' => array(
                        'label' => __('Heading', 'product-blocks'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid409',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/heading.svg'
                    ),
                    'image' => array(
                        'label' => __('Image', 'product-blocks'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid422',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/image.svg'
                    ),
//                    'product_search' => array(
//                        'label' => __('Search', 'product-blocks'),
//                        'default' => true,
////                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6825',
////                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
//                        'icon' => WOPB_URL.'/assets/img/blocks/search.svg'
//                    ),
                    'wrapper' => array(
                        'label' => __('Wrapper', 'product-blocks'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid428',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/wrapper.svg'
                    ),
                    'builder_archive_title' => array(
                        'label' => __('Archive Title', 'product-blocks'),
                        'default' => true,
//                        'live' => 'https://www.wpxpo.com/productx/blocks/#demoid353',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/builder/archive-title.svg'
                    ),
                    'product_filter' => array(
                        'label' => __('Product Filtering', 'product-blocks'),
                        'default' => true,
//                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6825',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/product-filter.svg'
                    ),
                    'currency_switcher' => array(
                        'label' => __('Currency Switcher', 'product-blocks'),
                        'default' => true,
//                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6825',
//                        'docs' => 'https://docs.wpxpo.com/docs/productx/all-blocks/',
                        'icon' => WOPB_URL.'/assets/img/blocks/currency_switcher.svg'
                    ),
                )
            ),
        );

        return $arr;
    }

    public function all_addons(){
        $all_addons = array(
            'wopb_builder' => array(
                'name' => __( 'Builder', 'product-blocks' ),
                'desc' => __( 'Design template for Archive, Category, Custom Taxonomy, Date and Search Page.', 'product-blocks' ),
                'img' => WOPB_URL.'assets/img/addons/builder.svg',
                'is_pro' => false,
                'docs' => 'https://docs.wpxpo.com/docs/productx/woocommerce-builder/',
                'live' => 'https://www.wpxpo.com/productx/addons/woocommerce-builder/'
            ),
            'wopb_compare' => array(
                'name' => __( 'Compare', 'product-blocks' ),
                'desc' => __( 'Add Compare to Your Blocks.', 'product-blocks' ),
                'img' => WOPB_URL.'/assets/img/addons/compare.svg',
                'is_pro' => false,
                'docs' => 'https://docs.wpxpo.com/docs/productx/add-ons/compare-settings/',
                'live' => 'https://www.wpxpo.com/productx/addons/product-comparison/'
            ),
            'wopb_flipimage' => array(
                'name' => __( 'Flip Image', 'product-blocks' ),
                'desc' => __( 'Add Flip Image Option for Blocks.', 'product-blocks' ),
                'img' => WOPB_URL.'/assets/img/addons/imageFlip.svg',
                'is_pro' => false,
                'docs' => 'https://docs.wpxpo.com/docs/productx/add-ons/flip-image-settings/',
                'live' => 'https://www.wpxpo.com/productx/addons/product-image-flipper/'
            ),
            'wopb_quickview' => array(
                'name' => __( 'Quickview', 'product-blocks' ),
                'desc' => __( 'Add Quickview to Your Blocks.', 'product-blocks' ),
                'img' => WOPB_URL.'/assets/img/addons/quickview.svg',
                'is_pro' => false,
                'docs' => 'https://docs.wpxpo.com/docs/productx/add-ons/quickview-settings/',
                'live' => 'https://www.wpxpo.com/productx/addons/quick-view/'
            ),
            'wopb_templates' => array(
                'name' => __( 'Saved Templates', 'product-blocks' ),
                'desc' => __( 'Create Short-codes and use them inside your page or page builder.', 'product-blocks' ),
                'img' => WOPB_URL.'/assets/img/addons/saved-template.svg',
                'is_pro' => false,
                'docs' => 'https://docs.wpxpo.com/docs/productx/add-ons/shortcode-support/',
                'live' => 'https://www.wpxpo.com/productx/addons/save-template/'
            ),
            'wopb_wishlist' => array(
                'name' => __( 'Wishlist', 'product-blocks' ),
                'desc' => __( 'Add Wishlist to Your Blocks.', 'product-blocks' ),
                'img' => WOPB_URL.'/assets/img/addons/wishlist.svg',
                'is_pro' => false,
                'docs' => 'https://docs.wpxpo.com/docs/productx/add-ons/wishlist-settings/',
                'live' => 'https://www.wpxpo.com/productx/addons/wishlist/'
            ),
            'wopb_preorder' => array(
                'name' => __( 'Pre-order', 'product-blocks' ),
                'desc' => __( 'Add Pre-order to WooCommerce.', 'product-blocks' ),
                'img' => WOPB_URL.'/assets/img/addons/pre-order.svg',
                'is_pro' => true,
                'docs' => 'https://docs.wpxpo.com/docs/productx/add-ons/pre-order-addon/',
                'live' => 'https://www.wpxpo.com/productx/addons/pre-order/'
            ),
            'wopb_stock_progress_bar' => array(
                'name' => __( 'Stock Progress Bar', 'product-blocks' ),
                'desc' => __( 'Add Stock Progress Bar to WooCommerce.', 'product-blocks' ),
                'img' => WOPB_URL.'/assets/img/addons/stock-progress-bar.svg',
                'is_pro' => true,
                'docs' => 'https://docs.wpxpo.com/docs/productx/add-ons/stock-progress-bar-addon/',
                'live' => 'https://www.wpxpo.com/productx/addons/stock-progress-bar/'
            ),
            'wopb_call_for_price' => array(
                'name' => __( 'Call for Price', 'product-blocks' ),
                'desc' => __( 'Add Call for Price to WooCommerce.', 'product-blocks' ),
                'img' => WOPB_URL.'/assets/img/addons/call-for-price.svg',
                'is_pro' => true,
                'docs' => 'https://docs.wpxpo.com/docs/productx/add-ons/call-for-price-addon/',
                'live' => 'https://www.wpxpo.com/productx/addons/call-for-price/'
            ),
            'wopb_backorder' => array(
                'name' => __( 'Backorder', 'product-blocks' ),
                'desc' => __( 'Add Backorder Option to WooCommerce.', 'product-blocks' ),
                'img' => WOPB_URL.'/assets/img/addons/backorder.svg',
                'is_pro' => true,
                'docs' => 'https://docs.wpxpo.com/docs/productx/add-ons/back-order-addon/',
                'live' => 'https://www.wpxpo.com/productx/addons/backorder/'
            ),
            'wopb_partial_payment' => array(
                'name' => __( 'Partial Payment', 'product-blocks' ),
                'desc' => __( 'Add Partial Payment to WooCommerce.', 'product-blocks' ),
                'img' => WOPB_URL.'/assets/img/addons/partial-payment.svg',
                'is_pro' => true,
                'docs' => 'https://docs.wpxpo.com/docs/productx/add-ons/partial-payment/',
                'live' => 'https://www.wpxpo.com/productx/addons/partial-payment/'
            ),
            'wopb_variation_swatches' => array(
                'name' => __( 'Variation Swatches', 'product-blocks' ),
                'desc' => __( 'Variation Swatches to Your Blocks.', 'product-blocks' ),
                'img' => WOPB_URL.'/assets/img/addons/variation_switcher.svg',
                'is_pro' => false,
                'docs' => 'https://docs.wpxpo.com/docs/productx/add-ons/variation-swatches-addon/',
                'live' => 'https://www.wpxpo.com/productx/addons/woocommerce-variation-swatches/'
            ),
            'wopb_currency_switcher' => array(
                'name' => __( 'Currency Switcher', 'product-blocks' ),
                'desc' => __( 'Let shoppers convert the currency of your WooCommerce store to their desired one.', 'product-blocks' ),
                'img' => WOPB_URL.'/assets/img/addons/variation_switcher.svg',
                'is_pro' => true,
                'docs' => 'https://docs.wpxpo.com/docs/productx/add-ons/currency-switcher-addon/',
                'live' => 'https://www.wpxpo.com/woocommerce-currency-switcher/'
            )
        );
        return apply_filters('wopb_addons_config', $all_addons);
    }

    /**
     * Payment Gateway List
     *
     * @return array
     */
     function payment_gateway_list() {
        $active_gateways = array();
        $gateways = WC()->payment_gateways->payment_gateways();
        foreach ( $gateways as $id => $gateway ) {
            if ( $gateway->enabled == 'yes' ) {
                $active_gateways[$id] = $gateway->title;
            }
        }
        return $active_gateways;
    }

    /**
     * Currency Switcher data
     *
     * @param float $value
     * @param string $type
     * @return array
     * @since v.2.4.8
     */
    public function currency_switcher_data($value = 0, $type = '') {
        if( wopb_function()->get_setting('wopb_currency_switcher') == 'true' &&
            wopb_function()->is_lc_active() &&
            wopb_function()->get_setting('wopb_current_currency') != wopb_function()->get_setting('wopb_default_currency')
        ) {
            $current_currency = Currency_Switcher_Action::get_currency(wopb_function()->get_setting('wopb_current_currency'));
            $data = [
                 'current_currency' =>  wopb_function()->get_setting('wopb_current_currency'),
                 'current_currency_rate' => isset($current_currency['wopb_currency_rate']) ? $current_currency['wopb_currency_rate'] : 1,
                 'current_currency_exchange_fee' => isset($current_currency['wopb_currency_exchange_fee']) && $current_currency['wopb_currency_exchange_fee'] > 0 ? $current_currency['wopb_currency_exchange_fee'] : 0,
            ];

            if($value > 0 && $type == 'default') {
                $data['value'] = (float)$value / ($data['current_currency_rate'] + $data['current_currency_exchange_fee']);
            }elseif($value > 0) {
                $data['value'] = ($data['current_currency_rate'] + $data['current_currency_exchange_fee']) *  (float)$value;
            }
            return $data;
        }
    }

     /**
     * Currency switcher require only for ajax
     *
     * @return array
     */
    public function currency_switcher_require() {
        if (wopb_function()->get_setting('wopb_currency_switcher') == 'true' && wopb_function()->is_lc_active()) {
            return new Currency_Switcher_Action();
        }
    }

}