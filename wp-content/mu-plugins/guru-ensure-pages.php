<?php
/**
 * Plugin Name: Guru Ensure Pages
 * Description: Garante que páginas essenciais existam — /grupo-promocoes-whatsapp/
 * Version: 1.0.0
 * Author: Guru do Desconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Cria ou recupera a página SEO do grupo WhatsApp.
 */
function guru_mu_ensure_whatsapp_page() {
	$page_id = (int) get_option( 'guru_whatsapp_page_id', 0 );
	if ( $page_id && 'publish' === get_post_status( $page_id ) ) {
		return $page_id;
	}

	$slug     = 'grupo-promocoes-whatsapp';
	$existing = get_page_by_path( $slug, OBJECT, 'page' );

	if ( $existing ) {
		if ( 'publish' !== $existing->post_status ) {
			wp_update_post( array(
				'ID'          => $existing->ID,
				'post_status' => 'publish',
			) );
		}
		update_option( 'guru_whatsapp_page_id', $existing->ID );
		update_post_meta( $existing->ID, '_wp_page_template', 'page-grupo-whatsapp.php' );
		flush_rewrite_rules( false );
		return $existing->ID;
	}

	$whatsapp = function_exists( 'guru_whatsapp_link' )
		? guru_whatsapp_link()
		: 'https://chat.whatsapp.com/I5Ln1bvpIP89FpaxRO4VJG?mode=gi_t';

	$content  = '<p>O <strong>Guru do Desconto</strong> reúne as melhores <strong>promoções no WhatsApp</strong> do Brasil.</p>';
	$content .= '<p><a href="' . esc_url( $whatsapp ) . '" class="btn btn-whatsapp" target="_blank" rel="noopener">Entrar no Grupo de Promoções no WhatsApp</a></p>';

	$new_id = wp_insert_post( array(
		'post_title'   => 'Grupo de Promoções no WhatsApp',
		'post_name'    => $slug,
		'post_content' => $content,
		'post_status'  => 'publish',
		'post_type'    => 'page',
	) );

	if ( $new_id && ! is_wp_error( $new_id ) ) {
		update_option( 'guru_whatsapp_page_id', $new_id );
		update_post_meta( $new_id, '_wp_page_template', 'page-grupo-whatsapp.php' );
		update_post_meta( $new_id, '_guru_meta_description', 'Grupo de promoções no WhatsApp do Guru do Desconto! Ofertas do Mercado Livre, Shopee e Amazon.' );
		flush_rewrite_rules( false );
		return $new_id;
	}

	return 0;
}
add_action( 'init', 'guru_mu_ensure_whatsapp_page', 5 );
