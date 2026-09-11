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
	'food_drinks'       => $defaults['food_drinks'] ?? seahivez_get_home_food_drinks_data(),
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
			$section['amenities'][] = array(
				'icon'  => '',
				'label' => (string) $text,
			);
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

$food_drinks_acf = array(
	'eyebrow' => (string) get_sub_field( 'food_drinks_eyebrow' ),
	'status'  => (string) get_sub_field( 'food_drinks_status' ),
	'note'    => (string) get_sub_field( 'food_drinks_note' ),
	'items'   => array(),
);

if ( have_rows( 'food_drinks_items' ) ) {
	while ( have_rows( 'food_drinks_items' ) ) {
		the_row();

		$food_drinks_acf['items'][] = array(
			'id'          => (string) get_sub_field( 'item_id' ),
			'icon'        => seahivez_normalize_acf_icon( get_sub_field( 'icon' ) ),
			'title'       => (string) get_sub_field( 'title' ),
			'price'       => (string) get_sub_field( 'price' ),
			'unit'        => (string) get_sub_field( 'unit' ),
			'description' => (string) get_sub_field( 'description' ),
		);
	}
}

$section['food_drinks'] = seahivez_map_home_food_drinks_section( $food_drinks_acf );

get_template_part(
	'template-parts/home/toys-extras',
	null,
	array( 'extras' => $section )
);
