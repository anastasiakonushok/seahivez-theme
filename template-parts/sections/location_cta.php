<?php
/**
 * Flexible section: Location & Booking CTA
 *
 * @package seahivez-theme
 */

get_template_part(
	'template-parts/home/location-cta',
	null,
	array(
		'location' => seahivez_map_acf_location_cta(),
	)
);
