<?php
/**
 * Experiences page — package overview cards linking to single package pages.
 *
 * @package seahivez-theme
 */

$experiences = seahivez_get_experiences_page_packages();

if ( empty( $experiences ) ) {
	return;
}
?>

<section class="experiences-page section-spacing bg-warm-white" aria-label="<?php esc_attr_e( 'Charter experiences', 'seahivez-theme' ); ?>">
	<div class="site-container">
		<div class="experiences-page__grid grid gap-8 md:grid-cols-2 lg:grid-cols-3">
			<?php foreach ( $experiences as $index => $experience ) : ?>
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
