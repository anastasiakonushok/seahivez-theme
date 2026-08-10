<?php
/**
 * Contact request form (markup ready for future form plugin / ACF).
 *
 * @package seahivez-theme
 */

$experiences = seahivez_get_home_experiences();
?>

<div class="contact-form">
	<h3 class="text-xl font-semibold text-navy-900"><?php esc_html_e( 'Send a request', 'seahivez-theme' ); ?></h3>

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

		<div class="grid gap-5 sm:grid-cols-2">
			<p>
				<label class="mb-2 block text-sm font-medium text-navy-900" for="contact-phone"><?php esc_html_e( 'Phone / WhatsApp', 'seahivez-theme' ); ?></label>
				<input class="w-full rounded-md border border-gray-200 bg-white px-4 py-3 text-navy-900 outline-none transition focus:border-gold/50" type="tel" id="contact-phone" name="contact_phone" autocomplete="tel">
			</p>
			<p>
				<label class="mb-2 block text-sm font-medium text-navy-900" for="contact-date"><?php esc_html_e( 'Preferred date', 'seahivez-theme' ); ?></label>
				<input class="w-full rounded-md border border-gray-200 bg-white px-4 py-3 text-navy-900 outline-none transition focus:border-gold/50" type="date" id="contact-date" name="contact_date">
			</p>
		</div>

		<div class="grid gap-5 sm:grid-cols-2">
			<p>
				<label class="mb-2 block text-sm font-medium text-navy-900" for="contact-experience"><?php esc_html_e( 'Experience', 'seahivez-theme' ); ?></label>
				<select class="w-full rounded-md border border-gray-200 bg-white px-4 py-3 text-navy-900 outline-none transition focus:border-gold/50" id="contact-experience" name="contact_experience">
					<option value=""><?php esc_html_e( 'Select experience', 'seahivez-theme' ); ?></option>
					<?php foreach ( $experiences as $experience ) : ?>
						<option value="<?php echo esc_attr( $experience['title'] ); ?>"><?php echo esc_html( $experience['title'] ); ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<label class="mb-2 block text-sm font-medium text-navy-900" for="contact-guests"><?php esc_html_e( 'Guests', 'seahivez-theme' ); ?></label>
				<input class="w-full rounded-md border border-gray-200 bg-white px-4 py-3 text-navy-900 outline-none transition focus:border-gold/50" type="number" id="contact-guests" name="contact_guests" min="1" max="10" placeholder="1–10">
			</p>
		</div>

		<p>
			<label class="mb-2 block text-sm font-medium text-navy-900" for="contact-message"><?php esc_html_e( 'Message', 'seahivez-theme' ); ?></label>
			<textarea class="min-h-[140px] w-full rounded-md border border-gray-200 bg-white px-4 py-3 text-navy-900 outline-none transition focus:border-gold/50" id="contact-message" name="contact_message" rows="5" required></textarea>
		</p>

		<button class="btn-primary" type="submit">
			<?php esc_html_e( 'Send request', 'seahivez-theme' ); ?>
		</button>
	</form>
</div>
