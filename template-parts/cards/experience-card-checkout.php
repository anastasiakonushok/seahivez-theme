<?php
/**
 * Charter card checkout block (totals + book now).
 *
 * @package seahivez-theme
 *
 * @var array<string, mixed> $args {
 *     @type string $package_key       Package key.
 *     @type string $default_route_id  Default route id.
 *     @type int    $base_price        Base charter price.
 *     @type string $package_url       Package page URL.
 *     @type string $cta_label         View package label.
 *     @type bool   $show_view_package Show view package button.
 *     @type bool   $inline            Inside calculator panel.
 * }
 */

$package_key      = (string) ( $args['package_key'] ?? '' );
$default_route_id = (string) ( $args['default_route_id'] ?? '' );
$base_price       = (int) ( $args['base_price'] ?? 0 );
$package_url      = (string) ( $args['package_url'] ?? '' );
$cta_label        = (string) ( $args['cta_label'] ?? __( 'View package', 'seahivez-theme' ) );
$show_view_package = ! empty( $args['show_view_package'] );
$inline           = ! empty( $args['inline'] );

if ( '' === $package_key ) {
	return;
}

$checkout_class = 'experience-card__checkout';

if ( $inline ) {
	$checkout_class .= ' experience-card__checkout--inline mt-4 border-t border-slate-200 pt-4';
} else {
	$checkout_class .= ' mt-4 border-t border-slate-200 pt-4';
}

if ( $show_view_package ) {
	$checkout_class .= ' experience-card__checkout--dual';
}
?>

<div class="<?php echo esc_attr( $checkout_class ); ?>" data-charter-checkout>
	<p class="experience-card__section-label"><?php esc_html_e( 'Your total', 'seahivez-theme' ); ?></p>
	<p class="experience-card__checkout-total mt-2 text-2xl font-medium leading-none tracking-tight text-navy-900 tabular-nums" data-charter-checkout-total>
		<?php echo esc_html( '€' . number_format_i18n( $base_price ) ); ?>
	</p>
	<p class="experience-card__checkout-deposit mt-2 text-sm text-slate-600" data-charter-checkout-deposit>
		<?php
		printf(
			/* translators: %s: deposit amount */
			esc_html__( 'Refundable deposit %s paid separately at the port.', 'seahivez-theme' ),
			esc_html( '€' . number_format_i18n( seahivez_get_charter_security_deposit() ) )
		);
		?>
	</p>

	<div class="experience-card__cta-row mt-4<?php echo $show_view_package ? ' flex flex-col gap-3' : ''; ?>">
		<form class="experience-card__checkout-form<?php echo $show_view_package ? '' : ' mt-0'; ?>" method="post" action="<?php echo esc_url( seahivez_get_checkout_url() ); ?>" data-charter-checkout-form>
			<?php wp_nonce_field( 'seahivez_checkout_prepare', 'seahivez_checkout_nonce' ); ?>
			<input type="hidden" name="seahivez_checkout_action" value="prepare">
			<input type="hidden" name="package" value="<?php echo esc_attr( $package_key ); ?>" data-charter-checkout-package>
			<input type="hidden" name="route_id" value="<?php echo esc_attr( $default_route_id ); ?>" data-charter-checkout-route>
			<input type="hidden" name="extras" value="[]" data-charter-checkout-extras>
			<input type="hidden" name="quantities" value="{}" data-charter-checkout-quantities>

			<button type="submit" class="experience-card__book-btn btn-primary inline-flex w-full justify-center">
				<span><?php esc_html_e( 'Book now', 'seahivez-theme' ); ?></span>
				<?php seahivez_render_link_arrow_icon( 'sm' ); ?>
			</button>
		</form>

		<?php if ( $show_view_package && $package_url ) : ?>
			<a class="experience-card__view-package-btn btn-outline inline-flex w-full justify-center" href="<?php echo esc_url( $package_url ); ?>">
				<span><?php echo esc_html( $cta_label ); ?></span>
				<?php seahivez_render_link_arrow_icon( 'sm' ); ?>
			</a>
		<?php endif; ?>
	</div>
</div>
