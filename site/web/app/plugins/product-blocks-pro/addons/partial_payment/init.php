<?php
defined( 'ABSPATH' ) || exit;

/**
 * Partial Payment Addons Initial Configuration
 * @since v.1.0.8
 */
add_filter('wopb_addons_config', 'wopb_partial_payment_config');
function wopb_partial_payment_config( $config ) {
	$configuration = array(
		'name' => __( 'Partial Payment', 'product-blocks' ),
		'desc' => __( 'Add Partial Payment to WooCommerce.', 'product-blocks' ),
		'img' => WOPB_PRO_URL.'/assets/img/addons/partial-payment.svg',
		'is_pro' => true,
        'docs' => 'https://docs.wpxpo.com/docs/productx/add-ons/partial-payment/',
        'live' => 'https://www.wpxpo.com/productx/addons/partial-payment/'
	);
	$config['wopb_partial_payment'] = $configuration;
	return $config;
}

/**
 * Require Main File
 * @since v.1.0.8
 */
add_action('wp_loaded', 'wopb_partial_payment_init');
function wopb_partial_payment_init(){
	$settings = wopb_function()->get_setting();
	if ( isset($settings['wopb_partial_payment']) ) {
		if ($settings['wopb_partial_payment'] == 'true') {
			require_once WOPB_PRO_PATH.'/addons/partial_payment/PartialPayment.php';
			$obj = new \WOPB_PRO\PartialPayment();
			if (!isset($settings['partial_payment_heading'])) {
				$obj->initial_setup();
			}
		}
	}

	add_filter('wopb_settings', 'get_partial_payment_settings', 10, 1);
}

/**
 * Partial Payment Addons Default Settings Parameters
 *
 * @param ARRAY | Default Settings Configuration
 * @return ARRAY
 * @since v.1.0.8
 */
function get_partial_payment_settings($config)
{
    $arr = array(
        'wopb_partial_payment' => array(
            'label' => __('Partial Payment', 'product-blocks'),
            'attr' => array(
                'partial_payment_heading' => array(
                    'type' => 'heading',
                    'label' => __('Partial Payment Settings', 'product-blocks'),
                ),
                'partial_payment_label_text' => array(
                    'type' => 'text',
                    'label' => __('Partial Payment Label/Text', 'product-blocks'),
                    'default' => __('Partial Payment', 'product-blocks')
                ),
                'deposit_payment_text' => array(
                    'type' => 'text',
                    'label' => __('Deposit/First Payment Text', 'product-blocks'),
                    'default' => __('First Payment', 'product-blocks')
                ),
                'full_payment_text' => array(
                    'type' => 'text',
                    'label' => __('Full Payment Text', 'product-blocks'),
                    'default' => __('Full Payment', 'product-blocks')
                ),
                'deposit_to_pay_text' => array(
                    'type' => 'text',
                    'label' => __('To Pay', 'product-blocks'),
                    'default' => __('To Pay', 'product-blocks')
                ),
                'deposit_paid_text' => array(
                    'type' => 'text',
                    'label' => __('Paid', 'product-blocks'),
                    'default' => __('Paid', 'product-blocks')
                ),
                'deposit_amount_text' => array(
                    'type' => 'text',
                    'label' => __('Deposit Amount Text', 'product-blocks'),
                    'default' => __('Deposit', 'product-blocks')
                ),
                'due_payment_text' => array(
                    'type' => 'text',
                    'label' => __('Due Payment', 'product-blocks'),
                    'default' => __('Due Payment', 'product-blocks')
                ),
                'deposit_paid_status' => array(
                    'type'    => 'select',
                    'default' => 'wc-processing',
                    'options' => wc_get_order_statuses(),
                    'label'   => __( 'Deposit Paid Status', 'product-blocks' ),
                    'desc'    => __( 'set order status when deposits are paid', 'product-blocks' ),
                ),
               'disable_payment_method' => array(
                    'type' => 'switch',
                    'label' => __( 'Disable Payment Methods	', 'product-blocks' ),
                    'options' => payment_gateway_list(),
                    'desc' => 'Test',
                ),
                'wopb_partial_payment' => array(
                    'type' => 'hidden',
                    'value' => 'true'
                )
            )
        )
    );
    return array_merge($config, $arr);
}

/**
 * Payment Gateway List
 *
 * @return ARRAY
 * @since v.1.0.8
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