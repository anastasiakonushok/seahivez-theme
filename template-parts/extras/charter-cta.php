<?php
/**
 * Extras page — charter calculator CTA.
 *
 * @package seahivez-theme
 */

$data = ! empty( $args['sections'] ) && is_array( $args['sections'] )
	? $args['sections']
	: seahivez_get_extras_page_sections();
$cta  = ! empty( $data['charter_cta'] ) && is_array( $data['charter_cta'] )
	? $data['charter_cta']
	: seahivez_get_extras_charter_cta();
?>

<section class="extras-charter-cta section-spacing-sm bg-warm-white" aria-labelledby="extras-charter-cta-heading">
	<div class="site-container">
		<div class="extras-charter-cta__inner reveal rounded-md border border-slate-200 bg-sand-50 px-6 py-8 md:px-10 md:py-10">
			<p class="section-eyebrow"><?php echo esc_html( (string) ( $cta['eyebrow'] ?? '' ) ); ?></p>
			<h2 id="extras-charter-cta-heading" class="section-heading mt-3 max-w-2xl">
				<?php echo esc_html( (string) ( $cta['text'] ?? '' ) ); ?>
			</h2>

			<?php if ( ! empty( $cta['cta_url'] ) && ! empty( $cta['cta_label'] ) ) : ?>
				<a class="btn-outline section-outline-cta link-arrow group mt-6" href="<?php echo esc_url( (string) $cta['cta_url'] ); ?>">
					<?php echo esc_html( (string) $cta['cta_label'] ); ?>
					<?php seahivez_render_link_arrow_icon( 'md' ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
