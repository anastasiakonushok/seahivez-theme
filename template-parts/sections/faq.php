<?php
/**
 * Flexible section: FAQ
 *
 * @package seahivez-theme
 */

$defaults = seahivez_get_home_faq_header();
$cta      = seahivez_parse_acf_link(
	get_sub_field( 'cta_link' ),
	array(
		'label' => $defaults['cta_label'],
		'url'   => $defaults['contact_url'],
	)
);

$header = array(
	'eyebrow'      => get_sub_field( 'eyebrow' ) ?: $defaults['eyebrow'],
	'heading'      => get_sub_field( 'heading' ) ?: $defaults['heading'],
	'description'  => get_sub_field( 'description' ) ?: $defaults['description'],
	'cta_heading'  => get_sub_field( 'cta_heading' ) ?: $defaults['cta_heading'],
	'cta_label'    => $cta['label'],
	'contact_url'  => $cta['url'],
);

get_template_part(
	'template-parts/home/faq',
	null,
	array(
		'header' => $header,
		'items'  => seahivez_map_acf_faq_items(),
	)
);
