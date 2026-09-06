<?php
/**
 * Template Name: Gallery
 *
 * @package seahivez-theme
 */

get_header();

get_template_part( 'template-parts/page/page-hero', null, seahivez_get_gallery_page_hero() );

get_template_part( 'template-parts/gallery/gallery-grid' );
get_template_part( 'template-parts/page/booking-cta' );

get_footer();
