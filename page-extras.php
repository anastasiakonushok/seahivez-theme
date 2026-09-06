<?php
/**
 * Template Name: Extras
 *
 * @package seahivez-theme
 */

get_header();

get_template_part( 'template-parts/page/page-hero', null, seahivez_get_extras_page_hero() );

get_template_part( 'template-parts/extras/extras-content' );
get_template_part( 'template-parts/page/booking-cta' );

get_footer();
