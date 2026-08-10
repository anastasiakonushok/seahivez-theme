<?php
/**
 * Front page template.
 *
 * @package seahivez-theme
 */

get_header();
?>

<main id="primary" class="site-main flex-1">

	<?php
	get_template_part( 'template-parts/home/hero' );
	get_template_part( 'template-parts/home/specs-bar' );
	get_template_part( 'template-parts/home/about-yacht' );
	get_template_part( 'template-parts/home/specifications' );
	get_template_part( 'template-parts/home/experiences' );
	get_template_part( 'template-parts/home/toys-extras' );
	get_template_part( 'template-parts/home/gallery' );
	get_template_part( 'template-parts/home/faq' );
	get_template_part( 'template-parts/home/news' );
	get_template_part( 'template-parts/home/location-cta' );
	?>

</main>

<?php
get_footer();
