<?php
/**
 * Template Name: Experiences
 *
 * @package seahivez-theme
 */

get_header();

get_template_part( 'template-parts/page/page-hero', null, seahivez_get_experiences_page_hero() );

get_template_part( 'template-parts/experiences/experiences-list' );
get_template_part( 'template-parts/page/booking-cta' );

get_footer();
