<?php
/**
 * Default page template.
 *
 * @package seahivez-theme
 */

get_header();
?>

<main id="primary" class="site-main flex-1">
	<?php
	while ( have_posts() ) :
		the_post();

		$hero_image = '';
		if ( has_post_thumbnail() ) {
			$hero_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
		}

		get_template_part(
			'template-parts/page/page-hero',
			null,
			seahivez_get_page_hero_defaults(
				array(
					'eyebrow'     => __( 'SeaHivez', 'seahivez-theme' ),
					'heading'     => get_the_title(),
					'description' => has_excerpt() ? get_the_excerpt() : '',
					'image'       => $hero_image ? $hero_image : seahivez_get_theme_image_uri( 'assets/images/photo/2.jpg' ),
					'image_alt'   => get_the_title(),
					'compact'     => true,
				)
			)
		);
		?>

		<section class="page-content section-spacing bg-warm-white">
			<div class="site-container">
				<article <?php post_class( 'entry-content mx-auto max-w-3xl reveal' ); ?> id="post-<?php the_ID(); ?>">
					<?php
					the_content();

					wp_link_pages(
						array(
							'before' => '<div class="page-links mt-8">' . esc_html__( 'Pages:', 'seahivez-theme' ),
							'after'  => '</div>',
						)
					);
					?>
				</article>
			</div>
		</section>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
