<?php
/**
 * Instagram icon partial.
 *
 * @package seahivez-theme
 *
 * @var array $args {
 *     @type string $size  Size token: sm, md.
 *     @type string $class Additional CSS classes.
 * }
 */

$args = wp_parse_args(
	$args ?? array(),
	array(
		'class' => '',
	)
);

seahivez_render_social_icon(
	'instagram',
	array(
		'class' => $args['class'],
	)
);
