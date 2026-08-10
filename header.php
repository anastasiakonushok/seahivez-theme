<?php
/**
 * The header for our theme
 *
 * @package seahivez-theme
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class( 'min-h-screen flex flex-col' ); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site flex min-h-screen flex-col">
	<a class="screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'seahivez-theme' ); ?></a>

	<?php get_template_part( 'template-parts/header/site-header' ); ?>
