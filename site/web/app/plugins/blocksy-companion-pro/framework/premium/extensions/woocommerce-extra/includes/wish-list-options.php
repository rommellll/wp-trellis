<?php

$all_pages = get_posts([
	'post_type' => 'page',
	'numberposts' => -1
]);

$pages_choices = [
	'' => __('Select a page', 'blocksy-companion')
];

foreach ($all_pages as $page) {
	$pages_choices[$page->ID] = $page->post_title;
}

$options = [
	blocksy_rand_md5() => [
		'label' => __('Products Wishlist', 'blocksy-companion'),
		'type' => 'ct-panel',
		'setting' => ['transport' => 'postMessage'],
		'inner-options' => [
			blocksy_rand_md5() => [
				'title' => __( 'General', 'blocksy-companion' ),
				'type' => 'tab',
				'options' => [

					'product_wishlist_display_for' => [
						'label' => __('Show Wishlist Page To', 'blocksy-companion'),
						'type' => 'ct-radio',
						'value' => 'logged_users',
						'view' => 'text',
						'design' => 'block',
						'choices' => [
							'logged_users' => __( 'Logged Users', 'blocksy-companion' ),
							'all_users' => __( 'All Users', 'blocksy-companion' ),
						],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => [ 'product_wishlist_display_for' => 'all_users' ],
						'options' => [

							'woocommerce_wish_list_page' => [
								'label' => __('Wish List Page', 'blocksy-companion'),
								'type' => 'ct-select',
								'value' => '',
								'view' => 'text',
								'design' => 'inline',
								'divider' => 'top',
								'desc' => __('The page you select here will display the wish list for your logged out users.', 'blocksy-companion'),
								'choices' => blocksy_ordered_keys($pages_choices)
							]

						],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-title',
						'label' => __( 'Display Wishlist Button On', 'blocksy-companion' ),
					],

					'has_archive_wishlist' => [
						'label' => __( 'Archive Pages', 'blocksy-companion' ),
						'type' => 'ct-switch',
						'value' => 'no',
						'sync' => blocksy_sync_whole_page([
							'loader_selector' => '[data-products]'
						]),
					],

					'has_single_wishlist' => [
						'label' => __( 'Single Product Pages', 'blocksy-companion' ),
						'type' => 'ct-switch',
						'value' => 'no',
						'sync' => blocksy_sync_whole_page([
							'loader_selector' => '.type-product .product-entry-wrapper'
						]),
					],

					'has_quick_view_wishlist' => [
						'label' => __( 'Quick View Modal', 'blocksy-companion' ),
						'type' => 'ct-switch',
						'value' => 'no',
						'sync' => blocksy_sync_whole_page([
							'loader_selector' => '[data-products]'
						]),
					],

					blocksy_rand_md5() => [
						'type' => 'ct-divider',
					],

					'has_wishlist_ajax_add_to_cart' => [
						'label' => __('AJAX Add To Cart', 'blocksy-companion'),
						'type' => 'ct-switch',
						'value' => 'no',
					],

					'has_wishlist_ajax_sync' => [
						'label' => __('Enable AJAX loading', 'blocksy-companion'),
						'type' => 'ct-switch',
						'value' => 'no',
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => [ 'product_wishlist_display_for' => 'all_users' ],
						'options' => [
							blocksy_get_options('single-elements/post-share-box', [
								'display_style' => 'switch',
								'prefix' => 'wish_list',
								'has_share_box_type' => false,
								'has_share_box_location1' => false,
								'has_bottom_share_box_spacing' => false,
								'has_share_items_border' => false,
								'has_forced_icons_spacing' => true
							])
						],
					],

				],
			],

			blocksy_rand_md5() => [
				'title' => __( 'Design', 'blocksy-companion' ),
				'type' => 'tab',
				'options' => [

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => [ 'has_archive_wishlist' => 'yes' ],
						'options' => [

							blocksy_rand_md5() => [
								'type' => 'ct-title',
								'label' => __( 'Archive Button', 'blocksy-companion' ),
							],

							'archive_wishlist_button_icon_color' => [
								'label' => __( 'Icon Color', 'blocksy-companion' ),
								'type'  => 'ct-color-picker',
								'design' => 'block:right',
								'responsive' => true,
								'setting' => [ 'transport' => 'postMessage' ],
								'value' => [

									'default' => [
										'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
									],

									'hover' => [
										'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
									],
								],

								'pickers' => [
									[
										'title' => __( 'Initial', 'blocksy-companion' ),
										'id' => 'default',
										'inherit' => 'var(--color)',
									],

									[
										'title' => __( 'Hover/Active', 'blocksy-companion' ),
										'id' => 'hover',
										'inherit' => '#ffffff',
									],
								],
							],

							'archive_wishlist_button_background_color' => [
								'label' => __( 'Background Color', 'blocksy-companion' ),
								'type'  => 'ct-color-picker',
								'design' => 'block:right',
								'divider' => 'top',
								'responsive' => true,
								'setting' => [ 'transport' => 'postMessage' ],
								'value' => [
									'default' => [
										'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
									],

									'hover' => [
										'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
									],
								],

								'pickers' => [
									[
										'title' => __( 'Initial', 'blocksy-companion' ),
										'id' => 'default',
										'inherit' => '#ffffff',
									],

									[
										'title' => __( 'Hover/Active', 'blocksy-companion' ),
										'id' => 'hover',
										'inherit' => 'var(--paletteColor1)',
									],
								],
							],

						],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => [ 'has_single_wishlist' => 'yes' ],
						'options' => [

							blocksy_rand_md5() => [
								'type' => 'ct-title',
								'label' => __( 'Single Product Button', 'blocksy-companion' ),
							],

							'single_wishlist_button_icon_color' => [
								'label' => __( 'Icon Color', 'blocksy-companion' ),
								'type'  => 'ct-color-picker',
								'design' => 'block:right',
								'responsive' => true,
								'setting' => [ 'transport' => 'postMessage' ],
								'value' => [
									'default' => [
										'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
									],

									'hover' => [
										'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
									],
								],

								'pickers' => [
									[
										'title' => __( 'Initial', 'blocksy-companion' ),
										'id' => 'default',
										'inherit' => 'var(--color)',
									],

									[
										'title' => __( 'Hover/Active', 'blocksy-companion' ),
										'id' => 'hover',
										'inherit' => 'var(--paletteColor1)',
									],
								],
							],

							// 'single_wishlist_button_background_color' => [
							// 	'label' => __( 'Border Color', 'blocksy-companion' ),
							// 	'type'  => 'ct-color-picker',
							// 	'design' => 'block:right',
							// 	'divider' => 'top',
							// 	'responsive' => true,
							// 	'setting' => [ 'transport' => 'postMessage' ],
							// 	'value' => [
							// 		'default' => [
							// 			'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							// 		],

							// 		'hover' => [
							// 			'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							// 		],
							// 	],

							// 	'pickers' => [
							// 		[
							// 			'title' => __( 'Default', 'blocksy-companion' ),
							// 			'id' => 'default',
							// 			'inherit' => 'var(--border-color)',
							// 		],

							// 		[
							// 			'title' => __( 'Hover/Active', 'blocksy-companion' ),
							// 			'id' => 'hover',
							// 			'inherit' => 'var(--border-color)',
							// 		],
							// 	],
							// ],

						],
					],

					blocksy_rand_md5() => [
						'type' => 'ct-condition',
						'condition' => [ 'has_quick_view_wishlist' => 'yes' ],
						'options' => [

							blocksy_rand_md5() => [
								'type' => 'ct-title',
								'label' => __( 'Quick View Modal Button', 'blocksy-companion' ),
							],

							'quick_view_wishlist_button_icon_color' => [
								'label' => __( 'Icon Color', 'blocksy-companion' ),
								'type'  => 'ct-color-picker',
								'design' => 'block:right',
								'responsive' => true,
								'setting' => [ 'transport' => 'postMessage' ],
								'value' => [
									'default' => [
										'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
									],

									'hover' => [
										'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
									],
								],

								'pickers' => [
									[
										'title' => __( 'Initial', 'blocksy-companion' ),
										'id' => 'default',
										'inherit' => 'var(--color)',
									],

									[
										'title' => __( 'Hover/Active', 'blocksy-companion' ),
										'id' => 'hover',
										'inherit' => 'var(--paletteColor1)',
									],
								],
							],

							// 'quick_view_wishlist_button_background_color' => [
							// 	'label' => __( 'Background Color', 'blocksy-companion' ),
							// 	'type'  => 'ct-color-picker',
							// 	'design' => 'block:right',
							// 	'divider' => 'top',
							// 	'responsive' => true,
							// 	'setting' => [ 'transport' => 'postMessage' ],
							// 	'value' => [
							// 		'default' => [
							// 			'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							// 		],

							// 		'hover' => [
							// 			'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							// 		],
							// 	],

							// 	'pickers' => [
							// 		[
							// 			'title' => __( 'Default', 'blocksy-companion' ),
							// 			'id' => 'default',
							// 			'inherit' => 'var(--paletteColor1)',
							// 		],

							// 		[
							// 			'title' => __( 'Hover/Active', 'blocksy-companion' ),
							// 			'id' => 'hover',
							// 			'inherit' => 'var(--paletteColor1)',
							// 		],
							// 	],
							// ],

						],
					],
				],
			],

		],
	],
];
