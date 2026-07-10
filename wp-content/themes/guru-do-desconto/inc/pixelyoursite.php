<?php
/**
 * Integração com PixelYourSite — evita Pixel duplicado; mantém eventos do tema.
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * PixelYourSite está instalado e ativo?
 */
function guru_pixelyoursite_active() {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	return is_plugin_active( 'pixelyoursite/facebook-pixel-master.php' );
}

/**
 * O tema deve injetar o código base do Meta Pixel?
 * Quando o PYS está ativo, ele gerencia PageView, CAPI, etc.
 */
function guru_theme_handles_meta_pixel_base() {
	return ! guru_pixelyoursite_active();
}

/**
 * Eventos customizados do tema (Lead, Contact, WhatsAppClick) via tracking.js.
 */
function guru_meta_pixel_events_enabled() {
	if ( is_admin() || wp_doing_ajax() || wp_is_json_request() ) {
		return false;
	}

	if ( guru_meta_pixel_skip_for_user() ) {
		return false;
	}

	if ( guru_pixelyoursite_active() ) {
		return true;
	}

	return guru_meta_pixel_enabled() && (bool) guru_meta_pixel_id();
}

/**
 * Garante o Meta Pixel ID no PixelYourSite (browser + Conversion API).
 *
 * Fonte: Customizer / GURU_META_PIXEL_ID no .env.
 * Só preenche se o campo do plugin estiver vazio (não sobrescreve token/CAPI do admin).
 */
function guru_pixelyoursite_sync_pixel_id() {
	if ( ! guru_pixelyoursite_active() ) {
		return;
	}

	if ( ! function_exists( 'PixelYourSite\Facebook' ) ) {
		return;
	}

	$pixel_id = function_exists( 'guru_meta_pixel_id' ) ? guru_meta_pixel_id() : '';
	if ( ! $pixel_id ) {
		return;
	}

	$facebook = \PixelYourSite\Facebook();
	$current  = (array) $facebook->getOption( 'pixel_id' );
	$current  = preg_replace( '/\D/', '', (string) ( $current[0] ?? '' ) );

	if ( $current === $pixel_id ) {
		return;
	}

	// Não sobrescreve um ID já configurado no painel do plugin.
	if ( $current !== '' ) {
		return;
	}

	$facebook->updateOptions(
		array(
			'enabled'  => true,
			'pixel_id' => array( $pixel_id ),
		)
	);
}
add_action( 'init', 'guru_pixelyoursite_sync_pixel_id', 20 );

/**
 * Fallback: injeta o ID via filtro se a opção do PYS ainda estiver vazia.
 *
 * @param array $ids Pixel IDs do Facebook.
 * @return array
 */
function guru_pixelyoursite_filter_facebook_ids( $ids ) {
	$ids = array_filter( (array) $ids );

	if ( ! empty( $ids ) ) {
		return $ids;
	}

	$pixel_id = function_exists( 'guru_meta_pixel_id' ) ? guru_meta_pixel_id() : '';

	return $pixel_id ? array( $pixel_id ) : $ids;
}
add_filter( 'pys_facebook_ids', 'guru_pixelyoursite_filter_facebook_ids' );
