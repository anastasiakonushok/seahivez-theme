<?php
/**
 * Experiences page — rich package list.
 *
 * @package seahivez-theme
 */

$experiences = seahivez_get_home_experiences();
$included    = seahivez_get_experience_included_items();
?>

<section class="experiences-page section-spacing bg-warm-white" aria-label="<?php esc_attr_e( 'Charter experiences', 'seahivez-theme' ); ?>">
	<div class="site-container space-y-16">
		<?php foreach ( $experiences as $index => $experience ) : ?>
			<?php
			$price_label = seahivez_format_extra_price_label( $experience['price'], false );
			$reverse     = 1 === $index % 2;
			?>
			<article class="experiences-page__item grid items-center gap-10 lg:grid-cols-2 lg:gap-16 <?php echo $reverse ? 'lg:[&>*:first-child]:order-2' : ''; ?>">
				<div class="reveal aspect-[4/3] overflow-hidden rounded-md">
					<?php if ( ! empty( $experience['image'] ) ) : ?>
						<img
							class="h-full w-full object-cover"
							src="<?php echo esc_url( $experience['image'] ); ?>"
							alt="<?php echo esc_attr( $experience['title'] ); ?>"
							loading="lazy"
							decoding="async"
						>
					<?php endif; ?>
				</div>

				<div class="reveal reveal-delay-1">
					<h2 class="section-heading"><?php echo esc_html( $experience['title'] ); ?></h2>

					<?php if ( $price_label ) : ?>
						<p class="mt-4 text-2xl font-semibold text-navy-900"><?php echo esc_html( $price_label ); ?></p>
					<?php endif; ?>

					<div class="mt-5 space-y-1.5">
						<?php if ( ! empty( $experience['duration'] ) ) : ?>
							<p class="text-sm font-medium uppercase tracking-wider text-slate-500">
								<?php echo esc_html( $experience['duration'] ); ?>
							</p>
						<?php endif; ?>
						<?php if ( ! empty( $experience['time_slot'] ) ) : ?>
							<p class="flex items-center gap-2 text-sm text-slate-500">
								<svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
									<circle cx="12" cy="12" r="8"/><path stroke-linecap="round" d="M12 8v4l2.5 2.5"/>
								</svg>
								<span><?php echo esc_html( $experience['time_slot'] ); ?></span>
							</p>
						<?php endif; ?>
					</div>

					<?php if ( ! empty( $experience['description'] ) ) : ?>
						<p class="type-body mt-5 max-w-xl"><?php echo esc_html( $experience['description'] ); ?></p>
					<?php endif; ?>

					<ul class="mt-6 space-y-2">
						<?php foreach ( $included as $line ) : ?>
							<li class="flex items-start gap-2 text-sm text-gray-600">
								<span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-gold" aria-hidden="true"></span>
								<span><?php echo esc_html( $line ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>

					<a class="btn-primary mt-8 inline-flex" href="<?php echo esc_url( $experience['url'] ); ?>">
						<?php esc_html_e( 'Book now', 'seahivez-theme' ); ?>
					</a>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
</section>
