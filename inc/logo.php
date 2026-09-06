<?php
/**
 * Theme logo helpers.
 *
 * @package seahivez-theme
 */

/**
 * Logo asset URLs.
 *
 * @return array{light: string, dark: string, alt: string}
 */
function seahivez_get_logo_assets() {
	$defaults = array(
		'light' => seahivez_get_theme_image_uri( 'assets/images/logo/logo-white.png' ),
		'dark'  => seahivez_get_theme_image_uri( 'assets/images/logo/logo-dark.png' ),
		'alt'   => get_bloginfo( 'name', 'display' ),
	);

	if ( function_exists( 'seahivez_get_header_settings' ) ) {
		$header = seahivez_get_header_settings();

		return array(
			'light' => $header['logo_light'],
			'dark'  => $header['logo_dark'],
			'alt'   => $header['logo_alt'],
		);
	}

	return $defaults;
}

/**
 * Render the dual-variant site logo.
 *
 * @param array<string, string> $args {
 *     @type string $variant  light|dark|adaptive. Default adaptive.
 *     @type string $class    Extra wrapper classes.
 * }
 * @return void
 */
function seahivez_render_site_logo( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'variant' => 'adaptive',
			'class'   => '',
		)
	);

	$assets = seahivez_get_logo_assets();

	if ( empty( $assets['light'] ) && empty( $assets['dark'] ) ) {
		return;
	}

	$variant_class = 'site-logo--adaptive';

	if ( 'light' === $args['variant'] ) {
		$variant_class = 'site-logo--light';
	} elseif ( 'dark' === $args['variant'] ) {
		$variant_class = 'site-logo--dark';
	}

	$wrapper_classes = trim( 'site-logo ' . $variant_class . ' ' . $args['class'] );
	?>
	<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
		<a class="site-logo__link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php if ( ! empty( $assets['light'] ) ) : ?>
				<img
					class="site-logo__image site-logo__image--light"
					src="<?php echo esc_url( $assets['light'] ); ?>"
					alt="<?php echo esc_attr( $assets['alt'] ); ?>"
					width="160"
					height="48"
					decoding="async"
				>
			<?php endif; ?>

			<?php if ( ! empty( $assets['dark'] ) ) : ?>
				<img
					class="site-logo__image site-logo__image--dark"
					src="<?php echo esc_url( $assets['dark'] ); ?>"
					alt="<?php echo esc_attr( $assets['alt'] ); ?>"
					width="160"
					height="48"
					decoding="async"
				>
			<?php endif; ?>
		</a>
	</div>
	<?php
}
