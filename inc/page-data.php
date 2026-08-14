<?php
/**
 * Interior page content helpers.
 *
 * Structured for future ACF migration without rewriting layouts.
 *
 * @package seahivez-theme
 */

/**
 * Default interior page hero args.
 *
 * @param array $args Optional overrides.
 * @return array<string, mixed>
 */
function seahivez_get_page_hero_defaults( $args = array() ) {
	return wp_parse_args(
		$args,
		array(
			'eyebrow'     => '',
			'heading'     => '',
			'description' => '',
			'image'       => '',
			'image_alt'   => '',
			'overlay'     => true,
			'compact'     => false,
		)
	);
}

/**
 * The Yacht page hero.
 *
 * @return array<string, mixed>
 */
function seahivez_get_yacht_page_hero() {
	return seahivez_get_page_hero_defaults(
		array(
			'eyebrow'     => __( 'The Yacht', 'seahivez-theme' ),
			'heading'     => __( 'Meet SeaHivez', 'seahivez-theme' ),
			'description' => __( 'A contemporary Numarine 55 Fly crafted for refined days along the Mallorca coastline — generous outdoor living, calm interiors, and attentive crew.', 'seahivez-theme' ),
			'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/2.jpg' ),
			'image_alt'   => __( 'Numarine 55 Fly exterior on the water', 'seahivez-theme' ),
		)
	);
}

/**
 * Yacht layout spreads — deck plans with interior/exterior feature lists.
 *
 * @return array<int, array<string, mixed>>
 */
function seahivez_get_yacht_layout_sections() {
	return array(
		array(
			'id'           => 'interiors',
			'eyebrow'      => __( 'Interiors', 'seahivez-theme' ),
			'heading'      => __( 'Inside the yacht', 'seahivez-theme' ),
			'description'  => __( 'Light-filled cabins and a refined salon create a calm onboard atmosphere — designed for comfort between swim stops, lunch, and golden-hour cruising.', 'seahivez-theme' ),
			'list_heading' => __( 'Below deck', 'seahivez-theme' ),
			'plan'         => array(
				'path'  => 'assets/images/home/interiores.png',
				'alt'   => __( 'Numarine 55 Fly interior deck plan', 'seahivez-theme' ),
				'frame' => 'light',
			),
			'items' => array(
				__( 'Full-beam master cabin with ensuite', 'seahivez-theme' ),
				__( 'VIP forward cabin', 'seahivez-theme' ),
				__( 'Twin guest cabin', 'seahivez-theme' ),
				__( 'Open salon & dining area', 'seahivez-theme' ),
				__( 'Fully equipped galley', 'seahivez-theme' ),
				__( '2 bathrooms with showers', 'seahivez-theme' ),
				__( 'Natural light throughout', 'seahivez-theme' ),
				__( 'Air-conditioned interiors', 'seahivez-theme' ),
			),
			'bg' => 'bg-warm-white',
		),
		array(
			'id'           => 'exteriors',
			'eyebrow'      => __( 'Exterior areas', 'seahivez-theme' ),
			'heading'      => __( 'Outdoor living', 'seahivez-theme' ),
			'description'  => __( 'From the elevated flybridge to the aft lounge and swim platform, open decks are laid out for sun, conversation, and effortless Mediterranean entertaining.', 'seahivez-theme' ),
			'list_heading' => __( 'On deck', 'seahivez-theme' ),
			'plan'         => array(
				'path'  => 'assets/images/home/exteriores.png',
				'alt'   => __( 'Numarine 55 Fly exterior deck plan', 'seahivez-theme' ),
				'frame' => 'dark',
			),
			'items' => array(
				__( 'Elevated flybridge with seating', 'seahivez-theme' ),
				__( 'Bow sunpad & forward lounge', 'seahivez-theme' ),
				__( 'Aft lounge with U-shaped sofa', 'seahivez-theme' ),
				__( 'Outdoor dining & coffee table', 'seahivez-theme' ),
				__( 'Main cockpit & helm station', 'seahivez-theme' ),
				__( 'Teak swim platform', 'seahivez-theme' ),
				__( 'Hard-top shading', 'seahivez-theme' ),
				__( 'Panoramic Mediterranean views', 'seahivez-theme' ),
			),
			'bg' => 'bg-sand-50',
		),
	);
}

/**
 * Yacht page editorial blocks.
 *
 * @return array<int, array<string, string>>
 */
function seahivez_get_yacht_editorial_sections() {
	return array(
		array(
			'eyebrow'     => __( 'Design', 'seahivez-theme' ),
			'heading'     => __( 'Contemporary flybridge profile', 'seahivez-theme' ),
			'description' => __( 'Clean lines, generous glazing, and a balanced hull form define the Numarine 55 Fly — a modern silhouette built for confident cruising along the Balearic coast.', 'seahivez-theme' ),
			'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/3.jpg' ),
			'image_alt'   => __( 'Yacht anchored in a Mediterranean cove', 'seahivez-theme' ),
			'reverse'     => true,
		),
		array(
			'eyebrow'     => __( 'Cruising', 'seahivez-theme' ),
			'heading'     => __( 'Mediterranean days', 'seahivez-theme' ),
			'description' => __( 'From hidden coves near S\'Arenal to the open waters off Palma, every charter is paced to your preferences — elegant, flexible, and unmistakably Mallorcan.', 'seahivez-theme' ),
			'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/4.png' ),
			'image_alt'   => __( 'Sunset charter aboard SeaHivez', 'seahivez-theme' ),
			'reverse'     => false,
		),
	);
}

/**
 * Yacht page gallery mosaic items (more photos than homepage preview).
 *
 * @return array<int, array<string, mixed>>
 */
function seahivez_get_yacht_gallery_items() {
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
			'path'    => 'assets/images/photo/3.jpg',
			'alt'     => __( 'Mediterranean cove from the yacht', 'seahivez-theme' ),
			'caption' => __( 'Quiet anchorage along the Mallorca coast', 'seahivez-theme' ),
			'span'    => '',
		),
		array(
			'path'    => 'assets/images/photo/1.jpg',
			'alt'     => __( 'Day on the water with SeaHivez', 'seahivez-theme' ),
			'caption' => __( 'Swim stops and open deck living', 'seahivez-theme' ),
			'span'    => '',
		),
		array(
			'path'    => 'assets/images/photo/2.jpg',
			'alt'     => __( 'SeaHivez yacht cruising Mallorca', 'seahivez-theme' ),
			'caption' => __( 'Cruising the Balearic coastline', 'seahivez-theme' ),
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
 * Yacht page specification groups (ACF-ready titles).
 *
 * @return array<int, array<string, mixed>>
 */
function seahivez_get_yacht_specification_groups() {
	$home = seahivez_get_home_specification_groups();

	return array(
		array(
			'title'      => __( 'Dimensions & Performance', 'seahivez-theme' ),
			'grid_class' => 'grid grid-cols-2 gap-x-6 gap-y-8 sm:grid-cols-3 lg:grid-cols-6',
			'items'      => $home[0]['items'],
		),
		array(
			'title'      => __( 'Comfort', 'seahivez-theme' ),
			'grid_class' => 'grid grid-cols-2 gap-x-6 gap-y-8 md:grid-cols-3',
			'items'      => $home[1]['items'],
		),
		array(
			'title'      => __( 'Crew & Service', 'seahivez-theme' ),
			'grid_class' => 'grid grid-cols-2 gap-x-6 gap-y-8 md:grid-cols-2',
			'items'      => $home[2]['items'],
		),
	);
}

/**
 * Crew roles (no invented biographies).
 *
 * @return array<int, array<string, string>>
 */
function seahivez_get_yacht_crew() {
	return array(
		array(
			'role'        => __( 'Captain', 'seahivez-theme' ),
			'description' => __( 'Professional skipper responsible for navigation, safety, and a seamless day on the water.', 'seahivez-theme' ),
		),
		array(
			'role'        => __( 'Deckhand', 'seahivez-theme' ),
			'description' => __( 'Attentive support on deck — assisting with boarding, service, and guest comfort throughout the charter.', 'seahivez-theme' ),
		),
	);
}

/**
 * Experiences page extras included per package (shared messaging).
 *
 * @return array<int, string>
 */
function seahivez_get_experience_included_items() {
	return array(
		__( 'Professional captain & deckhand', 'seahivez-theme' ),
		__( 'Towel service', 'seahivez-theme' ),
		__( 'Final cleaning', 'seahivez-theme' ),
		__( 'Insurance & taxes', 'seahivez-theme' ),
		__( 'Drinking water', 'seahivez-theme' ),
	);
}

/**
 * FAQ page optional groups.
 *
 * @return array<int, array<string, mixed>>
 */
function seahivez_get_faq_page_groups() {
	$items = seahivez_get_home_faq_items();

	return array(
		array(
			'title' => __( 'Booking', 'seahivez-theme' ),
			'items' => array_values(
				array_filter(
					$items,
					static function ( $item ) {
						$q = isset( $item['question'] ) ? $item['question'] : '';
						return false !== stripos( $q, 'book' ) || false !== stripos( $q, 'depart' ) || false !== stripos( $q, 'guests' ) || false !== stripos( $q, 'included' );
					}
				)
			),
		),
		array(
			'title' => __( 'On board', 'seahivez-theme' ),
			'items' => array_values(
				array_filter(
					$items,
					static function ( $item ) {
						$q = isset( $item['question'] ) ? $item['question'] : '';
						return false !== stripos( $q, 'route' ) || false !== stripos( $q, 'food' ) || false !== stripos( $q, 'children' );
					}
				)
			),
		),
		array(
			'title' => __( 'Weather & safety', 'seahivez-theme' ),
			'items' => array_values(
				array_filter(
					$items,
					static function ( $item ) {
						$q = isset( $item['question'] ) ? $item['question'] : '';
						return false !== stripos( $q, 'weather' );
					}
				)
			),
		),
	);
}

/**
 * Booking "what happens next" steps.
 *
 * @return array<int, array<string, string>>
 */
function seahivez_get_booking_steps() {
	return array(
		array(
			'title'       => __( 'Choose experience and date', 'seahivez-theme' ),
			'description' => __( 'Select Sunset, Half Day or Full Day and your preferred schedule.', 'seahivez-theme' ),
		),
		array(
			'title'       => __( 'Submit booking', 'seahivez-theme' ),
			'description' => __( 'Send your request through the booking form or SuperSaaS widget.', 'seahivez-theme' ),
		),
		array(
			'title'       => __( 'Availability is confirmed', 'seahivez-theme' ),
			'description' => __( 'Our team confirms the charter and shares next steps by email or WhatsApp.', 'seahivez-theme' ),
		),
		array(
			'title'       => __( 'Receive meeting instructions', 'seahivez-theme' ),
			'description' => __( 'Exact boarding details for S\'Arenal are provided before departure.', 'seahivez-theme' ),
		),
	);
}

/**
 * Contact page hero.
 *
 * @return array<string, mixed>
 */
function seahivez_get_contact_page_hero() {
	return seahivez_get_page_hero_defaults(
		array(
			'eyebrow'     => __( 'Contact', 'seahivez-theme' ),
			'heading'     => __( 'Plan your day in Mallorca', 'seahivez-theme' ),
			'description' => __( 'Tell us your preferred date and charter style — we will confirm availability and help shape a seamless day on the water.', 'seahivez-theme' ),
			'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/1.jpg' ),
			'image_alt'   => __( 'SeaHivez yacht in Mallorca', 'seahivez-theme' ),
			'compact'     => true,
		)
	);
}
