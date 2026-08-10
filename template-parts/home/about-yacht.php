<?php
/**
 * Homepage about yacht section.
 *
 * @package seahivez-theme
 */

$about = seahivez_get_home_about_data();
?>

<section class="about-yacht section-spacing bg-warm-white" aria-labelledby="about-yacht-heading">
	<div class="site-container">
		<div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
			<div class="reveal">
				<p class="section-eyebrow">
					<?php echo esc_html( $about['eyebrow'] ); ?>
				</p>

				<h2 id="about-yacht-heading" class="section-heading mt-3">
					<?php echo esc_html( $about['heading'] ); ?>
				</h2>

				<div class="mt-6 space-y-4 type-body">
					<?php foreach ( $about['paragraphs'] as $paragraph ) : ?>
						<p><?php echo esc_html( $paragraph ); ?></p>
					<?php endforeach; ?>
				</div>

				<a class="link-arrow mt-8 inline-flex" href="<?php echo esc_url( $about['link_url'] ); ?>">
					<?php echo esc_html( $about['link_label'] ); ?>
					<?php seahivez_render_link_arrow_icon( 'sm' ); ?>
				</a>
			</div>

			<div class="reveal reveal-delay-1">
				<?php if ( ! empty( $about['image'] ) ) : ?>
					<figure class="aspect-[4/3] overflow-hidden rounded-md">
						<img
							class="h-full w-full object-cover"
							src="<?php echo esc_url( $about['image'] ); ?>"
							alt="<?php echo esc_attr( $about['image_alt'] ); ?>"
							width="1280"
							height="730"
							loading="lazy"
							decoding="async"
						>
					</figure>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
