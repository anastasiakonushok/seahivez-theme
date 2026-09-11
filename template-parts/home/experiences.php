<?php
/**
 * Homepage experiences section.
 *
 * @package seahivez-theme
 */

$section_args  = ! empty( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : array();
$experiences   = ! empty( $section_args['experiences'] ) ? $section_args['experiences'] : seahivez_get_packages_for_display();
$experiences   = seahivez_attach_home_charter_calculator_data( $experiences );
$calculator_configs = seahivez_get_home_charter_calculator_configs( $experiences );
$checkout_configs   = seahivez_get_charter_calculator_configs_for_cards( $experiences, true );
$eyebrow       = ! empty( $section_args['eyebrow'] ) ? $section_args['eyebrow'] : __( 'Charter Services', 'seahivez-theme' );
$heading       = ! empty( $section_args['heading'] ) ? $section_args['heading'] : __( 'Choose your experience', 'seahivez-theme' );

if ( empty( $experiences ) ) {
	return;
}
?>

<section class="experiences section-spacing bg-warm-white" id="experiences" aria-labelledby="experiences-heading">
	<div class="site-container">
		<div class="reveal max-w-2xl">
			<?php if ( $eyebrow ) : ?>
				<p class="section-eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<?php endif; ?>
			<?php if ( $heading ) : ?>
				<h2 id="experiences-heading" class="section-heading mt-3">
					<?php echo esc_html( $heading ); ?>
				</h2>
			<?php endif; ?>
		</div>

		<div class="experiences__grid mt-10 grid items-stretch gap-8 md:grid-cols-2 lg:grid-cols-3">
			<?php foreach ( $experiences as $index => $experience ) : ?>
				<div class="reveal h-full<?php echo $index ? ' reveal-delay-' . min( $index, 2 ) : ''; ?>">
					<?php
					get_template_part(
						'template-parts/cards/experience-card',
						null,
						array_merge(
							$experience,
							array(
								'is_homepage' => true,
							)
						)
					);
					?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<?php if ( ! empty( $checkout_configs ) ) : ?>
		<script type="application/json" id="seahivez-charter-checkout-config">
			<?php echo wp_json_encode( $checkout_configs ); ?>
		</script>
	<?php endif; ?>

	<?php if ( ! empty( $calculator_configs ) ) : ?>
		<script type="application/json" id="seahivez-charter-calculator-config">
			<?php echo wp_json_encode( $calculator_configs ); ?>
		</script>
	<?php endif; ?>
</section>
