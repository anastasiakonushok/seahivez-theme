<?php
/**
 * Template Name: Flexible Page
 *
 * @package seahivez-theme
 */

get_header();
?>

<main id="primary" class="site-main flex-1">

	<?php
	while ( have_posts() ) :
		the_post();

		if ( seahivez_has_flexible_sections() ) {
			seahivez_render_flexible_sections();
		} else {
			?>
			<section class="page-content section-spacing bg-warm-white">
				<div class="site-container">
					<article <?php post_class( 'entry-content mx-auto max-w-3xl reveal' ); ?>>
						<?php the_content(); ?>
					</article>
				</div>
			</section>
			<?php
		}
	endwhile;
	?>

</main>

<?php
get_footer();
