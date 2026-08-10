<?php
/**
 * SuperSaaS booking widget placeholder / future embed.
 *
 * @package seahivez-theme
 */

$widget_html = apply_filters( 'seahivez_booking_widget_html', '' );
?>

<div class="booking-widget" id="booking-widget">
	<h2 class="section-heading text-[28px] md:text-[32px]">
		<?php esc_html_e( 'Booking', 'seahivez-theme' ); ?>
	</h2>
	<p class="type-body mt-3 max-w-xl">
		<?php esc_html_e( 'Select your experience and preferred date. The live booking calendar will appear here once SuperSaaS is connected.', 'seahivez-theme' ); ?>
	</p>

	<div class="booking-widget__frame mt-8 min-h-[320px] rounded-md border border-dashed border-gray-300 bg-sand-50 p-8">
		<?php if ( $widget_html ) : ?>
			<?php echo $widget_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Filtered embed markup. ?>
		<?php else : ?>
			<div class="flex h-full min-h-[260px] flex-col items-center justify-center text-center">
				<p class="text-sm font-medium uppercase tracking-widest text-gray-500">
					<?php esc_html_e( 'SuperSaaS widget placeholder', 'seahivez-theme' ); ?>
				</p>
				<p class="mt-3 max-w-sm text-sm text-gray-500">
					<?php esc_html_e( 'No live availability is shown in development. Configure the booking embed via the seahivez_booking_widget_html filter.', 'seahivez-theme' ); ?>
				</p>
				<a class="btn-outline mt-6" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
					<?php esc_html_e( 'Contact us instead', 'seahivez-theme' ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>
