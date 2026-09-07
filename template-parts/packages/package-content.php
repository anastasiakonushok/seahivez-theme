<?php
/**
 * Package single — details, inclusions, booking CTA.
 *
 * @package seahivez-theme
 */

$package_id  = get_the_ID();
$card        = seahivez_get_package_card_data( $package_id );
$price_label = seahivez_format_extra_price_label( $card['price'], false );
$included    = seahivez_get_package_included_items( $package_id );
$booking_url = ! empty( $card['booking_url'] ) ? $card['booking_url'] : seahivez_get_booking_url();
$experiences = get_page_by_path( 'experiences' );
$back_url    = $experiences ? get_permalink( $experiences ) : home_url( '/' );
?>

<section class="package-single section-spacing bg-warm-white">
	<div class="site-container">
		<div class="package-single__layout mx-auto max-w-3xl">
			<p class="reveal section-eyebrow">
				<a class="transition-colors hover:text-gold-dark" href="<?php echo esc_url( $back_url ); ?>">
					<?php esc_html_e( 'Experiences', 'seahivez-theme' ); ?>
				</a>
			</p>

			<?php if ( $price_label ) : ?>
				<p class="reveal package-single__price mt-4 text-3xl font-semibold tracking-tight text-navy-900 md:text-4xl">
					<?php echo esc_html( $price_label ); ?>
				</p>
			<?php endif; ?>

			<div class="reveal reveal-delay-1 mt-5 space-y-1.5">
				<?php if ( ! empty( $card['duration'] ) ) : ?>
					<p class="text-sm font-medium uppercase tracking-wider text-slate-500">
						<?php echo esc_html( $card['duration'] ); ?>
					</p>
				<?php endif; ?>

				<?php if ( ! empty( $card['time_slot'] ) ) : ?>
					<p class="package-single__time flex items-center gap-2 text-sm text-slate-500">
						<svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
							<circle cx="12" cy="12" r="8"/>
							<path stroke-linecap="round" d="M12 8v4l2.5 2.5"/>
						</svg>
						<span><?php echo esc_html( $card['time_slot'] ); ?></span>
					</p>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $card['description'] ) ) : ?>
				<p class="reveal reveal-delay-1 type-body mt-6 text-lg">
					<?php echo esc_html( $card['description'] ); ?>
				</p>
			<?php endif; ?>

			<?php if ( get_the_content() ) : ?>
				<div class="reveal reveal-delay-2 entry-content entry-content--article type-body mt-6 space-y-4">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $included ) ) : ?>
				<ul class="reveal reveal-delay-2 package-single__included mt-8 space-y-2">
					<?php foreach ( $included as $line ) : ?>
						<li class="flex items-start gap-2 text-sm text-gray-600">
							<span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-gold" aria-hidden="true"></span>
							<span><?php echo esc_html( $line ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<a class="reveal reveal-delay-2 btn-primary mt-10 inline-flex" href="<?php echo esc_url( $booking_url ); ?>">
				<?php esc_html_e( 'Book now', 'seahivez-theme' ); ?>
			</a>
		</div>
	</div>
</section>
