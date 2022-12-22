<?php
namespace WOPB_PRO;

defined('ABSPATH') || exit;

class License{

    private $page_slug   = 'wopb-license';
    private $server_url  = 'https://www.wpxpo.com';
    private $item_id     = 1263;
    private $name        = 'ProductX Pro - Gutenberg Product Blocks for WooCommerce';
    private $version     = WOPB_PRO_VER;
    private $slug        = 'product-blocks-pro/product-blocks-pro.php';
    private $dummy_license_key = '******************';

    public function __construct(){
        add_action('admin_init',    array($this, 'edd_license_updater'));
        add_action('admin_init',    array($this, 'edd_activate_license'));
        add_action('admin_menu',    array($this, 'menu_page_callback'), 11);
    }
    
    public function edd_license_updater() {

        if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
            require_once WOPB_PRO_PATH.'classes/updater/EDD_SL_Plugin_Updater.php';
        }

        $license_key = trim( get_option( 'edd_wopb_license_key' ) );

        $edd_updater = new \EDD_SL_Plugin_Updater(
            $this->server_url,
            $this->slug,
            array(
                'version' => $this->version,
                'license' => $license_key,
                'item_id' => $this->item_id,
                'author'  => $this->name,
                'url'     => home_url(),
                'beta'    => false,
            )
        );

    }

    public function menu_page_callback() {
        add_submenu_page(
            'wopb-settings',
            __('License', 'product-blocks-pro'),
            __('License', 'product-blocks-pro'),
            'manage_options',
            $this->page_slug,
            array($this, 'edd_license_page'),
            25
        );
    }

    public function edd_license_page() {
        $status  = get_option( 'edd_wopb_license_status' );
        $this->license_css();
        ?>
        <div class="wopb-license-dashboard">
            <div class="wopb-license-wrap">
                <div class="wopb-licence-header">
                    Add License key
                </div>
                <form method="post" action="<?php echo admin_url( 'admin.php?page=' . $this->page_slug ); ?>">
                    <?php settings_fields('edd_wopb_license'); ?>
                    <?php
                        if (isset($_GET['note'])) {
                            echo '<div style="padding:.75rem 1.25rem; border-radius:.25rem; color:#721c24; background-color:#f8d7da; border-color:#f5c6cb;">'.$_GET['note'].'</div>';
                        }
                    ?>
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <td>
                                    <?php wp_nonce_field( 'edd_download_nonce', 'edd_download_nonce' ); ?>
                                    <input id="edd_wopb_license_key" name="edd_wopb_license_key" type="password" class="regular-text" value="<?php esc_attr_e($this->dummy_license_key) ?>" />
                                    <?php if( $status !== false && $status == 'valid' ) { ?>
                                        <span class="wopb-license-label"><?php _e('Your License Key is Activated.', 'product-blocks-pro'); ?></span>
                                    <?php } else { ?>
                                        <span class="wopb-invalid-label" style=""><?php _e('Your License Key is Not Activated', 'product-blocks-pro'); ?></span>
                                    <?php } ?>
                                    <br/><label class="description" for="edd_wopb_license_key"><?php _e('Enter your license key', 'roduct-blocks-pro'); ?></label>

                                    <?php if( $status !== false && $status != 'valid' ) { ?>
                                        <div class="wrap wopb-license-btn--wrap"><?php _e('Your License is not Activated.', 'product-blocks-pro'); ?> <a class="page-title-action" target="_blank" href="https://www.wpxpo.com/productx/"><?php _e('Buy Pro', 'product-blocks-pro'); ?></a></div>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td><?php submit_button(__('Check & Save License', 'product-blocks-pro'),"wopb-licence-check"); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <?php if(  $status !== false && $status != 'valid' ){ ?>
                <div class="wopb-license-wrap wopb-instraction-wrap">
                    <div class="wopb-ins-content">
                        <div class="wopb-ins-title">
                            How to use license key?
                        </div>
                        <div class="wopb-ins-step">
                            <div>
                                <b>***Note:</b> Make sure you have installed both the free and pro version of ProductX.
                            </div>
                            <div>
                                You have to add the license key and activate it to start using all the amazing features of ProductX. For that, you can follow the below steps:
                            </div>
                        </div>
                        <div class="wopb-ins-step">
                            <span>Step 1:</span>
                            <div>
                                Copy the License Key from wpxpo.com > My Account > Order > View License > Then Click Key Icon
                            </div>
                        </div>
                        <div class="wopb-ins-step">
                            <span>Step 2:</span>
                            <div>
                                Paste the copied license on the above field.
                            </div>
                        </div>
                        <div class="wopb-ins-step">
                            <div>
                                <b>***Note: </b> You will also get the license key and download link on the purchase confirmation page and email.
                            </div>
                        </div>
<!--                        <div class="wopb-licence-video">-->
<!--                            <iframe width="560" height="315" src="https://www.youtube.com/embed/Gce48hoA_wc" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>-->
<!--                        </div>-->
                    </div>
                    <div class="wopb-ins-box">
                        <div class="wopb-ins-box__heading">
                            <span class="dashicons dashicons-book"></span>
                            <div>
                                How to use License Key?
                            </div>
                        </div>
                        <div class="wopb-ins-box__desc">
                            Check out complete documentation of ProductX pro installation and using the license key.
                        </div>
                        <a href="https://docs.wpxpo.com/docs/productx/getting-started/pro-version-installation/" target="_blank">
                            Documentation
                        </a>
                    </div>
                </div>
            <?php } else { ?>
                <div class="wopb-license-wrap wopb-instraction-wrap">
                    <div class="wopb-ins-content">
                        <div class="wopb-ins-title">
                            How to Upgrade ProductX Pro Plan?
                        </div>
                        <div class="wopb-ins-step">
                            <div>
                                For upgrading the ProductX Pro Plan, you can follow the below steps:
                            </div>
                        </div>
                        <div class="wopb-ins-step">
                            <span>Step 1:</span>
                            <div>
                                Upgrade your current plan from <strong>wpxpo.com > my account > My Order > View Licenses > View Upgrades > Select Your Desired Plan</strong>
                            </div>
                        </div>
                        <div class="wopb-ins-step">
                            <span>Step 2:</span>
                            <div>
                                Complete the Payment and enjoy your plan.
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php
    }


    public function edd_activate_license() {

        if(isset($_POST['edd_wopb_license_key'])){
            if( ! check_admin_referer( 'edd_download_nonce', 'edd_download_nonce' ) ) {
                return '';
            }

            $license = trim( $_POST['edd_wopb_license_key'] );
            if($this->dummy_license_key == $license && wopb_function()->is_lc_active()) {
                $license = get_option( 'edd_wopb_license_key' );
            }
            update_option( 'edd_wopb_license_key', $license);
    
            // $license = trim( get_option( 'edd_wopb_license_key' ) );
            
            $api_params = array(
                'edd_action' => 'activate_license',
                'license'    => $license,
                'item_id'    => $this->item_id,
                'url'        => home_url()
            );
    
            $response = wp_remote_post( $this->server_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

            if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
                $message =  ( is_wp_error( $response ) && ! empty( $response->get_error_message() ) ) ? $response->get_error_message() : __('An error occurred, please try again.', 'product-blocks-pro');
            } else {
                $license_data = json_decode( wp_remote_retrieve_body( $response ) );
                if ( false === $license_data->success ) {
                    switch( $license_data->error ) {
                        case 'expired' :
                            $message = sprintf(
                                __('Your license key expired on %s.', 'product-blocks-pro'),
                                date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
                            );
                            break;
                        case 'revoked' :
                            $message = __('Your license key has been disabled.', 'product-blocks-pro');
                            break;
                        case 'missing' :
                            $message = __('Invalid license.', 'product-blocks-pro');
                            break;
                        case 'invalid' :
                        case 'site_inactive':
                            $message = __('Your license is not active for this URL.', 'product-blocks-pro');
                            break;
                        case 'item_name_mismatch':
                            $message = __('This appears to be an invalid license key.', 'product-blocks-pro');
                            break;
                        case 'no_activations_left':
                            $message = __('Your license key has reached its activation limit.', 'product-blocks-pro');
                            break;
                        default :
                            $message = __('An error occurred, please try again.', 'product-blocks-pro');
                            break;
                    }
                }
    
                if ( ! empty( $message ) ) {
                    $base_url = admin_url( 'admin.php?page=' . $this->page_slug );
                    $redirect = add_query_arg( array( 'sl_activation' => 'false', 'note' => urlencode( $message ) ), $base_url );
                    wp_redirect( $redirect );
                }
                update_option( 'edd_wopb_license_status', $license_data->license );
                wp_redirect( admin_url( 'admin.php?page=' . $this->page_slug ) );
                exit();
            }
        }
    }

    /**
	 * License Page CSS
     *
     * @since v.1.0.0
	 * @param NULL
	 * @return STRING
	 */
	public function license_css() { ?>
		<style type="text/css">
            .wopb-setting-header2 {
                margin-bottom: 0;
                border-bottom: none;
            }
            .wopb-license-dashboard .wopb-license-wrap {
                display:block;
                max-width: 1200px;
                border: solid 1px #ebebeb;
                box-sizing: border-box;
                border-radius: 4px;
                background-color: #fff;
                margin: 31px auto 0px;
                padding: 22px 43px 30px 40px;
            }

            .wopb-license-dashboard .wopb-licence-header {
                color: #2e2e2e;
                font-size: 20px;
                font-weight: 500;
                text-align: left;
                line-height: 50px;
                border-bottom: 1px solid #c3c3c3;
                margin-bottom: 30px;
            }

            .wopb-license-dashboard .wopb-license-label {
                color: #fff;
                font-size: 14px;
                text-align: center;
                border-radius: 2px;
                background-color: #1da522;
                padding: 4px 10px 4px 12px;
            }

            .wopb-license-dashboard .wopb-licence-check {
                color: #fff !important;
                font-size: 16px !important;
                text-align: center;
                line-height: normal !important;
                width: 100%;
                max-width: 188px !important;
                min-height: auto !important;
                display: inline-block;
                border-radius: 4px;
                box-sizing: content-box !important;
                background-color: #037fff !important;
                margin-top: 10px !important;
                padding: 9px 18px  !important;
            }

            .wopb-license-dashboard .wopb-license-btn--wrap  {
                display: inline;
                white-space: nowrap;
            }

            .wopb-license-dashboard .wopb-license-wrap table,
            .wopb-license-dashboard .wopb-license-wrap table td{
                padding: 0px;
            }

            .wopb-license-dashboard .wopb-license-wrap table input {
                width: 100%;
                max-width: 341px;
                margin: 0 12px 8px 0;
                padding: 6px 164px 6px 17px;
                border: solid 1px #e0e0e0;
                border-radius: 4px;
                background-color: #fff;
            }

            .wopb-license-dashboard .wopb-license-wrap table .description {
                color: #292929;
                font-size: 16px;
                line-height: 1.63;
                text-align: left;
                display: block;
                margin: 0px 0px 10px 3px;
            }

            .wopb-license-dashboard .wopb-invalid-label {
                color: #ffffff;
                font-size: 14px;
                text-align: center;
                white-space: nowrap;
                display: inline-block;
                background-color: #da0505;
                border-radius: 2px;
                margin-top: 10px;
                padding: 4px 10px 4px 12px;
            }

            .wopb-license-dashboard .wopb-license-btn--wrap .page-title-action{
                position: unset !important;
            }

            /* =================
                Instraction
            ================== */
            .wopb-license-dashboard .wopb-instraction-wrap {
                display: flex;
                justify-content: space-between;
                border: none !important;
                background: none !important;
                padding: 0px !important;
                margin-top: 40px !important;
            }

            @media only screen and (max-width: 1400px) {
                .wopb-license-dashboard .wopb-license-wrap {
                    margin: 40px 40px 40px 20px !important
                }
            }
            @media only screen and (max-width: 1300px) {
                .wopb-license-dashboard .wopb-instraction-wrap {
                    justify-content: flex-start;
                }
            }
            .wopb-license-dashboard .wopb-ins-content {
                padding: 0px 43px 30px 0px;
            }

            .wopb-license-dashboard .wopb-ins-step {
                margin-bottom: 20px;
            }

            .wopb-license-dashboard .wopb-ins-title {
                color: #292929;
                font-size: 28px;
                text-align: left;
                font-weight: bold;
                line-height: 1.29;
                margin: 0 0px 22px 0;
            }

            .wopb-license-dashboard .wopb-ins-step span {
                color: #292929;
                font-size: 20px;
                font-weight: bold;
                text-align: left;
                margin: 0 0px 10px 0;
            }

            .wopb-license-dashboard .wopb-ins-step div {
                color: #292929;
                font-size: 16px;
                font-weight: normal;
                text-align: left;
                margin: 10px 0 0;
            }

            .wopb-license-dashboard .wopb-licence-video {
                margin-top: 50px;
            }

            /* Instracton Box */
            .wopb-license-dashboard .wopb-ins-box {
                width: 100%;
                height: fit-content;
                max-width: 381px;
                border: solid 2px #e6a11e;
                border-radius: 4px;
                background-color: #fff;
                padding: 35px 35px 35px 29px;
            }

            @media only screen and (max-width: 1024px) {
                .wopb-license-dashboard .wopb-instraction-wrap {
                    flex-wrap: wrap;
                }
            }

            .wopb-license-dashboard .wopb-ins-box a {
                color: #fff;
                font-size: 18px;
                font-weight: 500;
                text-align: center;
                letter-spacing: normal;
                text-decoration: none;
                display: block;
                max-width: 187px;
                border-radius: 4px;
                background-color: #e6a11e;
                padding: 15px 29px 15px 35px;
            }

            .wopb-license-dashboard .wopb-ins-box__heading {
                display: flex;
                align-items: center;
                gap: 20px;
            }

            .wopb-license-dashboard .wopb-ins-box__heading span {
                color: #8b8b8b;
                font-size: 30px;
                line-height: unset !important;
                display: block;
            }

            .wopb-license-dashboard .wopb-ins-box__heading div {
                color: #212121;
                font-size: 20px;
                font-weight: 500;
                text-align: left;
                height: 26px;
            }

            .wopb-license-dashboard .wopb-ins-box__desc {
                color: #484848;
                font-size: 16px;
                text-align: left;
                margin: 20px 0px 20px 0px;
            };

        </style>
    <?php
    }
}