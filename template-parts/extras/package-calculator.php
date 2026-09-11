<?php
/**
 * Extras page — package price calculator.
 *
 * @package seahivez-theme
 */

$data     = ! empty( $args['sections'] ) && is_array( $args['sections'] )
	? $args['sections']
	: seahivez_get_extras_page_sections();
$packages = ! empty( $data['calculator_packages'] ) && is_array( $data['calculator_packages'] )
	? $data['calculator_packages']
	: seahivez_get_extras_page_calculator_packages();
$configs    = ! empty( $data['calculator_configs'] ) && is_array( $data['calculator_configs'] )
	? $data['calculator_configs']
	: seahivez_get_extras_page_calculator_configs();
$calculator = ! empty( $data['calculator'] ) && is_array( $data['calculator'] )
	? $data['calculator']
	: array();

if ( empty( $packages ) || empty( $configs ) ) {
	return;
}

$first_package_id = '';

foreach ( $packages as $package ) {
	$package_id = (string) ( $package['id'] ?? '' );

	if ( $package_id && seahivez_package_supports_charter_calculator( $package_id ) ) {
		$first_package_id = $package_id;
		break;
	}
}

if ( '' === $first_package_id ) {
	$first_package_id = (string) ( $packages[0]['id'] ?? '' );
}
?>

<section class="extras-calculator section-spacing bg-sand-50" id="extras-calculator" aria-labelledby="extras-calculator-heading" data-extras-calculator>
	<div class="site-container">
		<div class="reveal max-w-2xl">
			<p class="section-eyebrow"><?php echo esc_html( (string) ( $calculator['eyebrow'] ?? __( 'Plan your charter', 'seahivez-theme' ) ) ); ?></p>
			<h2 id="extras-calculator-heading" class="section-heading mt-3">
				<?php echo esc_html( (string) ( $calculator['heading'] ?? __( 'Calculate your charter price', 'seahivez-theme' ) ) ); ?>
			</h2>
			<p class="type-body mt-4 text-slate-600">
				<?php echo esc_html( (string) ( $calculator['description'] ?? __( 'Choose your package, route and optional extras to see your estimated total.', 'seahivez-theme' ) ) ); ?>
			</p>
		</div>

		<div class="extras-calculator__layout mt-10 grid gap-8 lg:grid-cols-[minmax(0,320px)_minmax(0,1fr)] lg:gap-10" data-extras-calculator-layout>
			<div class="extras-calculator__sidebar reveal">
				<p class="extras-section__title"><?php esc_html_e( 'Charter package', 'seahivez-theme' ); ?></p>

				<div
					class="extras-calculator__package-tabs mt-4 flex flex-col gap-2"
					role="tablist"
					aria-label="<?php esc_attr_e( 'Choose your charter package', 'seahivez-theme' ); ?>"
				>
					<?php foreach ( $packages as $package ) : ?>
						<?php
						$package_id     = (string) ( $package['id'] ?? '' );
						$is_active      = $package_id === $first_package_id;
						$package_title  = (string) ( $package['title'] ?? '' );
						$duration       = (string) ( $package['duration'] ?? '' );
						$price          = (int) ( $package['price'] ?? 0 );
						$is_simple_tab  = ! seahivez_package_supports_charter_calculator( $package_id );
						?>
						<button
							type="button"
							class="extras-calculator__package-tab<?php echo $is_active ? ' is-active' : ''; ?>"
							role="tab"
							aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
							data-extras-package-tab="<?php echo esc_attr( $package_id ); ?>"
							data-extras-package-simple="<?php echo $is_simple_tab ? 'true' : 'false'; ?>"
						>
							<span class="extras-calculator__package-tab-title"><?php echo esc_html( $package_title ); ?></span>
							<?php if ( $duration ) : ?>
								<span class="extras-calculator__package-tab-meta"><?php echo esc_html( $duration ); ?></span>
							<?php endif; ?>
							<span class="extras-calculator__package-tab-price"><?php echo esc_html( '€' . number_format_i18n( $price ) ); ?></span>
						</button>
					<?php endforeach; ?>
				</div>

				<div class="extras-calculator__included mt-8 border-t border-slate-200 pt-6">
					<p class="extras-section__title"><?php esc_html_e( 'Included in charter', 'seahivez-theme' ); ?></p>
					<p class="extras-calculator__included-text mt-3 text-sm leading-relaxed text-slate-600">
						<?php echo esc_html( seahivez_get_charter_included_in_charter_summary() ); ?>
					</p>
				</div>

				<div class="extras-calculator__routes mt-8 hidden" data-extras-route-wrap hidden>
					<p class="extras-section__title"><?php esc_html_e( 'Route', 'seahivez-theme' ); ?></p>
					<div class="extras-calculator__route-tabs mt-3 flex flex-col gap-2" data-extras-route-list></div>
				</div>
			</div>

			<div class="extras-calculator__panel reveal reveal-delay-1" data-extras-calculator-panel>
				<div class="charter-calculator-inline charter-calculator-inline--page" data-extras-calculator-body></div>
			</div>
		</div>
	</div>

	<script type="application/json" id="seahivez-extras-calculator-config">
		<?php echo wp_json_encode( $configs ); ?>
	</script>
</section>
