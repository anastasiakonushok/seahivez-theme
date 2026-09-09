<?php
/**
 * Facebook icon partial.
 *
 * @package seahivez-theme
 *
 * @var array $args {
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
	'facebook',
	array(
		'class' => $args['class'],
	)
);
