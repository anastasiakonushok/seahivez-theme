<?php
/**
 * Homepage gallery section — immersive full-bleed Swiper slider.
 *
 * @package seahivez-theme
 */

$header = seahivez_get_home_gallery_header();
$items  = seahivez_get_home_gallery_items();

if ( empty( $items ) ) {
	return;
}

$total           = count( $items );
$desktop_thumbs  = 4;
$remaining_count = max( 0, $total - $desktop_thumbs );
$more_label      = $remaining_count > 0
	? sprintf(
		/* translators: %d: number of additional gallery photos */
		__( '+%d Photos', 'seahivez-theme' ),
		$remaining_count
	)
	: __( 'View gallery', 'seahivez-theme' );
?>

<section class="gallery w-full max-w-none bg-navy-950" aria-labelledby="gallery-heading" data-gallery-slider>
	<div class="gallery-slider gallery-swiper w-full max-w-none">
		<div class="gallery-slider__stage relative w-full overflow-hidden">
			<div class="swiper gallery-slider__main h-full w-full" data-gallery-main>
				<div class="swiper-wrapper h-full">
					<?php foreach ( $items as $index => $item ) : ?>
						<?php
						$large   = ! empty( $item['large'] ) ? $item['large'] : $item['image'];
						$full    = ! empty( $item['full'] ) ? $item['full'] : $large;
						$caption = ! empty( $item['caption'] ) ? $item['caption'] : $item['alt'];
						$srcset  = ! empty( $item['large_srcset'] ) ? $item['large_srcset'] : ( $item['srcset'] ?? '' );
						$sizes   = ! empty( $item['large_sizes'] ) ? $item['large_sizes'] : ( $item['sizes'] ?? '100vw' );
						$eager   = 0 === $index;
						?>
						<div class="swiper-slide gallery-slider__slide h-full">
							<a
								class="gallery-slider__link"
								href="<?php echo esc_url( $full ); ?>"
								data-fancybox="seahivez-gallery"
								data-caption="<?php echo esc_attr( $caption ); ?>"
							>
								<img
									class="gallery-slider__image h-full w-full object-cover"
									src="<?php echo esc_url( $large ); ?>"
									alt="<?php echo esc_attr( $item['alt'] ); ?>"
									<?php if ( $srcset ) : ?>
										srcset="<?php echo esc_attr( $srcset ); ?>"
									<?php endif; ?>
									<?php if ( $sizes ) : ?>
										sizes="<?php echo esc_attr( $sizes ); ?>"
									<?php endif; ?>
									<?php if ( $eager ) : ?>
										loading="eager"
										fetchpriority="high"
									<?php else : ?>
										loading="lazy"
									<?php endif; ?>
									decoding="async"
									draggable="false"
								>
							</a>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<button
				type="button"
				class="gallery-slider__nav gallery-slider__nav--prev"
				data-gallery-prev
				aria-label="<?php esc_attr_e( 'Previous image', 'seahivez-theme' ); ?>"
			>
				<?php seahivez_render_arrow( 'left', array( 'size' => 'md', 'class' => 'gallery-slider__nav-icon' ) ); ?>
			</button>

			<button
				type="button"
				class="gallery-slider__nav gallery-slider__nav--next"
				data-gallery-next
				aria-label="<?php esc_attr_e( 'Next image', 'seahivez-theme' ); ?>"
			>
				<?php seahivez_render_arrow( 'right', array( 'size' => 'md', 'class' => 'gallery-slider__nav-icon' ) ); ?>
			</button>

			<div class="gallery-slider__overlay pointer-events-none">
				<div class="gallery-slider__chrome pointer-events-auto">
					<p class="gallery-slider__eyebrow type-eyebrow text-white/70">
						<?php echo esc_html( $header['eyebrow'] ); ?>
					</p>
					<h2 id="gallery-heading" class="gallery-slider__title">
						<span class="gallery-slider__title-brand"><?php esc_html_e( 'SeaHivez', 'seahivez-theme' ); ?></span>
						<span class="gallery-slider__title-label"><?php esc_html_e( 'Gallery', 'seahivez-theme' ); ?></span>
					</h2>

					<div class="gallery-slider__thumbs-row">
						<div class="swiper gallery-slider__thumbs" data-gallery-thumbs>
							<div class="swiper-wrapper">
								<?php foreach ( $items as $index => $item ) : ?>
									<?php
									$thumb        = ! empty( $item['thumbnail'] ) ? $item['thumbnail'] : $item['image'];
									$thumb_srcset = $item['thumb_srcset'] ?? '';
									$thumb_sizes  = ! empty( $item['thumb_sizes'] ) ? $item['thumb_sizes'] : '140px';
									?>
									<div class="swiper-slide gallery-slider__thumb-slide">
										<button
											type="button"
											class="gallery-slider__thumb"
											aria-label="<?php echo esc_attr( sprintf( __( 'Show image %d', 'seahivez-theme' ), $index + 1 ) ); ?>"
										>
											<img
												class="gallery-slider__thumb-image h-full w-full object-cover"
												src="<?php echo esc_url( $thumb ); ?>"
												alt=""
												<?php if ( $thumb_srcset ) : ?>
													srcset="<?php echo esc_attr( $thumb_srcset ); ?>"
												<?php endif; ?>
												sizes="<?php echo esc_attr( $thumb_sizes ); ?>"
												loading="lazy"
												decoding="async"
												draggable="false"
											>
										</button>
									</div>
								<?php endforeach; ?>
							</div>
						</div>

						<button
							type="button"
							class="gallery-slider__more"
							data-gallery-more
							aria-label="<?php echo esc_attr( $more_label ); ?>"
						>
							<span class="gallery-slider__more-text"><?php echo esc_html( $more_label ); ?></span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
