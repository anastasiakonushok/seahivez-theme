<?php
/**
 * Homepage quick specifications bar.
 *
 * @package seahivez-theme
 */

$items = seahivez_get_home_quick_specs();
?>

<section class="specs-bar relative z-20 -mt-8 md:-mt-10" aria-label="<?php esc_attr_e( 'Yacht quick specifications', 'seahivez-theme' ); ?>">
	<div class="site-container">
		<div class="specs-bar__inner reveal rounded-md border border-gray-200 bg-sand-50 px-4 py-6 shadow-sm md:px-8 md:py-8">
			<ul class="specs-bar__grid grid w-full grid-cols-2 gap-x-4 gap-y-8 md:grid-cols-3 md:gap-x-6 lg:grid-cols-6 lg:gap-x-0 lg:gap-y-0">
				<?php foreach ( $items as $item ) : ?>
					<li class="specs-bar__item w-full min-w-0">
						<?php
						get_template_part(
							'template-parts/cards/spec-item',
							null,
							array(
								'icon'  => $item['icon'],
								'label' => $item['label'],
								'value' => $item['value'],
								'class' => 'spec-item--bar',
							)
						);
						?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
