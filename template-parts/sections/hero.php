<?php
/**
 * Flexible section: Hero
 *
 * @package seahivez-theme
 */

$hero = seahivez_map_acf_hero_section();

if ( empty( $hero['heading'] ) ) {
	return;
}

get_template_part( 'template-parts/home/hero', null, array( 'hero' => $hero ) );
