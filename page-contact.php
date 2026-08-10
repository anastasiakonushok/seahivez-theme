<?php
/**
 * Template Name: Contact
 *
 * @package seahivez-theme
 */

get_header();

get_template_part( 'template-parts/page/page-hero', null, seahivez_get_contact_page_hero() );
get_template_part( 'template-parts/contact/contact-layout' );

get_footer();
