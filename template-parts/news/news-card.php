<?php
/**
 * Shared News card — homepage, archive, related posts.
 *
 * Must be rendered inside a WordPress post loop.
 *
 * @package seahivez-theme
 */

$categories = get_the_category();
$category   = ! empty( $categories ) ? $categories[0] : null;
?>

<article <?php post_class( 'news-card group h-full' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a
			class="news-card__media"
			href="<?php the_permalink(); ?>"
			tabindex="-1"
			aria-hidden="true"
		>
			<?php
			the_post_thumbnail(
				'large',
				array(
					'class'    => 'news-card__image',
					'loading'  => 'lazy',
					'decoding' => 'async',
				)
			);
			?>
		</a>
	<?php else : ?>
		<a
			class="news-card__media flex items-center justify-center"
			href="<?php the_permalink(); ?>"
			tabindex="-1"
			aria-hidden="true"
		>
			<span class="text-xs font-semibold uppercase tracking-widest text-gray-400">
				<?php esc_html_e( 'SeaHivez', 'seahivez-theme' ); ?>
			</span>
		</a>
	<?php endif; ?>

	<div class="news-card__body flex flex-1 flex-col p-6">
		<div class="flex flex-wrap items-center gap-x-2 gap-y-1">
			<time class="type-eyebrow text-gray-500" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
				<?php echo esc_html( strtoupper( get_the_date( 'j M Y' ) ) ); ?>
			</time>
			<?php if ( $category ) : ?>
				<span class="type-eyebrow text-gray-300" aria-hidden="true">·</span>
				<span class="type-eyebrow text-gray-500"><?php echo esc_html( $category->name ); ?></span>
			<?php endif; ?>
		</div>

		<h3 class="news-card__title card-title mt-3">
			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</h3>

		<?php
		$excerpt = get_the_excerpt();
		if ( $excerpt ) :
			?>
			<p class="news-card__excerpt type-body mt-3 flex-1">
				<?php echo esc_html( $excerpt ); ?>
			</p>
		<?php endif; ?>

		<a class="link-arrow mt-6 inline-flex" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'Read story', 'seahivez-theme' ); ?>
			<?php seahivez_render_link_arrow_icon( 'sm' ); ?>
		</a>
	</div>
</article>
