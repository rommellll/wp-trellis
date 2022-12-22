<?php
defined( 'ABSPATH' ) || exit;

/**
 * Preorder Addons Initial Configuration
 * @since v.1.0.4
 */
add_filter('wopb_addons_config', 'wopb_preorder_config');
function wopb_preorder_config( $config ) {
	$configuration = array(
		'name' => __( 'Pre-order', 'product-blocks' ),
		'desc' => __( 'Add Pre-order to Your Blocks.', 'product-blocks' ),
		'img' => WOPB_PRO_URL.'/assets/img/addons/pre-order.svg',
		'is_pro' => true,
        'docs' => 'https://docs.wpxpo.com/docs/productx/add-ons/pre-order-addon/',
        'live' => 'https://www.wpxpo.com/productx/addons/pre-order/'
	);
	$config['wopb_preorder'] = $configuration;
	return $config;
}

/**
 * Require Main File
 * @since v.1.0.4
 */
add_action('wp_loaded', 'wopb_preorder_init');
function wopb_preorder_init(){
	$settings = wopb_function()->get_setting();
	if ( isset($settings['wopb_preorder']) ) {
		if ($settings['wopb_preorder'] == 'true') {
			require_once WOPB_PRO_PATH.'/addons/preorder/Preorder.php';
			$obj = new \WOPB_PRO\preorder();
			if (!isset($settings['preorder_heading'])) {
				$obj->initial_setup();
			}
		}
	}

	add_filter('wopb_settings', 'get_preorder_settings', 10, 1);
}

/**
 * Preorder Addons Default Settings Parameters
 *
 * @param ARRAY | Default Settings Congiguration
 * @return ARRAY
 * @since v.1.0.4
 */
function get_preorder_settings($config)
{
    $arr = array(
        'wopb_preorder' => array(
            'label' => __('Pre-order', 'product-blocks'),
            'attr' => array(
                'preorder_heading' => array(
                    'type' => 'heading',
                    'label' => __('Pre-order Settings', 'product-blocks'),
                ),
                'preorder_button_text' => array(
                    'type' => 'text',
                    'label' => __('Pre-order Label/Text', 'product-blocks'),
                    'default' => __('Pre-order', 'product-blocks')
                ),
                'preorder_add_to_cart_button_text' => array(
                    'type' => 'text',
                    'label' => __('Pre-order Add to Cart Text', 'product-blocks'),
                    'default' => __('Pre-order Now', 'product-blocks')
                ),
                'preorder_message_text' => array(
                    'type' => 'text',
                    'label' => __('Availability Message', 'product-blocks'),
                    'default' => __('Available On', 'product-blocks')
                ),
                'preorder_coming_soon_text' => array(
                    'type' => 'text',
                    'label' => __('Pre-Release Message', 'product-blocks'),
                    'default' => __('Coming Soon', 'product-blocks')
                ),
                'wopb_preorder' => array(
                    'type' => 'hidden',
                    'value' => 'true'
                )
            )
        )
    );
    return array_merge($config, $arr);
}