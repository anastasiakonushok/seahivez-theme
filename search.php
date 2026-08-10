<?php
/**
 * Search results.
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
				'eyebrow'     => __( 'Search', 'seahivez-theme' ),
				'heading'     => sprintf(
					/* translators: %s: search query */
					__( 'Search results for “%s”', 'seahivez-theme' ),
					get_search_query()
				),
				'description' => '',
				'image'       => seahivez_get_theme_image_uri( 'assets/images/photo/2.jpg' ),
				'image_alt'   => '',
				'compact'     => true,
			)
		)
	);
	?>

	<section class="search-results section-spacing bg-warm-white">
		<div class="site-container">
			<?php if ( have_posts() ) : ?>
				<ul class="mx-auto max-w-3xl divide-y divide-gray-200 border-y border-gray-200" role="list">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<li class="reveal py-8">
							<article>
								<p class="type-eyebrow text-gray-500"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></p>
								<h2 class="mt-2 text-xl font-semibold text-navy-900">
									<a class="transition-colors hover:text-gold-dark" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h2>
								<?php if ( has_excerpt() || get_the_excerpt() ) : ?>
									<p class="type-body mt-3"><?php echo esc_html( get_the_excerpt() ); ?></p>
								<?php endif; ?>
								<a class="link-arrow mt-4 inline-flex" href="<?php the_permalink(); ?>">
									<?php esc_html_e( 'Read more', 'seahivez-theme' ); ?>
									<?php seahivez_render_link_arrow_icon( 'sm' ); ?>
								</a>
							</article>
						</li>
						<?php
					endwhile;
					?>
				</ul>
				<div class="mt-10"><?php the_posts_pagination(); ?></div>
			<?php else : ?>
				<div class="reveal mx-auto max-w-xl text-center">
					<p class="type-body"><?php esc_html_e( 'Nothing matched your search. Try a different keyword or return home.', 'seahivez-theme' ); ?></p>
					<a class="btn-primary mt-8 inline-flex" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php esc_html_e( 'Back home', 'seahivez-theme' ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();
