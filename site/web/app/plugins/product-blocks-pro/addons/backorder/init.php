<?php
defined( 'ABSPATH' ) || exit;

/**
 * Backorder Addons Initial Configuration
 * @since v.1.0.7
 */
add_filter('wopb_addons_config', 'wopb_backorder_config');
function wopb_backorder_config( $config ) {
	$configuration = array(
		'name' => __( 'Backorder', 'product-blocks' ),
		'desc' => __( 'Add Backorder to Your Blocks.', 'product-blocks' ),
		'img' => WOPB_PRO_URL.'/assets/img/addons/backorder.svg',
		'is_pro' => true,
        'docs' => 'https://docs.wpxpo.com/docs/productx/add-ons/back-order-addon/',
        'live' => 'https://www.wpxpo.com/productx/addons/backorder/'
	);
	$config['wopb_backorder'] = $configuration;
	return $config;
}

/**
 * Require Main File
 * @since v.1.0.7
 */
add_action('wp_loaded', 'wopb_backorder_init');
function wopb_backorder_init(){
	$settings = wopb_function()->get_setting();
	if ( isset($settings['wopb_backorder']) ) {
		if ($settings['wopb_backorder'] == 'true') {
			require_once WOPB_PRO_PATH.'/addons/backorder/Backorder.php';
			$obj = new \WOPB_PRO\backorder();
			if (!isset($settings['backorder_heading'])) {
				$obj->initial_setup();
			}
		}
	}

	add_filter('wopb_settings', 'get_backorder_settings', 10, 1);
}

/**
 * Backorder Addons Default Settings Parameters
 *
 * @param ARRAY | Default Settings Congiguration
 * @return ARRAY
 * @since v.1.0.7
 */
function get_backorder_settings($config)
{
    $arr = array(
        'wopb_backorder' => array(
            'label' => __('Backorder', 'product-blocks-pro'),
            'attr' => array(
                'backorder_heading' => array(
                    'type' => 'heading',
                    'label' => __('Backorder Settings', 'product-blocks-pro'),
                ),
                'backorder_button_text' => array(
                    'type' => 'text',
                    'label' => __('Backorder Label/Text', 'product-blocks-pro'),
                    'default' => __('Backorder', 'product-blocks-pro')
                ),
                'backorder_add_to_cart_button_text' => array(
                    'type' => 'text',
                    'label' => __('Backorder Add to Cart Text', 'product-blocks-pro'),
                    'default' => __('Backorder Now', 'product-blocks-pro')
                ),
                'backorder_message_text' => array(
                    'type' => 'text',
                    'label' => __('Availability Message', 'product-blocks-pro'),
                    'default' => __('Available On', 'product-blocks-pro')
                ),
                'wopb_backorder' => array(
                    'type' => 'hidden',
                    'value' => 'true'
                )
            )
        )
    );
    return array_merge($config, $arr);
}