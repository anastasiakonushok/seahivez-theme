<?php

/**
 * SeaHivez location coordinates and map/weather shared config.
 *
 * @package seahivez-theme
 */

/**
 * Yacht departure port coordinates (S'Arenal, Mallorca).
 *
 * @return array{lat: float, lng: float, label: string, place: string, maps_url: string}
 */
function seahivez_get_port_location()
{
	$lat = 39.501457098007364;
	$lng = 2.746069652847715;

	return array(
		'lat'      => $lat,
		'lng'      => $lng,
		'label'    => __("S'Arenal Marina", 'seahivez-theme'),
		'place'    => __("S'Arenal, Mallorca", 'seahivez-theme'),
		'maps_url' => sprintf(
			'https://www.google.com/maps/search/?api=1&query=%s,%s',
			rawurlencode((string) $lat),
			rawurlencode((string) $lng)
		),
	);
}

/**
 * Google Maps JavaScript API key (browser-restricted).
 *
 * @return string
 */
function seahivez_get_google_maps_api_key()
{
	return 'AIzaSyDbfR3ucylbi6DwwkPD64Q1KS0gOdwQsms';
}

/**
 * Google Cloud Map ID.
 *
 * @return string
 */
function seahivez_get_google_maps_map_id()
{
	return '7b593fc817ebc3b4aa8f9fad';
}

/**
 * Server-side Google Weather API key.
 *
 * @return string
 */
function seahivez_get_weather_api_key()
{
	if (defined('SEAHIVEZ_WEATHER_API_KEY') && SEAHIVEZ_WEATHER_API_KEY) {
		return (string) SEAHIVEZ_WEATHER_API_KEY;
	}

	return 'AIzaSyBnT1IMmwGJ-CL__i2zcZRdu22cZA3F0Uk';
}
