<?php
/**
 * Flexible section: News
 *
 * @package seahivez-theme
 */

$defaults = seahivez_get_home_news_header();
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

$count = (int) get_sub_field( 'posts_count' );

get_template_part(
	'template-parts/home/news',
	null,
	array(
		'header'      => $header,
		'posts_count' => $count > 0 ? $count : 3,
	)
);
