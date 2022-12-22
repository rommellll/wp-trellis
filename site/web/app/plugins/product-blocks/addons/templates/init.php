<?php
defined( 'ABSPATH' ) || exit;

/**
 * SaveTemplate Addons Initial Configuration
 * @since v.1.1.0
 */
add_filter('wopb_addons_config', 'wopb_templates_config');
function wopb_templates_config( $config ) {
	$configuration = array(
		'name' => __( 'Saved Templates', 'product-blocks' ),
		'desc' => __( 'Create Short-codes and use them inside your page or page builder.', 'product-blocks' ),
		'img' => WOPB_URL.'/assets/img/addons/saved-template.svg',
		'is_pro' => false,
        'docs' => 'https://docs.wpxpo.com/docs/productx/add-ons/shortcode-support/',
        'live' => 'https://www.wpxpo.com/productx/addons/save-template/'
	);
	$config['wopb_templates'] = $configuration;
	return $config;
}

/**
 * Require Main File
 * @since v.1.1.0
 */
add_action('init', 'wopb_templates_init');
function wopb_templates_init() {
	$settings = wopb_function()->get_setting();
	if ( isset($settings['wopb_templates']) ) {
		if ($settings['wopb_templates'] == 'true') {
			require_once WOPB_PATH.'/addons/templates/Saved_Templates.php';
			require_once WOPB_PATH.'/addons/templates/Shortcode.php';
			new \WOPB\Saved_Templates();
			new \WOPB\Shortcode();
		}
	}
}
/**
 * Function to load css in elementor for using shortcode
 */
add_action( 'elementor/frontend/after_enqueue_styles', 'wopb_add_css_in_elementor_page' );

function wopb_add_css_in_elementor_page() {
	wp_register_style('wopb-style-in-elementor', WOPB_URL.'assets/css/blocks.style.css', array(), WOPB_VER );
	wp_enqueue_style('wopb-style-in-elementor');
}