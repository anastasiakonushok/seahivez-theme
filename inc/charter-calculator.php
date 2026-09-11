<?php
/**
 * Homepage charter card display and calculator configuration.
 *
 * @package seahivez-theme
 */

/**
 * Maximum guest capacity for quantity controls.
 *
 * @return int
 */
function seahivez_get_yacht_max_guests() {
	return 10;
}

/**
 * Refundable security deposit amount.
 *
 * @return int
 */
function seahivez_get_charter_security_deposit() {
	return 300;
}

/**
 * Whether a package should expose the inline charter calculator.
 *
 * @param string $package_key Package key.
 * @return bool
 */
function seahivez_package_supports_charter_calculator( $package_key ) {
	$package_key = sanitize_key( (string) $package_key );

	return '' !== $package_key && 'sunset' !== $package_key;
}

/**
 * Shared optional extras for the calculator.
 *
 * @return array<int, array<string, mixed>>
 */
function seahivez_get_charter_calculator_extras() {
	return array(
		array(
			'id'    => 'seabob',
			'label' => __( 'SeaBob', 'seahivez-theme' ),
			'price' => 400,
		),
		array(
			'id'    => 'jet-ski',
			'label' => __( 'Jet Ski', 'seahivez-theme' ),
			'price' => 500,
		),
		array(
			'id'    => 'efoil-air',
			'label' => __( 'Efoil Air', 'seahivez-theme' ),
			'price' => 500,
		),
		array(
			'id'    => 'donat',
			'label' => __( 'Donat', 'seahivez-theme' ),
			'price' => 50,
		),
		array(
			'id'          => 'fishing-package',
			'label'       => __( 'Fishing Package', 'seahivez-theme' ),
			'price'       => 300,
			'description' => __( 'Equipment for up to 4 people, bait and licence.', 'seahivez-theme' ),
		),
	);
}

/**
 * Shared food and drinks catalog for homepage display and calculator.
 *
 * @return array<int, array<string, mixed>>
 */
function seahivez_get_charter_food_drinks_catalog() {
	return array(
		array(
			'id'               => 'food',
			'icon'             => 'food',
			'title'            => __( 'Food', 'seahivez-theme' ),
			'calculator_label' => __( 'Food', 'seahivez-theme' ),
			'price'            => 25,
			'unit'             => __( 'person', 'seahivez-theme' ),
			'description'      => '',
		),
		array(
			'id'               => 'drinks',
			'icon'             => 'drinks',
			'title'            => __( 'Drinks', 'seahivez-theme' ),
			'calculator_label' => __( 'Drinks', 'seahivez-theme' ),
			'price'            => 20,
			'unit'             => __( 'person', 'seahivez-theme' ),
			'description'      => __( 'Wine, beer, cava, cola, Fanta and Sprite — unlimited.', 'seahivez-theme' ),
		),
		array(
			'id'               => 'children',
			'icon'             => 'children-menu',
			'title'            => __( "Children's Menu", 'seahivez-theme' ),
			'calculator_label' => __( 'Children', 'seahivez-theme' ),
			'price'            => 10,
			'unit'             => __( 'child', 'seahivez-theme' ),
			'description'      => '',
		),
	);
}

/**
 * Format a food / drinks unit price for display.
 *
 * @param int    $price Price amount.
 * @param string $unit  Unit label, e.g. person or child.
 * @return string
 */
function seahivez_format_food_drinks_price_label( $price, $unit ) {
	return sprintf(
		'€%1$s / %2$s',
		number_format_i18n( max( 0, (int) $price ) ),
		$unit
	);
}

/**
 * Map ACF food & drinks rows onto the shared catalog defaults.
 *
 * @param array<string, mixed> $acf_section Optional ACF section values.
 * @return array<string, mixed>
 */
function seahivez_map_home_food_drinks_section( $acf_section = array() ) {
	$defaults = seahivez_get_home_food_drinks_data();
	$acf_section = is_array( $acf_section ) ? $acf_section : array();

	foreach ( array( 'eyebrow', 'status', 'note' ) as $key ) {
		if ( ! empty( $acf_section[ $key ] ) ) {
			$defaults[ $key ] = (string) $acf_section[ $key ];
		}
	}

	if ( empty( $acf_section['items'] ) || ! is_array( $acf_section['items'] ) ) {
		return $defaults;
	}

	$catalog_by_id = array();

	foreach ( seahivez_get_charter_food_drinks_catalog() as $catalog_item ) {
		$catalog_by_id[ (string) $catalog_item['id'] ] = $catalog_item;
	}

	$items = array();

	foreach ( $acf_section['items'] as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}

		$row_id    = sanitize_key( (string) ( $row['id'] ?? '' ) );
		$fallback  = $catalog_by_id[ $row_id ] ?? null;
		$row_title = (string) ( $row['title'] ?? '' );

		if ( '' === $row_title && empty( $fallback ) ) {
			continue;
		}

		$icon = $row['icon'] ?? ( $fallback['icon'] ?? '' );

		$items[] = array(
			'id'               => $row_id ? $row_id : (string) ( $fallback['id'] ?? sanitize_key( $row_title ) ),
			'icon'             => is_array( $icon ) ? $icon : (string) $icon,
			'title'            => $row_title ? $row_title : (string) ( $fallback['title'] ?? '' ),
			'calculator_label' => (string) ( $row['calculator_label'] ?? ( $fallback['calculator_label'] ?? $row_title ) ),
			'price'            => isset( $row['price'] ) && '' !== (string) $row['price']
				? (int) $row['price']
				: (int) ( $fallback['price'] ?? 0 ),
			'unit'             => ! empty( $row['unit'] )
				? (string) $row['unit']
				: (string) ( $fallback['unit'] ?? '' ),
			'description'      => isset( $row['description'] )
				? (string) $row['description']
				: (string) ( $fallback['description'] ?? '' ),
		);
	}

	if ( ! empty( $items ) ) {
		$defaults['items'] = $items;
	}

	return $defaults;
}

/**
 * Homepage food and drinks section payload.
 *
 * @return array<string, mixed>
 */
function seahivez_get_home_food_drinks_data() {
	return array(
		'eyebrow' => __( 'Food & Drinks', 'seahivez-theme' ),
		'status'  => __( 'Available on request', 'seahivez-theme' ),
		'note'    => __( 'Please arrange in advance.', 'seahivez-theme' ),
		'items'   => seahivez_get_charter_food_drinks_catalog(),
	);
}

/**
 * Per-person / per-child quantity extras.
 *
 * @return array<int, array<string, mixed>>
 */
function seahivez_get_charter_calculator_quantity_extras() {
	$items = array();

	foreach ( seahivez_get_charter_food_drinks_catalog() as $item ) {
		$items[] = array(
			'id'    => (string) $item['id'],
			'label' => (string) ( $item['calculator_label'] ?? $item['title'] ),
			'price' => (int) $item['price'],
			'unit'  => (string) $item['unit'],
		);
	}

	return $items;
}

/**
 * Standard equipment included in half / full day summaries.
 *
 * @return string
 */
function seahivez_get_charter_equipment_summary_line() {
	return __( 'Equipment · taxes · insurance · final cleaning', 'seahivez-theme' );
}

/**
 * Standard "included in charter" summary for calculator sidebars.
 *
 * @return string
 */
function seahivez_get_charter_included_in_charter_summary() {
	$equipment = seahivez_get_charter_included_equipment_labels();

	return implode(
		' · ',
		array(
			__( 'Captain', 'seahivez-theme' ),
			__( 'Fuel', 'seahivez-theme' ),
			$equipment['snorkel'],
			$equipment['paddle_boards'],
			__( 'Flippers', 'seahivez-theme' ),
			__( 'Towels', 'seahivez-theme' ),
			__( 'Taxes', 'seahivez-theme' ),
			__( 'Insurance', 'seahivez-theme' ),
			__( 'Final cleaning', 'seahivez-theme' ),
		)
	);
}

/**
 * Split a charter route path into individual stops.
 *
 * @param string $path Route path string.
 * @return array<int, string>
 */
function seahivez_parse_charter_route_stops( $path ) {
	$parts = preg_split( '/\s*→\s*/u', (string) $path );

	if ( empty( $parts ) ) {
		return array();
	}

	return array_values(
		array_filter(
			array_map(
				static function ( $part ) {
					return trim( (string) $part );
				},
				$parts
			)
		)
	);
}

/**
 * Fuel / surcharge lines for route detail display.
 *
 * @param array<string, mixed> $route Route data.
 * @return array<int, array<string, string>>
 */
function seahivez_get_route_fuel_lines( $route ) {
	$fuel      = (int) ( $route['fuel'] ?? 0 );
	$surcharge = (int) ( $route['surcharge'] ?? 0 );
	$lines     = array();

	if ( $fuel > 0 && 0 === $surcharge ) {
		$lines[] = array(
			'label' => __( 'Fuel included', 'seahivez-theme' ),
			'value' => '€' . number_format_i18n( $fuel ),
		);
	} elseif ( $fuel > 0 ) {
		$lines[] = array(
			'label' => __( 'Fuel:', 'seahivez-theme' ),
			'value' => '€' . number_format_i18n( $fuel ),
		);
		$lines[] = array(
			'label' => __( 'Route surcharge:', 'seahivez-theme' ),
			'value' => '+€' . number_format_i18n( $surcharge ),
		);
	}

	return $lines;
}

/**
 * Normalize route payload with parsed path stops.
 *
 * @param array<string, mixed> $route Route data.
 * @return array<string, mixed>
 */
function seahivez_normalize_charter_route( $route ) {
	$route['path_stops'] = seahivez_parse_charter_route_stops( (string) ( $route['path'] ?? '' ) );
	$route['fuel_lines'] = seahivez_get_route_fuel_lines( $route );

	return $route;
}

/**
 * Route definitions keyed by package.
 *
 * @return array<string, array<int, array<string, mixed>>>
 */
function seahivez_get_charter_route_definitions() {
	return array(
		'sunset'   => array(
			array(
				'id'         => 'sunset',
				'number'     => '',
				'name'       => __( 'Sunset at Cala de Reina', 'seahivez-theme' ),
				'path'       => __( "S'Arenal → Cala de Reina → S'Arenal", 'seahivez-theme' ),
				'note'       => __( 'Sunset experience at Cala de Reina.', 'seahivez-theme' ),
				'fuel'       => 0,
				'surcharge'  => 0,
				'fuel_note'  => __( 'Included in base price', 'seahivez-theme' ),
			),
		),
		'half-day' => array(
			array(
				'id'         => 'half-day-east',
				'number'     => '01',
				'name'       => __( 'East Coast', 'seahivez-theme' ),
				'path'       => __( "S'Arenal → Cala de Reina → Cala Vela → Es Rocal (fish reserve) → S'Arenal", 'seahivez-theme' ),
				'note'       => '',
				'fuel'       => 400,
				'surcharge'  => 0,
				'fuel_note'  => __( 'Included in base price', 'seahivez-theme' ),
			),
		),
		'full-day' => array(
			array(
				'id'         => 'full-day-east',
				'number'     => '01',
				'name'       => __( 'East Coast', 'seahivez-theme' ),
				'path'       => __( "S'Arenal → Cala de Reina → Cala Vela → Es Rocal (fish reserve) → S'Arenal", 'seahivez-theme' ),
				'note'       => '',
				'fuel'       => 400,
				'surcharge'  => 0,
				'fuel_note'  => __( 'Included in base price', 'seahivez-theme' ),
			),
			array(
				'id'         => 'full-day-palma',
				'number'     => '02',
				'name'       => __( 'Palma Coast', 'seahivez-theme' ),
				'path'       => __( "S'Arenal → Catedral de Palma → Casa del Rey → Restaurante Siso (Palmanova) → Cala Portals Vells → S'Arenal", 'seahivez-theme' ),
				'note'       => '',
				'fuel'       => 600,
				'surcharge'  => 200,
				'fuel_note'  => __( '+€200', 'seahivez-theme' ),
			),
			array(
				'id'         => 'full-day-both',
				'number'     => '03',
				'name'       => __( 'Best of Both', 'seahivez-theme' ),
				'path'       => __( "S'Arenal → Cala de Reina → Es Rocal (fish reserve) → Restaurante Siso (Palmanova) → Cala Portals Vells → S'Arenal", 'seahivez-theme' ),
				'note'       => '',
				'fuel'       => 800,
				'surcharge'  => 400,
				'fuel_note'  => __( '+€400', 'seahivez-theme' ),
			),
		),
	);
}

/**
 * Build homepage card display payload.
 *
 * @param string $package_key Package key.
 * @param int    $base_price  Base charter price.
 * @return array<string, mixed>|null
 */
function seahivez_get_home_charter_card_display( $package_key, $base_price ) {
	$routes_map = seahivez_get_charter_route_definitions();
	$equipment  = seahivez_get_charter_included_equipment_labels();

	if ( empty( $routes_map[ $package_key ] ) ) {
		return null;
	}

	$base_price = max( 0, (int) $base_price );
	$routes     = array_map( 'seahivez_normalize_charter_route', $routes_map[ $package_key ] );

	$display = array(
		'package_key'      => $package_key,
		'routes'           => $routes,
		'route_choice'     => 'full-day' === $package_key,
		'default_route_id' => $routes[0]['id'],
	);

	if ( 'sunset' === $package_key ) {
		$display['included_label']   = __( 'Included in charter', 'seahivez-theme' );
		$display['included_compact'] = seahivez_get_charter_included_in_charter_summary();
		$display['included_breakdown'] = array();
		$display['included_summary']   = '';
		$display['included_items']     = array();
	} elseif ( 'half-day' === $package_key ) {
		$display['included_label'] = sprintf(
			/* translators: %s: base charter price */
			__( 'Included in €%s', 'seahivez-theme' ),
			number_format_i18n( $base_price )
		);
		$display['included_compact'] = __( 'Fuel €400 · Captain €300 · Deckhand €100', 'seahivez-theme' );
		$display['included_summary'] = seahivez_get_charter_equipment_summary_line();
		$display['included_breakdown'] = array();
		$display['included_items']     = array();
	} else {
		$display['included_label'] = sprintf(
			/* translators: %s: base charter price */
			__( 'Included in €%s', 'seahivez-theme' ),
			number_format_i18n( $base_price )
		);
		$display['included_compact'] = __( 'Route 1 fuel €400 · Captain €300 · Deckhand €100', 'seahivez-theme' );
		$display['included_summary'] = seahivez_get_charter_equipment_summary_line();
		$display['included_breakdown'] = array();
		$display['included_items']     = array();
	}

	return $display;
}

/**
 * Calculator configuration for a package (JSON-ready).
 *
 * @param string $package_key Package key.
 * @param string $title       Package title.
 * @param int    $base_price  Base charter price.
 * @return array<string, mixed>|null
 */
function seahivez_get_charter_calculator_config( $package_key, $title, $base_price ) {
	$routes_map = seahivez_get_charter_route_definitions();
	$card       = seahivez_get_home_charter_card_display( $package_key, $base_price );

	if ( empty( $routes_map[ $package_key ] ) || empty( $card ) ) {
		return null;
	}

	$included_fuel = 'full-day' === $package_key || 'half-day' === $package_key ? 400 : 0;

	return array(
		'id'              => $package_key,
		'title'           => $title,
		'basePrice'       => max( 0, (int) $base_price ),
		'includedFuel'    => $included_fuel,
		'deposit'         => seahivez_get_charter_security_deposit(),
		'maxGuests'       => seahivez_get_yacht_max_guests(),
		'routes'          => array_map( 'seahivez_normalize_charter_route', $routes_map[ $package_key ] ),
		'routeChoice'     => 'full-day' === $package_key,
		'defaultRouteId'  => $routes_map[ $package_key ][0]['id'],
		'includedLabel'   => $card['included_label'],
		'includedItems'   => $card['included_items'],
		'includedBreakdown' => $card['included_breakdown'],
		'includedSummary' => $card['included_summary'],
		'extras'          => seahivez_get_charter_calculator_extras(),
		'quantityExtras'  => seahivez_get_charter_calculator_quantity_extras(),
		'labels'          => array(
			'baseCharter'       => __( 'Base charter', 'seahivez-theme' ),
			'routeSurcharge'    => __( 'Fuel', 'seahivez-theme' ),
			'optionalExtras'    => __( 'Optional extras', 'seahivez-theme' ),
			'foodDrinks'        => __( 'Food & drinks', 'seahivez-theme' ),
			'charterTotal'      => __( 'Charter total', 'seahivez-theme' ),
			'deposit'           => __( 'Refundable security deposit', 'seahivez-theme' ),
			'amountToPrepare'   => __( 'Amount to prepare', 'seahivez-theme' ),
			'depositNote'       => sprintf(
				/* translators: %s: deposit amount */
				__( '%1$s deposit is refundable after the charter.', 'seahivez-theme' ),
				'€' . number_format_i18n( seahivez_get_charter_security_deposit() )
			),
			'depositExplainer'  => __( 'Paid at the port and returned after the charter according to booking conditions. Not a charter expense.', 'seahivez-theme' ),
			'includedInBase'    => __( 'Included in base price', 'seahivez-theme' ),
			'chooseRoute'       => __( 'Choose your route', 'seahivez-theme' ),
			'selectedRoute'     => __( 'Selected route', 'seahivez-theme' ),
			'selectedRoutePrefix' => __( 'Selected route:', 'seahivez-theme' ),
			'priceBreakdown'    => __( 'Price breakdown', 'seahivez-theme' ),
			'calculateFinalPrice' => __( 'Calculate final price', 'seahivez-theme' ),
			'closeCalculator'   => __( 'Close calculator', 'seahivez-theme' ),
			'baseCharterFooter' => __( 'Base charter', 'seahivez-theme' ),
			'fuel'              => __( 'Fuel', 'seahivez-theme' ),
			'fuelIncluded'      => __( 'Fuel included', 'seahivez-theme' ),
		),
	);
}

/**
 * Attach homepage charter card + calculator keys to experience cards.
 *
 * @param array<int, array<string, mixed>> $cards Experience cards.
 * @return array<int, array<string, mixed>>
 */
function seahivez_attach_home_charter_calculator_data( $cards ) {
	foreach ( $cards as $index => $card ) {
		$key = seahivez_get_experience_package_key( $card );

		if ( '' === $key ) {
			continue;
		}

		$base_price = (int) preg_replace( '/[^\d]/', '', (string) ( $card['price'] ?? '0' ) );
		$display    = seahivez_get_home_charter_card_display( $key, $base_price );

		if ( $display ) {
			$cards[ $index ]['package_key']   = $key;
			$cards[ $index ]['charter_card']  = $display;
		}
	}

	return $cards;
}

/**
 * Build calculator configs for all homepage cards.
 *
 * @param array<int, array<string, mixed>> $cards Experience cards.
 * @return array<string, array<string, mixed>>
 */
function seahivez_get_home_charter_calculator_configs( $cards ) {
	$configs = array();

	foreach ( $cards as $card ) {
		$key = (string) ( $card['package_key'] ?? '' );

		if ( '' === $key ) {
			$key = seahivez_get_experience_package_key( $card );
		}

		if ( '' === $key || isset( $configs[ $key ] ) || ! seahivez_package_supports_charter_calculator( $key ) ) {
			continue;
		}

		$base_price = (int) preg_replace( '/[^\d]/', '', (string) ( $card['price'] ?? '0' ) );
		$config     = seahivez_get_charter_calculator_config(
			$key,
			(string) ( $card['title'] ?? '' ),
			$base_price
		);

		if ( $config ) {
			$configs[ $key ] = $config;
		}
	}

	return $configs;
}
