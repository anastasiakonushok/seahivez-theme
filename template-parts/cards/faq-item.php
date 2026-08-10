<?php
/**
 * FAQ accordion item.
 *
 * @package seahivez-theme
 *
 * @var array $args {
 *     @type int    $index    Item index.
 *     @type string $question Question text.
 *     @type string $answer   Answer text.
 *     @type bool   $open     Whether the item starts open.
 * }
 */

$args = wp_parse_args(
	$args ?? array(),
	array(
		'index'    => 0,
		'question' => '',
		'answer'   => '',
		'open'     => false,
	)
);

if ( empty( $args['question'] ) || empty( $args['answer'] ) ) {
	return;
}

$index     = absint( $args['index'] );
$is_open   = ! empty( $args['open'] );
$panel_id  = 'faq-panel-' . $index;
$button_id = 'faq-button-' . $index;
?>

<div class="faq-item<?php echo $is_open ? ' is-open' : ''; ?>" data-faq-item>
	<h3 class="faq-item__question">
		<button
			id="<?php echo esc_attr( $button_id ); ?>"
			class="faq-item__trigger"
			type="button"
			aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>"
			aria-controls="<?php echo esc_attr( $panel_id ); ?>"
			data-faq-trigger
		>
			<span class="faq-item__question-text"><?php echo esc_html( $args['question'] ); ?></span>
			<span class="faq-item__icon" aria-hidden="true">
				<svg class="faq-item__icon-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
					<path d="M12 5v14M5 12h14"/>
				</svg>
				<svg class="faq-item__icon-minus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
					<path d="M5 12h14"/>
				</svg>
			</span>
		</button>
	</h3>

	<div
		id="<?php echo esc_attr( $panel_id ); ?>"
		class="faq-item__panel"
		role="region"
		aria-labelledby="<?php echo esc_attr( $button_id ); ?>"
		aria-hidden="<?php echo $is_open ? 'false' : 'true'; ?>"
		data-faq-panel
	>
		<div class="faq-item__panel-inner">
			<div class="faq-item__answer type-body">
				<?php echo esc_html( $args['answer'] ); ?>
			</div>
		</div>
	</div>
</div>
