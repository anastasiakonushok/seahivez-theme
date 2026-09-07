<?php
/**
 * Charter package custom post type and data helpers.
 *
 * @package seahivez-theme
 */

/**
 * Package post type slug.
 *
 * @return string
 */
function seahivez_get_package_post_type() {
	return 'package';
}

/**
 * Register the package post type.
 */
function seahivez_register_package_post_type() {
	$labels = array(
		'name'                  => __( 'Packages', 'seahivez-theme' ),
		'singular_name'         => __( 'Package', 'seahivez-theme' ),
		'menu_name'             => __( 'Packages', 'seahivez-theme' ),
		'name_admin_bar'        => __( 'Package', 'seahivez-theme' ),
		'add_new'               => __( 'Add New', 'seahivez-theme' ),
		'add_new_item'          => __( 'Add New Package', 'seahivez-theme' ),
		'new_item'              => __( 'New Package', 'seahivez-theme' ),
		'edit_item'             => __( 'Edit Package', 'seahivez-theme' ),
		'view_item'             => __( 'View Package', 'seahivez-theme' ),
		'all_items'             => __( 'All Packages', 'seahivez-theme' ),
		'search_items'          => __( 'Search Packages', 'seahivez-theme' ),
		'not_found'             => __( 'No packages found.', 'seahivez-theme' ),
		'not_found_in_trash'    => __( 'No packages found in Trash.', 'seahivez-theme' ),
		'featured_image'        => __( 'Hero image', 'seahivez-theme' ),
		'set_featured_image'    => __( 'Set hero image', 'seahivez-theme' ),
		'remove_featured_image' => __( 'Remove hero image', 'seahivez-theme' ),
		'use_featured_image'    => __( 'Use as hero image', 'seahivez-theme' ),
	);

	register_post_type(
		seahivez_get_package_post_type(),
		array(
			'labels'              => $labels,
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'has_archive'         => false,
			'rewrite'             => array(
				'slug'       => 'packages',
				'with_front' => false,
			),
			'menu_position'       => 21,
			'menu_icon'           => 'dashicons-palmtree',
			'capability_type'     => 'post',
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
		)
	);
}
add_action( 'init', 'seahivez_register_package_post_type' );

/**
 * Seed default packages once CPT is available.
 */
function seahivez_packages_bootstrap() {
	seahivez_maybe_seed_default_packages();
}
add_action( 'init', 'seahivez_packages_bootstrap', 20 );

/**
 * Flush rewrite rules when the theme is activated.
 */
function seahivez_package_theme_activation() {
	seahivez_register_package_post_type();
	flush_rewrite_rules();
	seahivez_maybe_seed_default_packages();
}
add_action( 'after_switch_theme', 'seahivez_package_theme_activation' );

/**
 * Create starter packages from theme defaults when none exist.
 */
function seahivez_maybe_seed_default_packages() {
	if ( ! function_exists( 'update_field' ) ) {
		return;
	}

	$existing = get_posts(
		array(
			'post_type'      => seahivez_get_package_post_type(),
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	if ( ! empty( $existing ) ) {
		return;
	}

	$defaults = seahivez_get_home_experiences();

	foreach ( $defaults as $index => $package ) {
		if ( empty( $package['title'] ) ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => seahivez_get_package_post_type(),
				'post_status'  => 'publish',
				'post_title'   => $package['title'],
				'post_content' => $package['description'],
				'post_excerpt' => $package['description'],
				'menu_order'   => $index,
			),
			true
		);

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		update_field( 'duration', $package['duration'], $post_id );
		update_field( 'time_slot', $package['time_slot'], $post_id );
		update_field( 'price', $package['price'], $post_id );
		update_field( 'short_description', $package['description'], $post_id );

		if ( ! empty( $package['image'] ) ) {
			update_post_meta( $post_id, '_seahivez_card_image_url', esc_url_raw( $package['image'] ) );
		}
	}
}

/**
 * Normalize relationship / ID input into package post objects.
 *
 * @param mixed $selected ACF relationship value.
 * @return array<int, WP_Post>
 */
function seahivez_normalize_package_posts( $selected ) {
	$posts = array();

	if ( empty( $selected ) || ! is_array( $selected ) ) {
		return $posts;
	}

	foreach ( $selected as $item ) {
		if ( $item instanceof WP_Post && seahivez_get_package_post_type() === $item->post_type ) {
			$posts[] = $item;
			continue;
		}

		if ( is_numeric( $item ) ) {
			$post = get_post( (int) $item );

			if ( $post && seahivez_get_package_post_type() === $post->post_type ) {
				$posts[] = $post;
			}
		}
	}

	return $posts;
}

/**
 * Query all published packages in menu order.
 *
 * @return array<int, WP_Post>
 */
function seahivez_query_all_package_posts() {
	$query = new WP_Query(
		array(
			'post_type'      => seahivez_get_package_post_type(),
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		)
	);

	return ! empty( $query->posts ) ? $query->posts : array();
}

/**
 * Card data for a single package.
 *
 * @param int $post_id Package post ID.
 * @return array<string, string>
 */
function seahivez_get_package_card_data( $post_id ) {
	$post_id = (int) $post_id;

	$booking = seahivez_parse_acf_link(
		function_exists( 'get_field' ) ? get_field( 'booking_link', $post_id ) : null,
		array(
			'label' => '',
			'url'   => seahivez_get_booking_url(),
		)
	);

	$card_image = function_exists( 'get_field' ) ? get_field( 'card_image', $post_id ) : null;
	$image      = seahivez_get_acf_image_url( $card_image, 'seahivez-card', '' );

	if ( ! $image ) {
		$image = get_the_post_thumbnail_url( $post_id, 'seahivez-card' );
	}

	if ( ! $image ) {
		$fallback = get_post_meta( $post_id, '_seahivez_card_image_url', true );
		$image    = is_string( $fallback ) ? $fallback : '';
	}

	$short_description = function_exists( 'get_field' ) ? (string) get_field( 'short_description', $post_id ) : '';

	if ( '' === $short_description ) {
		$short_description = get_the_excerpt( $post_id );
	}

	return array(
		'id'          => (string) $post_id,
		'title'       => get_the_title( $post_id ),
		'duration'    => function_exists( 'get_field' ) ? (string) get_field( 'duration', $post_id ) : '',
		'time_slot'   => function_exists( 'get_field' ) ? (string) get_field( 'time_slot', $post_id ) : '',
		'price'       => function_exists( 'get_field' ) ? (string) get_field( 'price', $post_id ) : '',
		'description' => $short_description,
		'image'       => $image,
		'url'         => get_permalink( $post_id ),
		'booking_url' => $booking['url'],
	);
}

/**
 * Map package posts to card arrays.
 *
 * @param array<int, WP_Post> $posts Package posts.
 * @return array<int, array<string, string>>
 */
function seahivez_map_package_posts_to_cards( $posts ) {
	$cards = array();

	foreach ( $posts as $post ) {
		if ( ! $post instanceof WP_Post ) {
			continue;
		}

		$cards[] = seahivez_get_package_card_data( $post->ID );
	}

	return $cards;
}

/**
 * Resolve packages for homepage / experiences sections.
 *
 * @param mixed $selected Optional ACF relationship selection.
 * @return array<int, array<string, string>>
 */
function seahivez_get_packages_for_display( $selected = null ) {
	$defaults = seahivez_get_home_experiences();

	if ( ! post_type_exists( seahivez_get_package_post_type() ) ) {
		return $defaults;
	}

	$posts = seahivez_normalize_package_posts( $selected );

	if ( empty( $posts ) ) {
		$posts = seahivez_query_all_package_posts();
	}

	if ( empty( $posts ) ) {
		return $defaults;
	}

	return seahivez_map_package_posts_to_cards( $posts );
}

/**
 * Hero data for a package single page.
 *
 * @param int|null $post_id Package post ID.
 * @return array<string, mixed>
 */
function seahivez_get_package_hero_data( $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	$card      = seahivez_get_package_card_data( $post_id );
	$hero_text = function_exists( 'get_field' ) ? (string) get_field( 'hero_description', $post_id ) : '';

	if ( '' === $hero_text ) {
		$hero_text = $card['description'];
	}

	return seahivez_get_page_hero_defaults(
		array(
			'eyebrow'     => $card['duration'],
			'heading'     => $card['title'],
			'description' => $hero_text,
			'image'       => $card['image'],
			'image_alt'   => $card['title'],
			'compact'     => true,
			'overlay'     => true,
		)
	);
}

/**
 * Included items for a package (package override or shared defaults).
 *
 * @param int|null $post_id Package post ID.
 * @return array<int, string>
 */
function seahivez_get_package_included_items( $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();
	$items   = array();

	if ( function_exists( 'have_rows' ) && have_rows( 'included_items', $post_id ) ) {
		while ( have_rows( 'included_items', $post_id ) ) {
			the_row();

			$text = (string) get_sub_field( 'text' );

			if ( '' !== $text ) {
				$items[] = $text;
			}
		}
	}

	if ( ! empty( $items ) ) {
		return $items;
	}

	return seahivez_get_experience_included_items();
}

/**
 * Other packages for cross-linking on single pages.
 *
 * @param int $current_id Current package ID.
 * @return array<int, array<string, string>>
 */
function seahivez_get_other_package_cards( $current_id ) {
	$posts = seahivez_query_all_package_posts();
	$cards = array();

	foreach ( $posts as $post ) {
		if ( (int) $post->ID === (int) $current_id ) {
			continue;
		}

		$cards[] = seahivez_get_package_card_data( $post->ID );
	}

	return $cards;
}
