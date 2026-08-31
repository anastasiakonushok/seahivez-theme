<?php
/**
 * Template Name: FAQ
 *
 * @package seahivez-theme
 */

get_header();
?>

<main id="primary" class="site-main flex-1">

	<?php
	get_template_part( 'template-parts/page/page-hero', null, seahivez_get_faq_page_hero() );
	get_template_part( 'template-parts/faq/faq-page' );
	get_template_part( 'template-parts/home/location-cta' );
	?>

</main>

<?php
get_footer();
