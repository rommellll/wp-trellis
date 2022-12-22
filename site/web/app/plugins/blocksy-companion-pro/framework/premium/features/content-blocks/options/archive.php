<?php

$options = [
	'has_inline_code_editor' => [
		// 'type' => 'ct-switch',
		'type' => 'hidden',
		'value' => 'no'
	],

	'template_subtype' => [
		'label' => __( 'Replacement Behavior', 'blocksy-companion' ),
		'type' => 'ct-radio',
		'value' => 'card',
		'view' => 'text',
		'design' => 'block',
		'choices' => [
			'card' => __( 'Only Card', 'blocksy-companion' ),
			'canvas' => __( 'Full Page', 'blocksy-companion' ),
		]
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [ 'template_subtype' => 'card' ],
		'options' => [

			'previewedPost' => [
				'label' => __( 'Dynamic Content Preview', 'blocksy-companion' ),
				'type' => 'blocksy-previewed-post',
				'value' => [
					'post_id' => '',
					'post_type' => 'post'
				],
				'divider' => 'top:full',
				'desc' => __('Select a post/page to preview it\'s content inside the editor while building the archive.', 'blocksy-companion'),
			],

			'template_editor_width_source' => [
				'label' => __( 'Editor/Card Width', 'blocksy-companion' ),
				'type' => 'ct-radio',
				'value' => 'small',
				'view' => 'text',
				'design' => 'block',
				'divider' => 'top',
				'choices' => [
					'small' => __( 'Small', 'blocksy-companion' ),
					'medium' => __( 'Medium', 'blocksy-companion' ),
					'custom' => __( 'Custom', 'blocksy-companion' ),
				],
				'desc' => __('Set the editor width for better understanging the layout you are building (just for preview purpose, this option won\'t apply in frontend).', 'blocksy-companion'),
			],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'template_editor_width_source' => 'custom' ],
				'options' => [

					'template_editor_width' => [
						'label' => __( 'Custom Width', 'blocksy-companion' ),
						'type' => 'ct-slider',
						'value' => 1290,
						'min' => 100,
						'max' => 1900,
						// 'divider' => 'top',
					],

				],
			],

			'has_template_default_layout' => [
				'label' => __('Default Card Layout', 'blocksy-companion'),
				'type' => 'ct-switch',
				'value' => 'yes',
				'design' => 'inline',
				'divider' => 'top',
				'desc' => __('Inherit card wrapper settings from Customizer (background color, spacing, shadow).', 'blocksy-companion'),
			],

		],
	],


	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [ 'template_subtype' => 'canvas' ],
		'options' => [
			
			blocksy_rand_md5() => [
				'type' => 'ct-title',
				'variation' => 'simple-small-heading',
				'label' => __( 'Page Structure', 'blocksy-companion' ),
			],

			blocksy_rand_md5() => [
				'title' => __( 'General', 'blocksy' ),
				'type' => 'tab',
				'options' => [
					'content_block_structure' => [
						'label' => false,
						'type' => 'ct-image-picker',
						'value' => 'type-4',
						'choices' => [
							'type-3' => [
								'src' => blocksy_image_picker_url('narrow.svg'),
								'title' => __('Narrow Width', 'blocksy'),
							],

							'type-4' => [
								'src' => blocksy_image_picker_url('normal.svg'),
								'title' => __('Normal Width', 'blocksy'),
							],

							'type-2' => [
								'src' => blocksy_image_picker_url('left-single-sidebar.svg'),
								'title' => __('Left Sidebar', 'blocksy'),
							],

							'type-1' => [
								'src' => blocksy_image_picker_url('right-single-sidebar.svg'),
								'title' => __('Right Sidebar', 'blocksy'),
							],
						],
					],

					'content_style' => [
						'label' => __('Content Area Style', 'blocksy-companion'),
						'type' => 'ct-radio',
						'value' => 'wide',
						'view' => 'text',
						'design' => 'block',
						'divider' => 'top',
						'responsive' => true,
						'choices' => [
							'wide' => __( 'Wide', 'blocksy' ),
							'boxed' => __( 'Boxed', 'blocksy' ),
						],
					],

					'content_block_spacing' => [
						'label' => __('Content Area Vertical Spacing', 'blocksy-companion'),
						'type' => 'ct-radio',
						'value' => 'both',
						'divider' => 'top',
						'view' => 'text',
						'design' => 'block',
						'disableRevertButton' => true,
						'attr' => [ 'data-type' => 'content-spacing' ],
						'setting' => [ 'transport' => 'postMessage' ],
						'choices' => [
							'both'   => '<span></span>
							<i class="ct-tooltip-top">' . __( 'Top & Bottom', 'blocksy-companion' ) . '</i>',

							'top'    => '<span></span>
							<i class="ct-tooltip-top">' . __( 'Only Top', 'blocksy-companion' ) . '</i>',

							'bottom' => '<span></span>
							<i class="ct-tooltip-top">' . __( 'Only Bottom', 'blocksy-companion' ) . '</i>',

							'none'   => '<span></span>
							<i class="ct-tooltip-top">' . __( 'Disabled', 'blocksy-companion' ) . '</i>',
						]
					],

				],
			],

			blocksy_rand_md5() => [
				'title' => __( 'Design', 'blocksy' ),
				'type' => 'tab',
				'options' => [

					blocksy_get_options('single-elements/structure-design', [
						// 'has_background' => false
					])

				]
			],
		]
	],

	blocksy_rand_md5() => [
		'type' => 'ct-divider',
	],

	'conditions' => [
		'label' => __('Display Conditions', 'blocksy-companion'),
		'type' => 'blocksy-display-condition',
		'sectionAttr' => [ 'class' => 'ct-content-blocks-conditions' ],
		'filter' => 'archive',
		'value' => [
			[
				'type' => 'include',
				'rule' => 'singulars',
				'payload' => []
			]
		],

		'value' => [],
		'design' => 'block',
	],

	blocksy_rand_md5() => [
		'type' => 'ct-divider',
	],

	'has_content_block_expiration' => [
		'label' => __('Expiration Date/Time', 'blocksy-companion'),
		'type' => 'ct-switch',
		'value' => 'no',
		'design' => 'inline'
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => ['has_content_block_expiration' => 'yes'],
		'options' => [
			'expiration_date' => [
				'label' => false,
				'type' => 'date-time-picker',
				'value' => '',
				'disableRevertButton' => true,
			],
		]
	],

];

