<?php
/**
 * ACF Pro integration — options page, JSON sync, bootstrap.
 *
 * @package seahivez-theme
 */

/**
 * Whether ACF Pro is available.
 *
 * @return bool
 */
function seahivez_acf_is_active() {
	return function_exists( 'get_field' ) && function_exists( 'acf_add_options_page' );
}

/**
 * Register Theme Settings options page.
 */
function seahivez_acf_register_options_page() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => __( 'Theme Settings', 'seahivez-theme' ),
			'menu_title' => __( 'Theme Settings', 'seahivez-theme' ),
			'menu_slug'  => 'theme-settings',
			'capability' => 'edit_posts',
			'redirect'   => false,
			'position'   => 61,
			'icon_url'   => 'dashicons-admin-customizer',
		)
	);
}
add_action( 'acf/init', 'seahivez_acf_register_options_page' );

/**
 * Save and load ACF JSON from theme acf-json directory.
 *
 * @param string $path Default save path.
 * @return string
 */
function seahivez_acf_json_save_path( $path ) {
	return get_theme_file_path( 'acf-json' );
}
add_filter( 'acf/settings/save_json', 'seahivez_acf_json_save_path' );

/**
 * @param array $paths Load paths.
 * @return array
 */
function seahivez_acf_json_load_paths( $paths ) {
	$paths[] = get_theme_file_path( 'acf-json' );

	return $paths;
}
add_filter( 'acf/settings/load_json', 'seahivez_acf_json_load_paths' );

/**
 * Register local field groups when JSON is not yet imported.
 */
function seahivez_acf_register_field_groups() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	// When JSON sync files exist in acf-json/, ACF loads them automatically.
	$json_files = glob( get_theme_file_path( 'acf-json/group_*.json' ) );

	if ( ! empty( $json_files ) ) {
		return;
	}

	$groups_file = get_theme_file_path( 'inc/acf-fields.php' );

	if ( file_exists( $groups_file ) ) {
		require $groups_file;
	}
}
add_action( 'acf/include_fields', 'seahivez_acf_register_field_groups' );

/**
 * Write local field groups to acf-json when files are missing.
 *
 * Ensures import-ready JSON exists in the theme without a CLI export step.
 */
function seahivez_acf_sync_json_files() {
	if ( ! function_exists( 'acf_get_local_field_groups' ) || ! function_exists( 'acf_prepare_field_group_for_export' ) ) {
		return;
	}

	$json_dir = get_theme_file_path( 'acf-json' );

	if ( ! is_dir( $json_dir ) ) {
		wp_mkdir_p( $json_dir );
	}

	$groups = acf_get_local_field_groups();

	if ( empty( $groups ) ) {
		return;
	}

	foreach ( $groups as $group ) {
		$key = $group['key'] ?? '';

		if ( '' === $key ) {
			continue;
		}

		$file = $json_dir . '/' . $key . '.json';

		if ( file_exists( $file ) ) {
			continue;
		}

		$export = acf_prepare_field_group_for_export( acf_get_field_group( $key ) );

		if ( empty( $export ) ) {
			continue;
		}

		$encoded = wp_json_encode( $export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );

		if ( false !== $encoded ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
			file_put_contents( $file, $encoded . "\n" );
		}
	}
}
add_action( 'acf/init', 'seahivez_acf_sync_json_files', 20 );
