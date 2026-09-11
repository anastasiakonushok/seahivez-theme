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
		<ul class="amenities-bar extras-services__grid grid w-full grid-cols-1 gap-x-6 gap-y-4 border-t border-slate-200 pt-8 sm:grid-cols-2 lg:grid-cols-4 lg:gap-x-8" role="list">
			<?php foreach ( $items as $index => $service ) : ?>
				<?php
				$label = is_string( $service )
					? $service
					: (string) ( $service['label'] ?? '' );

				if ( '' === $label ) {
					continue;
				}
				?>
				<li class="amenities-bar__item reveal<?php echo $index ? ' reveal-delay-' . min( $index, 2 ) : ''; ?> flex min-w-0 items-center gap-3 text-sm text-slate-600">
					<svg class="amenities-bar__icon h-4 w-4 shrink-0 text-navy-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
						<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
					</svg>
					<span><?php echo esc_html( $label ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
