<?php
/**
 * Extras page content — Included / Extra Paid.
 *
 * @package seahivez-theme
 */

$data = seahivez_get_home_extras_data();
?>

<section class="extras-page section-spacing bg-warm-white" aria-labelledby="extras-page-heading">
	<div class="site-container">
		<div class="reveal max-w-2xl">
			<p class="section-eyebrow"><?php echo esc_html( $data['eyebrow'] ); ?></p>
			<h2 id="extras-page-heading" class="section-heading mt-3">
				<?php echo esc_html( $data['heading'] ); ?>
			</h2>
			<p class="type-body mt-4"><?php echo esc_html( $data['description'] ); ?></p>
		</div>

		<div class="extras-groups mt-12">
			<div class="extras-group extras-group--included reveal">
				<div class="extras-group__header">
					<h3 class="extras-group__title"><?php echo esc_html( $data['included_heading'] ); ?></h3>
					<p class="extras-group__helper"><?php echo esc_html( $data['included_helper'] ); ?></p>
				</div>
				<ul class="extras-grid mt-6" role="list">
					<?php foreach ( $data['included'] as $item ) : ?>
						<li class="extras-grid__item">
							<?php get_template_part( 'template-parts/cards/extra-item', null, $item ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="extras-group extras-group--paid reveal reveal-delay-1">
				<div class="extras-group__header">
					<h3 class="extras-group__title"><?php echo esc_html( $data['paid_heading'] ); ?></h3>
					<p class="extras-group__helper"><?php echo esc_html( $data['paid_helper'] ); ?></p>
				</div>
				<ul class="extras-grid mt-6" role="list">
					<?php foreach ( $data['paid'] as $item ) : ?>
						<li class="extras-grid__item">
							<?php get_template_part( 'template-parts/cards/extra-item', null, $item ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>

		<?php if ( ! empty( $data['amenities'] ) ) : ?>
			<ul class="mt-12 grid gap-4 border-t border-gray-200 pt-10 sm:grid-cols-2 lg:grid-cols-4" role="list">
				<?php foreach ( $data['amenities'] as $amenity ) : ?>
					<li class="reveal text-sm font-medium text-navy-900">
						<span class="mr-2 inline-block h-1.5 w-1.5 rounded-full bg-gold align-middle" aria-hidden="true"></span>
						<?php echo esc_html( $amenity ); ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
