<?php
/**
 * Plugin Name: Guru Bundled Plugins
 * Description: Ativa uma vez os plugins incluídos no repositório (Site Kit, PixelYourSite).
 * Version: 1.0.0
 * Author: Guru do Desconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Ativa plugins empacotados no deploy (primeira visita ao admin).
 */
function guru_activate_bundled_plugins() {
	if ( get_option( 'guru_bundled_plugins_v1' ) ) {
		return;
	}

	if ( ! function_exists( 'activate_plugin' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$bundled = array(
		'google-site-kit/google-site-kit.php',
		'pixelyoursite/facebook-pixel-master.php',
	);

	foreach ( $bundled as $plugin ) {
		$path = WP_PLUGIN_DIR . '/' . $plugin;
		if ( file_exists( $path ) && ! is_plugin_active( $plugin ) ) {
			activate_plugin( $plugin, '', false, false );
		}
	}

	update_option( 'guru_bundled_plugins_v1', 1 );
}
add_action( 'admin_init', 'guru_activate_bundled_plugins', 1 );
