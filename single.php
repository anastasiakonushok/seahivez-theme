<?php
/**
 * Single News article (standard WordPress post).
 *
 * @package seahivez-theme
 */

get_header();

$posts_url = seahivez_get_posts_page_url();
?>

<main id="primary" class="site-main flex-1">
	<?php
	while ( have_posts() ) :
		the_post();

		$categories = get_the_category();
		$cat_label  = ! empty( $categories ) ? $categories[0]->name : '';
		?>

		<article <?php post_class( 'single-news' ); ?> id="post-<?php the_ID(); ?>">
			<header class="single-news__hero section-spacing bg-sand-50">
				<div class="site-container">
					<div class="reveal mx-auto max-w-[52rem] text-center">
						<p class="section-eyebrow">
							<a class="transition-colors hover:text-gold-dark" href="<?php echo esc_url( $posts_url ); ?>">
								<?php esc_html_e( 'News', 'seahivez-theme' ); ?>
							</a>
							<?php if ( $cat_label ) : ?>
								<span class="mx-2 text-gray-300" aria-hidden="true">/</span>
								<span><?php echo esc_html( $cat_label ); ?></span>
							<?php endif; ?>
						</p>

						<time
							class="mt-3 block type-eyebrow text-gray-500"
							datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"
						>
							<?php echo esc_html( strtoupper( get_the_date( 'j M Y' ) ) ); ?>
						</time>

						<h1 class="section-heading mt-4"><?php the_title(); ?></h1>

						<?php if ( has_excerpt() ) : ?>
							<p class="type-body-lg mx-auto mt-5 max-w-2xl text-gray-600">
								<?php echo esc_html( get_the_excerpt() ); ?>
							</p>
						<?php endif; ?>
					</div>

					<?php if ( has_post_thumbnail() ) : ?>
						<figure class="reveal single-news__featured mt-10 overflow-hidden rounded-md md:mt-12">
							<?php
							the_post_thumbnail(
								'large',
								array(
									'class'    => 'mx-auto max-h-[640px] w-full object-cover',
									'loading'  => 'eager',
									'decoding' => 'async',
								)
							);
							?>
						</figure>
					<?php endif; ?>
				</div>
			</header>

			<div class="single-news__body section-spacing bg-warm-white">
				<div class="site-container">
					<div class="reveal mx-auto mb-10 max-w-[820px]">
						<a class="link-arrow link-arrow--back inline-flex items-center gap-2" href="<?php echo esc_url( $posts_url ); ?>">
							<span class="link-arrow__icon" aria-hidden="true">
								<?php seahivez_render_arrow( 'left', array( 'size' => 'sm' ) ); ?>
							</span>
							<?php esc_html_e( 'All News', 'seahivez-theme' ); ?>
						</a>
					</div>

					<div class="entry-content entry-content--article reveal mx-auto max-w-[820px]">
						<?php the_content(); ?>
					</div>

					<?php get_template_part( 'template-parts/news/post-navigation' ); ?>
				</div>
			</div>
		</article>

		<?php get_template_part( 'template-parts/news/related-posts' ); ?>

		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
