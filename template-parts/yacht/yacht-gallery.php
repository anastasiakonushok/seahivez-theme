<?php
/**
 * Yacht page gallery preview (compact grid + Fancybox).
 *
 * @package seahivez-theme
 */

$items = seahivez_get_home_gallery_items();
$header = seahivez_get_home_gallery_header();

if ( empty( $items ) ) {
	return;
}
?>

<section class="yacht-gallery section-spacing bg-warm-white" aria-labelledby="yacht-gallery-heading">
	<div class="site-container">
		<div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
			<div class="reveal max-w-2xl">
				<p class="section-eyebrow"><?php echo esc_html( $header['eyebrow'] ); ?></p>
				<h2 id="yacht-gallery-heading" class="section-heading mt-3">
					<?php esc_html_e( 'Life on board', 'seahivez-theme' ); ?>
				</h2>
			</div>
			<div class="reveal hidden shrink-0 lg:block">
				<a class="btn-outline section-outline-cta link-arrow group" href="<?php echo esc_url( $header['cta_url'] ); ?>">
					<?php esc_html_e( 'View full gallery', 'seahivez-theme' ); ?>
					<?php seahivez_render_link_arrow_icon( 'md' ); ?>
				</a>
			</div>
		</div>

		<ul class="mt-10 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
			<?php foreach ( $items as $index => $item ) : ?>
				<li class="reveal<?php echo $index % 2 ? ' reveal-delay-1' : ''; ?>">
					<?php
					get_template_part(
						'template-parts/cards/gallery-item',
						null,
						array_merge(
							$item,
							array(
								'featured' => false,
							)
						)
					);
					?>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="mt-8 text-center reveal lg:hidden">
			<a class="btn-outline section-outline-cta link-arrow group" href="<?php echo esc_url( $header['cta_url'] ); ?>">
				<?php esc_html_e( 'View full gallery', 'seahivez-theme' ); ?>
				<?php seahivez_render_link_arrow_icon( 'md' ); ?>
			</a>
		</div>
	</div>
</section>
