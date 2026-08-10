<?php
/**
 * Homepage news section.
 *
 * @package seahivez-theme
 */

$news_query = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

if ( ! $news_query->have_posts() ) {
	return;
}

$header = seahivez_get_home_news_header();
?>

<section class="news section-spacing bg-sand-50" aria-labelledby="news-heading">
	<div class="site-container">
		<div class="news__header flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
			<div class="reveal max-w-2xl">
				<p class="section-eyebrow"><?php echo esc_html( $header['eyebrow'] ); ?></p>
				<h2 id="news-heading" class="section-heading mt-3">
					<?php echo esc_html( $header['heading'] ); ?>
				</h2>
				<p class="type-body mt-4">
					<?php echo esc_html( $header['description'] ); ?>
				</p>
			</div>

			<div class="reveal hidden shrink-0 lg:block">
				<a class="btn-outline section-outline-cta link-arrow group" href="<?php echo esc_url( $header['cta_url'] ); ?>">
					<?php echo esc_html( $header['cta_label'] ); ?>
					<?php seahivez_render_link_arrow_icon( 'md' ); ?>
				</a>
			</div>
		</div>

		<ul class="news__grid mt-10 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
			<?php
			$index = 0;
			while ( $news_query->have_posts() ) :
				$news_query->the_post();
				?>
				<li class="news__item reveal<?php echo $index ? ' reveal-delay-' . min( $index, 2 ) : ''; ?>">
					<?php get_template_part( 'template-parts/news/news-card' ); ?>
				</li>
				<?php
				++$index;
			endwhile;
			wp_reset_postdata();
			?>
		</ul>

		<div class="mt-8 text-center reveal lg:hidden">
			<a class="btn-outline section-outline-cta link-arrow group" href="<?php echo esc_url( $header['cta_url'] ); ?>">
				<?php echo esc_html( $header['cta_label'] ); ?>
				<?php seahivez_render_link_arrow_icon( 'md' ); ?>
			</a>
		</div>
	</div>
</section>
