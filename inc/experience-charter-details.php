<?php
/**
 * Charter route / included / not-included content for homepage experience cards.
 *
 * @package seahivez-theme
 */

/**
 * Shared included equipment labels (all charters).
 *
 * @return array{snorkel: string, paddle_boards: string}
 */
function seahivez_get_charter_included_equipment_labels() {
	return array(
		'snorkel'       => __( '6 snorkel sets', 'seahivez-theme' ),
		'paddle_boards' => __( '2 paddle boards', 'seahivez-theme' ),
	);
}

/**
 * Detect package key from card title.
 *
 * @param array<string, mixed> $card Experience card data.
 * @return string sunset|half-day|full-day
 */
function seahivez_get_experience_package_key( $card ) {
	$title = strtolower( (string) ( $card['title'] ?? '' ) );

	if ( str_contains( $title, 'sunset' ) ) {
		return 'sunset';
	}

	if ( str_contains( $title, 'half' ) ) {
		return 'half-day';
	}

	if ( str_contains( $title, 'full' ) ) {
		return 'full-day';
	}

	return '';
}

/**
 * Editorial charter details for a homepage experience card.
 *
 * @param string $package_key Package identifier.
 * @return array<string, mixed>|null
 */
function seahivez_get_experience_charter_details( $package_key ) {
	$equipment = seahivez_get_charter_included_equipment_labels();

	$packages = array(
		'sunset'   => array(
			'type'            => 'sunset',
			'sections'        => array(
				array(
					'label' => __( 'Sunset experience', 'seahivez-theme' ),
					'text'  => __( 'A relaxed evening cruise along the Mallorca coast.', 'seahivez-theme' ),
				),
			),
			'included'        => array(
				__( 'Captain', 'seahivez-theme' ),
				__( 'Fuel', 'seahivez-theme' ),
				$equipment['snorkel'],
				$equipment['paddle_boards'],
				__( 'Taxes / insurance / final cleaning', 'seahivez-theme' ),
			),
			'included_layout' => 'inline',
		),
		'half-day' => array(
			'type'            => 'half-day',
			'routes'          => array(
				array(
					'number'     => '01',
					'name'       => __( 'East Coast', 'seahivez-theme' ),
					'path'       => __( "S'Arenal → Cala de Reina → Cala Vela → Es Rocal (fish reserve) → S'Arenal", 'seahivez-theme' ),
					'note'       => '',
					'fuel_label' => __( 'Fuel', 'seahivez-theme' ),
					'fuel_cost'  => '€400',
				),
			),
			'included'        => array(
				$equipment['snorkel'],
				$equipment['paddle_boards'],
				__( 'Towels', 'seahivez-theme' ),
				__( 'Taxes / insurance / cleaning', 'seahivez-theme' ),
			),
			'included_layout' => 'grid',
			'not_included'    => array(
				array(
					'label' => __( 'Fuel', 'seahivez-theme' ),
					'cost'  => '€400',
				),
				array(
					'label' => __( 'Captain', 'seahivez-theme' ),
					'cost'  => '€300',
				),
				array(
					'label' => __( 'Deckhand', 'seahivez-theme' ),
					'cost'  => '€100',
				),
				array(
					'label' => __( 'Security deposit', 'seahivez-theme' ),
					'cost'  => '€300',
				),
			),
		),
		'full-day' => array(
			'type'            => 'full-day',
			'routes_label'    => __( 'Choose your route', 'seahivez-theme' ),
			'routes'          => array(
				array(
					'number'     => '01',
					'name'       => __( 'East Coast', 'seahivez-theme' ),
					'path'       => __( "S'Arenal → Cala de Reina → Cala Vela → Es Rocal (fish reserve) → S'Arenal", 'seahivez-theme' ),
					'fuel_label' => __( 'Fuel', 'seahivez-theme' ),
					'fuel_cost'  => '€400',
				),
				array(
					'number'     => '02',
					'name'       => __( 'Palma Coast', 'seahivez-theme' ),
					'path'       => __( 'Palma → Siso → Portals Vells', 'seahivez-theme' ),
					'fuel_label' => __( 'Fuel', 'seahivez-theme' ),
					'fuel_cost'  => '€600',
				),
				array(
					'number'     => '03',
					'name'       => __( 'Best of Both', 'seahivez-theme' ),
					'path'       => __( 'East Coast → Siso → Portals Vells', 'seahivez-theme' ),
					'fuel_label' => __( 'Fuel', 'seahivez-theme' ),
					'fuel_cost'  => '€800',
				),
			),
			'included'        => array(
				$equipment['snorkel'],
				$equipment['paddle_boards'],
				__( 'Towels', 'seahivez-theme' ),
				__( 'Taxes / insurance / cleaning', 'seahivez-theme' ),
			),
			'included_layout' => 'grid',
			'not_included'    => array(
				array(
					'label' => __( 'Fuel', 'seahivez-theme' ),
					'cost'  => '€400',
				),
				array(
					'label' => __( 'Captain', 'seahivez-theme' ),
					'cost'  => '€300',
				),
				array(
					'label' => __( 'Deckhand', 'seahivez-theme' ),
					'cost'  => '€100',
				),
				array(
					'label' => __( 'Security deposit', 'seahivez-theme' ),
					'cost'  => '€300',
				),
			),
		),
	);

	return $packages[ $package_key ] ?? null;
}

/**
 * Convert charter details array to ACF group value.
 *
 * @param array<string, mixed> $details Charter details payload.
 * @return array<string, mixed>
 */
function seahivez_charter_details_to_acf_value( $details ) {
	$acf = array(
		'type'             => (string) ( $details['type'] ?? '' ),
		'routes_label'     => (string) ( $details['routes_label'] ?? '' ),
		'included_layout'  => (string) ( $details['included_layout'] ?? 'grid' ),
		'sections'         => array(),
		'routes'           => array(),
		'included'         => array(),
		'not_included'     => array(),
	);

	if ( ! empty( $details['sections'] ) && is_array( $details['sections'] ) ) {
		foreach ( $details['sections'] as $section ) {
			$acf['sections'][] = array(
				'label' => (string) ( $section['label'] ?? '' ),
				'text'  => (string) ( $section['text'] ?? '' ),
			);
		}
	}

	if ( ! empty( $details['routes'] ) && is_array( $details['routes'] ) ) {
		foreach ( $details['routes'] as $route ) {
			$acf['routes'][] = array(
				'number'     => (string) ( $route['number'] ?? '' ),
				'name'       => (string) ( $route['name'] ?? '' ),
				'path'       => (string) ( $route['path'] ?? '' ),
				'note'       => (string) ( $route['note'] ?? '' ),
				'fuel_label' => (string) ( $route['fuel_label'] ?? '' ),
				'fuel_cost'  => (string) ( $route['fuel_cost'] ?? '' ),
			);
		}
	}

	if ( ! empty( $details['included'] ) && is_array( $details['included'] ) ) {
		foreach ( $details['included'] as $item ) {
			if ( '' !== (string) $item ) {
				$acf['included'][] = array(
					'text' => (string) $item,
				);
			}
		}
	}

	if ( ! empty( $details['not_included'] ) && is_array( $details['not_included'] ) ) {
		foreach ( $details['not_included'] as $row ) {
			$acf['not_included'][] = array(
				'label' => (string) ( $row['label'] ?? '' ),
				'cost'  => (string) ( $row['cost'] ?? '' ),
			);
		}
	}

	return $acf;
}

/**
 * Map ACF charter_details group to card payload.
 *
 * @param array<string, mixed>|null $raw ACF group value.
 * @return array<string, mixed>|null
 */
function seahivez_map_acf_charter_details( $raw ) {
	if ( empty( $raw ) || ! is_array( $raw ) ) {
		return null;
	}

	$type = ! empty( $raw['type'] ) ? sanitize_key( (string) $raw['type'] ) : '';

	if ( '' === $type ) {
		return null;
	}

	$details = array(
		'type' => $type,
	);

	if ( ! empty( $raw['sections'] ) && is_array( $raw['sections'] ) ) {
		$sections = array();

		foreach ( $raw['sections'] as $section ) {
			if ( ! is_array( $section ) ) {
				continue;
			}

			$label = (string) ( $section['label'] ?? '' );
			$text  = (string) ( $section['text'] ?? '' );

			if ( '' === $label && '' === $text ) {
				continue;
			}

			$sections[] = array(
				'label' => $label,
				'text'  => $text,
			);
		}

		if ( ! empty( $sections ) ) {
			$details['sections'] = $sections;
		}
	}

	if ( ! empty( $raw['routes_label'] ) ) {
		$details['routes_label'] = (string) $raw['routes_label'];
	}

	if ( ! empty( $raw['routes'] ) && is_array( $raw['routes'] ) ) {
		$routes = array();

		foreach ( $raw['routes'] as $route ) {
			if ( ! is_array( $route ) ) {
				continue;
			}

			$name = (string) ( $route['name'] ?? '' );
			$path = (string) ( $route['path'] ?? '' );

			if ( '' === $name && '' === $path ) {
				continue;
			}

			$routes[] = array(
				'number'     => (string) ( $route['number'] ?? '' ),
				'name'       => $name,
				'path'       => $path,
				'note'       => (string) ( $route['note'] ?? '' ),
				'fuel_label' => (string) ( $route['fuel_label'] ?? '' ),
				'fuel_cost'  => (string) ( $route['fuel_cost'] ?? '' ),
			);
		}

		if ( ! empty( $routes ) ) {
			$details['routes'] = $routes;
		}
	}

	$layout = ! empty( $raw['included_layout'] ) ? sanitize_key( (string) $raw['included_layout'] ) : 'grid';
	$details['included_layout'] = in_array( $layout, array( 'inline', 'grid' ), true ) ? $layout : 'grid';

	if ( ! empty( $raw['included'] ) && is_array( $raw['included'] ) ) {
		$included = array();

		foreach ( $raw['included'] as $row ) {
			$text = is_array( $row ) ? (string) ( $row['text'] ?? '' ) : (string) $row;

			if ( '' !== $text ) {
				$included[] = $text;
			}
		}

		if ( ! empty( $included ) ) {
			$details['included'] = $included;
		}
	}

	if ( ! empty( $raw['not_included'] ) && is_array( $raw['not_included'] ) ) {
		$not_included = array();

		foreach ( $raw['not_included'] as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}

			$label = (string) ( $row['label'] ?? '' );

			if ( '' === $label ) {
				continue;
			}

			$not_included[] = array(
				'label' => $label,
				'cost'  => (string) ( $row['cost'] ?? '' ),
			);
		}

		if ( ! empty( $not_included ) ) {
			$details['not_included'] = $not_included;
		}
	}

	return $details;
}

/**
 * Charter details for a package post (ACF with theme fallback).
 *
 * @param int $post_id Package post ID.
 * @return array<string, mixed>|null
 */
function seahivez_get_package_charter_details( $post_id ) {
	$post_id = (int) $post_id;

	if ( ! $post_id ) {
		return null;
	}

	if ( function_exists( 'get_field' ) ) {
		$mapped = seahivez_map_acf_charter_details( get_field( 'charter_details', $post_id ) );

		if ( $mapped ) {
			return $mapped;
		}
	}

	$key = seahivez_get_experience_package_key(
		array(
			'title' => get_the_title( $post_id ),
		)
	);

	return $key ? seahivez_get_experience_charter_details( $key ) : null;
}

/**
 * Attach homepage charter details to experience cards.
 *
 * @param array<int, array<string, mixed>> $cards Experience cards.
 * @return array<int, array<string, mixed>>
 */
function seahivez_attach_home_experience_charter_details( $cards ) {
	foreach ( $cards as $index => $card ) {
		if ( ! empty( $card['id'] ) ) {
			$details = seahivez_get_package_charter_details( (int) $card['id'] );

			if ( $details ) {
				$cards[ $index ]['charter_details'] = $details;
				continue;
			}
		}

		$key = seahivez_get_experience_package_key( $card );

		if ( $key ) {
			$cards[ $index ]['charter_details'] = seahivez_get_experience_charter_details( $key );
		}
	}

	return $cards;
}
