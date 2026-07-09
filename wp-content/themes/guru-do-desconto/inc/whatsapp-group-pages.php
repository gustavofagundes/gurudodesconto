<?php
/**
 * Landing pages por grupo WhatsApp — criação e helpers.
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Slug da página-pai dos grupos.
 */
function guru_whatsapp_groups_hub_slug() {
	return 'grupo-whatsapp';
}

/**
 * URL da landing de um grupo.
 */
function guru_whatsapp_group_landing_url( $group ) {
	$slug = is_array( $group ) ? ( $group['slug'] ?? '' ) : (string) $group;
	if ( ! $slug ) {
		return home_url( '/#grupos-whatsapp' );
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
				__( 'Sim! O Guru do Desconto tem 8 grupos por nicho (%s e mais). Você pode entrar em quantos quiser.', 'guru-do-desconto' ),
				$tag
			),
		),
	);
}

/**
 * Cria ou atualiza páginas /grupo-whatsapp/{slug}/.
 */
function guru_ensure_whatsapp_group_pages() {
	$version = 2;
	if ( (int) get_option( 'guru_whatsapp_group_pages_version', 0 ) >= $version ) {
		return;
	}

	$hub_slug = guru_whatsapp_groups_hub_slug();
	$hub      = get_page_by_path( $hub_slug, OBJECT, 'page' );

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
		if ( 'publish' !== $hub->post_status ) {
			wp_update_post(
				array(
					'ID'          => $hub_id,
					'post_status' => 'publish',
				)
			);
		}
	}

	if ( ! $hub_id || is_wp_error( $hub_id ) ) {
		return;
	}

	update_option( 'guru_whatsapp_groups_hub_id', $hub_id );
	update_post_meta( $hub_id, '_wp_page_template', 'page-grupos-whatsapp-hub.php' );
	update_post_meta(
		$hub_id,
		'_guru_meta_description',
		__( '8 grupos de promoções no WhatsApp por nicho — Shopee, Casa, Mulher, Kids, Tech, Até R$50, Homem e Geral. Grátis!', 'guru-do-desconto' )
	);

	$page_ids = array();

	foreach ( guru_whatsapp_groups() as $group ) {
		$child_path = $hub_slug . '/' . $group['slug'];
		$existing   = get_page_by_path( $child_path, OBJECT, 'page' );

		$title   = sprintf(
			/* translators: %s: group name */
			__( 'Grupo %s no WhatsApp', 'guru-do-desconto' ),
			$group['name']
		);
		$content = '<p>' . esc_html( $group['description'] ) . '</p>';

		if ( $existing ) {
			$page_id = (int) $existing->ID;
			wp_update_post(
				array(
					'ID'            => $page_id,
					'post_title'    => $title,
					'post_content'  => $content,
					'post_parent'   => $hub_id,
					'post_status'   => 'publish',
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

	update_option( 'guru_whatsapp_group_page_ids', $page_ids );
	update_option( 'guru_whatsapp_group_pages_version', $version );
	flush_rewrite_rules( false );
}
add_action( 'init', 'guru_ensure_whatsapp_group_pages', 6 );
add_action( 'after_switch_theme', 'guru_ensure_whatsapp_group_pages' );
