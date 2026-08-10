<?php
/**
 * Arrow icon partial.
 *
 * @package seahivez-theme
 *
 * @var array $args {
 *     @type string $variant     Arrow variant: right, left, down, right-up.
 *     @type string $size        Size token: sm, md, lg.
 *     @type string $class       Additional CSS classes.
 *     @type bool   $aria_hidden Whether the icon is decorative.
 * }
 */

$args = wp_parse_args(
	$args ?? array(),
	array(
		'variant'     => 'right',
		'size'        => 'sm',
		'class'       => '',
		'aria_hidden' => true,
	)
);

seahivez_render_arrow(
	$args['variant'],
	array(
		'size'        => $args['size'],
		'class'       => $args['class'],
		'aria_hidden' => (bool) $args['aria_hidden'],
	)
);
