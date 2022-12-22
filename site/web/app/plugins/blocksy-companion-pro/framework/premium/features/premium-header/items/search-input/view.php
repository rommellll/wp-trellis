<?php

$search_through = blocksy_akg('search_through', $atts, [
	'post' => true,
	'page' => true,
	'product' => true
]);

$all_cpts = blocksy_manager()->post_types->get_supported_post_types();

if (function_exists('is_bbpress')) {
	$all_cpts[] = 'forum';
	$all_cpts[] = 'topic';
	$all_cpts[] = 'reply';
}

foreach ($all_cpts as $single_cpt) {
	if (! isset($search_through[$single_cpt])) {
		$search_through[$single_cpt] = true;
	}
}

$post_type = [];

foreach ($search_through as $single_post_type => $enabled) {
	if (
		! $enabled
		||
		! get_post_type_object($single_post_type)
	) {
		continue;
	}

	if (
		$single_post_type !== 'post'
		&&
		$single_post_type !== 'page'
		&&
		$single_post_type !== 'product'
		&&
		! in_array($single_post_type, $all_cpts)
	) {
		continue;
	}

	$post_type[] = $single_post_type;
}

$class = 'ct-search-box';

if ($panel_type === 'header') {
	$visibility = blocksy_default_akg('visibility', $atts, [
		'tablet' => true,
		'mobile' => true,
	]);
} else {
	$visibility = blocksy_default_akg('footer_visibility', $atts, [
		'desktop' => true,
		'tablet' => true,
		'mobile' => true,
	]);
}

$class .= ' ' . blocksy_visibility_classes($visibility);

$icon = '<svg class="ct-icon" aria-hidden="true" width="15" height="15" viewBox="0 0 15 15"><path d="M14.8,13.7L12,11c0.9-1.2,1.5-2.6,1.5-4.2c0-3.7-3-6.8-6.8-6.8S0,3,0,6.8s3,6.8,6.8,6.8c1.6,0,3.1-0.6,4.2-1.5l2.8,2.8c0.1,0.1,0.3,0.2,0.5,0.2s0.4-0.1,0.5-0.2C15.1,14.5,15.1,14,14.8,13.7z M1.5,6.8c0-2.9,2.4-5.2,5.2-5.2S12,3.9,12,6.8S9.6,12,6.8,12S1.5,9.6,1.5,6.8z"/></svg>';

if (function_exists('blc_get_icon') && isset($atts['icon'])) {
	$icon = blc_get_icon([
		'icon_descriptor' => blocksy_akg('icon', $atts, [
			'icon' => 'blc blc-search'
		]),
		'icon_container' => false,
		'icon_class' => 'ct-icon'
	]);
}

?>

<div
	class="<?php echo esc_attr($class) ?>"
	<?php echo blocksy_attr_to_html($attr) ?>>
	<?php get_search_form([
		'ct_post_type' => $post_type,
		'search_live_results' => blocksy_akg('enable_live_results', $atts, 'no'),
		'live_results_attr' => blocksy_akg(
			'live_results_images',
			$atts,
			'yes'
		) === 'yes' ? 'thumbs' : '',
		'search_placeholder' => blocksy_translate_dynamic(
			blocksy_default_akg(
				'search_box_placeholder',
				$atts,
				__('Search', 'blocksy-companion')
			),
			'header:' . $section_id . ':search-input:search_box_placeholder'
		),
		'icon' => $icon
	]); ?>
</div>
