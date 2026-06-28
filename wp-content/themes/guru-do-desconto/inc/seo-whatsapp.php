<?php
/**
 * SEO — Grupo de promoções WhatsApp (palavras-chave e schema)
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Descrição SEO padrão com foco em grupo WhatsApp.
 */
function guru_default_seo_description() {
	return 'Grupos de promoções no WhatsApp por nicho: Casa, Mulher, Kids, Tech, Até R$50, Homem e Geral. Ofertas grátis do Mercado Livre, Shopee e Amazon.';
}

/**
 * Título SEO da página inicial.
 */
function guru_front_page_title( $parts ) {
	if ( is_front_page() ) {
		$parts['title'] = __( 'Grupos de Promoções no WhatsApp por Nicho', 'guru-do-desconto' );
		$parts['tagline'] = __( 'Casa, Tech, Kids e mais — ML, Shopee, Amazon', 'guru-do-desconto' );
	}
	return $parts;
}
add_filter( 'document_title_parts', 'guru_front_page_title' );

/**
 * Meta description otimizada na home e página do grupo.
 */
function guru_whatsapp_meta_description( $desc ) {
	if ( is_front_page() || guru_is_whatsapp_landing_page() ) {
		return guru_default_seo_description();
	}
	return $desc;
}

/**
 * Verifica se é a landing page do grupo WhatsApp.
 */
function guru_is_whatsapp_landing_page() {
	$page_id = (int) get_option( 'guru_whatsapp_page_id', 0 );
	return $page_id && is_page( $page_id );
}

/**
 * Perguntas frequentes para conteúdo e schema FAQ.
 */
function guru_whatsapp_faq_items() {
	$whatsapp = guru_whatsapp_link();

	return array(
		array(
			'question' => __( 'O que é o grupo de promoções no WhatsApp do Guru do Desconto?', 'guru-do-desconto' ),
			'answer'   => __( 'É um grupo gratuito no WhatsApp onde compartilhamos diariamente as melhores promoções, cupons e ofertas relâmpago do Mercado Livre, Shopee e Amazon — selecionadas para você economizar de verdade.', 'guru-do-desconto' ),
		),
		array(
			'question' => __( 'Como entrar no grupo de ofertas no WhatsApp?', 'guru-do-desconto' ),
			'answer'   => __( 'Na página inicial, escolha o grupo do seu nicho (Casa, Mulher, Kids, Tech, Até R$ 50, Homem ou Geral) e clique em "Entrar no grupo". O link abre o WhatsApp para confirmar a entrada.', 'guru-do-desconto' ),
		),
		array(
			'question' => __( 'O grupo de promoções no WhatsApp é gratuito?', 'guru-do-desconto' ),
			'answer'   => __( 'Sim! A participação é 100% gratuita. Você recebe alertas de descontos sem pagar nada — nossa missão é economia e alegria.', 'guru-do-desconto' ),
		),
		array(
			'question' => __( 'Quantos grupos de promoções existem?', 'guru-do-desconto' ),
			'answer'   => __( 'São 7 grupos gratuitos no WhatsApp: Geral, Casa, Mulher, Kids, Tech & Games, Até R$ 50 e Homem. Cada um envia ofertas do Mercado Livre, Shopee e Amazon no seu nicho.', 'guru-do-desconto' ),
		),
		array(
			'question' => __( 'Posso entrar em mais de um grupo?', 'guru-do-desconto' ),
			'answer'   => __( 'Sim! Escolha os nichos que você mais compra — por exemplo, Tech para games e Casa para eletrodomésticos. Todos são gratuitos.', 'guru-do-desconto' ),
		),
		array(
			'question' => __( 'Posso sair do grupo de promoções quando quiser?', 'guru-do-desconto' ),
			'answer'   => __( 'Sim. A qualquer momento você pode sair do grupo pelo próprio WhatsApp, sem burocracia.', 'guru-do-desconto' ),
		),
	);
}

/**
 * Cria ou recupera a landing page /grupo-promocoes-whatsapp/.
 */
function guru_ensure_whatsapp_seo_page() {
	$page_id = (int) get_option( 'guru_whatsapp_page_id', 0 );
	if ( $page_id && 'publish' === get_post_status( $page_id ) ) {
		return $page_id;
	}

	// Delega ao mu-plugin se disponível.
	if ( function_exists( 'guru_mu_ensure_whatsapp_page' ) ) {
		return guru_mu_ensure_whatsapp_page();
	}

	$slug     = 'grupo-promocoes-whatsapp';
	$existing = get_page_by_path( $slug, OBJECT, 'page' );

	if ( $existing && 'publish' === $existing->post_status ) {
		update_option( 'guru_whatsapp_page_id', $existing->ID );
		update_post_meta( $existing->ID, '_wp_page_template', 'page-grupo-whatsapp.php' );
		update_post_meta( $existing->ID, '_guru_meta_description', guru_default_seo_description() );
		return $existing->ID;
	}

	// Página existe mas está rascunho/lixeira — republica.
	if ( $existing ) {
		wp_update_post( array(
			'ID'          => $existing->ID,
			'post_status' => 'publish',
		) );
		update_option( 'guru_whatsapp_page_id', $existing->ID );
		update_post_meta( $existing->ID, '_wp_page_template', 'page-grupo-whatsapp.php' );
		flush_rewrite_rules( false );
		return $existing->ID;
	}

	$whatsapp = guru_whatsapp_link();
	$content  = '<p>O <strong>Guru do Desconto</strong> reúne as melhores <strong>promoções no WhatsApp</strong> do Brasil. Nosso <strong>grupo de ofertas no WhatsApp</strong> é gratuito e envia alertas diários do Mercado Livre, Shopee e Amazon.</p>';
	$content .= '<p>Se você busca um <strong>grupo de cupons e descontos no WhatsApp</strong>, encontrou o lugar certo. Economize com curadoria de quem entende de promoção.</p>';
	$content .= '<p><a href="' . esc_url( $whatsapp ) . '" class="btn btn-whatsapp" target="_blank" rel="noopener">' . esc_html__( 'Entrar no Grupo de Promoções no WhatsApp', 'guru-do-desconto' ) . '</a></p>';

	$page_id = wp_insert_post( array(
		'post_title'   => 'Grupo de Promoções no WhatsApp',
		'post_name'    => $slug,
		'post_content' => $content,
		'post_status'  => 'publish',
		'post_type'    => 'page',
	) );

	if ( $page_id && ! is_wp_error( $page_id ) ) {
		update_option( 'guru_whatsapp_page_id', $page_id );
		update_post_meta( $page_id, '_guru_meta_description', guru_default_seo_description() );
		update_post_meta( $page_id, '_wp_page_template', 'page-grupo-whatsapp.php' );
		flush_rewrite_rules( false );
		return $page_id;
	}

	return 0;
}
add_action( 'after_switch_theme', 'guru_ensure_whatsapp_seo_page' );

/**
 * Schema WebPage com author — páginas estáticas e landing WhatsApp.
 */
function guru_schema_webpage() {
	if ( ! is_page() || is_front_page() ) {
		return;
	}

	$schema = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'WebPage',
		'name'        => get_the_title(),
		'url'         => get_permalink(),
		'description' => wp_strip_all_tags( get_the_excerpt() ?: wp_trim_words( get_the_content(), 30 ) ),
		'inLanguage'  => 'pt-BR',
		'author'      => guru_schema_author(),
		'publisher'   => guru_schema_publisher(),
		'datePublished' => get_the_date( 'c' ),
		'dateModified'  => get_the_modified_date( 'c' ),
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'guru_schema_webpage', 10 );

/**
 * Schema WebPage — landing grupo WhatsApp.
 */
function guru_schema_whatsapp_landing() {
	if ( ! guru_is_whatsapp_landing_page() ) {
		return;
	}

	$schema = array(
		'@context'      => 'https://schema.org',
		'@type'         => 'WebPage',
		'name'          => get_the_title(),
		'url'           => get_permalink(),
		'description'   => guru_default_seo_description(),
		'inLanguage'    => 'pt-BR',
		'author'        => guru_schema_author(),
		'publisher'     => guru_schema_publisher(),
		'datePublished' => get_the_date( 'c' ),
		'dateModified'  => get_the_modified_date( 'c' ),
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'guru_schema_whatsapp_landing', 10 );

/**
 * Schema FAQPage na home e landing do WhatsApp.
 */
function guru_schema_whatsapp_faq() {
	if ( ! is_front_page() && ! guru_is_whatsapp_landing_page() ) {
		return;
	}

	$entities = array();
	foreach ( guru_whatsapp_faq_items() as $item ) {
		$entities[] = array(
			'@type'          => 'Question',
			'name'           => $item['question'],
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => wp_strip_all_tags( $item['answer'] ),
			),
		);
	}

	$schema = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $entities,
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'guru_schema_whatsapp_faq', 11 );

/**
 * Enriquece schema da home com palavras-chave do grupo WhatsApp.
 */
function guru_schema_home_whatsapp() {
	if ( ! is_front_page() ) {
		return;
	}

	$channels = array();
	$list     = array();
	$pos      = 1;

	foreach ( guru_whatsapp_groups() as $group ) {
		$channels[] = array(
			'@type'             => 'CommunicationChannel',
			'name'              => $group['name'],
			'description'       => $group['description'],
			'url'               => $group['url'],
			'availableLanguage' => 'pt-BR',
			'serviceType'       => 'WhatsApp Group',
		);
		$list[] = array(
			'@type'    => 'ListItem',
			'position' => $pos++,
			'name'     => $group['name'],
			'url'      => home_url( '/#grupo-' . $group['slug'] ),
		);
	}

	$schema = array(
		'@context' => 'https://schema.org',
		'@graph'   => array_merge(
			array(
				array(
					'@type'       => 'WebPage',
					'@id'         => home_url( '/#grupos-whatsapp' ),
					'url'         => home_url( '/' ),
					'name'        => __( 'Grupos de Promoções no WhatsApp por Nicho — Guru do Desconto', 'guru-do-desconto' ),
					'description' => guru_default_seo_description(),
					'inLanguage'  => 'pt-BR',
					'author'      => guru_schema_author(),
					'publisher'   => guru_schema_publisher(),
					'isPartOf'    => array( '@id' => home_url( '/#website' ) ),
				),
				array(
					'@type'           => 'ItemList',
					'name'            => __( 'Grupos WhatsApp Guru do Desconto', 'guru-do-desconto' ),
					'itemListElement' => $list,
				),
			),
			$channels
		),
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'guru_schema_home_whatsapp', 12 );
