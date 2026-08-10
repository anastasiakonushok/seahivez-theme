<?php
/**
 * Homepage hero section.
 *
 * @package seahivez-theme
 */

$hero = seahivez_get_home_hero_data();
?>

<section class="hero relative flex min-h-[70vh] items-end overflow-hidden md:min-h-[80vh] lg:min-h-[88vh]" aria-labelledby="hero-heading">
	<div class="hero__media absolute inset-0">
		<div class="hero__video-slot hidden" aria-hidden="true">
			<?php
			/**
			 * Future background video slot.
			 * Replace image layer with:
			 * <video autoplay muted loop playsinline class="hero__video h-full w-full object-cover">...</video>
			 */
			?>
		</div>

		<?php if ( ! empty( $hero['image'] ) ) : ?>
			<img
				class="hero__image h-full w-full object-cover"
				src="<?php echo esc_url( $hero['image'] ); ?>"
				alt="<?php echo esc_attr( $hero['image_alt'] ); ?>"
				width="<?php echo esc_attr( ! empty( $hero['image_width'] ) ? (string) $hero['image_width'] : '1280' ); ?>"
				height="<?php echo esc_attr( ! empty( $hero['image_height'] ) ? (string) $hero['image_height'] : '847' ); ?>"
				fetchpriority="high"
				decoding="async"
			>
		<?php endif; ?>

		<div class="hero__overlay absolute inset-0" aria-hidden="true"></div>
	</div>

	<div class="hero__content site-container relative z-10 w-full pb-16 pt-32 md:pb-20 md:pt-40 lg:pb-24">
		<div class="max-w-2xl reveal">
			<p class="hero__eyebrow mb-4">
				<?php echo esc_html( $hero['eyebrow'] ); ?>
			</p>

			<h1 id="hero-heading" class="hero__heading">
				<?php echo esc_html( $hero['heading'] ); ?>
			</h1>

			<p class="hero__description mt-5 max-w-xl">
				<?php echo esc_html( $hero['description'] ); ?>
			</p>

			<div class="hero__actions mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
				<a class="btn-primary" href="<?php echo esc_url( $hero['primary_url'] ); ?>">
					<?php echo esc_html( $hero['primary_label'] ); ?>
				</a>
				<a class="btn-outline-light" href="<?php echo esc_url( $hero['secondary_url'] ); ?>">
					<?php echo esc_html( $hero['secondary_label'] ); ?>
				</a>
			</div>
		</div>
	</div>
</section>
