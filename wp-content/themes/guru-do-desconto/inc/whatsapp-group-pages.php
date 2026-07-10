<?php
/**
 * Landing pages por grupo WhatsApp — criação e helpers.
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Slug da página-pai dos grupos (URL canônica: /grupos-whatsapp/).
 */
function guru_whatsapp_groups_hub_slug() {
	return 'grupos-whatsapp';
}

/**
 * Path da requisição atual (sem barra inicial/final).
 */
function guru_request_path() {
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
	return trim( (string) wp_parse_url( $uri, PHP_URL_PATH ), '/' );
}

/**
 * Redireciona URLs antigas (singular) e aliases para /grupos-whatsapp/.
 */
function guru_whatsapp_hub_redirect_aliases() {
	if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return;
	}

	$path = strtolower( guru_request_path() );
	$hub  = guru_whatsapp_groups_hub_slug();

	if ( $path === $hub ) {
		return;
	}

	// Slug antigo (singular) e filhos: /grupo-whatsapp/ → /grupos-whatsapp/
	if ( $path === 'grupo-whatsapp' || str_starts_with( $path, 'grupo-whatsapp/' ) ) {
		$suffix = $path === 'grupo-whatsapp' ? '' : substr( $path, strlen( 'grupo-whatsapp/' ) );
		$target = $suffix ? home_url( '/' . $hub . '/' . $suffix . '/' ) : guru_whatsapp_groups_hub_url();
		wp_safe_redirect( $target, 301 );
		exit;
	}

	// Slug antigo mulher → moda-beleza
	$slug_redirects = array(
		$hub . '/mulher'           => 'moda-beleza',
		'grupo-whatsapp/mulher'    => 'moda-beleza',
		$hub . '/moda-e-beleza'    => 'moda-beleza',
		'moda-e-beleza'            => 'moda-beleza',
	);
	if ( isset( $slug_redirects[ $path ] ) ) {
		wp_safe_redirect( home_url( '/' . $hub . '/' . $slug_redirects[ $path ] . '/' ), 301 );
		exit;
	}

	// Grupos removidos (Até R$50 / Homem) → hub
	$retired = array(
		$hub . '/ate-50',
		$hub . '/homem',
		'grupo-whatsapp/ate-50',
		'grupo-whatsapp/homem',
	);
	if ( in_array( $path, $retired, true ) ) {
		wp_safe_redirect( guru_whatsapp_groups_hub_url(), 301 );
		exit;
	}

	$aliases = array(
		'grupos-de-whatsapp',
		'grupo-de-whatsapp',
		'grupos',
		'mega-achadinhos',
		'achadinhos-da-sho',
		'maternidade',
	);

	if ( in_array( $path, $aliases, true ) ) {
		$map = array(
			'mega-achadinhos'   => 'geral',
			'achadinhos-da-sho' => 'shopee',
			'maternidade'       => 'kids',
		);
		if ( isset( $map[ $path ] ) ) {
			wp_safe_redirect( home_url( '/' . $hub . '/' . $map[ $path ] . '/' ), 301 );
			exit;
		}
		wp_safe_redirect( guru_whatsapp_groups_hub_url(), 301 );
		exit;
	}
}
add_action( 'init', 'guru_whatsapp_hub_redirect_aliases', 1 );
add_action( 'template_redirect', 'guru_whatsapp_hub_redirect_aliases', 1 );

/**
 * URL limpa (sem #) da página hub com todos os grupos.
 */
function guru_whatsapp_groups_hub_url() {
	$hub_id = (int) get_option( 'guru_whatsapp_groups_hub_id', 0 );
	if ( $hub_id ) {
		$url = get_permalink( $hub_id );
		if ( $url ) {
			return $url;
		}
	}

	return home_url( '/' . guru_whatsapp_groups_hub_slug() . '/' );
}

/**
 * URL da landing de um grupo.
 */
function guru_whatsapp_group_landing_url( $group ) {
	$slug = is_array( $group ) ? ( $group['slug'] ?? '' ) : (string) $group;
	if ( ! $slug ) {
		return guru_whatsapp_groups_hub_url();
	}

	$page_ids = get_option( 'guru_whatsapp_group_page_ids', array() );
	if ( ! empty( $page_ids[ $slug ] ) ) {
		$url = get_permalink( (int) $page_ids[ $slug ] );
		if ( $url ) {
			return $url;
		}
	}

	return home_url( '/' . guru_whatsapp_groups_hub_slug() . '/' . $slug . '/' );
}

/**
 * Grupo associado à página atual (landing por nicho).
 */
function guru_get_whatsapp_group_from_page( $post_id = 0 ) {
	$post_id = $post_id ?: get_the_ID();
	if ( ! $post_id ) {
		return null;
	}

	$slug = get_post_meta( $post_id, '_guru_whatsapp_group_slug', true );
	if ( ! $slug ) {
		return null;
	}

	return guru_get_whatsapp_group( $slug );
}

/**
 * É landing de um grupo específico?
 */
function guru_is_whatsapp_group_landing_page( $post_id = 0 ) {
	return (bool) guru_get_whatsapp_group_from_page( $post_id );
}

/**
 * É a página hub /grupo-whatsapp/ ?
 */
function guru_is_whatsapp_groups_hub_page( $post_id = 0 ) {
	$post_id = $post_id ?: get_the_ID();
	$hub_id  = (int) get_option( 'guru_whatsapp_groups_hub_id', 0 );
	return $hub_id && (int) $post_id === $hub_id;
}

/**
 * Meta description SEO por grupo.
 */
function guru_whatsapp_group_meta_description( $group ) {
	if ( ! empty( $group['meta_description'] ) ) {
		return $group['meta_description'];
	}

	return sprintf(
		/* translators: %s: group name */
		__( 'Entre no grupo %s no WhatsApp — promoções grátis do Mercado Livre, Shopee e Amazon. 100%% gratuito.', 'guru-do-desconto' ),
		$group['name'] ?? ''
	);
}

/**
 * FAQ específica da landing do grupo.
 *
 * @return array<int, array{question: string, answer: string}>
 */
function guru_whatsapp_group_faq_items( $group ) {
	$name = $group['name'] ?? __( 'grupo', 'guru-do-desconto' );
	$tag  = $group['tagline'] ?? '';

	return array(
		array(
			'question' => sprintf(
				/* translators: %s: group name */
				__( 'O que recebo no grupo %s?', 'guru-do-desconto' ),
				$name
			),
			'answer'   => $group['description'] ?? '',
		),
		array(
			'question' => __( 'O grupo é gratuito?', 'guru-do-desconto' ),
			'answer'   => __( 'Sim! A participação é 100% gratuita. Você recebe alertas de promoções sem pagar nada e pode sair quando quiser.', 'guru-do-desconto' ),
		),
		array(
			'question' => __( 'Como entrar no grupo pelo WhatsApp?', 'guru-do-desconto' ),
			'answer'   => __( 'Clique no botão verde "Entrar no grupo no WhatsApp". O link abre o app para você confirmar a entrada — leva menos de 1 minuto.', 'guru-do-desconto' ),
		),
		array(
			'question' => __( 'Existem outros grupos além deste?', 'guru-do-desconto' ),
			'answer'   => sprintf(
				/* translators: %s: group tagline */
				__( 'Sim! O Guru do Desconto tem 6 grupos por nicho (%s e mais). Você pode entrar em quantos quiser.', 'guru-do-desconto' ),
				$tag
			),
		),
	);
}

/**
 * Mapa de slugs antigos → novos (renomeação de landings).
 *
 * @return array<string, string> new_slug => old_slug
 */
function guru_whatsapp_group_slug_renames() {
	return array(
		'moda-beleza' => 'mulher',
	);
}

/**
 * Garante renomeação mulher → moda-beleza (e similares) mesmo se a migração por versão já rodou.
 */
function guru_whatsapp_fix_renamed_slugs() {
	$hub_id = (int) get_option( 'guru_whatsapp_groups_hub_id', 0 );
	if ( ! $hub_id ) {
		return;
	}

	$page_ids = (array) get_option( 'guru_whatsapp_group_page_ids', array() );
	$changed  = false;

	foreach ( guru_whatsapp_group_slug_renames() as $new_slug => $old_slug ) {
		// Já existe a URL nova?
		$new_page = get_page_by_path( guru_whatsapp_groups_hub_slug() . '/' . $new_slug, OBJECT, 'page' );
		if ( $new_page && 'publish' === $new_page->post_status ) {
			$page_ids[ $new_slug ] = (int) $new_page->ID;
			unset( $page_ids[ $old_slug ] );
			update_post_meta( (int) $new_page->ID, '_guru_whatsapp_group_slug', $new_slug );
			continue;
		}

		$old_page = get_page_by_path( guru_whatsapp_groups_hub_slug() . '/' . $old_slug, OBJECT, 'page' );
		if ( ! $old_page && ! empty( $page_ids[ $old_slug ] ) ) {
			$old_page = get_post( (int) $page_ids[ $old_slug ] );
		}
		if ( ! $old_page ) {
			// Busca por meta do grupo antigo.
			$found = get_posts(
				array(
					'post_type'      => 'page',
					'post_status'    => array( 'publish', 'draft', 'private' ),
					'posts_per_page' => 1,
					'post_parent'    => $hub_id,
					'meta_key'       => '_guru_whatsapp_group_slug',
					'meta_value'     => $old_slug,
				)
			);
			$old_page = $found[0] ?? null;
		}

		if ( ! $old_page ) {
			continue;
		}

		$result = wp_update_post(
			array(
				'ID'          => (int) $old_page->ID,
				'post_name'   => $new_slug,
				'post_parent' => $hub_id,
				'post_status' => 'publish',
			),
			true
		);

		if ( is_wp_error( $result ) ) {
			continue;
		}

		update_post_meta( (int) $old_page->ID, '_guru_whatsapp_group_slug', $new_slug );
		$page_ids[ $new_slug ] = (int) $old_page->ID;
		unset( $page_ids[ $old_slug ] );
		$changed = true;
	}

	if ( $changed || $page_ids !== (array) get_option( 'guru_whatsapp_group_page_ids', array() ) ) {
		update_option( 'guru_whatsapp_group_page_ids', $page_ids, false );
	}

	if ( $changed ) {
		flush_rewrite_rules( false );
	}
}
add_action( 'init', 'guru_whatsapp_fix_renamed_slugs', 7 );

/**
 * Cria ou atualiza páginas /grupos-whatsapp/{slug}/.
 */
function guru_ensure_whatsapp_group_pages() {
	$version = 7;
	if ( (int) get_option( 'guru_whatsapp_group_pages_version', 0 ) >= $version ) {
		return;
	}

	$hub_slug = guru_whatsapp_groups_hub_slug();
	$hub_id   = (int) get_option( 'guru_whatsapp_groups_hub_id', 0 );
	$hub      = $hub_id ? get_post( $hub_id ) : null;

	if ( ! $hub || 'page' !== $hub->post_type ) {
		$hub = get_page_by_path( $hub_slug, OBJECT, 'page' );
	}

	if ( ! $hub ) {
		$hub = get_page_by_path( 'grupo-whatsapp', OBJECT, 'page' );
	}

	if ( ! $hub ) {
		$hub_id = wp_insert_post(
			array(
				'post_title'   => __( 'Grupos WhatsApp por Nicho', 'guru-do-desconto' ),
				'post_name'    => $hub_slug,
				'post_content' => '',
				'post_status'  => 'publish',
				'post_type'    => 'page',
			)
		);
	} else {
		$hub_id = (int) $hub->ID;
		wp_update_post(
			array(
				'ID'          => $hub_id,
				'post_name'   => $hub_slug,
				'post_status' => 'publish',
			)
		);
	}

	if ( ! $hub_id || is_wp_error( $hub_id ) ) {
		return;
	}

	update_option( 'guru_whatsapp_groups_hub_id', $hub_id );
	update_post_meta( $hub_id, '_wp_page_template', 'page-grupos-whatsapp-hub.php' );
	update_post_meta(
		$hub_id,
		'_guru_meta_description',
		__( '6 grupos de achadinhos no WhatsApp: Mega Achadinhos, Shô, Casa e Decoração, Moda e Beleza, Maternidade e Tecnologia. Grátis!', 'guru-do-desconto' )
	);

	$page_ids = array();
	$active_slugs = array();

	foreach ( guru_whatsapp_groups() as $group ) {
		$active_slugs[] = $group['slug'];
		$child_path     = $hub_slug . '/' . $group['slug'];
		$existing       = get_page_by_path( $child_path, OBJECT, 'page' );

		// Renomeações de slug (ex.: mulher → moda-beleza).
		$slug_aliases = guru_whatsapp_group_slug_renames();
		if ( ! $existing && ! empty( $slug_aliases[ $group['slug'] ] ) ) {
			$old_slug = $slug_aliases[ $group['slug'] ];
			$old_path = $hub_slug . '/' . $old_slug;
			$existing = get_page_by_path( $old_path, OBJECT, 'page' );

			if ( ! $existing ) {
				$old_ids = (array) get_option( 'guru_whatsapp_group_page_ids', array() );
				if ( ! empty( $old_ids[ $old_slug ] ) ) {
					$existing = get_post( (int) $old_ids[ $old_slug ] );
				}
			}
		}

		$title   = sprintf(
			/* translators: %s: group name */
			__( '%s no WhatsApp — Grupo Grátis de Promoções', 'guru-do-desconto' ),
			$group['name']
		);
		$content = '<p>' . esc_html( $group['description'] ) . '</p>';

		if ( $existing ) {
			$page_id = (int) $existing->ID;
			wp_update_post(
				array(
					'ID'           => $page_id,
					'post_title'   => $title,
					'post_name'    => $group['slug'],
					'post_content' => $content,
					'post_parent'  => $hub_id,
					'post_status'  => 'publish',
				)
			);
		} else {
			$page_id = wp_insert_post(
				array(
					'post_title'   => $title,
					'post_name'    => $group['slug'],
					'post_content' => $content,
					'post_parent'  => $hub_id,
					'post_status'  => 'publish',
					'post_type'    => 'page',
				)
			);
		}

		if ( ! $page_id || is_wp_error( $page_id ) ) {
			continue;
		}

		update_post_meta( $page_id, '_wp_page_template', 'page-grupo-whatsapp-nicho.php' );
		update_post_meta( $page_id, '_guru_whatsapp_group_slug', $group['slug'] );
		update_post_meta( $page_id, '_guru_meta_description', guru_whatsapp_group_meta_description( $group ) );

		$page_ids[ $group['slug'] ] = $page_id;
	}

	// Despublica landings de grupos removidos (Até R$50 / Homem).
	$old_ids = (array) get_option( 'guru_whatsapp_group_page_ids', array() );
	foreach ( $old_ids as $old_slug => $old_page_id ) {
		if ( in_array( $old_slug, $active_slugs, true ) || ! $old_page_id ) {
			continue;
		}
		wp_update_post(
			array(
				'ID'          => (int) $old_page_id,
				'post_status' => 'trash',
			)
		);
	}

	update_option( 'guru_whatsapp_group_page_ids', $page_ids );
	update_option( 'guru_whatsapp_group_pages_version', $version );
	flush_rewrite_rules( false );
}
add_action( 'init', 'guru_ensure_whatsapp_group_pages', 6 );
add_action( 'after_switch_theme', 'guru_ensure_whatsapp_group_pages' );
