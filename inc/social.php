<?php
/**
 * Social and contact configuration helpers.
 *
 * Central source for social URLs and contact details.
 * Phase 4: replace placeholders with ACF Options → Social & Contact fields.
 *
 * @package seahivez-theme
 */

/**
 * Social and contact data.
 *
 * @return array<string, string>
 */
function seahivez_get_social_contact_data() {
	return array(
		'instagram_url'    => 'https://instagram.com/seahivez',
		'instagram_handle' => '@seahivez',
		'whatsapp_number'  => '34000000000',
		'whatsapp_url'     => '',
		'phone'            => '+34 000 000 000',
		'email'            => 'info@seahivez.com',
		'address'          => __( "Mallorca / S'Arenal", 'seahivez-theme' ),
	);
}

/**
 * Instagram profile URL.
 *
 * @return string
 */
function seahivez_get_instagram_url() {
	$data = seahivez_get_social_contact_data();

	return ! empty( $data['instagram_url'] ) ? $data['instagram_url'] : '';
}

/**
 * Instagram handle for display (e.g. @seahivez).
 *
 * @return string
 */
function seahivez_get_instagram_handle() {
	$data = seahivez_get_social_contact_data();

	return ! empty( $data['instagram_handle'] ) ? $data['instagram_handle'] : '';
}

/**
 * Build a WhatsApp wa.me URL from a digits-only number.
 *
 * @param string $number Phone number; spaces and "+" are stripped.
 * @return string
 */
function seahivez_format_whatsapp_url( $number ) {
	$digits = preg_replace( '/\D+/', '', (string) $number );

	if ( '' === $digits ) {
		return '';
	}

	return 'https://wa.me/' . $digits;
}

/**
 * WhatsApp chat URL.
 *
 * Uses whatsapp_url when set, otherwise builds from whatsapp_number.
 *
 * @return string
 */
function seahivez_get_whatsapp_url() {
	$data = seahivez_get_social_contact_data();

	if ( ! empty( $data['whatsapp_url'] ) ) {
		return $data['whatsapp_url'];
	}

	return seahivez_format_whatsapp_url( $data['whatsapp_number'] ?? '' );
}

/**
 * Allowed social icon identifiers.
 *
 * @return array<string, string>
 */
function seahivez_get_allowed_social_icons() {
	return array(
		'instagram' => __( 'Instagram', 'seahivez-theme' ),
		'whatsapp'  => __( 'WhatsApp', 'seahivez-theme' ),
	);
}

/**
 * SVG path markup for social icons.
 *
 * @param string $icon_name Icon identifier.
 * @return string|false
 */
function seahivez_get_social_icon_paths( $icon_name ) {
	$paths = array(
		'instagram' => array(
			'<rect x="3" y="3" width="18" height="18" rx="5"/>',
			'<circle cx="12" cy="12" r="4"/>',
			'<circle cx="17" cy="7" r="0.75" fill="currentColor" stroke="none"/>',
		),
		'whatsapp'  => array(
			'<path d="M8.5 19.5 5 20l.8-3.2C4.3 15.2 3.5 13.2 3.5 11a8.5 8.5 0 1 1 17 0 8.5 8.5 0 0 1-12 8.5z"/>',
			'<path d="M9.5 10.5c.3.6 1.2 2 2.8 2.6 1.1.4 1.8.2 2.3-.1.3-.2.9-.7 1-.9.1-.2.1-.4 0-.6-.1-.1-.4-.3-.6-.4-.2-.1-.4-.1-.5 0-.2.2-.5.5-.6.6-.1.1-.3.1-.5 0-.4-.2-1.5-.6-2.4-1.5-.9-.9-1.3-2-1.4-2.3 0-.2 0-.4.2-.6.1-.1.3-.3.4-.4.1-.1.1-.3 0-.4-.1-.2-.3-.7-.4-.9-.1-.2-.2-.2-.4-.2h-.8c-.2 0-.5.1-.7.4-.2.3-.9.9-.9 2.1 0 1.2.9 2.4 1 2.6.1.2 1.8 2.8 4.4 3.8.6.2 1 .4 1.3.5.6.2 1.1.2 1.5.1.4-.1 1.3-.5 1.5-1 .2-.5.2-.9.1-1-.1-.1-.3-.2-.6-.3-.3-.1-1.3-.6-1.5-.7-.2-.1-.4-.1-.6 0-.2.1-.5.3-.6.4-.2.1-.3.1-.5 0z"/>',
		),
	);

	$icon_name = sanitize_key( $icon_name );

	return $paths[ $icon_name ] ?? false;
}

/**
 * Build inline SVG markup for a social icon.
 *
 * @param string $icon_name Icon identifier.
 * @param array  $args {
 *     Optional. Rendering arguments.
 *
 *     @type string $class CSS classes for the SVG element.
 *     @type string $size  Size token: sm, md.
 * }
 * @return string
 */
function seahivez_get_social_icon_svg( $icon_name, $args = array() ) {
	$defaults = array(
		'class' => '',
	);

	$args   = wp_parse_args( $args, $defaults );
	$paths  = seahivez_get_social_icon_paths( $icon_name );
	$labels = seahivez_get_allowed_social_icons();

	if ( ! $paths || ! isset( $labels[ $icon_name ] ) ) {
		return '';
	}

	$classes = trim( 'icon-social h-6 w-6 ' . $args['class'] );

	$path_markup = implode( '', $paths );

	$svg = sprintf(
		'<svg class="%1$s" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%2$s</svg>',
		esc_attr( $classes ),
		$path_markup
	);

	return wp_kses( $svg, seahivez_get_svg_allowed_html() );
}

/**
 * Echo a social icon SVG.
 *
 * @param string $icon_name Icon identifier.
 * @param array  $args      Optional rendering arguments.
 * @return void
 */
function seahivez_render_social_icon( $icon_name, $args = array() ) {
	echo seahivez_get_social_icon_svg( $icon_name, $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sanitized via wp_kses.
}

/**
 * Social link items prepared for templates.
 *
 * @return array<int, array<string, string>>
 */
function seahivez_get_social_links() {
	$data  = seahivez_get_social_contact_data();
	$links = array();

	if ( ! empty( $data['instagram_url'] ) ) {
		$links[] = array(
			'key'      => 'instagram',
			'url'      => $data['instagram_url'],
			'label'    => __( 'Instagram', 'seahivez-theme' ),
			'subtitle' => ! empty( $data['instagram_handle'] ) ? $data['instagram_handle'] : __( 'Follow us', 'seahivez-theme' ),
		);
	}

	$whatsapp_url = seahivez_get_whatsapp_url();

	if ( ! empty( $whatsapp_url ) ) {
		$links[] = array(
			'key'      => 'whatsapp',
			'url'      => $whatsapp_url,
			'label'    => __( 'WhatsApp', 'seahivez-theme' ),
			'subtitle' => __( 'Chat with us', 'seahivez-theme' ),
		);
	}

	return $links;
}
