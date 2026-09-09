<?php
/**
 * SeaHivez location coordinates and map/weather shared config.
 *
 * Fixed Mallorca / S'Arenal port coordinates — not user-supplied.
 *
 * @package seahivez-theme
 */

/**
 * Default Google Cloud Map ID (cloud-based styling).
 */
const SEAHIVEZ_DEFAULT_GOOGLE_MAPS_MAP_ID = '7b593fc817ebc3b4aa8f9fad';

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
		'label'    => __( "S'Arenal Marina", 'seahivez-theme' ),
		'place'    => __( "S'Arenal, Mallorca", 'seahivez-theme' ),
		'maps_url' => sprintf(
			'https://www.google.com/maps/search/?api=1&query=%s,%s',
			rawurlencode( (string) $lat ),
			rawurlencode( (string) $lng )
		),
	);
}

/**
 * Read a config value from environment or wp-config constant.
 *
 * @param string $env_name  Environment variable name.
 * @param string $const_name PHP constant name.
 * @return string
 */
function seahivez_get_env_or_constant( $env_name, $const_name ) {
	$from_env = getenv( $env_name );

	if ( false !== $from_env && '' !== $from_env ) {
		return (string) $from_env;
	}

	if ( defined( $const_name ) && constant( $const_name ) ) {
		return (string) constant( $const_name );
	}

	return '';
}

/**
 * Browser-safe Google Maps JavaScript API key.
 *
 * Priority:
 * 1. SEAHIVEZ_GOOGLE_MAPS_API_KEY environment variable
 * 2. SEAHIVEZ_GOOGLE_MAPS_API_KEY constant in wp-config.php
 *
 * @return string
 */
function seahivez_get_google_maps_api_key() {
	return seahivez_get_env_or_constant( 'SEAHIVEZ_GOOGLE_MAPS_API_KEY', 'SEAHIVEZ_GOOGLE_MAPS_API_KEY' );
}

/**
 * Google Cloud Map ID (cloud-based map styling).
 *
 * Priority:
 * 1. SEAHIVEZ_GOOGLE_MAPS_MAP_ID environment variable
 * 2. SEAHIVEZ_GOOGLE_MAPS_MAP_ID constant in wp-config.php
 * 3. Theme default Map ID
 *
 * @return string
 */
function seahivez_get_google_maps_map_id() {
	$map_id = seahivez_get_env_or_constant( 'SEAHIVEZ_GOOGLE_MAPS_MAP_ID', 'SEAHIVEZ_GOOGLE_MAPS_MAP_ID' );

	if ( '' !== $map_id ) {
		return $map_id;
	}

	return SEAHIVEZ_DEFAULT_GOOGLE_MAPS_MAP_ID;
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
	return seahivez_get_env_or_constant( 'SEAHIVEZ_WEATHER_API_KEY', 'SEAHIVEZ_WEATHER_API_KEY' );
}
