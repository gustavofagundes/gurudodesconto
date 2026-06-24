<?php
/**
 * Sincroniza reviews de content/reviews/*.html para o CPT review após deploy Git.
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

const GURU_REVIEW_SYNC_VERSION = 'html-v7';

/**
 * Diretório dos arquivos versionados no repositório.
 */
function guru_reviews_content_dir() {
	return trailingslashit( ABSPATH ) . 'content/reviews/';
}

/**
 * Parse frontmatter YAML simples + corpo HTML.
 *
 * @param string $raw Conteúdo do arquivo .html.
 * @return array{meta: array<string, string>, body: string}|null
 */
function guru_parse_review_frontmatter( $raw ) {
	if ( ! preg_match( '/\A---\s*\r?\n(.*?)\r?\n---\s*\r?\n(.*)\z/s', $raw, $matches ) ) {
		return null;
	}

	$meta  = array();
	$lines = preg_split( '/\r?\n/', $matches[1] );

	foreach ( $lines as $line ) {
		$line = trim( $line );
		if ( '' === $line || ! str_contains( $line, ':' ) ) {
			continue;
		}

		list( $key, $value ) = array_map( 'trim', explode( ':', $line, 2 ) );
		$key = strtolower( str_replace( '-', '_', $key ) );

		if ( preg_match( '/^"(.*)"$/s', $value, $quoted ) ) {
			$value = str_replace( '\\"', '"', $quoted[1] );
		}

		$meta[ $key ] = $value;
	}

	return array(
		'meta' => $meta,
		'body' => trim( $matches[2] ),
	);
}

/**
 * Lê um arquivo HTML de review e retorna meta + corpo.
 *
 * @param string $file_path Caminho absoluto do arquivo.
 * @return array{meta: array<string, string>, body: string}|null
 */
function guru_parse_review_file( $file_path ) {
	$raw = file_get_contents( $file_path );
	if ( false === $raw || '' === trim( $raw ) ) {
		return null;
	}

	$parsed = guru_parse_review_frontmatter( $raw );
	if ( $parsed ) {
		return $parsed;
	}

	$slug = sanitize_title( pathinfo( $file_path, PATHINFO_FILENAME ) );

	return array(
		'meta' => array(
			'slug'  => $slug,
			'title' => ucwords( str_replace( '-', ' ', $slug ) ),
		),
		'body' => trim( $raw ),
	);
}

/**
 * Normaliza keyword para comparação (evita duplicar "air fryer" vs "Air Fryer").
 */
function guru_normalize_review_keyword( $keyword ) {
	$keyword = strtolower( trim( (string) $keyword ) );
	if ( '' === $keyword ) {
		return '';
	}

	if ( function_exists( 'remove_accents' ) ) {
		$keyword = remove_accents( $keyword );
	}

	return sanitize_title( $keyword );
}

/**
 * Chave canônica para deduplicação — unifica "melhor air fryer" com "air fryer".
 */
function guru_canonical_review_keyword( $keyword ) {
	$normalized = guru_normalize_review_keyword( $keyword );
	if ( ! $normalized ) {
		return '';
	}

	$canonical = preg_replace( '/^melhores?-/', '', $normalized );

	return $canonical ?: $normalized;
}

/**
 * Chave única de identidade do review (keyword da planilha tem prioridade).
 */
function guru_review_dedupe_key( $keyword = '', $focus_keyphrase = '' ) {
	$keyword = trim( (string) $keyword );
	if ( $keyword ) {
		return guru_canonical_review_keyword( $keyword );
	}

	return guru_canonical_review_keyword( $focus_keyphrase );
}

/**
 * Calcula a chave de dedup de um post (meta salva ou legado).
 */
function guru_compute_post_dedupe_key( $post_id ) {
	$stored = get_post_meta( $post_id, '_guru_dedupe_key', true );
	if ( is_string( $stored ) && '' !== $stored ) {
		return $stored;
	}

	return guru_review_dedupe_key(
		get_post_meta( $post_id, '_guru_keyword', true ),
		get_post_meta( $post_id, '_guru_focus_keyword', true )
	);
}

/**
 * Tenta adquirir lock global de sincronização (init, cron, webhook, admin).
 */
function guru_acquire_review_sync_lock() {
	if ( get_transient( 'guru_review_sync_running' ) ) {
		return false;
	}

	set_transient( 'guru_review_sync_running', 1, 5 * MINUTE_IN_SECONDS );

	return true;
}

/**
 * Libera lock global de sincronização.
 */
function guru_release_review_sync_lock() {
	delete_transient( 'guru_review_sync_running' );
}

/**
 * Encontra post review existente pelo slug.
 */
function guru_find_review_by_slug( $slug ) {
	$slug = sanitize_title( $slug );
	if ( ! $slug ) {
		return null;
	}

	$posts = get_posts(
		array(
			'post_type'              => 'review',
			'name'                   => $slug,
			'post_status'            => array( 'publish', 'draft', 'pending', 'future', 'private' ),
			'posts_per_page'         => 1,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	return $posts ? $posts[0] : null;
}

/**
 * Encontra post review pelo caminho versionado no repositório.
 */
function guru_find_review_by_repo_path( $repo_path ) {
	$repo_path = ltrim( (string) $repo_path, '/' );
	if ( '' === $repo_path ) {
		return null;
	}

	$posts = get_posts(
		array(
			'post_type'              => 'review',
			'post_status'            => array( 'publish', 'draft', 'pending', 'future', 'private' ),
			'posts_per_page'         => 1,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'meta_key'               => '_guru_repo_path',
			'meta_value'             => $repo_path,
		)
	);

	return $posts ? $posts[0] : null;
}

/**
 * Lista reviews com a mesma chave de identidade (keyword / focus_keyphrase).
 *
 * @return WP_Post[]
 */
function guru_find_reviews_by_dedupe_key( $dedupe_key ) {
	$dedupe_key = (string) $dedupe_key;
	if ( '' === $dedupe_key ) {
		return array();
	}

	$posts = get_posts(
		array(
			'post_type'              => 'review',
			'post_status'            => array( 'publish', 'draft', 'pending', 'future', 'private' ),
			'posts_per_page'         => -1,
			'no_found_rows'          => true,
			'update_post_meta_cache' => true,
			'update_post_term_cache' => false,
		)
	);

	if ( ! $posts ) {
		return array();
	}

	$matched = array();
	foreach ( $posts as $post ) {
		if ( guru_compute_post_dedupe_key( $post->ID ) === $dedupe_key ) {
			$matched[] = $post;
		}
	}

	return $matched;
}

/**
 * @deprecated Use guru_find_reviews_by_dedupe_key().
 * @return WP_Post[]
 */
function guru_find_reviews_by_keyword( $keyword ) {
	return guru_find_reviews_by_dedupe_key( guru_canonical_review_keyword( $keyword ) );
}

/**
 * Resolve qual post atualizar: slug → repo path → chave de dedup.
 */
function guru_resolve_existing_review( $slug, $keyword, $repo_path = '', $focus_keyphrase = '' ) {
	$by_slug = guru_find_review_by_slug( $slug );
	if ( $by_slug ) {
		return $by_slug;
	}

	if ( $repo_path ) {
		$by_repo = guru_find_review_by_repo_path( $repo_path );
		if ( $by_repo ) {
			return $by_repo;
		}
	}

	$dedupe_key = guru_review_dedupe_key( $keyword, $focus_keyphrase );
	$matches    = guru_find_reviews_by_dedupe_key( $dedupe_key );
	if ( ! $matches ) {
		return null;
	}

	foreach ( $matches as $post ) {
		if ( $post->post_name === $slug ) {
			return $post;
		}
	}

	foreach ( $matches as $post ) {
		if ( get_post_meta( $post->ID, '_guru_repo_path', true ) ) {
			return $post;
		}
	}

	return $matches[0];
}

/**
 * Remove cópias extras do mesmo review (mesma chave de dedup).
 */
function guru_dedupe_reviews_by_key( $dedupe_key, $keep_id ) {
	$keep_id    = (int) $keep_id;
	$dedupe_key = (string) $dedupe_key;
	if ( ! $keep_id || '' === $dedupe_key ) {
		return 0;
	}

	$removed = 0;
	foreach ( guru_find_reviews_by_dedupe_key( $dedupe_key ) as $post ) {
		if ( (int) $post->ID === $keep_id ) {
			continue;
		}
		wp_trash_post( $post->ID );
		++$removed;
	}

	return $removed;
}

/**
 * @deprecated Use guru_dedupe_reviews_by_key().
 */
function guru_dedupe_reviews_by_keyword( $keyword, $keep_id ) {
	return guru_dedupe_reviews_by_key( guru_canonical_review_keyword( $keyword ), $keep_id );
}

/**
 * Varre todos os reviews e remove duplicatas por keyword (mantém o mais recente com repo path).
 *
 * @return int Número de posts enviados à lixeira.
 */
function guru_cleanup_duplicate_reviews() {
	$posts = get_posts(
		array(
			'post_type'      => 'review',
			'post_status'    => array( 'publish', 'draft', 'pending', 'future', 'private' ),
			'posts_per_page' => -1,
			'orderby'        => 'modified',
			'order'          => 'DESC',
		)
	);

	$groups = array();

	foreach ( $posts as $post ) {
		$dedupe_key = guru_compute_post_dedupe_key( $post->ID );
		if ( ! $dedupe_key ) {
			continue;
		}
		$groups[ $dedupe_key ][] = $post;
	}

	$removed = 0;

	foreach ( $groups as $group ) {
		if ( count( $group ) < 2 ) {
			continue;
		}

		usort(
			$group,
			static function ( $a, $b ) {
				$a_repo = (bool) get_post_meta( $a->ID, '_guru_repo_path', true );
				$b_repo = (bool) get_post_meta( $b->ID, '_guru_repo_path', true );
				if ( $a_repo !== $b_repo ) {
					return $b_repo <=> $a_repo;
				}
				return strtotime( $b->post_modified ) <=> strtotime( $a->post_modified );
			}
		);

		$keep_id = (int) $group[0]->ID;
		$dedupe  = guru_compute_post_dedupe_key( $keep_id );
		if ( $dedupe ) {
			update_post_meta( $keep_id, '_guru_dedupe_key', $dedupe );
		}
		for ( $i = 1; $i < count( $group ); $i++ ) {
			wp_trash_post( $group[ $i ]->ID );
			++$removed;
		}
	}

	return $removed;
}

/**
 * Importa ou atualiza um review a partir de um arquivo .html.
 */
function guru_sync_review_file( $file_path ) {
	$raw = file_get_contents( $file_path );
	if ( false === $raw || '' === trim( $raw ) ) {
		return false;
	}

	$parsed = guru_parse_review_file( $file_path );
	if ( ! $parsed || empty( $parsed['body'] ) ) {
		return false;
	}

	$meta = $parsed['meta'];
	$slug = sanitize_title( $meta['slug'] ?? pathinfo( $file_path, PATHINFO_FILENAME ) );
	if ( ! $slug ) {
		return false;
	}

	$keyword            = trim( (string) ( $meta['keyword'] ?? '' ) );
	$focus_keyphrase    = trim( (string) ( $meta['focus_keyphrase'] ?? '' ) );
	$keyword_normalized = guru_normalize_review_keyword( $keyword );
	$dedupe_key         = guru_review_dedupe_key( $keyword, $focus_keyphrase );
	$repo_path          = 'content/reviews/' . basename( $file_path );

	$hash = md5( $raw );

	$existing    = guru_resolve_existing_review( $slug, $keyword, $repo_path, $focus_keyphrase );
	$stored_hash = $existing ? get_post_meta( $existing->ID, '_guru_repo_hash', true ) : '';

	$status = in_array( $meta['status'] ?? '', array( 'publish', 'draft', 'pending' ), true )
		? $meta['status']
		: 'publish';

	if ( $existing && $stored_hash === $hash ) {
		if ( 'publish' === $status && 'publish' !== $existing->post_status ) {
			wp_update_post(
				array(
					'ID'          => $existing->ID,
					'post_status' => 'publish',
				)
			);
		}

		// Meta pode estar desatualizada (ex.: featured_image errada) mesmo com mesmo hash.
		$stale_meta = array(
			'_guru_affiliate_link'     => $meta['affiliate_link'] ?? '',
			'_guru_price'              => $meta['price'] ?? '',
			'_guru_price_old'          => $meta['price_old'] ?? '',
			'_guru_rating'             => $meta['rating'] ?? '',
			'_guru_featured_image_url' => $meta['featured_image'] ?? '',
			'_guru_keyword'            => $keyword,
			'_guru_keyword_normalized' => $keyword_normalized,
			'_guru_dedupe_key'         => $dedupe_key,
			'_guru_repo_path'          => $repo_path,
		);
		foreach ( $stale_meta as $key => $value ) {
			update_post_meta( $existing->ID, $key, $value );
		}
		guru_maybe_set_review_thumbnail( $existing->ID, $meta['featured_image'] ?? '', $meta['title'] ?? $slug );

		if ( $dedupe_key ) {
			guru_dedupe_reviews_by_key( $dedupe_key, $existing->ID );
		}

		return $existing->ID;
	}

	$post_data = array(
		'post_type'    => 'review',
		'post_title'   => wp_strip_all_tags( $meta['title'] ?? $slug ),
		'post_name'    => $slug,
		'post_content' => $parsed['body'],
		'post_excerpt' => $meta['meta_description'] ?? '',
		'post_status'  => $status,
	);

	if ( $existing ) {
		$post_data['ID'] = $existing->ID;
		// Preserva URL canônica se o slug do arquivo mudou entre execuções.
		if ( $existing->post_name && $existing->post_name !== $slug ) {
			$post_data['post_name'] = $existing->post_name;
		}
		$post_id = wp_update_post( $post_data, true );
	} else {
		$collision = guru_find_review_by_slug( $slug );
		if ( $collision ) {
			$post_data['ID'] = $collision->ID;
			$post_id         = wp_update_post( $post_data, true );
		} else {
			$post_id = wp_insert_post( $post_data, true );
		}
	}

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return false;
	}

	$meta_map = array(
		'_guru_affiliate_link'         => $meta['affiliate_link'] ?? '',
		'_guru_price'                  => $meta['price'] ?? '',
		'_guru_price_old'              => $meta['price_old'] ?? '',
		'_guru_rating'                 => $meta['rating'] ?? '',
		'_guru_meta_description'       => $meta['meta_description'] ?? '',
		'_guru_focus_keyword'          => $meta['focus_keyphrase'] ?? ( $meta['keyword'] ?? '' ),
		'_guru_keyword'                => $keyword,
		'_guru_keyword_normalized'     => $keyword_normalized,
		'_guru_dedupe_key'             => $dedupe_key,
		'_guru_seo_title'              => $meta['seo_title'] ?? '',
		'_guru_featured_image_url'     => $meta['featured_image'] ?? '',
		'_guru_faq_json'               => $meta['faq_json'] ?? '',
		'_guru_item_list_json'         => $meta['item_list_json'] ?? '',
		'_guru_repo_hash'              => $hash,
		'_guru_repo_path'              => $repo_path,
	);

	foreach ( $meta_map as $key => $value ) {
		update_post_meta( $post_id, $key, $value );
	}

	guru_maybe_set_review_thumbnail( $post_id, $meta['featured_image'] ?? '', $meta['title'] ?? $slug );

	if ( $dedupe_key ) {
		guru_dedupe_reviews_by_key( $dedupe_key, $post_id );
	}

	return $post_id;
}

/**
 * Define imagem destacada a partir de URL externa (ML).
 *
 * @param int    $post_id   ID do post.
 * @param string $image_url URL da imagem.
 * @param string $title     Título para alt text.
 */
function guru_maybe_set_review_thumbnail( $post_id, $image_url, $title = '' ) {
	$image_url = esc_url_raw( $image_url );
	if ( ! $image_url ) {
		return;
	}

	$stored_url = get_post_meta( $post_id, '_guru_featured_image_url', true );
	$thumb_id   = get_post_thumbnail_id( $post_id );

	if ( $thumb_id ) {
		$file = get_attached_file( $thumb_id );
		if ( $file && file_exists( $file ) && $stored_url === $image_url ) {
			return;
		}
		if ( ! $file || ! file_exists( $file ) ) {
			delete_post_thumbnail( $post_id );
		}
	}

	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$attachment_id = media_sideload_image( $image_url, $post_id, $title, 'id' );
	if ( is_wp_error( $attachment_id ) ) {
		return;
	}

	set_post_thumbnail( $post_id, (int) $attachment_id );
}

/**
 * Lista arquivos HTML de review no repositório.
 *
 * @return string[]
 */
function guru_review_html_files() {
	$dir = guru_reviews_content_dir();
	if ( ! is_dir( $dir ) ) {
		return array();
	}

	$files = glob( $dir . '*.html' );
	return $files ? $files : array();
}

/**
 * Fingerprint dos arquivos .html (detecta mudanças após deploy/FTP).
 */
function guru_reviews_content_fingerprint() {
	$files = guru_review_html_files();
	if ( ! $files ) {
		return '';
	}

	sort( $files );
	$parts = array();

	foreach ( $files as $file ) {
		$raw = file_get_contents( $file );
		if ( false === $raw ) {
			continue;
		}
		$parts[] = basename( $file ) . ':' . md5( $raw );
	}

	return $parts ? md5( implode( '|', $parts ) ) : '';
}

/**
 * Verifica se há arquivos novos ou alterados para sincronizar.
 */
function guru_reviews_need_sync() {
	$files = guru_review_html_files();
	if ( ! $files ) {
		return false;
	}

	if ( get_option( 'guru_review_sync_version', '' ) !== GURU_REVIEW_SYNC_VERSION ) {
		return true;
	}

	$fingerprint = guru_reviews_content_fingerprint();
	if ( $fingerprint !== get_option( 'guru_review_content_fingerprint', '' ) ) {
		return true;
	}

	foreach ( $files as $file ) {
		$raw = file_get_contents( $file );
		if ( false === $raw ) {
			continue;
		}

		$parsed = guru_parse_review_file( $file );
		if ( ! $parsed ) {
			continue;
		}

		$slug            = sanitize_title( $parsed['meta']['slug'] ?? pathinfo( $file, PATHINFO_FILENAME ) );
		$keyword         = trim( (string) ( $parsed['meta']['keyword'] ?? '' ) );
		$focus_keyphrase = trim( (string) ( $parsed['meta']['focus_keyphrase'] ?? '' ) );
		$repo_path       = 'content/reviews/' . basename( $file );
		$existing        = guru_resolve_existing_review( $slug, $keyword, $repo_path, $focus_keyphrase );
		$hash     = md5( $raw );

		if ( ! $existing || get_post_meta( $existing->ID, '_guru_repo_hash', true ) !== $hash ) {
			return true;
		}
	}

	return false;
}

/**
 * Secret para webhook de sincronização (n8n / CI).
 */
function guru_review_sync_secret() {
	if ( function_exists( 'guru_env' ) ) {
		$secret = guru_env( 'GURU_REVIEW_SYNC_SECRET' );
		if ( $secret ) {
			return $secret;
		}
	}

	return defined( 'AUTH_KEY' ) ? AUTH_KEY : '';
}

/**
 * Sincroniza todos os .html em content/reviews/.
 *
 * @return array{synced: int, files: int, dir: string, deduped: int, skipped?: bool}
 */
function guru_run_review_sync() {
	if ( ! guru_acquire_review_sync_lock() ) {
		return array(
			'synced'  => 0,
			'files'   => count( guru_review_html_files() ),
			'dir'     => guru_reviews_content_dir(),
			'deduped' => 0,
			'skipped' => true,
		);
	}

	$dir   = guru_reviews_content_dir();
	$files = guru_review_html_files();
	$count = 0;

	foreach ( $files as $file ) {
		if ( guru_sync_review_file( $file ) ) {
			++$count;
		}
	}

	$deduped = guru_cleanup_duplicate_reviews();

	update_option( 'guru_review_sync_version', GURU_REVIEW_SYNC_VERSION, false );
	update_option( 'guru_review_content_fingerprint', guru_reviews_content_fingerprint(), false );
	guru_release_review_sync_lock();

	return array(
		'synced'  => $count,
		'files'   => count( $files ),
		'dir'     => $dir,
		'deduped' => $deduped,
	);
}

/**
 * Sincroniza automaticamente quando arquivos .html mudam (qualquer visita ao site).
 */
function guru_maybe_sync_reviews_from_repo() {
	if ( wp_installing() || ! guru_reviews_need_sync() ) {
		return;
	}

	guru_run_review_sync();
}
add_action( 'init', 'guru_maybe_sync_reviews_from_repo', 20 );

/**
 * Cron: sincroniza a cada 15 minutos (fallback na Hostinger).
 */
function guru_review_sync_cron_schedules( $schedules ) {
	$schedules['guru_every_fifteen_minutes'] = array(
		'interval' => 15 * MINUTE_IN_SECONDS,
		'display'  => __( 'A cada 15 minutos (Guru reviews)', 'guru-do-desconto' ),
	);
	return $schedules;
}
add_filter( 'cron_schedules', 'guru_review_sync_cron_schedules' );

function guru_schedule_review_sync_cron() {
	if ( ! wp_next_scheduled( 'guru_cron_sync_reviews' ) ) {
		wp_schedule_event( time(), 'guru_every_fifteen_minutes', 'guru_cron_sync_reviews' );
	}
}
add_action( 'after_switch_theme', 'guru_schedule_review_sync_cron' );
add_action( 'init', 'guru_schedule_review_sync_cron' );

function guru_cron_sync_reviews() {
	if ( ! guru_reviews_need_sync() ) {
		return;
	}
	guru_run_review_sync();
}
add_action( 'guru_cron_sync_reviews', 'guru_cron_sync_reviews' );

/**
 * Remove reviews de exemplo que não vieram de content/reviews/*.html.
 */
function guru_remove_fake_reviews_once() {
	if ( wp_installing() || get_option( 'guru_fake_reviews_removed' ) ) {
		return;
	}

	$posts = get_posts(
		array(
			'post_type'      => 'review',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'     => '_guru_repo_path',
					'compare' => 'NOT EXISTS',
				),
			),
		)
	);

	foreach ( $posts as $post_id ) {
		wp_delete_post( $post_id, true );
	}

	update_option( 'guru_fake_reviews_removed', true, false );
}
add_action( 'init', 'guru_remove_fake_reviews_once', 21 );

/**
 * Publica reviews sincronizados que ficaram como rascunho (correção única).
 */
function guru_publish_draft_repo_reviews_once() {
	if ( wp_installing() || get_option( 'guru_draft_repo_reviews_published' ) ) {
		return;
	}

	$posts = get_posts(
		array(
			'post_type'      => 'review',
			'post_status'    => 'draft',
			'posts_per_page' => -1,
			'meta_key'       => '_guru_repo_path',
		)
	);

	foreach ( $posts as $post ) {
		wp_update_post(
			array(
				'ID'          => $post->ID,
				'post_status' => 'publish',
			)
		);
	}

	update_option( 'guru_draft_repo_reviews_published', true, false );
}
add_action( 'init', 'guru_publish_draft_repo_reviews_once', 22 );

/**
 * Remove duplicatas legadas após upgrade para html-v7 (mesmo sem arquivos .html).
 */
function guru_dedupe_reviews_once_v7() {
	if ( wp_installing() || get_option( 'guru_dedupe_v7_done' ) ) {
		return;
	}

	guru_cleanup_duplicate_reviews();
	update_option( 'guru_dedupe_v7_done', true, false );
}
add_action( 'init', 'guru_dedupe_reviews_once_v7', 23 );

/**
 * Página no admin para sincronizar reviews na Hostinger (sem WP-CLI).
 */
function guru_review_sync_admin_menu() {
	add_submenu_page(
		'edit.php?post_type=review',
		__( 'Sincronizar do Repositório', 'guru-do-desconto' ),
		__( 'Sincronizar', 'guru-do-desconto' ),
		'manage_options',
		'guru-sync-reviews',
		'guru_review_sync_admin_page'
	);
}
add_action( 'admin_menu', 'guru_review_sync_admin_menu' );

/**
 * Renderiza a página de sincronização.
 */
function guru_review_sync_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$result  = null;
	$message = '';

	if ( isset( $_POST['guru_sync_reviews'] ) && check_admin_referer( 'guru_sync_reviews' ) ) {
		$result = guru_run_review_sync();
		if ( ! empty( $result['skipped'] ) ) {
			$message = __( 'Outra sincronização já está em andamento. Tente novamente em instantes.', 'guru-do-desconto' );
		} else {
			$message = sprintf(
				/* translators: 1: synced count, 2: total files, 3: deduped count */
				__( 'Sincronização concluída: %1$d de %2$d arquivo(s) importado(s). %3$d duplicata(s) removida(s).', 'guru-do-desconto' ),
				$result['synced'],
				$result['files'],
				$result['deduped'] ?? 0
			);
		}
	}

	$dir        = guru_reviews_content_dir();
	$dir_exists = is_dir( $dir );
	$files      = guru_review_html_files();
	$published  = (int) wp_count_posts( 'review' )->publish;
	$drafts     = (int) wp_count_posts( 'review' )->draft;
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Sincronizar Reviews', 'guru-do-desconto' ); ?></h1>

		<?php if ( $message ) : ?>
			<div class="notice <?php echo ! empty( $result['skipped'] ) ? 'notice-warning' : 'notice-success'; ?> is-dismissible"><p><?php echo esc_html( $message ); ?></p></div>
		<?php endif; ?>

		<p><?php esc_html_e( 'Os reviews são lidos de arquivos .html na pasta content/reviews/ na raiz do WordPress.', 'guru-do-desconto' ); ?></p>

		<table class="widefat striped" style="max-width:720px;margin:1rem 0">
			<tbody>
				<tr>
					<th scope="row"><?php esc_html_e( 'Pasta esperada', 'guru-do-desconto' ); ?></th>
					<td><code><?php echo esc_html( $dir ); ?></code></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Pasta encontrada?', 'guru-do-desconto' ); ?></th>
					<td><?php echo $dir_exists ? '✅ ' . esc_html__( 'Sim', 'guru-do-desconto' ) : '❌ ' . esc_html__( 'Não — envie a pasta content/reviews/ via FTP/File Manager', 'guru-do-desconto' ); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Arquivos .html', 'guru-do-desconto' ); ?></th>
					<td>
						<?php
						if ( $files ) {
							echo '<ul style="margin:0">';
							foreach ( $files as $file ) {
								echo '<li><code>' . esc_html( basename( $file ) ) . '</code></li>';
							}
							echo '</ul>';
						} else {
							esc_html_e( 'Nenhum arquivo encontrado', 'guru-do-desconto' );
						}
						?>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'No WordPress', 'guru-do-desconto' ); ?></th>
					<td>
						<?php
						printf(
							/* translators: 1: published count, 2: draft count */
							esc_html__( '%1$d publicado(s), %2$d rascunho(s)', 'guru-do-desconto' ),
							$published,
							$drafts
						);
						?>
						<?php if ( $drafts > 0 ) : ?>
							<br><span style="color:#b32d2e"><?php esc_html_e( 'Rascunhos não aparecem no site — clique em Sincronizar agora.', 'guru-do-desconto' ); ?></span>
						<?php endif; ?>
					</td>
				</tr>
			</tbody>
		</table>

		<form method="post">
			<?php wp_nonce_field( 'guru_sync_reviews' ); ?>
			<p>
				<button type="submit" name="guru_sync_reviews" class="button button-primary" <?php disabled( ! $files ); ?>>
					<?php esc_html_e( 'Sincronizar agora', 'guru-do-desconto' ); ?>
				</button>
			</p>
		</form>

		<p class="description">
			<?php esc_html_e( 'A sincronização é automática: ao enviar novos .html, o site importa na próxima visita ou em até 15 minutos (cron). O botão acima força a sincronização imediata.', 'guru-do-desconto' ); ?>
		</p>
		<p class="description">
			<?php
			printf(
				/* translators: %s: REST URL */
				esc_html__( 'Webhook (n8n/CI): POST %s com header X-Guru-Sync-Token (defina GURU_REVIEW_SYNC_SECRET no .env).', 'guru-do-desconto' ),
				esc_url( rest_url( 'guru/v1/sync-reviews' ) )
			);
			?>
		</p>
	</div>
	<?php
}

/**
 * WP-CLI: wp guru sync-reviews
 */
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command(
		'guru sync-reviews',
		function () {
			$files = guru_review_html_files();
			if ( ! $files ) {
				WP_CLI::error( 'Nenhum arquivo .html encontrado em content/reviews/.' );
			}

			$result = guru_run_review_sync();
			foreach ( $files as $file ) {
				WP_CLI::log( "OK: {$file}" );
			}
			WP_CLI::success( "Sincronizados {$result['synced']} review(s)." );
		}
	);
}
