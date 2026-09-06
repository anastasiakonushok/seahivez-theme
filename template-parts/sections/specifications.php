<?php
/**
 * Flexible section: Specifications
 *
 * @package seahivez-theme
 */

$section = array(
	'eyebrow' => get_sub_field( 'eyebrow' ),
	'heading' => get_sub_field( 'heading' ),
	'intro'   => get_sub_field( 'intro' ),
	'groups'  => array(),
);

if ( have_rows( 'groups' ) ) {
	while ( have_rows( 'groups' ) ) {
		the_row();
		$group_items = array();

		if ( have_rows( 'items' ) ) {
			while ( have_rows( 'items' ) ) {
				the_row();
				$group_items[] = seahivez_map_acf_spec_item_row(
					array(
						'icon'      => get_sub_field( 'icon' ),
						'label'     => get_sub_field( 'label' ),
						'value'     => get_sub_field( 'value' ),
						'languages' => get_sub_field( 'languages' ),
					)
				);
			}
		}

		$section['groups'][] = array(
			'title' => (string) get_sub_field( 'title' ),
			'items' => $group_items,
		);
	}
}

get_template_part(
	'template-parts/home/specifications',
	null,
	array( 'section' => $section )
);
