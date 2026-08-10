<?php
/**
 * Theme setup and feature support.
 *
 * @package seahivez-theme
 */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function seahivez_theme_setup() {
	load_theme_textdomain( 'seahivez-theme', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 240,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	add_theme_support( 'customize-selective-refresh-widgets' );

	add_image_size( 'seahivez-hero', 1920, 1080, true );
	add_image_size( 'seahivez-card', 800, 600, true );
	add_image_size( 'seahivez-gallery', 600, 600, true );
}
add_action( 'after_setup_theme', 'seahivez_theme_setup' );

/**
 * Set the content width in pixels.
 *
 * @global int $content_width
 */
function seahivez_theme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'seahivez_theme_content_width', 1280 );
}
add_action( 'after_setup_theme', 'seahivez_theme_content_width', 0 );

/**
 * Register widget area.
 */
function seahivez_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'seahivez-theme' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'seahivez-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'seahivez_theme_widgets_init' );

/**
 * Returns a safe asset version based on file modification time.
 *
 * @param string $relative_path Path relative to the theme root.
 * @return string
 */
function seahivez_get_asset_version( $relative_path ) {
	$file_path = get_theme_file_path( $relative_path );

	if ( file_exists( $file_path ) ) {
		return (string) filemtime( $file_path );
	}

	return defined( 'SEAHIVEZ_VERSION' ) ? SEAHIVEZ_VERSION : '1.0.0';
}

/**
 * Booking page URL placeholder until ACF/SuperSaaS integration.
 *
 * @return string
 */
function seahivez_get_booking_url() {
	$booking_page = get_page_by_path( 'booking' );

	if ( $booking_page instanceof WP_Post ) {
		return get_permalink( $booking_page );
	}

	return home_url( '/booking/' );
}
