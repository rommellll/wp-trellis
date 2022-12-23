<?php
defined( 'ABSPATH' ) || exit;

/**
 * Stock Progress Bar Addons Initial Configuration
 * @since v.1.0.5
 */
add_filter('wopb_addons_config', 'wopb_stock_progress_bar_config');
function wopb_stock_progress_bar_config( $config ) {
	$configuration = array(
		'name' => __( 'Stock Progress Bar', 'product-blocks' ),
		'desc' => __( 'Add Stock Progress Bar to Your Blocks.', 'product-blocks' ),
		'img' => WOPB_PRO_URL.'/assets/img/addons/stock-progress-bar.svg',
		'is_pro' => false,
        'docs' => 'https://docs.wpxpo.com/docs/productx/add-ons/stock-progress-bar-addon/',
        'live' => 'https://www.wpxpo.com/productx/addons/stock-progress-bar/'
	);
	$config['wopb_stock_progress_bar'] = $configuration;
	return $config;
}

/**
 * Require Main File
 * @since v.1.0.5
 */
add_action('wp_loaded', 'wopb_stock_progress_bar_init');
function wopb_stock_progress_bar_init(){
	$settings = wopb_function()->get_setting();
	if ( isset($settings['wopb_stock_progress_bar']) ) {
		if ($settings['wopb_stock_progress_bar'] == 'true') {
			require_once WOPB_PRO_PATH.'/addons/stock_progress_bar/StockProgressBar.php';
			$obj = new \WOPB_PRO\StockProgressBar();
			if( !isset($settings['stock_progress_bar_heading']) ){
				$obj->initial_setup();
			}
		}
	}

    add_filter('wopb_settings', 'get_stock_progress_settings', 10, 1);
}

/**
 * Stock Progress Bar Addons Default Settings Param
 *
 * @since v.1.0.5
 * @param ARRAY | Default Filter Congiguration
 * @return ARRAY
 */
function get_stock_progress_settings($config){
    $arr = array(
        'wopb_stock_progress_bar' => array(
            'label' => __('Stock Progress Bar', 'product-blocks'),
            'attr' => array(
                'stock_progress_bar_heading' => array(
                    'type' => 'heading',
                    'label' => __('Stock Progress Bar Settings', 'product-blocks'),
                ),
                'total_sell_count_text' => array(
                    'type' => 'text',
                    'label' => __('Total Sell Count Text', 'product-blocks'),
                    'default' => __('Total Sold', 'product-blocks')
                ),
                'available_item_count_text' => array(
                    'type' => 'text',
                    'label' => __('Available Item Count Text', 'product-blocks'),
                    'default' => __('Available Item', 'product-blocks')
                ),
            )
        )
    );

    return array_merge($config, $arr);
}