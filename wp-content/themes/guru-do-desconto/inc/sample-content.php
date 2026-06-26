<?php
/**
 * Sample content on theme activation
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Pretty permalinks são obrigatórios para REST API (/wp-json/) e Google Site Kit.
 */
function guru_setup_permalinks() {
	if ( get_option( 'permalink_structure' ) ) {
		return;
	}
	update_option( 'permalink_structure', '/%postname%/' );
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'guru_setup_permalinks' );

/**
 * Create default pages and menus.
 */
function guru_create_sample_content() {
	if ( get_option( 'guru_sample_content_created' ) ) {
		return;
	}

	// Homepage.
	$home_id = wp_insert_post( array(
		'post_title'   => 'Início',
		'post_status'  => 'publish',
		'post_type'    => 'page',
		'post_content' => '',
	) );

	// Reviews archive page content.
	wp_insert_post( array(
		'post_title'   => 'Sobre',
		'post_status'  => 'publish',
		'post_type'    => 'page',
		'post_content' => '<p>O <strong>Guru do Desconto</strong> é seu guia para economizar com inteligência. Analisamos produtos, comparamos preços e encontramos as melhores promoções no Mercado Livre, Shopee e Amazon.</p><p>Entre no nosso grupo do WhatsApp para receber ofertas em tempo real!</p>',
	) );

	if ( $home_id && ! is_wp_error( $home_id ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_id );
		update_post_meta( $home_id, '_guru_meta_description', guru_default_seo_description() );
	}

	update_option( 'guru_sample_content_created', true );

	if ( function_exists( 'guru_remove_placeholder_content' ) ) {
		guru_remove_placeholder_content();
	}
}
add_action( 'after_switch_theme', 'guru_create_sample_content' );
