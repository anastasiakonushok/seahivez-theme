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
	return seahivez_map_acf_page_hero(
		seahivez_get_page_hero_defaults(
			array(
				'eyebrow'     => __( 'The Yacht', 'seahivez-theme' ),
				'heading'     => __( 'Meet SeaHivez', 'seahivez-theme' ),
				'description' => __( 'A contemporary Numarine 55 Fly crafted for refined days along the Mallorca coastline — generous outdoor living, calm interiors, and attentive crew.', 'seahivez-theme' ),
				'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/2.jpg' ),
				'image_alt'   => __( 'Numarine 55 Fly exterior on the water', 'seahivez-theme' ),
			)
		)
	);
}

/**
 * Yacht page intro block (about-style two column).
 *
 * @return array<string, mixed>
 */
function seahivez_get_yacht_page_intro() {
	$defaults = seahivez_get_home_about_data();

	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$intro = get_field( 'intro' );

	if ( empty( $intro ) || ! is_array( $intro ) ) {
		return $defaults;
	}

	$paragraphs = array();

	if ( ! empty( $intro['paragraphs'] ) && is_array( $intro['paragraphs'] ) ) {
		foreach ( $intro['paragraphs'] as $row ) {
			if ( ! empty( $row['text'] ) ) {
				$paragraphs[] = (string) $row['text'];
			}
		}
	}

	$image = $intro['image'] ?? null;

	return array(
		'eyebrow'    => ! empty( $intro['eyebrow'] ) ? (string) $intro['eyebrow'] : $defaults['eyebrow'],
		'heading'    => ! empty( $intro['heading'] ) ? (string) $intro['heading'] : $defaults['heading'],
		'paragraphs' => ! empty( $paragraphs ) ? $paragraphs : $defaults['paragraphs'],
		'link_label' => $defaults['link_label'],
		'link_url'   => $defaults['link_url'],
		'image'      => seahivez_get_acf_image_url( $image, 'large', $defaults['image'] ),
		'image_alt'  => ! empty( $intro['image_alt'] ) ? (string) $intro['image_alt'] : $defaults['image_alt'],
	);
}

/**
 * Yacht layout spreads — deck plans with interior/exterior feature lists.
 *
 * @return array<int, array<string, mixed>>
 */
function seahivez_get_yacht_layout_sections() {
	$defaults = array(
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

	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$rows = get_field( 'layout_sections' );

	if ( empty( $rows ) || ! is_array( $rows ) ) {
		return $defaults;
	}

	$mapped = array();

	foreach ( $rows as $index => $row ) {
		$items = array();

		if ( ! empty( $row['features'] ) && is_array( $row['features'] ) ) {
			foreach ( $row['features'] as $feature ) {
				if ( ! empty( $feature['text'] ) ) {
					$items[] = (string) $feature['text'];
				}
			}
		}

		$plan_image = $row['plan_image'] ?? null;
		$plan_url   = seahivez_get_acf_image_url( $plan_image, 'large', '' );
		$plan       = array(
			'path'  => '',
			'url'   => $plan_url,
			'alt'   => ! empty( $row['plan_alt'] ) ? (string) $row['plan_alt'] : '',
			'frame' => ! empty( $row['plan_frame'] ) ? sanitize_key( (string) $row['plan_frame'] ) : 'light',
		);

		if ( empty( $plan_url ) && ! empty( $defaults[ $index ]['plan']['path'] ) ) {
			$plan = $defaults[ $index ]['plan'];
		}

		$mapped[] = array(
			'id'           => ! empty( $row['section_id'] ) ? sanitize_key( (string) $row['section_id'] ) : 'layout-' . $index,
			'eyebrow'      => (string) ( $row['eyebrow'] ?? '' ),
			'heading'      => (string) ( $row['heading'] ?? '' ),
			'description'  => (string) ( $row['description'] ?? '' ),
			'list_heading' => (string) ( $row['list_heading'] ?? '' ),
			'plan'         => $plan,
			'items'        => ! empty( $items ) ? $items : ( $defaults[ $index ]['items'] ?? array() ),
			'bg'           => ! empty( $row['background'] ) ? (string) $row['background'] : ( $defaults[ $index ]['bg'] ?? 'bg-warm-white' ),
		);
	}

	return ! empty( $mapped ) ? $mapped : $defaults;
}

/**
 * Yacht page editorial blocks.
 *
 * @return array<int, array<string, string>>
 */
function seahivez_get_yacht_editorial_sections() {
	$defaults = array(
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

	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$rows = get_field( 'editorial_sections' );

	if ( empty( $rows ) || ! is_array( $rows ) ) {
		return $defaults;
	}

	$mapped = array();

	foreach ( $rows as $row ) {
		$image = $row['image'] ?? null;

		if ( empty( $row['heading'] ) ) {
			continue;
		}

		$mapped[] = array(
			'eyebrow'     => (string) ( $row['eyebrow'] ?? '' ),
			'heading'     => (string) $row['heading'],
			'description' => (string) ( $row['description'] ?? '' ),
			'image'       => seahivez_get_acf_image_url( $image, 'large', '' ),
			'image_alt'   => ! empty( $row['image_alt'] ) ? (string) $row['image_alt'] : (string) ( $row['heading'] ?? '' ),
			'reverse'     => ! empty( $row['reverse'] ),
		);
	}

	return ! empty( $mapped ) ? $mapped : $defaults;
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

	$spans = array(
		'md:col-span-2 md:row-span-2',
		'',
		'',
		'md:col-span-2',
		'',
		'',
		'sm:col-span-2 md:col-span-1',
	);

	$acf_images = null;

	if ( seahivez_acf_is_active() ) {
		$gallery = get_field( 'gallery' );

		if ( ! empty( $gallery['images'] ) && is_array( $gallery['images'] ) ) {
			$acf_images = $gallery['images'];
		}
	}

	$acf_items = seahivez_map_acf_gallery_image_array( $acf_images, array() );

	if ( ! empty( $acf_items ) ) {
		foreach ( $acf_items as $index => $acf_item ) {
			$acf_items[ $index ]['span'] = $spans[ $index % count( $spans ) ];
		}

		return $acf_items;
	}

	return $items;
}

/**
 * Yacht page specification groups (ACF-ready titles).
 *
 * @return array<int, array<string, mixed>>
 */
function seahivez_get_yacht_specification_groups() {
	$home     = seahivez_get_home_specification_groups();
	$defaults = array(
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

	if ( seahivez_acf_is_active() ) {
		$specs = get_field( 'specifications' );

		if ( ! empty( $specs['groups'] ) && is_array( $specs['groups'] ) ) {
			$mapped = seahivez_map_acf_specification_groups_from_rows( $specs['groups'], $defaults );

			if ( ! empty( $mapped ) ) {
				return $mapped;
			}
		}
	}

	return seahivez_map_acf_specification_groups( $defaults, 'specification_groups' );
}

/**
 * Yacht specifications section header.
 *
 * @return array<string, string>
 */
function seahivez_get_yacht_specifications_header() {
	$defaults = array(
		'eyebrow' => __( 'Specifications', 'seahivez-theme' ),
		'heading' => __( 'Key specifications', 'seahivez-theme' ),
	);

	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$specs = get_field( 'specifications' );

	if ( empty( $specs ) || ! is_array( $specs ) ) {
		return $defaults;
	}

	return array(
		'eyebrow' => ! empty( $specs['eyebrow'] ) ? (string) $specs['eyebrow'] : $defaults['eyebrow'],
		'heading' => ! empty( $specs['heading'] ) ? (string) $specs['heading'] : $defaults['heading'],
	);
}

/**
 * Yacht gallery section header for the yacht page.
 *
 * @return array<string, string>
 */
function seahivez_get_yacht_gallery_header() {
	$defaults = array(
		'eyebrow'     => seahivez_get_home_gallery_header()['eyebrow'],
		'heading'     => __( 'Life on board', 'seahivez-theme' ),
		'description' => __( 'Explore interiors, decks and Mediterranean moments aboard the Numarine 55 Fly.', 'seahivez-theme' ),
		'cta_label'   => __( 'View full gallery', 'seahivez-theme' ),
		'cta_url'     => home_url( '/gallery/' ),
	);

	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$gallery = get_field( 'gallery' );

	if ( empty( $gallery ) || ! is_array( $gallery ) ) {
		return $defaults;
	}

	$cta = seahivez_parse_acf_link(
		$gallery['cta_link'] ?? null,
		array(
			'label' => $defaults['cta_label'],
			'url'   => $defaults['cta_url'],
		)
	);

	return array(
		'eyebrow'     => ! empty( $gallery['eyebrow'] ) ? (string) $gallery['eyebrow'] : $defaults['eyebrow'],
		'heading'     => ! empty( $gallery['heading'] ) ? (string) $gallery['heading'] : $defaults['heading'],
		'description' => ! empty( $gallery['description'] ) ? (string) $gallery['description'] : $defaults['description'],
		'cta_label'   => $cta['label'],
		'cta_url'     => $cta['url'],
	);
}

/**
 * Crew roles (no invented biographies).
 *
 * @return array<int, array<string, string>>
 */
function seahivez_get_yacht_crew() {
	$defaults = array(
		array(
			'role'        => __( 'Captain', 'seahivez-theme' ),
			'description' => __( 'Professional skipper responsible for navigation, safety, and a seamless day on the water.', 'seahivez-theme' ),
		),
		array(
			'role'        => __( 'Deckhand', 'seahivez-theme' ),
			'description' => __( 'Attentive support on deck — assisting with boarding, service, and guest comfort throughout the charter.', 'seahivez-theme' ),
		),
	);

	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$crew = get_field( 'crew' );

	if ( empty( $crew ) || ! is_array( $crew ) || empty( $crew['members'] ) ) {
		return $defaults;
	}

	$members = array();

	foreach ( $crew['members'] as $member ) {
		if ( empty( $member['role'] ) ) {
			continue;
		}

		$members[] = array(
			'role'        => (string) $member['role'],
			'description' => (string) ( $member['description'] ?? '' ),
		);
	}

	return ! empty( $members ) ? $members : $defaults;
}

/**
 * Yacht crew section header.
 *
 * @return array<string, string>
 */
function seahivez_get_yacht_crew_header() {
	$defaults = array(
		'eyebrow'     => __( 'Crew', 'seahivez-theme' ),
		'heading'     => __( 'Captain + Deckhand', 'seahivez-theme' ),
		'description' => __( 'Every charter is supported by a professional crew focused on safety, service, and an unhurried Mediterranean pace.', 'seahivez-theme' ),
	);

	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$crew = get_field( 'crew' );

	if ( empty( $crew ) || ! is_array( $crew ) ) {
		return $defaults;
	}

	return array(
		'eyebrow'     => ! empty( $crew['eyebrow'] ) ? (string) $crew['eyebrow'] : $defaults['eyebrow'],
		'heading'     => ! empty( $crew['heading'] ) ? (string) $crew['heading'] : $defaults['heading'],
		'description' => ! empty( $crew['description'] ) ? (string) $crew['description'] : $defaults['description'],
	);
}

/**
 * Experiences page extras included per package (shared messaging).
 *
 * @return array<int, string>
 */
function seahivez_get_experience_included_items() {
	$defaults = array(
		__( 'Professional captain & deckhand', 'seahivez-theme' ),
		__( 'Towel service', 'seahivez-theme' ),
		__( 'Final cleaning', 'seahivez-theme' ),
		__( 'Insurance & taxes', 'seahivez-theme' ),
		__( 'Drinking water', 'seahivez-theme' ),
	);

	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$rows = get_field( 'included_items' );

	if ( empty( $rows ) || ! is_array( $rows ) ) {
		return $defaults;
	}

	$items = array();

	foreach ( $rows as $row ) {
		if ( ! empty( $row['text'] ) ) {
			$items[] = (string) $row['text'];
		}
	}

	return ! empty( $items ) ? $items : $defaults;
}

/**
 * News / blog posts page hero.
 *
 * Fields are edited on the page set as "Posts page" in Settings → Reading.
 *
 * @return array<string, mixed>
 */
function seahivez_get_news_page_hero() {
	$defaults = seahivez_get_page_hero_defaults(
		array(
			'eyebrow'     => __( 'News & Inspiration', 'seahivez-theme' ),
			'heading'     => __( 'Stories from Mallorca', 'seahivez-theme' ),
			'description' => __( 'Discover local places, charter inspiration, hidden coves and life on the Mediterranean.', 'seahivez-theme' ),
			'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/1.jpg' ),
			'image_alt'   => __( 'SeaHivez news and inspiration', 'seahivez-theme' ),
			'compact'     => true,
		)
	);

	$posts_page_id = (int) get_option( 'page_for_posts' );

	if ( $posts_page_id <= 0 ) {
		return $defaults;
	}

	return seahivez_map_acf_page_hero( $defaults, 'hero', $posts_page_id );
}

/**
 * Posts per page for the news archive.
 *
 * @return int
 */
function seahivez_get_news_posts_per_page() {
	$default = 9;

	if ( ! seahivez_acf_is_active() ) {
		return $default;
	}

	$posts_page_id = (int) get_option( 'page_for_posts' );

	if ( $posts_page_id <= 0 ) {
		return $default;
	}

	$archive = get_field( 'news_archive', $posts_page_id );

	if ( empty( $archive ) || ! is_array( $archive ) ) {
		return $default;
	}

	$count = isset( $archive['posts_per_page'] ) ? (int) $archive['posts_per_page'] : 0;

	return $count > 0 ? $count : $default;
}

/**
 * FAQ page hero.
 *
 * @return array<string, mixed>
 */
function seahivez_get_faq_page_hero() {
	return seahivez_map_acf_page_hero(
		seahivez_get_page_hero_defaults(
			array(
				'eyebrow'     => __( 'FAQ', 'seahivez-theme' ),
				'heading'     => __( 'Everything you need to know', 'seahivez-theme' ),
				'description' => __( 'Practical answers before your day on the water with SeaHivez.', 'seahivez-theme' ),
				'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/3.jpg' ),
				'image_alt'   => __( 'FAQ', 'seahivez-theme' ),
				'compact'     => true,
			)
		)
	);
}

/**
 * FAQ page sidebar intro (not the top hero).
 *
 * @return array<string, string>
 */
function seahivez_get_faq_page_intro() {
	$defaults = seahivez_get_home_faq_header();

	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$intro = get_field( 'faq_intro' );

	if ( empty( $intro ) || ! is_array( $intro ) ) {
		return $defaults;
	}

	$cta = seahivez_parse_acf_link(
		$intro['cta_link'] ?? null,
		array(
			'label' => $defaults['cta_label'],
			'url'   => $defaults['contact_url'],
		)
	);

	return array(
		'heading'      => ! empty( $intro['heading'] ) ? (string) $intro['heading'] : $defaults['heading'],
		'description'  => ! empty( $intro['description'] ) ? (string) $intro['description'] : $defaults['description'],
		'cta_heading'  => ! empty( $intro['cta_heading'] ) ? (string) $intro['cta_heading'] : $defaults['cta_heading'],
		'cta_label'    => $cta['label'],
		'contact_url'  => $cta['url'],
	);
}

/**
 * FAQ page URL.
 *
 * @return string
 */
function seahivez_get_faq_page_url() {
	$faq_page = get_page_by_path( 'faq' );

	if ( $faq_page instanceof WP_Post ) {
		return get_permalink( $faq_page );
	}

	return home_url( '/faq/' );
}

/**
 * FAQ page optional groups.
 *
 * @return array<int, array<string, mixed>>
 */
function seahivez_get_faq_page_groups() {
	$items = seahivez_get_home_faq_items();

	$defaults = array(
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

	return seahivez_map_acf_faq_groups( $defaults, 'faq_groups' );
}

/**
 * Booking "what happens next" steps.
 *
 * @return array<int, array<string, string>>
 */
function seahivez_get_booking_steps() {
	$defaults = array(
		array(
			'title'       => __( 'Choose experience and date', 'seahivez-theme' ),
			'description' => __( 'Select Sunset, Half Day or Full Day and your preferred schedule.', 'seahivez-theme' ),
		),
		array(
			'title'       => __( 'Submit booking', 'seahivez-theme' ),
			'description' => __( 'Complete your reservation in the SuperSaaS calendar.', 'seahivez-theme' ),
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

	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$rows = get_field( 'booking_steps' );

	if ( empty( $rows ) || ! is_array( $rows ) ) {
		return $defaults;
	}

	$steps = array();

	foreach ( $rows as $row ) {
		if ( empty( $row['title'] ) ) {
			continue;
		}

		$steps[] = array(
			'title'       => (string) $row['title'],
			'description' => (string) ( $row['description'] ?? '' ),
		);
	}

	return ! empty( $steps ) ? $steps : $defaults;
}

/**
 * Contact page hero.
 *
 * @return array<string, mixed>
 */
function seahivez_get_contact_page_hero() {
	return seahivez_map_acf_page_hero(
		seahivez_get_page_hero_defaults(
			array(
				'eyebrow'     => __( 'Contact', 'seahivez-theme' ),
				'heading'     => __( 'Plan your day in Mallorca', 'seahivez-theme' ),
				'description' => __( 'Tell us your preferred date and charter style — we will confirm availability and help shape a seamless day on the water.', 'seahivez-theme' ),
				'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/1.jpg' ),
				'image_alt'   => __( 'SeaHivez yacht in Mallorca', 'seahivez-theme' ),
				'compact'     => true,
			)
		)
	);
}

/**
 * Contact page details column.
 *
 * @return array<string, string>
 */
function seahivez_get_contact_page_details() {
	$location = seahivez_get_home_location_data();
	$defaults = array(
		'eyebrow'        => __( 'Follow / Contact us', 'seahivez-theme' ),
		'heading'        => $location['location'],
		'social_heading' => __( 'Instagram & WhatsApp', 'seahivez-theme' ),
	);

	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$details = get_field( 'contact_details' );

	if ( empty( $details ) || ! is_array( $details ) ) {
		return $defaults;
	}

	return array(
		'eyebrow'        => ! empty( $details['eyebrow'] ) ? (string) $details['eyebrow'] : $defaults['eyebrow'],
		'heading'        => ! empty( $details['heading'] ) ? (string) $details['heading'] : $defaults['heading'],
		'social_heading' => ! empty( $details['social_heading'] ) ? (string) $details['social_heading'] : $defaults['social_heading'],
	);
}

/**
 * Contact page form labels.
 *
 * @return array<string, string>
 */
function seahivez_get_contact_page_form() {
	$defaults = array(
		'heading'            => __( 'Send a message', 'seahivez-theme' ),
		'description'        => __( 'Questions about your charter? Write to us — for date reservations use the booking calendar.', 'seahivez-theme' ),
		'booking_link_label' => __( 'Book a charter', 'seahivez-theme' ),
	);

	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$form = get_field( 'contact_form' );

	if ( empty( $form ) || ! is_array( $form ) ) {
		return $defaults;
	}

	return array(
		'heading'            => ! empty( $form['heading'] ) ? (string) $form['heading'] : $defaults['heading'],
		'description'        => ! empty( $form['description'] ) ? (string) $form['description'] : $defaults['description'],
		'booking_link_label' => ! empty( $form['booking_link_label'] ) ? (string) $form['booking_link_label'] : $defaults['booking_link_label'],
	);
}

/**
 * Contact page map block.
 *
 * @return array<string, string>
 */
function seahivez_get_contact_page_map() {
	$port     = seahivez_get_port_location();
	$defaults = array(
		'eyebrow'  => __( 'Location', 'seahivez-theme' ),
		'heading'  => ! empty( $port['label'] ) ? $port['label'] : __( "S'Arenal / Mallorca", 'seahivez-theme' ),
		'lat'      => isset( $port['lat'] ) ? (string) $port['lat'] : '',
		'lng'      => isset( $port['lng'] ) ? (string) $port['lng'] : '',
		'label'    => isset( $port['label'] ) ? $port['label'] : 'SeaHivez',
		'place'    => isset( $port['place'] ) ? $port['place'] : '',
		'maps_url' => isset( $port['maps_url'] ) ? $port['maps_url'] : '',
	);

	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$map = get_field( 'contact_map' );

	if ( empty( $map ) || ! is_array( $map ) ) {
		return $defaults;
	}

	return array(
		'eyebrow'  => ! empty( $map['eyebrow'] ) ? (string) $map['eyebrow'] : $defaults['eyebrow'],
		'heading'  => ! empty( $map['heading'] ) ? (string) $map['heading'] : $defaults['heading'],
		'lat'      => ! empty( $map['lat'] ) ? (string) $map['lat'] : $defaults['lat'],
		'lng'      => ! empty( $map['lng'] ) ? (string) $map['lng'] : $defaults['lng'],
		'label'    => ! empty( $map['label'] ) ? (string) $map['label'] : $defaults['label'],
		'place'    => ! empty( $map['place'] ) ? (string) $map['place'] : $defaults['place'],
		'maps_url' => ! empty( $map['maps_url'] ) ? (string) $map['maps_url'] : $defaults['maps_url'],
	);
}

/**
 * Gallery page hero.
 *
 * @return array<string, mixed>
 */
function seahivez_get_gallery_page_hero() {
	return seahivez_map_acf_page_hero(
		seahivez_get_page_hero_defaults(
			array(
				'eyebrow'     => __( 'Gallery', 'seahivez-theme' ),
				'heading'     => __( 'Life on board SeaHivez', 'seahivez-theme' ),
				'description' => __( 'Discover the spaces, details and Mediterranean light that define the SeaHivez experience.', 'seahivez-theme' ),
				'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/3.jpg' ),
				'image_alt'   => __( 'SeaHivez gallery', 'seahivez-theme' ),
				'compact'     => true,
			)
		)
	);
}

/**
 * Gallery page images.
 *
 * @return array<int, array<string, mixed>>
 */
function seahivez_get_gallery_page_items() {
	return seahivez_map_acf_gallery_items( seahivez_get_home_gallery_items(), 'gallery_images' );
}

/**
 * Booking page hero.
 *
 * @return array<string, mixed>
 */
function seahivez_get_booking_page_hero() {
	return seahivez_map_acf_page_hero(
		seahivez_get_page_hero_defaults(
			array(
				'eyebrow'     => __( 'Book your experience', 'seahivez-theme' ),
				'heading'     => __( 'Your day on the Mediterranean starts here', 'seahivez-theme' ),
				'description' => __( 'Choose your charter style and preferred date. Availability is confirmed by our team before departure.', 'seahivez-theme' ),
				'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/2.jpg' ),
				'image_alt'   => __( 'Book a SeaHivez charter', 'seahivez-theme' ),
				'compact'     => true,
			)
		)
	);
}

/**
 * Booking widget intro copy.
 *
 * @return array<string, string>
 */
function seahivez_get_booking_widget_content() {
	$defaults = array(
		'heading'     => __( 'Book Your Mallorca Yacht Rental', 'seahivez-theme' ),
		'description' => __( 'Choose your experience and preferred date — availability updates in real time.', 'seahivez-theme' ),
	);

	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$widget = get_field( 'booking_widget' );

	if ( empty( $widget ) || ! is_array( $widget ) ) {
		return $defaults;
	}

	return array(
		'heading'     => ! empty( $widget['heading'] ) ? (string) $widget['heading'] : $defaults['heading'],
		'description' => ! empty( $widget['description'] ) ? (string) $widget['description'] : $defaults['description'],
	);
}

/**
 * Booking page sidebar content.
 *
 * @return array<string, string>
 */
function seahivez_get_booking_sidebar_content() {
	$defaults = array(
		'packages_heading' => __( 'Charter packages', 'seahivez-theme' ),
		'steps_heading'    => __( 'What happens next?', 'seahivez-theme' ),
		'whatsapp_label'   => __( 'Chat on WhatsApp', 'seahivez-theme' ),
	);

	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$sidebar = get_field( 'booking_sidebar' );

	if ( empty( $sidebar ) || ! is_array( $sidebar ) ) {
		return $defaults;
	}

	return array(
		'packages_heading' => ! empty( $sidebar['packages_heading'] ) ? (string) $sidebar['packages_heading'] : $defaults['packages_heading'],
		'steps_heading'    => ! empty( $sidebar['steps_heading'] ) ? (string) $sidebar['steps_heading'] : $defaults['steps_heading'],
		'whatsapp_label'   => ! empty( $sidebar['whatsapp_label'] ) ? (string) $sidebar['whatsapp_label'] : $defaults['whatsapp_label'],
	);
}

/**
 * Experiences page hero.
 *
 * @return array<string, mixed>
 */
function seahivez_get_experiences_page_hero() {
	return seahivez_map_acf_page_hero(
		seahivez_get_page_hero_defaults(
			array(
				'eyebrow'     => __( 'Experiences', 'seahivez-theme' ),
				'heading'     => __( 'Choose your day on the water', 'seahivez-theme' ),
				'description' => __( 'Sunset escapes, half-day coastal cruising, or a full day discovering Mallorca from the sea.', 'seahivez-theme' ),
				'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/4.png' ),
				'image_alt'   => __( 'Sunset charter experience', 'seahivez-theme' ),
				'compact'     => true,
			)
		)
	);
}

/**
 * Experiences listed on the experiences page.
 *
 * @return array<int, array<string, string>>
 */
function seahivez_get_experiences_page_packages() {
	return seahivez_map_acf_experience_packages( seahivez_get_home_experiences(), 'packages' );
}

/**
 * Extras page hero.
 *
 * @return array<string, mixed>
 */
function seahivez_get_extras_page_hero() {
	return seahivez_map_acf_page_hero(
		seahivez_get_page_hero_defaults(
			array(
				'eyebrow'     => __( 'Toys & Extras', 'seahivez-theme' ),
				'heading'     => __( 'More ways to enjoy the water', 'seahivez-theme' ),
				'description' => __( 'From essential equipment included in every charter to premium water toys available on request.', 'seahivez-theme' ),
				'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/1.jpg' ),
				'image_alt'   => __( 'Water toys and extras', 'seahivez-theme' ),
				'compact'     => true,
			)
		)
	);
}

/**
 * Extras page content block.
 *
 * @return array<string, mixed>
 */
function seahivez_get_extras_page_data() {
	$defaults = seahivez_get_home_extras_data();

	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$content = get_field( 'extras_content' );

	if ( empty( $content ) || ! is_array( $content ) ) {
		return $defaults;
	}

	$included = array();
	$paid     = array();
	$amenities = array();

	if ( ! empty( $content['included'] ) && is_array( $content['included'] ) ) {
		foreach ( $content['included'] as $row ) {
			$included[] = array(
				'icon'     => seahivez_normalize_acf_icon( $row['icon'] ?? '' ),
				'title'    => (string) ( $row['title'] ?? '' ),
				'included' => true,
			);
		}
	}

	if ( ! empty( $content['paid'] ) && is_array( $content['paid'] ) ) {
		foreach ( $content['paid'] as $row ) {
			$paid[] = array(
				'icon'     => seahivez_normalize_acf_icon( $row['icon'] ?? '' ),
				'title'    => (string) ( $row['title'] ?? '' ),
				'price'    => (string) ( $row['price'] ?? '' ),
				'included' => false,
			);
		}
	}

	if ( ! empty( $content['amenities'] ) && is_array( $content['amenities'] ) ) {
		foreach ( $content['amenities'] as $row ) {
			if ( ! empty( $row['text'] ) ) {
				$amenities[] = (string) $row['text'];
			}
		}
	}

	return array(
		'eyebrow'          => ! empty( $content['eyebrow'] ) ? (string) $content['eyebrow'] : $defaults['eyebrow'],
		'heading'          => ! empty( $content['heading'] ) ? (string) $content['heading'] : $defaults['heading'],
		'description'      => ! empty( $content['description'] ) ? (string) $content['description'] : $defaults['description'],
		'included_heading' => ! empty( $content['included_heading'] ) ? (string) $content['included_heading'] : $defaults['included_heading'],
		'included_helper'  => ! empty( $content['included_helper'] ) ? (string) $content['included_helper'] : $defaults['included_helper'],
		'paid_heading'     => ! empty( $content['paid_heading'] ) ? (string) $content['paid_heading'] : $defaults['paid_heading'],
		'paid_helper'      => ! empty( $content['paid_helper'] ) ? (string) $content['paid_helper'] : $defaults['paid_helper'],
		'included'         => ! empty( $included ) ? $included : $defaults['included'],
		'paid'             => ! empty( $paid ) ? $paid : $defaults['paid'],
		'amenities'        => ! empty( $amenities ) ? $amenities : $defaults['amenities'],
	);
}

/**
 * Page-level booking CTA for interior templates.
 *
 * @return array<string, string>
 */
function seahivez_get_page_booking_cta() {
	return seahivez_map_acf_booking_cta( seahivez_get_home_location_data(), 'booking_cta' );
}
