<?php

class BlocksyExtensionLocalGoogleFonts {
	private $option_name = 'blocksy_ext_local_google_fonts_settings';

	public function __construct() {
		add_filter('blocksy_typography_font_sources', function ($sources) {
			$settings = $this->get_settings();

			if (
				! isset($settings['fonts'])
				||
				! isset($settings['last_saved'])
				||
				! $settings['last_saved']
			) {
				return $sources;
			}

			unset($sources['google']);
			return $sources;
		});

		add_filter(
			'gspb_local_font_array',
			function ($localfont) {
				$localfonts = [];

				if (! empty($localfont)) {
					$localfonts = json_decode($localfont, true);
				}

				$settings = $this->get_settings();

				if (
					! isset($settings['fonts'])
					||
					! isset($settings['last_saved'])
					||
					! $settings['last_saved']
				) {
					return $localfont;
				}

				$themefonts = [];

				foreach ($settings['fonts'] as $font) {
					$themefonts[$font['name']] = [
						'ttf' => '',
						'woff2' => '',
						'woff' => ''
					];
				}

				$mergefonts = array_merge($localfonts, $themefonts);

				return json_encode($mergefonts);
			}
		);

		add_filter('tpgb_google_font_load', function ($fonts) {
			$settings = $this->get_settings();

			if (
				! isset($settings['fonts'])
				||
				! isset($settings['last_saved'])
				||
				! $settings['last_saved']
			) {
				return $fonts;
			}

			return false;
		});

		add_filter('blocksy:typography:google:use-remote', function () {
			$settings = $this->get_settings();

			if (
				! isset($settings['fonts'])
				||
				! isset($settings['last_saved'])
				||
				! $settings['last_saved']
			) {
				return true;
			}

			return false;
		});

		add_filter('stackable_enqueue_font', function ($do) {
			$settings = $this->get_settings();

			if (
				! isset($settings['fonts'])
				||
				! isset($settings['last_saved'])
				||
				! $settings['last_saved']
			) {
				return $do;
			}

			return false;
		});

		add_filter('fl_builder_google_fonts_pre_enqueue', function($fonts) {
			return array();
		});

		add_action('wp_enqueue_scripts', function() {
			global $wp_styles;

			if (isset($wp_styles->queue)) {
				foreach ($wp_styles->queue as $key => $handle) {
					if (false !== strpos($handle, 'fl-builder-google-fonts-')) {
						unset($wp_styles->queue[$key]);
					}
				}
			}
		}, 101);

		add_filter('fl_builder_font_families_google', function ($f) {
			$settings = $this->get_settings();

			if (
				! isset($settings['fonts'])
				||
				! isset($settings['last_saved'])
				||
				! $settings['last_saved']
			) {
				return $f;
			}

			return [];
		});

		add_filter('fl_theme_system_fonts', [$this, 'handle_beaver_fonts'] );
		add_filter('fl_builder_font_families_system', [$this, 'handle_beaver_fonts'] );

		add_filter('kadence_blocks_print_google_fonts', function ($do) {
			$settings = $this->get_settings();

			if (
				! isset($settings['fonts'])
				||
				! isset($settings['last_saved'])
				||
				! $settings['last_saved']
			) {
				return $do;
			}

			return false;
		});

		add_action('blocksy:global-dynamic-css:enqueue', function ($args) {
			$settings = $this->get_settings();

			if (! isset($settings['cached_css'])) {
				return;
			}

			if (blocksy_dynamic_styles_should_call(
				array_merge([
					'chunk' => 'global'
				], $args)
			)) {
				$args['css']->put(
					\Blocksy_Css_Injector::get_inline_keyword(),
					$settings['cached_css']
				);
			}
		});

		add_action('blocksy:admin-dynamic-css:enqueue', function ($args) {
			$settings = $this->get_settings();

			if (! isset($settings['cached_css'])) {
				return;
			}

			if (wp_style_is('wp-edit-blocks')) {
				return;
			}

			$args['css']->put(
				\Blocksy_Css_Injector::get_inline_keyword(),
				$settings['cached_css']
			);
		});

		add_action('init', function () {
			$settings = $this->get_settings();

			if (! isset($settings['cached_css'])) {
				return;
			}

			wp_add_inline_style(
				'wp-edit-blocks',
				$settings['cached_css']
			);
		});

		add_filter('elementor/fonts/groups', function ($font_groups) {
			$settings = $this->get_settings();

			if (
				! isset($settings['fonts'])
				||
				! isset($settings['last_saved'])
				||
				! $settings['last_saved']
			) {
				return $font_groups;
			}

			unset($font_groups['googlefonts']);
			unset($font_groups['earlyaccess']);

			$font_groups['blocksy-local-google-fonts'] = __('Local Google Fonts', 'blocksy-companion');
			return $font_groups;
		});

		add_filter('elementor/fonts/additional_fonts', function ($fonts) {
			$settings = $this->get_settings();

			if (
				! isset($settings['fonts'])
				||
				! isset($settings['last_saved'])
				||
				! $settings['last_saved']
			) {
				return $fonts;
			}

			foreach ($settings['fonts'] as $family) {
				$fonts[$family['name']] = 'blocksy-local-google-fonts';
			}

			return $fonts;
		});

		add_filter('blocksy_typography_font_sources', function ($sources) {
			$font_families = [];
			$settings = $this->get_settings();

			if (! isset($settings['fonts'])) {
				return $sources;
			}

			foreach ($settings['fonts'] as $single_family) {
				$google_font = $this->get_single_font($single_family['name']);

				if (! is_array($google_font['all_variations'])) {
					continue;
				}

				if (count($google_font['all_variations']) === 0) {
					continue;
				}

				$font_families[] = [
					'family' => $single_family['name'],
					'display' => $single_family['name'],
					'source' => 'local-google-fonts',
					'variations' => [],
					'all_variations' => array_map(function ($variation) {
						return $variation;
					}, $google_font['all_variations'])
				];
			}

			$sources['local-google-fonts'] = [
				'type' => 'local-google-fonts',
				'families' => $font_families
			];

			return $sources;
		});
	}

	public function handle_beaver_fonts($system_fonts) {
		$settings = $this->get_settings();

		if (
			! isset($settings['fonts'])
			||
			! isset($settings['last_saved'])
			||
			! $settings['last_saved']
		) {
			return $system_fonts;
		}

		foreach ($settings['fonts'] as $single_family) {
			$google_font = $this->get_single_font($single_family['name']);

			if (! is_array($google_font['all_variations'])) {
				continue;
			}

			if (count($google_font['all_variations']) === 0) {
				continue;
			}

			$system_fonts[$single_family['name']] = array(
				'fallback' => 'Verdana, Arial, sans-serif',
				'weights' => array_map(function ($variation) {
					$initial_variation = $variation;

					$variation = str_replace('n', '', $variation);
					$variation = str_replace('i', '', $variation);
					$variation = intval($variation) * 100;

					if ($initial_variation[0] === 'i') {
						$variation .= 'i';
					}

					return $variation;
				}, $google_font['all_variations'])
			);
		}

		return $system_fonts;
	}

	public function get_settings() {
		return get_option($this->option_name, [
			'fonts' => [
				/*
				[
					'name' => 'ProximaNova',
				]
				*/
			],
			'cached_css' => '',
			'last_saved' => null
		]);
	}

	public function set_settings($value) {
		update_option($this->option_name, $value);
	}

	public function get_remote_css_for($settings) {
		$url = 'https://fonts.googleapis.com/css2?';

		$families = [];

		foreach ($settings['fonts'] as $family) {
			$to_push = 'family=' . urlencode($family['name']) . ':';

			$google_font = $this->get_single_font($family['name']);

			$ital_vars = [];
			$wght_vars = [];

			foreach ($google_font['all_variations'] as $variation) {
				$var_to_push = intval($variation[1]) * 100;
				$var_to_push .= $variation[0] === 'i' ? 'i' : '';

				if ($variation[0] === 'i') {
					$ital_vars[] = intval($variation[1]) * 100;
				} else {
					$wght_vars[] = intval($variation[1]) * 100;
				}
			}

			sort($ital_vars);
			sort($wght_vars);

			$axis_tag_list = [];

			if (count($ital_vars) > 0) {
				$axis_tag_list[] = 'ital';
			}

			if (count($wght_vars) > 0) {
				$axis_tag_list[] = 'wght';
			}

			$to_push .= implode(',', $axis_tag_list);
			$to_push .= '@';

			$all_vars = [];

			foreach ($ital_vars as $ital_var) {
				$all_vars[] = '0,' . $ital_var;
			}

			foreach ($wght_vars as $wght_var) {
				if (count($axis_tag_list) > 1) {
					$all_vars[] = '1,' . $wght_var;
				} else {
					$all_vars[] = $wght_var;
				}
			}

			$to_push .= implode(';', $all_vars);

			$families[] = $to_push;
		}

		$families = implode('&', $families);

		if (empty($families)) {
			return null;
		}

		$url .= $families;
		$url .= '&display=swap';

		$options = [
			'http' => [
				'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:87.0) Gecko/20100101 Firefox/87.0'
			]
		];

		$context = stream_context_create($options);

		return @blc_load_xml_file(
			$url,
			'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:87.0) Gecko/20100101 Firefox/87.0'
		);
	}

	public function start_fonts_persistence($settings) {
		$this->maybe_prepare_theme_uploads_path([
			'should_generate' => true
		]);
	}

	public function persist_fonts_for($settings) {
		$css_to_cache = $this->get_remote_css_for($settings);

		$urls = array_map(function ($url) {
			return str_replace(')', '', $url);
		}, $this->getUrls($css_to_cache));

		$theme_paths = $this->maybe_prepare_theme_uploads_path();

		foreach ($urls as $url) {
			$parsed_url = parse_url($url);
			$dirname = $theme_paths['google_path'] . dirname($parsed_url['path']);

			if (! $this->wp_filesystem->is_dir($dirname)) {
				wp_mkdir_p($dirname);
			}

			$this->wp_filesystem->put_contents(
				$theme_paths['google_path'] . $parsed_url['path'],
				blc_load_xml_file($url)
			);
		}
	}

	public function conclude_fonts_persistence($settings) {
		$css_to_cache = $this->get_remote_css_for($settings);

		$urls = array_map(function ($url) {
			return str_replace(')', '', $url);
		}, $this->getUrls($css_to_cache));

		$theme_paths = $this->maybe_prepare_theme_uploads_path();

		foreach ($urls as $url) {
			$parsed_url = parse_url($url);

			$css_to_cache = str_replace(
				$url,
				blc_empty_site_host_and_domain_for(
					$theme_paths['google_url'] . $parsed_url['path']
				),
				$css_to_cache
			);
		}

		if ($css_to_cache) {
			$settings['cached_css'] = $css_to_cache;
		}

		$this->set_settings($settings);
	}

	public function get_single_font($family) {
		if (! class_exists('\Blocksy_Fonts_Manager')) {
			return null;
		}

		$m = new \Blocksy_Fonts_Manager();

		$all_google_fonts = $m->get_googgle_fonts();

		foreach ($all_google_fonts as $font) {
			if ($font['family'] === $family) {
				return $font;
			}
		}

		return null;
	}

	private function getUrls($string) {
		$regex = '/https?\:\/\/[^\" ]+/i';
		preg_match_all($regex, $string, $matches);
		return ($matches[0]);
	}

	private function maybe_prepare_theme_uploads_path($args = []) {
		$args = wp_parse_args($args, [
			'should_generate' => false,
		]);

		require_once (ABSPATH . '/wp-admin/includes/file.php');
		WP_Filesystem();

		global $wp_filesystem;

		$this->wp_filesystem = $wp_filesystem;
		$uploads = wp_upload_dir();

		// Theme folders in `uploads` directory.
		$folders_in_uploads = [
			'google' => 'blocksy/local-google-fonts'
		];

		foreach($folders_in_uploads as $folder => $path) {
			// Server path.
			$theme_paths[
				$folder . '_path'
			] = $uploads['basedir'] . '/' . $path;

			// URL.
			$theme_paths[
				$folder . '_url'
			] = $uploads['baseurl'] . '/' . $path;
		}

		if (! $this->has_direct_access()) {
			return false;
		}

		if (! $this->wp_filesystem) {
			return false;
		}

		if (! $args['should_generate']) {
			return $theme_paths;
		}

		foreach(array_keys($folders_in_uploads) as $folder) {
			$path = $theme_paths[$folder . '_path'];
			$parent = dirname($path);

			if ($this->wp_filesystem->is_writable($parent)) {
				if ($folder === 'google') {
					$this->wp_filesystem->rmdir($path, true);
				}

				if (! $this->wp_filesystem->is_dir($path)) {
					$this->wp_filesystem->mkdir($path);
				}
			}
		}

		return $theme_paths;
	}

	public function has_direct_access( $context = null ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		WP_Filesystem();

		/** @var WP_Filesystem_Base $wp_filesystem */
		global $wp_filesystem;

		if ($wp_filesystem) {
			if ($wp_filesystem->method !== 'direct') {
				if ( is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->get_error_code() ) {
					return true;
				} else {
					return $wp_filesystem->method === 'direct';
				}
			} else {
				return true;
			}
		}

		if ( get_filesystem_method( [], $context ) === 'direct' ) {
			ob_start();

			{
				$creds = request_filesystem_credentials( admin_url(), '', false, $context, null );
			}

			ob_end_clean();

			if ( WP_Filesystem( $creds ) ) {
				return true;
			}
		}

		return false;
	}
}

