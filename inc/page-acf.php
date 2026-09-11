<?php
/**
 * ACF field mappers for interior page templates.
 *
 * @package seahivez-theme
 */

/**
 * Map ACF page hero group to hero args array.
 *
 * @param array<string, mixed> $defaults Default hero data.
 * @param string               $field    ACF field name.
 * @param int|null             $post_id  Optional post ID (e.g. Posts page).
 * @return array<string, mixed>
 */
function seahivez_map_acf_page_hero( $defaults, $field = 'hero', $post_id = null ) {
	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$hero = get_field( $field, $post_id );

	if ( empty( $hero ) || ! is_array( $hero ) ) {
		return $defaults;
	}

	$image = $hero['image'] ?? null;

	$mapped = array(
		'eyebrow'     => ! empty( $hero['eyebrow'] ) ? (string) $hero['eyebrow'] : $defaults['eyebrow'],
		'heading'     => ! empty( $hero['heading'] ) ? (string) $hero['heading'] : $defaults['heading'],
		'description' => ! empty( $hero['description'] ) ? (string) $hero['description'] : $defaults['description'],
		'image'       => seahivez_get_acf_image_url( $image, 'seahivez-hero', $defaults['image'] ),
		'image_alt'   => ! empty( $hero['image_alt'] ) ? (string) $hero['image_alt'] : ( ! empty( $defaults['image_alt'] ) ? $defaults['image_alt'] : '' ),
		'overlay'     => isset( $defaults['overlay'] ) ? (bool) $defaults['overlay'] : true,
		'compact'     => ! empty( $hero['compact'] ),
	);

	return array_merge( $defaults, $mapped );
}

/**
 * Map ACF location / booking CTA group.
 *
 * @param array<string, string> $defaults Defaults.
 * @param string                $field    Field name.
 * @return array<string, string>
 */
function seahivez_map_acf_booking_cta( $defaults, $field = 'booking_cta' ) {
	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$data = get_field( $field );

	if ( empty( $data ) || ! is_array( $data ) ) {
		return $defaults;
	}

	$cta = seahivez_parse_acf_link(
		$data['cta_button'] ?? null,
		array(
			'label' => $defaults['cta_label'] ?? '',
			'url'   => $defaults['cta_url'] ?? '',
		)
	);

	return array(
		'heading'     => ! empty( $data['heading'] ) ? (string) $data['heading'] : ( $defaults['heading'] ?? '' ),
		'description' => ! empty( $data['description'] ) ? (string) $data['description'] : ( $defaults['description'] ?? '' ),
		'cta_label'   => $cta['label'],
		'cta_url'     => $cta['url'],
		'location'    => $defaults['location'] ?? '',
		'phone'       => $defaults['phone'] ?? '',
		'email'       => $defaults['email'] ?? '',
	);
}

/**
 * Map a single ACF specification row.
 *
 * @param array<string, mixed> $item ACF repeater row.
 * @return array<string, mixed>
 */
function seahivez_map_acf_spec_item_row( $item ) {
	$languages = array();

	if ( ! empty( $item['languages'] ) && is_array( $item['languages'] ) ) {
		$languages = array_values(
			array_filter(
				array_map( 'sanitize_key', $item['languages'] )
			)
		);
	}

	return array(
		'icon'      => seahivez_normalize_acf_icon( $item['icon'] ?? '' ),
		'label'     => (string) ( $item['label'] ?? '' ),
		'value'     => empty( $languages ) ? (string) ( $item['value'] ?? '' ) : '',
		'languages' => $languages,
	);
}

/**
 * Map ACF specification groups repeater.
 *
 * @param array<int, array<string, mixed>> $defaults Default groups.
 * @param string                            $field    Field name.
 * @return array<int, array<string, mixed>>
 */
function seahivez_map_acf_specification_groups( $defaults, $field = 'specification_groups' ) {
	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$groups = get_field( $field );

	if ( empty( $groups ) || ! is_array( $groups ) ) {
		return $defaults;
	}

	return seahivez_map_acf_specification_groups_from_rows( $groups, $defaults );
}

/**
 * Map specification group rows from a repeater array.
 *
 * @param array<int, array<string, mixed>> $rows     Repeater rows.
 * @param array<int, array<string, mixed>> $defaults Default groups for grid classes.
 * @return array<int, array<string, mixed>>
 */
function seahivez_map_acf_specification_groups_from_rows( $rows, $defaults ) {
	$mapped = array();

	foreach ( $rows as $group_index => $group ) {
		if ( empty( $group['items'] ) || ! is_array( $group['items'] ) ) {
			continue;
		}

		$items = array();

		foreach ( $group['items'] as $item ) {
			$items[] = seahivez_map_acf_spec_item_row( $item );
		}

		if ( empty( $items ) ) {
			continue;
		}

		$mapped[] = array(
			'title'      => (string) ( $group['title'] ?? '' ),
			'grid_class' => ! empty( $defaults[ $group_index ]['grid_class'] )
				? $defaults[ $group_index ]['grid_class']
				: 'grid grid-cols-2 gap-x-6 gap-y-8 md:grid-cols-3',
			'items'      => $items,
		);
	}

	return $mapped;
}

/**
 * Map experience packages from ACF relationship or fall back to all packages.
 *
 * @param array<int, array<string, string>> $defaults Defaults.
 * @param string                            $field    Field name.
 * @return array<int, array<string, string>>
 */
function seahivez_map_acf_experience_packages( $defaults, $field = 'packages' ) {
	if ( ! seahivez_acf_is_active() ) {
		return seahivez_get_packages_for_display();
	}

	$selected = get_field( $field );
	$packages = seahivez_get_packages_for_display( $selected );

	return ! empty( $packages ) ? $packages : $defaults;
}

/**
 * Map gallery field to resolved gallery items.
 *
 * @param array<int, array<string, mixed>> $defaults Defaults.
 * @param string                            $field    Field name.
 * @return array<int, array<string, mixed>>
 */
function seahivez_map_acf_gallery_items( $defaults, $field = 'gallery_images' ) {
	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$images = get_field( $field );

	if ( empty( $images ) || ! is_array( $images ) ) {
		return $defaults;
	}

	$items = array();

	foreach ( $images as $image ) {
		$raw = array(
			'attachment_id' => is_array( $image ) && ! empty( $image['ID'] ) ? (int) $image['ID'] : 0,
			'path'          => '',
			'alt'           => is_array( $image ) && ! empty( $image['alt'] ) ? (string) $image['alt'] : '',
			'caption'       => is_array( $image ) && ! empty( $image['caption'] ) ? (string) $image['caption'] : '',
		);

		$items[] = array_merge( $raw, seahivez_resolve_gallery_item_images( $raw ) );
	}

	return ! empty( $items ) ? $items : $defaults;
}

/**
 * Map a raw ACF gallery image array to resolved items.
 *
 * @param array<int, mixed>|null           $images   Gallery images.
 * @param array<int, array<string, mixed>> $defaults Fallback items.
 * @return array<int, array<string, mixed>>
 */
function seahivez_map_acf_gallery_image_array( $images, $defaults = array() ) {
	if ( empty( $images ) || ! is_array( $images ) ) {
		return $defaults;
	}

	$items = array();

	foreach ( $images as $image ) {
		$raw = array(
			'attachment_id' => is_array( $image ) && ! empty( $image['ID'] ) ? (int) $image['ID'] : 0,
			'path'          => '',
			'alt'           => is_array( $image ) && ! empty( $image['alt'] ) ? (string) $image['alt'] : '',
			'caption'       => is_array( $image ) && ! empty( $image['caption'] ) ? (string) $image['caption'] : '',
		);

		$items[] = array_merge( $raw, seahivez_resolve_gallery_item_images( $raw ) );
	}

	return ! empty( $items ) ? $items : $defaults;
}

/**
 * Map FAQ groups repeater for FAQ page.
 *
 * @param array<int, array<string, mixed>> $defaults Defaults.
 * @param string                            $field    Field name.
 * @return array<int, array<string, mixed>>
 */
function seahivez_map_acf_faq_groups( $defaults, $field = 'faq_groups' ) {
	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$groups = get_field( $field );

	if ( empty( $groups ) || ! is_array( $groups ) ) {
		return $defaults;
	}

	$mapped = array();

	foreach ( $groups as $group ) {
		$items = array();

		if ( ! empty( $group['items'] ) && is_array( $group['items'] ) ) {
			foreach ( $group['items'] as $item ) {
				if ( empty( $item['question'] ) ) {
					continue;
				}

				$items[] = array(
					'question' => (string) $item['question'],
					'answer'   => (string) ( $item['answer'] ?? '' ),
				);
			}
		}

		if ( empty( $items ) ) {
			continue;
		}

		$mapped[] = array(
			'title' => (string) ( $group['title'] ?? '' ),
			'items' => $items,
		);
	}

	return ! empty( $mapped ) ? $mapped : $defaults;
}

/**
 * Map ACF included equipment rows for the Extras page.
 *
 * @param array<int, array<string, mixed>> $defaults Default equipment rows.
 * @param array<int, array<string, mixed>> $rows     ACF repeater rows.
 * @return array<int, array<string, mixed>>
 */
function seahivez_map_acf_extras_included_equipment( $defaults, $rows ) {
	if ( empty( $rows ) || ! is_array( $rows ) ) {
		return $defaults;
	}

	$defaults_by_title = array();

	foreach ( $defaults as $item ) {
		$title = strtolower( trim( (string) ( $item['title'] ?? '' ) ) );

		if ( $title ) {
			$defaults_by_title[ $title ] = $item;
		}
	}

	$mapped = array();

	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}

		$title    = (string) ( $row['title'] ?? '' );
		$fallback = $defaults_by_title[ strtolower( trim( $title ) ) ] ?? null;

		if ( '' === $title && empty( $fallback ) ) {
			continue;
		}

		$mapped[] = array(
			'icon'        => seahivez_normalize_acf_icon( $row['icon'] ?? '' ) ?: (string) ( $fallback['icon'] ?? '' ),
			'title'       => $title ? $title : (string) ( $fallback['title'] ?? '' ),
			'status'      => ! empty( $row['status'] )
				? (string) $row['status']
				: (string) ( $fallback['status'] ?? __( 'Included', 'seahivez-theme' ) ),
			'description' => isset( $row['description'] ) && (string) $row['description'] !== ''
				? (string) $row['description']
				: (string) ( $fallback['description'] ?? '' ),
		);
	}

	return ! empty( $mapped ) ? $mapped : $defaults;
}

/**
 * Map ACF included service rows for the Extras page.
 *
 * @param array<int, array<string, string>> $defaults Default service rows.
 * @param array<int, array<string, mixed>>  $rows     ACF repeater rows.
 * @return array<int, array<string, string>>
 */
function seahivez_map_acf_extras_included_services( $defaults, $rows ) {
	if ( empty( $rows ) || ! is_array( $rows ) ) {
		return $defaults;
	}

	$mapped = array();

	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}

		$label = (string) ( $row['label'] ?? '' );

		if ( '' === $label ) {
			continue;
		}

		$mapped[] = array(
			'icon'  => seahivez_normalize_acf_icon( $row['icon'] ?? '' ),
			'label' => $label,
		);
	}

	return ! empty( $mapped ) ? $mapped : $defaults;
}

/**
 * Map ACF paid extras rows for the Extras page.
 *
 * @param array<int, array<string, mixed>> $defaults Default paid rows.
 * @param array<int, array<string, mixed>> $rows     ACF repeater rows.
 * @return array<int, array<string, mixed>>
 */
function seahivez_map_acf_extras_paid_items( $defaults, $rows ) {
	if ( empty( $rows ) || ! is_array( $rows ) ) {
		return $defaults;
	}

	$defaults_by_id = array();

	foreach ( $defaults as $item ) {
		$id = (string) ( $item['id'] ?? '' );

		if ( $id ) {
			$defaults_by_id[ $id ] = $item;
		}
	}

	$mapped = array();

	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}

		$row_id   = sanitize_key( (string) ( $row['item_id'] ?? '' ) );
		$fallback = $row_id ? ( $defaults_by_id[ $row_id ] ?? null ) : null;
		$title    = (string) ( $row['title'] ?? '' );

		if ( '' === $title && empty( $fallback ) ) {
			continue;
		}

		$mapped[] = array(
			'id'          => $row_id ? $row_id : (string) ( $fallback['id'] ?? sanitize_key( $title ) ),
			'icon'        => seahivez_normalize_acf_icon( $row['icon'] ?? '' ) ?: (string) ( $fallback['icon'] ?? '' ),
			'title'       => $title ? $title : (string) ( $fallback['title'] ?? '' ),
			'price'       => isset( $row['price'] ) && '' !== (string) $row['price']
				? (int) $row['price']
				: (int) ( $fallback['price'] ?? 0 ),
			'description' => isset( $row['description'] ) && (string) $row['description'] !== ''
				? (string) $row['description']
				: (string) ( $fallback['description'] ?? '' ),
		);
	}

	return ! empty( $mapped ) ? $mapped : $defaults;
}

/**
 * Map ACF food & drinks rows for the Extras page.
 *
 * @param array<string, mixed> $defaults Default section payload.
 * @param array<string, mixed> $acf      ACF group values.
 * @return array<string, mixed>
 */
function seahivez_map_acf_extras_food_drinks_section( $defaults, $acf ) {
	if ( empty( $acf ) || ! is_array( $acf ) ) {
		return $defaults;
	}

	foreach ( array( 'eyebrow', 'status', 'note' ) as $key ) {
		if ( ! empty( $acf[ $key ] ) ) {
			$defaults[ $key ] = (string) $acf[ $key ];
		}
	}

	if ( empty( $acf['items'] ) || ! is_array( $acf['items'] ) ) {
		return $defaults;
	}

	$defaults_by_id = array();

	foreach ( $defaults['items'] as $item ) {
		$id = (string) ( $item['id'] ?? '' );

		if ( $id ) {
			$defaults_by_id[ $id ] = $item;
		}
	}

	$items = array();

	foreach ( $acf['items'] as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}

		$row_id   = sanitize_key( (string) ( $row['item_id'] ?? '' ) );
		$fallback = $row_id ? ( $defaults_by_id[ $row_id ] ?? null ) : null;
		$title    = (string) ( $row['title'] ?? '' );

		if ( '' === $title && empty( $fallback ) ) {
			continue;
		}

		$description_list = array();

		if ( ! empty( $row['description_list'] ) ) {
			$description_list = array_values(
				array_filter(
					array_map(
						'trim',
						preg_split( '/\r\n|\r|\n/', (string) $row['description_list'] )
					)
				)
			);
		} elseif ( ! empty( $fallback['description_list'] ) && is_array( $fallback['description_list'] ) ) {
			$description_list = $fallback['description_list'];
		}

		$items[] = array(
			'id'               => $row_id ? $row_id : (string) ( $fallback['id'] ?? sanitize_key( $title ) ),
			'icon'             => seahivez_normalize_acf_icon( $row['icon'] ?? '' ) ?: ( $fallback['icon'] ?? '' ),
			'title'            => $title ? $title : (string) ( $fallback['title'] ?? '' ),
			'price'            => isset( $row['price'] ) && '' !== (string) $row['price']
				? (int) $row['price']
				: (int) ( $fallback['price'] ?? 0 ),
			'unit'             => ! empty( $row['unit'] )
				? (string) $row['unit']
				: (string) ( $fallback['unit'] ?? '' ),
			'description'      => isset( $row['description'] ) && (string) $row['description'] !== ''
				? (string) $row['description']
				: (string) ( $fallback['description'] ?? '' ),
			'description_list' => $description_list,
		);
	}

	if ( ! empty( $items ) ) {
		$defaults['items'] = $items;
	}

	return $defaults;
}

/**
 * Map ACF good-to-know notes for the Extras page.
 *
 * @param array<int, string>             $defaults Default notes.
 * @param array<string, mixed>|null      $acf      ACF group values.
 * @param string                         $title_default Default section title.
 * @return array{title: string, items: array<int, string>}
 */
function seahivez_map_acf_extras_good_to_know( $defaults, $acf, $title_default ) {
	$result = array(
		'title' => $title_default,
		'items' => $defaults,
	);

	if ( empty( $acf ) || ! is_array( $acf ) ) {
		return $result;
	}

	if ( ! empty( $acf['title'] ) ) {
		$result['title'] = (string) $acf['title'];
	}

	if ( empty( $acf['items'] ) || ! is_array( $acf['items'] ) ) {
		return $result;
	}

	$items = array();

	foreach ( $acf['items'] as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}

		$text = trim( (string) ( $row['text'] ?? '' ) );

		if ( '' !== $text ) {
			$items[] = $text;
		}
	}

	if ( ! empty( $items ) ) {
		$result['items'] = $items;
	}

	return $result;
}

/**
 * Map ACF gallery section for the Extras page.
 *
 * @param array<string, mixed>             $header_defaults Default header.
 * @param array<int, array<string, mixed>> $items_defaults  Default gallery items.
 * @param array<string, mixed>|null        $acf             ACF group values.
 * @return array{header: array<string, string>, items: array<int, array<string, mixed>>}
 */
function seahivez_map_acf_extras_gallery_section( $header_defaults, $items_defaults, $acf ) {
	$header = $header_defaults;
	$items  = $items_defaults;

	if ( empty( $acf ) || ! is_array( $acf ) ) {
		return array(
			'header' => $header,
			'items'  => $items,
		);
	}

	foreach ( array( 'eyebrow', 'heading', 'description' ) as $key ) {
		if ( ! empty( $acf[ $key ] ) ) {
			$header[ $key ] = (string) $acf[ $key ];
		}
	}

	if ( ! empty( $acf['images'] ) && is_array( $acf['images'] ) ) {
		$mapped_items = seahivez_map_acf_gallery_image_array( $acf['images'], $items_defaults );

		if ( ! empty( $mapped_items ) ) {
			$spans = seahivez_get_yacht_gallery_span_pattern( count( $mapped_items ) );

			foreach ( $mapped_items as $index => $item ) {
				$mapped_items[ $index ]['span'] = $spans[ $index ] ?? '';
			}

			$items = $mapped_items;
		}
	}

	return array(
		'header' => $header,
		'items'  => $items,
	);
}

/**
 * Merge ACF Extras page fields onto theme defaults.
 *
 * @param array<string, mixed> $defaults Default sections payload.
 * @return array<string, mixed>
 */
function seahivez_map_acf_extras_page_sections( $defaults ) {
	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$intro = get_field( 'intro' );

	if ( ! empty( $intro ) ) {
		$defaults['intro'] = (string) $intro;
	}

	$included_section = get_field( 'included_section' );

	if ( ! empty( $included_section ) && is_array( $included_section ) ) {
		if ( ! empty( $included_section['included_heading'] ) ) {
			$defaults['included_heading'] = (string) $included_section['included_heading'];
		}

		if ( ! empty( $included_section['included_helper'] ) ) {
			$defaults['included_helper'] = (string) $included_section['included_helper'];
		}

		$defaults['included_equipment'] = seahivez_map_acf_extras_included_equipment(
			$defaults['included_equipment'],
			$included_section['included_equipment'] ?? array()
		);

		$defaults['included_services'] = seahivez_map_acf_extras_included_services(
			$defaults['included_services'],
			$included_section['included_services'] ?? array()
		);
	}

	$paid_section = get_field( 'paid_section' );

	if ( ! empty( $paid_section ) && is_array( $paid_section ) ) {
		if ( ! empty( $paid_section['paid_heading'] ) ) {
			$defaults['paid_heading'] = (string) $paid_section['paid_heading'];
		}

		if ( ! empty( $paid_section['paid_helper'] ) ) {
			$defaults['paid_helper'] = (string) $paid_section['paid_helper'];
		}

		$defaults['paid_items'] = seahivez_map_acf_extras_paid_items(
			$defaults['paid_items'],
			$paid_section['paid_items'] ?? array()
		);
	}

	$food_drinks = get_field( 'food_drinks' );

	$defaults['food_drinks'] = seahivez_map_acf_extras_food_drinks_section(
		$defaults['food_drinks'],
		is_array( $food_drinks ) ? $food_drinks : array()
	);

	$good_to_know = seahivez_map_acf_extras_good_to_know(
		$defaults['good_to_know'],
		get_field( 'good_to_know' ),
		(string) $defaults['good_to_know_title']
	);

	$defaults['good_to_know_title'] = $good_to_know['title'];
	$defaults['good_to_know']       = $good_to_know['items'];

	$calculator = get_field( 'calculator' );

	if ( ! empty( $calculator ) && is_array( $calculator ) ) {
		$defaults['calculator'] = array_merge(
			$defaults['calculator'],
			array_filter(
				array(
					'eyebrow'     => ! empty( $calculator['eyebrow'] ) ? (string) $calculator['eyebrow'] : '',
					'heading'     => ! empty( $calculator['heading'] ) ? (string) $calculator['heading'] : '',
					'description' => ! empty( $calculator['description'] ) ? (string) $calculator['description'] : '',
				)
			)
		);

		$selected_packages = $calculator['packages'] ?? null;

		if ( ! empty( $selected_packages ) ) {
			$defaults['calculator_packages'] = seahivez_get_extras_page_calculator_packages( $selected_packages );
			$defaults['calculator_configs']  = seahivez_get_extras_page_calculator_configs( $defaults['calculator_packages'] );
		}
	}

	$gallery = seahivez_map_acf_extras_gallery_section(
		$defaults['gallery_header'],
		$defaults['gallery_items'],
		get_field( 'gallery' )
	);

	$defaults['gallery_header'] = $gallery['header'];
	$defaults['gallery_items']  = $gallery['items'];

	return $defaults;
}
