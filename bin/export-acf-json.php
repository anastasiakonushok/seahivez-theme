<?php
/**
 * Export ACF local field groups to acf-json/.
 *
 * Usage: php bin/export-acf-json.php
 *
 * @package seahivez-theme
 */

$wp_load = dirname( __DIR__, 4 ) . '/wp-load.php';

if ( ! file_exists( $wp_load ) ) {
	fwrite( STDERR, "WordPress not found at {$wp_load}\n" );
	exit( 1 );
}

require $wp_load;

if ( ! function_exists( 'acf_get_local_field_groups' ) ) {
	fwrite( STDERR, "ACF is not active.\n" );
	exit( 1 );
}

$json_dir = get_theme_file_path( 'acf-json' );

if ( ! is_dir( $json_dir ) ) {
	wp_mkdir_p( $json_dir );
}

$groups = acf_get_local_field_groups();

if ( empty( $groups ) ) {
	fwrite( STDERR, "No local field groups found.\n" );
	exit( 1 );
}

foreach ( $groups as $group ) {
	$key = $group['key'] ?? '';

	if ( '' === $key ) {
		continue;
	}

	$export = acf_prepare_field_group_for_export( acf_get_field_group( $key ) );

	if ( empty( $export ) ) {
		continue;
	}

	$filename = $json_dir . '/' . $key . '.json';
	$encoded  = wp_json_encode( $export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );

	if ( false === $encoded ) {
		fwrite( STDERR, "Failed to encode {$key}\n" );
		continue;
	}

	file_put_contents( $filename, $encoded . "\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	echo "Exported {$filename}\n";
}

echo "Done.\n";
