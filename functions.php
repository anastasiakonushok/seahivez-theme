<?php
/**
 * SeaHivez Theme — functions loader
 *
 * @package seahivez-theme
 */

if ( ! defined( 'SEAHIVEZ_VERSION' ) ) {
	define( 'SEAHIVEZ_VERSION', '1.0.0' );
}

/**
 * Backward compatibility for legacy Underscores constant usage.
 */
if ( ! defined( '_S_VERSION' ) ) {
	define( '_S_VERSION', SEAHIVEZ_VERSION );
}

require get_template_directory() . '/inc/setup.php';
require get_template_directory() . '/inc/menus.php';
require get_template_directory() . '/inc/enqueue.php';
require get_template_directory() . '/inc/icons.php';
require get_template_directory() . '/inc/arrows.php';
require get_template_directory() . '/inc/social.php';
require get_template_directory() . '/inc/languages.php';
require get_template_directory() . '/inc/location.php';
require get_template_directory() . '/inc/weather.php';
require get_template_directory() . '/inc/homepage-data.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';

if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}
