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
 * @return array<int, array<string, mixed>>
 */
function guru_whatsapp_groups() {
	$base = GURU_THEME_URI . '/assets/images/grupos/';

	return array(
		array(
			'slug'             => 'geral',
			'name'             => __( 'Mega Achadinhos', 'guru-do-desconto' ),
			'hook'             => __( 'Os melhores achados da internet em um só lugar — de graça no WhatsApp.', 'guru-do-desconto' ),
			'headline'         => __( 'Amazon, Mercado Livre, Shopee, Magalu e AliExpress. Promoções reais, cupons e ofertas relâmpago todo dia.', 'guru-do-desconto' ),
			'promise'          => __( 'Economize todos os dias sem perder tempo procurando — grupo 100% grátis.', 'guru-do-desconto' ),
			'tagline'          => __( 'Todos os achadinhos do dia', 'guru-do-desconto' ),
			'description'      => __( 'Grupo Mega Achadinhos no WhatsApp: os melhores achados da Amazon, Mercado Livre, Shopee, Magalu e AliExpress. Promoções reais, cupons de desconto, ofertas relâmpago e produtos bem avaliados — 100% grátis.', 'guru-do-desconto' ),
			'social_proof'     => __( 'Quem está no Mega Achadinhos recebe promoções selecionadas antes de esgotar — sem spam.', 'guru-do-desconto' ),
			'urgency'          => __( 'Oferta relâmpago acaba em minutos. Quem está no grupo pega primeiro.', 'guru-do-desconto' ),
			'cta_primary'      => __( 'Entrar no Mega Achadinhos', 'guru-do-desconto' ),
			'cta_sticky'       => __( 'Participar do Grupo Agora', 'guru-do-desconto' ),
			'cta_card'         => __( 'Entrar Grátis', 'guru-do-desconto' ),
			'teaser_items'     => array(
				__( 'Cupom Amazon e Mercado Livre', 'guru-do-desconto' ),
				__( 'Oferta relâmpago Shopee e Magalu', 'guru-do-desconto' ),
				__( 'Achadinhos AliExpress com frete', 'guru-do-desconto' ),
			),
			'url'              => 'https://chat.whatsapp.com/I5Ln1bvpIP89FpaxRO4VJG',
			'image'            => $base . 'grupo-geral.png',
			'keywords'         => 'mega achadinhos whatsapp, grupo promoções amazon mercado livre shopee, cupons desconto',
			'featured'         => true,
			'meta_description' => 'Mega Achadinhos no WhatsApp — promoções da Amazon, ML, Shopee, Magalu e AliExpress. Grupo grátis. Entre agora!',
			'benefits'         => array(
				__( 'Amazon, ML, Shopee, Magalu e AliExpress', 'guru-do-desconto' ),
				__( 'Promoções reais e cupons', 'guru-do-desconto' ),
				__( 'Ofertas relâmpago do dia', 'guru-do-desconto' ),
				__( 'Produtos testados e bem avaliados', 'guru-do-desconto' ),
			),
		),
		array(
			'slug'             => 'shopee',
			'name'             => __( 'Achadinhos da Shô', 'guru-do-desconto' ),
			'hook'             => __( 'Os melhores achados da Shopee — cupom, frete grátis e oferta relâmpago.', 'guru-do-desconto' ),
			'headline'         => __( 'Produtos virais, cupons e frete grátis selecionados todo dia. Grupo 100% grátis.', 'guru-do-desconto' ),
			'promise'          => __( 'Tudo selecionado para você economizar de verdade na Shopee.', 'guru-do-desconto' ),
			'tagline'          => __( 'Ofertas e cupons Shopee', 'guru-do-desconto' ),
			'description'      => __( 'Grupo Achadinhos da Shô no WhatsApp: os melhores achados da Shopee com ofertas relâmpago, cupons, frete grátis, produtos virais e ótimo custo-benefício. 100% gratuito.', 'guru-do-desconto' ),
			'social_proof'     => __( 'Quem ama Shopee já recebe os achadinhos do dia no grupo — antes de esgotar.', 'guru-do-desconto' ),
			'urgency'          => __( 'Oferta relâmpago Shopee some em minutos. Entre e receba o alerta.', 'guru-do-desconto' ),
			'cta_primary'      => __( 'Entrar nos Achadinhos da Shô', 'guru-do-desconto' ),
			'cta_sticky'       => __( 'Participar do Grupo Agora', 'guru-do-desconto' ),
			'cta_card'         => __( 'Entrar Grátis', 'guru-do-desconto' ),
			'teaser_items'     => array(
				__( 'Cupom Shopee com frete grátis', 'guru-do-desconto' ),
				__( 'Produto viral em promoção', 'guru-do-desconto' ),
				__( 'Oferta relâmpago do dia', 'guru-do-desconto' ),
			),
			'url'              => 'https://chat.whatsapp.com/IqBfSnqeq6WF2MNg3DMAti?mode=gi_t',
			'image'            => $base . 'grupo-shopee.png',
			'keywords'         => 'achadinhos shopee whatsapp, cupom shopee, frete grátis shopee, ofertas relâmpago shopee',
			'meta_description' => 'Achadinhos da Shô no WhatsApp — cupons, frete grátis e ofertas relâmpago da Shopee. Grupo grátis. Entre agora!',
			'benefits'         => array(
				__( 'Ofertas relâmpago Shopee', 'guru-do-desconto' ),
				__( 'Cupons e frete grátis', 'guru-do-desconto' ),
				__( 'Produtos virais', 'guru-do-desconto' ),
				__( 'Ótimo custo-benefício', 'guru-do-desconto' ),
			),
		),
		array(
			'slug'             => 'casa',
			'name'             => __( 'Casa e Decoração', 'guru-do-desconto' ),
			'hook'             => __( 'Transforme sua casa economizando — achadinhos de decoração todo dia.', 'guru-do-desconto' ),
			'headline'         => __( 'Decoração, organização, utilidades, cama, mesa e banho. Grupo grátis no WhatsApp.', 'guru-do-desconto' ),
			'promise'          => __( 'Muito conforto, estilo e economia para o seu lar.', 'guru-do-desconto' ),
			'tagline'          => __( 'Decoração e utilidades', 'guru-do-desconto' ),
			'description'      => __( 'Grupo Achadinhos de Casa e Decoração no WhatsApp: decoração, organização, utilidades domésticas, cama, mesa e banho, iluminação e itens para deixar o lar mais bonito — com desconto. 100% grátis.', 'guru-do-desconto' ),
			'social_proof'     => __( 'Quem reforma ou organiza a casa recebe os melhores achadinhos antes de acabar.', 'guru-do-desconto' ),
			'urgency'          => __( 'Promoção de casa e decoração esgota rápido. Quem está no grupo pega primeiro.', 'guru-do-desconto' ),
			'cta_primary'      => __( 'Quero Achadinhos de Casa', 'guru-do-desconto' ),
			'cta_sticky'       => __( 'Participar do Grupo Agora', 'guru-do-desconto' ),
			'cta_card'         => __( 'Entrar Grátis', 'guru-do-desconto' ),
			'teaser_items'     => array(
				__( 'Decoração e organização em oferta', 'guru-do-desconto' ),
				__( 'Utilidades domésticas com cupom', 'guru-do-desconto' ),
				__( 'Cama, mesa e banho com desconto', 'guru-do-desconto' ),
			),
			'url'              => 'https://chat.whatsapp.com/EcyErDlzw9K75xi2v6w68f',
			'image'            => $base . 'grupo-casa.png',
			'keywords'         => 'achadinhos casa decoração whatsapp, utilidades domésticas promoção, organização casa barata',
			'meta_description' => 'Casa e Decoração no WhatsApp — decoração, utilidades e organização com desconto. Grupo grátis. Entre agora!',
			'benefits'         => array(
				__( 'Decoração e organização', 'guru-do-desconto' ),
				__( 'Utilidades domésticas', 'guru-do-desconto' ),
				__( 'Cama, mesa e banho', 'guru-do-desconto' ),
				__( 'Iluminação e itens para o lar', 'guru-do-desconto' ),
			),
		),
		array(
			'slug'             => 'moda-beleza',
			'name'             => __( 'Moda e Beleza', 'guru-do-desconto' ),
			'hook'             => __( 'Seu estilo gastando menos — moda, maquiagem e perfume em promoção.', 'guru-do-desconto' ),
			'headline'         => __( 'Roupas, calçados, bolsas, maquiagem e perfumes. Grupo 100% grátis no WhatsApp.', 'guru-do-desconto' ),
			'promise'          => __( 'Os melhores achados para renovar o visual sem pesar no bolso.', 'guru-do-desconto' ),
			'tagline'          => __( 'Moda, maquiagem e perfume', 'guru-do-desconto' ),
			'description'      => __( 'Grupo Achadinhos de Moda e Beleza no WhatsApp: roupas femininas e masculinas, calçados, bolsas, maquiagem, perfumes e cuidados pessoais com desconto. 100% gratuito.', 'guru-do-desconto' ),
			'social_proof'     => __( 'Quem ama moda e beleza já recebe os achadinhos do dia — antes de esgotar.', 'guru-do-desconto' ),
			'urgency'          => __( 'Promoção de maquiagem e perfume acaba em horas. Entre e não perca.', 'guru-do-desconto' ),
			'cta_primary'      => __( 'Entrar em Moda e Beleza', 'guru-do-desconto' ),
			'cta_sticky'       => __( 'Participar do Grupo Agora', 'guru-do-desconto' ),
			'cta_card'         => __( 'Entrar Grátis', 'guru-do-desconto' ),
			'teaser_items'     => array(
				__( 'Roupas e calçados em promoção', 'guru-do-desconto' ),
				__( 'Maquiagem e perfume com cupom', 'guru-do-desconto' ),
				__( 'Bolsas e acessórios com desconto', 'guru-do-desconto' ),
			),
			'url'              => 'https://chat.whatsapp.com/LyGuB0HbtpJ1heJhMj0AjR',
			'image'            => $base . 'grupo-mulher.png',
			'keywords'         => 'achadinhos moda beleza whatsapp, maquiagem promoção, perfume barato, roupas desconto',
			'meta_description' => 'Moda e Beleza no WhatsApp — roupas, maquiagem, perfumes e acessórios com desconto. Grupo grátis. Entre agora!',
			'benefits'         => array(
				__( 'Roupas femininas e masculinas', 'guru-do-desconto' ),
				__( 'Calçados, bolsas e acessórios', 'guru-do-desconto' ),
				__( 'Maquiagem e perfumes', 'guru-do-desconto' ),
				__( 'Cuidados pessoais', 'guru-do-desconto' ),
			),
		),
		array(
			'slug'             => 'kids',
			'name'             => __( 'Achadinhos Maternidade', 'guru-do-desconto' ),
			'hook'             => __( 'Tudo para mamães, papais e bebês — com preço especial.', 'guru-do-desconto' ),
			'headline'         => __( 'Mamadeiras, carrinhos, roupinhas, enxoval e brinquedos. Grupo 100% grátis.', 'guru-do-desconto' ),
			'promise'          => __( 'Produtos de qualidade com preços especiais para cuidar de quem mais importa.', 'guru-do-desconto' ),
			'tagline'          => __( 'Bebê, enxoval e maternidade', 'guru-do-desconto' ),
			'description'      => __( 'Grupo Achadinhos de Maternidade no WhatsApp: mamadeiras, carrinhos, brinquedos, roupinhas, enxoval, bolsas maternidade e produtos para o bebê com desconto. Ideal para mamães e papais. 100% grátis.', 'guru-do-desconto' ),
			'social_proof'     => __( 'Mamães e papais do grupo economizam em enxoval, roupinha e itens do bebê todo mês.', 'guru-do-desconto' ),
			'urgency'          => __( 'Promoção de maternidade acaba rápido. Quem está no grupo pega antes.', 'guru-do-desconto' ),
			'cta_primary'      => __( 'Entrar em Maternidade', 'guru-do-desconto' ),
			'cta_sticky'       => __( 'Participar do Grupo Agora', 'guru-do-desconto' ),
			'cta_card'         => __( 'Entrar Grátis', 'guru-do-desconto' ),
			'teaser_items'     => array(
				__( 'Enxoval e roupinhas em oferta', 'guru-do-desconto' ),
				__( 'Carrinho e bolsa maternidade', 'guru-do-desconto' ),
				__( 'Mamadeiras e produtos para o bebê', 'guru-do-desconto' ),
			),
			'url'              => 'https://chat.whatsapp.com/EteqsYCIS7449981BGYhdw',
			'image'            => $base . 'grupo-kids.png',
			'keywords'         => 'achadinhos maternidade whatsapp, enxoval bebê promoção, roupinha bebê barata, grupo mamães',
			'meta_description' => 'Achadinhos Maternidade no WhatsApp — enxoval, roupinhas, carrinhos e itens de bebê com desconto. Grupo grátis!',
			'benefits'         => array(
				__( 'Mamadeiras e produtos para o bebê', 'guru-do-desconto' ),
				__( 'Carrinhos e bolsas maternidade', 'guru-do-desconto' ),
				__( 'Roupinhas e enxoval', 'guru-do-desconto' ),
				__( 'Brinquedos com desconto', 'guru-do-desconto' ),
			),
		),
		array(
			'slug'             => 'tech-games',
			'name'             => __( 'Tecnologia e Games', 'guru-do-desconto' ),
			'hook'             => __( 'O paraíso para quem ama tecnologia — smartphones, notebooks e games.', 'guru-do-desconto' ),
			'headline'         => __( 'Celulares, notebooks, smartwatches, fones, games e PCs. Só custo-benefício. Grupo grátis.', 'guru-do-desconto' ),
			'promise'          => __( 'Apenas promoções com excelente custo-benefício — antes de esgotar.', 'guru-do-desconto' ),
			'tagline'          => __( 'Smartphones, notebooks e games', 'guru-do-desconto' ),
			'description'      => __( 'Grupo Achadinhos de Tecnologia e Games no WhatsApp: smartphones, notebooks, smartwatches, fones, games, acessórios, PCs, monitores e periféricos com excelente custo-benefício. 100% grátis.', 'guru-do-desconto' ),
			'social_proof'     => __( 'Gamers e tech lovers do grupo pegam promoção de celular e notebook antes de acabar.', 'guru-do-desconto' ),
			'urgency'          => __( 'Promoção de tech dura poucas horas. Alerta só para quem entrou no grupo.', 'guru-do-desconto' ),
			'cta_primary'      => __( 'Quero Alertas de Tech', 'guru-do-desconto' ),
			'cta_sticky'       => __( 'Participar do Grupo Agora', 'guru-do-desconto' ),
			'cta_card'         => __( 'Entrar Grátis', 'guru-do-desconto' ),
			'teaser_items'     => array(
				__( 'Smartphone e notebook em oferta', 'guru-do-desconto' ),
				__( 'Fone e smartwatch com cupom', 'guru-do-desconto' ),
				__( 'Games e periféricos com desconto', 'guru-do-desconto' ),
			),
			'url'              => 'https://chat.whatsapp.com/D6PPWCGnz4n1YIP4LepI5e',
			'image'            => $base . 'grupo-tech-games.png',
			'keywords'         => 'achadinhos tecnologia games whatsapp, notebook promoção, smartphone barato, periféricos desconto',
			'meta_description' => 'Tecnologia e Games no WhatsApp — smartphones, notebooks, fones e games com custo-benefício. Grupo grátis. Entre!',
			'benefits'         => array(
				__( 'Smartphones e notebooks', 'guru-do-desconto' ),
				__( 'Smartwatches e fones', 'guru-do-desconto' ),
				__( 'Games e acessórios', 'guru-do-desconto' ),
				__( 'PCs, monitores e periféricos', 'guru-do-desconto' ),
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
