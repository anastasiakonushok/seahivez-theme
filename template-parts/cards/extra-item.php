<?php
/**
 * Extra / toy item card.
 *
 * Used by the Toys & Extras section. Accepts data via $args from get_template_part().
 *
 * @package seahivez-theme
 *
 * @var array $args {
 *     @type string $icon        Allowed icon slug (see seahivez_get_allowed_toy_icons()).
 *     @type string $title       Item title.
 *     @type string $description Optional short description.
 *     @type string $price       Price value, e.g. "300" or "€300" (ignored when included).
 *     @type bool   $included    Whether the item is included in the charter.
 *     @type string $class       Additional wrapper classes.
 * }
 */

$args = wp_parse_args(
	$args ?? array(),
	array(
		'icon'        => '',
		'title'       => '',
		'description' => '',
		'price'       => '',
		'included'    => false,
		'class'       => '',
	)
);

if ( empty( $args['title'] ) ) {
	return;
}

$is_included  = (bool) $args['included'];
$price_label  = seahivez_format_extra_price_label( $args['price'], $is_included );
$status_class = $is_included ? 'extra-item--included' : 'extra-item--paid';
$icon_slug    = sanitize_key( str_replace( '_', '-', $args['icon'] ) );
?>

<article class="extra-item group <?php echo esc_attr( trim( $status_class . ' ' . $args['class'] ) ); ?>">
	<?php if ( $icon_slug && seahivez_get_toy_icon_path( $icon_slug ) ) : ?>
		<div class="extra-item__icon-wrap" aria-hidden="true">
			<?php
			seahivez_render_toy_icon(
				$icon_slug,
				array(
					'class' => 'extra-item__icon h-10 w-10',
				)
			);
			?>
		</div>
	<?php endif; ?>

	<div class="extra-item__body">
		<h3 class="extra-item__title">
			<?php echo esc_html( $args['title'] ); ?>
		</h3>

		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="extra-item__description">
				<?php echo esc_html( $args['description'] ); ?>
			</p>
		<?php endif; ?>

		<?php if ( $price_label ) : ?>
			<p class="extra-item__price">
				<?php echo esc_html( $price_label ); ?>
			</p>
		<?php endif; ?>
	</div>
</article>
