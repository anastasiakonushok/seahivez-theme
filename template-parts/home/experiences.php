<?php
/**
 * Homepage experiences section.
 *
 * @package seahivez-theme
 */

$experiences = seahivez_get_home_experiences();
?>

<section class="experiences section-spacing bg-warm-white" id="experiences" aria-labelledby="experiences-heading">
	<div class="site-container">
		<div class="reveal max-w-2xl">
			<p class="section-eyebrow"><?php esc_html_e( 'Charter Services', 'seahivez-theme' ); ?></p>
			<h2 id="experiences-heading" class="section-heading mt-3">
				<?php esc_html_e( 'Choose your experience', 'seahivez-theme' ); ?>
			</h2>
		</div>

		<div class="experiences__grid mt-10 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
			<?php foreach ( $experiences as $index => $experience ) : ?>
				<div class="reveal h-full<?php echo $index ? ' reveal-delay-' . min( $index, 2 ) : ''; ?>">
					<?php get_template_part( 'template-parts/cards/experience-card', null, $experience ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
