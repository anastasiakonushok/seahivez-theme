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
	if ( seahivez_has_flexible_sections() ) {
		seahivez_render_flexible_sections();
	} else {
		seahivez_render_default_home_sections();
	}
	?>

</main>

<?php
get_footer();
