<?php
/**
 * Dedicated Extras page content (ACF-ready defaults).
 *
 * @package seahivez-theme
 */

/**
 * Included equipment with full page descriptions.
 *
 * @return array<int, array<string, mixed>>
 */
function seahivez_get_extras_included_equipment() {
	return array(
		array(
			'icon'        => 'snorkel',
			'title'       => __( 'Snorkel sets', 'seahivez-theme' ),
			'status'      => __( 'Included', 'seahivez-theme' ),
			'description' => __( '6 snorkel sets available on board for swimming and exploring the clear waters around Mallorca.', 'seahivez-theme' ),
		),
		array(
			'icon'        => 'paddle-board',
			'title'       => __( 'Paddle boards', 'seahivez-theme' ),
			'status'      => __( 'Included', 'seahivez-theme' ),
			'description' => __( '2 paddle boards available for relaxing, exploring the bays and enjoying time on the water.', 'seahivez-theme' ),
		),
		array(
			'icon'        => 'flippers',
			'title'       => __( 'Flippers', 'seahivez-theme' ),
			'status'      => __( 'Included', 'seahivez-theme' ),
			'description' => __( 'Flippers are available on board together with the snorkelling equipment.', 'seahivez-theme' ),
		),
		array(
			'icon'        => 'towel',
			'title'       => __( 'Towels', 'seahivez-theme' ),
			'status'      => __( 'Included', 'seahivez-theme' ),
			'description' => __( 'Fresh towels are provided for guests during the charter.', 'seahivez-theme' ),
		),
	);
}

/**
 * Included service row items.
 *
 * @return array<int, array<string, string>>
 */
function seahivez_get_extras_included_services() {
	$home = seahivez_get_home_extras_data();

	return ! empty( $home['amenities'] ) && is_array( $home['amenities'] )
		? $home['amenities']
		: array();
}

/**
 * Paid extras catalog for the Extras page (prices from charter calculator).
 *
 * @return array<int, array<string, mixed>>
 */
function seahivez_get_extras_paid_items() {
	$copy = array(
		'seabob'            => array(
			'icon'        => 'seabob',
			'description' => __( 'Enjoy fast, effortless movement above and below the water with a premium Seabob.', 'seahivez-theme' ),
		),
		'jet-ski'           => array(
			'icon'        => 'jet-ski',
			'description' => __( 'Add a Jet Ski to your charter for an extra dose of speed and fun on the water.', 'seahivez-theme' ),
		),
		'efoil-air'         => array(
			'icon'        => 'efoil-air',
			'description' => __( 'Experience silent electric hydrofoil riding above the water with the Efoil Air.', 'seahivez-theme' ),
		),
		'donat'             => array(
			'icon'        => 'donat',
			'description' => __( 'Inflatable donut for relaxing and having fun in the water.', 'seahivez-theme' ),
		),
		'fishing-package'   => array(
			'icon'        => 'fishing-package',
			'description' => __( 'Fishing equipment for up to 4 people, including bait and the required fishing licence.', 'seahivez-theme' ),
		),
	);

	$items = array();

	foreach ( seahivez_get_charter_calculator_extras() as $extra ) {
		$id = (string) ( $extra['id'] ?? '' );

		if ( '' === $id || empty( $copy[ $id ] ) ) {
			continue;
		}

		$items[] = array(
			'id'          => $id,
			'icon'        => (string) $copy[ $id ]['icon'],
			'title'       => (string) ( $extra['label'] ?? '' ),
			'price'       => (int) ( $extra['price'] ?? 0 ),
			'description' => (string) $copy[ $id ]['description'],
		);
	}

	return $items;
}

/**
 * Food & drinks section for the Extras page.
 *
 * @return array<string, mixed>
 */
function seahivez_get_extras_food_drinks_section() {
	$base = seahivez_get_home_food_drinks_data();

	$page_copy = array(
		'food'     => array(
			'description' => __( 'Food can be arranged in advance for your charter.', 'seahivez-theme' ),
		),
		'drinks'   => array(
			'description'      => __( 'Unlimited selection of:', 'seahivez-theme' ),
			'description_list' => array(
				__( 'Wine', 'seahivez-theme' ),
				__( 'Beer', 'seahivez-theme' ),
				__( 'Cava', 'seahivez-theme' ),
				__( 'Cola', 'seahivez-theme' ),
				__( 'Fanta', 'seahivez-theme' ),
				__( 'Sprite', 'seahivez-theme' ),
			),
		),
		'children' => array(
			'description' => __( "A separate children's food option can be arranged on request.", 'seahivez-theme' ),
		),
	);

	$items = array();

	foreach ( $base['items'] as $item ) {
		$id   = (string) ( $item['id'] ?? '' );
		$copy = $page_copy[ $id ] ?? array();

		$items[] = array_merge(
			$item,
			array(
				'description'      => (string) ( $copy['description'] ?? ( $item['description'] ?? '' ) ),
				'description_list' => ! empty( $copy['description_list'] ) ? $copy['description_list'] : array(),
			)
		);
	}

	return array(
		'eyebrow' => (string) ( $base['eyebrow'] ?? __( 'Food & Drinks', 'seahivez-theme' ) ),
		'status'  => (string) ( $base['status'] ?? __( 'Available on request', 'seahivez-theme' ) ),
		'note'    => (string) ( $base['note'] ?? '' ),
		'items'   => $items,
	);
}

/**
 * Good to know notes.
 *
 * @return array<int, string>
 */
function seahivez_get_extras_good_to_know_items() {
	return array(
		__( 'Additional extras are subject to availability.', 'seahivez-theme' ),
		__( 'Food and drinks should be requested in advance.', 'seahivez-theme' ),
		__( 'Optional extras can be selected when planning the charter.', 'seahivez-theme' ),
		__( 'Final availability will be confirmed before the trip.', 'seahivez-theme' ),
	);
}

/**
 * Sort calculator packages longest / highest value first.
 *
 * @param array<int, array<string, mixed>> $items Package rows.
 * @return array<int, array<string, mixed>>
 */
function seahivez_sort_extras_calculator_packages( array $items ) {
	$order = array(
		'full-day' => 0,
		'half-day' => 1,
		'sunset'   => 2,
	);

	usort(
		$items,
		static function ( $a, $b ) use ( $order ) {
			$a_key = (string) ( $a['id'] ?? '' );
			$b_key = (string) ( $b['id'] ?? '' );
			$a_pos = $order[ $a_key ] ?? 99;
			$b_pos = $order[ $b_key ] ?? 99;

			if ( $a_pos === $b_pos ) {
				return strcmp( $a_key, $b_key );
			}

			return $a_pos <=> $b_pos;
		}
	);

	return $items;
}

/**
 * Packages for the Extras page calculator.
 *
 * @param array<int, mixed>|null $selected Optional package post IDs from ACF.
 * @return array<int, array<string, mixed>>
 */
function seahivez_get_extras_page_calculator_packages( $selected = null ) {
	$packages = seahivez_attach_home_charter_calculator_data( seahivez_get_packages_for_display( $selected ) );
	$items    = array();

	foreach ( $packages as $package ) {
		$key = ! empty( $package['package_key'] )
			? (string) $package['package_key']
			: seahivez_get_experience_package_key( $package );

		if ( '' === $key ) {
			continue;
		}

		$base_price = (int) preg_replace( '/[^\d]/', '', (string) ( $package['price'] ?? '0' ) );

		$items[] = array(
			'id'       => $key,
			'title'    => (string) ( $package['title'] ?? '' ),
			'duration' => (string) ( $package['duration'] ?? '' ),
			'price'    => $base_price,
		);
	}

	return seahivez_sort_extras_calculator_packages( $items );
}

/**
 * Calculator JSON configs for the Extras page.
 *
 * @param array<int, array<string, mixed>>|null $packages Optional pre-built package rows.
 * @return array<string, array<string, mixed>>
 */
function seahivez_get_extras_page_calculator_configs( $packages = null ) {
	$packages = is_array( $packages ) ? $packages : seahivez_get_extras_page_calculator_packages();

	$configs = seahivez_get_home_charter_calculator_configs(
		array_map(
			static function ( $item ) {
				return array(
					'title'       => $item['title'],
					'price'       => (string) $item['price'],
					'package_key' => $item['id'],
				);
			},
			$packages
		)
	);

	foreach ( array_keys( $configs ) as $package_key ) {
		if ( ! seahivez_package_supports_charter_calculator( $package_key ) ) {
			unset( $configs[ $package_key ] );
		}
	}

	return $configs;
}

/**
 * Bottom booking CTA for the Extras page.
 *
 * @return array<string, string>
 */
function seahivez_get_extras_booking_cta() {
	return array(
		'heading'          => __( 'Ready to plan your day?', 'seahivez-theme' ),
		'description'      => __( 'Choose your charter and add the extras that make it yours.', 'seahivez-theme' ),
		'primary_label'    => __( 'Book now', 'seahivez-theme' ),
		'primary_url'      => seahivez_get_booking_url(),
		'secondary_label'  => __( 'Contact us', 'seahivez-theme' ),
		'secondary_url'    => home_url( '/contact/' ),
	);
}

/**
 * Extras page editorial intro.
 *
 * @return string
 */
function seahivez_get_extras_page_intro() {
	return __( 'Your charter includes essential equipment for a comfortable day at sea, with additional premium water toys, fishing equipment and catering available on request.', 'seahivez-theme' );
}

/**
 * Extras page gallery header.
 *
 * @return array<string, string>
 */
function seahivez_get_extras_gallery_header() {
	return array(
		'eyebrow'     => __( 'On the water', 'seahivez-theme' ),
		'heading'     => __( 'See the experience', 'seahivez-theme' ),
		'description' => __( 'A glimpse of life aboard SeaHivez and days spent on the Mediterranean.', 'seahivez-theme' ),
	);
}

/**
 * Extras page gallery mosaic items.
 *
 * @return array<int, array<string, mixed>>
 */
function seahivez_get_extras_gallery_items() {
	$items = array(
		array(
			'path'    => 'assets/images/photo/2.jpg',
			'alt'     => __( 'Numarine 55 Fly exterior on the water', 'seahivez-theme' ),
			'caption' => __( 'Numarine 55 Fly exterior on the water', 'seahivez-theme' ),
			'span'    => 'md:col-span-2 md:row-span-2',
		),
		array(
			'path'    => 'assets/images/photo/1.jpg',
			'alt'     => __( 'Aerial view of SeaHivez yacht with guests swimming', 'seahivez-theme' ),
			'caption' => __( 'Aerial view of SeaHivez yacht with guests swimming', 'seahivez-theme' ),
			'span'    => '',
		),
		array(
			'path'    => 'assets/images/photo/3.jpg',
			'alt'     => __( 'Yacht anchored in a turquoise Mediterranean cove', 'seahivez-theme' ),
			'caption' => __( 'Yacht anchored in a turquoise Mediterranean cove', 'seahivez-theme' ),
			'span'    => '',
		),
		array(
			'path'    => 'assets/images/photo/4.png',
			'alt'     => __( 'Sunset charter aboard SeaHivez', 'seahivez-theme' ),
			'caption' => __( 'Sunset light on the Numarine 55 Fly', 'seahivez-theme' ),
			'span'    => 'md:col-span-2',
		),
		array(
			'path'    => 'assets/images/photo/1.jpg',
			'alt'     => __( 'Guests enjoying time on the water', 'seahivez-theme' ),
			'caption' => __( 'Swim stops and open deck living', 'seahivez-theme' ),
			'span'    => '',
		),
		array(
			'path'    => 'assets/images/photo/3.jpg',
			'alt'     => __( 'Mediterranean cove from the yacht', 'seahivez-theme' ),
			'caption' => __( 'Quiet anchorage along the Mallorca coast', 'seahivez-theme' ),
			'span'    => 'sm:col-span-2 md:col-span-1',
		),
	);

	foreach ( $items as $index => $item ) {
		$resolved        = seahivez_resolve_gallery_item_images( $item );
		$items[ $index ] = array_merge( $item, $resolved );
	}

	return $items;
}

/**
 * Default Extras page payload (theme fallbacks).
 *
 * @return array<string, mixed>
 */
function seahivez_get_extras_page_sections_defaults() {
	return array(
		'intro'               => seahivez_get_extras_page_intro(),
		'included_equipment'  => seahivez_get_extras_included_equipment(),
		'included_services'   => seahivez_get_extras_included_services(),
		'paid_items'          => seahivez_get_extras_paid_items(),
		'food_drinks'         => seahivez_get_extras_food_drinks_section(),
		'good_to_know'        => seahivez_get_extras_good_to_know_items(),
		'calculator'          => array(
			'eyebrow'     => __( 'Plan your charter', 'seahivez-theme' ),
			'heading'     => __( 'Calculate your charter price', 'seahivez-theme' ),
			'description' => __( 'Choose your package, route and optional extras to see your estimated total.', 'seahivez-theme' ),
		),
		'calculator_packages' => seahivez_get_extras_page_calculator_packages(),
		'calculator_configs'  => seahivez_get_extras_page_calculator_configs(),
		'gallery_header'      => seahivez_get_extras_gallery_header(),
		'gallery_items'       => seahivez_get_extras_gallery_items(),
		'booking_cta'         => seahivez_get_extras_booking_cta(),
		'included_heading'    => __( 'Included', 'seahivez-theme' ),
		'included_helper'     => __( 'Already included in your charter', 'seahivez-theme' ),
		'paid_heading'        => __( 'Extra Paid', 'seahivez-theme' ),
		'paid_helper'         => __( 'Available on request', 'seahivez-theme' ),
		'good_to_know_title'  => __( 'Good to know', 'seahivez-theme' ),
	);
}

/**
 * Full Extras page payload with optional ACF overrides.
 *
 * @return array<string, mixed>
 */
function seahivez_get_extras_page_sections() {
	return seahivez_map_acf_extras_page_sections( seahivez_get_extras_page_sections_defaults() );
}
