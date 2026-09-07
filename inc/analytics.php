<?php
/**
 * Google Analytics (gtag.js).
 *
 * @package seahivez-theme
 */

/**
 * Google Analytics measurement ID.
 *
 * @return string
 */
function seahivez_get_google_analytics_id() {
	$measurement_id = defined( 'SEAHIVEZ_GA_MEASUREMENT_ID' )
		? SEAHIVEZ_GA_MEASUREMENT_ID
		: 'G-1WT6XP56H0';

	/**
	 * Filter the Google Analytics measurement ID.
	 *
	 * @param string $measurement_id GA4 measurement ID.
	 */
	return (string) apply_filters( 'seahivez_google_analytics_id', $measurement_id );
}

/**
 * Enqueue Google Analytics on the frontend.
 */
function seahivez_enqueue_google_analytics() {
	if ( is_admin() ) {
		return;
	}

	$measurement_id = seahivez_get_google_analytics_id();

	if ( '' === $measurement_id ) {
		return;
	}

	wp_enqueue_script(
		'seahivez-google-gtag',
		'https://www.googletagmanager.com/gtag/js?id=' . rawurlencode( $measurement_id ),
		array(),
		null,
		false
	);

	wp_script_add_data( 'seahivez-google-gtag', 'async', true );

	$inline_script = sprintf(
		"window.dataLayer = window.dataLayer || [];\nfunction gtag(){dataLayer.push(arguments);}\ngtag('js', new Date());\ngtag('config', '%s');",
		esc_js( $measurement_id )
	);

	wp_add_inline_script( 'seahivez-google-gtag', $inline_script, 'after' );
}
add_action( 'wp_enqueue_scripts', 'seahivez_enqueue_google_analytics', 5 );
