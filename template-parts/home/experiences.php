<?php
/**
 * Homepage experiences section.
 *
 * @package seahivez-theme
 */

$section_args  = ! empty( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : array();
$experiences   = ! empty( $section_args['experiences'] ) ? $section_args['experiences'] : seahivez_get_packages_for_display();
$eyebrow       = ! empty( $section_args['eyebrow'] ) ? $section_args['eyebrow'] : __( 'Charter Services', 'seahivez-theme' );
$heading       = ! empty( $section_args['heading'] ) ? $section_args['heading'] : __( 'Choose your experience', 'seahivez-theme' );

if ( empty( $experiences ) ) {
	return;
}
?>

<section class="experiences section-spacing bg-warm-white" id="experiences" aria-labelledby="experiences-heading">
	<div class="site-container">
		<div class="reveal max-w-2xl">
			<?php if ( $eyebrow ) : ?>
				<p class="section-eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<?php endif; ?>
			<?php if ( $heading ) : ?>
				<h2 id="experiences-heading" class="section-heading mt-3">
					<?php echo esc_html( $heading ); ?>
				</h2>
			<?php endif; ?>
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
