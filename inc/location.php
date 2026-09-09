<?php
/**
 * SeaHivez location coordinates and map/weather shared config.
 *
 * Fixed Mallorca / S'Arenal port coordinates — not user-supplied.
 *
 * @package seahivez-theme
 */

/**
 * Yacht departure port coordinates (S'Arenal, Mallorca).
 *
 * @return array{lat: float, lng: float, label: string, place: string, maps_url: string}
 */
function seahivez_get_port_location() {
	$lat = 39.50087;
	$lng = 2.74671;

	return array(
		'lat'      => $lat,
		'lng'      => $lng,
		'label'    => 'SeaHivez',
		'place'    => "S'Arenal, Mallorca",
		'maps_url' => sprintf(
			'https://www.google.com/maps/search/?api=1&query=%s,%s',
			rawurlencode( (string) $lat ),
			rawurlencode( (string) $lng )
		),
	);
}

/**
 * Browser-safe Google Maps JavaScript API key.
 *
 * Define in wp-config.php:
 * define( 'SEAHIVEZ_GOOGLE_MAPS_API_KEY', 'your-browser-restricted-key' );
 *
 * @return string
 */
function seahivez_get_google_maps_api_key() {
	if ( defined( 'SEAHIVEZ_GOOGLE_MAPS_API_KEY' ) && SEAHIVEZ_GOOGLE_MAPS_API_KEY ) {
		return (string) SEAHIVEZ_GOOGLE_MAPS_API_KEY;
	}

	return '';
}

/**
 * Google Cloud Map ID (cloud-based map styling).
 *
 * Define in wp-config.php:
 * define( 'SEAHIVEZ_GOOGLE_MAPS_MAP_ID', 'your-map-id' );
 *
 * @return string
 */
function seahivez_get_google_maps_map_id() {
	if ( defined( 'SEAHIVEZ_GOOGLE_MAPS_MAP_ID' ) && SEAHIVEZ_GOOGLE_MAPS_MAP_ID ) {
		return (string) SEAHIVEZ_GOOGLE_MAPS_MAP_ID;
	}

	return '';
}

/**
 * Server-side Google Weather API key.
 *
 * Define in wp-config.php:
 * define( 'SEAHIVEZ_WEATHER_API_KEY', 'your-server-side-key' );
 *
 * @return string
 */
function seahivez_get_weather_api_key() {
	if ( defined( 'SEAHIVEZ_WEATHER_API_KEY' ) && SEAHIVEZ_WEATHER_API_KEY ) {
		return (string) SEAHIVEZ_WEATHER_API_KEY;
	}

	return '';
}
