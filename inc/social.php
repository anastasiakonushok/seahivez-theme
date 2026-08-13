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
		'telegram_url'     => 'https://t.me/seahivez',
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
		'telegram'  => __( 'Telegram', 'seahivez-theme' ),
	);
}

/**
 * Resolve a social icon slug to an absolute SVG file path.
 *
 * @param string $icon_name Icon identifier.
 * @return string|false
 */
function seahivez_get_social_icon_path( $icon_name ) {
	$icon_name = sanitize_key( $icon_name );
	$allowed   = seahivez_get_allowed_social_icons();

	if ( ! array_key_exists( $icon_name, $allowed ) ) {
		return false;
	}

	$file_map = array(
		'instagram' => 'svg-instagram.svg',
		'whatsapp'  => 'svg-whatsup.svg',
		'telegram'  => 'svg-telegram.svg',
	);

	$filename = $file_map[ $icon_name ] ?? '';

	if ( '' === $filename ) {
		return false;
	}

	$relative = 'assets/images/icons/social-media/' . $filename;
	$path     = get_theme_file_path( $relative );

	return file_exists( $path ) ? $path : false;
}

/**
 * Build inline SVG markup for a social icon.
 *
 * Loads designer SVGs from assets/images/icons/social-media/.
 *
 * @param string $icon_name Icon identifier.
 * @param array  $args {
 *     Optional. Rendering arguments.
 *
 *     @type string $class CSS classes for the SVG element.
 * }
 * @return string
 */
function seahivez_get_social_icon_svg( $icon_name, $args = array() ) {
	$defaults = array(
		'class' => '',
	);

	$args   = wp_parse_args( $args, $defaults );
	$labels = seahivez_get_allowed_social_icons();
	$path   = seahivez_get_social_icon_path( $icon_name );

	if ( ! $path || ! isset( $labels[ $icon_name ] ) ) {
		return '';
	}

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	$svg = file_get_contents( $path );

	if ( false === $svg || '' === $svg ) {
		return '';
	}

	$svg = str_ireplace( array( '#0B1F3A', '#070C26' ), 'currentColor', $svg );

	$classes = trim( 'icon-social h-6 w-6 ' . $args['class'] );
	$class_attr = esc_attr( $classes );

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

		if ( false === stripos( $attrs, 'aria-hidden=' ) ) {
			$svg = preg_replace( '/<svg\b/', '<svg aria-hidden="true" focusable="false"', $svg, 1 );
		}
	}

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

	if ( ! empty( $data['telegram_url'] ) ) {
		$links[] = array(
			'key'      => 'telegram',
			'url'      => $data['telegram_url'],
			'label'    => __( 'Telegram', 'seahivez-theme' ),
			'subtitle' => __( 'Message us', 'seahivez-theme' ),
		);
	}

	return $links;
}
