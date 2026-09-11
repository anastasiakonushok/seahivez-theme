<?php
/**
 * Extras page — paid extras list.
 *
 * @package seahivez-theme
 */

$data  = ! empty( $args['sections'] ) && is_array( $args['sections'] )
	? $args['sections']
	: seahivez_get_extras_page_sections();
$items = ! empty( $data['paid_items'] ) && is_array( $data['paid_items'] )
	? $data['paid_items']
	: array();

if ( empty( $items ) ) {
	return;
}
?>

<section class="extras-paid section-spacing bg-sand-50" aria-labelledby="extras-paid-heading">
	<div class="site-container">
		<div class="extras-paid__header reveal">
			<h2 id="extras-paid-heading" class="extras-section__title">
				<?php echo esc_html( (string) ( $data['paid_heading'] ?? __( 'Extra Paid', 'seahivez-theme' ) ) ); ?>
			</h2>
			<?php
			get_template_part(
				'template-parts/components/section-status',
				null,
				array(
					'label'   => (string) ( $data['paid_helper'] ?? __( 'Available on request', 'seahivez-theme' ) ),
					'variant' => 'request',
				)
			);
			?>
		</div>

		<ul class="extras-paid__list mt-10 divide-y divide-slate-200 border-t border-slate-200" role="list">
			<?php foreach ( $items as $index => $item ) : ?>
				<li class="extras-paid__item reveal<?php echo $index ? ' reveal-delay-' . min( $index % 3, 2 ) : ''; ?> py-8 first:pt-8">
					<div class="extras-paid__item-top flex gap-5">
						<?php if ( ! empty( $item['icon'] ) && seahivez_has_icon( $item['icon'] ) ) : ?>
							<div class="extras-paid__icon shrink-0 text-navy-900" aria-hidden="true">
								<?php seahivez_render_flexible_icon( $item['icon'], array( 'class' => 'h-10 w-10' ) ); ?>
							</div>
						<?php endif; ?>

						<div class="extras-paid__content min-w-0 flex-1">
							<div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2">
								<h3 class="extras-paid__title text-sm font-medium uppercase tracking-[0.14em] text-navy-900">
									<?php echo esc_html( (string) ( $item['title'] ?? '' ) ); ?>
								</h3>
								<p class="extras-paid__price text-lg font-medium leading-none tracking-tight text-navy-900 tabular-nums">
									<?php echo esc_html( seahivez_format_extra_price_label( (string) ( $item['price'] ?? '' ), false ) ); ?>
								</p>
							</div>

							<?php if ( ! empty( $item['description'] ) ) : ?>
								<p class="extras-paid__description mt-3 max-w-2xl text-sm leading-relaxed text-slate-600">
									<?php echo esc_html( (string) $item['description'] ); ?>
								</p>
							<?php endif; ?>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
