<?php
/**
 * Extras grid — 3 per row, fills left to right.
 *
 * @package seahivez-theme
 *
 * @var array $args {
 *     @type array<int, array<string, mixed>> $items Extra item rows.
 *     @type string                          $class Additional wrapper classes.
 * }
 */

$args = wp_parse_args(
	$args ?? array(),
	array(
		'items' => array(),
		'class' => '',
	)
);

$items = array_values( array_filter( (array) $args['items'] ) );

if ( empty( $items ) ) {
	return;
}

$wrapper_class = trim( 'extras-grid ' . $args['class'] );
?>

<ul class="<?php echo esc_attr( $wrapper_class ); ?>" role="list">
	<?php foreach ( $items as $item ) : ?>
		<li class="extras-grid__item">
			<?php get_template_part( 'template-parts/cards/extra-item', null, $item ); ?>
		</li>
	<?php endforeach; ?>
</ul>
