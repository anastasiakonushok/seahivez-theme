<?php
/**
 * Site footer.
 *
 * @package seahivez-theme
 */

$footer_settings    = seahivez_get_footer_settings();
$footer_description = ! empty( $footer_settings['description'] )
	? $footer_settings['description']
	: get_bloginfo( 'description', 'display' );

if ( empty( $footer_description ) ) {
	$footer_description = __( 'Private yacht charter experiences in Mallorca aboard the Numarine 55 Fly.', 'seahivez-theme' );
}

$current_year = gmdate( 'Y' );

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
				<?php
				get_template_part(
					'template-parts/components/site-logo',
					null,
					array(
						'variant' => 'light',
						'class'   => 'site-logo--footer',
					)
				);
				?>

				<?php if ( $footer_description ) : ?>
					<p class="site-description max-w-sm text-sm leading-relaxed text-sand-100/80">
						<?php echo esc_html( $footer_description ); ?>
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
						<li><a class="footer-link" href="<?php echo esc_url( seahivez_get_faq_page_url() ); ?>"><?php esc_html_e( 'FAQ', 'seahivez-theme' ); ?></a></li>
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
					<?php if ( ! empty( $footer_settings['address'] ) ) : ?>
						<li><?php echo esc_html( $footer_settings['address'] ); ?></li>
					<?php endif; ?>
					<?php if ( ! empty( $footer_settings['email'] ) ) : ?>
						<li>
							<a class="footer-link" href="mailto:<?php echo esc_attr( $footer_settings['email'] ); ?>">
								<?php echo esc_html( $footer_settings['email'] ); ?>
							</a>
						</li>
					<?php endif; ?>
					<?php if ( ! empty( $footer_settings['phone'] ) ) : ?>
						<li>
							<a class="footer-link" href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $footer_settings['phone'] ) ); ?>">
								<?php echo esc_html( $footer_settings['phone'] ); ?>
							</a>
						</li>
					<?php endif; ?>
				</ul>
			</div>

			<div class="space-y-4">
				<h2 class="type-eyebrow text-white">
					<?php echo esc_html( $footer_settings['book_heading'] ); ?>
				</h2>
				<p class="text-sm leading-relaxed text-sand-100/80">
					<?php echo esc_html( $footer_settings['book_description'] ); ?>
				</p>
				<a class="btn-ghost inline-flex" href="<?php echo esc_url( $footer_settings['book_cta_url'] ); ?>">
					<?php echo esc_html( $footer_settings['book_cta_label'] ); ?>
				</a>
			</div>
		</div>

		<div class="mt-10 flex flex-col gap-4 border-t border-white/10 pt-6 text-sm text-sand-100/70 md:flex-row md:items-center md:justify-between">
			<p>
				<?php
				if ( ! empty( $footer_settings['copyright'] ) ) {
					echo esc_html( $footer_settings['copyright'] );
				} else {
					printf(
						/* translators: %s: current year */
						esc_html__( '© %s SeaHivez. All rights reserved.', 'seahivez-theme' ),
						esc_html( $current_year )
					);
				}
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
