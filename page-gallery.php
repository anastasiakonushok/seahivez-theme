<?php
/**
 * Template Name: Gallery
 *
 * @package seahivez-theme
 */

get_header();

get_template_part(
	'template-parts/page/page-hero',
	null,
	seahivez_get_page_hero_defaults(
		array(
			'eyebrow'     => __( 'Gallery', 'seahivez-theme' ),
			'heading'     => __( 'Life on board SeaHivez', 'seahivez-theme' ),
			'description' => __( 'Discover the spaces, details and Mediterranean light that define the SeaHivez experience.', 'seahivez-theme' ),
			'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/3.jpg' ),
			'image_alt'   => __( 'SeaHivez gallery', 'seahivez-theme' ),
			'compact'     => true,
		)
	)
);

get_template_part( 'template-parts/gallery/gallery-grid' );
get_template_part( 'template-parts/page/booking-cta' );

get_footer();
