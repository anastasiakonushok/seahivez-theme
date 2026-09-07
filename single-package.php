<?php
/**
 * Single package template.
 *
 * @package seahivez-theme
 */

get_header();
?>

<main id="primary" class="site-main flex-1">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/page/page-hero', null, seahivez_get_package_hero_data() );
		get_template_part( 'template-parts/packages/package-content' );
		get_template_part( 'template-parts/packages/package-more' );
		get_template_part( 'template-parts/page/booking-cta' );
	endwhile;
	?>
</main>

<?php
get_footer();
