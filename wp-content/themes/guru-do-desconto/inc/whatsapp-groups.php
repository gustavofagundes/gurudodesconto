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
			'hook'             => __( 'E se você soubesse da promoção antes de esgotar — de graça, no WhatsApp?', 'guru-do-desconto' ),
			'headline'         => __( 'Achadinhos selecionados do Mercado Livre, Shopee e Amazon todo dia. Grupo 100% gratuito.', 'guru-do-desconto' ),
			'promise'          => __( 'Ofertas do Mercado Livre, Shopee e Amazon selecionadas para você — grupo 100% grátis.', 'guru-do-desconto' ),
			'tagline'          => __( 'Todas as ofertas do dia', 'guru-do-desconto' ),
			'description'      => __( 'O grupo principal com promoções variadas do Mercado Livre, Shopee e Amazon — ideal para quem quer ver tudo.', 'guru-do-desconto' ),
			'social_proof'     => __( 'Milhares de pessoas já recebem nossos achadinhos — só as melhores oportunidades do dia, sem spam.', 'guru-do-desconto' ),
			'urgency'          => __( 'A oferta de hoje pode acabar em poucas horas. Quem está no grupo recebe primeiro.', 'guru-do-desconto' ),
			'cta_primary'      => __( 'Participar do Grupo Grátis', 'guru-do-desconto' ),
			'cta_sticky'       => __( 'Participar do Grupo Agora', 'guru-do-desconto' ),
			'cta_card'         => __( 'Participar Gratuitamente', 'guru-do-desconto' ),
			'teaser_items'     => array(
				__( 'Cupom Mercado Livre com frete grátis', 'guru-do-desconto' ),
				__( 'Achadinho Shopee abaixo de R$ 30', 'guru-do-desconto' ),
				__( 'Oferta relâmpago Amazon com 50% off', 'guru-do-desconto' ),
			),
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
			'hook'             => __( 'Sua air fryer pode estar com 40% off agora — e você nem ficou sabendo?', 'guru-do-desconto' ),
			'headline'         => __( 'Eletro, móveis e utilidades com o melhor preço. Grupo grátis, só achadinhos de casa.', 'guru-do-desconto' ),
			'promise'          => __( 'Air fryer, geladeira, panelas e utilidades com o melhor preço — entre grátis.', 'guru-do-desconto' ),
			'tagline'          => __( 'Eletro, móveis e utilidades', 'guru-do-desconto' ),
			'description'      => __( 'Promoções de air fryer, geladeira, panelas, organização e tudo para sua casa.', 'guru-do-desconto' ),
			'social_proof'     => __( 'Quem reforma ou muda sabe: achadinho de casa no grupo economiza de verdade.', 'guru-do-desconto' ),
			'urgency'          => __( 'Promoção de eletro não avisa — quem está no grupo recebe o alerta na hora.', 'guru-do-desconto' ),
			'cta_primary'      => __( 'Quero Achadinhos de Casa', 'guru-do-desconto' ),
			'cta_sticky'       => __( 'Participar do Grupo Agora', 'guru-do-desconto' ),
			'cta_card'         => __( 'Participar Gratuitamente', 'guru-do-desconto' ),
			'teaser_items'     => array(
				__( 'Air fryer Philips com cupom', 'guru-do-desconto' ),
				__( 'Jogo de panelas abaixo de R$ 150', 'guru-do-desconto' ),
				__( 'Organizador Shopee por menos de R$ 25', 'guru-do-desconto' ),
			),
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
			'hook'             => __( 'Aquele kit de skincare que você queria… pode estar metade do preço no grupo hoje.', 'guru-do-desconto' ),
			'headline'         => __( 'Maquiagem, moda e beleza com desconto real. Grupo 100% grátis, sem pegadinha.', 'guru-do-desconto' ),
			'promise'          => __( 'Ofertas de beleza, perfumes e moda feminina selecionadas todo dia — 100% grátis.', 'guru-do-desconto' ),
			'tagline'          => __( 'Moda, skincare e autocuidado', 'guru-do-desconto' ),
			'description'      => __( 'Ofertas de maquiagem, perfumes, roupas, acessórios e produtos de beleza com desconto.', 'guru-do-desconto' ),
			'social_proof'     => __( 'Mulheres que amam economizar em beleza já recebem os achadinhos do dia no grupo.', 'guru-do-desconto' ),
			'urgency'          => __( 'Promoção de maquiagem e perfume esgota em horas — alerta só para quem está dentro.', 'guru-do-desconto' ),
			'cta_primary'      => __( 'Participar do Grupo Grátis', 'guru-do-desconto' ),
			'cta_sticky'       => __( 'Participar do Grupo Agora', 'guru-do-desconto' ),
			'cta_card'         => __( 'Participar Gratuitamente', 'guru-do-desconto' ),
			'teaser_items'     => array(
				__( 'Kit skincare com 50% off', 'guru-do-desconto' ),
				__( 'Perfume importado em promoção', 'guru-do-desconto' ),
				__( 'Vestido Shopee abaixo de R$ 40', 'guru-do-desconto' ),
			),
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
			'hook'             => __( 'Fralda boa por menos de R$ 40? Isso aparece no grupo quase todo dia — você sabia?', 'guru-do-desconto' ),
			'headline'         => __( 'Mamães e gestantes: achadinhos de bebê e criança selecionados. Entrada 100% grátis.', 'guru-do-desconto' ),
			'promise'          => __( 'Promoções de fraldas, roupinhas, brinquedos e itens infantis todos os dias no WhatsApp.', 'guru-do-desconto' ),
			'tagline'          => __( 'Brinquedos, fraldas e escola', 'guru-do-desconto' ),
			'description'      => __( 'Economize em fraldas, roupinhas, brinquedos e itens de bebê. Ofertas infantis antes que acabem.', 'guru-do-desconto' ),
			'social_proof'     => __( 'Mamães do grupo economizam centenas por mês em fralda, roupinha e brinquedo.', 'guru-do-desconto' ),
			'urgency'          => __( 'Fralda em promoção acaba em minutos. Quem está no grupo pega antes.', 'guru-do-desconto' ),
			'cta_primary'      => __( 'Participar do Grupo Grátis', 'guru-do-desconto' ),
			'cta_sticky'       => __( 'Participar do Grupo Agora', 'guru-do-desconto' ),
			'cta_card'         => __( 'Quero Entrar no Grupo', 'guru-do-desconto' ),
			'teaser_items'     => array(
				__( 'Fralda Huggies com cupom', 'guru-do-desconto' ),
				__( 'Roupinha de bebê abaixo de R$ 25', 'guru-do-desconto' ),
				__( 'Brinquedo educativo com 40% off', 'guru-do-desconto' ),
			),
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
			'hook'             => __( 'PS5 com desconto real? Notebook gamer barato? Só quem está no grupo fica sabendo.', 'guru-do-desconto' ),
			'headline'         => __( 'Alertas de tech, games e hardware com o melhor preço. Grupo grátis, sem enrolação.', 'guru-do-desconto' ),
			'promise'          => __( 'Alertas de tech, games e hardware com melhor preço — antes que acabem.', 'guru-do-desconto' ),
			'tagline'          => __( 'Eletrônicos, games e PC', 'guru-do-desconto' ),
			'description'      => __( 'Alertas de PS5, notebooks, celulares, periféricos, jogos e hardware com melhor preço.', 'guru-do-desconto' ),
			'social_proof'     => __( 'Gamers e tech lovers do grupo pegam promoção de console e PC antes de esgotar.', 'guru-do-desconto' ),
			'urgency'          => __( 'Promoção de PS5 e notebook dura poucas horas. Alerta só para quem entrou.', 'guru-do-desconto' ),
			'cta_primary'      => __( 'Quero Alertas de Tech', 'guru-do-desconto' ),
			'cta_sticky'       => __( 'Participar do Grupo Agora', 'guru-do-desconto' ),
			'cta_card'         => __( 'Participar Gratuitamente', 'guru-do-desconto' ),
			'teaser_items'     => array(
				__( 'PS5 com cupom Mercado Livre', 'guru-do-desconto' ),
				__( 'Notebook gamer abaixo de R$ 3.000', 'guru-do-desconto' ),
				__( 'Fone JBL com 45% off', 'guru-do-desconto' ),
			),
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
			'hook'             => __( 'Presente bom por menos de R$ 30 existe — o segredo é estar no grupo certo.', 'guru-do-desconto' ),
			'headline'         => __( 'Achadinhos abaixo de R$ 50 todo dia. Grupo 100% grátis, direto no WhatsApp.', 'guru-do-desconto' ),
			'promise'          => __( 'Ofertas relâmpago da Shopee e Mercado Livre — presentes e utilidades baratas.', 'guru-do-desconto' ),
			'tagline'          => __( 'Achadinhos de bolso', 'guru-do-desconto' ),
			'description'      => __( 'Ofertas abaixo de R$ 50 — ideal para compras rápidas, presentes e utilidades do dia a dia.', 'guru-do-desconto' ),
			'social_proof'     => __( 'Quem ama achadinho sem pesar no bolso já está recebendo ofertas abaixo de R$ 50.', 'guru-do-desconto' ),
			'urgency'          => __( 'Achadinho barato esgota rápido — quem está no grupo compra antes.', 'guru-do-desconto' ),
			'cta_primary'      => __( 'Ver Achadinhos de Hoje', 'guru-do-desconto' ),
			'cta_sticky'       => __( 'Participar do Grupo Agora', 'guru-do-desconto' ),
			'cta_card'         => __( 'Participar Gratuitamente', 'guru-do-desconto' ),
			'teaser_items'     => array(
				__( 'Presente criativo abaixo de R$ 25', 'guru-do-desconto' ),
				__( 'Utilidade Shopee por R$ 15', 'guru-do-desconto' ),
				__( 'Kit de canetas com 60% off', 'guru-do-desconto' ),
			),
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
			'hook'             => __( 'Tênis de marca pela metade do preço? Alguém precisa te avisar — e é de graça.', 'guru-do-desconto' ),
			'headline'         => __( 'Moda masculina, tênis e grooming com desconto real. Grupo 100% grátis.', 'guru-do-desconto' ),
			'promise'          => __( 'Camisetas, tênis, relógios e barbearia com desconto — grupo 100% grátis.', 'guru-do-desconto' ),
			'tagline'          => __( 'Moda masculina e grooming', 'guru-do-desconto' ),
			'description'      => __( 'Camisetas, tênis, relógios, barbearia e acessórios masculinos em promoção.', 'guru-do-desconto' ),
			'social_proof'     => __( 'Homens do grupo pegam tênis e relógio em promoção antes de acabar o estoque.', 'guru-do-desconto' ),
			'urgency'          => __( 'Promoção de tênis acaba rápido — alerta só para quem está dentro do grupo.', 'guru-do-desconto' ),
			'cta_primary'      => __( 'Participar do Grupo Grátis', 'guru-do-desconto' ),
			'cta_sticky'       => __( 'Participar do Grupo Agora', 'guru-do-desconto' ),
			'cta_card'         => __( 'Participar Gratuitamente', 'guru-do-desconto' ),
			'teaser_items'     => array(
				__( 'Tênis Nike com cupom', 'guru-do-desconto' ),
				__( 'Kit barbearia abaixo de R$ 40', 'guru-do-desconto' ),
				__( 'Relógio com 50% off na Shopee', 'guru-do-desconto' ),
			),
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
