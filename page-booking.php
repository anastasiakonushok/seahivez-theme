<?php
/**
 * Template Name: Booking
 *
 * @package seahivez-theme
 */

get_header();

get_template_part(
	'template-parts/page/page-hero',
	null,
	seahivez_get_page_hero_defaults(
		array(
			'eyebrow'     => __( 'Book your experience', 'seahivez-theme' ),
			'heading'     => __( 'Your day on the Mediterranean starts here', 'seahivez-theme' ),
			'description' => __( 'Choose your charter style and preferred date. Availability is confirmed by our team before departure.', 'seahivez-theme' ),
			'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/2.jpg' ),
			'image_alt'   => __( 'Book a SeaHivez charter', 'seahivez-theme' ),
			'compact'     => true,
		)
	)
);
?>

<section class="booking-page section-spacing bg-warm-white">
	<div class="site-container">
		<div class="grid gap-12 lg:grid-cols-[minmax(0,1.2fr)_minmax(0,1fr)] lg:gap-16">
			<div class="reveal">
				<?php get_template_part( 'template-parts/booking/booking-widget' ); ?>
			</div>

			<div class="reveal reveal-delay-1 space-y-10">
				<div>
					<h2 class="text-xl font-semibold text-navy-900"><?php esc_html_e( 'Charter packages', 'seahivez-theme' ); ?></h2>
					<ul class="mt-5 divide-y divide-gray-200 border-y border-gray-200" role="list">
						<?php foreach ( seahivez_get_home_experiences() as $experience ) : ?>
							<li class="flex items-baseline justify-between gap-4 py-4">
								<span class="font-medium text-navy-900"><?php echo esc_html( $experience['title'] ); ?></span>
								<span class="text-lg font-semibold text-navy-900"><?php echo esc_html( seahivez_format_extra_price_label( $experience['price'], false ) ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>

				<div>
					<h2 class="text-xl font-semibold text-navy-900"><?php esc_html_e( 'What happens next?', 'seahivez-theme' ); ?></h2>
					<ol class="mt-5 space-y-5">
						<?php foreach ( seahivez_get_booking_steps() as $index => $step ) : ?>
							<li class="flex gap-4">
								<span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-gray-200 text-sm font-semibold text-navy-900">
									<?php echo esc_html( (string) ( $index + 1 ) ); ?>
								</span>
								<div>
									<p class="font-medium text-navy-900"><?php echo esc_html( $step['title'] ); ?></p>
									<p class="mt-1 text-sm text-gray-600"><?php echo esc_html( $step['description'] ); ?></p>
								</div>
							</li>
						<?php endforeach; ?>
					</ol>
				</div>

				<?php
				$whatsapp = seahivez_get_whatsapp_url();
				if ( $whatsapp ) :
					?>
					<a class="link-arrow inline-flex" href="<?php echo esc_url( $whatsapp ); ?>" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'Chat on WhatsApp', 'seahivez-theme' ); ?>
						<?php seahivez_render_link_arrow_icon( 'sm' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
