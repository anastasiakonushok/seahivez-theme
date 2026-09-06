<?php
/**
 * Site header.
 *
 * @package seahivez-theme
 */

$is_homepage_header = is_front_page();
$header_settings    = seahivez_get_header_settings();
$header_classes     = 'site-header w-full transition-all duration-300';

if ( $is_homepage_header ) {
	$header_classes .= ' site-header--overlay fixed left-0 right-0 top-0 z-50 border-b border-transparent bg-transparent';
} else {
	$header_classes .= ' relative border-b border-gray-200 bg-white';
}
?>

<header id="masthead" class="<?php echo esc_attr( $header_classes ); ?>">
	<div class="site-container">
		<div class="flex items-center justify-between gap-4 py-4 lg:py-5">
			<div class="site-branding shrink-0">
				<?php
				get_template_part(
					'template-parts/components/site-logo',
					null,
					array(
						'variant' => $is_homepage_header ? 'adaptive' : 'dark',
					)
				);
				?>
			</div>

			<div class="hidden flex-1 items-center justify-center lg:flex">
				<?php get_template_part( 'template-parts/header/primary-nav' ); ?>
			</div>

			<div class="site-header__actions hidden items-center gap-5 lg:flex">
				<?php
				get_template_part(
					'template-parts/components/social-links',
					null,
					array(
						'variant' => 'compact',
						'class'   => 'site-header__social',
					)
				);
				?>

				<?php if ( ! empty( $header_settings['cta_url'] ) && ! empty( $header_settings['cta_label'] ) ) : ?>
					<a class="btn-primary site-header__cta shrink-0" href="<?php echo esc_url( $header_settings['cta_url'] ); ?>">
						<?php echo esc_html( $header_settings['cta_label'] ); ?>
					</a>
				<?php endif; ?>
			</div>

			<button
				id="mobile-menu-toggle"
				class="site-header__toggle inline-flex h-11 w-11 items-center justify-center rounded-md border lg:hidden"
				type="button"
				aria-controls="mobile-navigation"
				aria-expanded="false"
				aria-label="<?php esc_attr_e( 'Open menu', 'seahivez-theme' ); ?>"
			>
				<span class="sr-only"><?php esc_html_e( 'Menu', 'seahivez-theme' ); ?></span>
				<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
					<path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
				</svg>
			</button>
		</div>
	</div>

	<?php get_template_part( 'template-parts/header/mobile-nav' ); ?>
</header>

<?php if ( $is_homepage_header ) : ?>
	<div class="site-header-spacer h-0" aria-hidden="true"></div>
<?php endif; ?>
