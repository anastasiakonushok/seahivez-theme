<?php
/**
 * SuperSaaS booking calendar embed.
 *
 * @package seahivez-theme
 */

$init_script = seahivez_get_supersaas_init_script();
?>

<div class="booking-widget" id="booking-widget">
	<h2 class="section-heading text-[28px] md:text-[32px]">
		<?php esc_html_e( 'Book Your Mallorca Yacht Rental', 'seahivez-theme' ); ?>
	</h2>
	<p class="type-body mt-3 max-w-xl">
		<?php esc_html_e( 'Choose your experience and preferred date — availability updates in real time.', 'seahivez-theme' ); ?>
	</p>

	<div class="booking-widget__frame mt-8">
		<?php if ( $init_script ) : ?>
			<script class="supersaas-widget">
				<?php echo $init_script; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built from wp_json_encode(). ?>
			</script>
		<?php else : ?>
			<div class="booking-widget__placeholder flex min-h-[320px] flex-col items-center justify-center rounded-md border border-dashed border-gray-300 bg-sand-50 p-8 text-center">
				<p class="text-sm font-medium uppercase tracking-widest text-gray-500">
					<?php esc_html_e( 'Booking calendar unavailable', 'seahivez-theme' ); ?>
				</p>
				<a class="btn-outline mt-6" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
					<?php esc_html_e( 'Contact us', 'seahivez-theme' ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>
