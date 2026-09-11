<?php
/**
 * Compact route / included / not-included block for experience cards.
 *
 * @package seahivez-theme
 *
 * @var array<string, mixed> $args Charter details payload.
 */

$details = $args ?? array();

if ( empty( $details['type'] ) ) {
	return;
}

$included_layout = ! empty( $details['included_layout'] ) ? $details['included_layout'] : 'grid';
?>

<div class="experience-card__details">
	<?php if ( ! empty( $details['sections'] ) && is_array( $details['sections'] ) ) : ?>
		<?php foreach ( $details['sections'] as $section ) : ?>
			<div class="experience-card__detail-block">
				<?php if ( ! empty( $section['label'] ) ) : ?>
					<p class="experience-card__section-label"><?php echo esc_html( $section['label'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $section['text'] ) ) : ?>
					<p class="experience-card__section-text"><?php echo esc_html( $section['text'] ); ?></p>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>

	<?php if ( ! empty( $details['routes'] ) && is_array( $details['routes'] ) ) : ?>
		<div class="experience-card__detail-block">
			<?php if ( ! empty( $details['routes_label'] ) ) : ?>
				<p class="experience-card__section-label"><?php echo esc_html( $details['routes_label'] ); ?></p>
			<?php endif; ?>

			<div class="experience-card__routes">
				<?php foreach ( $details['routes'] as $route_index => $route ) : ?>
					<div class="experience-card__route<?php echo $route_index ? ' experience-card__route--divider' : ''; ?>">
						<div class="experience-card__route-row">
							<?php if ( ! empty( $route['number'] ) ) : ?>
								<span class="experience-card__route-badge"><?php echo esc_html( $route['number'] ); ?></span>
							<?php endif; ?>

							<div class="experience-card__route-content">
								<?php if ( ! empty( $route['name'] ) ) : ?>
									<p class="experience-card__route-name"><?php echo esc_html( $route['name'] ); ?></p>
								<?php endif; ?>

								<?php if ( ! empty( $route['path'] ) ) : ?>
									<p class="experience-card__route-path"><?php echo esc_html( $route['path'] ); ?></p>
								<?php endif; ?>

								<?php if ( ! empty( $route['note'] ) ) : ?>
									<p class="experience-card__route-note"><?php echo esc_html( $route['note'] ); ?></p>
								<?php endif; ?>

								<?php if ( ! empty( $route['fuel_label'] ) && ! empty( $route['fuel_cost'] ) ) : ?>
									<div class="experience-card__price-row">
										<span><?php echo esc_html( $route['fuel_label'] ); ?></span>
										<span class="experience-card__price-value"><?php echo esc_html( $route['fuel_cost'] ); ?></span>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $details['included'] ) && is_array( $details['included'] ) ) : ?>
		<div class="experience-card__status experience-card__status--included">
			<p class="experience-card__status-label experience-card__status-label--included">
				<span class="experience-card__status-dot experience-card__status-dot--included" aria-hidden="true"></span>
				<?php esc_html_e( 'Included', 'seahivez-theme' ); ?>
			</p>

			<?php if ( 'inline' === $included_layout ) : ?>
				<p class="experience-card__included-inline">
					<?php echo esc_html( implode( ' · ', $details['included'] ) ); ?>
				</p>
			<?php else : ?>
				<ul class="experience-card__included-grid" role="list">
					<?php foreach ( $details['included'] as $item ) : ?>
						<li><?php echo esc_html( $item ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $details['not_included'] ) && is_array( $details['not_included'] ) ) : ?>
		<div class="experience-card__status experience-card__status--excluded">
			<p class="experience-card__status-label experience-card__status-label--excluded">
				<span class="experience-card__status-dot experience-card__status-dot--excluded" aria-hidden="true"></span>
				<?php esc_html_e( 'Not included', 'seahivez-theme' ); ?>
			</p>

			<div class="experience-card__price-list">
				<?php foreach ( $details['not_included'] as $row ) : ?>
					<?php
					$label = $row['label'] ?? '';
					$cost  = $row['cost'] ?? '';

					if ( '' === $label ) {
						continue;
					}
					?>
					<div class="experience-card__price-row experience-card__price-row--list">
						<span><?php echo esc_html( $label ); ?></span>
						<?php if ( $cost ) : ?>
							<span class="experience-card__price-value"><?php echo esc_html( $cost ); ?></span>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
</div>
