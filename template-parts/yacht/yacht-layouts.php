<?php
/**
 * Yacht layout spreads — deck plans with feature lists.
 *
 * @package seahivez-theme
 */

$sections = seahivez_get_yacht_layout_sections();

if ( empty( $sections ) ) {
	return;
}
?>

<?php foreach ( $sections as $index => $section ) : ?>
	<?php
	$section_id = ! empty( $section['id'] ) ? $section['id'] : 'layout-' . $index;
	$plan       = isset( $section['plan'] ) ? $section['plan'] : array();
	$items      = isset( $section['items'] ) ? $section['items'] : array();
	$frame      = ! empty( $plan['frame'] ) ? $plan['frame'] : 'light';
	$bg         = ! empty( $section['bg'] ) ? $section['bg'] : 'bg-warm-white';
	?>

	<section
		class="yacht-layout section-spacing <?php echo esc_attr( $bg ); ?>"
		aria-labelledby="yacht-layout-<?php echo esc_attr( $section_id ); ?>"
	>
		<div class="site-container">
			<div class="yacht-layout__grid grid items-center gap-10 lg:grid-cols-2 lg:gap-16 xl:gap-20">
				<?php if ( ! empty( $plan['path'] ) ) : ?>
					<div class="yacht-layout__plan reveal order-2 lg:order-1">
						<div class="yacht-layout__plan-frame yacht-layout__plan-frame--<?php echo esc_attr( $frame ); ?>">
							<img
								class="yacht-layout__plan-image"
								src="<?php echo esc_url( seahivez_get_theme_image_uri( $plan['path'] ) ); ?>"
								alt="<?php echo esc_attr( $plan['alt'] ?? '' ); ?>"
								width="480"
								height="720"
								loading="lazy"
								decoding="async"
							>
						</div>
					</div>
				<?php endif; ?>

				<div class="yacht-layout__content reveal reveal-delay-1 order-1 lg:order-2">
					<p class="section-eyebrow"><?php echo esc_html( $section['eyebrow'] ?? '' ); ?></p>
					<h2 id="yacht-layout-<?php echo esc_attr( $section_id ); ?>" class="section-heading mt-3">
						<?php echo esc_html( $section['heading'] ?? '' ); ?>
					</h2>
					<div class="yacht-layout__heading-rule" aria-hidden="true"></div>

					<?php if ( ! empty( $section['description'] ) ) : ?>
						<p class="type-body mt-6 max-w-lg">
							<?php echo esc_html( $section['description'] ); ?>
						</p>
					<?php endif; ?>

					<?php if ( ! empty( $items ) ) : ?>
						<div class="yacht-layout__features">
							<?php if ( ! empty( $section['list_heading'] ) ) : ?>
								<h3 class="yacht-layout__features-title">
									<?php echo esc_html( $section['list_heading'] ); ?>
								</h3>
							<?php endif; ?>

							<ul class="yacht-layout__features-list">
								<?php foreach ( $items as $item ) : ?>
									<li class="yacht-layout__feature">
										<span class="yacht-layout__feature-marker" aria-hidden="true"></span>
										<span><?php echo esc_html( $item ); ?></span>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
<?php endforeach; ?>
