<?php
/**
 * Editorial arrow icon helpers.
 *
 * Inline SVG arrows shared across CTAs, links, gallery hints, and future controls.
 *
 * @package seahivez-theme
 */

/**
 * Allowed arrow icon variants.
 *
 * @return array<string, string>
 */
function seahivez_get_allowed_arrows() {
	return array(
		'right'     => __( 'Arrow right', 'seahivez-theme' ),
		'left'      => __( 'Arrow left', 'seahivez-theme' ),
		'down'      => __( 'Arrow down', 'seahivez-theme' ),
		'right-up'  => __( 'Arrow right up', 'seahivez-theme' ),
	);
}

/**
 * SVG path data for each arrow variant.
 *
 * Shared stroke style: elongated shaft, refined arrowhead, round caps.
 *
 * @param string $variant Arrow identifier.
 * @return string|false
 */
function seahivez_get_arrow_path( $variant ) {
	$paths = array(
		'right'    => 'M4.5 12h11.75M13.25 7.75L18.25 12l-5 4.25',
		'left'     => 'M19.5 12H7.75M10.75 7.75L5.75 12l5 4.25',
		'down'     => 'M12 4.5v11.75M7.75 13.25L12 18.25l4.25-5',
		'right-up' => 'M6.75 17.25L17.25 6.75M10.75 6.75h6.5v6.5',
	);

	$variant = sanitize_key( str_replace( '_', '-', $variant ) );

	if ( ! isset( $paths[ $variant ] ) ) {
		return false;
	}

	return $paths[ $variant ];
}

/**
 * Resolve a size token to CSS classes.
 *
 * @param string $size Size token: sm, md, lg.
 * @return string
 */
function seahivez_get_arrow_size_class( $size = 'sm' ) {
	$sizes = array(
		'sm' => 'icon-arrow--sm',
		'md' => 'icon-arrow--md',
		'lg' => 'icon-arrow--lg',
	);

	$size = sanitize_key( $size );

	return $sizes[ $size ] ?? $sizes['sm'];
}

/**
 * Build inline SVG markup for an arrow icon.
 *
 * @param string $variant Arrow identifier.
 * @param array  $args {
 *     Optional. Rendering arguments.
 *
 *     @type string $class       CSS classes for the SVG element.
 *     @type string $size        Size token: sm, md, lg.
 *     @type bool   $aria_hidden Whether the icon is decorative.
 *     @type string $title       Accessible title when not decorative.
 * }
 * @return string
 */
function seahivez_get_arrow_svg( $variant = 'right', $args = array() ) {
	$defaults = array(
		'class'       => '',
		'size'        => 'sm',
		'aria_hidden' => true,
		'title'       => '',
	);

	$args = wp_parse_args( $args, $defaults );
	$path = seahivez_get_arrow_path( $variant );

	if ( ! $path ) {
		return '';
	}

	$classes = trim( 'icon-arrow ' . seahivez_get_arrow_size_class( $args['size'] ) . ' ' . $args['class'] );
	$allowed = seahivez_get_allowed_arrows();
	$label   = $allowed[ sanitize_key( str_replace( '_', '-', $variant ) ) ] ?? '';

	$aria_hidden = $args['aria_hidden'] ? ' aria-hidden="true" focusable="false"' : ' role="img"';
	$title_markup = '';

	if ( ! $args['aria_hidden'] && $args['title'] ) {
		$title_markup = '<title>' . esc_html( $args['title'] ) . '</title>';
	} elseif ( ! $args['aria_hidden'] && $label ) {
		$title_markup = '<title>' . esc_html( $label ) . '</title>';
	}

	$svg = sprintf(
		'<svg class="%1$s" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"%2$s>%3$s<path d="%4$s"/></svg>',
		esc_attr( $classes ),
		$aria_hidden,
		$title_markup,
		esc_attr( $path )
	);

	return wp_kses( $svg, seahivez_get_svg_allowed_html() );
}

/**
 * Echo an arrow icon SVG.
 *
 * @param string $variant Arrow identifier.
 * @param array  $args    Optional rendering arguments.
 * @return void
 */
function seahivez_render_arrow( $variant = 'right', $args = array() ) {
	echo seahivez_get_arrow_svg( $variant, $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sanitized via wp_kses.
}

/**
 * Echo a decorative arrow icon for use inside `.link-arrow` CTAs.
 *
 * @param string $size Size token: sm, md, lg.
 * @return void
 */
function seahivez_render_link_arrow_icon( $size = 'sm' ) {
	printf(
		'<span class="link-arrow__icon" aria-hidden="true">%s</span>',
		seahivez_get_arrow_svg(
			'right',
			array(
				'size' => $size,
			)
		)
	);
}
