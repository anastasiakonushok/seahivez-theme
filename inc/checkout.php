<?php
/**
 * Charter checkout session, recalculation, and booking requests.
 *
 * Payment gateway integration (Stripe, pay on arrival) can be added in
 * seahivez_process_checkout_payment() without rewriting this flow.
 *
 * @package seahivez-theme
 */

/**
 * Checkout session cookie name.
 */
define( 'SEAHIVEZ_CHECKOUT_COOKIE', 'seahivez_checkout_id' );

/**
 * Booking notification email address.
 *
 * @return string
 */
function seahivez_get_booking_notification_email() {
	$email = 'libertytopes@gmail.com';

	return (string) apply_filters( 'seahivez_booking_notification_email', $email );
}

/**
 * Sanitize an international phone number.
 *
 * @param string $phone Raw phone value.
 * @return string
 */
function seahivez_sanitize_international_phone( $phone ) {
	$phone = sanitize_text_field( (string) $phone );
	$phone = preg_replace( '/[^\d+]/', '', $phone );

	return trim( (string) $phone );
}

/**
 * Half day charter time slot options.
 *
 * @return array<string, string>
 */
function seahivez_get_half_day_time_slots() {
	return array(
		'morning'   => '10:00–14:00',
		'afternoon' => '15:00–19:00',
	);
}

/**
 * Whether checkout should show a fixed time slot picker.
 *
 * @param string $package_key Package key.
 * @return bool
 */
function seahivez_checkout_requires_time_slot( $package_key ) {
	return 'half-day' === sanitize_key( (string) $package_key );
}

/**
 * Get package key from a checkout booking payload.
 *
 * @param array<string, mixed> $booking Booking payload.
 * @return string
 */
function seahivez_get_checkout_booking_package_key( $booking ) {
	if ( empty( $booking ) || ! is_array( $booking ) ) {
		return '';
	}

	if ( ! empty( $booking['selection']['package'] ) ) {
		return sanitize_key( (string) $booking['selection']['package'] );
	}

	if ( ! empty( $booking['package']['key'] ) ) {
		return sanitize_key( (string) $booking['package']['key'] );
	}

	return '';
}

/**
 * Sanitize a half day time slot selection.
 *
 * @param string $slot_id Slot id.
 * @return string
 */
function seahivez_sanitize_half_day_time_slot( $slot_id ) {
	$slot_id = sanitize_key( (string) $slot_id );
	$slots   = seahivez_get_half_day_time_slots();

	return isset( $slots[ $slot_id ] ) ? $slot_id : '';
}

/**
 * Checkout page URL.
 *
 * @return string
 */
function seahivez_get_checkout_url() {
	$page = get_page_by_path( 'checkout' );

	if ( $page instanceof WP_Post ) {
		return get_permalink( $page );
	}

	return home_url( '/checkout/' );
}

/**
 * Whether the current request is the checkout page.
 *
 * @return bool
 */
function seahivez_is_checkout_page() {
	return is_page_template( 'page-checkout.php' ) || is_page( 'checkout' );
}

/**
 * Register private booking request post type.
 */
function seahivez_register_booking_request_post_type() {
	register_post_type(
		'seahivez_booking',
		array(
			'labels'              => array(
				'name'          => __( 'Booking Requests', 'seahivez-theme' ),
				'singular_name' => __( 'Booking Request', 'seahivez-theme' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 26,
			'menu_icon'           => 'dashicons-calendar-alt',
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'supports'            => array( 'title' ),
			'has_archive'         => false,
			'exclude_from_search' => true,
		)
	);
}
add_action( 'init', 'seahivez_register_booking_request_post_type' );

/**
 * Find a package card by package key.
 *
 * @param string $package_key Package key.
 * @return array<string, string>
 */
function seahivez_get_package_card_by_key( $package_key ) {
	$package_key = sanitize_key( (string) $package_key );

	foreach ( seahivez_get_packages_for_display() as $card ) {
		if ( seahivez_get_experience_package_key( $card ) === $package_key ) {
			return $card;
		}
	}

	return array();
}

/**
 * Sanitize checkout selection payload from the client.
 *
 * @param array<string, mixed> $raw Raw POST/JSON values.
 * @return array<string, mixed>|null
 */
function seahivez_sanitize_checkout_selection( $raw ) {
	if ( empty( $raw ) || ! is_array( $raw ) ) {
		return null;
	}

	$package_key = sanitize_key( (string) ( $raw['package'] ?? '' ) );

	if ( '' === $package_key ) {
		return null;
	}

	$card = seahivez_get_package_card_by_key( $package_key );

	if ( empty( $card ) ) {
		return null;
	}

	$base_price = (int) preg_replace( '/[^\d]/', '', (string) ( $card['price'] ?? '0' ) );
	$config     = seahivez_get_charter_calculator_config( $package_key, (string) ( $card['title'] ?? '' ), $base_price );

	if ( empty( $config ) ) {
		return null;
	}

	$route_id = sanitize_key( (string) ( $raw['route_id'] ?? '' ) );
	$routes   = is_array( $config['routes'] ) ? $config['routes'] : array();
	$valid_route_ids = array_map(
		static function ( $route ) {
			return sanitize_key( (string) ( $route['id'] ?? '' ) );
		},
		$routes
	);

	if ( '' === $route_id || ! in_array( $route_id, $valid_route_ids, true ) ) {
		$route_id = (string) ( $config['defaultRouteId'] ?? ( $routes[0]['id'] ?? '' ) );
	}

	$selected_extras = array();
	$extras_input    = $raw['extras'] ?? array();

	if ( is_string( $extras_input ) ) {
		$decoded      = json_decode( $extras_input, true );
		$extras_input = is_array( $decoded ) ? $decoded : array();
	}

	if ( is_array( $extras_input ) ) {
		$allowed_extras = array();

		foreach ( seahivez_get_charter_calculator_extras() as $extra ) {
			$allowed_extras[] = sanitize_key( (string) ( $extra['id'] ?? '' ) );
		}

		foreach ( $extras_input as $extra_id ) {
			$extra_id = sanitize_key( (string) $extra_id );

			if ( in_array( $extra_id, $allowed_extras, true ) ) {
				$selected_extras[] = $extra_id;
			}
		}
	}

	$quantities      = array();
	$quantities_input = $raw['quantities'] ?? array();

	if ( is_string( $quantities_input ) ) {
		$decoded          = json_decode( $quantities_input, true );
		$quantities_input = is_array( $decoded ) ? $decoded : array();
	}

	if ( is_array( $quantities_input ) ) {
		$allowed_qty = array();

		foreach ( seahivez_get_charter_calculator_quantity_extras() as $extra ) {
			$allowed_qty[] = sanitize_key( (string) ( $extra['id'] ?? '' ) );
		}

		$max_guests = seahivez_get_yacht_max_guests();

		foreach ( $allowed_qty as $qty_id ) {
			$value = isset( $quantities_input[ $qty_id ] ) ? (int) $quantities_input[ $qty_id ] : 0;
			$quantities[ $qty_id ] = max( 0, min( $max_guests, $value ) );
		}
	}

	return array(
		'package'    => $package_key,
		'route_id'   => $route_id,
		'extras'     => array_values( array_unique( $selected_extras ) ),
		'quantities' => $quantities,
	);
}

/**
 * Build checkout totals and display lines from a sanitized selection.
 *
 * @param array<string, mixed> $selection Sanitized selection.
 * @return array<string, mixed>|null
 */
function seahivez_calculate_charter_booking( $selection ) {
	if ( empty( $selection ) || ! is_array( $selection ) ) {
		return null;
	}

	$package_key = (string) ( $selection['package'] ?? '' );
	$card        = seahivez_get_package_card_by_key( $package_key );

	if ( empty( $card ) ) {
		return null;
	}

	$base_price = (int) preg_replace( '/[^\d]/', '', (string) ( $card['price'] ?? '0' ) );
	$config     = seahivez_get_charter_calculator_config( $package_key, (string) ( $card['title'] ?? '' ), $base_price );

	if ( empty( $config ) ) {
		return null;
	}

	$route_id         = (string) ( $selection['route_id'] ?? '' );
	$routes           = is_array( $config['routes'] ) ? $config['routes'] : array();
	$route            = null;
	$selected_extras  = is_array( $selection['extras'] ?? null ) ? $selection['extras'] : array();
	$quantities       = is_array( $selection['quantities'] ?? null ) ? $selection['quantities'] : array();
	$extras_map       = array();
	$quantity_map     = array();

	foreach ( seahivez_get_charter_calculator_extras() as $extra ) {
		$extras_map[ (string) $extra['id'] ] = $extra;
	}

	foreach ( seahivez_get_charter_calculator_quantity_extras() as $extra ) {
		$quantity_map[ (string) $extra['id'] ] = $extra;
	}

	foreach ( $routes as $route_item ) {
		if ( (string) ( $route_item['id'] ?? '' ) === $route_id ) {
			$route = $route_item;
			break;
		}
	}

	if ( ! $route && ! empty( $routes[0] ) ) {
		$route    = $routes[0];
		$route_id = (string) ( $route['id'] ?? '' );
	}

	$surcharge = (int) ( $route['surcharge'] ?? 0 );
	$base      = (int) ( $config['basePrice'] ?? 0 );
	$deposit   = (int) ( $config['deposit'] ?? seahivez_get_charter_security_deposit() );

	$price_lines   = array();
	$extras_lines  = array();
	$food_lines    = array();
	$extras_total  = 0;
	$food_total    = 0;

	$price_lines[] = array(
		'key'    => 'base',
		'label'  => __( 'Base charter', 'seahivez-theme' ),
		'amount' => $base,
	);

	$price_lines[] = array(
		'key'    => 'route',
		'label'  => __( 'Route surcharge', 'seahivez-theme' ),
		'amount' => $surcharge,
	);

	foreach ( $selected_extras as $extra_id ) {
		if ( empty( $extras_map[ $extra_id ] ) ) {
			continue;
		}

		$extra   = $extras_map[ $extra_id ];
		$amount  = (int) ( $extra['price'] ?? 0 );
		$extras_total += $amount;

		$extras_lines[] = array(
			'id'     => $extra_id,
			'label'  => (string) ( $extra['label'] ?? '' ),
			'amount' => $amount,
		);
	}

	if ( $extras_total > 0 ) {
		$price_lines[] = array(
			'key'    => 'extras',
			'label'  => __( 'Extras', 'seahivez-theme' ),
			'amount' => $extras_total,
		);
	}

	foreach ( $quantities as $qty_id => $qty ) {
		$qty = (int) $qty;

		if ( $qty <= 0 || empty( $quantity_map[ $qty_id ] ) ) {
			continue;
		}

		$extra   = $quantity_map[ $qty_id ];
		$unit    = (string) ( $extra['unit'] ?? '' );
		$amount  = $qty * (int) ( $extra['price'] ?? 0 );
		$food_total += $amount;

		$food_lines[] = array(
			'id'     => $qty_id,
			'label'  => (string) ( $extra['label'] ?? '' ),
			'unit'   => $unit,
			'qty'    => $qty,
			'amount' => $amount,
		);
	}

	if ( $food_total > 0 ) {
		$price_lines[] = array(
			'key'    => 'food',
			'label'  => __( 'Food & drinks', 'seahivez-theme' ),
			'amount' => $food_total,
		);
	}

	$charter_total = $base + $surcharge + $extras_total + $food_total;

	$route_number = trim( (string) ( $route['number'] ?? '' ) );
	$route_name   = trim( (string) ( $route['name'] ?? '' ) );
	$route_label  = trim( $route_number . ' ' . $route_name );

	return array(
		'selection'     => array(
			'package'    => $package_key,
			'route_id'   => $route_id,
			'extras'     => $selected_extras,
			'quantities' => $quantities,
		),
		'package'       => array(
			'key'       => $package_key,
			'title'     => (string) ( $card['title'] ?? '' ),
			'duration'  => (string) ( $card['duration'] ?? '' ),
			'time_slot' => (string) ( $card['time_slot'] ?? '' ),
			'price'     => $base,
		),
		'route'         => array(
			'id'     => $route_id,
			'number' => $route_number,
			'name'   => $route_name,
			'label'  => $route_label,
			'path'   => (string) ( $route['path'] ?? '' ),
		),
		'extras_lines'  => $extras_lines,
		'food_lines'    => $food_lines,
		'price_lines'   => $price_lines,
		'charter_total' => $charter_total,
		'deposit'       => $deposit,
	);
}

/**
 * Create a checkout session token and store booking data.
 *
 * @param array<string, mixed> $booking Calculated booking payload.
 * @return string
 */
function seahivez_store_checkout_session( $booking ) {
	$token = wp_generate_password( 32, false, false );

	set_transient( 'seahivez_checkout_' . $token, $booking, 30 * MINUTE_IN_SECONDS );

	if ( ! headers_sent() ) {
		setcookie(
			SEAHIVEZ_CHECKOUT_COOKIE,
			$token,
			time() + ( 30 * MINUTE_IN_SECONDS ),
			COOKIEPATH ? COOKIEPATH : '/',
			COOKIE_DOMAIN,
			is_ssl(),
			true
		);
	}

	$_COOKIE[ SEAHIVEZ_CHECKOUT_COOKIE ] = $token;

	return $token;
}

/**
 * Read the active checkout session.
 *
 * @return array<string, mixed>|null
 */
function seahivez_get_checkout_session() {
	$token = isset( $_COOKIE[ SEAHIVEZ_CHECKOUT_COOKIE ] ) ? sanitize_key( wp_unslash( $_COOKIE[ SEAHIVEZ_CHECKOUT_COOKIE ] ) ) : '';

	if ( '' === $token ) {
		return null;
	}

	$booking = get_transient( 'seahivez_checkout_' . $token );

	return is_array( $booking ) ? $booking : null;
}

/**
 * Store a confirmation payload after successful submission.
 *
 * @param array<string, mixed> $payload Confirmation payload.
 * @return string
 */
function seahivez_store_checkout_confirmation( $payload ) {
	$token = wp_generate_password( 32, false, false );

	set_transient( 'seahivez_checkout_confirm_' . $token, $payload, 30 * MINUTE_IN_SECONDS );

	return $token;
}

/**
 * Read a checkout confirmation payload.
 *
 * @param string $token Confirmation token.
 * @return array<string, mixed>|null
 */
function seahivez_get_checkout_confirmation( $token ) {
	$token = sanitize_key( (string) $token );

	if ( '' === $token ) {
		return null;
	}

	$data = get_transient( 'seahivez_checkout_confirm_' . $token );

	return is_array( $data ) ? $data : null;
}

/**
 * Clear checkout session cookie and transient.
 */
function seahivez_clear_checkout_session() {
	$token = isset( $_COOKIE[ SEAHIVEZ_CHECKOUT_COOKIE ] ) ? sanitize_key( wp_unslash( $_COOKIE[ SEAHIVEZ_CHECKOUT_COOKIE ] ) ) : '';

	if ( $token ) {
		delete_transient( 'seahivez_checkout_' . $token );
	}

	if ( ! headers_sent() ) {
		setcookie( SEAHIVEZ_CHECKOUT_COOKIE, '', time() - HOUR_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), true );
	}
}

/**
 * Save a booking request and notify the site admin.
 *
 * @param array<string, mixed> $customer Customer form data.
 * @param array<string, mixed> $booking  Calculated booking payload.
 * @return int|false Post ID on success.
 */
function seahivez_save_booking_request( $customer, $booking ) {
	$title = sprintf(
		/* translators: 1: customer name, 2: package title */
		__( 'Charter request — %1$s — %2$s', 'seahivez-theme' ),
		(string) ( $customer['name'] ?? '' ),
		(string) ( $booking['package']['title'] ?? '' )
	);

	$post_id = wp_insert_post(
		array(
			'post_type'   => 'seahivez_booking',
			'post_status' => 'private',
			'post_title'  => $title,
		),
		true
	);

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return false;
	}

	update_post_meta( $post_id, '_seahivez_customer', $customer );
	update_post_meta( $post_id, '_seahivez_booking', $booking );

	seahivez_send_booking_request_email( $customer, $booking, (int) $post_id );

	return (int) $post_id;
}

/**
 * Build plain-text booking email body.
 *
 * @param array<string, mixed> $customer Customer data.
 * @param array<string, mixed> $booking  Booking data.
 * @return string
 */
function seahivez_format_booking_email_body( $customer, $booking ) {
	$package      = ! empty( $booking['package'] ) && is_array( $booking['package'] ) ? $booking['package'] : array();
	$route        = ! empty( $booking['route'] ) && is_array( $booking['route'] ) ? $booking['route'] : array();
	$extras_lines = ! empty( $booking['extras_lines'] ) && is_array( $booking['extras_lines'] ) ? $booking['extras_lines'] : array();
	$food_lines   = ! empty( $booking['food_lines'] ) && is_array( $booking['food_lines'] ) ? $booking['food_lines'] : array();
	$price_lines  = ! empty( $booking['price_lines'] ) && is_array( $booking['price_lines'] ) ? $booking['price_lines'] : array();

	$lines = array(
		__( 'New charter booking request', 'seahivez-theme' ),
		'',
		'--- ' . __( 'Customer', 'seahivez-theme' ) . ' ---',
		__( 'Name', 'seahivez-theme' ) . ': ' . (string) ( $customer['name'] ?? '' ),
		__( 'Email', 'seahivez-theme' ) . ': ' . (string) ( $customer['email'] ?? '' ),
		__( 'Phone / WhatsApp', 'seahivez-theme' ) . ': ' . (string) ( $customer['phone'] ?? '' ),
		__( 'Preferred date', 'seahivez-theme' ) . ': ' . (string) ( $customer['date'] ?? '' ),
		__( 'Guests', 'seahivez-theme' ) . ': ' . (string) ( $customer['guests'] ?? '' ),
	);

	if ( ! empty( $customer['preferred_time'] ) ) {
		$time_label = ! empty( $customer['time_slot'] )
			? __( 'Selected time slot', 'seahivez-theme' )
			: __( 'Preferred time', 'seahivez-theme' );
		$lines[]    = $time_label . ': ' . (string) $customer['preferred_time'];
	}

	if ( ! empty( $customer['message'] ) ) {
		$lines[] = __( 'Message', 'seahivez-theme' ) . ': ' . (string) $customer['message'];
	}

	$lines[] = '';
	$lines[] = '--- ' . __( 'Booking', 'seahivez-theme' ) . ' ---';
	$lines[] = __( 'Package', 'seahivez-theme' ) . ': ' . (string) ( $package['title'] ?? '' );

	if ( ! empty( $package['duration'] ) ) {
		$lines[] = __( 'Duration', 'seahivez-theme' ) . ': ' . (string) $package['duration'];
	}

	if ( ! empty( $package['time_slot'] ) ) {
		$lines[] = __( 'Time slot', 'seahivez-theme' ) . ': ' . (string) $package['time_slot'];
	}

	$lines[] = '';
	$lines[] = __( 'Route', 'seahivez-theme' ) . ': ' . (string) ( $route['label'] ?? '' );

	if ( ! empty( $route['path'] ) ) {
		$lines[] = (string) $route['path'];
	}

	$lines[] = '';
	$lines[] = '--- ' . __( 'Extras', 'seahivez-theme' ) . ' ---';

	if ( ! empty( $extras_lines ) ) {
		foreach ( $extras_lines as $line ) {
			$lines[] = sprintf(
				'%s — %s',
				(string) ( $line['label'] ?? '' ),
				seahivez_format_checkout_amount( (int) ( $line['amount'] ?? 0 ) )
			);
		}
	} else {
		$lines[] = __( 'None selected', 'seahivez-theme' );
	}

	$lines[] = '';
	$lines[] = '--- ' . __( 'Food & drinks', 'seahivez-theme' ) . ' ---';

	if ( ! empty( $food_lines ) ) {
		foreach ( $food_lines as $line ) {
			$lines[] = sprintf(
				/* translators: 1: label, 2: quantity, 3: unit, 4: amount */
				__( '%1$s · %2$d %3$s — %4$s', 'seahivez-theme' ),
				(string) ( $line['label'] ?? '' ),
				(int) ( $line['qty'] ?? 0 ),
				(string) ( $line['unit'] ?? '' ),
				seahivez_format_checkout_amount( (int) ( $line['amount'] ?? 0 ) )
			);
		}
	} else {
		$lines[] = __( 'None selected', 'seahivez-theme' );
	}

	$lines[] = '';
	$lines[] = '--- ' . __( 'Price', 'seahivez-theme' ) . ' ---';

	foreach ( $price_lines as $line ) {
		$lines[] = sprintf(
			'%s — %s',
			(string) ( $line['label'] ?? '' ),
			seahivez_format_checkout_amount( (int) ( $line['amount'] ?? 0 ) )
		);
	}

	$lines[] = '';
	$lines[] = __( 'TOTAL', 'seahivez-theme' ) . ': ' . seahivez_format_checkout_amount( (int) ( $booking['charter_total'] ?? 0 ) );
	$lines[] = __( 'Refundable security deposit (paid separately at the port)', 'seahivez-theme' ) . ': ' . seahivez_format_checkout_amount( (int) ( $booking['deposit'] ?? seahivez_get_charter_security_deposit() ) );

	return implode( "\n", $lines );
}

/**
 * Send booking request notification email.
 *
 * @param array<string, mixed> $customer Customer data.
 * @param array<string, mixed> $booking  Booking data.
 * @param int                  $post_id  Saved request ID.
 */
function seahivez_send_booking_request_email( $customer, $booking, $post_id ) {
	$admin_email = seahivez_get_booking_notification_email();

	if ( ! is_email( $admin_email ) ) {
		$admin_email = get_option( 'admin_email' );
	}

	$subject = sprintf(
		/* translators: 1: package title, 2: customer name */
		__( 'Charter booking request — %1$s — %2$s', 'seahivez-theme' ),
		(string) ( $booking['package']['title'] ?? get_bloginfo( 'name' ) ),
		(string) ( $customer['name'] ?? '' )
	);

	$body = seahivez_format_booking_email_body( $customer, $booking );

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );

	if ( ! empty( $customer['email'] ) && is_email( $customer['email'] ) ) {
		$headers[] = 'Reply-To: ' . sanitize_email( (string) $customer['email'] );
	}

	return seahivez_send_theme_mail( $admin_email, $subject, $body, $headers, (int) $post_id );
}

/**
 * Future payment hook placeholder.
 *
 * @param array<string, mixed> $customer Customer data.
 * @param array<string, mixed> $booking  Booking data.
 * @param string               $method   Payment method slug.
 * @return void
 */
function seahivez_process_checkout_payment( $customer, $booking, $method = '' ) {
	do_action( 'seahivez_process_checkout_payment', $customer, $booking, $method );
}

/**
 * Whether the current request targets the checkout URL path.
 *
 * @return bool
 */
function seahivez_is_checkout_request_path() {
	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
	$request_path = trim( (string) wp_parse_url( $request_uri, PHP_URL_PATH ), '/' );
	$checkout_path = trim( (string) wp_parse_url( seahivez_get_checkout_url(), PHP_URL_PATH ), '/' );

	return '' !== $request_path && $request_path === $checkout_path;
}

/**
 * Handle checkout prepare/submit actions.
 */
function seahivez_handle_checkout_actions() {
	if ( 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
		return;
	}

	$action = isset( $_POST['seahivez_checkout_action'] ) ? sanitize_key( wp_unslash( $_POST['seahivez_checkout_action'] ) ) : '';

	if ( 'prepare' !== $action && ( ! seahivez_is_checkout_page() && ! seahivez_is_checkout_request_path() ) ) {
		return;
	}

	if ( 'submit' === $action && ! seahivez_is_checkout_page() && ! seahivez_is_checkout_request_path() ) {
		return;
	}

	if ( 'prepare' === $action ) {
		if ( ! isset( $_POST['seahivez_checkout_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['seahivez_checkout_nonce'] ) ), 'seahivez_checkout_prepare' ) ) {
			return;
		}

		$selection = seahivez_sanitize_checkout_selection( wp_unslash( $_POST ) );
		$booking   = $selection ? seahivez_calculate_charter_booking( $selection ) : null;

		if ( empty( $booking ) ) {
			wp_safe_redirect( add_query_arg( 'checkout_error', 'invalid', seahivez_get_checkout_url() ) );
			exit;
		}

		seahivez_store_checkout_session( $booking );
		wp_safe_redirect( seahivez_get_checkout_url() );
		exit;
	}

	if ( 'submit' === $action ) {
		if ( ! isset( $_POST['seahivez_checkout_submit_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['seahivez_checkout_submit_nonce'] ) ), 'seahivez_checkout_submit' ) ) {
			return;
		}

		$booking = seahivez_get_checkout_session();

		if ( empty( $booking ) ) {
			wp_safe_redirect( add_query_arg( 'checkout_error', 'expired', seahivez_get_checkout_url() ) );
			exit;
		}

		$package_key = seahivez_get_checkout_booking_package_key( $booking );
		$time_slots  = seahivez_get_half_day_time_slots();
		$time_slot   = seahivez_sanitize_half_day_time_slot( wp_unslash( $_POST['customer_time_slot'] ?? '' ) );

		$customer = array(
			'name'           => sanitize_text_field( wp_unslash( $_POST['customer_name'] ?? '' ) ),
			'email'          => sanitize_email( wp_unslash( $_POST['customer_email'] ?? '' ) ),
			'phone'          => seahivez_sanitize_international_phone( wp_unslash( $_POST['customer_phone'] ?? '' ) ),
			'date'           => sanitize_text_field( wp_unslash( $_POST['customer_date'] ?? '' ) ),
			'guests'         => max( 1, (int) ( $_POST['customer_guests'] ?? 1 ) ),
			'message'        => sanitize_textarea_field( wp_unslash( $_POST['customer_message'] ?? '' ) ),
			'preferred_time' => sanitize_text_field( wp_unslash( $_POST['customer_preferred_time'] ?? '' ) ),
			'time_slot'      => $time_slot,
		);

		if ( seahivez_checkout_requires_time_slot( $package_key ) ) {
			$customer['preferred_time'] = $time_slot ? (string) $time_slots[ $time_slot ] : '';
		}

		$errors = array();

		if ( '' === $customer['name'] ) {
			$errors[] = 'name';
		}

		if ( '' === $customer['email'] || ! is_email( $customer['email'] ) ) {
			$errors[] = 'email';
		}

		if ( '' === $customer['phone'] ) {
			$errors[] = 'phone';
		}

		if ( '' === $customer['date'] ) {
			$errors[] = 'date';
		}

		if ( seahivez_checkout_requires_time_slot( $package_key ) && '' === $time_slot ) {
			$errors[] = 'time_slot';
		}

		if ( ! empty( $errors ) ) {
			set_transient( 'seahivez_checkout_form_errors_' . md5( (string) ( $_COOKIE[ SEAHIVEZ_CHECKOUT_COOKIE ] ?? '' ) ), $errors, 5 * MINUTE_IN_SECONDS );
			wp_safe_redirect( seahivez_get_checkout_url() );
			exit;
		}

		$post_id = seahivez_save_booking_request( $customer, $booking );

		if ( ! $post_id ) {
			wp_safe_redirect( add_query_arg( 'checkout_error', 'save', seahivez_get_checkout_url() ) );
			exit;
		}

		seahivez_process_checkout_payment( $customer, $booking, '' );

		$confirm_token = seahivez_store_checkout_confirmation(
			array(
				'customer' => $customer,
				'booking'  => $booking,
				'post_id'  => $post_id,
			)
		);

		seahivez_clear_checkout_session();

		wp_safe_redirect(
			add_query_arg(
				array(
					'confirmed' => $confirm_token,
				),
				seahivez_get_checkout_url()
			)
		);
		exit;
	}
}
add_action( 'template_redirect', 'seahivez_handle_checkout_actions', 5 );

/**
 * Checkout view model for templates.
 *
 * @return array<string, mixed>
 */
function seahivez_get_checkout_view_data() {
	$confirm_token = isset( $_GET['confirmed'] ) ? sanitize_key( wp_unslash( $_GET['confirmed'] ) ) : '';

	if ( $confirm_token ) {
		$confirmation = seahivez_get_checkout_confirmation( $confirm_token );

		if ( $confirmation ) {
			return array(
				'mode'         => 'confirmed',
				'confirmation' => $confirmation,
			);
		}
	}

	$booking = seahivez_get_checkout_session();
	$errors  = array();
	$token   = isset( $_COOKIE[ SEAHIVEZ_CHECKOUT_COOKIE ] ) ? sanitize_key( wp_unslash( $_COOKIE[ SEAHIVEZ_CHECKOUT_COOKIE ] ) ) : '';

	if ( $token ) {
		$cached_errors = get_transient( 'seahivez_checkout_form_errors_' . md5( $token ) );

		if ( is_array( $cached_errors ) ) {
			$errors = $cached_errors;
			delete_transient( 'seahivez_checkout_form_errors_' . md5( $token ) );
		}
	}

	return array(
		'mode'    => $booking ? 'checkout' : 'empty',
		'booking' => $booking,
		'errors'  => $errors,
		'error'   => isset( $_GET['checkout_error'] ) ? sanitize_key( wp_unslash( $_GET['checkout_error'] ) ) : '',
	);
}

/**
 * Format euro amount for checkout templates.
 *
 * @param int $amount Amount.
 * @return string
 */
function seahivez_format_checkout_amount( $amount ) {
	return '€' . number_format_i18n( max( 0, (int) $amount ) );
}
