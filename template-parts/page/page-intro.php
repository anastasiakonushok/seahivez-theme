<?php
/**
 * Simple interior page intro (no image).
 *
 * @package seahivez-theme
 *
 * @var array $args {
 *     @type string $eyebrow
 *     @type string $heading
 *     @type string $description
 * }
 */

$args = wp_parse_args(
	$args ?? array(),
	array(
		'eyebrow'     => '',
		'heading'     => '',
		'description' => '',
	)
);

if ( empty( $args['heading'] ) ) {
	return;
}
?>

<section class="page-intro section-spacing bg-warm-white" aria-labelledby="page-intro-heading">
	<div class="site-container">
		<div class="reveal max-w-3xl">
			<?php if ( ! empty( $args['eyebrow'] ) ) : ?>
				<p class="section-eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></p>
			<?php endif; ?>
			<h1 id="page-intro-heading" class="section-heading mt-3">
				<?php echo esc_html( $args['heading'] ); ?>
			</h1>
			<?php if ( ! empty( $args['description'] ) ) : ?>
				<p class="type-body mt-4 max-w-2xl">
					<?php echo esc_html( $args['description'] ); ?>
				</p>
			<?php endif; ?>
		</div>
	</div>
</section>
