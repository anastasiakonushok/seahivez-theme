<?php
/**
 * Homepage location and booking CTA section.
 *
 * @package seahivez-theme
 */

$data = seahivez_get_home_location_data();
$port = seahivez_get_port_location();
?>

<section class="location-cta section-spacing bg-sand-50" aria-labelledby="location-cta-heading">
	<div class="site-container">
		<div class="location-cta__inner grid gap-10 rounded-md border border-gray-200 bg-white p-8 lg:grid-cols-2 lg:gap-16 lg:p-12">
			<div class="reveal">
				<h2 id="location-cta-heading" class="section-heading">
					<?php echo esc_html( $data['heading'] ); ?>
				</h2>

				<p class="type-body mt-4 max-w-lg">
					<?php echo esc_html( $data['description'] ); ?>
				</p>

				<a class="btn-primary mt-8" href="<?php echo esc_url( $data['cta_url'] ); ?>">
					<?php echo esc_html( $data['cta_label'] ); ?>
				</a>

				<ul class="mt-10 space-y-4 text-sm text-gray-600">
					<li class="flex items-start gap-3">
						<?php seahivez_render_spec_icon( 'location', array( 'class' => 'mt-0.5 h-5 w-5 shrink-0 text-navy-900' ) ); ?>
						<span><?php echo esc_html( $data['location'] ); ?></span>
					</li>
					<li class="flex items-start gap-3">
						<svg class="mt-0.5 h-5 w-5 shrink-0 text-navy-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M5 4h4l2 5-2 1a11 11 0 0 0 5 5l1-2 5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg>
						<a class="hover:text-navy-900" href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $data['phone'] ) ); ?>">
							<?php echo esc_html( $data['phone'] ); ?>
						</a>
					</li>
					<li class="flex items-start gap-3">
						<svg class="mt-0.5 h-5 w-5 shrink-0 text-navy-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
						<a class="hover:text-navy-900" href="mailto:<?php echo esc_attr( $data['email'] ); ?>">
							<?php echo esc_html( $data['email'] ); ?>
						</a>
					</li>
				</ul>

				<div
					class="weather-card mt-8"
					data-weather-widget
					aria-live="polite"
				>
					<p class="weather-card__eyebrow"><?php esc_html_e( 'Palma today', 'seahivez-theme' ); ?></p>
					<p class="weather-card__conditions" data-weather-conditions>
						<span data-weather-temp>—</span><span class="weather-card__sep" aria-hidden="true"> · </span><span data-weather-condition><?php esc_html_e( 'Loading…', 'seahivez-theme' ); ?></span>
					</p>
					<p class="weather-card__date" data-weather-date></p>
				</div>

				<div class="location-cta__social mt-8 border-t border-gray-200 pt-6">
					<?php
					get_template_part(
						'template-parts/components/social-links',
						null,
						array(
							'variant' => 'compact',
							'heading' => __( 'Follow / Contact us', 'seahivez-theme' ),
							'class'   => 'location-cta__social-links',
						)
					);
					?>
				</div>
			</div>

			<div class="reveal reveal-delay-1">
				<div
					class="location-cta__map"
					data-seahivez-map
					data-lat="<?php echo esc_attr( (string) $port['lat'] ); ?>"
					data-lng="<?php echo esc_attr( (string) $port['lng'] ); ?>"
					data-label="<?php echo esc_attr( $port['label'] ); ?>"
					data-place="<?php echo esc_attr( $port['place'] ); ?>"
					data-maps-url="<?php echo esc_url( $port['maps_url'] ); ?>"
				>
					<div class="location-cta__map-canvas" data-map-canvas role="region" aria-label="<?php esc_attr_e( 'Interactive map of SeaHivez port in S\'Arenal, Mallorca', 'seahivez-theme' ); ?>"></div>

					<div class="location-cta__map-fallback" data-map-fallback hidden>
						<p class="location-cta__map-fallback-title"><?php echo esc_html( $data['location'] ); ?></p>
						<p class="location-cta__map-fallback-text">
							<?php esc_html_e( 'Map could not be loaded.', 'seahivez-theme' ); ?>
						</p>
						<a class="link-arrow mt-4 inline-flex" href="<?php echo esc_url( $port['maps_url'] ); ?>" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'Open in Google Maps', 'seahivez-theme' ); ?>
							<?php seahivez_render_link_arrow_icon( 'sm' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
