<?php
/**
 * Checkout empty state.
 *
 * @package seahivez-theme
 *
 * @var array<string, mixed> $args Checkout view args.
 */
?>

<section class="checkout-empty section-spacing">
	<div class="site-container max-w-2xl">
		<p class="section-eyebrow"><?php esc_html_e( 'Checkout', 'seahivez-theme' ); ?></p>
		<h1 class="section-heading mt-3"><?php esc_html_e( 'No charter selected yet', 'seahivez-theme' ); ?></h1>
		<p class="type-body mt-4 text-slate-600">
			<?php esc_html_e( 'Choose your charter package on the homepage, configure your options, and click Book now to continue here.', 'seahivez-theme' ); ?>
		</p>
		<a class="btn-primary mt-8 inline-flex" href="<?php echo esc_url( home_url( '/#experiences' ) ); ?>">
			<span><?php esc_html_e( 'View charter packages', 'seahivez-theme' ); ?></span>
			<?php seahivez_render_link_arrow_icon( 'sm' ); ?>
		</a>
	</div>
</section>
