<?php
/**
 * Extras page — included services row.
 *
 * @package seahivez-theme
 */

$data  = ! empty( $args['sections'] ) && is_array( $args['sections'] )
	? $args['sections']
	: seahivez_get_extras_page_sections();
$items = ! empty( $data['included_services'] ) && is_array( $data['included_services'] )
	? $data['included_services']
	: array();

if ( empty( $items ) ) {
	return;
}
?>

<section class="extras-services bg-warm-white pb-12 md:pb-16" aria-label="<?php esc_attr_e( 'Included services', 'seahivez-theme' ); ?>">
	<div class="site-container">
		<ul class="extras-services__grid grid grid-cols-1 gap-x-6 gap-y-4 border-t border-slate-200 pt-8 sm:grid-cols-2 lg:grid-cols-4 lg:gap-x-8" role="list">
			<?php foreach ( $items as $index => $service ) : ?>
				<?php
				$label = is_string( $service )
					? $service
					: (string) ( $service['label'] ?? '' );
				$icon  = is_array( $service ) ? ( $service['icon'] ?? '' ) : '';

				if ( '' === $label ) {
					continue;
				}
				?>
				<li class="extras-services__item reveal<?php echo $index ? ' reveal-delay-' . min( $index, 2 ) : ''; ?> flex min-w-0 items-center gap-3 text-sm text-slate-600">
					<span class="extras-services__icon shrink-0 text-navy-900" aria-hidden="true">
						<?php if ( $icon && seahivez_has_icon( $icon ) ) : ?>
							<?php seahivez_render_flexible_icon( $icon, array( 'class' => 'h-6 w-6' ) ); ?>
						<?php else : ?>
							<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
							</svg>
						<?php endif; ?>
					</span>
					<span><?php echo esc_html( $label ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
