<?php
/**
 * Initial Setup.
 *
 * @package WOPB\Notice
 * @since v.2.4.4
 */
namespace WOPB;

defined('ABSPATH') || exit;

class InitialSetup {

    public function __construct(){
        add_action( 'admin_menu', array( $this, 'menu_page_callback' ) );
        add_action( 'admin_menu', function() {
            remove_menu_page( 'wopb-initial-setup-wizard' );
        }, 99 );

        add_action('wp_ajax_wopb_send_initial_plugin_data', array($this, 'send_initial_plugin_data'));

        add_action('wp_ajax_wopb_initial_setup_complete', array($this, 'initial_setup_complete')); // Initial Setup complete
	}

	/**
     * Initial Setup Menu Page Added
     *
     * @since v.2.4.4
     * @return NULL
     */
    public static function menu_page_callback() {
        add_menu_page(
            esc_html__('Initial Setup', 'product-blocks'),
            esc_html__('Initial Setup', 'product-blocks'),
            'manage_options',
            'wopb-initial-setup-wizard',
            array(self::class, 'initial_setup'),
            '',
            null
        );
    }

    /**
     * Initial Plugin Setting
     *
     * * @since v.2.4.4
     * @return STRING
     */
    public static function initial_setup() {
        $html = '';
        $html .= '<div class="wopb-initial-setting-wrap" id="wopb-initial-setting">';
        $html .= '</div>';
        echo $html;
    }

    /**
     * Send Plugin Data When Initial Setup
     *
     * * @since v.2.4.4
     * @return STRING
     */
    public function send_initial_plugin_data($type) {
        require_once WOPB_PATH.'classes/Deactive.php';
        $obj = new \WOPB\Deactive();
        $obj->send_plugin_data('productx_wizard');
    }

    /**
     * Initial Plugin Setup Complete
     *
     * * @since v.2.4.4
     * @return STRING
     */
    public static function initial_setup_complete() {
        update_option( '_wopb_initial_setup', true );
         return wp_send_json_success([
            'redirect' => admin_url('admin.php?page=wopb-settings'),
        ]);
    }
}