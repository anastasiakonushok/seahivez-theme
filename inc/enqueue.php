<?php

/**
 * Enqueue theme scripts and styles.
 *
 * @package seahivez-theme
 */

/**
 * Enqueue frontend assets.
 */
function seahivez_enqueue_assets()
{
	$css_relative = 'assets/dist/main.css';
	$js_relative  = 'assets/dist/main.js';
	$css_path     = get_theme_file_path($css_relative);
	$js_path      = get_theme_file_path($js_relative);

	if (file_exists($css_path)) {
		wp_enqueue_style(
			'seahivez-main',
			get_theme_file_uri($css_relative),
			array(),
			seahivez_get_asset_version($css_relative)
		);

		$specifications_line = 'assets/images/decor/specifications-line.png';

		if (file_exists(get_theme_file_path($specifications_line))) {
			wp_add_inline_style(
				'seahivez-main',
				':root { --seahivez-specifications-line: url("' . esc_url(get_theme_file_uri($specifications_line)) . '"); }'
			);
		}
	}

	if (file_exists($js_path)) {
		wp_enqueue_script(
			'seahivez-main',
			get_theme_file_uri($js_relative),
			array(),
			seahivez_get_asset_version($js_relative),
			true
		);

		$seahivez_data = array(
			'mapsApiKey'  => seahivez_get_google_maps_api_key(),
			'mapsMapId'   => seahivez_get_google_maps_map_id(),
			'port'        => seahivez_get_port_location(),
			'checkoutUrl' => seahivez_get_checkout_url(),
		);

		if (function_exists('seahivez_is_checkout_page') && seahivez_is_checkout_page()) {
			$intl_tel_css = 'assets/vendor/intl-tel-input/intlTelInput.css';
			$intl_tel_utils = 'assets/vendor/intl-tel-input/utils.js';

			if (file_exists(get_theme_file_path($intl_tel_css))) {
				wp_enqueue_style(
					'intl-tel-input',
					get_theme_file_uri($intl_tel_css),
					array('seahivez-main'),
					seahivez_get_asset_version($intl_tel_css)
				);

				$flags_1x = esc_url(get_theme_file_uri('assets/vendor/intl-tel-input/img/flags.webp'));
				$flags_2x = esc_url(get_theme_file_uri('assets/vendor/intl-tel-input/img/flags@2x.webp'));
				$globe_1x = esc_url(get_theme_file_uri('assets/vendor/intl-tel-input/img/globe.webp'));
				$globe_2x = esc_url(get_theme_file_uri('assets/vendor/intl-tel-input/img/globe@2x.webp'));

				wp_add_inline_style(
					'intl-tel-input',
					".checkout-field--phone {
						--iti-path-flags-1x: url('{$flags_1x}');
						--iti-path-flags-2x: url('{$flags_2x}');
						--iti-path-globe-1x: url('{$globe_1x}');
						--iti-path-globe-2x: url('{$globe_2x}');
					}"
				);
			}

			if (file_exists(get_theme_file_path($intl_tel_utils))) {
				$seahivez_data['intlTelUtilsUrl'] = get_theme_file_uri($intl_tel_utils);
			}
		}

		wp_localize_script(
			'seahivez-main',
			'seahivezData',
			$seahivez_data
		);
	}

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'seahivez_enqueue_assets');

/**
 * Preload the self-hosted Satoshi variable font (frontend only).
 */
function seahivez_preload_satoshi_font()
{
	$relative = 'assets/fonts/Satoshi-Variable.woff2';
	$path     = get_theme_file_path($relative);

	if (! file_exists($path)) {
		return;
	}

	printf(
		'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
		esc_url(get_theme_file_uri($relative))
	);
}
add_action('wp_head', 'seahivez_preload_satoshi_font', 1);

/**
 * Preload the homepage hero image for LCP.
 */
function seahivez_preload_hero_image()
{
	if (! is_front_page()) {
		return;
	}

	$hero = seahivez_get_home_hero_data();

	if (empty($hero['image'])) {
		return;
	}

	printf(
		'<link rel="preload" href="%s" as="image">' . "\n",
		esc_url($hero['image'])
	);
}
add_action('wp_head', 'seahivez_preload_hero_image', 2);
