<?php
/**
 * ACF data helpers — read fields with fallbacks to theme defaults.
 *
 * @package seahivez-theme
 */

/**
 * Get an option field with fallback.
 *
 * @param string $name     Field name.
 * @param mixed  $fallback Fallback value.
 * @return mixed
 */
function seahivez_get_option_field( $name, $fallback = '' ) {
	if ( ! seahivez_acf_is_active() ) {
		return $fallback;
	}

	$value = get_field( $name, 'option' );

	if ( null === $value || false === $value || '' === $value ) {
		return $fallback;
	}

	return $value;
}

/**
 * Resolve ACF image field to URL.
 *
 * @param mixed  $image    ACF image array or ID.
 * @param string $size     Image size.
 * @param string $fallback Fallback URL.
 * @return string
 */
function seahivez_get_acf_image_url( $image, $size = 'full', $fallback = '' ) {
	if ( empty( $image ) ) {
		return $fallback;
	}

	if ( is_numeric( $image ) ) {
		$url = wp_get_attachment_image_url( (int) $image, $size );

		return $url ? $url : $fallback;
	}

	if ( is_array( $image ) ) {
		if ( ! empty( $image['sizes'][ $size ] ) ) {
			return (string) $image['sizes'][ $size ];
		}

		if ( ! empty( $image['url'] ) ) {
			return (string) $image['url'];
		}
	}

	return $fallback;
}

/**
 * Parse ACF link field into label + url array.
 *
 * @param mixed $link     ACF link field.
 * @param array $defaults Default label/url.
 * @return array{label: string, url: string}
 */
function seahivez_parse_acf_link( $link, $defaults = array() ) {
	$defaults = wp_parse_args(
		$defaults,
		array(
			'label' => '',
			'url'   => '',
		)
	);

	if ( empty( $link ) || ! is_array( $link ) ) {
		return $defaults;
	}

	return array(
		'label' => ! empty( $link['title'] ) ? (string) $link['title'] : $defaults['label'],
		'url'   => ! empty( $link['url'] ) ? (string) $link['url'] : $defaults['url'],
	);
}

/**
 * Header settings from Theme Settings.
 *
 * @return array<string, mixed>
 */
function seahivez_get_header_settings() {
	$defaults = array(
		'logo_light'      => seahivez_get_theme_image_uri( 'assets/images/logo/logo-white.png' ),
		'logo_dark'       => seahivez_get_theme_image_uri( 'assets/images/logo/logo-dark.png' ),
		'logo_alt'        => get_bloginfo( 'name', 'display' ),
		'cta_label'       => __( 'Book Now', 'seahivez-theme' ),
		'cta_url'         => seahivez_get_booking_url(),
	);

	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$header = get_field( 'header', 'option' );

	if ( empty( $header ) || ! is_array( $header ) ) {
		return $defaults;
	}

	$cta = seahivez_parse_acf_link(
		$header['cta_button'] ?? null,
		array(
			'label' => $defaults['cta_label'],
			'url'   => $defaults['cta_url'],
		)
	);

	return array(
		'logo_light' => seahivez_get_acf_image_url( $header['logo_light'] ?? null, 'full', $defaults['logo_light'] ),
		'logo_dark'  => seahivez_get_acf_image_url( $header['logo_dark'] ?? null, 'full', $defaults['logo_dark'] ),
		'logo_alt'   => ! empty( $header['logo_alt'] ) ? (string) $header['logo_alt'] : $defaults['logo_alt'],
		'cta_label'  => $cta['label'],
		'cta_url'    => $cta['url'],
	);
}

/**
 * Footer settings from Theme Settings.
 *
 * @return array<string, mixed>
 */
function seahivez_get_footer_settings() {
	$contact = seahivez_get_social_contact_data();

	$defaults = array(
		'description'      => __( 'Private yacht charter experiences in Mallorca aboard the Numarine 55 Fly.', 'seahivez-theme' ),
		'phone'            => $contact['phone'],
		'email'            => $contact['email'],
		'address'          => $contact['address'],
		'book_heading'     => __( 'Book Your Experience', 'seahivez-theme' ),
		'book_description' => __( 'Plan your private charter day on the Mediterranean.', 'seahivez-theme' ),
		'book_cta_label'   => __( 'Book Now', 'seahivez-theme' ),
		'book_cta_url'     => seahivez_get_booking_url(),
		'copyright'        => '',
	);

	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$footer = get_field( 'footer', 'option' );

	if ( empty( $footer ) || ! is_array( $footer ) ) {
		return $defaults;
	}

	$book_cta = seahivez_parse_acf_link(
		$footer['book_cta'] ?? null,
		array(
			'label' => $defaults['book_cta_label'],
			'url'   => $defaults['book_cta_url'],
		)
	);

	return array(
		'description'      => ! empty( $footer['description'] ) ? (string) $footer['description'] : $defaults['description'],
		'phone'            => $defaults['phone'],
		'email'            => $defaults['email'],
		'address'          => $defaults['address'],
		'book_heading'     => ! empty( $footer['book_heading'] ) ? (string) $footer['book_heading'] : $defaults['book_heading'],
		'book_description' => ! empty( $footer['book_description'] ) ? (string) $footer['book_description'] : $defaults['book_description'],
		'book_cta_label'   => $book_cta['label'],
		'book_cta_url'     => $book_cta['url'],
		'copyright'        => ! empty( $footer['copyright'] ) ? (string) $footer['copyright'] : $defaults['copyright'],
	);
}

/**
 * Social/contact settings from Theme Settings.
 *
 * @return array<string, string>
 */
function seahivez_get_theme_social_settings() {
	$defaults = array(
		'instagram_url'    => 'https://instagram.com/seahivez',
		'instagram_handle' => '@seahivez',
		'whatsapp_number'  => '34000000000',
		'whatsapp_url'     => '',
		'telegram_url'     => 'https://t.me/seahivez',
		'phone'            => '+34 000 000 000',
		'email'            => 'info@seahivez.com',
		'address'          => __( "Mallorca / S'Arenal", 'seahivez-theme' ),
	);

	if ( ! seahivez_acf_is_active() ) {
		return $defaults;
	}

	$social = get_field( 'social_contact', 'option' );

	if ( empty( $social ) || ! is_array( $social ) ) {
		return $defaults;
	}

	$merged = $defaults;

	foreach ( array_keys( $defaults ) as $key ) {
		if ( ! empty( $social[ $key ] ) ) {
			$merged[ $key ] = (string) $social[ $key ];
		}
	}

	return $merged;
}

/**
 * Whether the current post has flexible sections.
 *
 * @param int|null $post_id Post ID.
 * @return bool
 */
function seahivez_has_flexible_sections( $post_id = null ) {
	if ( ! seahivez_acf_is_active() ) {
		return false;
	}

	$post_id = $post_id ? (int) $post_id : get_queried_object_id();

	return (bool) have_rows( 'sections', $post_id );
}

/**
 * Render flexible content sections for a page.
 *
 * @param int|null $post_id Post ID.
 * @return void
 */
function seahivez_render_flexible_sections( $post_id = null ) {
	if ( ! seahivez_has_flexible_sections( $post_id ) ) {
		return;
	}

	$post_id = $post_id ? (int) $post_id : get_queried_object_id();

	while ( have_rows( 'sections', $post_id ) ) {
		the_row();

		$layout = get_row_layout();

		if ( empty( $layout ) ) {
			continue;
		}

		$template = locate_template( 'template-parts/sections/' . $layout . '.php' );

		if ( $template ) {
			get_template_part( 'template-parts/sections/' . $layout );
			continue;
		}

		// Fallback to home partials when section wrapper is not created yet.
		$home_map = array(
			'hero'           => 'home/hero',
			'specs_bar'      => 'home/specs-bar',
			'about'          => 'home/about-yacht',
			'specifications' => 'home/specifications',
			'experiences'    => 'home/experiences',
			'toys_extras'    => 'home/toys-extras',
			'gallery'        => 'home/gallery',
			'faq'            => 'home/faq',
			'news'           => 'home/news',
			'location_cta'   => 'home/location-cta',
		);

		if ( isset( $home_map[ $layout ] ) ) {
			get_template_part( 'template-parts/' . $home_map[ $layout ] );
		}
	}
}

/**
 * Render default homepage sections (hardcoded fallback).
 *
 * @return void
 */
function seahivez_render_default_home_sections() {
	$sections = array(
		'home/hero',
		'home/specs-bar',
		'home/about-yacht',
		'home/specifications',
		'home/experiences',
		'home/toys-extras',
		'home/gallery',
		'home/faq',
		'home/news',
		'home/location-cta',
	);

	foreach ( $sections as $section ) {
		get_template_part( 'template-parts/' . $section );
	}
}

/**
 * Map ACF hero layout to hero data array.
 *
 * @return array<string, mixed>
 */
function seahivez_map_acf_hero_section() {
	$defaults = seahivez_get_home_hero_data();
	$primary  = seahivez_parse_acf_link(
		get_sub_field( 'primary_button' ),
		array(
			'label' => $defaults['primary_label'],
			'url'   => $defaults['primary_url'],
		)
	);
	$secondary = seahivez_parse_acf_link(
		get_sub_field( 'secondary_button' ),
		array(
			'label' => $defaults['secondary_label'],
			'url'   => $defaults['secondary_url'],
		)
	);

	$image = get_sub_field( 'image' );

	return array(
		'eyebrow'         => get_sub_field( 'eyebrow' ) ?: $defaults['eyebrow'],
		'heading'         => get_sub_field( 'heading' ) ?: $defaults['heading'],
		'description'     => get_sub_field( 'description' ) ?: $defaults['description'],
		'primary_label'   => $primary['label'],
		'primary_url'     => $primary['url'],
		'secondary_label' => $secondary['label'],
		'secondary_url'   => $secondary['url'],
		'image'           => seahivez_get_acf_image_url( $image, 'seahivez-hero', $defaults['image'] ),
		'image_width'     => $defaults['image_width'],
		'image_height'    => $defaults['image_height'],
		'image_alt'       => get_sub_field( 'image_alt' ) ?: ( is_array( $image ) && ! empty( $image['alt'] ) ? $image['alt'] : $defaults['image_alt'] ),
	);
}

/**
 * Map ACF specs bar repeater.
 *
 * @return array<int, array<string, string>>
 */
function seahivez_map_acf_specs_bar() {
	$items = array();

	if ( have_rows( 'items' ) ) {
		while ( have_rows( 'items' ) ) {
			the_row();
			$icon = get_sub_field( 'icon' );

			$row = seahivez_map_acf_spec_item_row(
				array(
					'icon'      => $icon,
					'label'     => get_sub_field( 'label' ),
					'value'     => get_sub_field( 'value' ),
					'languages' => get_sub_field( 'languages' ),
				)
			);

			if ( empty( $row['label'] ) && ! seahivez_has_icon( $row['icon'] ) && empty( $row['languages'] ) ) {
				continue;
			}

			$items[] = $row;
		}
	}

	return ! empty( $items ) ? $items : seahivez_get_home_quick_specs();
}

/**
 * Map ACF about section.
 *
 * @return array<string, mixed>
 */
function seahivez_map_acf_about_section() {
	$defaults = seahivez_get_home_about_data();
	$link     = seahivez_parse_acf_link(
		get_sub_field( 'link' ),
		array(
			'label' => $defaults['link_label'],
			'url'   => $defaults['link_url'],
		)
	);
	$image    = get_sub_field( 'image' );
	$paragraphs = array();

	if ( have_rows( 'paragraphs' ) ) {
		while ( have_rows( 'paragraphs' ) ) {
			the_row();
			$text = get_sub_field( 'text' );

			if ( ! empty( $text ) ) {
				$paragraphs[] = (string) $text;
			}
		}
	}

	return array(
		'eyebrow'    => get_sub_field( 'eyebrow' ) ?: $defaults['eyebrow'],
		'heading'    => get_sub_field( 'heading' ) ?: $defaults['heading'],
		'paragraphs' => ! empty( $paragraphs ) ? $paragraphs : $defaults['paragraphs'],
		'link_label' => $link['label'],
		'link_url'   => $link['url'],
		'image'      => seahivez_get_acf_image_url( $image, 'large', $defaults['image'] ),
		'image_alt'  => get_sub_field( 'image_alt' ) ?: $defaults['image_alt'],
	);
}

/**
 * Map ACF FAQ items repeater.
 *
 * @return array<int, array{question: string, answer: string}>
 */
function seahivez_map_acf_faq_items() {
	$items = array();

	if ( have_rows( 'items' ) ) {
		while ( have_rows( 'items' ) ) {
			the_row();
			$question = get_sub_field( 'question' );
			$answer   = get_sub_field( 'answer' );

			if ( empty( $question ) ) {
				continue;
			}

			$items[] = array(
				'question' => (string) $question,
				'answer'   => (string) $answer,
			);
		}
	}

	return ! empty( $items ) ? $items : seahivez_get_home_faq_items();
}

/**
 * Map ACF location CTA section.
 *
 * @return array<string, string>
 */
function seahivez_map_acf_location_cta() {
	$defaults = seahivez_get_home_location_data();
	$cta      = seahivez_parse_acf_link(
		get_sub_field( 'cta_button' ),
		array(
			'label' => $defaults['cta_label'],
			'url'   => $defaults['cta_url'],
		)
	);

	return array(
		'heading'     => get_sub_field( 'heading' ) ?: $defaults['heading'],
		'description' => get_sub_field( 'description' ) ?: $defaults['description'],
		'cta_label'   => $cta['label'],
		'cta_url'     => $cta['url'],
		'location'    => $defaults['location'],
		'phone'       => $defaults['phone'],
		'email'       => $defaults['email'],
	);
}
