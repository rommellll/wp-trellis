<?php
namespace WOPB_PRO;

defined('ABSPATH') || exit;

/**
 * Plugin Initialization
 */
class Initialization{

    /**
     * Plugin Constructor
     */
    public function __construct(){
        $this->requires();
    }

    /**
	 * Require File for ProductX
     * 
     * @since v.2.0.7
	 * @return NULL
	 */
    public function requires() {
        require_once WOPB_PRO_PATH.'classes/Notice.php';
        new \WOPB_PRO\Notice();

        // Pro Plugin Updater Class
        require_once WOPB_PRO_PATH.'classes/updater/License.php';
        new \WOPB_PRO\License();

        add_action( 'activated_plugin', array($this, 'activation_redirect'));
        
        if ( wopb_pro_function()->is_wopb_free_ready() ) {
            $this->include_addons();
        }
    }

    public function activation_redirect($plugin) {
        // Redirect To License Page
        if( $plugin == 'product-blocks-pro/product-blocks-pro.php' ) {
            exit(wp_redirect(admin_url('admin.php?page=wopb-license')));
        }
    }

    // Include Addons directory
	public function include_addons() {
        $active = is_multisite() ? array_keys(get_site_option('active_sitewide_plugins', array())) : (array)get_option('active_plugins', array());
        if (file_exists(WP_PLUGIN_DIR.'/woocommerce/woocommerce.php') && in_array('woocommerce/woocommerce.php', $active)) {
            $addons_dir = array_filter(glob(WOPB_PRO_PATH.'addons/*'), 'is_dir');
            if (count($addons_dir) > 0) {
                foreach( $addons_dir as $key => $value ) {
                    $addon_dir_name = str_replace(dirname($value).'/', '', $value);
                    $file_name = WOPB_PRO_PATH . 'addons/'.$addon_dir_name.'/init.php';
                    if ( file_exists($file_name) ) {
                        include_once $file_name;
                    }
                }
            }
        }
    }
}