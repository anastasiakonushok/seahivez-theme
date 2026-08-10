<?php
/**
 * Weather REST proxy with transient caching.
 *
 * Endpoint: GET /wp-json/seahivez/v1/weather
 * Uses fixed SeaHivez port coordinates and a server-side API key.
 *
 * @package seahivez-theme
 */

/**
 * Register weather REST routes.
 */
function seahivez_register_weather_routes() {
	register_rest_route(
		'seahivez/v1',
		'/weather',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'seahivez_rest_get_weather',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'seahivez_register_weather_routes' );

/**
 * REST callback: current weather for the SeaHivez port.
 *
 * @return WP_REST_Response|WP_Error
 */
function seahivez_rest_get_weather() {
	$cached = get_transient( 'seahivez_weather_cache' );

	if ( is_array( $cached ) && isset( $cached['temperature'], $cached['condition'] ) ) {
		$cached['cached'] = true;
		return rest_ensure_response( $cached );
	}

	$payload = seahivez_fetch_google_weather();

	if ( is_wp_error( $payload ) ) {
		return $payload;
	}

	set_transient( 'seahivez_weather_cache', $payload, 15 * MINUTE_IN_SECONDS );

	$payload['cached'] = false;

	return rest_ensure_response( $payload );
}

/**
 * Call Google Weather API for fixed port coordinates.
 *
 * @return array<string, mixed>|WP_Error
 */
function seahivez_fetch_google_weather() {
	$api_key = seahivez_get_weather_api_key();

	if ( '' === $api_key ) {
		return new WP_Error(
			'seahivez_weather_missing_key',
			__( 'Weather API key is not configured.', 'seahivez-theme' ),
			array( 'status' => 503 )
		);
	}

	$port = seahivez_get_port_location();

	$url = add_query_arg(
		array(
			'key'                 => $api_key,
			'location.latitude'   => $port['lat'],
			'location.longitude'  => $port['lng'],
			'unitsSystem'         => 'METRIC',
		),
		'https://weather.googleapis.com/v1/currentConditions:lookup'
	);

	$response = wp_remote_get(
		$url,
		array(
			'timeout' => 8,
			'headers' => array(
				'Accept' => 'application/json',
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		return new WP_Error(
			'seahivez_weather_request_failed',
			__( 'Unable to reach the weather service.', 'seahivez-theme' ),
			array( 'status' => 502 )
		);
	}

	$code = (int) wp_remote_retrieve_response_code( $response );
	$body = json_decode( (string) wp_remote_retrieve_body( $response ), true );

	if ( 200 !== $code || ! is_array( $body ) ) {
		return new WP_Error(
			'seahivez_weather_bad_response',
			__( 'Weather service returned an unexpected response.', 'seahivez-theme' ),
			array( 'status' => 502 )
		);
	}

	$temperature = null;
	if ( isset( $body['temperature']['degrees'] ) ) {
		$temperature = (int) round( (float) $body['temperature']['degrees'] );
	}

	$condition = '';
	if ( ! empty( $body['weatherCondition']['description']['text'] ) ) {
		$condition = sanitize_text_field( $body['weatherCondition']['description']['text'] );
	} elseif ( ! empty( $body['weatherCondition']['type'] ) ) {
		$condition = sanitize_text_field( ucwords( strtolower( str_replace( '_', ' ', $body['weatherCondition']['type'] ) ) ) );
	}

	$icon = '';
	if ( ! empty( $body['weatherCondition']['iconBaseUri'] ) ) {
		// Google provides a base URI; append .svg for a lightweight icon.
		$icon = esc_url_raw( $body['weatherCondition']['iconBaseUri'] . '.svg' );
	}

	$wind = null;
	if ( isset( $body['wind']['speed']['value'] ) ) {
		$wind = (int) round( (float) $body['wind']['speed']['value'] );
	}

	if ( null === $temperature || '' === $condition ) {
		return new WP_Error(
			'seahivez_weather_incomplete',
			__( 'Weather data is incomplete.', 'seahivez-theme' ),
			array( 'status' => 502 )
		);
	}

	return array(
		'temperature' => $temperature,
		'condition'   => $condition,
		'icon'        => $icon,
		'wind'        => $wind,
		'updated_at'  => gmdate( 'c' ),
	);
}
