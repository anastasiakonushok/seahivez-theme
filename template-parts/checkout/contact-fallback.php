<?php
/**
 * Checkout confirmation contact fallback.
 *
 * @package seahivez-theme
 */

$contact   = seahivez_get_social_contact_data();
$whatsapp  = seahivez_get_whatsapp_url();
$phone     = (string) ( $contact['phone'] ?? '' );
$email     = (string) ( $contact['email'] ?? '' );
$has_phone = '' !== $phone;
$has_email = '' !== $email && is_email( $email );

if ( ! $has_phone && ! $has_email && ! $whatsapp ) {
	return;
}
?>

<aside class="checkout-contact-fallback mt-8 rounded-md border border-slate-200 bg-white p-5 sm:p-6">
	<p class="text-sm leading-relaxed text-slate-600">
		<?php esc_html_e( 'If anything went wrong or you do not hear from us within 24 hours, please contact us directly:', 'seahivez-theme' ); ?>
	</p>

	<ul class="checkout-contact-fallback__list mt-5 space-y-4" role="list">
		<?php if ( $has_phone ) : ?>
			<li class="checkout-contact-fallback__item">
				<p class="text-xs font-medium uppercase tracking-[0.12em] text-slate-500"><?php esc_html_e( 'Phone / WhatsApp', 'seahivez-theme' ); ?></p>
				<a class="mt-1 inline-block text-sm font-medium text-navy-900 transition-colors duration-200 hover:text-gold-dark" href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $phone ) ); ?>">
					<?php echo esc_html( $phone ); ?>
				</a>
			</li>
		<?php endif; ?>

		<?php if ( $has_email ) : ?>
			<li class="checkout-contact-fallback__item">
				<p class="text-xs font-medium uppercase tracking-[0.12em] text-slate-500"><?php esc_html_e( 'Email', 'seahivez-theme' ); ?></p>
				<a class="mt-1 inline-block text-sm font-medium text-navy-900 transition-colors duration-200 hover:text-gold-dark" href="<?php echo esc_url( 'mailto:' . $email ); ?>">
					<?php echo esc_html( $email ); ?>
				</a>
			</li>
		<?php endif; ?>
	</ul>

	<?php if ( $whatsapp ) : ?>
		<a class="link-arrow checkout-contact-fallback__whatsapp mt-5 inline-flex text-sm" href="<?php echo esc_url( $whatsapp ); ?>" target="_blank" rel="noopener noreferrer">
			<?php esc_html_e( 'Chat on WhatsApp', 'seahivez-theme' ); ?>
			<?php seahivez_render_link_arrow_icon( 'sm' ); ?>
		</a>
	<?php endif; ?>
</aside>
