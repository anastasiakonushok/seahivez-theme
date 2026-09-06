<?php
/**
 * Flexible section: WYSIWYG content
 *
 * @package seahivez-theme
 */

$content = get_sub_field( 'content' );

if ( empty( $content ) ) {
	return;
}
?>

<section class="page-content section-spacing bg-warm-white">
	<div class="site-container">
		<article class="entry-content mx-auto max-w-3xl reveal prose prose-navy">
			<?php echo wp_kses_post( $content ); ?>
		</article>
	</div>
</section>
