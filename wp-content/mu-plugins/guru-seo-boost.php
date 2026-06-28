<?php
/**
 * Plugin Name: Guru SEO Boost
 * Description: Otimizações SEO adicionais para o Guru do Desconto — sitemap, robots, performance.
 * Version: 1.1.0
 * Author: Guru do Desconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Remove unnecessary head tags for cleaner HTML.
 */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );

/**
 * CSS principal: preload + carregamento não bloqueante.
 */
function guru_seo_style_loader( $html, $handle, $href, $media ) {
	if ( 'guru-style' !== $handle ) {
		return $html;
	}

	$preload = sprintf(
		'<link rel="preload" href="%s" as="style">',
		esc_url( $href )
	);

	$async = preg_replace(
		'/media=[\'"]all[\'"]/',
		'media="print" onload="this.media=\'all\'"',
		$html,
		1
	);

	if ( $async === $html ) {
		$async = str_replace( "media='all'", "media='print' onload=\"this.media='all'\"", $html );
	}

	$noscript = preg_replace( '/\smedia="print"\s+onload="this\.media=\'all\'"/', ' media="all"', $async );
	$noscript = str_replace( "media='print' onload=\"this.media='all'\"", "media='all'", $noscript );

	return $preload . $async . '<noscript>' . $noscript . '</noscript>';
}
add_filter( 'style_loader_tag', 'guru_seo_style_loader', 10, 4 );

/**
 * Defer em scripts do tema (fallback para WP < 6.3).
 */
function guru_seo_script_loader( $tag, $handle ) {
	if ( in_array( $handle, array( 'guru-main', 'guru-tracking' ), true ) && false === strpos( $tag, ' defer' ) ) {
		return str_replace( ' src', ' defer src', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'guru_seo_script_loader', 10, 2 );

/**
 * Preload da imagem LCP (hero) na home com alta prioridade.
 */
function guru_seo_preload_hero() {
	if ( ! is_front_page() ) {
		return;
	}

	$hero = guru_hero_image_url();
	if ( function_exists( 'guru_get_whatsapp_group' ) ) {
		$geral = guru_get_whatsapp_group( 'geral' );
		if ( $geral && ! empty( $geral['image'] ) ) {
			$hero = $geral['image'];
		}
	}

	printf(
		'<link rel="preload" as="image" href="%s" fetchpriority="high">' . "\n",
		esc_url( $hero )
	);
}
add_action( 'wp_head', 'guru_seo_preload_hero', 1 );

/**
 * Add lastmod to sitemap entries.
 */
function guru_seo_sitemap_entry( $entry, $post ) {
	$entry['lastmod'] = get_post_modified_time( 'c', false, $post );
	return $entry;
}
add_filter( 'wp_sitemaps_posts_entry', 'guru_seo_sitemap_entry', 10, 2 );
