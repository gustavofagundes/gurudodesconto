<?php
/**
 * Plugin Name: Guru Canonical URL
 * Description: Consolida HTTP/www em https://gurudodesconto.com.br (Google Search — evita duplicatas).
 * Version: 1.0.0
 * Author: Guru do Desconto
 *
 * @see https://developers.google.com/search/docs/crawling-indexing/consolidate-duplicate-urls
 */

defined( 'ABSPATH' ) || exit;

/**
 * Host canônico (sem www, sem protocolo).
 */
function guru_canonical_host() {
	$host = function_exists( 'guru_env' )
		? guru_env( 'GURU_CANONICAL_HOST', 'gurudodesconto.com.br' )
		: 'gurudodesconto.com.br';

	$host = strtolower( trim( $host ) );
	return preg_replace( '/^www\./', '', $host );
}

/**
 * Origem canônica https://dominio
 */
function guru_canonical_origin() {
	return 'https://' . guru_canonical_host();
}

/**
 * Ambiente de produção (não aplica em localhost).
 */
function guru_is_production_domain() {
	$host = strtolower( $_SERVER['HTTP_HOST'] ?? '' );
	return str_contains( $host, guru_canonical_host() );
}

/**
 * Detecta HTTPS atrás de proxy (Hostinger, Cloudflare).
 */
function guru_request_is_https() {
	if ( is_ssl() ) {
		return true;
	}

	if ( ! empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] )
		&& 'https' === strtolower( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ) {
		return true;
	}

	if ( ! empty( $_SERVER['HTTP_X_FORWARDED_SSL'] )
		&& 'on' === strtolower( $_SERVER['HTTP_X_FORWARDED_SSL'] ) ) {
		return true;
	}

	if ( ! empty( $_SERVER['HTTP_CF_VISITOR'] )
		&& str_contains( $_SERVER['HTTP_CF_VISITOR'], '"scheme":"https"' ) ) {
		return true;
	}

	return false;
}

/**
 * Normaliza URL para o domínio canônico.
 */
function guru_normalize_canonical_url( $url ) {
	if ( empty( $url ) || ! is_string( $url ) ) {
		return $url;
	}

	$parts = wp_parse_url( $url );
	if ( empty( $parts['host'] ) ) {
		return $url;
	}

	$host = strtolower( $parts['host'] );
	$host = preg_replace( '/^www\./', '', $host );

	if ( $host !== guru_canonical_host() ) {
		return $url;
	}

	$path  = $parts['path'] ?? '';
	$query = isset( $parts['query'] ) ? '?' . $parts['query'] : '';
	$frag  = isset( $parts['fragment'] ) ? '#' . $parts['fragment'] : '';

	if ( '' === $path ) {
		$path = '/';
	}

	return guru_canonical_origin() . $path . $query . $frag;
}

/**
 * 301 — HTTP e www → https://gurudodesconto.com.br (fallback se .htaccess não aplicar).
 */
function guru_canonical_redirect() {
	if ( ! guru_is_production_domain() ) {
		return;
	}

	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		return;
	}

	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		return;
	}

	$host          = strtolower( $_SERVER['HTTP_HOST'] ?? '' );
	$canonical     = guru_canonical_host();
	$needs_redirect = ! guru_request_is_https()
		|| str_starts_with( $host, 'www.' )
		|| $host !== $canonical;

	if ( ! $needs_redirect ) {
		return;
	}

	$uri    = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
	$target = guru_canonical_origin() . $uri;

	wp_safe_redirect( $target, 301 );
	exit;
}
add_action( 'template_redirect', 'guru_canonical_redirect', 0 );

/**
 * Alinha home/siteurl do WordPress ao domínio canônico (uma vez).
 */
function guru_sync_wp_canonical_options() {
	if ( ! guru_is_production_domain() || get_option( 'guru_canonical_urls_synced_v1' ) ) {
		return;
	}

	$canonical = guru_canonical_origin();
	$home      = (string) get_option( 'home', '' );

	if ( $home && str_contains( $home, guru_canonical_host() ) ) {
		update_option( 'home', $canonical );
		update_option( 'siteurl', $canonical );
	}

	update_option( 'guru_canonical_urls_synced_v1', 1 );
}
add_action( 'init', 'guru_sync_wp_canonical_options', 1 );

/**
 * URLs geradas pelo WordPress sempre no domínio canônico.
 */
function guru_filter_canonical_urls( $url ) {
	if ( ! guru_is_production_domain() ) {
		return $url;
	}
	return guru_normalize_canonical_url( $url );
}
add_filter( 'home_url', 'guru_filter_canonical_urls', 99 );
add_filter( 'site_url', 'guru_filter_canonical_urls', 99 );
add_filter( 'get_canonical_url', 'guru_filter_canonical_urls', 99 );

/**
 * Evita redirect_canonical do WP conflitar (já consolidamos no domínio certo).
 */
function guru_redirect_canonical_fix( $redirect_url, $requested_url ) {
	if ( ! guru_is_production_domain() || ! $redirect_url ) {
		return $redirect_url;
	}

	return guru_normalize_canonical_url( $redirect_url );
}
add_filter( 'redirect_canonical', 'guru_redirect_canonical_fix', 10, 2 );

/**
 * Força HTTPS no admin quando em produção.
 */
function guru_force_ssl_admin( $force ) {
	if ( guru_is_production_domain() ) {
		return true;
	}
	return $force;
}
add_filter( 'force_ssl_admin', 'guru_force_ssl_admin' );

if ( guru_is_production_domain() && ! defined( 'FORCE_SSL_ADMIN' ) ) {
	define( 'FORCE_SSL_ADMIN', true );
}
