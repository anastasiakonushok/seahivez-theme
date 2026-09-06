<?php
/**
 * Yacht specifications (reuses spec-item cards).
 *
 * @package seahivez-theme
 */

$groups  = seahivez_get_yacht_specification_groups();
$header  = seahivez_get_yacht_specifications_header();
?>

<section class="yacht-specifications section-spacing bg-sand-50" aria-labelledby="yacht-specs-heading">
	<div class="site-container">
		<div class="reveal max-w-2xl">
			<?php if ( ! empty( $header['eyebrow'] ) ) : ?>
				<p class="section-eyebrow"><?php echo esc_html( $header['eyebrow'] ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $header['heading'] ) ) : ?>
				<h2 id="yacht-specs-heading" class="section-heading mt-3">
					<?php echo esc_html( $header['heading'] ); ?>
				</h2>
			<?php endif; ?>
		</div>

		<div class="mt-12 space-y-14">
			<?php foreach ( $groups as $group_index => $group ) : ?>
				<div class="reveal<?php echo $group_index ? ' reveal-delay-1' : ''; ?>">
					<h3 class="spec-group__title"><?php echo esc_html( $group['title'] ); ?></h3>
					<div class="<?php echo esc_attr( $group['grid_class'] ); ?> mt-8">
						<?php foreach ( $group['items'] as $item ) : ?>
							<?php
							get_template_part(
								'template-parts/cards/spec-item',
								null,
								array_merge(
									$item,
									array(
										'class' => isset( $item['span_class'] ) ? $item['span_class'] : '',
									)
								)
							);
							?>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
