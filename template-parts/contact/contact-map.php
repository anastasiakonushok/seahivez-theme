<?php
/**
 * Contact map container (reuses Maps JS via data-seahivez-map).
 *
 * @package seahivez-theme
 */

$map = seahivez_get_contact_page_map();
?>

<div class="contact-map">
	<?php if ( ! empty( $map['eyebrow'] ) ) : ?>
		<p class="type-eyebrow text-gray-500"><?php echo esc_html( $map['eyebrow'] ); ?></p>
	<?php endif; ?>
	<h3 class="mt-2 text-xl font-semibold text-navy-900">
		<?php echo esc_html( $map['heading'] ); ?>
	</h3>

	<div
		class="contact-map__frame mt-4 overflow-hidden rounded-md border border-gray-200 bg-sand-100"
		data-seahivez-map
		data-lat="<?php echo esc_attr( $map['lat'] ); ?>"
		data-lng="<?php echo esc_attr( $map['lng'] ); ?>"
		data-label="<?php echo esc_attr( $map['label'] ); ?>"
		data-place="<?php echo esc_attr( $map['place'] ); ?>"
		data-maps-url="<?php echo esc_url( $map['maps_url'] ); ?>"
	>
		<div class="seahivez-map__loading" data-map-loading aria-hidden="true">
			<span class="seahivez-map__loading-pulse"></span>
		</div>

		<div
			class="contact-map__canvas w-full"
			data-map-canvas
			role="region"
			aria-label="<?php esc_attr_e( 'Map of SeaHivez departure location', 'seahivez-theme' ); ?>"
			hidden
		></div>
		<div class="contact-map__fallback" data-map-fallback hidden>
			<p class="font-medium text-navy-900"><?php echo esc_html( $map['place'] ); ?></p>
			<p class="mt-2"><?php esc_html_e( 'Map is temporarily unavailable.', 'seahivez-theme' ); ?></p>
		</div>
	</div>
</div>
