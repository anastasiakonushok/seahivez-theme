<?php
/**
 * Checkout confirmation screen.
 *
 * @package seahivez-theme
 *
 * @var array<string, mixed> $args Checkout view args.
 */

$confirmation = ! empty( $args['confirmation'] ) && is_array( $args['confirmation'] ) ? $args['confirmation'] : array();
$booking      = ! empty( $confirmation['booking'] ) && is_array( $confirmation['booking'] ) ? $confirmation['booking'] : array();
?>

<section class="checkout-confirmation section-spacing">
	<div class="site-container">
		<div class="checkout-confirmation__grid grid gap-10 lg:grid-cols-[minmax(0,1fr)_minmax(0,420px)] lg:items-start lg:gap-12">
			<div class="checkout-confirmation__message max-w-xl">
				<p class="section-eyebrow"><?php esc_html_e( 'Thank you', 'seahivez-theme' ); ?></p>
				<h1 class="section-heading mt-3"><?php esc_html_e( 'Your charter request has been received', 'seahivez-theme' ); ?></h1>
				<p class="type-body mt-4 text-slate-600">
					<?php esc_html_e( 'We will contact you shortly to confirm availability and booking details.', 'seahivez-theme' ); ?>
				</p>

				<?php get_template_part( 'template-parts/checkout/contact-fallback' ); ?>
			</div>

			<div class="checkout-confirmation__summary">
				<?php
				get_template_part(
					'template-parts/checkout/summary',
					null,
					array(
						'booking' => $booking,
						'heading' => __( 'Booking summary', 'seahivez-theme' ),
					)
				);
				?>
			</div>
		</div>
	</div>
</section>
