<?php
defined( 'ABSPATH' ) || exit;

/**
 * Call fo Price Addons Initial Configuration
 * @since v.1.0.6
 */
add_filter('wopb_addons_config', 'wopb_call_for_price_config');
function wopb_call_for_price_config( $config ) {
	$configuration = array(
		'name' => __( 'Call for Price', 'product-blocks' ),
		'desc' => __( 'Call for Price to Your Blocks.', 'product-blocks' ),
		'img' => WOPB_PRO_URL.'/assets/img/addons/call-for-price.svg',
		'is_pro' => false,
        'docs' => 'https://docs.wpxpo.com/docs/productx/add-ons/call-for-price-addon/',
        'live' => 'https://www.wpxpo.com/productx/addons/call-for-price/'
	);
	$config['wopb_call_for_price'] = $configuration;
	return $config;
}

/**
 * Require Main File
 * @since v.1.0.6
 */
add_action('wp_loaded', 'wopb_call_for_price_init');
function wopb_call_for_price_init(){
	$settings = wopb_function()->get_setting();
	if ( isset($settings['wopb_call_for_price']) ) {
		if ($settings['wopb_call_for_price'] == 'true') {
			require_once WOPB_PRO_PATH.'/addons/call_for_price/CallForPrice.php';
			$obj = new \WOPB_PRO\CallForPrices();
			if( !isset($settings['call_for_price_heading']) ){
				$obj->initial_setup();
			}
		}
	}

	add_filter('wopb_settings', 'get_call_price_settings', 10, 1);
}

/**
 * Call for Price Addons Default Settings Param
 *
 * @since v.1.0.8
 * @param ARRAY | Default Filter Congiguration
 * @return ARRAY
 */
function get_call_price_settings($config){
    $arr = array(
        'wopb_call_for_price' => array(
            'label' => __('Call for Price', 'product-blocks'),
            'attr' => array(
                'call_for_price_heading' => array(
                    'type' => 'heading',
                    'label' => __('Call for Price Settings', 'product-blocks'),
                ),
                'call_for_price_text' => array(
                    'type' => 'text',
                    'label' => __('Call for Price Text', 'product-blocks'),
                    'default' => __('Call for Price', 'product-blocks')
                ),
                'call_type' => array(
                    'type' => 'select',
                    'label' => __('Call Type', 'product-blocks'),
                    'options' => array(
                        ''   => __( 'Select Call Type','product-blocks' ),
                        'phone'   => __( 'Phone Call','product-blocks' ),
                        'skype' => __( 'Skype','product-blocks' ),
                        'whatsapp' => __( 'Whatsapp','product-blocks' ),
                    ),
                    'default' => '',
                ),
                'call_link' => array(
                    'type' => 'text',
                    'required' => true,
                    'label' => __('Recipient Number / ID', 'product-blocks'),
                    'desc' => __('<strong>Note:</strong> Add number with country code for direct calling and WhatsApp. Add ID for skype.', 'product-blocks'),
                ),
            )
        )
    );

    return array_merge($config, $arr);
}