<?php
/**
 * Reusable social links component — compact icon-only row.
 *
 * @package seahivez-theme
 *
 * @var array $args {
 *     @type string $variant Component tone: compact (default), light (dark backgrounds).
 *     @type string $class   Additional wrapper classes.
 *     @type string $heading Optional heading above the icon row.
 * }
 */

$args = wp_parse_args(
	$args ?? array(),
	array(
		'variant' => 'compact',
		'class'   => '',
		'heading' => '',
	)
);

$links = seahivez_get_social_links();

if ( empty( $links ) ) {
	return;
}

$is_light      = 'light' === sanitize_key( $args['variant'] );
$wrapper_class = trim(
	'social-links social-links--compact' . ( $is_light ? ' social-links--light' : '' ) . ' ' . $args['class']
);
$link_class    = trim( 'social-link social-link--compact' . ( $is_light ? ' social-link--light' : '' ) );
?>

<div class="<?php echo esc_attr( $wrapper_class ); ?>">
	<?php if ( ! empty( $args['heading'] ) ) : ?>
		<p class="social-links__heading">
			<?php echo esc_html( $args['heading'] ); ?>
		</p>
	<?php endif; ?>

	<ul class="social-links__list" role="list">
		<?php foreach ( $links as $link ) : ?>
			<li class="social-links__item">
				<a
					class="<?php echo esc_attr( $link_class ); ?>"
					href="<?php echo esc_url( $link['url'] ); ?>"
					target="_blank"
					rel="noopener noreferrer"
					aria-label="<?php echo esc_attr( $link['label'] ); ?>"
				>
					<?php seahivez_render_social_icon( $link['key'] ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
