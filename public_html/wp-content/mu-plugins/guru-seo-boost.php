<?php
/**
 * Plugin Name: Guru SEO Boost
 * Description: Otimizações SEO adicionais para o Guru do Desconto — sitemap, robots, performance.
 * Version: 1.0.0
 * Author: Guru do Desconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add robots.txt rules for better crawling.
 */
function guru_seo_robots_txt( $output, $public ) {
	if ( ! $public ) {
		return $output;
	}

	$extra  = "User-agent: *\n";
	$extra .= "Allow: /\n";
	$extra .= "Disallow: /wp-admin/\n";
	$extra .= "Allow: /wp-admin/admin-ajax.php\n";
	$extra .= "Sitemap: " . home_url( '/wp-sitemap.xml' ) . "\n";

	return $extra;
}
add_filter( 'robots_txt', 'guru_seo_robots_txt', 10, 2 );

/**
 * Remove unnecessary head tags for cleaner HTML.
 */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );

/**
 * Add async/defer to scripts where possible.
 */
function guru_seo_script_loader( $tag, $handle ) {
	if ( 'guru-main' === $handle ) {
		return str_replace( ' src', ' defer src', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'guru_seo_script_loader', 10, 2 );

/**
 * Preload hero image on front page.
 */
function guru_seo_preload_hero() {
	if ( ! is_front_page() ) {
		return;
	}
	$hero = get_template_directory_uri() . '/assets/images/Guru_sem_fundo.png';
	echo '<link rel="preload" as="image" href="' . esc_url( $hero ) . '">' . "\n";
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
