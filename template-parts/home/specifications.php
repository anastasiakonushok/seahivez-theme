<?php
/**
 * Homepage specifications section.
 *
 * @package seahivez-theme
 */

$section_args    = ! empty( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : array();
$default_groups  = seahivez_get_home_specification_groups();
$groups          = ! empty( $section_args['groups'] ) ? $section_args['groups'] : $default_groups;
$eyebrow         = ! empty( $section_args['eyebrow'] ) ? $section_args['eyebrow'] : __( 'The Yacht', 'seahivez-theme' );
$heading         = ! empty( $section_args['heading'] ) ? $section_args['heading'] : __( 'Specifications', 'seahivez-theme' );
$intro           = ! empty( $section_args['intro'] ) ? $section_args['intro'] : __( 'Everything you need to know about the Numarine 55 Fly.', 'seahivez-theme' );

foreach ( $groups as $group_index => $group ) {
	if ( empty( $group['grid_class'] ) && ! empty( $default_groups[ $group_index ]['grid_class'] ) ) {
		$groups[ $group_index ]['grid_class'] = $default_groups[ $group_index ]['grid_class'];
	} elseif ( empty( $group['grid_class'] ) ) {
		$groups[ $group_index ]['grid_class'] = 'grid grid-cols-2 gap-x-6 gap-y-8 md:grid-cols-3';
	}
}

if ( empty( $groups ) ) {
	return;
}
?>

<section class="specifications section-spacing bg-sand-50" id="specifications" aria-labelledby="specifications-heading">
	<div class="site-container">
		<div class="reveal max-w-2xl">
			<?php if ( $eyebrow ) : ?>
				<p class="section-eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<?php endif; ?>
			<?php if ( $heading ) : ?>
				<h2 id="specifications-heading" class="section-heading mt-3">
					<?php echo esc_html( $heading ); ?>
				</h2>
			<?php endif; ?>
			<?php if ( $intro ) : ?>
				<p class="type-body mt-4">
					<?php echo esc_html( $intro ); ?>
				</p>
			<?php endif; ?>
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
