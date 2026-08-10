<?php
/**
 * Register navigation menus.
 *
 * @package seahivez-theme
 */

/**
 * Register theme menu locations.
 */
function seahivez_register_menus() {
	register_nav_menus(
		array(
			'primary'      => esc_html__( 'Primary Menu', 'seahivez-theme' ),
			'footer'       => esc_html__( 'Footer Menu', 'seahivez-theme' ),
			'footer-yacht' => esc_html__( 'Footer Yacht Menu', 'seahivez-theme' ),
		)
	);
}
add_action( 'after_setup_theme', 'seahivez_register_menus' );

/**
 * Add Tailwind-friendly classes to menu links.
 *
 * @param array    $atts Link attributes.
 * @param WP_Post  $item Menu item.
 * @param stdClass $args Menu arguments.
 * @return array
 */
function seahivez_nav_menu_link_attributes( $atts, $item, $args ) {
	if ( empty( $args->theme_location ) ) {
		return $atts;
	}

	if ( 'primary' === $args->theme_location ) {
		$atts['class'] = trim( ( $atts['class'] ?? '' ) . ' nav-link' );
	}

	if ( 'footer' === $args->theme_location || 'footer-yacht' === $args->theme_location ) {
		$atts['class'] = trim( ( $atts['class'] ?? '' ) . ' footer-link' );
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'seahivez_nav_menu_link_attributes', 10, 3 );

/**
 * Add classes to menu list items.
 *
 * @param string[] $classes Menu item classes.
 * @param WP_Post  $item    Menu item.
 * @param stdClass $args    Menu arguments.
 * @return string[]
 */
function seahivez_nav_menu_css_class( $classes, $item, $args ) {
	if ( ! empty( $args->theme_location ) && 'primary' === $args->theme_location ) {
		$classes[] = 'menu-item';
	}

	if ( ! empty( $args->theme_location ) && in_array( $args->theme_location, array( 'footer', 'footer-yacht' ), true ) ) {
		$classes[] = 'footer-menu-item';
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'seahivez_nav_menu_css_class', 10, 3 );
