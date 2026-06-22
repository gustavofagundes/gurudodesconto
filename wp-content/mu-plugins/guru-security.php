<?php
/**
 * Plugin Name: Guru Security
 * Description: Endurecimento de segurança — login, headers, XML-RPC, enumeração de usuários.
 * Version: 1.0.0
 * Author: Guru do Desconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Headers de segurança HTTP.
 */
function guru_security_headers() {
	if ( headers_sent() || is_admin() ) {
		return;
	}

	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'Permissions-Policy: geolocation=(), microphone=(), camera=()' );

	if ( is_ssl() ) {
		header( 'Strict-Transport-Security: max-age=31536000; includeSubDomains' );
	}
}
add_action( 'send_headers', 'guru_security_headers' );

/**
 * Desativa XML-RPC (vetor comum de brute force e DDoS).
 */
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'wp_headers', function ( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
} );

/**
 * Bloqueia enumeração de usuários via ?author=1
 */
function guru_block_author_enum() {
	if ( is_admin() || ! isset( $_GET['author'] ) ) {
		return;
	}
	wp_safe_redirect( home_url( '/' ), 301 );
	exit;
}
add_action( 'template_redirect', 'guru_block_author_enum' );

/**
 * Remove versão do WordPress do HTML (facilita ataques direcionados).
 */
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

/**
 * Limita tentativas de login por IP.
 */
function guru_login_rate_limit( $username ) {
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	$key = 'guru_login_' . md5( $ip );
	$max = (int) guru_env( 'LOGIN_MAX_ATTEMPTS', 5 );
	$ttl = (int) guru_env( 'LOGIN_LOCKOUT_MINUTES', 15 ) * MINUTE_IN_SECONDS;

	$attempts = (int) get_transient( $key );
	if ( $attempts >= $max ) {
		wp_die(
			esc_html__( 'Muitas tentativas de login. Aguarde alguns minutos e tente novamente.', 'guru-do-desconto' ),
			esc_html__( 'Acesso temporariamente bloqueado', 'guru-do-desconto' ),
			array( 'response' => 403 )
		);
	}

	set_transient( $key, $attempts + 1, $ttl );
}
add_action( 'wp_login_failed', 'guru_login_rate_limit' );

function guru_login_rate_limit_reset( $user_login, $user ) {
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	$key = 'guru_login_' . md5( $ip );
	delete_transient( $key );
}
add_action( 'wp_login', 'guru_login_rate_limit_reset', 10, 2 );

/**
 * Helper para ler env (reutiliza se wp-config já carregou).
 */
if ( ! function_exists( 'guru_env' ) ) {
	function guru_env( $key, $default = '' ) {
		$val = getenv( $key );
		return ( $val !== false && $val !== '' ) ? $val : $default;
	}
}

/**
 * Desabilita listagem de diretórios em uploads via index.php vazio.
 */
function guru_secure_uploads_dir() {
	$upload_dir = wp_upload_dir();
	if ( empty( $upload_dir['basedir'] ) ) {
		return;
	}

	$htaccess = $upload_dir['basedir'] . '/.htaccess';
	if ( ! file_exists( $htaccess ) ) {
		$rules = "<Files *.php>\nRequire all denied\n</Files>\n";
		@file_put_contents( $htaccess, $rules ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
	}

	$index = $upload_dir['basedir'] . '/index.php';
	if ( ! file_exists( $index ) ) {
		@file_put_contents( $index, "<?php\n// Silence is golden.\n" ); // phpcs:ignore
	}
}
add_action( 'admin_init', 'guru_secure_uploads_dir' );

/**
 * Força cookies seguros no admin quando HTTPS está ativo.
 */
function guru_secure_auth_cookie() {
	if ( is_ssl() ) {
		@ini_set( 'session.cookie_httponly', 1 ); // phpcs:ignore
		@ini_set( 'session.cookie_secure', 1 );  // phpcs:ignore
	}
}
add_action( 'init', 'guru_secure_auth_cookie' );
