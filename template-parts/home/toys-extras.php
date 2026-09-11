<?php
/**
 * Homepage toys and extras section.
 *
 * @package seahivez-theme
 */

$data        = ! empty( $args['extras'] ) && is_array( $args['extras'] ) ? $args['extras'] : seahivez_get_home_extras_data();
$defaults    = seahivez_get_home_extras_data();
$amenities   = ! empty( $data['amenities'] ) && is_array( $data['amenities'] ) ? $data['amenities'] : array();
$food_drinks = ! empty( $data['food_drinks'] ) && is_array( $data['food_drinks'] )
	? $data['food_drinks']
	: ( $defaults['food_drinks'] ?? seahivez_get_home_food_drinks_data() );
$food_items  = ! empty( $food_drinks['items'] ) && is_array( $food_drinks['items'] ) ? $food_drinks['items'] : array();
?>

<section class="toys-extras section-spacing scroll-mt-24 bg-sand-50 md:scroll-mt-28" id="toys-extras" aria-labelledby="toys-extras-heading">
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
				<?php
				get_template_part(
					'template-parts/components/extras-grid',
					null,
					array(
						'items' => $data['included'],
						'class' => 'mt-6',
					)
				);
				?>
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
				<?php
				get_template_part(
					'template-parts/components/extras-grid',
					null,
					array(
						'items' => $data['paid'],
						'class' => 'mt-6',
					)
				);
				?>
			</div>
		</div>

		<?php if ( ! empty( $amenities ) ) : ?>
			<ul class="toys-extras__amenities amenities-bar mt-12 grid w-full grid-cols-1 gap-x-6 gap-y-4 border-t border-slate-200 pt-8 sm:grid-cols-2 lg:grid-cols-4 lg:gap-x-8">
				<?php foreach ( $amenities as $amenity ) : ?>
					<?php
					$amenity_label = is_string( $amenity )
						? $amenity
						: (string) ( $amenity['label'] ?? '' );

					if ( '' === $amenity_label ) {
						continue;
					}
					?>
					<li class="amenities-bar__item flex min-w-0 items-center gap-3 text-sm text-slate-600">
						<svg class="amenities-bar__icon h-4 w-4 shrink-0 text-navy-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
						</svg>
						<span><?php echo esc_html( $amenity_label ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( ! empty( $food_items ) ) : ?>
			<div class="toys-extras__food-drinks mt-12 border-t border-slate-200 pt-8">
				<div class="toys-extras__food-drinks-header">
					<p class="section-eyebrow"><?php echo esc_html( (string) ( $food_drinks['eyebrow'] ?? '' ) ); ?></p>
					<div class="mt-2 flex items-center gap-2">
						<span class="toys-extras__status-dot toys-extras__status-dot--request" aria-hidden="true"></span>
						<span class="toys-extras__status-label toys-extras__status-label--request">
							<?php echo esc_html( (string) ( $food_drinks['status'] ?? __( 'Available on request', 'seahivez-theme' ) ) ); ?>
						</span>
					</div>

					<?php if ( ! empty( $food_drinks['note'] ) ) : ?>
						<p class="toys-extras__food-note mt-2 text-sm leading-snug text-slate-600">
							<?php echo esc_html( (string) $food_drinks['note'] ); ?>
						</p>
					<?php endif; ?>
				</div>

				<div class="toys-extras__food-grid mt-8 grid grid-cols-1 gap-8 md:grid-cols-3 md:gap-0">
					<?php foreach ( $food_items as $index => $item ) : ?>
						<?php
						$item_title = (string) ( $item['title'] ?? '' );
						$item_icon  = (string) ( $item['icon'] ?? '' );
						$item_price = seahivez_format_food_drinks_price_label(
							(int) ( $item['price'] ?? 0 ),
							(string) ( $item['unit'] ?? '' )
						);
						$item_description = (string) ( $item['description'] ?? '' );
						?>
						<div class="toys-extras__food-item<?php echo $index ? ' md:border-l md:border-slate-200 md:pl-8' : ''; ?><?php echo 1 === $index ? ' md:px-8' : ''; ?>">
							<?php if ( seahivez_has_icon( $item_icon ) ) : ?>
								<div class="toys-extras__food-icon text-navy-900" aria-hidden="true">
									<?php seahivez_render_flexible_icon( $item_icon, array( 'class' => 'h-10 w-10' ) ); ?>
								</div>
							<?php endif; ?>

							<?php if ( $item_title ) : ?>
								<p class="toys-extras__food-title mt-3 text-xs font-medium uppercase tracking-[0.14em] text-navy-800/80">
									<?php echo esc_html( $item_title ); ?>
								</p>
							<?php endif; ?>

							<p class="toys-extras__food-price mt-2 text-lg font-medium leading-none tracking-tight text-navy-900">
								<?php echo esc_html( $item_price ); ?>
							</p>

							<?php if ( $item_description ) : ?>
								<p class="toys-extras__food-description mt-2 max-w-xs text-sm leading-snug text-slate-600">
									<?php echo esc_html( $item_description ); ?>
								</p>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
