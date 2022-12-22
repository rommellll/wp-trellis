<?php

$sync_id = 'header_placements_item:language-switcher';

if (isset($panel_type) && $panel_type === 'footer') {
	$sync_id = 'footer_placements_item:language-switcher';
}

if (! isset($panel_type)) {
	$panel_type = 'header';
}

$options = [

	blocksy_rand_md5() => [
		'type' => 'ct-title',
		'label' => __( 'Top Level Options', 'blocksy-companion' ),
	],

	blocksy_rand_md5() => [
		'title' => __( 'General', 'blocksy-companion' ),
		'type' => 'tab',
		'options' => array_merge([

			'ls_type' => [
				'label' => false,
				'type' => $panel_type === 'header' ? 'ct-image-picker' : 'hidden',
				'value' => 'inline',
				'choices' => [
					'inline' => [
						'src' => blocksy_image_picker_url('ls-inline.svg'),
						'title' => __('Inline', 'blocksy-companion'),
					],

					'dropdown' => [
						'src' => blocksy_image_picker_url('ls-dropdown.svg'),
						'title' => __('Dropdown', 'blocksy-companion'),
					],
				],

				'sync' => [
					'id' => $sync_id
				]
			],

			'language_type' => [
				'label' => __( 'Display', 'blocksy-companion' ),
				'type' => 'ct-checkboxes',
				'design' => 'block',
				'view' => 'text',
				'divider' => 'top',
				'disableRevertButton' => true,
				'value' => [
					'icon' => true,
					'label' => true,
				],

				'choices' => blocksy_ordered_keys([
					'icon' => __( 'Flag', 'blocksy-companion' ),
					'label' => __( 'Label', 'blocksy-companion' ),
				]),

				'sync' => [
					'id' => $sync_id
				]
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'language_type/label' => true ],
				'options' => [
					'language_label' => [
						'label' => __( 'Label Style', 'blocksy-companion' ),
						'type' => 'ct-radio',
						'value' => 'long',
						'view' => 'text',
						'design' => 'block',
						'choices' => [
							'long' => __( 'Long', 'blocksy-companion' ),
							'short' => __( 'Short', 'blocksy-companion' ),
						],
						'sync' => [
							'id' => $sync_id
						]
					],
				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'ls_type' => 'inline' ],
				'options' => [

					'ls_items_spacing' => [
						'label' => __( 'Items Spacing', 'blocksy-companion' ),
						'type' => 'ct-slider',
						'min' => 5,
						'max' => 50,
						'value' => 20,
						'responsive' => true,
						'divider' => 'top',
					],

				],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'ls_type' => 'inline' ],
				'options' => [
					'hide_current_language' => [
						'label' => __( 'Hide Current Language', 'blocksy-companion' ),
						'type' => 'ct-switch',
						'design' => 'inline',
						'divider' => 'top',
						'disableRevertButton' => true,
						'value' => 'no',
						'sync' => [
							'id' => $sync_id
						]
					],
				],
			],

		], $panel_type === 'footer' ? [

			blocksy_rand_md5() => [
				'type' => 'ct-divider',
			],

			'footer_ls_horizontal_alignment' => [
				'type' => 'ct-radio',
				'label' => __( 'Horizontal Alignment', 'blocksy' ),
				'view' => 'text',
				'design' => 'block',
				'responsive' => true,
				'attr' => [ 'data-type' => 'alignment' ],
				'setting' => [ 'transport' => 'postMessage' ],
				'value' => 'CT_CSS_SKIP_RULE',
				'choices' => [
					'flex-start' => '',
					'center' => '',
					'flex-end' => '',
				],
			],

			'footer_ls_vertical_alignment' => [
				'type' => 'ct-radio',
				'label' => __( 'Vertical Alignment', 'blocksy' ),
				'view' => 'text',
				'design' => 'block',
				'divider' => 'top',
				'responsive' => true,
				'attr' => [ 'data-type' => 'vertical-alignment' ],
				'setting' => [ 'transport' => 'postMessage' ],
				'value' => 'CT_CSS_SKIP_RULE',
				'choices' => [
					'flex-start' => '',
					'center' => '',
					'flex-end' => '',
				],
			],

			'footer_visibility' => [
				'label' => __('Element Visibility', 'blocksy'),
				'type' => 'ct-visibility',
				'design' => 'block',
				'divider' => 'top',
				'sync' => 'live',
				'value' => [
					'desktop' => true,
					'tablet' => true,
					'mobile' => true,
				],
				'choices' => blocksy_ordered_keys([
					'desktop' => __( 'Desktop', 'blocksy' ),
					'tablet' => __( 'Tablet', 'blocksy' ),
					'mobile' => __( 'Mobile', 'blocksy' ),
				]),
			],

		] : []),
	],

	blocksy_rand_md5() => [
		'title' => __( 'Design', 'blocksy-companion' ),
		'type' => 'tab',
		'options' => [

			'ls_font' => [
				'type' => 'ct-typography',
				'label' => __( 'Font', 'blocksy-companion' ),
				'value' => blocksy_typography_default_values([
					'size' => '12px',
					'variation' => 'n5',
					'text-transform' => 'uppercase',
				]),
				'setting' => [ 'transport' => 'postMessage' ],
			],

			blocksy_rand_md5() => [
				'type' => 'ct-labeled-group',
				'label' => __( 'Font Color', 'blocksy-companion' ),
				'responsive' => true,
				'choices' => [
					[
						'id' => 'ls_label_color',
						'label' => __('Default State', 'blocksy-companion')
					],

					[
						'id' => 'transparent_ls_label_color',
						'label' => __('Transparent State', 'blocksy-companion'),
						'condition' => [
							'row' => '!offcanvas',
							'builderSettings/has_transparent_header' => 'yes',
						],
					],

					[
						'id' => 'sticky_ls_label_color',
						'label' => __('Sticky State', 'blocksy-companion'),
						'condition' => [
							'row' => '!offcanvas',
							'builderSettings/has_sticky_header' => 'yes',
						],
					],
				],
				'options' => [

					'ls_label_color' => [
						'label' => __( 'Font Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,

						'value' => [
							'default' => [
								'color' => 'var(--color)',
							],

							'hover' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __( 'Initial', 'blocksy-companion' ),
								'id' => 'default',
							],

							[
								'title' => __( 'Hover/Active', 'blocksy-companion' ),
								'id' => 'hover',
								'inherit' => 'var(--linkHoverColor)'
							],
						],
					],

					'transparent_ls_label_color' => [
						'label' => __( 'Font Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,

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
							],

							[
								'title' => __( 'Hover/Active', 'blocksy-companion' ),
								'id' => 'hover',
							],
						],
					],

					'sticky_ls_label_color' => [
						'label' => __( 'Font Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'block:right',
						'responsive' => true,

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
							],

							[
								'title' => __( 'Hover/Active', 'blocksy-companion' ),
								'id' => 'hover',
							],
						],
					],

				],
			],

			'ls_margin' => [
				'label' => __( 'Margin', 'blocksy-companion' ),
				'type' => 'ct-spacing',
				'divider' => 'top',
				'value' => blocksy_spacing_value([
					'linked' => true,
				]),
				'responsive' => true
			],

		],
	],


	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [ 'ls_type' => 'dropdown' ],
		'options' => [

			blocksy_rand_md5() => [
				'type' => 'ct-title',
				'label' => __( 'Dropdown Options', 'blocksy-companion' ),
			],

			blocksy_rand_md5() => [
				'title' => __( 'General', 'blocksy-companion' ),
				'type' => 'tab',
				'options' => [

					'ls_dropdown_arrow' => [
						'label' => __( 'Dropdown Arrow', 'blocksy' ),
						'type' => 'ct-switch',
						'value' => 'no',
						'sync' => [
							'id' => $sync_id
						]
					],

					'ls_dropdown_offset' => [
						'label' => __( 'Dropdown Top Offset', 'blocksy-companion' ),
						'type' => 'ct-slider',
						'value' => 15,
						'min' => 0,
						'max' => 50,
					],

					'ls_dropdown_items_spacing' => [
						'label' => __( 'Items Vertical Spacing', 'blocksy-companion' ),
						'type' => 'ct-slider',
						'value' => 15,
						'min' => 5,
						'max' => 30,
					],

				],
			],

			blocksy_rand_md5() => [
				'title' => __( 'Design', 'blocksy-companion' ),
				'type' => 'tab',
				'options' => [
					'ls_dropdown_font' => [
						'type' => 'ct-typography',
						'label' => __( 'Font', 'blocksy-companion' ),
						'value' => blocksy_typography_default_values(),
						'setting' => [ 'transport' => 'postMessage' ],
					],

					'ls_dropdown_font_color' => [
						'label' => __( 'Font Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'inline',
						'divider' => 'bottom',

						'value' => [
							'default' => [
								'color' => '#ffffff',
							],

							'hover' => [
								'color' => Blocksy_Css_Injector::get_skip_rule_keyword('DEFAULT'),
							],
						],

						'pickers' => [
							[
								'title' => __( 'Initial', 'blocksy-companion' ),
								'id' => 'default',
							],

							[
								'title' => __( 'Hover', 'blocksy-companion' ),
								'id' => 'hover',
								'inherit' => 'var(--linkHoverColor)'
							],
						],
					],

					'ls_dropdown_background' => [
						'label' => __( 'Background Color', 'blocksy-companion' ),
						'type'  => 'ct-color-picker',
						'design' => 'inline',
						'divider' => 'bottom',

						'value' => [
							'default' => [
								'color' => '#29333C',
							],
						],

						'pickers' => [
							[
								'title' => __( 'Initial', 'blocksy-companion' ),
								'id' => 'default',
							],
						],
					],

					'ls_dropdown_divider' => [
						'label' => __( 'Items Divider', 'blocksy-companion' ),
						'type' => 'ct-border',
						'design' => 'inline',
						'divider' => 'bottom',
						'value' => [
							'width' => 1,
							'style' => 'dashed',
							'color' => [
								'color' => 'rgba(255, 255, 255, 0.1)',
							],
						]
					],

					'ls_dropdown_shadow' => [
						'label' => __( 'Shadow', 'blocksy-companion' ),
						'type' => 'ct-box-shadow',
						'design' => 'inline',
						// 'responsive' => true,
						'divider' => 'bottom',
						'value' => blocksy_box_shadow_value([
							'enable' => true,
							'h_offset' => 0,
							'v_offset' => 10,
							'blur' => 20,
							'spread' => 0,
							'inset' => false,
							'color' => [
								'color' => 'rgba(41, 51, 61, 0.1)',
							],
						])
					],

					'ls_dropdown_radius' => [
						'label' => __( 'Border Radius', 'blocksy-companion' ),
						'type' => 'ct-spacing',
						'value' => blocksy_spacing_value([
							'linked' => true,
							'top' => '2px',
							'left' => '2px',
							'right' => '2px',
							'bottom' => '2px',
						]),
						// 'responsive' => true
					],

				],
			],

		],
	],
];

if ($panel_type === 'header') {
	$options[blocksy_rand_md5()] = [
		'type' => 'ct-condition',
		'condition' => [
			'wp_customizer_current_view' => 'tablet|mobile'
		],
		'options' => [
			blocksy_rand_md5() => [
				'type' => 'ct-divider',
			],

			'visibility' => [
				'label' => __( 'Element Visibility', 'blocksy' ),
				'type' => 'ct-visibility',
				'design' => 'block',
				'setting' => [ 'transport' => 'postMessage' ],
				'allow_empty' => true,
				'value' => [
					'tablet' => true,
					'mobile' => true,
				],

				'choices' => blocksy_ordered_keys([
					'tablet' => __( 'Tablet', 'blocksy' ),
					'mobile' => __( 'Mobile', 'blocksy' ),
				]),
			],

		],
	];
}
