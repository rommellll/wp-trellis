<?php

$languages =  apply_filters(
	'wpml_active_languages',
	null,
	'skip_missing=0&orderby=custom&order=asc'
);

if (empty($languages)) {
	return;
}

$list_class = 'class="ct-language"';

if ($ls_type === 'dropdown') {
	$list_class = '';

	foreach ($languages as $l) {
		if ($l['active']) {
			echo '<div class="ct-language ct-active-language" tabindex="0">';

			if ($l['country_flag_url'] && $language_type['icon']) {
				echo '<img src="' . $l['country_flag_url'] . '" height="12" alt="' . $l['language_code'] . '" width="18">';
			}

			if ($language_type['label']) {
				echo '<span>';

				if ($language_label === 'long') {
					echo $l['native_name'];
				} else {
					echo strtoupper($l['language_code']);
				}

				echo '</span>';
			}

			if ($has_arrow) {
				echo '<svg class="ct-icon" width="8" height="8" viewBox="0 0 15 15"><path d="M2.1,3.2l5.4,5.4l5.4-5.4L15,4.3l-7.5,7.5L0,4.3L2.1,3.2z"></path></svg>';
			}

			echo '</div>';
		}
	}
}

echo '<ul ' . $list_class . '>';

foreach ($languages as $l) {
	if ($l['active']) {
		if ($hide_current_language) {
			continue;
		}

		echo '<li class="current-lang">';
	} else {
		echo '<li>';
	}

	echo '<a href="' . $l['url'] . '" aria-label="' . $l['native_name'] . '">';
	if ($l['country_flag_url'] && $language_type['icon']) {
		echo '<img src="' . $l['country_flag_url'] . '" height="12" alt="' . $l['language_code'] . '" width="18">';
	}

	if ($language_type['label']) {
		echo '<span>';
		if ($language_label === 'long') {
			echo $l['native_name'];
		} else {
			echo strtoupper($l['language_code']);
		}
		echo '</span>';
	}

	echo '</a>';

	echo '</li>';
}

echo '</ul>';
