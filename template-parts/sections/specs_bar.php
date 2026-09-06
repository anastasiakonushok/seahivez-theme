<?php
/**
 * Flexible section: Quick specs bar
 *
 * @package seahivez-theme
 */

get_template_part(
	'template-parts/home/specs-bar',
	null,
	array(
		'specs' => seahivez_map_acf_specs_bar(),
	)
);
