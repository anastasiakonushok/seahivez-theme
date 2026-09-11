<?php
/**
 * Checkout page layout.
 *
 * @package seahivez-theme
 *
 * @var array<string, mixed> $args Checkout view args.
 */

$booking = ! empty( $args['booking'] ) && is_array( $args['booking'] ) ? $args['booking'] : array();
?>

<section class="checkout-layout section-spacing">
	<div class="site-container">
		<div class="checkout-layout__grid grid gap-10 lg:grid-cols-[minmax(0,1fr)_minmax(0,420px)] lg:items-start lg:gap-12">
			<div class="checkout-layout__form">
				<?php get_template_part( 'template-parts/checkout/form', null, $args ); ?>
			</div>

			<div class="checkout-layout__summary lg:sticky lg:top-28">
				<?php get_template_part( 'template-parts/checkout/summary', null, array( 'booking' => $booking ) ); ?>
			</div>
		</div>
	</div>
</section>
