<?php
/**
 * Checkout booking request form.
 *
 * @package seahivez-theme
 *
 * @var array<string, mixed> $args Checkout view args.
 */

$errors       = ! empty( $args['errors'] ) && is_array( $args['errors'] ) ? $args['errors'] : array();
$booking      = ! empty( $args['booking'] ) && is_array( $args['booking'] ) ? $args['booking'] : array();
$package_key  = seahivez_get_checkout_booking_package_key( $booking );
$requires_slot = seahivez_checkout_requires_time_slot( $package_key );
$time_slots   = $requires_slot ? seahivez_get_half_day_time_slots() : array();
?>

<div class="checkout-form">
	<div class="checkout-form__intro max-w-xl">
		<p class="section-eyebrow"><?php esc_html_e( 'Booking request', 'seahivez-theme' ); ?></p>
		<h1 class="section-heading mt-3"><?php esc_html_e( 'Complete your charter request', 'seahivez-theme' ); ?></h1>
		<p class="type-body mt-4 text-slate-600">
			<?php esc_html_e( 'Share your details and we will contact you to confirm availability. No payment is taken at this stage.', 'seahivez-theme' ); ?>
		</p>
	</div>

	<?php if ( ! empty( $args['error'] ) ) : ?>
		<p class="checkout-form__notice mt-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
			<?php
			if ( 'expired' === $args['error'] ) {
				esc_html_e( 'Your charter selection has expired. Please choose your package again.', 'seahivez-theme' );
			} elseif ( 'save' === $args['error'] ) {
				esc_html_e( 'We could not save your request. Please try again.', 'seahivez-theme' );
			} else {
				esc_html_e( 'Something went wrong. Please try again.', 'seahivez-theme' );
			}
			?>
		</p>
	<?php endif; ?>

	<form class="checkout-form__fields mt-8 space-y-5" method="post" action="<?php echo esc_url( seahivez_get_checkout_url() ); ?>" novalidate>
		<?php wp_nonce_field( 'seahivez_checkout_submit', 'seahivez_checkout_submit_nonce' ); ?>
		<input type="hidden" name="seahivez_checkout_action" value="submit">

		<div class="checkout-field">
			<label class="checkout-field__label" for="customer_name"><?php esc_html_e( 'Full name', 'seahivez-theme' ); ?> <span aria-hidden="true">*</span></label>
			<input class="checkout-field__input<?php echo in_array( 'name', $errors, true ) ? ' is-invalid' : ''; ?>" type="text" id="customer_name" name="customer_name" required autocomplete="name">
		</div>

		<div class="checkout-field">
			<label class="checkout-field__label" for="customer_email"><?php esc_html_e( 'Email', 'seahivez-theme' ); ?> <span aria-hidden="true">*</span></label>
			<input class="checkout-field__input<?php echo in_array( 'email', $errors, true ) ? ' is-invalid' : ''; ?>" type="email" id="customer_email" name="customer_email" required autocomplete="email">
		</div>

		<div class="checkout-field checkout-field--phone" data-checkout-phone>
			<label class="checkout-field__label" for="customer_phone"><?php esc_html_e( 'Phone / WhatsApp', 'seahivez-theme' ); ?> <span aria-hidden="true">*</span></label>
			<input class="checkout-field__input--phone<?php echo in_array( 'phone', $errors, true ) ? ' is-invalid' : ''; ?>" type="tel" id="customer_phone" name="customer_phone" required autocomplete="tel" inputmode="tel">
		</div>

		<div class="checkout-field-grid grid gap-5 sm:grid-cols-2">
			<div class="checkout-field">
				<label class="checkout-field__label" for="customer_date"><?php esc_html_e( 'Preferred date', 'seahivez-theme' ); ?> <span aria-hidden="true">*</span></label>
				<input class="checkout-field__input<?php echo in_array( 'date', $errors, true ) ? ' is-invalid' : ''; ?>" type="date" id="customer_date" name="customer_date" required>
			</div>

			<div class="checkout-field">
				<label class="checkout-field__label" for="customer_guests"><?php esc_html_e( 'Number of guests', 'seahivez-theme' ); ?> <span aria-hidden="true">*</span></label>
				<input class="checkout-field__input" type="number" id="customer_guests" name="customer_guests" min="1" max="<?php echo esc_attr( (string) seahivez_get_yacht_max_guests() ); ?>" value="1" required>
			</div>
		</div>

		<?php if ( $requires_slot && ! empty( $time_slots ) ) : ?>
			<div class="checkout-field checkout-field--time-slots<?php echo in_array( 'time_slot', $errors, true ) ? ' is-invalid' : ''; ?>">
				<p class="checkout-field__label" id="customer_time_slot_label">
					<?php esc_html_e( 'Preferred time slot', 'seahivez-theme' ); ?> <span aria-hidden="true">*</span>
				</p>
				<div class="checkout-time-slots mt-2 flex flex-wrap gap-2" role="radiogroup" aria-labelledby="customer_time_slot_label">
					<?php foreach ( $time_slots as $slot_id => $slot_label ) : ?>
						<label class="checkout-time-slot">
							<input
								class="checkout-time-slot__input"
								type="radio"
								name="customer_time_slot"
								value="<?php echo esc_attr( $slot_id ); ?>"
								required
							>
							<span class="checkout-time-slot__label"><?php echo esc_html( $slot_label ); ?></span>
						</label>
					<?php endforeach; ?>
				</div>
			</div>
		<?php else : ?>
			<div class="checkout-field">
				<label class="checkout-field__label" for="customer_preferred_time"><?php esc_html_e( 'Preferred time (optional)', 'seahivez-theme' ); ?></label>
				<input class="checkout-field__input" type="text" id="customer_preferred_time" name="customer_preferred_time" placeholder="<?php esc_attr_e( 'e.g. Sunset departure', 'seahivez-theme' ); ?>">
			</div>
		<?php endif; ?>

		<div class="checkout-field">
			<label class="checkout-field__label" for="customer_message"><?php esc_html_e( 'Message / special requests', 'seahivez-theme' ); ?></label>
			<textarea class="checkout-field__textarea" id="customer_message" name="customer_message" rows="4"></textarea>
		</div>

		<?php
		/**
		 * Future payment methods (Stripe card, pay on arrival) can be rendered here
		 * before the submit action via seahivez_render_checkout_payment_methods().
		 */
		?>

		<div class="checkout-form__actions pt-2">
			<button type="submit" class="btn-primary checkout-form__submit w-full sm:w-auto">
				<span><?php esc_html_e( 'Send booking request', 'seahivez-theme' ); ?></span>
				<?php seahivez_render_link_arrow_icon( 'sm' ); ?>
			</button>
		</div>
	</form>
</div>
