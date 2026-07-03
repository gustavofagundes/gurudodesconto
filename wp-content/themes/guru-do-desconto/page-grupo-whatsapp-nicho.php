<?php
/**
 * Template: landing por nicho — /grupo-whatsapp/{slug}/
 *
 * @package GuruDoDesconto
 */

get_header();

$group = guru_get_whatsapp_group_from_page();
if ( ! $group ) {
	get_template_part( 'template-parts/content', 'page' );
	get_footer();
	return;
}

get_template_part( 'template-parts/whatsapp', 'group-landing', array( 'group' => $group ) );

get_footer();
