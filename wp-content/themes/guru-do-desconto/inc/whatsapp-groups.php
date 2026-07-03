<?php
/**
 * Grupos de promoções WhatsApp por nicho.
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lista de grupos WhatsApp (slug, link, imagem, SEO).
 *
 * @return array<int, array<string, string>>
 */
function guru_whatsapp_groups() {
	$base = GURU_THEME_URI . '/assets/images/grupos/';

	return array(
		array(
			'slug'             => 'geral',
			'name'             => __( 'Promoções Gerais', 'guru-do-desconto' ),
			'headline'         => __( 'Receba achadinhos e promoções todos os dias no WhatsApp', 'guru-do-desconto' ),
			'promise'          => __( 'Ofertas do Mercado Livre, Shopee e Amazon selecionadas para você — grupo 100% grátis.', 'guru-do-desconto' ),
			'tagline'          => __( 'Todas as ofertas do dia', 'guru-do-desconto' ),
			'description'      => __( 'O grupo principal com promoções variadas do Mercado Livre, Shopee e Amazon — ideal para quem quer ver tudo.', 'guru-do-desconto' ),
			'social_proof'     => __( 'Comunidade gratuita para quem ama economizar — promoções selecionadas todos os dias.', 'guru-do-desconto' ),
			'urgency'          => __( 'Algumas promoções duram poucas horas. Entre agora para não perder os achadinhos do dia.', 'guru-do-desconto' ),
			'cta_primary'      => __( 'Entrar no Grupo Grátis', 'guru-do-desconto' ),
			'cta_sticky'       => __( 'Entrar no Grupo Grátis', 'guru-do-desconto' ),
			'cta_card'         => __( 'Receber Ofertas no WhatsApp', 'guru-do-desconto' ),
			'url'              => 'https://chat.whatsapp.com/I5Ln1bvpIP89FpaxRO4VJG',
			'image'            => $base . 'grupo-geral.png',
			'keywords'         => 'grupo promoções whatsapp, cupons mercado livre',
			'featured'         => true,
			'meta_description' => 'Grupo Geral de promoções no WhatsApp — ofertas diárias do Mercado Livre, Shopee e Amazon. 100% grátis. Entre agora!',
			'benefits'         => array(
				__( 'Todas as categorias em um só lugar', 'guru-do-desconto' ),
				__( 'Ofertas relâmpago do dia', 'guru-do-desconto' ),
				__( 'ML, Shopee e Amazon', 'guru-do-desconto' ),
				__( 'Ideal para quem quer ver tudo', 'guru-do-desconto' ),
			),
		),
		array(
			'slug'             => 'casa',
			'name'             => __( 'Casa & Decoração', 'guru-do-desconto' ),
			'headline'         => __( 'Promoções de eletro, móveis e casa todos os dias no WhatsApp', 'guru-do-desconto' ),
			'promise'          => __( 'Air fryer, geladeira, panelas e utilidades com o melhor preço — entre grátis.', 'guru-do-desconto' ),
			'tagline'          => __( 'Eletro, móveis e utilidades', 'guru-do-desconto' ),
			'description'      => __( 'Promoções de air fryer, geladeira, panelas, organização e tudo para sua casa.', 'guru-do-desconto' ),
			'social_proof'     => __( 'Grupo gratuito para quem quer economizar em casa e eletrodomésticos.', 'guru-do-desconto' ),
			'urgency'          => __( 'Ofertas de eletro podem acabar a qualquer momento — entre para receber alertas.', 'guru-do-desconto' ),
			'cta_primary'      => __( 'Quero Ofertas de Casa', 'guru-do-desconto' ),
			'cta_sticky'       => __( 'Entrar no Grupo Grátis', 'guru-do-desconto' ),
			'cta_card'         => __( 'Receber Ofertas no WhatsApp', 'guru-do-desconto' ),
			'url'              => 'https://chat.whatsapp.com/EcyErDlzw9K75xi2v6w68f',
			'image'            => $base . 'grupo-casa.png',
			'keywords'         => 'promoções casa whatsapp, eletrodomésticos baratos',
			'meta_description' => 'Grupo de promoções Casa & Decoração no WhatsApp — eletrodomésticos, móveis e utilidades com desconto. Grátis!',
			'benefits'         => array(
				__( 'Air fryer, geladeira e eletro', 'guru-do-desconto' ),
				__( 'Móveis e organização', 'guru-do-desconto' ),
				__( 'Ofertas Mercado Livre e Shopee', 'guru-do-desconto' ),
				__( 'Alertas só do seu nicho', 'guru-do-desconto' ),
			),
		),
		array(
			'slug'             => 'mulher',
			'name'             => __( 'Mulher & Beleza', 'guru-do-desconto' ),
			'headline'         => __( 'Maquiagem, moda e skincare com desconto no seu WhatsApp', 'guru-do-desconto' ),
			'promise'          => __( 'Ofertas de beleza, perfumes e moda feminina selecionadas todo dia — 100% grátis.', 'guru-do-desconto' ),
			'tagline'          => __( 'Moda, skincare e autocuidado', 'guru-do-desconto' ),
			'description'      => __( 'Ofertas de maquiagem, perfumes, roupas, acessórios e produtos de beleza com desconto.', 'guru-do-desconto' ),
			'social_proof'     => __( 'Comunidade gratuita para quem ama economizar em beleza e moda.', 'guru-do-desconto' ),
			'urgency'          => __( 'Promoções de beleza esgotam rápido — entre para receber antes que acabem.', 'guru-do-desconto' ),
			'cta_primary'      => __( 'Participar Gratuitamente', 'guru-do-desconto' ),
			'cta_sticky'       => __( 'Entrar no Grupo Grátis', 'guru-do-desconto' ),
			'cta_card'         => __( 'Receber Ofertas no WhatsApp', 'guru-do-desconto' ),
			'url'              => 'https://chat.whatsapp.com/LyGuB0HbtpJ1heJhMj0AjR',
			'image'            => $base . 'grupo-mulher.png',
			'keywords'         => 'promoções mulher whatsapp, beleza shopee',
			'meta_description' => 'Grupo Mulher & Beleza no WhatsApp — maquiagem, moda, skincare e perfumes em promoção. Entre grátis!',
			'benefits'         => array(
				__( 'Maquiagem e skincare', 'guru-do-desconto' ),
				__( 'Moda e acessórios', 'guru-do-desconto' ),
				__( 'Perfumes com desconto', 'guru-do-desconto' ),
				__( 'Ofertas curadas para você', 'guru-do-desconto' ),
			),
		),
		array(
			'slug'             => 'kids',
			'name'             => __( 'Kids & Bebê', 'guru-do-desconto' ),
			'headline'         => __( 'Mamães e gestantes: achadinhos de bebê e criança no WhatsApp', 'guru-do-desconto' ),
			'promise'          => __( 'Promoções de fraldas, roupinhas, brinquedos e itens infantis todos os dias no WhatsApp.', 'guru-do-desconto' ),
			'tagline'          => __( 'Brinquedos, fraldas e escola', 'guru-do-desconto' ),
			'description'      => __( 'Economize em fraldas, roupinhas, brinquedos e itens de bebê. Ofertas infantis antes que acabem.', 'guru-do-desconto' ),
			'social_proof'     => __( 'Grupo gratuito feito para quem quer economizar em itens infantis e maternidade.', 'guru-do-desconto' ),
			'urgency'          => __( 'Ofertas de fraldas e brinquedos podem acabar a qualquer momento — entre hoje.', 'guru-do-desconto' ),
			'cta_primary'      => __( 'Receber Ofertas no WhatsApp', 'guru-do-desconto' ),
			'cta_sticky'       => __( 'Entrar no Grupo Grátis', 'guru-do-desconto' ),
			'cta_card'         => __( 'Quero Entrar no Grupo', 'guru-do-desconto' ),
			'url'              => 'https://chat.whatsapp.com/EteqsYCIS7449981BGYhdw',
			'image'            => $base . 'grupo-kids.png',
			'keywords'         => 'promoções infantil whatsapp, brinquedos baratos',
			'meta_description' => 'Grupo Kids & Bebê no WhatsApp — brinquedos, fraldas, roupas infantis e escola com desconto. Grátis!',
			'benefits'         => array(
				__( 'Brinquedos e games infantis', 'guru-do-desconto' ),
				__( 'Fraldas e itens de bebê', 'guru-do-desconto' ),
				__( 'Material escolar', 'guru-do-desconto' ),
				__( 'Economia para a família', 'guru-do-desconto' ),
			),
		),
		array(
			'slug'             => 'tech-games',
			'name'             => __( 'Tech & Games', 'guru-do-desconto' ),
			'headline'         => __( 'PS5, notebook e celular barato? Receba no WhatsApp', 'guru-do-desconto' ),
			'promise'          => __( 'Alertas de tech, games e hardware com melhor preço — antes que acabem.', 'guru-do-desconto' ),
			'tagline'          => __( 'Eletrônicos, games e PC', 'guru-do-desconto' ),
			'description'      => __( 'Alertas de PS5, notebooks, celulares, periféricos, jogos e hardware com melhor preço.', 'guru-do-desconto' ),
			'social_proof'     => __( 'Comunidade gratuita para gamers e amantes de tecnologia.', 'guru-do-desconto' ),
			'urgency'          => __( 'Promoções de tech duram poucas horas — entre para não perder o preço baixo.', 'guru-do-desconto' ),
			'cta_primary'      => __( 'Ver Achadinhos de Hoje', 'guru-do-desconto' ),
			'cta_sticky'       => __( 'Entrar no Grupo Grátis', 'guru-do-desconto' ),
			'cta_card'         => __( 'Receber Ofertas no WhatsApp', 'guru-do-desconto' ),
			'url'              => 'https://chat.whatsapp.com/D6PPWCGnz4n1YIP4LepI5e',
			'image'            => $base . 'grupo-tech-games.png',
			'keywords'         => 'promoções games whatsapp, tech mercado livre',
			'meta_description' => 'Grupo Tech & Games no WhatsApp — PS5, notebook, celular e periféricos com o melhor preço. Entre grátis!',
			'benefits'         => array(
				__( 'PS5, Xbox e jogos', 'guru-do-desconto' ),
				__( 'Notebooks e celulares', 'guru-do-desconto' ),
				__( 'Periféricos e hardware', 'guru-do-desconto' ),
				__( 'Alertas de preço baixo', 'guru-do-desconto' ),
			),
		),
		array(
			'slug'             => 'ate-50',
			'name'             => __( 'Até R$ 50', 'guru-do-desconto' ),
			'headline'         => __( 'Achadinhos abaixo de R$ 50 todos os dias no WhatsApp', 'guru-do-desconto' ),
			'promise'          => __( 'Ofertas relâmpago da Shopee e Mercado Livre — presentes e utilidades baratas.', 'guru-do-desconto' ),
			'tagline'          => __( 'Achadinhos de bolso', 'guru-do-desconto' ),
			'description'      => __( 'Ofertas abaixo de R$ 50 — ideal para compras rápidas, presentes e utilidades do dia a dia.', 'guru-do-desconto' ),
			'social_proof'     => __( 'Grupo gratuito para quem ama achadinho sem pesar no bolso.', 'guru-do-desconto' ),
			'urgency'          => __( 'Achadinhos baratos esgotam rápido — entre para receber os do dia.', 'guru-do-desconto' ),
			'cta_primary'      => __( 'Ver Achadinhos de Hoje', 'guru-do-desconto' ),
			'cta_sticky'       => __( 'Entrar no Grupo Grátis', 'guru-do-desconto' ),
			'cta_card'         => __( 'Quero Entrar no Grupo', 'guru-do-desconto' ),
			'url'              => 'https://chat.whatsapp.com/LBhG35evJyE8m6TTRJqtbj',
			'image'            => $base . 'grupo-ate-50.png',
			'keywords'         => 'promoções baratas whatsapp, achadinhos shopee',
			'meta_description' => 'Grupo Até R$ 50 no WhatsApp — achadinhos baratos, presentes e utilidades abaixo de R$ 50. Grátis!',
			'benefits'         => array(
				__( 'Produtos abaixo de R$ 50', 'guru-do-desconto' ),
				__( 'Achadinhos da Shopee', 'guru-do-desconto' ),
				__( 'Presentes e utilidades', 'guru-do-desconto' ),
				__( 'Compras rápidas sem pesar no bolso', 'guru-do-desconto' ),
			),
		),
		array(
			'slug'             => 'homem',
			'name'             => __( 'Homem & Estilo', 'guru-do-desconto' ),
			'headline'         => __( 'Moda masculina, tênis e grooming em promoção no WhatsApp', 'guru-do-desconto' ),
			'promise'          => __( 'Camisetas, tênis, relógios e barbearia com desconto — grupo 100% grátis.', 'guru-do-desconto' ),
			'tagline'          => __( 'Moda masculina e grooming', 'guru-do-desconto' ),
			'description'      => __( 'Camisetas, tênis, relógios, barbearia e acessórios masculinos em promoção.', 'guru-do-desconto' ),
			'social_proof'     => __( 'Comunidade gratuita para homens que querem estilo com preço justo.', 'guru-do-desconto' ),
			'urgency'          => __( 'Promoções de tênis e moda acabam rápido — entre para não perder.', 'guru-do-desconto' ),
			'cta_primary'      => __( 'Participar Gratuitamente', 'guru-do-desconto' ),
			'cta_sticky'       => __( 'Entrar no Grupo Grátis', 'guru-do-desconto' ),
			'cta_card'         => __( 'Receber Ofertas no WhatsApp', 'guru-do-desconto' ),
			'url'              => 'https://chat.whatsapp.com/L6Z7ZUNkE8oILJBGAZRGYp',
			'image'            => $base . 'grupo-homem.png',
			'keywords'         => 'promoções masculinas whatsapp, moda homem',
			'meta_description' => 'Grupo Homem & Estilo no WhatsApp — moda masculina, tênis, relógios e grooming com desconto. Grátis!',
			'benefits'         => array(
				__( 'Camisetas e tênis', 'guru-do-desconto' ),
				__( 'Relógios e acessórios', 'guru-do-desconto' ),
				__( 'Barbearia e grooming', 'guru-do-desconto' ),
				__( 'Estilo com preço justo', 'guru-do-desconto' ),
			),
		),
	);
}

/**
 * Grupo por slug.
 */
function guru_get_whatsapp_group( $slug ) {
	foreach ( guru_whatsapp_groups() as $group ) {
		if ( $group['slug'] === $slug ) {
			return $group;
		}
	}
	return null;
}

/**
 * URL do grupo com UTMs.
 */
function guru_whatsapp_group_url( $group, $placement = 'card' ) {
	$url = is_array( $group ) ? ( $group['url'] ?? '' ) : '';
	if ( ! $url ) {
		return guru_whatsapp_tracked_url( $placement );
	}

	$slug = is_array( $group ) ? ( $group['slug'] ?? 'grupo' ) : 'grupo';
	return guru_append_utm_params( $url, 'whatsapp_' . $placement . '_' . $slug );
}

/**
 * Atributos HTML do link de um grupo.
 */
function guru_whatsapp_group_link_attrs( $group, $placement = 'card' ) {
	$url  = guru_whatsapp_group_url( $group, $placement );
	$slug = is_array( $group ) ? ( $group['slug'] ?? 'grupo' ) : 'grupo';

	return sprintf(
		'href="%s" target="_blank" rel="noopener" data-guru-track="whatsapp" data-guru-utm-content="%s" data-guru-group="%s"',
		esc_url( $url ),
		esc_attr( 'whatsapp_' . $placement . '_' . $slug ),
		esc_attr( $slug )
	);
}

/**
 * URL da landing page do grupo.
 */
function guru_whatsapp_group_page_link( $group ) {
	return function_exists( 'guru_whatsapp_group_landing_url' )
		? guru_whatsapp_group_landing_url( $group )
		: home_url( '/#grupo-' . ( is_array( $group ) ? ( $group['slug'] ?? '' ) : $group ) );
}
