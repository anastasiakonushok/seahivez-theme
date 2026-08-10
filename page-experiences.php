<?php
/**
 * Template Name: Experiences
 *
 * @package seahivez-theme
 */

get_header();

get_template_part(
	'template-parts/page/page-hero',
	null,
	seahivez_get_page_hero_defaults(
		array(
			'eyebrow'     => __( 'Experiences', 'seahivez-theme' ),
			'heading'     => __( 'Choose your day on the water', 'seahivez-theme' ),
			'description' => __( 'Sunset escapes, half-day coastal cruising, or a full day discovering Mallorca from the sea.', 'seahivez-theme' ),
			'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/4.png' ),
			'image_alt'   => __( 'Sunset charter experience', 'seahivez-theme' ),
			'compact'     => true,
		)
	)
);

get_template_part( 'template-parts/experiences/experiences-list' );
get_template_part( 'template-parts/page/booking-cta' );

get_footer();
