<?php

$languages_available = array_values((array)weglot_get_languages_available())[0];
$current_language = weglot_get_current_language();
$original_language = weglot_get_original_language();
$destination_languages = array_map(function ($object) {
	return $object['language_to'];
}, weglot_get_destination_languages());
$languages = array_merge(array($original_language), $destination_languages);
$list_class = 'class="ct-language"';

if ($ls_type === 'dropdown') {
	$list_class = '';

	echo sprintf(
		'<div class="ct-language ct-active-language %s" tabindex="0">',
		$language_type['icon'] ? 'weglot-flags flag-0 ' . $current_language : ''
	);

	echo '<span class="wglanguage-name">';
	if ($language_type['label']) {
		if ($language_label === 'long') {
			echo $languages_available[$current_language]->getLocalName();
		} else {
			echo $languages_available[$current_language]->getExternalCode();
		}
	}
	echo '</span>';

	if ($has_arrow) {
		echo '<svg class="ct-icon" width="8" height="8" viewBox="0 0 15 15"><path d="M2.1,3.2l5.4,5.4l5.4-5.4L15,4.3l-7.5,7.5L0,4.3L2.1,3.2z"></path></svg>';
	}

	echo '</div>';
}

echo '<ul ' . $list_class . '>';

foreach ($languages as $internal_code) {
	$language = $languages_available[$internal_code];
	$name = $language_label === 'long' ? $language->getLocalName() : $language->getExternalCode();
	$weglot_url = weglot_get_request_url_service()->get_weglot_url();
	$language_button_displayed = $weglot_url->getExcludeOption(
		$language,
		'language_button_displayed'
	);
	$link_button = $weglot_url->getForLanguage($language, true);

	if ($internal_code === $current_language && $hide_current_language) {
		continue;
	}

	if (!$language_button_displayed) {
		if (weglot_get_original_language() === $internal_code) {
			$link_button = $weglot_url->getForLanguage($language, true);
		} else {
			$link_button = $weglot_url->getForLanguage($language, false);
		}
	}

	if ($link_button) {
		$is_orig = $internal_code === weglot_get_original_language() ? 'true' : 'false';

		if (strpos($link_button, '?') !== false) {
			$link_button = str_replace(
				'?',
				"?wg-choose-original=$is_orig&",
				$link_button
			);
		} else {
			$link_button .= "?wg-choose-original=$is_orig";
		}

		echo sprintf(
			'<li class="%s %s">',
			$internal_code === $current_language ? 'current-lang' : '',
			$language_type['icon'] ? 'weglot-flags flag-0 ' . $internal_code : ''
		);

		echo sprintf(
			'<a data-wg-notranslate="" href="%s">%s</a>',
			esc_url($link_button),
			$language_type['label'] ? esc_html($name) : ''
		);

		echo '</li>';
	}
}

echo '</ul>';
