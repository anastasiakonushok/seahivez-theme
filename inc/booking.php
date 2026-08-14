<?php
/**
 * SuperSaaS booking widget configuration.
 *
 * @package seahivez-theme
 */

/**
 * Whether the current request is the booking page.
 *
 * @return bool
 */
function seahivez_is_booking_page() {
	return is_page_template( 'page-booking.php' ) || is_page( 'booking' );
}

/**
 * SuperSaaS account, schedule and widget options.
 *
 * @return array<string, mixed>
 */
function seahivez_get_supersaas_config() {
	return apply_filters(
		'seahivez_supersaas_config',
		array(
			'account'  => '634448:MAGICA_BOAT',
			'schedule' => '843010:magica_yacht',
			'options'  => array(
				'widget_type' => 'frame',
				'view'        => 'week',
				'modal_width' => '500px',
			),
		)
	);
}

/**
 * Enqueue SuperSaaS widget script on the booking page (head, before inline init).
 */
function seahivez_enqueue_supersaas_widget() {
	if ( ! seahivez_is_booking_page() ) {
		return;
	}

	wp_enqueue_script(
		'supersaas-widget',
		'https://cdn.supersaas.net/widget.js',
		array(),
		null,
		false
	);
}
add_action( 'wp_enqueue_scripts', 'seahivez_enqueue_supersaas_widget' );

/**
 * Inline SuperSaaS init JSON for the booking template.
 *
 * @return string
 */
function seahivez_get_supersaas_init_script() {
	$config = seahivez_get_supersaas_config();

	if ( empty( $config['account'] ) || empty( $config['schedule'] ) ) {
		return '';
	}

	$options = isset( $config['options'] ) && is_array( $config['options'] ) ? $config['options'] : array();

	return sprintf(
		'var supersaas = new SuperSaaS(%1$s,%2$s,%3$s);',
		wp_json_encode( $config['account'], JSON_UNESCAPED_UNICODE ),
		wp_json_encode( $config['schedule'], JSON_UNESCAPED_UNICODE ),
		wp_json_encode( $options, JSON_UNESCAPED_UNICODE )
	);
}
