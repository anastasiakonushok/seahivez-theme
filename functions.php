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

$seahivez_secrets = WP_CONTENT_DIR . '/seahivez-secrets.php';

if ( is_readable( $seahivez_secrets ) ) {
	require_once $seahivez_secrets;
}

require get_template_directory() . '/inc/mail.php';
require get_template_directory() . '/inc/booking.php';
require get_template_directory() . '/inc/checkout.php';
require get_template_directory() . '/inc/setup.php';
require get_template_directory() . '/inc/menus.php';
require get_template_directory() . '/inc/enqueue.php';
require get_template_directory() . '/inc/analytics.php';
require get_template_directory() . '/inc/schema.php';
require get_template_directory() . '/inc/logo.php';
require get_template_directory() . '/inc/icons.php';
require get_template_directory() . '/inc/arrows.php';
require get_template_directory() . '/inc/social.php';
require get_template_directory() . '/inc/languages.php';
require get_template_directory() . '/inc/location.php';
require get_template_directory() . '/inc/weather.php';
require get_template_directory() . '/inc/acf.php';
require get_template_directory() . '/inc/acf-data.php';
require get_template_directory() . '/inc/homepage-data.php';
require get_template_directory() . '/inc/page-acf.php';
require get_template_directory() . '/inc/page-data.php';
require get_template_directory() . '/inc/packages.php';
require get_template_directory() . '/inc/experience-charter-details.php';
require get_template_directory() . '/inc/charter-calculator.php';
require get_template_directory() . '/inc/extras-page-data.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';

if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}
