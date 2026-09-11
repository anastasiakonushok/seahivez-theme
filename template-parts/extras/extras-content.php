<?php
/**
 * Extras page content orchestrator.
 *
 * @package seahivez-theme
 */

$sections = seahivez_get_extras_page_data();
$context  = array( 'sections' => $sections );

get_template_part( 'template-parts/extras/extras-intro', null, $context );
get_template_part( 'template-parts/extras/included-equipment', null, $context );
get_template_part( 'template-parts/extras/included-services', null, $context );
get_template_part( 'template-parts/extras/paid-extras', null, $context );
get_template_part( 'template-parts/extras/food-drinks', null, $context );
get_template_part( 'template-parts/extras/good-to-know', null, $context );
get_template_part( 'template-parts/extras/package-calculator', null, $context );
get_template_part( 'template-parts/extras/extras-gallery', null, $context );
get_template_part( 'template-parts/home/location-cta', null, array( 'location' => seahivez_get_page_booking_cta() ) );
