<?php
/**
 * Homepage toys and extras section.
 *
 * @package seahivez-theme
 */

$data = seahivez_get_home_extras_data();
?>

<section class="toys-extras section-spacing bg-sand-50" id="toys-extras" aria-labelledby="toys-extras-heading">
	<div class="site-container">
		<div class="reveal max-w-2xl">
			<p class="section-eyebrow"><?php echo esc_html( $data['eyebrow'] ); ?></p>
			<h2 id="toys-extras-heading" class="section-heading mt-3">
				<?php echo esc_html( $data['heading'] ); ?>
			</h2>
			<p class="type-body mt-4">
				<?php echo esc_html( $data['description'] ); ?>
			</p>
		</div>

		<div class="extras-groups">
			<div class="extras-group extras-group--included reveal">
				<div class="extras-group__header">
					<h3 class="extras-group__title"><?php echo esc_html( $data['included_heading'] ); ?></h3>
					<div class="mt-2 flex items-center gap-2">
						<span
							aria-hidden="true"
							style="display:inline-block;width:8px;height:8px;min-width:8px;border-radius:9999px;background:#65A844;"
						></span>
						<span style="color:#65A844;">
							<?php echo esc_html( $data['included_helper'] ); ?>
						</span>
					</div>
				</div>
				<ul class="extras-grid mt-6">
					<?php foreach ( $data['included'] as $item ) : ?>
						<li class="extras-grid__item w-full min-w-0">
							<?php get_template_part( 'template-parts/cards/extra-item', null, $item ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="extras-group extras-group--paid reveal reveal-delay-1">
				<div class="extras-group__header">
					<h3 class="extras-group__title"><?php echo esc_html( $data['paid_heading'] ); ?></h3>
					<div class="mt-2 flex items-center gap-2">
						<span
							aria-hidden="true"
							style="display:inline-block;width:8px;height:8px;min-width:8px;border-radius:9999px;background:#C65D5D;"
						></span>
						<span style="color:#C65D5D;">
							<?php echo esc_html( $data['paid_helper'] ); ?>
						</span>
					</div>
				</div>
				<ul class="extras-grid mt-6">
					<?php foreach ( $data['paid'] as $item ) : ?>
						<li class="extras-grid__item w-full min-w-0">
							<?php get_template_part( 'template-parts/cards/extra-item', null, $item ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>

		<?php if ( ! empty( $data['amenities'] ) ) : ?>
			<ul class="amenities-bar mt-12 grid w-full grid-cols-2 gap-x-6 gap-y-4 border-t border-gray-200 pt-8 lg:grid-cols-4 lg:gap-x-8">
				<?php foreach ( $data['amenities'] as $amenity ) : ?>
					<li class="amenities-bar__item flex min-w-0 items-center gap-3 text-sm text-gray-600">
						<svg class="amenities-bar__icon h-4 w-4 shrink-0 text-navy-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
						</svg>
						<span><?php echo esc_html( $amenity ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
