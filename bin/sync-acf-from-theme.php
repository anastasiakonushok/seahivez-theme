<?php
/**
 * Import ACF field groups from theme acf-json/ into the database.
 *
 * Run when Custom Fields → Sync is confusing or unavailable:
 *   php bin/sync-acf-from-theme.php
 *
 * @package seahivez-theme
 */

$wp_load = dirname( __DIR__, 4 ) . '/wp-load.php';

if ( ! file_exists( $wp_load ) ) {
	fwrite( STDERR, "WordPress not found at {$wp_load}\n" );
	exit( 1 );
}

require $wp_load;

if ( ! function_exists( 'acf_import_field_group' ) ) {
	fwrite( STDERR, "ACF Pro is not active.\n" );
	exit( 1 );
}

$json_dir = get_theme_file_path( 'acf-json' );
$files    = glob( $json_dir . '/group_*.json' );

if ( empty( $files ) ) {
	fwrite( STDERR, "No field group JSON files found in {$json_dir}\n" );
	exit( 1 );
}

foreach ( $files as $file ) {
	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	$raw = file_get_contents( $file );

	if ( false === $raw || '' === $raw ) {
		fwrite( STDERR, "Skipped empty file: {$file}\n" );
		continue;
	}

	$group = json_decode( $raw, true );

	if ( empty( $group ) || empty( $group['key'] ) ) {
		fwrite( STDERR, "Skipped invalid JSON: {$file}\n" );
		continue;
	}

	$result = acf_import_field_group( $group );

	if ( empty( $result ) ) {
		fwrite( STDERR, "Failed to import: {$group['key']}\n" );
		continue;
	}

	echo 'Imported: ' . ( $group['title'] ?? $group['key'] ) . PHP_EOL;
}

echo "Done. Reload the page editor — Icon fields should be image pickers now.\n";
