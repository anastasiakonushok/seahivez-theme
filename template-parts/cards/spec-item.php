<?php
/**
 * Specification item card.
 *
 * @package seahivez-theme
 *
 * @var array $args {
 *     @type string       $icon      Spec icon slug.
 *     @type string       $label     Uppercase label.
 *     @type string       $value     Value text (ignored when languages is set).
 *     @type array|string $languages Optional language identifiers for flag chips.
 *     @type string       $class     Additional classes.
 * }
 */

$args = wp_parse_args(
	$args ?? array(),
	array(
		'icon'      => '',
		'label'     => '',
		'value'     => '',
		'languages' => array(),
		'class'     => '',
	)
);

$has_languages = ! empty( $args['languages'] );
?>

<div class="spec-item <?php echo esc_attr( $args['class'] ); ?>">
	<?php if ( ! empty( $args['icon'] ) ) : ?>
		<div class="spec-item__icon text-navy-900" aria-hidden="true">
			<?php seahivez_render_spec_icon( $args['icon'], array( 'class' => 'spec-item__icon-svg h-7 w-7 md:h-8 md:w-8' ) ); ?>
		</div>
	<?php endif; ?>

	<p class="spec-item__label">
		<?php echo esc_html( $args['label'] ); ?>
	</p>

	<?php if ( $has_languages ) : ?>
		<div class="spec-item__value spec-item__value--languages">
			<?php seahivez_render_language_chips( $args['languages'] ); ?>
		</div>
	<?php else : ?>
		<p class="spec-item__value">
			<?php echo esc_html( $args['value'] ); ?>
		</p>
	<?php endif; ?>
</div>
