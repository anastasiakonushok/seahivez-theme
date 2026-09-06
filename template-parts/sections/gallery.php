<?php
/**
 * Flexible section: Gallery
 *
 * @package seahivez-theme
 */

$defaults = seahivez_get_home_gallery_header();
$cta      = seahivez_parse_acf_link(
	get_sub_field( 'cta_link' ),
	array(
		'label' => $defaults['cta_label'],
		'url'   => $defaults['cta_url'],
	)
);

$header = array(
	'eyebrow'     => get_sub_field( 'eyebrow' ) ?: $defaults['eyebrow'],
	'heading'     => get_sub_field( 'heading' ) ?: $defaults['heading'],
	'description' => get_sub_field( 'description' ) ?: $defaults['description'],
	'cta_label'   => $cta['label'],
	'cta_url'     => $cta['url'],
);

$items = array();
$images = get_sub_field( 'images' );

if ( ! empty( $images ) && is_array( $images ) ) {
	foreach ( $images as $image ) {
		$raw = array(
			'attachment_id' => is_array( $image ) && ! empty( $image['ID'] ) ? (int) $image['ID'] : 0,
			'path'          => '',
			'alt'           => is_array( $image ) && ! empty( $image['alt'] ) ? (string) $image['alt'] : '',
			'caption'       => is_array( $image ) && ! empty( $image['caption'] ) ? (string) $image['caption'] : '',
		);

		$items[] = array_merge( $raw, seahivez_resolve_gallery_item_images( $raw ) );
	}
}

get_template_part(
	'template-parts/home/gallery',
	null,
	array(
		'header' => $header,
		'items'  => ! empty( $items ) ? $items : seahivez_get_home_gallery_items(),
	)
);
