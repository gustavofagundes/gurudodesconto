<?php
/**
 * Sincroniza reviews de content/reviews/*.html para o CPT review após deploy Git.
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

const GURU_REVIEW_SYNC_VERSION = 'html-v2';

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
 * Encontra post review existente pelo slug.
 */
function guru_find_review_by_slug( $slug ) {
	$posts = get_posts(
		array(
			'post_type'              => 'review',
			'name'                   => $slug,
			'post_status'            => array( 'publish', 'draft', 'pending', 'future' ),
			'posts_per_page'         => 1,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	return $posts ? $posts[0] : null;
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

	$hash = md5( $raw );

	$existing    = guru_find_review_by_slug( $slug );
	$stored_hash = $existing ? get_post_meta( $existing->ID, '_guru_repo_hash', true ) : '';

	if ( $existing && $stored_hash === $hash ) {
		return $existing->ID;
	}

	$status = in_array( $meta['status'] ?? '', array( 'publish', 'draft', 'pending' ), true )
		? $meta['status']
		: 'publish';

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
		$post_id         = wp_update_post( $post_data, true );
	} else {
		$post_id = wp_insert_post( $post_data, true );
	}

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return false;
	}

	$meta_map = array(
		'_guru_affiliate_link'   => $meta['affiliate_link'] ?? '',
		'_guru_price'            => $meta['price'] ?? '',
		'_guru_price_old'        => $meta['price_old'] ?? '',
		'_guru_rating'           => $meta['rating'] ?? '',
		'_guru_meta_description' => $meta['meta_description'] ?? '',
		'_guru_focus_keyword'    => $meta['focus_keyphrase'] ?? ( $meta['keyword'] ?? '' ),
		'_guru_repo_hash'        => $hash,
		'_guru_repo_path'        => 'content/reviews/' . basename( $file_path ),
	);

	foreach ( $meta_map as $key => $value ) {
		update_post_meta( $post_id, $key, $value );
	}

	return $post_id;
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
 * Sincroniza todos os .html em content/reviews/.
 *
 * @return array{synced: int, files: int, dir: string}
 */
function guru_run_review_sync() {
	$dir   = guru_reviews_content_dir();
	$files = guru_review_html_files();
	$count = 0;

	foreach ( $files as $file ) {
		if ( guru_sync_review_file( $file ) ) {
			++$count;
		}
	}

	$max_mtime = 0;
	foreach ( $files as $file ) {
		$max_mtime = max( $max_mtime, (int) filemtime( $file ) );
	}

	update_option( 'guru_review_sync_version', GURU_REVIEW_SYNC_VERSION, false );
	update_option( 'guru_review_sync_max_mtime', $max_mtime ?: time(), false );
	delete_transient( 'guru_review_sync_running' );

	return array(
		'synced' => $count,
		'files'  => count( $files ),
		'dir'    => $dir,
	);
}

/**
 * Sincroniza todos os .html em content/reviews/ quando os arquivos mudam.
 */
function guru_maybe_sync_reviews_from_repo() {
	if ( wp_installing() ) {
		return;
	}

	$files = guru_review_html_files();
	if ( ! $files ) {
		return;
	}

	$max_mtime = 0;
	foreach ( $files as $file ) {
		$max_mtime = max( $max_mtime, (int) filemtime( $file ) );
	}

	$stored_version = get_option( 'guru_review_sync_version', '' );
	$last_mtime     = (int) get_option( 'guru_review_sync_max_mtime', 0 );

	if ( GURU_REVIEW_SYNC_VERSION === $stored_version && $max_mtime <= $last_mtime ) {
		return;
	}

	if ( get_transient( 'guru_review_sync_running' ) ) {
		return;
	}

	set_transient( 'guru_review_sync_running', 1, 60 );
	guru_run_review_sync();
}
add_action( 'init', 'guru_maybe_sync_reviews_from_repo', 20 );

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
		$result  = guru_run_review_sync();
		$message = sprintf(
			/* translators: 1: synced count, 2: total files */
			__( 'Sincronização concluída: %1$d de %2$d arquivo(s) importado(s).', 'guru-do-desconto' ),
			$result['synced'],
			$result['files']
		);
	}

	$dir        = guru_reviews_content_dir();
	$dir_exists = is_dir( $dir );
	$files      = guru_review_html_files();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Sincronizar Reviews', 'guru-do-desconto' ); ?></h1>

		<?php if ( $message ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php echo esc_html( $message ); ?></p></div>
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
			<?php esc_html_e( 'Após enviar novos arquivos .html para a Hostinger, clique em "Sincronizar agora". Reviews sem status no arquivo são publicados automaticamente.', 'guru-do-desconto' ); ?>
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
