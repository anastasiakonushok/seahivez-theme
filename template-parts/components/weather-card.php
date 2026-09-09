<?php
/**
 * Palma 3-day weather card (server-rendered).
 *
 * @package seahivez-theme
 */

$weather = seahivez_get_palma_weather();
?>

<div class="weather-card mt-8">
	<p class="weather-card__eyebrow"><?php esc_html_e( 'Palma weather', 'seahivez-theme' ); ?></p>

	<?php if ( is_array( $weather ) && ! empty( $weather['days'] ) ) : ?>
		<div class="weather-card__grid">
			<?php foreach ( $weather['days'] as $day ) : ?>
				<div class="weather-card__day">
					<p class="weather-card__day-label"><?php echo esc_html( $day['label'] ); ?></p>

					<?php if ( ! empty( $day['icon'] ) ) : ?>
						<img
							class="weather-card__day-icon"
							src="<?php echo esc_url( $day['icon'] ); ?>"
							alt=""
							width="36"
							height="36"
							decoding="async"
						/>
					<?php endif; ?>

					<p class="weather-card__day-temp"><?php echo esc_html( (string) $day['max'] ); ?>°</p>

					<p class="weather-card__day-range">
						<?php
						echo esc_html(
							sprintf(
								'%1$d° / %2$d°',
								(int) $day['min'],
								(int) $day['max']
							)
						);
						?>
					</p>

					<p class="weather-card__day-condition"><?php echo esc_html( $day['condition'] ); ?></p>

					<?php if ( null !== $day['rain'] ) : ?>
						<p class="weather-card__day-rain">
							<?php
							echo esc_html(
								sprintf(
									/* translators: %d: rain probability percentage */
									__( 'Rain %d%%', 'seahivez-theme' ),
									(int) $day['rain']
								)
							);
							?>
						</p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<p class="weather-card__unavailable"><?php esc_html_e( 'Weather unavailable', 'seahivez-theme' ); ?></p>
	<?php endif; ?>
</div>
