<?php
/**
 * Garante páginas essenciais mesmo antes do tema carregar hooks pesados.
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {
	if ( ! function_exists( 'guru_ensure_whatsapp_seo_page' ) ) {
		return;
	}

	$slug = 'grupo-promocoes-whatsapp';
	if ( ! get_page_by_path( $slug, OBJECT, 'page' ) ) {
		guru_ensure_whatsapp_seo_page();
	}
}, 25 );
