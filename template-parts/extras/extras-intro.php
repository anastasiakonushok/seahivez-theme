<?php
/**
 * Extras page editorial intro.
 *
 * @package seahivez-theme
 */

$data = ! empty( $args['sections'] ) && is_array( $args['sections'] )
	? $args['sections']
	: seahivez_get_extras_page_sections();

$intro = (string) ( $data['intro'] ?? '' );

if ( '' === $intro ) {
	return;
}
?>

<section class="extras-intro section-spacing-sm bg-warm-white" aria-label="<?php esc_attr_e( 'Introduction', 'seahivez-theme' ); ?>">
	<div class="site-container">
		<p class="extras-intro__text reveal max-w-3xl type-body text-slate-600">
			<?php echo esc_html( $intro ); ?>
		</p>
	</div>
</section>
