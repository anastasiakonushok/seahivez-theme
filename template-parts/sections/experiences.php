<?php
/**
 * Flexible section: Experiences
 *
 * @package seahivez-theme
 */

$section = array(
	'eyebrow'     => get_sub_field( 'eyebrow' ),
	'heading'     => get_sub_field( 'heading' ),
	'experiences' => seahivez_get_packages_for_display( get_sub_field( 'packages' ) ),
);

get_template_part(
	'template-parts/home/experiences',
	null,
	array( 'section' => $section )
);
