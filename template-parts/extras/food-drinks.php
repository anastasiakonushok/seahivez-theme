<?php
/**
 * Extras page — food & drinks section.
 *
 * @package seahivez-theme
 */

$data        = ! empty( $args['sections'] ) && is_array( $args['sections'] )
	? $args['sections']
	: seahivez_get_extras_page_sections();
$food_drinks = ! empty( $data['food_drinks'] ) && is_array( $data['food_drinks'] )
	? $data['food_drinks']
	: seahivez_get_extras_food_drinks_section();
$items       = ! empty( $food_drinks['items'] ) && is_array( $food_drinks['items'] )
	? $food_drinks['items']
	: array();

if ( empty( $items ) ) {
	return;
}
?>

<section class="extras-food section-spacing bg-warm-white" aria-labelledby="extras-food-heading">
	<div class="site-container">
		<div class="extras-food__header reveal">
			<h2 id="extras-food-heading" class="extras-section__title">
				<?php echo esc_html( (string) ( $food_drinks['eyebrow'] ?? __( 'Food & Drinks', 'seahivez-theme' ) ) ); ?>
			</h2>
			<?php
			get_template_part(
				'template-parts/components/section-status',
				null,
				array(
					'label'   => (string) ( $food_drinks['status'] ?? __( 'Available on request', 'seahivez-theme' ) ),
					'variant' => 'request',
				)
			);
			?>

			<?php if ( ! empty( $food_drinks['note'] ) ) : ?>
				<p class="extras-food__note mt-2 text-sm leading-snug text-slate-600">
					<?php echo esc_html( (string) $food_drinks['note'] ); ?>
				</p>
			<?php endif; ?>
		</div>

		<div class="extras-food__grid mt-10 grid grid-cols-1 gap-8 border-t border-slate-200 pt-8 md:grid-cols-3 md:gap-0">
			<?php foreach ( $items as $index => $item ) : ?>
				<?php
				$item_title = (string) ( $item['title'] ?? '' );
				$item_icon  = $item['icon'] ?? '';
				$item_price = seahivez_format_food_drinks_price_label(
					(int) ( $item['price'] ?? 0 ),
					(string) ( $item['unit'] ?? '' )
				);
				?>
				<div class="extras-food__item reveal<?php echo $index ? ' md:border-l md:border-slate-200 md:pl-8' : ''; ?><?php echo 1 === $index ? ' md:px-8' : ''; ?>">
					<?php if ( seahivez_has_icon( $item_icon ) ) : ?>
						<div class="extras-food__icon text-navy-900" aria-hidden="true">
							<?php seahivez_render_flexible_icon( $item_icon, array( 'class' => 'h-10 w-10' ) ); ?>
						</div>
					<?php endif; ?>

					<?php if ( $item_title ) : ?>
						<p class="extras-food__title mt-3 text-xs font-medium uppercase tracking-[0.14em] text-navy-800/80">
							<?php echo esc_html( $item_title ); ?>
						</p>
					<?php endif; ?>

					<p class="extras-food__price mt-2 text-lg font-medium leading-none tracking-tight text-navy-900 tabular-nums">
						<?php echo esc_html( $item_price ); ?>
					</p>

					<?php if ( ! empty( $item['description'] ) ) : ?>
						<p class="extras-food__description mt-2 max-w-xs text-sm leading-snug text-slate-600">
							<?php echo esc_html( (string) $item['description'] ); ?>
						</p>
					<?php endif; ?>

					<?php if ( ! empty( $item['description_list'] ) && is_array( $item['description_list'] ) ) : ?>
						<ul class="extras-food__list mt-2 space-y-1 text-sm text-slate-600" role="list">
							<?php foreach ( $item['description_list'] as $line ) : ?>
								<li><?php echo esc_html( (string) $line ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
