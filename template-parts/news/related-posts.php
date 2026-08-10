<?php
/**
 * Related News posts (excludes current).
 *
 * @package seahivez-theme
 */

$current_id = get_the_ID();

$related = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'post_status'         => 'publish',
		'post__not_in'        => array( $current_id ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

if ( ! $related->have_posts() ) {
	return;
}
?>

<section class="related-news section-spacing bg-sand-50" aria-labelledby="related-news-heading">
	<div class="site-container">
		<div class="reveal max-w-2xl">
			<p class="section-eyebrow"><?php esc_html_e( 'More from SeaHivez', 'seahivez-theme' ); ?></p>
			<h2 id="related-news-heading" class="section-heading mt-3">
				<?php esc_html_e( 'Continue exploring', 'seahivez-theme' ); ?>
			</h2>
		</div>

		<ul class="mt-10 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3" role="list">
			<?php
			$index = 0;
			while ( $related->have_posts() ) :
				$related->the_post();
				?>
				<li class="reveal<?php echo $index ? ' reveal-delay-' . min( $index, 2 ) : ''; ?>">
					<?php get_template_part( 'template-parts/news/news-card' ); ?>
				</li>
				<?php
				++$index;
			endwhile;
			wp_reset_postdata();
			?>
		</ul>
	</div>
</section>
