<?php
namespace WOPB_PRO;

defined('ABSPATH') || exit;

class Notice {
    public function __construct(){
		add_action('admin_init', array($this, 'admin_init_callback'));
		add_action('wp_ajax_wopb_install', array($this, 'install_callback'));
		add_action('admin_action_wopb_activate', array($this, 'activate_callback'));
		add_action('wp_ajax_wopb_dismiss_notice', array($this, 'set_dismiss_notice_callback'));
	}

	
	// Dismiss Notice Callback
	public function set_dismiss_notice_callback() {
		if (!wp_verify_nonce($_REQUEST['wpnonce'], 'wopb-nonce')) {
			return ;
		}
		update_option( 'wopb_dismiss_notice', 'yes' );
	}


	public function admin_init_callback(){
		if (!file_exists(WP_PLUGIN_DIR.'/product-blocks/product-blocks.php')) {
			add_action('admin_notices', array($this, 'installation_notice_callback'));
		} else if (file_exists(WP_PLUGIN_DIR.'/product-blocks/product-blocks.php') && ! is_plugin_active('product-blocks/product-blocks.php')) {
			add_action('admin_notices', array($this, 'activation_notice_callback'));
		}
	}


	public function installation_notice_callback() {
		if (!get_option('wopb_dismiss_notice')) {
			$this->notice_css();
			$this->notice_js();
			?>
			<div class="wc-install">
				<img width="150" src="<?php echo WOPB_PRO_URL.'assets/img/wopb.png'; ?>" alt="logo" />
				<div class="wc-install-body">
					<a class="wc-dismiss-notice" data-security=<?php echo wp_create_nonce('wopb-nonce'); ?>  data-ajax=<?php echo admin_url('admin-ajax.php'); ?> href="#"><span class="dashicons dashicons-no-alt"></span> <?php _e('Dismiss', 'product-blocks-pro'); ?></a>
					<h3><?php _e('Welcome to ProductX Pro.', 'product-blocks-pro'); ?></h3>
					<div class="wopb-pro-active-instruction"><?php _e('ProductX Pro is a block plugin. To use this plugins you have to install and activate ProductX.', 'product-blocks-pro'); ?></div>
					<a class="wopb-install-btn button button-primary button-hero" href="<?php echo add_query_arg(array('action' => 'woob_install'), admin_url()); ?>"><span class="dashicons dashicons-image-rotate"></span><?php _e('Install ProductX', 'product-blocks-pro'); ?></a>
					<div id="installation-msg"></div>
				</div>
			</div>
			<?php
		}
	}

	public function activation_notice_callback() {
		if (!get_option('wopb_dismiss_notice')) {
			$this->notice_css();
			$this->notice_js();
			?>
			<div class="wc-install">
				<img width="150" src="<?php echo WOPB_PRO_URL.'assets/img/wopb.png'; ?>" alt="logo" />
				<div class="wc-install-body">
					<a class="wc-dismiss-notice" data-security=<?php echo wp_create_nonce('wopb-nonce'); ?>  data-ajax=<?php echo admin_url('admin-ajax.php'); ?> href="#"><span class="dashicons dashicons-no-alt"></span> <?php _e('Dismiss', 'product-blocks-pro'); ?></a>
					<h3><?php _e('Welcome to ProductX Pro.', 'product-blocks-pro'); ?></h3>
					<div class="wopb-pro-active-instruction"><?php _e('ProductX Pro is a Block plugin. To use this plugins you have to install and activate ProductX.', 'product-blocks-pro'); ?></div>
					<a class="button button-primary button-hero" href="<?php echo add_query_arg(array('action' => 'wopb_activate'), admin_url()); ?>"><?php _e('Activate ProductX', 'product-blocks-pro'); ?></a>
				</div>
			</div>
			<?php
		}
	}


	public function notice_css() {
		?>
		<style type="text/css">
            .wc-install {
                display: -ms-flexbox;
                display: flex;
                align-items: center;
                background: #fff;
                margin-top: 40px;
                width: calc(100% - 50px);
                border: 1px solid #ccd0d4;
                padding: 15px;
                border-radius: 4px;
            }
            .wc-install img {
                margin-right: 10px;
            }
            .wc-install-body {
                -ms-flex: 1;
                flex: 1;
            }
            .wc-install-body > div.wopb-pro-active-instruction {
                max-width: 450px;
                margin-bottom: 20px;
            }
            .wc-install-body h3 {
                margin-top: 0;
                font-size: 24px;
                margin-bottom: 15px;
            }
            .wopb-install-btn {
                margin-top: 15px;
                display: inline-block;
            }
			.wc-install .dashicons{
				display: none;
				animation: dashicons-spin 1s infinite;
				animation-timing-function: linear;
			}
			.wc-install.loading .dashicons {
				display: inline-block;
				margin-top: 12px;
				margin-right: 5px;
			}
			@keyframes dashicons-spin {
				0% {
					transform: rotate( 0deg );
				}
				100% {
					transform: rotate( 360deg );
				}
			}
			.wc-dismiss-notice {
				position: relative;
				text-decoration: none;
				float: right;
				right: 26px;
			}
			.wc-dismiss-notice .dashicons{
				display: inline-block;
    			text-decoration: none;
				animation: none;
			}
		</style>
		<?php
	}


	public function notice_js() {
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				'use strict';
				$(document).on('click', '.wopb-install-btn', function(e){
					e.preventDefault();
					const $that = $(this);
					const wcInstall = $that.parents('.wc-install:first');
					$.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {install_plugin: 'product-blocks', action: 'wopb_install'},
						beforeSend: function(){
						    $that.parents('.wc-install').addClass('loading');
                        },
						success: function (data) {
							$that.parents('.wc-install-body:first').html(data);
						},
						complete: function () {
							wcInstall.removeClass('loading');
						}
					});
				});

				// Dismiss notice
				$(document).on('click', '.wc-dismiss-notice', function(e){
					e.preventDefault();
					const that = $(this);
					$.ajax({
						url: that.data('ajax'),
						type: 'POST',
						data: {
							action: 'wopb_dismiss_notice',
							wpnonce: that.data('security')
						},
						success: function (data) {
							that.parents('.wc-install').hide("slow", function() { that.parents('.wc-install').remove(); });
						},
						error: function(xhr) {
							console.log('Error occured. Please try again' + xhr.statusText + xhr.responseText );
						},
					});
				});

			});
		</script>
		<?php
	}


	public function install_callback(){
		include(ABSPATH . 'wp-admin/includes/plugin-install.php');
		include(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');

		if (! class_exists('Plugin_Upgrader')){
			include(ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php');
		}
		if (! class_exists('Plugin_Installer_Skin')) {
			include( ABSPATH . 'wp-admin/includes/class-plugin-installer-skin.php' );
		}

		$plugin = 'product-blocks';

		$api = plugins_api( 'plugin_information', array(
			'slug' => $plugin,
			'fields' => array(
				'short_description' => false,
				'sections' => false,
				'requires' => false,
				'rating' => false,
				'ratings' => false,
				'downloaded' => false,
				'last_updated' => false,
				'added' => false,
				'tags' => false,
				'compatibility' => false,
				'homepage' => false,
				'donate_link' => false,
			),
		) );

		if ( is_wp_error( $api ) ) {
			wp_die( $api );
		}
?>
        <a class="wc-dismiss-notice" data-security=<?php echo wp_create_nonce('wopb-nonce'); ?>  data-ajax=<?php echo admin_url('admin-ajax.php'); ?> href="#"><span class="dashicons dashicons-no-alt"></span> <?php _e('Dismiss', 'product-blocks-pro'); ?></a>
<?php
		$title = sprintf( __('Installing Plugin: %s', 'product-blocks-pro'), $api->name . ' ' . $api->version );
		$nonce = 'install-plugin_' . $plugin;
		$url = 'update.php?action=install-plugin&plugin=' . urlencode( $plugin );

		$upgrader = new \Plugin_Upgrader( new \Plugin_Installer_Skin( compact('title', 'url', 'nonce', 'plugin', 'api') ) );
		$upgrader->install($api->download_link);
		die();
	}


	public function activate_callback(){
		activate_plugin('product-blocks/product-blocks.php');
		wp_redirect(admin_url('admin.php?page=wopb-settings'));
		exit();
	}

}