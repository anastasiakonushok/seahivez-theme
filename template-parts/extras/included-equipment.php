<?php
/**
 * Extras page — included equipment grid.
 *
 * @package seahivez-theme
 */

$data  = ! empty( $args['sections'] ) && is_array( $args['sections'] )
	? $args['sections']
	: seahivez_get_extras_page_sections();
$items = ! empty( $data['included_equipment'] ) && is_array( $data['included_equipment'] )
	? $data['included_equipment']
	: array();

if ( empty( $items ) ) {
	return;
}
?>

<section class="extras-included section-spacing bg-warm-white" aria-labelledby="extras-included-heading">
	<div class="site-container">
		<div class="extras-included__header reveal">
			<h2 id="extras-included-heading" class="extras-section__title">
				<?php echo esc_html( (string) ( $data['included_heading'] ?? __( 'Included', 'seahivez-theme' ) ) ); ?>
			</h2>
			<?php
			get_template_part(
				'template-parts/components/section-status',
				null,
				array(
					'label'   => (string) ( $data['included_helper'] ?? __( 'Already included in your charter', 'seahivez-theme' ) ),
					'variant' => 'included',
				)
			);
			?>
		</div>

		<ul class="extras-included__grid mt-10 grid grid-cols-1 gap-0 border-t border-slate-200 md:grid-cols-2" role="list">
			<?php foreach ( $items as $index => $item ) : ?>
				<li class="extras-included__item reveal<?php echo $index % 2 ? ' md:border-l md:border-slate-200' : ''; ?><?php echo $index ? ' border-t border-slate-200 md:border-t-0' : ''; ?><?php echo $index >= 2 ? ' md:border-t md:border-slate-200' : ''; ?>">
					<div class="extras-included__item-inner py-8 md:px-8 md:py-10<?php echo 0 === $index % 2 ? ' md:pr-8 md:pl-0' : ' md:pl-8'; ?>">
						<?php if ( ! empty( $item['icon'] ) && seahivez_has_icon( $item['icon'] ) ) : ?>
							<div class="extras-included__icon text-navy-900" aria-hidden="true">
								<?php seahivez_render_flexible_icon( $item['icon'], array( 'class' => 'h-10 w-10' ) ); ?>
							</div>
						<?php endif; ?>

						<div class="extras-included__heading mt-4 flex flex-wrap items-baseline justify-between gap-3">
							<h3 class="extras-included__title text-sm font-medium uppercase tracking-[0.14em] text-navy-900">
								<?php echo esc_html( (string) ( $item['title'] ?? '' ) ); ?>
							</h3>
							<?php if ( ! empty( $item['status'] ) ) : ?>
								<p class="extras-included__status text-xs font-medium uppercase tracking-[0.12em] text-slate-500">
									<?php echo esc_html( (string) $item['status'] ); ?>
								</p>
							<?php endif; ?>
						</div>

						<?php if ( ! empty( $item['description'] ) ) : ?>
							<p class="extras-included__description mt-3 max-w-md text-sm leading-relaxed text-slate-600">
								<?php echo esc_html( (string) $item['description'] ); ?>
							</p>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
