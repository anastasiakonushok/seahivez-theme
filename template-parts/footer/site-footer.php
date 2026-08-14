<?php
/**
 * Site footer.
 *
 * @package seahivez-theme
 */

$footer_description = get_bloginfo( 'description', 'display' );
$current_year       = gmdate( 'Y' );

$yacht_fallback_links = array(
	array(
		'label' => __( 'Specifications', 'seahivez-theme' ),
		'url'   => home_url( '/the-yacht/#specifications' ),
	),
	array(
		'label' => __( 'Experiences', 'seahivez-theme' ),
		'url'   => home_url( '/#experiences' ),
	),
	array(
		'label' => __( 'Toys & Extras', 'seahivez-theme' ),
		'url'   => home_url( '/#toys-extras' ),
	),
	array(
		'label' => __( 'Gallery', 'seahivez-theme' ),
		'url'   => home_url( '/gallery/' ),
	),
);
?>

<footer id="colophon" class="site-footer bg-navy-950 text-sand-100">
	<div class="site-container section-spacing">
		<div class="grid gap-10 md:grid-cols-2 lg:grid-cols-5">
			<div class="space-y-4 lg:col-span-1">
				<?php if ( has_custom_logo() ) : ?>
					<div class="site-logo site-logo--footer">
						<?php the_custom_logo(); ?>
					</div>
				<?php else : ?>
					<p class="site-title text-white">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<?php bloginfo( 'name' ); ?>
						</a>
					</p>
				<?php endif; ?>

				<?php if ( $footer_description ) : ?>
					<p class="site-description max-w-sm text-sm leading-relaxed text-sand-100/80">
						<?php echo esc_html( $footer_description ); ?>
					</p>
				<?php else : ?>
					<p class="max-w-sm text-sm leading-relaxed text-sand-100/80">
						<?php esc_html_e( 'Private yacht charter experiences in Mallorca aboard the Numarine 55 Fly.', 'seahivez-theme' ); ?>
					</p>
				<?php endif; ?>

				<div class="pt-2">
					<h2 class="type-eyebrow mb-4 text-white">
						<?php esc_html_e( 'Follow us', 'seahivez-theme' ); ?>
					</h2>
					<?php
					get_template_part(
						'template-parts/components/social-links',
						null,
						array(
							'variant' => 'light',
							'class'   => 'site-footer__social',
						)
					);
					?>
				</div>
			</div>

			<div>
				<h2 class="type-eyebrow mb-4 text-white">
					<?php esc_html_e( 'Quick Links', 'seahivez-theme' ); ?>
				</h2>

				<?php if ( has_nav_menu( 'footer' ) ) : ?>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'menu_class'     => 'footer-menu space-y-3',
							'container'      => false,
							'fallback_cb'    => false,
							'depth'          => 1,
						)
					);
					?>
				<?php else : ?>
					<ul class="footer-menu space-y-3">
						<li><a class="footer-link" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'seahivez-theme' ); ?></a></li>
						<li><a class="footer-link" href="<?php echo esc_url( home_url( '/the-yacht/' ) ); ?>"><?php esc_html_e( 'The Yacht', 'seahivez-theme' ); ?></a></li>
						<li><a class="footer-link" href="<?php echo esc_url( seahivez_get_posts_page_url() ); ?>"><?php esc_html_e( 'News', 'seahivez-theme' ); ?></a></li>
						<li><a class="footer-link" href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>"><?php esc_html_e( 'Gallery', 'seahivez-theme' ); ?></a></li>
						<li><a class="footer-link" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'seahivez-theme' ); ?></a></li>
					</ul>
				<?php endif; ?>
			</div>

			<div>
				<h2 class="type-eyebrow mb-4 text-white">
					<?php esc_html_e( 'The Yacht', 'seahivez-theme' ); ?>
				</h2>

				<?php if ( has_nav_menu( 'footer-yacht' ) ) : ?>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer-yacht',
							'menu_class'     => 'footer-menu space-y-3',
							'container'      => false,
							'fallback_cb'    => false,
							'depth'          => 1,
						)
					);
					?>
				<?php else : ?>
					<ul class="footer-menu space-y-3">
						<?php foreach ( $yacht_fallback_links as $link ) : ?>
							<li>
								<a class="footer-link" href="<?php echo esc_url( $link['url'] ); ?>">
									<?php echo esc_html( $link['label'] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<div>
				<h2 class="type-eyebrow mb-4 text-white">
					<?php esc_html_e( 'Contact', 'seahivez-theme' ); ?>
				</h2>
				<ul class="space-y-3 text-sm text-sand-100/80">
					<li><?php esc_html_e( "Palma de Mallorca / S'Arenal", 'seahivez-theme' ); ?></li>
					<li>
						<a class="footer-link" href="mailto:info@seahivez.com">info@seahivez.com</a>
					</li>
					<li>
						<a class="footer-link" href="tel:+34000000000">+34 000 000 000</a>
					</li>
				</ul>
			</div>

			<div class="space-y-4">
				<h2 class="type-eyebrow text-white">
					<?php esc_html_e( 'Book Your Experience', 'seahivez-theme' ); ?>
				</h2>
				<p class="text-sm leading-relaxed text-sand-100/80">
					<?php esc_html_e( 'Plan your private charter day on the Mediterranean.', 'seahivez-theme' ); ?>
				</p>
				<a class="btn-ghost inline-flex" href="<?php echo esc_url( seahivez_get_booking_url() ); ?>">
					<?php esc_html_e( 'Book Now', 'seahivez-theme' ); ?>
				</a>
			</div>
		</div>

		<div class="mt-10 flex flex-col gap-4 border-t border-white/10 pt-6 text-sm text-sand-100/70 md:flex-row md:items-center md:justify-between">
			<p>
				<?php
				printf(
					/* translators: %s: current year */
					esc_html__( '© %s SeaHivez. All rights reserved.', 'seahivez-theme' ),
					esc_html( $current_year )
				);
				?>
			</p>

			<div class="flex flex-wrap gap-4">
				<a class="footer-link" href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">
					<?php esc_html_e( 'Privacy Policy', 'seahivez-theme' ); ?>
				</a>
				<a class="footer-link" href="<?php echo esc_url( home_url( '/terms-and-conditions/' ) ); ?>">
					<?php esc_html_e( 'Terms & Conditions', 'seahivez-theme' ); ?>
				</a>
			</div>
		</div>
	</div>
</footer>
