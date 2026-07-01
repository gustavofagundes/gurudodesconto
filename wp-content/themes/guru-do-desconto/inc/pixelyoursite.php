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
