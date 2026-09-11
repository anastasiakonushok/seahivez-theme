<?php
/**
 * Extras page — good to know notes.
 *
 * @package seahivez-theme
 */

$data  = ! empty( $args['sections'] ) && is_array( $args['sections'] )
	? $args['sections']
	: seahivez_get_extras_page_sections();
$items = ! empty( $data['good_to_know'] ) && is_array( $data['good_to_know'] )
	? $data['good_to_know']
	: array();

if ( empty( $items ) ) {
	return;
}
?>

<section class="extras-notes section-spacing-sm bg-sand-50" aria-labelledby="extras-notes-heading">
	<div class="site-container">
		<div class="extras-notes__inner reveal max-w-3xl border-t border-slate-200 pt-8">
			<h2 id="extras-notes-heading" class="extras-section__title">
				<?php echo esc_html( (string) ( $data['good_to_know_title'] ?? __( 'Good to know', 'seahivez-theme' ) ) ); ?>
			</h2>

			<ul class="extras-notes__list mt-5 space-y-3 text-sm leading-relaxed text-slate-600" role="list">
				<?php foreach ( $items as $item ) : ?>
					<li class="extras-notes__item flex gap-3">
						<span class="extras-notes__bullet mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-navy-900/50" aria-hidden="true"></span>
						<span><?php echo esc_html( (string) $item ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
