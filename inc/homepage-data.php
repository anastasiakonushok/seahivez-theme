<?php
/**
 * Hardcoded homepage content.
 *
 * Structured for straightforward ACF migration in Phase 4.
 * Each getter returns an array consumed by homepage template parts.
 *
 * @package seahivez-theme
 */

/**
 * Resolve a theme image URI when the asset exists.
 *
 * @param string $relative_path Path relative to theme root.
 * @return string
 */
function seahivez_get_theme_image_uri( $relative_path ) {
	$path = get_theme_file_path( $relative_path );

	if ( ! file_exists( $path ) ) {
		return '';
	}

	return get_theme_file_uri( $relative_path );
}

/**
 * Hero section content.
 *
 * @return array<string, mixed>
 */
function seahivez_get_home_hero_data() {
	return array(
		'eyebrow'          => __( 'Numarine 55 Fly', 'seahivez-theme' ),
		'heading'          => __( 'Private yacht charter in Mallorca', 'seahivez-theme' ),
		'description'      => __( 'Discover the Balearic Islands from the sea — refined comfort, Mediterranean light, and unforgettable moments aboard a contemporary flybridge yacht.', 'seahivez-theme' ),
		'primary_label'    => __( 'Book your experience', 'seahivez-theme' ),
		'primary_url'      => seahivez_get_booking_url(),
		'secondary_label'  => __( 'Explore the yacht', 'seahivez-theme' ),
		'secondary_url'    => home_url( '/the-yacht/' ),
		'image'            => seahivez_get_theme_image_uri( 'assets/images/photo/2.jpg' ),
		'image_width'      => 1280,
		'image_height'     => 847,
		'image_alt'        => __( 'Numarine 55 Fly on the water in Mallorca', 'seahivez-theme' ),
	);
}

/**
 * Quick specification bar items.
 *
 * @return array<int, array<string, string>>
 */
function seahivez_get_home_quick_specs() {
	return array(
		array(
			'icon'  => 'location',
			'label' => __( 'Location', 'seahivez-theme' ),
			'value' => __( 'Mallorca', 'seahivez-theme' ),
		),
		array(
			'icon'  => 'guests',
			'label' => __( 'Guests', 'seahivez-theme' ),
			'value' => __( 'Up to 10', 'seahivez-theme' ),
		),
		array(
			'icon'  => 'cabins',
			'label' => __( 'Cabins', 'seahivez-theme' ),
			'value' => '3',
		),
		array(
			'icon'  => 'crew',
			'label' => __( 'Crew', 'seahivez-theme' ),
			'value' => __( 'Captain + Deckhand', 'seahivez-theme' ),
		),
		array(
			'icon'  => 'calendar',
			'label' => __( 'Refitted', 'seahivez-theme' ),
			'value' => '2024',
		),
		array(
			'icon'  => 'speed',
			'label' => __( 'Cruising Speed', 'seahivez-theme' ),
			'value' => __( '15 knots', 'seahivez-theme' ),
		),
	);
}

/**
 * About yacht section content.
 *
 * @return array<string, mixed>
 */
function seahivez_get_home_about_data() {
	return array(
		'eyebrow'     => __( 'About the yacht', 'seahivez-theme' ),
		'heading'     => __( 'Numarine 55 Fly', 'seahivez-theme' ),
		'paragraphs'  => array(
			__( 'A contemporary flybridge yacht designed for generous outdoor living, refined interiors, and effortless days along the Mallorca coastline.', 'seahivez-theme' ),
			__( 'From hidden coves near S\'Arenal to the open waters off Palma, SeaHivez offers a calm, premium charter experience with attentive crew and thoughtful details throughout.', 'seahivez-theme' ),
			__( 'Whether you choose a sunset escape or a full day at sea, every charter is tailored to your pace — unhurried, elegant, and unmistakably Mediterranean.', 'seahivez-theme' ),
		),
		'link_label'  => __( 'View full details', 'seahivez-theme' ),
		'link_url'    => home_url( '/the-yacht/' ),
		'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/3.jpg' ),
		'image_alt'   => __( 'Numarine 55 Fly anchored in a Mediterranean cove', 'seahivez-theme' ),
	);
}

/**
 * Specification groups for the homepage specifications section.
 *
 * Structured for future ACF repeater migration:
 * specification_groups → group_title, items → icon, label, value
 *
 * @return array<int, array<string, mixed>>
 */
function seahivez_get_home_specification_groups() {
	return array(
		array(
			'title'      => __( 'Yacht Characteristics', 'seahivez-theme' ),
			'grid_class' => 'grid grid-cols-2 gap-x-6 gap-y-8 sm:grid-cols-3 lg:grid-cols-6',
			'items'      => array(
				array( 'icon' => 'length', 'label' => __( 'Length', 'seahivez-theme' ), 'value' => '16.70 m' ),
				array( 'icon' => 'beam', 'label' => __( 'Beam', 'seahivez-theme' ), 'value' => '4.80 m' ),
				array( 'icon' => 'draft', 'label' => __( 'Draft', 'seahivez-theme' ), 'value' => '1.45 m' ),
				array( 'icon' => 'engines', 'label' => __( 'Engines', 'seahivez-theme' ), 'value' => '2 × 800 HP' ),
				array( 'icon' => 'speed', 'label' => __( 'Cruising Speed', 'seahivez-theme' ), 'value' => __( '15 knots', 'seahivez-theme' ) ),
				array( 'icon' => 'calendar', 'label' => __( 'Refitted', 'seahivez-theme' ), 'value' => '2024' ),
			),
		),
		array(
			'title'      => __( 'Capacity & Accommodation', 'seahivez-theme' ),
			'grid_class' => 'grid grid-cols-2 gap-x-6 gap-y-8 md:grid-cols-3',
			'items'      => array(
				array( 'icon' => 'guests', 'label' => __( 'Guests', 'seahivez-theme' ), 'value' => '10' ),
				array( 'icon' => 'cabins', 'label' => __( 'Cabins', 'seahivez-theme' ), 'value' => '3' ),
				array( 'icon' => 'bathrooms', 'label' => __( 'Bathrooms', 'seahivez-theme' ), 'value' => '3' ),
			),
		),
		array(
			'title'      => __( 'Crew & Service', 'seahivez-theme' ),
			'grid_class' => 'grid grid-cols-2 gap-x-6 gap-y-8 md:grid-cols-2',
			'items'      => array(
				array( 'icon' => 'crew', 'label' => __( 'Crew', 'seahivez-theme' ), 'value' => '2' ),
				array(
					'icon'       => 'languages',
					'label'      => __( 'Languages', 'seahivez-theme' ),
					'languages'  => array( 'en', 'es', 'de' ),
					'span_class' => 'col-span-2 md:col-span-1',
				),
			),
		),
	);
}

/**
 * Charter experience packages.
 *
 * @return array<int, array<string, string>>
 */
function seahivez_get_home_experiences() {
	return array(
		array(
			'duration'    => __( 'Sunset · 2 Hours', 'seahivez-theme' ),
			'time_slot'   => __( '18:00–20:00', 'seahivez-theme' ),
			'title'       => __( 'Sunset Charter', 'seahivez-theme' ),
			'price'       => '800',
			'description' => __( 'Golden hour along the Mallorca coast with refreshments and a relaxed cruise into the evening light.', 'seahivez-theme' ),
			'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/4.png' ),
			'url'         => seahivez_get_booking_url(),
		),
		array(
			'duration'    => __( 'Half Day · 4 Hours', 'seahivez-theme' ),
			'time_slot'   => __( '10:00–14:00 / 15:00–19:00', 'seahivez-theme' ),
			'title'       => __( 'Half Day Charter', 'seahivez-theme' ),
			'price'       => '1500',
			'description' => __( 'Swim stops, coastal cruising, and time to unwind on deck with space for dining and sun lounging.', 'seahivez-theme' ),
			'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/1.jpg' ),
			'url'         => seahivez_get_booking_url(),
		),
		array(
			'duration'    => __( 'Full Day · 8 Hours', 'seahivez-theme' ),
			'time_slot'   => __( '10:00–18:00', 'seahivez-theme' ),
			'title'       => __( 'Full Day Charter', 'seahivez-theme' ),
			'price'       => '2000',
			'description' => __( 'The complete SeaHivez experience — hidden coves, leisurely lunch, water toys, and an unhurried day at sea.', 'seahivez-theme' ),
			'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/3.jpg' ),
			'url'         => seahivez_get_booking_url(),
		),
	);
}

/**
 * Toys and extras grouped content.
 *
 * @return array<string, mixed>
 */
function seahivez_get_home_extras_data() {
	return array(
		'eyebrow'          => __( 'Toys & Extras', 'seahivez-theme' ),
		'heading'          => __( 'Everything for your day on the water', 'seahivez-theme' ),
		'description'      => __( 'From essential equipment to premium water toys, everything is prepared for a comfortable day at sea.', 'seahivez-theme' ),
		'included_heading' => __( 'Included', 'seahivez-theme' ),
		'included_helper'  => __( 'Already included in your charter', 'seahivez-theme' ),
		'paid_heading'     => __( 'Extra Paid', 'seahivez-theme' ),
		'paid_helper'      => __( 'Available on request', 'seahivez-theme' ),
		'included'         => array(
			array( 'icon' => 'snorkel', 'title' => __( 'Snorkel sets', 'seahivez-theme' ), 'included' => true ),
			array( 'icon' => 'paddle-board', 'title' => __( 'Paddle boards', 'seahivez-theme' ), 'included' => true ),
			array( 'icon' => 'flippers', 'title' => __( 'Flippers', 'seahivez-theme' ), 'included' => true ),
			array( 'icon' => 'towel', 'title' => __( 'Towels', 'seahivez-theme' ), 'included' => true ),
		),
		'paid'             => array(
			array( 'icon' => 'seabob', 'title' => __( 'SeaBob', 'seahivez-theme' ), 'price' => '400', 'included' => false ),
			array( 'icon' => 'jet-ski', 'title' => __( 'Jet Ski', 'seahivez-theme' ), 'price' => '500', 'included' => false ),
			array( 'icon' => 'efoil-air', 'title' => __( 'Efoil Air', 'seahivez-theme' ), 'price' => '500', 'included' => false ),
		),
		'amenities'        => array(
			__( 'Towel service included', 'seahivez-theme' ),
			__( 'Final cleaning included', 'seahivez-theme' ),
			__( 'Insurance and taxes included', 'seahivez-theme' ),
			__( 'Water included', 'seahivez-theme' ),
		),
	);
}

/**
 * Gallery section header content.
 *
 * @return array<string, string>
 */
function seahivez_get_home_gallery_header() {
	return array(
		'eyebrow'     => __( 'Gallery', 'seahivez-theme' ),
		'heading'     => __( 'Life on board', 'seahivez-theme' ),
		'description' => __( 'Discover the spaces, details and moments that define the SeaHivez experience.', 'seahivez-theme' ),
		'cta_label'   => __( 'View full gallery', 'seahivez-theme' ),
		'cta_url'     => home_url( '/gallery/' ),
	);
}

/**
 * Gallery items.
 *
 * Prefer WordPress attachment IDs when available.
 * Falls back to theme placeholder images until media is uploaded.
 *
 * @return array<int, array<string, mixed>>
 */
function seahivez_get_home_gallery_items() {
	$items = array(
		array(
			'path'          => 'assets/images/photo/2.jpg',
			'alt'           => __( 'Numarine 55 Fly exterior on the water', 'seahivez-theme' ),
			'caption'       => __( 'Numarine 55 Fly exterior on the water', 'seahivez-theme' ),
			'attachment_id' => 0,
		),
		array(
			'path'          => 'assets/images/photo/1.jpg',
			'alt'           => __( 'Aerial view of SeaHivez yacht with guests swimming', 'seahivez-theme' ),
			'caption'       => __( 'Aerial view of SeaHivez yacht with guests swimming', 'seahivez-theme' ),
			'attachment_id' => 0,
		),
		array(
			'path'          => 'assets/images/photo/3.jpg',
			'alt'           => __( 'Yacht anchored in a turquoise Mediterranean cove', 'seahivez-theme' ),
			'caption'       => __( 'Yacht anchored in a turquoise Mediterranean cove', 'seahivez-theme' ),
			'attachment_id' => 0,
		),
	);

	foreach ( $items as $index => $item ) {
		$resolved          = seahivez_resolve_gallery_item_images( $item );
		$items[ $index ] = array_merge( $item, $resolved );
	}

	return $items;
}

/**
 * Resolve gallery thumbnail, large, and full-size image URLs.
 *
 * Uses attachment sizes when an attachment ID is present:
 * - thumbnail strip: seahivez-gallery or medium
 * - main slider: large (fallback seahivez-hero)
 * - Fancybox href: full
 *
 * @param array<string, mixed> $item Gallery item data.
 * @return array{
 *     thumbnail: string,
 *     large: string,
 *     full: string,
 *     image: string,
 *     srcset: string,
 *     large_srcset: string,
 *     thumb_srcset: string,
 *     sizes: string,
 *     large_sizes: string,
 *     thumb_sizes: string,
 *     alt: string,
 *     caption: string
 * }
 */
function seahivez_resolve_gallery_item_images( $item ) {
	$attachment_id = isset( $item['attachment_id'] ) ? absint( $item['attachment_id'] ) : 0;
	$alt           = isset( $item['alt'] ) ? (string) $item['alt'] : '';
	$caption       = isset( $item['caption'] ) ? (string) $item['caption'] : $alt;

	if ( $attachment_id > 0 ) {
		$thumb = wp_get_attachment_image_url( $attachment_id, 'seahivez-gallery' );
		if ( ! $thumb ) {
			$thumb = wp_get_attachment_image_url( $attachment_id, 'medium' );
		}

		$large = wp_get_attachment_image_url( $attachment_id, 'large' );
		if ( ! $large ) {
			$large = wp_get_attachment_image_url( $attachment_id, 'seahivez-hero' );
		}
		if ( ! $large ) {
			$large = $thumb;
		}

		$full = wp_get_attachment_image_url( $attachment_id, 'full' );
		if ( ! $full ) {
			$full = $large;
		}

		$attachment_alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
		if ( $attachment_alt ) {
			$alt = $attachment_alt;
		}

		$attachment_caption = wp_get_attachment_caption( $attachment_id );
		if ( $attachment_caption ) {
			$caption = $attachment_caption;
		}

		return array(
			'thumbnail'    => $thumb ? $thumb : '',
			'large'        => $large ? $large : '',
			'full'         => $full ? $full : '',
			'image'        => $large ? $large : ( $thumb ? $thumb : '' ),
			'srcset'       => (string) wp_get_attachment_image_srcset( $attachment_id, 'large' ),
			'large_srcset' => (string) wp_get_attachment_image_srcset( $attachment_id, 'large' ),
			'thumb_srcset' => (string) wp_get_attachment_image_srcset( $attachment_id, 'seahivez-gallery' ),
			'sizes'        => '(max-width: 1024px) 100vw, 1200px',
			'large_sizes'  => '(max-width: 1024px) 100vw, 1200px',
			'thumb_sizes'  => '140px',
			'alt'          => $alt,
			'caption'      => $caption,
		);
	}

	$placeholder = '';
	if ( ! empty( $item['path'] ) ) {
		$placeholder = seahivez_get_theme_image_uri( $item['path'] );
	} elseif ( ! empty( $item['file'] ) ) {
		$placeholder = seahivez_get_theme_image_uri( 'assets/images/home/' . $item['file'] );
	}

	return array(
		'thumbnail'    => $placeholder,
		'large'        => $placeholder,
		'full'         => $placeholder,
		'image'        => $placeholder,
		'srcset'       => '',
		'large_srcset' => '',
		'thumb_srcset' => '',
		'sizes'        => '',
		'large_sizes'  => '',
		'thumb_sizes'  => '',
		'alt'          => $alt,
		'caption'      => $caption,
	);
}

/**
 * Homepage FAQ section header.
 *
 * @return array<string, string>
 */
function seahivez_get_home_faq_header() {
	return array(
		'eyebrow'      => __( 'FAQ', 'seahivez-theme' ),
		'heading'      => __( 'Frequently Asked Questions', 'seahivez-theme' ),
		'description'  => __( 'Everything you need to know before your day on the water.', 'seahivez-theme' ),
		'cta_heading'  => __( 'Still have a question?', 'seahivez-theme' ),
		'cta_label'    => __( 'Contact us', 'seahivez-theme' ),
		'contact_url'  => home_url( '/contact/' ),
	);
}

/**
 * Homepage FAQ items.
 *
 * Structured for a future ACF repeater or `faq` CPT.
 *
 * @return array<int, array{question: string, answer: string}>
 */
function seahivez_get_home_faq_items() {
	return array(
		array(
			'question' => __( 'Where does the yacht depart from?', 'seahivez-theme' ),
			'answer'   => __( "SeaHivez departs from S'Arenal, Mallorca. Exact meeting instructions are provided after your booking is confirmed.", 'seahivez-theme' ),
		),
		array(
			'question' => __( 'How many guests can join?', 'seahivez-theme' ),
			'answer'   => __( 'The yacht accommodates up to 10 guests, with a professional captain and deckhand on board.', 'seahivez-theme' ),
		),
		array(
			'question' => __( 'What is included in the charter price?', 'seahivez-theme' ),
			'answer'   => __( 'Towel service, final cleaning, insurance, taxes and water are included. Selected water toys may be available at an additional charge.', 'seahivez-theme' ),
		),
		array(
			'question' => __( 'Can we choose our route?', 'seahivez-theme' ),
			'answer'   => __( 'Yes. The route can be adapted to weather conditions, charter duration and your preferences.', 'seahivez-theme' ),
		),
		array(
			'question' => __( 'Can we bring food and drinks?', 'seahivez-theme' ),
			'answer'   => __( 'Yes. Guests may bring their own food and drinks, and additional catering options can be discussed before departure.', 'seahivez-theme' ),
		),
		array(
			'question' => __( 'What happens if the weather is bad?', 'seahivez-theme' ),
			'answer'   => __( 'Safety always comes first. If conditions are unsuitable, the team will contact you to discuss available alternatives or rescheduling.', 'seahivez-theme' ),
		),
		array(
			'question' => __( 'Are children allowed on board?', 'seahivez-theme' ),
			'answer'   => __( "Yes. Families are welcome. Please provide children's ages when booking so appropriate safety arrangements can be prepared.", 'seahivez-theme' ),
		),
		array(
			'question' => __( 'How far in advance should we book?', 'seahivez-theme' ),
			'answer'   => __( 'During high season, booking as early as possible is recommended, especially for weekends and sunset charters.', 'seahivez-theme' ),
		),
	);
}

/**
 * News section header content.
 *
 * @return array<string, string>
 */
function seahivez_get_home_news_header() {
	return array(
		'eyebrow'     => __( 'News & Inspiration', 'seahivez-theme' ),
		'heading'     => __( 'Stories from Mallorca', 'seahivez-theme' ),
		'description' => __( 'Discover local places, charter inspiration, hidden coves and life on the Mediterranean.', 'seahivez-theme' ),
		'cta_label'   => __( 'View all news', 'seahivez-theme' ),
		'cta_url'     => seahivez_get_posts_page_url(),
	);
}

/**
 * URL for the WordPress posts index / News archive.
 *
 * Uses the configured Posts page when set (Settings → Reading).
 * Falls back to /news/ so the public URL stays stable before configuration.
 *
 * @return string
 */
function seahivez_get_posts_page_url() {
	$page_for_posts = (int) get_option( 'page_for_posts' );

	if ( $page_for_posts > 0 ) {
		return get_permalink( $page_for_posts );
	}

	return home_url( '/news/' );
}

/**
 * Location and booking CTA content.
 *
 * @return array<string, mixed>
 */
function seahivez_get_home_location_data() {
	return array(
		'heading'     => __( 'Ready to book your experience?', 'seahivez-theme' ),
		'description' => __( 'Tell us your preferred date and charter style — our team will confirm availability and help plan a seamless day on the water.', 'seahivez-theme' ),
		'cta_label'   => __( 'Book now', 'seahivez-theme' ),
		'cta_url'     => seahivez_get_booking_url(),
		'location'    => __( "Mallorca / S'Arenal", 'seahivez-theme' ),
		'phone'       => '+34 000 000 000',
		'email'       => 'info@seahivez.com',
	);
}
