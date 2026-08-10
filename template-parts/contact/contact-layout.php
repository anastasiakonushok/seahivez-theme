<?php
/**
 * Contact page two-column layout.
 *
 * @package seahivez-theme
 */

$location = seahivez_get_home_location_data();
$port     = seahivez_get_port_location();
?>

<section class="contact-page section-spacing bg-warm-white" aria-labelledby="contact-details-heading">
	<div class="site-container">
		<div class="grid gap-12 lg:grid-cols-2 lg:gap-16">
			<div class="reveal">
				<?php get_template_part( 'template-parts/contact/contact-details' ); ?>
			</div>

			<div class="reveal reveal-delay-1 space-y-10">
				<?php get_template_part( 'template-parts/contact/contact-map' ); ?>
				<?php get_template_part( 'template-parts/contact/contact-form' ); ?>
			</div>
		</div>
	</div>
</section>
