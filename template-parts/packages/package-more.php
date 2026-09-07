<?php
/**
 * Other packages on a package single page.
 *
 * @package seahivez-theme
 */

$others = seahivez_get_other_package_cards( get_the_ID() );

if ( empty( $others ) ) {
	return;
}
?>

<section class="package-more section-spacing bg-sand-50" aria-labelledby="package-more-heading">
	<div class="site-container">
		<div class="reveal max-w-2xl">
			<p class="section-eyebrow"><?php esc_html_e( 'Charter Services', 'seahivez-theme' ); ?></p>
			<h2 id="package-more-heading" class="section-heading mt-3">
				<?php esc_html_e( 'More experiences', 'seahivez-theme' ); ?>
			</h2>
		</div>

		<div class="package-more__grid mt-10 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
			<?php foreach ( $others as $index => $experience ) : ?>
				<div class="reveal h-full<?php echo $index ? ' reveal-delay-' . min( $index, 2 ) : ''; ?>">
					<?php
					get_template_part(
						'template-parts/cards/experience-card',
						null,
						array_merge(
							$experience,
							array(
								'cta_label' => __( 'View package', 'seahivez-theme' ),
							)
						)
					);
					?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
