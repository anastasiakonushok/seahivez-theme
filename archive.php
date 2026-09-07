<?php
/**
 * Category / tag / date archives — same card grid as News.
 *
 * @package seahivez-theme
 */

get_header();
?>

<main id="primary" class="site-main flex-1">
	<?php
	get_template_part(
		'template-parts/page/page-hero',
		null,
		seahivez_get_page_hero_defaults(
			array(
				'eyebrow'     => __( 'Archive', 'seahivez-theme' ),
				'heading'     => wp_strip_all_tags( get_the_archive_title() ),
				'description' => get_the_archive_description() ? wp_strip_all_tags( get_the_archive_description() ) : '',
				'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/1.jpg' ),
				'image_alt'   => '',
				'compact'     => true,
			)
		)
	);
	?>

	<section class="news-archive section-spacing bg-warm-white">
		<div class="site-container">
			<?php if ( have_posts() ) : ?>
				<ul class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3" role="list">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<li class="reveal">
							<?php get_template_part( 'template-parts/news/news-card' ); ?>
						</li>
						<?php
					endwhile;
					?>
				</ul>

				<?php get_template_part( 'template-parts/news/archive-pagination' ); ?>
			<?php else : ?>
				<div class="reveal mx-auto max-w-xl py-8 text-center">
					<p class="type-body text-lg text-navy-900">
						<?php esc_html_e( 'No stories have been published yet.', 'seahivez-theme' ); ?>
					</p>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();
