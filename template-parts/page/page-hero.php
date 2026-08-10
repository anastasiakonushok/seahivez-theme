<?php
/**
 * Interior page hero.
 *
 * @package seahivez-theme
 *
 * @var array $args Hero data from seahivez_get_page_hero_defaults().
 */

$args = seahivez_get_page_hero_defaults( $args ?? array() );

if ( empty( $args['heading'] ) ) {
	return;
}

$height_class = ! empty( $args['compact'] )
	? 'min-h-[42vh] md:min-h-[50vh] lg:min-h-[58vh]'
	: 'min-h-[48vh] md:min-h-[58vh] lg:min-h-[68vh]';
?>

<section class="page-hero relative flex <?php echo esc_attr( $height_class ); ?> items-end overflow-hidden bg-navy-950" aria-labelledby="page-hero-heading">
	<?php if ( ! empty( $args['image'] ) ) : ?>
		<div class="absolute inset-0">
			<img
				class="h-full w-full object-cover object-[60%_center]"
				src="<?php echo esc_url( $args['image'] ); ?>"
				alt="<?php echo esc_attr( $args['image_alt'] ); ?>"
				width="1280"
				height="800"
				fetchpriority="high"
				decoding="async"
			>
			<?php if ( ! empty( $args['overlay'] ) ) : ?>
				<div class="absolute inset-0 bg-gradient-to-r from-navy-950/85 via-navy-900/50 to-navy-900/20" aria-hidden="true"></div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="site-container relative z-10 w-full pb-12 pt-28 md:pb-16 md:pt-32">
		<div class="max-w-2xl reveal">
			<?php if ( ! empty( $args['eyebrow'] ) ) : ?>
				<p class="type-eyebrow text-sand-100/85">
					<?php echo esc_html( $args['eyebrow'] ); ?>
				</p>
			<?php endif; ?>

			<h1 id="page-hero-heading" class="type-h1 mt-3 text-white">
				<?php echo esc_html( $args['heading'] ); ?>
			</h1>

			<?php if ( ! empty( $args['description'] ) ) : ?>
				<p class="type-body-lg mt-5 max-w-xl text-sand-100/85">
					<?php echo esc_html( $args['description'] ); ?>
				</p>
			<?php endif; ?>
		</div>
	</div>
</section>
