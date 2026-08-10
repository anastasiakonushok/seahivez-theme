<?php
/**
 * 404 template.
 *
 * @package seahivez-theme
 */

get_header();
?>

<main id="primary" class="site-main flex-1">
	<section class="error-404 relative flex min-h-[70vh] items-center overflow-hidden bg-navy-950">
		<img
			class="absolute inset-0 h-full w-full object-cover opacity-50"
			src="<?php echo esc_url( seahivez_get_theme_image_uri( 'assets/images/photo/3.jpg' ) ); ?>"
			alt=""
			aria-hidden="true"
			loading="eager"
			decoding="async"
		>
		<div class="absolute inset-0 bg-gradient-to-r from-navy-950/90 via-navy-900/70 to-navy-900/40" aria-hidden="true"></div>

		<div class="site-container relative z-10 py-24">
			<div class="reveal max-w-xl">
				<p class="type-eyebrow text-sand-100/80">404</p>
				<h1 class="type-h1 mt-3 text-white"><?php esc_html_e( 'Lost at sea?', 'seahivez-theme' ); ?></h1>
				<p class="type-body-lg mt-5 text-sand-100/85">
					<?php esc_html_e( "The page you're looking for couldn't be found.", 'seahivez-theme' ); ?>
				</p>
				<div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
					<a class="btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php esc_html_e( 'Back home', 'seahivez-theme' ); ?>
					</a>
					<a class="btn-outline-light" href="<?php echo esc_url( seahivez_get_booking_url() ); ?>">
						<?php esc_html_e( 'Book your experience', 'seahivez-theme' ); ?>
					</a>
				</div>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
