<?php
/**
 * Contact map container (reuses Maps JS via data-seahivez-map).
 *
 * @package seahivez-theme
 */

$port = seahivez_get_port_location();
?>

<div class="contact-map">
	<p class="type-eyebrow text-gray-500"><?php esc_html_e( 'Location', 'seahivez-theme' ); ?></p>
	<h3 class="mt-2 text-xl font-semibold text-navy-900">
		<?php echo esc_html( ! empty( $port['label'] ) ? $port['label'] : __( "S'Arenal / Mallorca", 'seahivez-theme' ) ); ?>
	</h3>

	<div
		class="contact-map__frame mt-4 overflow-hidden rounded-md border border-gray-200 bg-sand-100"
		data-seahivez-map
		data-lat="<?php echo esc_attr( isset( $port['lat'] ) ? (string) $port['lat'] : '' ); ?>"
		data-lng="<?php echo esc_attr( isset( $port['lng'] ) ? (string) $port['lng'] : '' ); ?>"
		data-label="<?php echo esc_attr( isset( $port['label'] ) ? $port['label'] : 'SeaHivez' ); ?>"
		data-place="<?php echo esc_attr( isset( $port['place'] ) ? $port['place'] : '' ); ?>"
		data-maps-url="<?php echo esc_url( isset( $port['maps_url'] ) ? $port['maps_url'] : '' ); ?>"
	>
		<div
			class="contact-map__canvas aspect-[16/10] w-full"
			data-map-canvas
			role="region"
			aria-label="<?php esc_attr_e( 'Map of SeaHivez departure location', 'seahivez-theme' ); ?>"
		></div>
		<div class="contact-map__fallback p-6 text-sm text-gray-500" data-map-fallback hidden>
			<?php esc_html_e( 'Map unavailable. Please check the location details or try again later.', 'seahivez-theme' ); ?>
		</div>
	</div>
</div>
