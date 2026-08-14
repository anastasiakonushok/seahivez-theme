<?php
/**
 * Previous / next story navigation (single news only).
 *
 * @package seahivez-theme
 */

$prev_post = get_previous_post();
$next_post = get_next_post();

if ( ! $prev_post && ! $next_post ) {
	return;
}
?>

<nav class="single-news-nav reveal mx-auto mt-14 max-w-[820px] border-t border-gray-200 pt-10" aria-label="<?php esc_attr_e( 'Story navigation', 'seahivez-theme' ); ?>">
	<ul class="grid grid-cols-1 gap-6 md:grid-cols-2 md:gap-8" role="list">
		<?php if ( $prev_post ) : ?>
			<li class="single-news-nav__item single-news-nav__item--prev">
				<a class="single-news-nav__card group" href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>">
					<?php if ( has_post_thumbnail( $prev_post ) ) : ?>
						<span class="single-news-nav__media">
							<?php
							echo get_the_post_thumbnail(
								$prev_post,
								'medium',
								array(
									'class'    => 'single-news-nav__image',
									'loading'  => 'lazy',
									'decoding' => 'async',
									'alt'      => esc_attr( get_the_title( $prev_post ) ),
								)
							);
							?>
						</span>
					<?php else : ?>
						<span class="single-news-nav__media single-news-nav__media--placeholder" aria-hidden="true"></span>
					<?php endif; ?>

					<span class="single-news-nav__content">
						<span class="single-news-nav__label">
							<span class="single-news-nav__arrow" aria-hidden="true">
								<?php seahivez_render_arrow( 'left', array( 'size' => 'sm' ) ); ?>
							</span>
							<?php esc_html_e( 'Previous story', 'seahivez-theme' ); ?>
						</span>
						<span class="single-news-nav__title">
							<?php echo esc_html( get_the_title( $prev_post ) ); ?>
						</span>
					</span>
				</a>
			</li>
		<?php else : ?>
			<li class="hidden md:block" aria-hidden="true"></li>
		<?php endif; ?>

		<?php if ( $next_post ) : ?>
			<li class="single-news-nav__item single-news-nav__item--next<?php echo $prev_post ? '' : ' md:col-start-2'; ?>">
				<a class="single-news-nav__card group single-news-nav__card--next" href="<?php echo esc_url( get_permalink( $next_post ) ); ?>">
					<?php if ( has_post_thumbnail( $next_post ) ) : ?>
						<span class="single-news-nav__media">
							<?php
							echo get_the_post_thumbnail(
								$next_post,
								'medium',
								array(
									'class'    => 'single-news-nav__image',
									'loading'  => 'lazy',
									'decoding' => 'async',
									'alt'      => esc_attr( get_the_title( $next_post ) ),
								)
							);
							?>
						</span>
					<?php else : ?>
						<span class="single-news-nav__media single-news-nav__media--placeholder" aria-hidden="true"></span>
					<?php endif; ?>

					<span class="single-news-nav__content">
						<span class="single-news-nav__label">
							<?php esc_html_e( 'Next story', 'seahivez-theme' ); ?>
							<span class="single-news-nav__arrow" aria-hidden="true">
								<?php seahivez_render_arrow( 'right', array( 'size' => 'sm' ) ); ?>
							</span>
						</span>
						<span class="single-news-nav__title">
							<?php echo esc_html( get_the_title( $next_post ) ); ?>
						</span>
					</span>
				</a>
			</li>
		<?php endif; ?>
	</ul>
</nav>
