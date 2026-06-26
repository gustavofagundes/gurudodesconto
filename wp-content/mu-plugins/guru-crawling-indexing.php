<?php
/**
 * Plugin Name: Guru Crawling & Indexing
 * Description: SEO técnico — remove conteúdo padrão do WP, robots.txt, noindex em páginas de baixo valor.
 * Version: 1.0.0
 * Author: Guru do Desconto
 *
 * @see https://developers.google.com/search/docs/crawling-indexing
 */

defined( 'ABSPATH' ) || exit;

/**
 * Post/página padrão do WordPress (Hello World, Sample Page, etc.).
 */
function guru_is_placeholder_content( $post ) {
	if ( ! $post instanceof WP_Post ) {
		return false;
	}

	if ( (int) get_option( 'page_on_front' ) === (int) $post->ID ) {
		return false;
	}

	if ( (int) get_option( 'page_for_posts' ) === (int) $post->ID ) {
		return false;
	}

	if ( 'post' === $post->post_type ) {
		if ( preg_match( '/^hello\s+world!?\s*$/iu', trim( $post->post_title ) ) ) {
			return true;
		}

		$content = strtolower( wp_strip_all_tags( $post->post_content ) );
		if ( str_contains( $content, 'welcome to wordpress' )
			|| str_contains( $content, 'bem-vindo ao wordpress' )
			|| str_contains( $content, 'boas-vindas ao wordpress' ) ) {
			return true;
		}
	}

	if ( 'page' === $post->post_type ) {
		if ( 'sample-page' === $post->post_name ) {
			return true;
		}

		$title = strtolower( trim( $post->post_title ) );
		if ( in_array( $title, array( 'sample page', 'página de exemplo', 'pagina de exemplo' ), true ) ) {
			return true;
		}

		$content = strtolower( wp_strip_all_tags( $post->post_content ) );
		if ( str_contains( $content, 'this is an example page' )
			|| str_contains( $content, 'esta é uma página de exemplo' ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Remove Hello World, Sample Page e posts vazios de demonstração.
 */
function guru_remove_placeholder_content() {
	$removed = 0;

	$posts = get_posts(
		array(
			'post_type'      => array( 'post', 'page' ),
			'post_status'    => array( 'publish', 'draft', 'pending', 'private', 'future' ),
			'posts_per_page' => -1,
		)
	);

	foreach ( $posts as $post ) {
		if ( ! guru_is_placeholder_content( $post ) ) {
			continue;
		}

		if ( wp_delete_post( $post->ID, true ) ) {
			++$removed;
		}
	}

	// Evita listagem de blog padrão se não há posts reais.
	$real_posts = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	if ( ! $real_posts && get_option( 'page_for_posts' ) ) {
		update_option( 'page_for_posts', 0 );
	}

	if ( $removed > 0 ) {
		update_option( 'guru_placeholder_content_removed', time(), false );
	}

	return $removed;
}

/**
 * Executa limpeza uma vez e em upgrades do pacote.
 */
function guru_maybe_remove_placeholder_content() {
	if ( wp_installing() ) {
		return;
	}

	$version = '1.0.0';
	if ( get_option( 'guru_placeholder_cleanup_version' ) === $version ) {
		return;
	}

	guru_remove_placeholder_content();
	update_option( 'guru_placeholder_cleanup_version', $version, false );
}
add_action( 'init', 'guru_maybe_remove_placeholder_content', 3 );

/**
 * Desativa Hello Dolly (plugin padrão sem valor para o site).
 */
function guru_deactivate_hello_dolly() {
	if ( ! function_exists( 'deactivate_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	if ( is_plugin_active( 'hello.php' ) ) {
		deactivate_plugins( 'hello.php' );
	}
}
add_action( 'admin_init', 'guru_deactivate_hello_dolly' );

/**
 * robots.txt — orientações Google: permitir conteúdo, bloquear áreas técnicas.
 *
 * @see https://developers.google.com/search/docs/crawling-indexing/robots/intro
 */
function guru_crawling_robots_txt( $output, $public ) {
	if ( ! $public ) {
		return $output;
	}

	$lines   = array();
	$lines[] = 'User-agent: *';
	$lines[] = 'Allow: /';
	$lines[] = 'Disallow: /wp-admin/';
	$lines[] = 'Allow: /wp-admin/admin-ajax.php';
	$lines[] = 'Disallow: /wp-includes/';
	$lines[] = 'Disallow: /wp-json/';
	$lines[] = 'Disallow: /*?s=';
	$lines[] = 'Disallow: /search/';
	$lines[] = 'Disallow: /author/';
	$lines[] = 'Disallow: /cgi-bin/';
	$lines[] = '';
	$lines[] = 'Sitemap: ' . home_url( '/wp-sitemap.xml' );

	return implode( "\n", $lines ) . "\n";
}
add_filter( 'robots_txt', 'guru_crawling_robots_txt', 99, 2 );

/**
 * noindex em URLs de baixo valor (busca, arquivo, autor, data, anexos).
 *
 * @see https://developers.google.com/search/docs/crawling-indexing/block-indexing
 */
function guru_crawling_wp_robots( $robots ) {
	if ( is_search() || is_404() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}

	if ( is_author() || is_date() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}

	if ( is_attachment() ) {
		$robots['noindex'] = true;
		$robots['nofollow'] = true;
	}

	// Feed e páginas de comentários não precisam ranquear.
	if ( is_feed() || is_trackback() ) {
		$robots['noindex'] = true;
	}

	// Blog padrão (post type post) — só indexa se houver conteúdo editorial real.
	if ( is_home() && ! is_front_page() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}

	// Categorias/tags do blog padrão (não usamos para reviews).
	if ( is_category() || is_tag() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}

	return $robots;
}
add_filter( 'wp_robots', 'guru_crawling_wp_robots', 20 );

/**
 * Impede placeholder de entrar no sitemap caso ainda exista.
 */
function guru_crawling_sitemap_exclude_placeholders( $args, $post_type ) {
	if ( ! in_array( $post_type, array( 'post', 'page' ), true ) ) {
		return $args;
	}

	$exclude = get_posts(
		array(
			'post_type'      => $post_type,
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	);

	$placeholder_ids = array();
	foreach ( $exclude as $post_id ) {
		$post = get_post( $post_id );
		if ( $post && guru_is_placeholder_content( $post ) ) {
			$placeholder_ids[] = (int) $post_id;
		}
	}

	if ( $placeholder_ids ) {
		$args['post__not_in'] = array_merge(
			(array) ( $args['post__not_in'] ?? array() ),
			$placeholder_ids
		);
	}

	return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'guru_crawling_sitemap_exclude_placeholders', 10, 2 );
