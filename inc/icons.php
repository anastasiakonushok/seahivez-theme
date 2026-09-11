<?php
/**
 * SVG icon helpers.
 *
 * All icon assets live in assets/images/icons/{slug}.svg
 * for use in templates and future ACF image/icon fields.
 *
 * @package seahivez-theme
 */

/**
 * Relative theme path to the flat icons directory.
 *
 * @return string
 */
function seahivez_get_icons_base_relative_path() {
	return 'assets/images/icons/';
}

/**
 * Resolve an icon slug to an absolute file path.
 *
 * @param string $icon_name Icon slug, e.g. guests, jet-ski, instagram.
 * @return string|false
 */
function seahivez_get_icon_path( $icon_name ) {
	$icon_name = sanitize_key( str_replace( '_', '-', $icon_name ) );

	if ( '' === $icon_name ) {
		return false;
	}

	$base_path = get_theme_file_path( seahivez_get_icons_base_relative_path() );

	foreach ( array( 'svg', 'png' ) as $extension ) {
		$path = $base_path . $icon_name . '.' . $extension;

		if ( file_exists( $path ) ) {
			return $path;
		}
	}

	return false;
}

/**
 * Public URL for an icon SVG (for ACF image fields and <img> tags).
 *
 * @param string $icon_name Icon slug.
 * @return string
 */
function seahivez_get_icon_uri( $icon_name ) {
	$icon_name = sanitize_key( str_replace( '_', '-', $icon_name ) );

	if ( '' === $icon_name || ! seahivez_get_icon_path( $icon_name ) ) {
		return '';
	}

	foreach ( array( 'svg', 'png' ) as $extension ) {
		$relative = seahivez_get_icons_base_relative_path() . $icon_name . '.' . $extension;

		if ( file_exists( get_theme_file_path( $relative ) ) ) {
			return get_theme_file_uri( $relative );
		}
	}

	return '';
}

/**
 * Combined registry of all icon slugs available in the icons folder.
 *
 * @return array<string, string>
 */
function seahivez_get_all_icon_choices() {
	$choices = array_merge(
		seahivez_get_allowed_spec_icons(),
		seahivez_get_allowed_toy_icons()
	);

	if ( function_exists( 'seahivez_get_allowed_social_icons' ) ) {
		$choices = array_merge( $choices, seahivez_get_allowed_social_icons() );
	}

	return $choices;
}

/**
 * Normalize ACF icon field value (image array, attachment ID, or legacy slug).
 *
 * @param mixed  $icon         ACF icon field value.
 * @param string $default_slug Fallback slug from hardcoded defaults.
 * @return array{slug: string, url: string, attachment_id: int}
 */
function seahivez_normalize_acf_icon( $icon, $default_slug = '' ) {
	$result = array(
		'slug'            => '',
		'url'             => '',
		'attachment_id'   => 0,
	);

	if ( is_array( $icon ) && ! empty( $icon['url'] ) ) {
		$result['url']           = (string) $icon['url'];
		$result['attachment_id'] = ! empty( $icon['ID'] ) ? (int) $icon['ID'] : 0;

		return $result;
	}

	if ( is_numeric( $icon ) ) {
		$attachment_id = (int) $icon;
		$url           = wp_get_attachment_url( $attachment_id );

		if ( $url ) {
			$result['url']           = $url;
			$result['attachment_id'] = $attachment_id;
		}

		return $result;
	}

	$slug = sanitize_key( str_replace( '_', '-', (string) $icon ) );

	if ( '' === $slug ) {
		$slug = sanitize_key( str_replace( '_', '-', $default_slug ) );
	}

	if ( '' !== $slug ) {
		$result['slug'] = $slug;
		$result['url']  = seahivez_get_icon_uri( $slug );
	}

	return $result;
}

/**
 * Whether normalized icon data has a renderable source.
 *
 * @param array{slug?: string, url?: string, attachment_id?: int}|string $icon Icon data or legacy slug.
 * @return bool
 */
function seahivez_has_icon( $icon ) {
	if ( is_string( $icon ) ) {
		$icon = seahivez_normalize_acf_icon( $icon );
	}

	return ! empty( $icon['slug'] ) || ! empty( $icon['url'] ) || ! empty( $icon['attachment_id'] );
}

/**
 * Load inline SVG markup from a local file path.
 *
 * @param string $path Absolute file path.
 * @param array  $args Rendering arguments (class, aria_hidden).
 * @return string
 */
function seahivez_get_inline_svg_from_path( $path, $args = array() ) {
	$defaults = array(
		'class'       => 'h-8 w-8',
		'aria_hidden' => true,
	);

	$args = wp_parse_args( $args, $defaults );

	if ( ! $path || ! file_exists( $path ) ) {
		return '';
	}

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	$svg = file_get_contents( $path );

	if ( false === $svg || '' === $svg ) {
		return '';
	}

	$svg = str_ireplace( array( '#0B1F3A', '#070C26' ), 'currentColor', $svg );

	$class_attr = esc_attr( $args['class'] );

	if ( preg_match( '/<svg\b([^>]*)>/', $svg, $matches ) ) {
		$attrs = $matches[1];

		if ( false !== stripos( $attrs, 'class=' ) ) {
			$svg = preg_replace(
				'/<svg\b([^>]*)\bclass=(["\'])(.*?)\2/',
				'<svg$1class=$2$3 ' . $class_attr . '$2',
				$svg,
				1
			);
		} else {
			$svg = preg_replace(
				'/<svg\b/',
				'<svg class="' . $class_attr . '"',
				$svg,
				1
			);
		}

		if ( $args['aria_hidden'] && false === stripos( $attrs, 'aria-hidden=' ) ) {
			$svg = preg_replace( '/<svg\b/', '<svg aria-hidden="true"', $svg, 1 );
		}
	}

	return wp_kses( $svg, seahivez_get_svg_allowed_html() );
}

/**
 * Render an icon from ACF image field data or a legacy slug.
 *
 * @param array<string, mixed>|string $icon Normalized icon data, ACF image array, or slug.
 * @param array                       $args Rendering arguments.
 * @return void
 */
function seahivez_render_flexible_icon( $icon, $args = array() ) {
	$defaults = array(
		'class'       => 'h-8 w-8',
		'aria_hidden' => true,
	);

	$args       = wp_parse_args( $args, $defaults );
	$normalized = is_array( $icon ) && ( isset( $icon['slug'] ) || isset( $icon['url'] ) || isset( $icon['attachment_id'] ) )
		? $icon
		: seahivez_normalize_acf_icon( $icon );

	if ( ! empty( $normalized['attachment_id'] ) ) {
		$path = get_attached_file( (int) $normalized['attachment_id'] );

		if ( $path && file_exists( $path ) && preg_match( '/\.svg$/i', $path ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo seahivez_get_inline_svg_from_path( $path, $args );
			return;
		}
	}

	if ( ! empty( $normalized['slug'] ) ) {
		$path = seahivez_get_icon_path( $normalized['slug'] );

		if ( $path && preg_match( '/\.png$/i', $path ) ) {
			printf(
				'<img src="%1$s" alt="" class="%2$s"%3$s />',
				esc_url( seahivez_get_icon_uri( $normalized['slug'] ) ),
				esc_attr( $args['class'] ),
				$args['aria_hidden'] ? ' aria-hidden="true"' : ''
			);
			return;
		}

		if ( $path ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo seahivez_get_inline_svg_from_path( $path, $args );
			return;
		}
	}

	if ( ! empty( $normalized['url'] ) ) {
		printf(
			'<img src="%1$s" alt="" class="%2$s"%3$s />',
			esc_url( $normalized['url'] ),
			esc_attr( $args['class'] ),
			$args['aria_hidden'] ? ' aria-hidden="true"' : ''
		);
	}
}

function seahivez_get_acf_icon_field_schema( $key ) {
	return array(
		'key'           => $key,
		'label'         => __( 'Icon', 'seahivez-theme' ),
		'name'          => 'icon',
		'type'          => 'image',
		'return_format' => 'array',
		'preview_size'  => 'thumbnail',
		'mime_types'    => 'svg',
		'instructions'  => __( 'Choose an SVG from assets/images/icons/ or upload your own.', 'seahivez-theme' ),
	);
}

/**
 * Repeater sub-fields for a specification item row.
 *
 * @param string $prefix Unique field key prefix.
 * @return array<int, array<string, mixed>>
 */
function seahivez_get_acf_spec_item_sub_fields( $prefix ) {
	return array(
		seahivez_get_acf_icon_field_schema( $prefix . '_icon' ),
		array(
			'key'   => $prefix . '_label',
			'label' => __( 'Label', 'seahivez-theme' ),
			'name'  => 'label',
			'type'  => 'text',
		),
		array(
			'key'          => $prefix . '_value',
			'label'        => __( 'Value', 'seahivez-theme' ),
			'name'         => 'value',
			'type'         => 'text',
			'instructions' => __( 'Leave empty when using Languages below.', 'seahivez-theme' ),
		),
		array(
			'key'           => $prefix . '_languages',
			'label'         => __( 'Languages', 'seahivez-theme' ),
			'name'          => 'languages',
			'type'          => 'checkbox',
			'choices'       => seahivez_get_language_acf_choices(),
			'return_format' => 'value',
			'layout'        => 'horizontal',
			'instructions'  => __( 'Optional. Shows flag chips instead of Value.', 'seahivez-theme' ),
		),
	);
}

/**
 * Registry of allowed toy/extra icon identifiers.
 *
 * Keys are icon slugs stored in ACF or hardcoded data.
 * Values are human-readable labels for admin UI reference.
 *
 * @return array<string, string>
 */
function seahivez_get_allowed_toy_icons() {
	return array(
		'snorkel'       => __( 'Snorkel Set', 'seahivez-theme' ),
		'paddle-board'  => __( 'Paddle Board', 'seahivez-theme' ),
		'seabob'        => __( 'SeaBob', 'seahivez-theme' ),
		'jet-ski'       => __( 'Jet Ski', 'seahivez-theme' ),
		'efoil-air'        => __( 'Efoil Air', 'seahivez-theme' ),
		'donat'            => __( 'Donat', 'seahivez-theme' ),
		'fishing-package'  => __( 'Fishing Package', 'seahivez-theme' ),
		'towel'          => __( 'Towel Service', 'seahivez-theme' ),
		'water'          => __( 'Drinking Water', 'seahivez-theme' ),
		'flippers'       => __( 'Flippers', 'seahivez-theme' ),
		'swimming'       => __( 'Swimming', 'seahivez-theme' ),
		'food'           => __( 'Food', 'seahivez-theme' ),
		'drinks'         => __( 'Drinks', 'seahivez-theme' ),
		'children-menu'  => __( "Children's Menu", 'seahivez-theme' ),
		'cleaning'       => __( 'Final Cleaning', 'seahivez-theme' ),
		'insurance'      => __( 'Insurance', 'seahivez-theme' ),
	);
}

/**
 * Resolve a toy icon slug to an absolute file path.
 *
 * @param string $icon_name Icon identifier.
 * @return string|false
 */
function seahivez_get_toy_icon_path( $icon_name ) {
	$icon_name = sanitize_key( str_replace( '_', '-', $icon_name ) );
	$allowed   = seahivez_get_allowed_toy_icons();

	if ( ! array_key_exists( $icon_name, $allowed ) ) {
		return false;
	}

	return seahivez_get_icon_path( $icon_name );
}

/**
 * Allowed SVG tags/attributes for locally controlled icon files.
 *
 * @return array<string, array<string, bool>>
 */
function seahivez_get_svg_allowed_html() {
	$common = array(
		'class'           => true,
		'fill'            => true,
		'stroke'          => true,
		'stroke-width'    => true,
		'stroke-linecap'  => true,
		'stroke-linejoin' => true,
		'opacity'         => true,
		'transform'       => true,
		'clip-path'       => true,
		'mask'            => true,
		'style'           => true,
	);

	return array(
		'svg'      => array_merge(
			$common,
			array(
				'xmlns'       => true,
				'viewbox'     => true,
				'width'       => true,
				'height'      => true,
				'aria-hidden' => true,
				'role'        => true,
				'focusable'   => true,
			)
		),
		'path'     => array_merge(
			$common,
			array(
				'd' => true,
			)
		),
		'circle'   => array_merge(
			$common,
			array(
				'cx' => true,
				'cy' => true,
				'r'  => true,
			)
		),
		'line'     => array_merge(
			$common,
			array(
				'x1' => true,
				'y1' => true,
				'x2' => true,
				'y2' => true,
			)
		),
		'rect'     => array_merge(
			$common,
			array(
				'x'      => true,
				'y'      => true,
				'width'  => true,
				'height' => true,
				'rx'     => true,
				'ry'     => true,
			)
		),
		'polyline' => array_merge(
			$common,
			array(
				'points' => true,
			)
		),
		'polygon'  => array_merge(
			$common,
			array(
				'points' => true,
			)
		),
		'g'        => $common,
		'defs'     => array(),
		'clippath' => array(
			'id'    => true,
			'class' => true,
		),
		'mask'     => array(
			'id'           => true,
			'class'        => true,
			'style'        => true,
			'maskunits'    => true,
			'maskcontentunits' => true,
			'x'            => true,
			'y'            => true,
			'width'        => true,
			'height'       => true,
		),
	);
}

/**
 * Get sanitized inline SVG markup for a toy icon.
 *
 * @param string $icon_name Icon identifier.
 * @param array  $args {
 *     Optional. Rendering arguments.
 *
 *     @type string $class       CSS classes for the root SVG element.
 *     @type bool   $aria_hidden Whether the icon is decorative.
 *     @type string $title       Accessible title when not decorative.
 * }
 * @return string
 */
function seahivez_get_toy_icon_svg( $icon_name, $args = array() ) {
	$defaults = array(
		'class'       => 'h-8 w-8',
		'aria_hidden' => true,
		'title'       => '',
	);

	$args = wp_parse_args( $args, $defaults );
	$path = seahivez_get_toy_icon_path( $icon_name );

	if ( ! $path ) {
		return '';
	}

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	$svg = file_get_contents( $path );

	if ( false === $svg || '' === $svg ) {
		return '';
	}

	// Designer exports use navy fills — map to currentColor for hover accents.
	$svg = str_ireplace( array( '#0B1F3A', '#070C26' ), 'currentColor', $svg );

	$class_attr = esc_attr( $args['class'] );

	if ( preg_match( '/<svg\b([^>]*)>/', $svg, $matches ) ) {
		$attrs = $matches[1];

		if ( false !== stripos( $attrs, 'class=' ) ) {
			$svg = preg_replace(
				'/<svg\b([^>]*)\bclass=(["\'])(.*?)\2/',
				'<svg$1class=$2$3 ' . $class_attr . '$2',
				$svg,
				1
			);
		} else {
			$svg = preg_replace(
				'/<svg\b/',
				'<svg class="' . $class_attr . '"',
				$svg,
				1
			);
		}

		if ( $args['aria_hidden'] ) {
			if ( false === stripos( $attrs, 'aria-hidden=' ) ) {
				$svg = preg_replace( '/<svg\b/', '<svg aria-hidden="true"', $svg, 1 );
			}
		} else {
			$svg = preg_replace( '/<svg\b/', '<svg role="img"', $svg, 1 );
		}
	}

	return wp_kses( $svg, seahivez_get_svg_allowed_html() );
}

/**
 * Echo a toy icon SVG.
 *
 * @param string $icon_name Icon identifier.
 * @param array  $args      Optional rendering arguments.
 * @return void
 */
function seahivez_render_toy_icon( $icon_name, $args = array() ) {
	echo seahivez_get_toy_icon_svg( $icon_name, $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sanitized via wp_kses in helper.
}

/**
 * Format an extra item price label.
 *
 * @param string $price    Price string, e.g. "300" or "€300".
 * @param bool   $included Whether the item is included.
 * @return string
 */
function seahivez_format_extra_price_label( $price, $included = false ) {
	if ( $included ) {
		return __( 'Included', 'seahivez-theme' );
	}

	$price = trim( (string) $price );

	if ( '' === $price ) {
		return '';
	}

	if ( 0 === strpos( $price, '€' ) ) {
		return $price;
	}

	return '€' . $price;
}

/**
 * Registry of allowed specification icon identifiers.
 *
 * @return array<string, string>
 */
function seahivez_get_allowed_spec_icons() {
	return array(
		'location'  => __( 'Location', 'seahivez-theme' ),
		'guests'    => __( 'Guests', 'seahivez-theme' ),
		'cabins'    => __( 'Cabins', 'seahivez-theme' ),
		'crew'      => __( 'Crew', 'seahivez-theme' ),
		'calendar'  => __( 'Calendar', 'seahivez-theme' ),
		'speed'     => __( 'Speed', 'seahivez-theme' ),
		'length'    => __( 'Length', 'seahivez-theme' ),
		'beam'      => __( 'Beam', 'seahivez-theme' ),
		'draft'     => __( 'Draft', 'seahivez-theme' ),
		'engines'   => __( 'Engines', 'seahivez-theme' ),
		'bathrooms' => __( 'Bathrooms', 'seahivez-theme' ),
		'languages' => __( 'Languages', 'seahivez-theme' ),
	);
}

/**
 * Resolve a specification icon slug to an absolute file path.
 *
 * @param string $icon_name Icon identifier.
 * @return string|false
 */
function seahivez_get_spec_icon_path( $icon_name ) {
	$icon_name = sanitize_key( str_replace( '_', '-', $icon_name ) );
	$allowed   = seahivez_get_allowed_spec_icons();

	if ( ! array_key_exists( $icon_name, $allowed ) ) {
		return false;
	}

	return seahivez_get_icon_path( $icon_name );
}

/**
 * Get sanitized inline SVG markup for a specification icon.
 *
 * @param string $icon_name Icon identifier.
 * @param array  $args      Optional rendering arguments.
 * @return string
 */
function seahivez_get_spec_icon_svg( $icon_name, $args = array() ) {
	$defaults = array(
		'class'       => 'h-7 w-7',
		'aria_hidden' => true,
	);

	$args = wp_parse_args( $args, $defaults );
	$path = seahivez_get_spec_icon_path( $icon_name );

	if ( ! $path ) {
		return '';
	}

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	$svg = file_get_contents( $path );

	if ( false === $svg || '' === $svg ) {
		return '';
	}

	// Map original brand-navy artwork to theme text color without editing source geometry.
	$svg = str_ireplace( array( '#0B1F3A', '#070C26' ), 'currentColor', $svg );

	$class_attr = esc_attr( $args['class'] );

	if ( preg_match( '/<svg\b([^>]*)>/', $svg, $matches ) ) {
		if ( false !== stripos( $matches[1], 'class=' ) ) {
			$svg = preg_replace(
				'/<svg\b([^>]*)\bclass=(["\'])(.*?)\2/',
				'<svg$1class=$2$3 ' . $class_attr . '$2',
				$svg,
				1
			);
		} else {
			$svg = preg_replace(
				'/<svg\b/',
				'<svg class="' . $class_attr . '"',
				$svg,
				1
			);
		}

		if ( $args['aria_hidden'] && false === stripos( $matches[1], 'aria-hidden=' ) ) {
			$svg = preg_replace( '/<svg\b/', '<svg aria-hidden="true"', $svg, 1 );
		}
	}

	return wp_kses( $svg, seahivez_get_svg_allowed_html() );
}

/**
 * Echo a specification icon SVG.
 *
 * @param string $icon_name Icon identifier.
 * @param array  $args      Optional rendering arguments.
 * @return void
 */
function seahivez_render_spec_icon( $icon_name, $args = array() ) {
	echo seahivez_get_spec_icon_svg( $icon_name, $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
