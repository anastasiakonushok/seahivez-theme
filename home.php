<?php
/**
 * Blog posts index — public News archive (/news/ when Posts page is set).
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
		seahivez_get_news_page_hero()
	);
	?>

	<section class="news-archive section-spacing bg-warm-white" aria-label="<?php esc_attr_e( 'All news', 'seahivez-theme' ); ?>">
		<div class="site-container">
			<?php if ( have_posts() ) : ?>
				<ul class="news-archive__grid grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3" role="list">
					<?php
					$index = 0;
					while ( have_posts() ) :
						the_post();
						?>
						<li class="reveal<?php echo $index % 3 ? ' reveal-delay-' . min( $index % 3, 2 ) : ''; ?>">
							<?php get_template_part( 'template-parts/news/news-card' ); ?>
						</li>
						<?php
						++$index;
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
