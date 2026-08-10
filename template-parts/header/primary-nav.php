<?php
/**
 * Primary navigation for desktop.
 *
 * @package seahivez-theme
 */

if ( ! has_nav_menu( 'primary' ) ) {
	return;
}
?>

<nav id="site-navigation" class="primary-navigation hidden lg:block" aria-label="<?php esc_attr_e( 'Primary menu', 'seahivez-theme' ); ?>">
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'menu_id'        => 'primary-menu',
			'menu_class'     => 'nav-menu flex items-center gap-8',
			'container'      => false,
			'fallback_cb'    => false,
			'depth'          => 2,
		)
	);
	?>
</nav>
