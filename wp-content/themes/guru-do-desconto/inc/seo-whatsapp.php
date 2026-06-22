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
	return 'Grupo de promoções no WhatsApp do Guru do Desconto! Ofertas do Mercado Livre, Shopee e Amazon, cupons e descontos em tempo real. Entre grátis.';
}

/**
 * Título SEO da página inicial.
 */
function guru_front_page_title( $parts ) {
	if ( is_front_page() ) {
		$parts['title'] = __( 'Grupo de Promoções no WhatsApp', 'guru-do-desconto' );
		$parts['tagline'] = __( 'Mercado Livre, Shopee e Amazon', 'guru-do-desconto' );
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
			'answer'   => sprintf(
				/* translators: %s: WhatsApp group URL */
				__( 'Clique no botão "Entrar no Grupo de Promoções" nesta página ou acesse diretamente: %s. O link abre o WhatsApp e você confirma a entrada no grupo.', 'guru-do-desconto' ),
				$whatsapp
			),
		),
		array(
			'question' => __( 'O grupo de promoções no WhatsApp é gratuito?', 'guru-do-desconto' ),
			'answer'   => __( 'Sim! A participação é 100% gratuita. Você recebe alertas de descontos sem pagar nada — nossa missão é economia e alegria.', 'guru-do-desconto' ),
		),
		array(
			'question' => __( 'Quais tipos de promoções são enviadas no grupo?', 'guru-do-desconto' ),
			'answer'   => __( 'Enviamos ofertas de eletrônicos, casa, moda, beleza e muito mais nas lojas Mercado Livre, Shopee e Amazon. Incluímos cupons, frete grátis e promoções por tempo limitado.', 'guru-do-desconto' ),
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

	$whatsapp = guru_whatsapp_link();
	$schema   = array(
		'@context' => 'https://schema.org',
		'@graph'   => array(
			array(
				'@type'       => 'WebPage',
				'@id'         => home_url( '/#grupo-whatsapp' ),
				'url'         => home_url( '/' ),
				'name'        => __( 'Grupo de Promoções no WhatsApp — Guru do Desconto', 'guru-do-desconto' ),
				'description' => guru_default_seo_description(),
				'inLanguage'  => 'pt-BR',
				'isPartOf'    => array( '@id' => home_url( '/#website' ) ),
			),
			array(
				'@type'              => 'CommunicationChannel',
				'name'               => __( 'Grupo de Promoções no WhatsApp', 'guru-do-desconto' ),
				'description'        => __( 'Canal gratuito de ofertas e cupons do Mercado Livre, Shopee e Amazon.', 'guru-do-desconto' ),
				'url'                => $whatsapp,
				'availableLanguage'  => 'pt-BR',
				'serviceType'        => 'WhatsApp Group',
			),
		),
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'guru_schema_home_whatsapp', 12 );
