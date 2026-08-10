<?php
/**
 * Homepage specifications section.
 *
 * @package seahivez-theme
 */

$groups = seahivez_get_home_specification_groups();
?>

<section class="specifications section-spacing bg-sand-50" id="specifications" aria-labelledby="specifications-heading">
	<div class="site-container">
		<div class="reveal max-w-2xl">
			<p class="section-eyebrow"><?php esc_html_e( 'The Yacht', 'seahivez-theme' ); ?></p>
			<h2 id="specifications-heading" class="section-heading mt-3">
				<?php esc_html_e( 'Specifications', 'seahivez-theme' ); ?>
			</h2>
			<p class="type-body mt-4">
				<?php esc_html_e( 'Everything you need to know about the Numarine 55 Fly.', 'seahivez-theme' ); ?>
			</p>
		</div>

		<div class="specifications__groups mt-12 space-y-12 lg:mt-14 lg:space-y-14">
			<?php foreach ( $groups as $group_index => $group ) : ?>
				<div class="spec-group reveal<?php echo $group_index ? ' reveal-delay-1' : ''; ?>">
					<h3 class="spec-group__title">
						<?php echo esc_html( $group['title'] ); ?>
					</h3>

					<ul class="spec-group__grid mt-6 <?php echo esc_attr( $group['grid_class'] ); ?>">
						<?php foreach ( $group['items'] as $item_index => $item ) : ?>
							<li class="spec-group__item border-b border-gray-200/80 pb-6<?php echo ! empty( $item['span_class'] ) ? ' ' . esc_attr( $item['span_class'] ) : ''; ?>">
								<?php
								get_template_part(
									'template-parts/cards/spec-item',
									null,
									array(
										'icon'      => $item['icon'],
										'label'     => $item['label'],
										'value'     => $item['value'] ?? '',
										'languages' => $item['languages'] ?? array(),
									)
								);
								?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
