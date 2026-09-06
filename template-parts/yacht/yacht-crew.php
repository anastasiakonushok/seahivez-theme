<?php
/**
 * Yacht crew roles.
 *
 * @package seahivez-theme
 */

$crew   = seahivez_get_yacht_crew();
$header = seahivez_get_yacht_crew_header();
?>

<section class="yacht-crew section-spacing bg-warm-white" aria-labelledby="yacht-crew-heading">
	<div class="site-container">
		<div class="reveal max-w-2xl">
			<?php if ( ! empty( $header['eyebrow'] ) ) : ?>
				<p class="section-eyebrow"><?php echo esc_html( $header['eyebrow'] ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $header['heading'] ) ) : ?>
				<h2 id="yacht-crew-heading" class="section-heading mt-3">
					<?php echo esc_html( $header['heading'] ); ?>
				</h2>
			<?php endif; ?>
			<?php if ( ! empty( $header['description'] ) ) : ?>
				<p class="type-body mt-4">
					<?php echo esc_html( $header['description'] ); ?>
				</p>
			<?php endif; ?>
		</div>

		<div class="mt-10 grid gap-8 md:grid-cols-2">
			<?php foreach ( $crew as $index => $member ) : ?>
				<article class="reveal<?php echo $index ? ' reveal-delay-1' : ''; ?> border-t border-gray-200 pt-6">
					<div class="mb-4 text-navy-900" aria-hidden="true">
						<?php seahivez_render_spec_icon( 'crew', array( 'class' => 'h-7 w-7' ) ); ?>
					</div>
					<h3 class="card-title"><?php echo esc_html( $member['role'] ); ?></h3>
					<p class="type-body mt-3"><?php echo esc_html( $member['description'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
