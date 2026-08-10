<?php
/**
 * Toy/extra SVG icon helpers.
 *
 * Maps safe icon identifiers to local SVG assets under assets/images/icons/toys/.
 * Compatible with future ACF select fields — only whitelisted slugs are allowed.
 *
 * @package seahivez-theme
 */

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
		'efoil-air'     => __( 'Efoil Air', 'seahivez-theme' ),
		'towel'         => __( 'Towel Service', 'seahivez-theme' ),
		'water'         => __( 'Drinking Water', 'seahivez-theme' ),
		'flippers'      => __( 'Flippers', 'seahivez-theme' ),
		'swimming'      => __( 'Swimming', 'seahivez-theme' ),
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

	$relative = 'assets/images/icons/toys/' . $icon_name . '.svg';
	$path     = get_theme_file_path( $relative );

	if ( ! file_exists( $path ) ) {
		return false;
	}

	return $path;
}

/**
 * Allowed SVG tags/attributes for locally controlled icon files.
 *
 * @return array<string, array<string, bool>>
 */
function seahivez_get_svg_allowed_html() {
	return array(
		'svg'      => array(
			'class'           => true,
			'xmlns'           => true,
			'viewbox'         => true,
			'width'           => true,
			'height'          => true,
			'fill'            => true,
			'stroke'          => true,
			'stroke-width'    => true,
			'stroke-linecap'  => true,
			'stroke-linejoin' => true,
			'aria-hidden'     => true,
			'role'            => true,
			'focusable'       => true,
		),
		'path'     => array(
			'd'               => true,
			'fill'            => true,
			'stroke'          => true,
			'stroke-width'    => true,
			'stroke-linecap'  => true,
			'stroke-linejoin' => true,
		),
		'circle'   => array(
			'cx'              => true,
			'cy'              => true,
			'r'               => true,
			'fill'            => true,
			'stroke'          => true,
			'stroke-width'    => true,
		),
		'line'     => array(
			'x1'              => true,
			'y1'              => true,
			'x2'              => true,
			'y2'              => true,
			'stroke'          => true,
			'stroke-width'    => true,
			'stroke-linecap'  => true,
		),
		'rect'     => array(
			'x'               => true,
			'y'               => true,
			'width'           => true,
			'height'          => true,
			'rx'              => true,
			'fill'            => true,
			'stroke'          => true,
			'stroke-width'    => true,
		),
		'polyline' => array(
			'points'          => true,
			'fill'            => true,
			'stroke'          => true,
			'stroke-width'    => true,
			'stroke-linecap'  => true,
			'stroke-linejoin' => true,
		),
		'polygon'  => array(
			'points'          => true,
			'fill'            => true,
			'stroke'          => true,
			'stroke-width'    => true,
			'stroke-linejoin' => true,
		),
		'g'        => array(
			'class' => true,
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

	$file_map = array(
		'guests' => 'svg-guests.svg',
		'crew'   => 'svg-crew.svg',
		'speed'  => 'speed.svg',
	);

	$filename = $file_map[ $icon_name ] ?? ( $icon_name . '.svg' );
	$relative = 'assets/images/icons/specs/' . $filename;
	$path     = get_theme_file_path( $relative );

	if ( ! file_exists( $path ) ) {
		return false;
	}

	return $path;
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
		'class'       => 'h-6 w-6',
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
	$svg = str_ireplace( '#070C26', 'currentColor', $svg );

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
