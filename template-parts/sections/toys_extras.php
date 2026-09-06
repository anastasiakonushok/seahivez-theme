<?php
/**
 * Flexible section: Toys & Extras
 *
 * @package seahivez-theme
 */

$defaults = seahivez_get_home_extras_data();
$section  = array(
	'eyebrow'           => get_sub_field( 'eyebrow' ) ?: $defaults['eyebrow'],
	'heading'           => get_sub_field( 'heading' ) ?: $defaults['heading'],
	'description'       => get_sub_field( 'description' ) ?: $defaults['description'],
	'included_heading'  => $defaults['included_heading'],
	'included_helper'   => $defaults['included_helper'],
	'paid_heading'      => $defaults['paid_heading'],
	'paid_helper'       => $defaults['paid_helper'],
	'included'          => array(),
	'paid'              => array(),
	'amenities'         => array(),
);

if ( have_rows( 'included' ) ) {
	while ( have_rows( 'included' ) ) {
		the_row();
		$section['included'][] = array(
			'icon'     => seahivez_normalize_acf_icon( get_sub_field( 'icon' ) ),
			'title'    => (string) get_sub_field( 'title' ),
			'included' => true,
		);
	}
}

if ( have_rows( 'paid' ) ) {
	while ( have_rows( 'paid' ) ) {
		the_row();
		$section['paid'][] = array(
			'icon'     => seahivez_normalize_acf_icon( get_sub_field( 'icon' ) ),
			'title'    => (string) get_sub_field( 'title' ),
			'price'    => (string) get_sub_field( 'price' ),
			'included' => false,
		);
	}
}

if ( have_rows( 'amenities' ) ) {
	while ( have_rows( 'amenities' ) ) {
		the_row();
		$text = get_sub_field( 'text' );

		if ( $text ) {
			$section['amenities'][] = (string) $text;
		}
	}
}

if ( empty( $section['included'] ) ) {
	$section['included'] = $defaults['included'];
}
if ( empty( $section['paid'] ) ) {
	$section['paid'] = $defaults['paid'];
}
if ( empty( $section['amenities'] ) ) {
	$section['amenities'] = $defaults['amenities'];
}

get_template_part(
	'template-parts/home/toys-extras',
	null,
	array( 'extras' => $section )
);
