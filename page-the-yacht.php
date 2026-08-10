<?php
/**
 * Template Name: The Yacht
 * Template for /the-yacht/ page.
 *
 * @package seahivez-theme
 */

get_header();

get_template_part( 'template-parts/page/page-hero', null, seahivez_get_yacht_page_hero() );
get_template_part( 'template-parts/yacht/yacht-intro' );
get_template_part( 'template-parts/yacht/yacht-specifications' );
get_template_part( 'template-parts/yacht/yacht-editorial' );
get_template_part( 'template-parts/yacht/yacht-crew' );
get_template_part( 'template-parts/yacht/yacht-gallery' );
get_template_part( 'template-parts/page/booking-cta' );

get_footer();
