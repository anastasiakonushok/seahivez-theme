<?php
/**
 * Section status line with coloured dot.
 *
 * @package seahivez-theme
 *
 * @var array $args {
 *     @type string $label   Status label.
 *     @type string $variant included|request
 * }
 */

$args = wp_parse_args(
	$args ?? array(),
	array(
		'label'   => '',
		'variant' => 'included',
	)
);

if ( empty( $args['label'] ) ) {
	return;
}

$variant = 'request' === $args['variant'] ? 'request' : 'included';
?>

<div class="section-status section-status--<?php echo esc_attr( $variant ); ?> flex items-center gap-2">
	<span class="section-status__dot" aria-hidden="true"></span>
	<span class="section-status__label"><?php echo esc_html( $args['label'] ); ?></span>
</div>
