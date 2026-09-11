<?php
/**
 * Compact route + included summary for homepage experience cards.
 *
 * @package seahivez-theme
 *
 * @var array<string, mixed> $args Charter card display payload.
 */

$card = $args ?? array();

if ( empty( $card['package_key'] ) ) {
	return;
}

$package_key      = (string) $card['package_key'];
$routes           = ! empty( $card['routes'] ) && is_array( $card['routes'] ) ? $card['routes'] : array();
$route_choice     = ! empty( $card['route_choice'] );
$default_route_id = (string) ( $card['default_route_id'] ?? '' );
$first_route      = $routes[0] ?? array();
?>

<div
	class="experience-card__charter"
	data-charter-card
	data-package-key="<?php echo esc_attr( $package_key ); ?>"
	data-default-route="<?php echo esc_attr( $default_route_id ); ?>"
>
	<div class="experience-card__route-block mt-4 border-t border-slate-200 pt-4">
		<p class="experience-card__section-label"><?php esc_html_e( 'Route', 'seahivez-theme' ); ?></p>

		<?php if ( $route_choice && count( $routes ) > 1 ) : ?>
			<div
				class="experience-card__route-segments mt-2 flex flex-wrap gap-2"
				role="tablist"
				aria-label="<?php esc_attr_e( 'Choose your route', 'seahivez-theme' ); ?>"
			>
				<?php foreach ( $routes as $route ) : ?>
					<?php
					$route_id   = (string) ( $route['id'] ?? '' );
					$is_default = $route_id === $default_route_id;
					$tab_number = trim( (string) ( $route['number'] ?? '' ) );
					$tab_name   = trim( (string) ( $route['name'] ?? '' ) );
					$tab_label  = trim( $tab_number . ' ' . $tab_name );
					?>
					<button
						type="button"
						class="experience-card__route-segment<?php echo $is_default ? ' is-active' : ''; ?>"
						role="tab"
						aria-selected="<?php echo $is_default ? 'true' : 'false'; ?>"
						data-route-tab="<?php echo esc_attr( $route_id ); ?>"
					>
						<?php echo esc_html( $tab_label ); ?>
					</button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="experience-card__route-display mt-3" data-route-display>
			<?php foreach ( $routes as $route_index => $route ) : ?>
				<?php
				$route_id    = (string) ( $route['id'] ?? '' );
				$is_visible  = $route_choice ? ( $route_id === $default_route_id ) : ( 0 === $route_index );
				$route_title = trim( (string) ( $route['number'] ?? '' ) . ' ' . (string) ( $route['name'] ?? '' ) );
				?>
				<div
					class="experience-card__route-panel<?php echo $is_visible ? '' : ' hidden'; ?>"
					data-route-panel="<?php echo esc_attr( $route_id ); ?>"
					<?php echo $is_visible ? '' : ' hidden'; ?>
				>
					<?php if ( $route_title ) : ?>
						<p class="experience-card__route-heading"><?php echo esc_html( $route_title ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $route['path'] ) ) : ?>
						<p class="experience-card__route-path mt-1.5 text-sm leading-relaxed text-slate-600">
							<?php echo esc_html( $route['path'] ); ?>
						</p>
					<?php endif; ?>

					<?php if ( ! empty( $route['note'] ) ) : ?>
						<p class="experience-card__route-note mt-1.5 text-sm text-slate-500">
							<?php echo esc_html( $route['note'] ); ?>
						</p>
					<?php endif; ?>

				</div>
			<?php endforeach; ?>

			<?php if ( empty( $routes ) && ! empty( $first_route['path'] ) ) : ?>
				<p class="experience-card__route-path text-sm leading-relaxed text-slate-600">
					<?php echo esc_html( $first_route['path'] ); ?>
				</p>
			<?php endif; ?>
		</div>
	</div>

	<div class="experience-card__included mt-4 border-t border-slate-200 pt-4">
		<?php if ( ! empty( $card['included_label'] ) ) : ?>
			<p class="experience-card__status-label experience-card__status-label--included">
				<span class="experience-card__status-dot experience-card__status-dot--included" aria-hidden="true"></span>
				<?php echo esc_html( $card['included_label'] ); ?>
			</p>
		<?php endif; ?>

		<?php if ( ! empty( $card['included_compact'] ) ) : ?>
			<p class="experience-card__included-compact mt-1.5 text-sm leading-snug text-navy-900">
				<?php echo esc_html( (string) $card['included_compact'] ); ?>
			</p>
		<?php endif; ?>

		<?php if ( ! empty( $card['included_summary'] ) ) : ?>
			<p class="experience-card__included-summary mt-1 text-sm leading-snug text-slate-600">
				<?php echo esc_html( $card['included_summary'] ); ?>
			</p>
		<?php endif; ?>
	</div>

	<button
		type="button"
		class="experience-card__calculator-trigger mt-4"
		data-charter-calculator-toggle
		aria-expanded="false"
		aria-controls="charter-calculator-<?php echo esc_attr( $package_key ); ?>"
	>
		<span data-charter-calculator-toggle-text><?php esc_html_e( 'Calculate final price', 'seahivez-theme' ); ?></span>
		<span class="experience-card__calculator-trigger-icon" data-charter-calculator-toggle-icon aria-hidden="true">↓</span>
	</button>

	<div
		id="charter-calculator-<?php echo esc_attr( $package_key ); ?>"
		class="charter-calculator-inline mt-3 hidden"
		data-charter-calculator-panel
		hidden
	>
		<div data-charter-calculator-body></div>
	</div>
</div>
