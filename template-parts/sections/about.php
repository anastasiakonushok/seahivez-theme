<?php
/**
 * Flexible section: About
 *
 * @package seahivez-theme
 */

$about = seahivez_map_acf_about_section();

if ( empty( $about['heading'] ) ) {
	return;
}

get_template_part( 'template-parts/home/about-yacht', null, array( 'about' => $about ) );
