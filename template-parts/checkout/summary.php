<?php
/**
 * Checkout booking summary.
 *
 * @package seahivez-theme
 *
 * @var array<string, mixed> $args Checkout view args.
 */

$booking = ! empty( $args['booking'] ) && is_array( $args['booking'] ) ? $args['booking'] : array();

if ( empty( $booking ) ) {
	return;
}

$heading      = ! empty( $args['heading'] ) ? (string) $args['heading'] : __( 'Your booking', 'seahivez-theme' );
$package      = ! empty( $booking['package'] ) && is_array( $booking['package'] ) ? $booking['package'] : array();
$route        = ! empty( $booking['route'] ) && is_array( $booking['route'] ) ? $booking['route'] : array();
$extras_lines = ! empty( $booking['extras_lines'] ) && is_array( $booking['extras_lines'] ) ? $booking['extras_lines'] : array();
$food_lines   = ! empty( $booking['food_lines'] ) && is_array( $booking['food_lines'] ) ? $booking['food_lines'] : array();
$price_lines  = ! empty( $booking['price_lines'] ) && is_array( $booking['price_lines'] ) ? $booking['price_lines'] : array();
?>

<aside class="checkout-summary rounded-md border border-slate-200 bg-white p-6 lg:p-8" aria-labelledby="checkout-summary-heading">
	<h2 id="checkout-summary-heading" class="checkout-summary__title text-xs font-medium uppercase tracking-[0.14em] text-navy-900">
		<?php echo esc_html( $heading ); ?>
	</h2>

	<div class="checkout-summary__package mt-6">
		<p class="text-lg font-medium text-navy-900"><?php echo esc_html( (string) ( $package['title'] ?? '' ) ); ?></p>
		<?php if ( ! empty( $package['duration'] ) ) : ?>
			<p class="mt-1 text-sm text-slate-600"><?php echo esc_html( (string) $package['duration'] ); ?></p>
		<?php endif; ?>
		<?php if ( ! empty( $package['time_slot'] ) ) : ?>
			<p class="mt-1 text-sm text-slate-600"><?php echo esc_html( (string) $package['time_slot'] ); ?></p>
		<?php endif; ?>
	</div>

	<?php if ( ! empty( $route['label'] ) || ! empty( $route['path'] ) ) : ?>
		<div class="checkout-summary__section mt-8 border-t border-slate-200 pt-6">
			<p class="text-xs font-medium uppercase tracking-[0.12em] text-slate-500"><?php esc_html_e( 'Route', 'seahivez-theme' ); ?></p>
			<?php if ( ! empty( $route['label'] ) ) : ?>
				<p class="mt-2 text-sm font-medium text-navy-900"><?php echo esc_html( (string) $route['label'] ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $route['path'] ) ) : ?>
				<p class="mt-2 text-sm leading-relaxed text-slate-600"><?php echo esc_html( (string) $route['path'] ); ?></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $extras_lines ) ) : ?>
		<div class="checkout-summary__section mt-8 border-t border-slate-200 pt-6">
			<p class="text-xs font-medium uppercase tracking-[0.12em] text-slate-500"><?php esc_html_e( 'Extras', 'seahivez-theme' ); ?></p>
			<ul class="checkout-summary__lines mt-3 space-y-2" role="list">
				<?php foreach ( $extras_lines as $line ) : ?>
					<li class="checkout-summary__line flex items-baseline justify-between gap-4 text-sm">
						<span class="text-slate-700"><?php echo esc_html( (string) ( $line['label'] ?? '' ) ); ?></span>
						<span class="shrink-0 font-medium tabular-nums text-navy-900"><?php echo esc_html( seahivez_format_checkout_amount( (int) ( $line['amount'] ?? 0 ) ) ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $food_lines ) ) : ?>
		<div class="checkout-summary__section mt-8 border-t border-slate-200 pt-6">
			<p class="text-xs font-medium uppercase tracking-[0.12em] text-slate-500"><?php esc_html_e( 'Food & drinks', 'seahivez-theme' ); ?></p>
			<ul class="checkout-summary__lines mt-3 space-y-2" role="list">
				<?php foreach ( $food_lines as $line ) : ?>
					<li class="checkout-summary__line flex items-baseline justify-between gap-4 text-sm">
						<span class="text-slate-700">
							<?php
							echo esc_html(
								sprintf(
									/* translators: 1: label, 2: quantity, 3: unit */
									__( '%1$s · %2$d %3$s', 'seahivez-theme' ),
									(string) ( $line['label'] ?? '' ),
									(int) ( $line['qty'] ?? 0 ),
									(string) ( $line['unit'] ?? '' )
								)
							);
							?>
						</span>
						<span class="shrink-0 font-medium tabular-nums text-navy-900"><?php echo esc_html( seahivez_format_checkout_amount( (int) ( $line['amount'] ?? 0 ) ) ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<div class="checkout-summary__section mt-8 border-t border-slate-200 pt-6">
		<p class="text-xs font-medium uppercase tracking-[0.12em] text-slate-500"><?php esc_html_e( 'Price', 'seahivez-theme' ); ?></p>
		<ul class="checkout-summary__lines mt-3 space-y-2" role="list">
			<?php foreach ( $price_lines as $line ) : ?>
				<li class="checkout-summary__line flex items-baseline justify-between gap-4 text-sm">
					<span class="text-slate-700"><?php echo esc_html( (string) ( $line['label'] ?? '' ) ); ?></span>
					<span class="shrink-0 font-medium tabular-nums text-navy-900"><?php echo esc_html( seahivez_format_checkout_amount( (int) ( $line['amount'] ?? 0 ) ) ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="checkout-summary__total mt-4 flex items-baseline justify-between gap-4 border-t border-slate-200 pt-4">
			<span class="text-sm font-medium uppercase tracking-[0.1em] text-navy-900"><?php esc_html_e( 'Total', 'seahivez-theme' ); ?></span>
			<span class="text-2xl font-medium leading-none tracking-tight text-navy-900 tabular-nums">
				<?php echo esc_html( seahivez_format_checkout_amount( (int) ( $booking['charter_total'] ?? 0 ) ) ); ?>
			</span>
		</div>
	</div>

	<div class="checkout-summary__deposit mt-6 border-t border-slate-200 pt-6">
		<div class="flex items-baseline justify-between gap-4 text-sm">
			<span class="text-slate-700"><?php esc_html_e( 'Refundable security deposit', 'seahivez-theme' ); ?></span>
			<span class="shrink-0 font-medium tabular-nums text-navy-900">
				<?php echo esc_html( seahivez_format_checkout_amount( (int) ( $booking['deposit'] ?? seahivez_get_charter_security_deposit() ) ) ); ?>
			</span>
		</div>
		<p class="mt-2 text-sm text-slate-500">
			<?php esc_html_e( 'Paid separately at the port.', 'seahivez-theme' ); ?>
		</p>
	</div>
</aside>
