<?php
/**
 * Flexible section: Experiences
 *
 * @package seahivez-theme
 */

$section = array(
	'eyebrow'      => get_sub_field( 'eyebrow' ),
	'heading'      => get_sub_field( 'heading' ),
	'experiences'  => array(),
);

if ( have_rows( 'items' ) ) {
	while ( have_rows( 'items' ) ) {
		the_row();
		$link  = seahivez_parse_acf_link( get_sub_field( 'link' ), array( 'label' => '', 'url' => '' ) );
		$image = get_sub_field( 'image' );

		$section['experiences'][] = array(
			'title'       => (string) get_sub_field( 'title' ),
			'duration'    => (string) get_sub_field( 'duration' ),
			'time_slot'   => (string) get_sub_field( 'time_slot' ),
			'price'       => (string) get_sub_field( 'price' ),
			'description' => (string) get_sub_field( 'description' ),
			'image'       => seahivez_get_acf_image_url( $image, 'seahivez-card', '' ),
			'url'         => $link['url'],
		);
	}
}

get_template_part(
	'template-parts/home/experiences',
	null,
	array( 'section' => $section )
);
