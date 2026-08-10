<?php
/**
 * Mobile navigation panel.
 *
 * @package seahivez-theme
 */
?>

<div
	id="mobile-navigation"
	class="mobile-navigation border-t border-gray-200 bg-white lg:hidden"
	hidden
>
	<nav class="site-container py-4" aria-label="<?php esc_attr_e( 'Mobile menu', 'seahivez-theme' ); ?>">
		<?php
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_id'        => 'mobile-menu',
					'menu_class'     => 'nav-menu flex flex-col gap-4',
					'container'      => false,
					'fallback_cb'    => false,
					'depth'          => 2,
				)
			);
		} else {
			?>
			<p class="text-sm text-gray-500">
				<?php esc_html_e( 'Assign a menu to the Primary location in Appearance → Menus.', 'seahivez-theme' ); ?>
			</p>
			<?php
		}
		?>
	</nav>

	<div class="site-container border-t border-gray-200 py-4">
		<div class="flex flex-col gap-4">
			<p class="text-xs font-medium uppercase tracking-widest text-gray-500">
				<?php esc_html_e( 'Language', 'seahivez-theme' ); ?>
			</p>
			<p class="text-sm text-gray-700" aria-hidden="true">EN</p>

			<a class="btn-primary w-full text-center" href="<?php echo esc_url( seahivez_get_booking_url() ); ?>">
				<?php esc_html_e( 'Book Now', 'seahivez-theme' ); ?>
			</a>

			<?php
			get_template_part(
				'template-parts/components/social-links',
				null,
				array(
					'variant' => 'compact',
					'class'   => 'mobile-navigation__social',
				)
			);
			?>
		</div>
	</div>
</div>
