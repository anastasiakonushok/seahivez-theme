<?php
/**
 * Extras page — bottom booking CTA with dual actions.
 *
 * @package seahivez-theme
 */

$data = ! empty( $args['sections'] ) && is_array( $args['sections'] )
	? $args['sections']
	: seahivez_get_extras_page_sections();
$cta  = ! empty( $data['booking_cta'] ) && is_array( $data['booking_cta'] )
	? $data['booking_cta']
	: seahivez_get_extras_booking_cta();
?>

<section class="extras-booking-cta section-spacing bg-warm-white" aria-labelledby="extras-booking-cta-heading">
	<div class="site-container">
		<div class="extras-booking-cta__inner reveal rounded-md border border-slate-200 bg-white px-6 py-10 text-center md:px-12 md:py-12">
			<h2 id="extras-booking-cta-heading" class="section-heading">
				<?php echo esc_html( (string) ( $cta['heading'] ?? '' ) ); ?>
			</h2>

			<?php if ( ! empty( $cta['description'] ) ) : ?>
				<p class="type-body mx-auto mt-4 max-w-2xl text-slate-600">
					<?php echo esc_html( (string) $cta['description'] ); ?>
				</p>
			<?php endif; ?>

			<div class="extras-booking-cta__actions mt-8 flex flex-col items-stretch justify-center gap-3 sm:flex-row sm:items-center">
				<?php if ( ! empty( $cta['primary_url'] ) && ! empty( $cta['primary_label'] ) ) : ?>
					<a class="btn-primary" href="<?php echo esc_url( (string) $cta['primary_url'] ); ?>">
						<?php echo esc_html( (string) $cta['primary_label'] ); ?>
					</a>
				<?php endif; ?>

				<?php if ( ! empty( $cta['secondary_url'] ) && ! empty( $cta['secondary_label'] ) ) : ?>
					<a class="btn-outline" href="<?php echo esc_url( (string) $cta['secondary_url'] ); ?>">
						<?php echo esc_html( (string) $cta['secondary_label'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
