<?php
/**
 * SuperSaaS booking calendar embed.
 *
 * @package seahivez-theme
 */

$init_script = seahivez_get_supersaas_init_script();
$widget      = seahivez_get_booking_widget_content();
?>

<div class="booking-widget" id="booking-widget">
	<?php if ( ! empty( $widget['heading'] ) ) : ?>
		<h2 class="section-heading text-[28px] md:text-[32px]">
			<?php echo esc_html( $widget['heading'] ); ?>
		</h2>
	<?php endif; ?>
	<?php if ( ! empty( $widget['description'] ) ) : ?>
		<p class="type-body mt-3 max-w-xl">
			<?php echo esc_html( $widget['description'] ); ?>
		</p>
	<?php endif; ?>

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
