<?php
/**
 * Social and contact configuration helpers.
 *
 * @package seahivez-theme
 */

/**
 * Social and contact data.
 *
 * @return array<string, string>
 */
function seahivez_get_social_contact_data() {
	$defaults = array(
		'instagram_handle' => '@seahivez',
		'whatsapp_number'  => '34000000000',
		'phone'            => '+34 000 000 000',
		'email'            => 'info@seahivez.com',
		'address'          => __( "Mallorca / S'Arenal", 'seahivez-theme' ),
	);

	if ( function_exists( 'seahivez_get_theme_social_settings' ) ) {
		return seahivez_get_theme_social_settings();
	}

	return $defaults;
}

/**
 * Default social link rows when ACF is empty.
 *
 * @return array<int, array<string, string>>
 */
function seahivez_get_default_social_links() {
	return array(
		array(
			'key'      => 'instagram',
			'url'      => 'https://instagram.com/seahivez',
			'label'    => __( 'Instagram', 'seahivez-theme' ),
			'subtitle' => '@seahivez',
		),
		array(
			'key'      => 'whatsapp',
			'url'      => 'https://wa.me/34000000000',
			'label'    => __( 'WhatsApp', 'seahivez-theme' ),
			'subtitle' => __( 'Chat with us', 'seahivez-theme' ),
		),
		array(
			'key'      => 'telegram',
			'url'      => 'https://t.me/seahivez',
			'label'    => __( 'Telegram', 'seahivez-theme' ),
			'subtitle' => __( 'Message us', 'seahivez-theme' ),
		),
	);
}

/**
 * Social links from Theme Settings repeater.
 *
 * @return array<int, array<string, string>>
 */
function seahivez_get_theme_social_links() {
	if ( ! function_exists( 'seahivez_acf_is_active' ) || ! seahivez_acf_is_active() ) {
		return seahivez_get_default_social_links();
	}

	$social = get_field( 'social_contact', 'option' );

	if ( empty( $social ) || ! is_array( $social ) ) {
		return seahivez_get_default_social_links();
	}

	$rows = ! empty( $social['social_links'] ) && is_array( $social['social_links'] )
		? $social['social_links']
		: array();

	if ( empty( $rows ) ) {
		return seahivez_build_social_links_from_legacy( $social );
	}

	$allowed = seahivez_get_allowed_social_icons();
	$links   = array();

	foreach ( $rows as $row ) {
		if ( empty( $row ) || ! is_array( $row ) ) {
			continue;
		}

		$icon = sanitize_key( $row['icon'] ?? '' );
		$url  = ! empty( $row['url'] ) ? esc_url_raw( $row['url'] ) : '';

		if ( ! $icon || ! isset( $allowed[ $icon ] ) || ! $url ) {
			continue;
		}

		$label = ! empty( $row['label'] )
			? sanitize_text_field( $row['label'] )
			: $allowed[ $icon ];

		$subtitle = ! empty( $row['subtitle'] ) ? sanitize_text_field( $row['subtitle'] ) : '';

		if ( '' === $subtitle && 'instagram' === $icon && ! empty( $social['instagram_handle'] ) ) {
			$subtitle = sanitize_text_field( $social['instagram_handle'] );
		}

		if ( '' === $subtitle && 'whatsapp' === $icon ) {
			$subtitle = __( 'Chat with us', 'seahivez-theme' );
		}

		if ( '' === $subtitle && 'facebook' === $icon ) {
			$subtitle = __( 'Follow us', 'seahivez-theme' );
		}

		if ( '' === $subtitle && 'telegram' === $icon ) {
			$subtitle = __( 'Message us', 'seahivez-theme' );
		}

		$links[] = array(
			'key'      => $icon,
			'url'      => $url,
			'label'    => $label,
			'subtitle' => $subtitle,
		);
	}

	if ( empty( $links ) ) {
		return seahivez_get_default_social_links();
	}

	return $links;
}

/**
 * Build social links from legacy flat URL fields (pre-repeater saves).
 *
 * @param array<string, mixed> $social social_contact option data.
 * @return array<int, array<string, string>>
 */
function seahivez_build_social_links_from_legacy( $social ) {
	$links = array();

	if ( ! empty( $social['instagram_url'] ) ) {
		$links[] = array(
			'key'      => 'instagram',
			'url'      => esc_url_raw( $social['instagram_url'] ),
			'label'    => __( 'Instagram', 'seahivez-theme' ),
			'subtitle' => ! empty( $social['instagram_handle'] ) ? sanitize_text_field( $social['instagram_handle'] ) : __( 'Follow us', 'seahivez-theme' ),
		);
	}

	$whatsapp_url = '';
	if ( ! empty( $social['whatsapp_url'] ) ) {
		$whatsapp_url = esc_url_raw( $social['whatsapp_url'] );
	} elseif ( ! empty( $social['whatsapp_number'] ) ) {
		$whatsapp_url = seahivez_format_whatsapp_url( $social['whatsapp_number'] );
	}

	if ( $whatsapp_url ) {
		$links[] = array(
			'key'      => 'whatsapp',
			'url'      => $whatsapp_url,
			'label'    => __( 'WhatsApp', 'seahivez-theme' ),
			'subtitle' => __( 'Chat with us', 'seahivez-theme' ),
		);
	}

	if ( ! empty( $social['telegram_url'] ) ) {
		$links[] = array(
			'key'      => 'telegram',
			'url'      => esc_url_raw( $social['telegram_url'] ),
			'label'    => __( 'Telegram', 'seahivez-theme' ),
			'subtitle' => __( 'Message us', 'seahivez-theme' ),
		);
	}

	if ( empty( $links ) ) {
		return seahivez_get_default_social_links();
	}

	return $links;
}

/**
 * Find a social URL by icon slug.
 *
 * @param string $icon_name Icon identifier.
 * @return string
 */
function seahivez_get_social_link_url( $icon_name ) {
	$icon_name = sanitize_key( $icon_name );

	foreach ( seahivez_get_social_links() as $link ) {
		if ( $link['key'] === $icon_name && ! empty( $link['url'] ) ) {
			return $link['url'];
		}
	}

	if ( 'whatsapp' === $icon_name ) {
		$data = seahivez_get_social_contact_data();
		return seahivez_format_whatsapp_url( $data['whatsapp_number'] ?? '' );
	}

	return '';
}

/**
 * Instagram profile URL.
 *
 * @return string
 */
function seahivez_get_instagram_url() {
	return seahivez_get_social_link_url( 'instagram' );
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
 * @return string
 */
function seahivez_get_whatsapp_url() {
	return seahivez_get_social_link_url( 'whatsapp' );
}

/**
 * Allowed social icon identifiers.
 *
 * @return array<string, string>
 */
function seahivez_get_allowed_social_icons() {
	return array(
		'instagram' => __( 'Instagram', 'seahivez-theme' ),
		'facebook'  => __( 'Facebook', 'seahivez-theme' ),
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

	return seahivez_get_icon_path( $icon_name );
}

/**
 * Build inline SVG markup for a social icon.
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

	$classes    = trim( 'icon-social h-6 w-6 ' . $args['class'] );
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
	return seahivez_get_theme_social_links();
}
