<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package seahivez-theme
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function seahivez_theme_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'seahivez_theme_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function seahivez_theme_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'seahivez_theme_pingback_header' );

/**
 * News archive: posts per page and pagination query fix.
 *
 * @param WP_Query $query Main query.
 * @return void
 */
function seahivez_news_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! $query->is_home() && ! $query->is_category() && ! $query->is_tag() && ! $query->is_author() && ! $query->is_date() ) {
		return;
	}

	$query->set( 'posts_per_page', seahivez_get_news_posts_per_page() );

	// Posts page (/news/page/2/) may use `page` instead of `paged`.
	$paged = (int) get_query_var( 'paged' );
	$page  = (int) get_query_var( 'page' );

	if ( $paged < 1 && $page > 1 ) {
		$query->set( 'paged', $page );
	}
}
add_action( 'pre_get_posts', 'seahivez_news_archive_query' );

/**
 * Render numbered pagination for news archives.
 *
 * @param WP_Query|null $query Optional query; defaults to main query.
 * @return void
 */
function seahivez_render_news_pagination( $query = null ) {
	global $wp_query;

	if ( null === $query ) {
		$query = $wp_query;
	}

	$total_pages = (int) $query->max_num_pages;

	if ( $total_pages < 2 ) {
		return;
	}

	$current = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );

	$links = paginate_links(
		array(
			'total'     => $total_pages,
			'current'   => $current,
			'type'      => 'list',
			'mid_size'  => 2,
			'prev_text' => seahivez_get_arrow_svg( 'left', array( 'size' => 'sm' ) ) . '<span class="screen-reader-text">' . esc_html__( 'Previous', 'seahivez-theme' ) . '</span>',
			'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next', 'seahivez-theme' ) . '</span>' . seahivez_get_arrow_svg( 'right', array( 'size' => 'sm' ) ),
		)
	);

	if ( ! $links ) {
		return;
	}

	printf(
		'<nav class="news-pagination mt-14" aria-label="%1$s"><div class="nav-links">%2$s</div></nav>',
		esc_attr__( 'News pagination', 'seahivez-theme' ),
		$links // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built by paginate_links().
	);
}
