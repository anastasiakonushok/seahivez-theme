<?php
/**
 * Gallery item card with Fancybox lightbox trigger.
 *
 * @package seahivez-theme
 *
 * @var array $args {
 *     @type string $image    Thumbnail / grid image URL.
 *     @type string $full     Full-size image URL for Fancybox.
 *     @type string $srcset   Optional srcset for the thumbnail.
 *     @type string $sizes    Optional sizes attribute.
 *     @type string $alt      Alt text.
 *     @type string $caption  Fancybox caption.
 *     @type bool   $featured Whether this is the featured gallery image.
 * }
 */

$args = wp_parse_args(
	$args ?? array(),
	array(
		'image'    => '',
		'full'     => '',
		'srcset'   => '',
		'sizes'    => '',
		'alt'      => '',
		'caption'  => '',
		'featured' => false,
	)
);

if ( empty( $args['image'] ) ) {
	return;
}

$full_url = ! empty( $args['full'] ) ? $args['full'] : $args['image'];
$caption  = ! empty( $args['caption'] ) ? $args['caption'] : $args['alt'];

$figure_class = 'gallery-item group relative h-full overflow-hidden rounded-md';
$image_class  = 'gallery-item__image h-full w-full object-cover';

if ( ! empty( $args['featured'] ) ) {
	$image_class .= ' min-h-[280px] lg:min-h-full lg:aspect-auto aspect-[4/5]';
} else {
	$image_class .= ' aspect-[4/3]';
}
?>

<figure class="<?php echo esc_attr( $figure_class ); ?>">
	<a
		class="gallery-item__link relative block h-full cursor-zoom-in focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-navy-700"
		href="<?php echo esc_url( $full_url ); ?>"
		data-fancybox="seahivez-gallery"
		data-caption="<?php echo esc_attr( $caption ); ?>"
	>
		<img
			class="<?php echo esc_attr( $image_class ); ?>"
			src="<?php echo esc_url( $args['image'] ); ?>"
			alt="<?php echo esc_attr( $args['alt'] ); ?>"
			<?php if ( ! empty( $args['srcset'] ) ) : ?>
				srcset="<?php echo esc_attr( $args['srcset'] ); ?>"
			<?php endif; ?>
			<?php if ( ! empty( $args['sizes'] ) ) : ?>
				sizes="<?php echo esc_attr( $args['sizes'] ); ?>"
			<?php endif; ?>
			loading="lazy"
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
