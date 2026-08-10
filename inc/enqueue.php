<?php
/**
 * Enqueue theme scripts and styles.
 *
 * @package seahivez-theme
 */

/**
 * Enqueue frontend assets.
 */
function seahivez_enqueue_assets() {
	$css_relative = 'assets/dist/main.css';
	$js_relative  = 'assets/dist/main.js';
	$css_path     = get_theme_file_path( $css_relative );
	$js_path      = get_theme_file_path( $js_relative );

	if ( file_exists( $css_path ) ) {
		wp_enqueue_style(
			'seahivez-main',
			get_theme_file_uri( $css_relative ),
			array(),
			seahivez_get_asset_version( $css_relative )
		);
	}

	if ( file_exists( $js_path ) ) {
		wp_enqueue_script(
			'seahivez-main',
			get_theme_file_uri( $js_relative ),
			array(),
			seahivez_get_asset_version( $js_relative ),
			true
		);

		wp_localize_script(
			'seahivez-main',
			'seahivezData',
			array(
				'weatherEndpoint' => esc_url_raw( rest_url( 'seahivez/v1/weather' ) ),
				'mapsApiKey'      => seahivez_get_google_maps_api_key(),
				'port'            => seahivez_get_port_location(),
			)
		);
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'seahivez_enqueue_assets' );

/**
 * Enqueue block editor assets if needed in the future.
 */
function seahivez_enqueue_block_editor_assets() {
	$css_relative = 'assets/dist/main.css';
	$css_path     = get_theme_file_path( $css_relative );

	if ( file_exists( $css_path ) ) {
		wp_enqueue_style(
			'seahivez-main-editor',
			get_theme_file_uri( $css_relative ),
			array(),
			seahivez_get_asset_version( $css_relative )
		);
	}
}
add_action( 'enqueue_block_editor_assets', 'seahivez_enqueue_block_editor_assets' );

/**
 * Preload the self-hosted Satoshi variable font.
 */
function seahivez_preload_satoshi_font() {
	$relative = 'assets/fonts/Satoshi-Variable.woff2';
	$path     = get_theme_file_path( $relative );

	if ( ! file_exists( $path ) ) {
		return;
	}

	printf(
		'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
		esc_url( get_theme_file_uri( $relative ) )
	);
}
add_action( 'wp_head', 'seahivez_preload_satoshi_font', 1 );

/**
 * Preload the homepage hero image for LCP.
 */
function seahivez_preload_hero_image() {
	if ( ! is_front_page() ) {
		return;
	}

	$hero = seahivez_get_home_hero_data();

	if ( empty( $hero['image'] ) ) {
		return;
	}

	printf(
		'<link rel="preload" href="%s" as="image">' . "\n",
		esc_url( $hero['image'] )
	);
}
add_action( 'wp_head', 'seahivez_preload_hero_image', 2 );
