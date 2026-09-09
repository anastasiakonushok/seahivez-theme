<?php
/**
 * Palma de Mallorca weather via Google Weather API (server-side).
 *
 * @package seahivez-theme
 */

/**
 * Palma de Mallorca coordinates for the homepage weather card.
 *
 * @return array{lat: float, lng: float, label: string}
 */
function seahivez_get_palma_weather_location() {
	return array(
		'lat'   => 39.5696,
		'lng'   => 2.6502,
		'label' => 'Palma de Mallorca, Spain',
	);
}

/**
 * Log a weather error without exposing secrets.
 *
 * @param string $message Short error description.
 */
function seahivez_log_weather_error( $message ) {
	error_log( '[SeaHivez Weather] ' . $message );
}

/**
 * Cached Palma forecast for the homepage widget.
 *
 * @return array<string, mixed>|null
 */
function seahivez_get_palma_weather() {
	$cached = get_transient( 'seahivez_palma_weather' );

	if ( is_array( $cached ) && ! empty( $cached['condition'] ) ) {
		return $cached;
	}

	$fresh = seahivez_fetch_palma_weather_forecast();

	if ( is_array( $fresh ) ) {
		set_transient( 'seahivez_palma_weather', $fresh, 20 * MINUTE_IN_SECONDS );
		return $fresh;
	}

	return null;
}

/**
 * Fetch today's forecast from Google Weather API.
 *
 * @return array<string, mixed>|null
 */
function seahivez_fetch_palma_weather_forecast() {
	$api_key = seahivez_get_weather_api_key();

	if ( '' === $api_key ) {
		seahivez_log_weather_error( 'API key not configured (SEAHIVEZ_WEATHER_API_KEY).' );
		return null;
	}

	$location = seahivez_get_palma_weather_location();

	$url = add_query_arg(
		array(
			'key'                => $api_key,
			'location.latitude'  => $location['lat'],
			'location.longitude' => $location['lng'],
			'days'               => 1,
		),
		'https://weather.googleapis.com/v1/forecast/days:lookup'
	);

	$response = wp_remote_get(
		$url,
		array(
			'timeout' => 6,
			'headers' => array(
				'Accept' => 'application/json',
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		seahivez_log_weather_error( 'Request failed: ' . $response->get_error_message() );
		return null;
	}

	$code = (int) wp_remote_retrieve_response_code( $response );

	if ( 200 !== $code ) {
		seahivez_log_weather_error( 'HTTP ' . $code );
		return null;
	}

	$body = json_decode( (string) wp_remote_retrieve_body( $response ), true );

	if ( ! is_array( $body ) || empty( $body['forecastDays'][0] ) || ! is_array( $body['forecastDays'][0] ) ) {
		seahivez_log_weather_error( 'Invalid or empty forecast response.' );
		return null;
	}

	return seahivez_parse_palma_forecast_day( $body['forecastDays'][0] );
}

/**
 * Normalize forecast day payload into a compact cached structure.
 *
 * @param array<string, mixed> $day forecastDays[0].
 * @return array<string, mixed>|null
 */
function seahivez_parse_palma_forecast_day( $day ) {
	$daytime = isset( $day['daytimeForecast'] ) && is_array( $day['daytimeForecast'] )
		? $day['daytimeForecast']
		: array();

	$condition = '';
	if ( ! empty( $daytime['weatherCondition']['description']['text'] ) ) {
		$condition = sanitize_text_field( $daytime['weatherCondition']['description']['text'] );
	}

	$icon = '';
	if ( ! empty( $daytime['weatherCondition']['iconBaseUri'] ) ) {
		$icon = esc_url_raw( $daytime['weatherCondition']['iconBaseUri'] . '.svg' );
	}

	$max = isset( $day['maxTemperature']['degrees'] )
		? (int) round( (float) $day['maxTemperature']['degrees'] )
		: null;
	$min = isset( $day['minTemperature']['degrees'] )
		? (int) round( (float) $day['minTemperature']['degrees'] )
		: null;

	$rain = isset( $daytime['precipitation']['probability']['percent'] )
		? (int) $daytime['precipitation']['probability']['percent']
		: null;
	$wind = isset( $daytime['wind']['speed']['value'] )
		? (int) round( (float) $daytime['wind']['speed']['value'] )
		: null;
	$gust = isset( $daytime['wind']['gust']['value'] )
		? (int) round( (float) $daytime['wind']['gust']['value'] )
		: null;
	$humidity = isset( $daytime['relativeHumidity'] )
		? (int) $daytime['relativeHumidity']
		: null;
	$uv = isset( $daytime['uvIndex'] )
		? (int) $daytime['uvIndex']
		: null;

	$date_label = '';
	if ( ! empty( $day['displayDate'] ) && is_array( $day['displayDate'] ) ) {
		$date_label = seahivez_format_palma_display_date( $day['displayDate'] );
	}

	if ( '' === $condition || null === $max || null === $min || '' === $date_label ) {
		seahivez_log_weather_error( 'Incomplete forecast data.' );
		return null;
	}

	return array(
		'condition' => $condition,
		'icon'      => $icon,
		'max'       => $max,
		'min'       => $min,
		'rain'      => $rain,
		'wind'      => $wind,
		'gust'      => $gust,
		'humidity'  => $humidity,
		'uv'        => $uv,
		'date'      => $date_label,
	);
}

/**
 * Format Google displayDate as "9 September 2026".
 *
 * @param array<string, int> $display_date year, month, day.
 * @return string
 */
function seahivez_format_palma_display_date( $display_date ) {
	$year  = (int) ( $display_date['year'] ?? 0 );
	$month = (int) ( $display_date['month'] ?? 0 );
	$day   = (int) ( $display_date['day'] ?? 0 );

	if ( $year < 1 || $month < 1 || $day < 1 ) {
		return '';
	}

	$timestamp = gmmktime( 12, 0, 0, $month, $day, $year );

	return wp_date( 'j F Y', $timestamp, new DateTimeZone( 'Europe/Madrid' ) );
}

/**
 * Fallback date when forecast is unavailable.
 *
 * @return string
 */
function seahivez_get_palma_weather_fallback_date() {
	return wp_date( 'j F Y', time(), new DateTimeZone( 'Europe/Madrid' ) );
}
