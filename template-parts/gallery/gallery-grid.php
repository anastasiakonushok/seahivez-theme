<?php
/**
 * Full gallery editorial grid with Fancybox.
 *
 * @package seahivez-theme
 */

$items = seahivez_get_home_gallery_items();

if ( empty( $items ) ) {
	return;
}

$span_classes = array(
	'md:col-span-2 md:row-span-2',
	'',
	'',
	'md:col-span-2',
	'',
	'',
);
?>

<section class="gallery-page section-spacing bg-warm-white" aria-label="<?php esc_attr_e( 'Gallery', 'seahivez-theme' ); ?>">
	<div class="site-container">
		<div class="gallery-page__grid grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 md:auto-rows-[220px] lg:auto-rows-[260px] lg:gap-4">
			<?php foreach ( $items as $index => $item ) : ?>
				<?php
				$thumb   = ! empty( $item['thumbnail'] ) ? $item['thumbnail'] : $item['image'];
				$full    = ! empty( $item['full'] ) ? $item['full'] : $item['image'];
				$caption = ! empty( $item['caption'] ) ? $item['caption'] : $item['alt'];
				$span    = $span_classes[ $index % count( $span_classes ) ];
				?>
				<figure class="gallery-page__item group relative min-h-[220px] overflow-hidden rounded-md <?php echo esc_attr( $span ); ?> reveal">
					<a
						class="absolute inset-0 block cursor-zoom-in focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-navy-700"
						href="<?php echo esc_url( $full ); ?>"
						data-fancybox="seahivez-full-gallery"
						data-caption="<?php echo esc_attr( $caption ); ?>"
					>
						<img
							class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.03]"
							src="<?php echo esc_url( $thumb ); ?>"
							alt="<?php echo esc_attr( $item['alt'] ); ?>"
							loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>"
							decoding="async"
						>
					</a>
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>
