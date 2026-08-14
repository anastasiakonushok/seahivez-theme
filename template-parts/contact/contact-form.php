<?php
/**
 * Contact feedback form (no booking — use /booking/ for reservations).
 *
 * @package seahivez-theme
 */
?>

<div class="contact-form">
	<h3 class="text-xl font-semibold text-navy-900"><?php esc_html_e( 'Send a message', 'seahivez-theme' ); ?></h3>
	<p class="mt-2 text-sm text-gray-600">
		<?php esc_html_e( 'Questions about your charter? Write to us — for date reservations use the booking calendar.', 'seahivez-theme' ); ?>
	</p>

	<p class="mt-4">
		<a class="link-arrow inline-flex text-sm" href="<?php echo esc_url( seahivez_get_booking_url() ); ?>">
			<?php esc_html_e( 'Book a charter', 'seahivez-theme' ); ?>
			<?php seahivez_render_link_arrow_icon( 'sm' ); ?>
		</a>
	</p>

	<form class="mt-6 space-y-5" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="seahivez_contact_request">
		<?php wp_nonce_field( 'seahivez_contact_request', 'seahivez_contact_nonce' ); ?>

		<div class="grid gap-5 sm:grid-cols-2">
			<p class="sm:col-span-1">
				<label class="mb-2 block text-sm font-medium text-navy-900" for="contact-name"><?php esc_html_e( 'Name', 'seahivez-theme' ); ?></label>
				<input class="w-full rounded-md border border-gray-200 bg-white px-4 py-3 text-navy-900 outline-none transition focus:border-gold/50" type="text" id="contact-name" name="contact_name" required autocomplete="name">
			</p>
			<p class="sm:col-span-1">
				<label class="mb-2 block text-sm font-medium text-navy-900" for="contact-email"><?php esc_html_e( 'Email', 'seahivez-theme' ); ?></label>
				<input class="w-full rounded-md border border-gray-200 bg-white px-4 py-3 text-navy-900 outline-none transition focus:border-gold/50" type="email" id="contact-email" name="contact_email" required autocomplete="email">
			</p>
		</div>

		<p>
			<label class="mb-2 block text-sm font-medium text-navy-900" for="contact-phone"><?php esc_html_e( 'Phone / WhatsApp', 'seahivez-theme' ); ?></label>
			<input class="w-full rounded-md border border-gray-200 bg-white px-4 py-3 text-navy-900 outline-none transition focus:border-gold/50" type="tel" id="contact-phone" name="contact_phone" autocomplete="tel">
		</p>

		<p>
			<label class="mb-2 block text-sm font-medium text-navy-900" for="contact-message"><?php esc_html_e( 'Message', 'seahivez-theme' ); ?></label>
			<textarea class="min-h-[140px] w-full rounded-md border border-gray-200 bg-white px-4 py-3 text-navy-900 outline-none transition focus:border-gold/50" id="contact-message" name="contact_message" rows="5" required placeholder="<?php esc_attr_e( 'How can we help?', 'seahivez-theme' ); ?>"></textarea>
		</p>

		<button class="btn-primary" type="submit">
			<?php esc_html_e( 'Send message', 'seahivez-theme' ); ?>
		</button>
	</form>
</div>
