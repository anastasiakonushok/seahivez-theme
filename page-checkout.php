<?php
/**
 * Template Name: Checkout
 *
 * Charter booking request checkout (no payment gateway yet).
 *
 * @package seahivez-theme
 */

get_header();

$checkout = seahivez_get_checkout_view_data();
?>

<main id="primary" class="site-main checkout-page flex-1 bg-warm-white">
	<?php if ( 'confirmed' === ( $checkout['mode'] ?? '' ) ) : ?>
		<?php get_template_part( 'template-parts/checkout/confirmation', null, $checkout ); ?>
	<?php elseif ( 'checkout' === ( $checkout['mode'] ?? '' ) ) : ?>
		<?php get_template_part( 'template-parts/checkout/layout', null, $checkout ); ?>
	<?php else : ?>
		<?php get_template_part( 'template-parts/checkout/empty', null, $checkout ); ?>
	<?php endif; ?>
</main>

<?php
get_footer();
