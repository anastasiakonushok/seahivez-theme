<?php
/**
 * Extras page — editorial gallery with Fancybox.
 *
 * @package seahivez-theme
 */

$data   = ! empty( $args['sections'] ) && is_array( $args['sections'] )
	? $args['sections']
	: seahivez_get_extras_page_sections();
$header = ! empty( $data['gallery_header'] ) && is_array( $data['gallery_header'] )
	? $data['gallery_header']
	: seahivez_get_extras_gallery_header();
$items  = ! empty( $data['gallery_items'] ) && is_array( $data['gallery_items'] )
	? $data['gallery_items']
	: seahivez_get_extras_gallery_items();

if ( empty( $items ) ) {
	return;
}
?>

<section class="extras-gallery section-spacing bg-sand-50" aria-labelledby="extras-gallery-heading">
	<div class="site-container">
		<div class="extras-gallery__header reveal max-w-2xl">
			<p class="section-eyebrow"><?php echo esc_html( (string) ( $header['eyebrow'] ?? '' ) ); ?></p>
			<h2 id="extras-gallery-heading" class="section-heading mt-3">
				<?php echo esc_html( (string) ( $header['heading'] ?? '' ) ); ?>
			</h2>
			<?php if ( ! empty( $header['description'] ) ) : ?>
				<p class="type-body mt-4 text-slate-600">
					<?php echo esc_html( (string) $header['description'] ); ?>
				</p>
			<?php endif; ?>
		</div>

		<ul
			class="extras-gallery__grid mt-10 grid grid-cols-1 gap-3 sm:grid-cols-2 sm:auto-rows-[minmax(160px,1fr)] md:grid-cols-3 md:auto-rows-[200px] lg:auto-rows-[240px] lg:gap-4"
			role="list"
		>
			<?php foreach ( $items as $index => $item ) : ?>
				<?php
				$thumb   = ! empty( $item['thumbnail'] ) ? $item['thumbnail'] : $item['image'];
				$full    = ! empty( $item['full'] ) ? $item['full'] : $item['image'];
				$caption = ! empty( $item['caption'] ) ? $item['caption'] : $item['alt'];
				$span    = ! empty( $item['span'] ) ? $item['span'] : '';
				?>
				<li class="extras-gallery__cell <?php echo esc_attr( $span ); ?> reveal<?php echo $index % 3 ? ' reveal-delay-' . min( $index % 3, 2 ) : ''; ?>">
					<figure class="gallery-item group relative h-full overflow-hidden rounded-md">
						<a
							class="gallery-item__link relative block h-full cursor-zoom-in focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-navy-700"
							href="<?php echo esc_url( $full ); ?>"
							data-fancybox="extras-gallery"
							data-caption="<?php echo esc_attr( $caption ); ?>"
						>
							<img
								class="gallery-item__image absolute inset-0 h-full w-full object-cover"
								src="<?php echo esc_url( $thumb ); ?>"
								alt="<?php echo esc_attr( (string) ( $item['alt'] ?? '' ) ); ?>"
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
	</div>
</section>
