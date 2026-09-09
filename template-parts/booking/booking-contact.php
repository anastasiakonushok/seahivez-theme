<?php
/**
 * Booking page contact details and social links.
 *
 * @package seahivez-theme
 */

$contact = seahivez_get_social_contact_data();
$sidebar = seahivez_get_booking_sidebar_content();
?>

<div class="booking-contact border-t border-gray-200 pt-8">
	<?php if ( ! empty( $sidebar['contact_heading'] ) ) : ?>
		<h2 class="text-xl font-semibold text-navy-900">
			<?php echo esc_html( $sidebar['contact_heading'] ); ?>
		</h2>
	<?php endif; ?>

	<ul class="mt-5 space-y-4" role="list">
		<?php if ( ! empty( $contact['phone'] ) ) : ?>
			<li>
				<p class="type-eyebrow text-gray-500"><?php esc_html_e( 'Phone', 'seahivez-theme' ); ?></p>
				<a class="mt-1 inline-block font-medium text-navy-900 transition-colors hover:text-gold-dark" href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $contact['phone'] ) ); ?>">
					<?php echo esc_html( $contact['phone'] ); ?>
				</a>
			</li>
		<?php endif; ?>

		<?php if ( ! empty( $contact['email'] ) ) : ?>
			<li>
				<p class="type-eyebrow text-gray-500"><?php esc_html_e( 'Email', 'seahivez-theme' ); ?></p>
				<a class="mt-1 inline-block font-medium text-navy-900 transition-colors hover:text-gold-dark" href="<?php echo esc_url( 'mailto:' . $contact['email'] ); ?>">
					<?php echo esc_html( $contact['email'] ); ?>
				</a>
			</li>
		<?php endif; ?>
	</ul>

	<?php
	$whatsapp = seahivez_get_whatsapp_url();
	if ( $whatsapp && ! empty( $sidebar['whatsapp_label'] ) ) :
		?>
		<a class="link-arrow mt-6 inline-flex" href="<?php echo esc_url( $whatsapp ); ?>" target="_blank" rel="noopener noreferrer">
			<?php echo esc_html( $sidebar['whatsapp_label'] ); ?>
			<?php seahivez_render_link_arrow_icon( 'sm' ); ?>
		</a>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/components/weather-card' ); ?>

	<div class="mt-8">
		<?php
		get_template_part(
			'template-parts/components/social-links',
			null,
			array(
				'heading' => $sidebar['social_heading'],
				'class'   => 'booking-contact__social',
			)
		);
		?>
	</div>
</div>
