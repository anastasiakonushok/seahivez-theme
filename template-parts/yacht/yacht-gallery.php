<?php
/**
 * The Yacht page — mosaic gallery with Fancybox.
 *
 * @package seahivez-theme
 */

$items  = seahivez_get_yacht_gallery_items();
$header = seahivez_get_home_gallery_header();

if ( empty( $items ) ) {
	return;
}

$total = count( $items );
?>

<section class="yacht-gallery section-spacing bg-warm-white" aria-labelledby="yacht-gallery-heading">
	<div class="site-container">
		<div class="yacht-gallery__header flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
			<div class="reveal max-w-2xl">
				<p class="section-eyebrow"><?php echo esc_html( $header['eyebrow'] ); ?></p>
				<h2 id="yacht-gallery-heading" class="section-heading mt-3">
					<?php esc_html_e( 'Life on board', 'seahivez-theme' ); ?>
				</h2>
				<p class="type-body mt-4 max-w-xl">
					<?php esc_html_e( 'Explore interiors, decks and Mediterranean moments aboard the Numarine 55 Fly.', 'seahivez-theme' ); ?>
				</p>
			</div>

			<div class="reveal hidden shrink-0 lg:block">
				<a class="btn-outline section-outline-cta link-arrow group" href="<?php echo esc_url( $header['cta_url'] ); ?>">
					<?php esc_html_e( 'View full gallery', 'seahivez-theme' ); ?>
					<?php seahivez_render_link_arrow_icon( 'md' ); ?>
				</a>
			</div>
		</div>

		<ul
			class="yacht-gallery__grid mt-10 grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 md:auto-rows-[200px] lg:auto-rows-[240px] lg:gap-4"
			role="list"
		>
			<?php foreach ( $items as $index => $item ) : ?>
				<?php
				$thumb   = ! empty( $item['thumbnail'] ) ? $item['thumbnail'] : $item['image'];
				$full    = ! empty( $item['full'] ) ? $item['full'] : $item['image'];
				$caption = ! empty( $item['caption'] ) ? $item['caption'] : $item['alt'];
				$span    = ! empty( $item['span'] ) ? $item['span'] : '';
				?>
				<li class="yacht-gallery__cell min-h-[220px] <?php echo esc_attr( $span ); ?> reveal<?php echo $index % 3 ? ' reveal-delay-' . min( $index % 3, 2 ) : ''; ?>">
					<figure class="gallery-item group relative h-full min-h-[220px] overflow-hidden rounded-md">
						<a
							class="gallery-item__link relative block h-full min-h-[220px] cursor-zoom-in focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-navy-700"
							href="<?php echo esc_url( $full ); ?>"
							data-fancybox="seahivez-yacht-gallery"
							data-caption="<?php echo esc_attr( $caption ); ?>"
						>
							<img
								class="gallery-item__image absolute inset-0 h-full w-full object-cover"
								src="<?php echo esc_url( $thumb ); ?>"
								alt="<?php echo esc_attr( $item['alt'] ); ?>"
								loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>"
								decoding="async"
							>

							<span class="gallery-item__overlay" aria-hidden="true">
								<span class="gallery-item__hint">
									<?php esc_html_e( 'View image', 'seahivez-theme' ); ?>
									<?php seahivez_render_arrow( 'right', array( 'size' => 'sm', 'class' => 'gallery-item__hint-arrow' ) ); ?>
								</span>
							</span>
						</a>
					</figure>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="mt-8 flex flex-col items-center gap-4 reveal sm:flex-row sm:justify-between">
			<p class="type-eyebrow text-gray-500">
				<?php
				printf(
					/* translators: %d: number of photos */
					esc_html( _n( '%d photo', '%d photos', $total, 'seahivez-theme' ) ),
					(int) $total
				);
				?>
			</p>

			<a class="btn-outline section-outline-cta link-arrow group lg:hidden" href="<?php echo esc_url( $header['cta_url'] ); ?>">
				<?php esc_html_e( 'View full gallery', 'seahivez-theme' ); ?>
				<?php seahivez_render_link_arrow_icon( 'md' ); ?>
			</a>
		</div>
	</div>
</section>
