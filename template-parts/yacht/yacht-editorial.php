<?php
/**
 * Yacht editorial alternating sections.
 *
 * @package seahivez-theme
 */

$sections = seahivez_get_yacht_editorial_sections();
?>

<?php foreach ( $sections as $index => $section ) : ?>
	<section class="yacht-editorial section-spacing <?php echo 0 === $index % 2 ? 'bg-warm-white' : 'bg-sand-50'; ?>" aria-labelledby="yacht-editorial-<?php echo esc_attr( (string) $index ); ?>">
		<div class="site-container">
			<div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16 <?php echo ! empty( $section['reverse'] ) ? 'lg:[&>*:first-child]:order-2' : ''; ?>">
				<div class="reveal">
					<p class="section-eyebrow"><?php echo esc_html( $section['eyebrow'] ); ?></p>
					<h2 id="yacht-editorial-<?php echo esc_attr( (string) $index ); ?>" class="section-heading mt-3">
						<?php echo esc_html( $section['heading'] ); ?>
					</h2>
					<p class="type-body mt-4 max-w-xl">
						<?php echo esc_html( $section['description'] ); ?>
					</p>
				</div>

				<div class="reveal reveal-delay-1">
					<figure class="aspect-[4/3] overflow-hidden rounded-md">
						<img
							class="h-full w-full object-cover"
							src="<?php echo esc_url( $section['image'] ); ?>"
							alt="<?php echo esc_attr( $section['image_alt'] ); ?>"
							loading="lazy"
							decoding="async"
						>
					</figure>
				</div>
			</div>
		</div>
	</section>
<?php endforeach; ?>
