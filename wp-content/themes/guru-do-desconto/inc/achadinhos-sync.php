<?php
/**
 * Sincroniza achadinhos do dia de content/achadinhos/achadinhos.html → página /achadinhos/.
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

const GURU_ACHADINHOS_SYNC_VERSION = 'achadinhos-v1';

/**
 * Diretório dos arquivos versionados.
 */
function guru_achadinhos_content_dir() {
	return trailingslashit( ABSPATH ) . 'content/achadinhos/';
}

/**
 * Arquivo principal (sempre sobrescrito pelo n8n).
 */
function guru_achadinhos_content_file() {
	return guru_achadinhos_content_dir() . 'achadinhos.html';
}

/**
 * Reutiliza parser de frontmatter dos reviews.
 */
function guru_parse_achadinhos_file( $file_path ) {
	if ( ! function_exists( 'guru_parse_review_file' ) ) {
		return null;
	}
	return guru_parse_review_file( $file_path );
}

/**
 * ID da página fixa /achadinhos/.
 */
function guru_find_achadinhos_page() {
	$pages = get_posts(
		array(
			'post_type'              => 'page',
			'name'                   => 'achadinhos',
			'post_status'            => array( 'publish', 'draft', 'private' ),
			'posts_per_page'         => 1,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	return $pages ? $pages[0] : null;
}

/**
 * Remove páginas duplicadas /achadinhos (mantém apenas a principal).
 */
function guru_cleanup_duplicate_achadinhos_pages( $keep_id ) {
	$keep_id = (int) $keep_id;
	if ( ! $keep_id ) {
		return 0;
	}

	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'name'           => 'achadinhos',
			'post_status'    => array( 'publish', 'draft', 'private', 'trash' ),
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	);

	$removed = 0;
	foreach ( $pages as $page_id ) {
		if ( (int) $page_id === $keep_id ) {
			continue;
		}
		wp_delete_post( (int) $page_id, true );
		++$removed;
	}

	return $removed;
}

/**
 * Sincroniza arquivo HTML → página WordPress.
 */
function guru_sync_achadinhos_file( $file_path ) {
	$raw = file_get_contents( $file_path );
	if ( false === $raw || '' === trim( $raw ) ) {
		return false;
	}

	$parsed = guru_parse_achadinhos_file( $file_path );
	if ( ! $parsed || empty( $parsed['body'] ) ) {
		return false;
	}

	$meta = $parsed['meta'];
	$slug = sanitize_title( $meta['slug'] ?? 'achadinhos' ) ?: 'achadinhos';
	$hash = md5( $raw );

	$existing    = guru_find_achadinhos_page();
	$stored_hash = $existing ? get_post_meta( $existing->ID, '_guru_achadinhos_hash', true ) : '';

	if ( $existing && $stored_hash === $hash && 'publish' === $existing->post_status ) {
		return $existing->ID;
	}

	$title = wp_strip_all_tags( $meta['title'] ?? __( 'Achadinhos do dia', 'guru-do-desconto' ) );
	$status = in_array( $meta['status'] ?? '', array( 'publish', 'draft' ), true )
		? $meta['status']
		: 'publish';

	$post_data = array(
		'post_type'    => 'page',
		'post_title'   => $title,
		'post_name'    => $slug,
		'post_content' => $parsed['body'],
		'post_excerpt' => $meta['meta_description'] ?? '',
		'post_status'  => $status,
	);

	if ( $existing ) {
		$post_data['ID'] = $existing->ID;
		$post_id         = wp_update_post( $post_data, true );
	} else {
		$post_id = wp_insert_post( $post_data, true );
	}

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return false;
	}

	update_post_meta( $post_id, '_wp_page_template', 'page-achadinhos.php' );
	update_post_meta( $post_id, '_guru_achadinhos_hash', $hash );
	update_post_meta( $post_id, '_guru_achadinhos_date', $meta['date'] ?? gmdate( 'Y-m-d' ) );
	update_post_meta( $post_id, '_guru_achadinhos_marketplace', $meta['marketplace'] ?? 'amazon' );
	update_post_meta( $post_id, '_guru_item_list_json', $meta['item_list_json'] ?? '' );
	update_post_meta( $post_id, '_guru_repo_path', 'content/achadinhos/' . basename( $file_path ) );

	if ( function_exists( 'guru_maybe_set_review_thumbnail' ) ) {
		guru_maybe_set_review_thumbnail( $post_id, $meta['featured_image'] ?? '', $title );
	}

	guru_cleanup_duplicate_achadinhos_pages( $post_id );

	return $post_id;
}

/**
 * Fingerprint do arquivo achadinhos.
 */
function guru_achadinhos_content_fingerprint() {
	$file = guru_achadinhos_content_file();
	if ( ! is_readable( $file ) ) {
		return '';
	}
	$raw = file_get_contents( $file );
	return false === $raw ? '' : md5( $raw );
}

/**
 * Verifica se precisa sincronizar.
 */
function guru_achadinhos_need_sync() {
	$file = guru_achadinhos_content_file();
	if ( ! is_readable( $file ) ) {
		return false;
	}

	if ( get_option( 'guru_achadinhos_sync_version', '' ) !== GURU_ACHADINHOS_SYNC_VERSION ) {
		return true;
	}

	$fingerprint = guru_achadinhos_content_fingerprint();
	if ( $fingerprint !== get_option( 'guru_achadinhos_content_fingerprint', '' ) ) {
		return true;
	}

	$page = guru_find_achadinhos_page();
	if ( ! $page || 'publish' !== $page->post_status ) {
		return true;
	}

	$raw = file_get_contents( $file );
	if ( false === $raw ) {
		return false;
	}

	$hash = md5( $raw );
	return get_post_meta( $page->ID, '_guru_achadinhos_hash', true ) !== $hash;
}

/**
 * Executa sincronização.
 *
 * @return array{synced: bool, page_id: int, file: string, skipped?: bool}
 */
function guru_run_achadinhos_sync() {
	if ( get_transient( 'guru_achadinhos_sync_running' ) ) {
		return array(
			'synced'  => false,
			'page_id' => 0,
			'file'    => guru_achadinhos_content_file(),
			'skipped' => true,
		);
	}

	set_transient( 'guru_achadinhos_sync_running', 1, 5 * MINUTE_IN_SECONDS );

	$file    = guru_achadinhos_content_file();
	$page_id = is_readable( $file ) ? guru_sync_achadinhos_file( $file ) : false;

	update_option( 'guru_achadinhos_sync_version', GURU_ACHADINHOS_SYNC_VERSION, false );
	update_option( 'guru_achadinhos_content_fingerprint', guru_achadinhos_content_fingerprint(), false );

	delete_transient( 'guru_achadinhos_sync_running' );

	return array(
		'synced'  => (bool) $page_id,
		'page_id' => (int) $page_id,
		'file'    => $file,
	);
}

/**
 * Auto-sync no init (mesmo padrão dos reviews).
 */
function guru_maybe_sync_achadinhos_from_repo() {
	if ( wp_installing() || ! guru_achadinhos_need_sync() ) {
		return;
	}
	guru_run_achadinhos_sync();
}
add_action( 'init', 'guru_maybe_sync_achadinhos_from_repo', 21 );

/**
 * Cron fallback 15 min.
 */
function guru_schedule_achadinhos_sync_cron() {
	if ( ! wp_next_scheduled( 'guru_cron_sync_achadinhos' ) ) {
		wp_schedule_event( time(), 'guru_every_fifteen_minutes', 'guru_cron_sync_achadinhos' );
	}
}
add_action( 'after_switch_theme', 'guru_schedule_achadinhos_sync_cron' );
add_action( 'init', 'guru_schedule_achadinhos_sync_cron' );

function guru_cron_sync_achadinhos() {
	if ( ! guru_achadinhos_need_sync() ) {
		return;
	}
	guru_run_achadinhos_sync();
}
add_action( 'guru_cron_sync_achadinhos', 'guru_cron_sync_achadinhos' );
