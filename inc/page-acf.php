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
 * @return array<string, mixed>
 */
function seahivez_map_acf_page_hero( $defaults, $field = 'hero' ) {
	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$hero = get_field( $field );

	if ( empty( $hero ) || ! is_array( $hero ) ) {
		return $defaults;
	}

	$image = $hero['image'] ?? null;

	return array(
		'eyebrow'     => ! empty( $hero['eyebrow'] ) ? (string) $hero['eyebrow'] : $defaults['eyebrow'],
		'heading'     => ! empty( $hero['heading'] ) ? (string) $hero['heading'] : $defaults['heading'],
		'description' => ! empty( $hero['description'] ) ? (string) $hero['description'] : $defaults['description'],
		'image'       => seahivez_get_acf_image_url( $image, 'seahivez-hero', $defaults['image'] ),
		'image_alt'   => ! empty( $hero['image_alt'] ) ? (string) $hero['image_alt'] : ( ! empty( $defaults['image_alt'] ) ? $defaults['image_alt'] : '' ),
		'overlay'     => isset( $defaults['overlay'] ) ? (bool) $defaults['overlay'] : true,
		'compact'     => ! empty( $hero['compact'] ),
	);
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
 * Map experience packages repeater from ACF.
 *
 * @param array<int, array<string, string>> $defaults Defaults.
 * @param string                            $field    Field name.
 * @return array<int, array<string, string>>
 */
function seahivez_map_acf_experience_packages( $defaults, $field = 'packages' ) {
	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$rows = get_field( $field );

	if ( empty( $rows ) || ! is_array( $rows ) ) {
		return $defaults;
	}

	$packages = array();

	foreach ( $rows as $row ) {
		$link  = seahivez_parse_acf_link( $row['link'] ?? null, array( 'label' => '', 'url' => seahivez_get_booking_url() ) );
		$image = $row['image'] ?? null;

		if ( empty( $row['title'] ) ) {
			continue;
		}

		$packages[] = array(
			'title'       => (string) $row['title'],
			'duration'    => (string) ( $row['duration'] ?? '' ),
			'time_slot'   => (string) ( $row['time_slot'] ?? '' ),
			'price'       => (string) ( $row['price'] ?? '' ),
			'description' => (string) ( $row['description'] ?? '' ),
			'image'       => seahivez_get_acf_image_url( $image, 'seahivez-card', '' ),
			'url'         => $link['url'],
		);
	}

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
