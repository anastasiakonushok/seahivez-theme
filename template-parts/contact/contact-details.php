<?php
/**
 * Contact details + social.
 *
 * @package seahivez-theme
 */

$details  = seahivez_get_contact_page_details();
$location = seahivez_get_home_location_data();
?>

<div class="contact-details">
	<?php if ( ! empty( $details['eyebrow'] ) ) : ?>
		<p class="section-eyebrow"><?php echo esc_html( $details['eyebrow'] ); ?></p>
	<?php endif; ?>
	<h2 id="contact-details-heading" class="section-heading mt-3">
		<?php echo esc_html( $details['heading'] ); ?>
	</h2>

	<ul class="mt-8 space-y-4" role="list">
		<?php if ( ! empty( $location['phone'] ) ) : ?>
			<li>
				<p class="type-eyebrow text-gray-500"><?php esc_html_e( 'Phone', 'seahivez-theme' ); ?></p>
				<a class="mt-1 inline-block text-lg font-medium text-navy-900 transition-colors hover:text-gold-dark" href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $location['phone'] ) ); ?>">
					<?php echo esc_html( $location['phone'] ); ?>
				</a>
			</li>
		<?php endif; ?>

		<?php if ( ! empty( $location['email'] ) ) : ?>
			<li>
				<p class="type-eyebrow text-gray-500"><?php esc_html_e( 'Email', 'seahivez-theme' ); ?></p>
				<a class="mt-1 inline-block text-lg font-medium text-navy-900 transition-colors hover:text-gold-dark" href="<?php echo esc_url( 'mailto:' . $location['email'] ); ?>">
					<?php echo esc_html( $location['email'] ); ?>
				</a>
			</li>
		<?php endif; ?>
	</ul>

	<div class="mt-8">
		<?php
		get_template_part(
			'template-parts/components/social-links',
			null,
			array(
				'heading' => $details['social_heading'],
			)
		);
		?>
	</div>

	<div class="mt-10">
		<?php get_template_part( 'template-parts/contact/contact-map' ); ?>
	</div>
</div>
