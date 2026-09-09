<?php
/**
 * Palma today weather card (server-rendered).
 *
 * @package seahivez-theme
 */

$weather       = seahivez_get_palma_weather();
$fallback_date = seahivez_get_palma_weather_fallback_date();
?>

<div class="weather-card mt-8">
	<p class="weather-card__eyebrow"><?php esc_html_e( 'Palma today', 'seahivez-theme' ); ?></p>

	<?php if ( is_array( $weather ) ) : ?>
		<div class="weather-card__hero">
			<?php if ( ! empty( $weather['icon'] ) ) : ?>
				<img
					class="weather-card__icon"
					src="<?php echo esc_url( $weather['icon'] ); ?>"
					alt=""
					width="40"
					height="40"
					decoding="async"
				/>
			<?php endif; ?>
			<p class="weather-card__temp"><?php echo esc_html( (string) $weather['max'] ); ?>°C</p>
		</div>

		<p class="weather-card__condition"><?php echo esc_html( $weather['condition'] ); ?></p>

		<p class="weather-card__range">
			<?php
			echo esc_html(
				sprintf(
					'%1$d° / %2$d°',
					(int) $weather['min'],
					(int) $weather['max']
				)
			);
			?>
		</p>

		<?php if ( null !== $weather['rain'] || null !== $weather['wind'] ) : ?>
			<p class="weather-card__meta">
				<?php
				$meta_parts = array();

				if ( null !== $weather['rain'] ) {
					$meta_parts[] = sprintf(
						/* translators: %d: rain probability percentage */
						__( 'Rain %d%%', 'seahivez-theme' ),
						(int) $weather['rain']
					);
				}

				if ( null !== $weather['wind'] ) {
					$meta_parts[] = sprintf(
						/* translators: %d: wind speed in km/h */
						__( 'Wind %d km/h', 'seahivez-theme' ),
						(int) $weather['wind']
					);
				}

				echo esc_html( implode( ' · ', $meta_parts ) );
				?>
			</p>
		<?php endif; ?>

		<p class="weather-card__date"><?php echo esc_html( $weather['date'] ); ?></p>
	<?php else : ?>
		<p class="weather-card__unavailable"><?php esc_html_e( 'Weather unavailable', 'seahivez-theme' ); ?></p>
		<p class="weather-card__date"><?php echo esc_html( $fallback_date ); ?></p>
	<?php endif; ?>
</div>
