<?php
/**
 * Template Name: FAQ
 *
 * @package seahivez-theme
 */

get_header();

get_template_part(
	'template-parts/page/page-hero',
	null,
	seahivez_get_page_hero_defaults(
		array(
			'eyebrow'     => __( 'FAQ', 'seahivez-theme' ),
			'heading'     => __( 'Everything you need to know', 'seahivez-theme' ),
			'description' => __( 'Practical answers before your day on the water with SeaHivez.', 'seahivez-theme' ),
			'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/3.jpg' ),
			'image_alt'   => __( 'FAQ', 'seahivez-theme' ),
			'compact'     => true,
		)
	)
);

get_template_part( 'template-parts/faq/faq-page' );

get_footer();
