<?php
/**
 * Template Name: Extras
 *
 * @package seahivez-theme
 */

get_header();

get_template_part(
	'template-parts/page/page-hero',
	null,
	seahivez_get_page_hero_defaults(
		array(
			'eyebrow'     => __( 'Toys & Extras', 'seahivez-theme' ),
			'heading'     => __( 'More ways to enjoy the water', 'seahivez-theme' ),
			'description' => __( 'From essential equipment included in every charter to premium water toys available on request.', 'seahivez-theme' ),
			'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/1.jpg' ),
			'image_alt'   => __( 'Water toys and extras', 'seahivez-theme' ),
			'compact'     => true,
		)
	)
);

get_template_part( 'template-parts/extras/extras-content' );
get_template_part( 'template-parts/page/booking-cta' );

get_footer();
