<?php

$class = 'ct-language-switcher';

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

$language_type = blocksy_default_akg(
	'language_type',
	$atts,
	[
		'icon' => true,
		'label' => true,
	]
);

$language_label = blocksy_default_akg('language_label', $atts, 'long');

$ls_type = blocksy_default_akg('ls_type', $atts, 'inline');

$hide_current_language = blocksy_default_akg('hide_current_language', $atts, 'no') === 'yes';

$has_arrow = blocksy_akg('ls_dropdown_arrow', $atts, 'no') === 'yes';

$current_plugin = null;

if (function_exists('icl_object_id') && function_exists('icl_disp_language')) {
	$current_plugin = 'wpml';
}

if (function_exists('pll_the_languages')) {
	$current_plugin = 'polylang';
}

if (class_exists('TRP_Translate_Press')) {
	$current_plugin = 'translate-press';
}

if (function_exists('weglot_get_current_language')) {
	$current_plugin = 'weglot';
}

$output = '';

if ($current_plugin) {
	$output = blocksy_render_view(
		dirname(__FILE__) . '/plugins/' . $current_plugin . '.php',
		[
			'language_type' => $language_type,
			'language_label' => $language_label,
			'ls_type' => $ls_type,
			'hide_current_language' => $hide_current_language,
			'has_arrow' => $has_arrow,
		]
	);
}

?>

<div
	class="<?php echo esc_attr($class) ?>"
	data-type="<?php echo $ls_type ?>"
	<?php echo blocksy_attr_to_html($attr) ?>>

	<?php echo $output ?>

</div>
