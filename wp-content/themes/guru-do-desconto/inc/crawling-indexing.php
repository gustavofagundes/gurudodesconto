<?php
/**
 * Rastreamento e indexação — sitemap, canonização, breadcrumbs (Google Search Central).
 *
 * @see https://developers.google.com/search/docs/crawling-indexing
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sitemap: reviews + páginas; remove blog padrão (post) do mapa.
 */
function guru_crawling_sitemap_post_types( $post_types ) {
	unset( $post_types['post'] );

	if ( ! isset( $post_types['review'] ) ) {
		$post_types['review'] = true;
	}

	return $post_types;
}
add_filter( 'wp_sitemaps_post_types', 'guru_crawling_sitemap_post_types', 20 );

/**
 * Sitemap: taxonomias padrão do blog não são usadas pelo site.
 */
function guru_crawling_sitemap_taxonomies( $taxonomies ) {
	unset( $taxonomies['category'], $taxonomies['post_tag'] );
	return $taxonomies;
}
add_filter( 'wp_sitemaps_taxonomies', 'guru_crawling_sitemap_taxonomies' );

/**
 * Prioridade e frequência para reviews no sitemap.
 */
function guru_crawling_sitemap_entry( $entry, $post, $post_type ) {
	if ( 'review' === $post_type ) {
		$entry['priority'] = 0.8;
	}

	if ( 'page' === $post_type ) {
		$whatsapp_id = (int) get_option( 'guru_whatsapp_page_id', 0 );
		if ( $whatsapp_id && (int) $post->ID === $whatsapp_id ) {
			$entry['priority'] = 0.9;
		}

		$hub_id = (int) get_option( 'guru_whatsapp_groups_hub_id', 0 );
		if ( $hub_id && (int) $post->ID === $hub_id ) {
			$entry['priority'] = 0.9;
		}

		$group_slug = get_post_meta( $post->ID, '_guru_whatsapp_group_slug', true );
		if ( $group_slug ) {
			$entry['priority'] = 0.85;
		}
	}

	return $entry;
}
add_filter( 'wp_sitemaps_posts_entry', 'guru_crawling_sitemap_entry', 15, 3 );

/**
 * URLs canônicas — reforça o núcleo do WP sem duplicar <link rel="canonical">.
 *
 * @see https://developers.google.com/search/docs/crawling-indexing/consolidate-duplicate-urls
 */
function guru_crawling_canonical_url( $canonical ) {
	if ( function_exists( 'guru_normalize_canonical_url' ) && function_exists( 'guru_is_production_domain' ) && guru_is_production_domain() ) {
		$canonical = guru_normalize_canonical_url( $canonical );
	}

	if ( is_singular( 'review' ) ) {
		return get_permalink();
	}

	if ( is_post_type_archive( 'review' ) ) {
		return get_post_type_archive_link( 'review' );
	}

	if ( is_front_page() ) {
		return function_exists( 'guru_canonical_origin' )
			? guru_canonical_origin() . '/'
			: home_url( '/' );
	}

	if ( function_exists( 'guru_is_whatsapp_group_landing_page' ) && guru_is_whatsapp_group_landing_page() ) {
		return get_permalink();
	}

	if ( function_exists( 'guru_is_whatsapp_landing_page' ) && guru_is_whatsapp_landing_page() ) {
		return get_permalink();
	}

	// Paginação do arquivo de reviews → canônico na primeira página.
	if ( is_paged() && is_post_type_archive( 'review' ) ) {
		return get_post_type_archive_link( 'review' );
	}

	return $canonical;
}
add_filter( 'get_canonical_url', 'guru_crawling_canonical_url', 15 );

/**
 * Paginação: rel prev/next para arquivos (Google entende séries de páginas).
 */
function guru_crawling_pagination_links() {
	if ( ! is_post_type_archive( 'review' ) || (int) $GLOBALS['wp_query']->max_num_pages <= 1 ) {
		return;
	}

	$paged = max( 1, (int) get_query_var( 'paged' ) );
	$max   = (int) $GLOBALS['wp_query']->max_num_pages;
	$base  = get_post_type_archive_link( 'review' );

	if ( $paged > 1 ) {
		$prev = 1 === $paged - 1 ? $base : trailingslashit( $base ) . 'page/' . ( $paged - 1 ) . '/';
		echo '<link rel="prev" href="' . esc_url( $prev ) . '">' . "\n";
	}

	if ( $paged < $max ) {
		echo '<link rel="next" href="' . esc_url( trailingslashit( $base ) . 'page/' . ( $paged + 1 ) . '/' ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'guru_crawling_pagination_links', 4 );

/**
 * BreadcrumbList — dados estruturados para navegação (Google).
 *
 * @see https://developers.google.com/search/docs/appearance/structured-data/breadcrumb
 */
function guru_crawling_breadcrumb_schema() {
	if ( is_admin() || is_404() || is_search() ) {
		return;
	}

	$items   = array();
	$items[] = array(
		'name' => __( 'Início', 'guru-do-desconto' ),
		'url'  => home_url( '/' ),
	);

	if ( is_front_page() ) {
		return;
	}

	if ( is_post_type_archive( 'review' ) ) {
		$items[] = array(
			'name' => __( 'Reviews', 'guru-do-desconto' ),
			'url'  => get_post_type_archive_link( 'review' ),
		);
	} elseif ( is_singular( 'review' ) ) {
		$items[] = array(
			'name' => __( 'Reviews', 'guru-do-desconto' ),
			'url'  => get_post_type_archive_link( 'review' ),
		);
		$items[] = array(
			'name' => get_the_title(),
			'url'  => get_permalink(),
		);
	} elseif ( is_page() ) {
		$items[] = array(
			'name' => get_the_title(),
			'url'  => get_permalink(),
		);
	} else {
		return;
	}

	$list = array();
	foreach ( $items as $i => $item ) {
		$list[] = array(
			'@type'    => 'ListItem',
			'position' => $i + 1,
			'name'     => wp_strip_all_tags( $item['name'] ),
			'item'     => esc_url_raw( $item['url'] ),
		);
	}

	$schema = array(
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => $list,
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'guru_crawling_breadcrumb_schema', 11 );

/**
 * Links internos rastreáveis — garante que menus usem URLs absolutas válidas.
 * (WordPress já gera <a href>; isto corrige edge cases em conteúdo.)
 */
function guru_crawling_fix_empty_links( $content ) {
	if ( ! is_singular() ) {
		return $content;
	}

	return preg_replace( '/<a\s+href=["\']#["\']/i', '<a href="' . esc_url( get_permalink() ) . '"', $content );
}
add_filter( 'the_content', 'guru_crawling_fix_empty_links', 99 );
