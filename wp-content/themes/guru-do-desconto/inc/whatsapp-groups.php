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
			'slug'        => 'geral',
			'name'        => __( 'Promoções Gerais', 'guru-do-desconto' ),
			'tagline'     => __( 'Todas as ofertas do dia', 'guru-do-desconto' ),
			'description' => __( 'O grupo principal com promoções variadas do Mercado Livre, Shopee e Amazon — ideal para quem quer ver tudo.', 'guru-do-desconto' ),
			'url'         => 'https://chat.whatsapp.com/I5Ln1bvpIP89FpaxRO4VJG',
			'image'       => $base . 'grupo-geral.png',
			'keywords'    => 'grupo promoções whatsapp, cupons mercado livre',
			'featured'    => true,
		),
		array(
			'slug'        => 'casa',
			'name'        => __( 'Casa & Decoração', 'guru-do-desconto' ),
			'tagline'     => __( 'Eletro, móveis e utilidades', 'guru-do-desconto' ),
			'description' => __( 'Promoções de air fryer, geladeira, panelas, organização e tudo para sua casa.', 'guru-do-desconto' ),
			'url'         => 'https://chat.whatsapp.com/EcyErDlzw9K75xi2v6w68f',
			'image'       => $base . 'grupo-casa.png',
			'keywords'    => 'promoções casa whatsapp, eletrodomésticos baratos',
		),
		array(
			'slug'        => 'mulher',
			'name'        => __( 'Mulher & Beleza', 'guru-do-desconto' ),
			'tagline'     => __( 'Moda, skincare e autocuidado', 'guru-do-desconto' ),
			'description' => __( 'Ofertas de maquiagem, perfumes, roupas, acessórios e produtos de beleza com desconto.', 'guru-do-desconto' ),
			'url'         => 'https://chat.whatsapp.com/LyGuB0HbtpJ1heJhMj0AjR',
			'image'       => $base . 'grupo-mulher.png',
			'keywords'    => 'promoções mulher whatsapp, beleza shopee',
		),
		array(
			'slug'        => 'kids',
			'name'        => __( 'Kids & Bebê', 'guru-do-desconto' ),
			'tagline'     => __( 'Brinquedos, fraldas e escola', 'guru-do-desconto' ),
			'description' => __( 'Descontos em brinquedos, roupas infantis, fraldas, leite e itens para bebê e criança.', 'guru-do-desconto' ),
			'url'         => 'https://chat.whatsapp.com/EteqsYCIS7449981BGYhdw',
			'image'       => $base . 'grupo-kids.png',
			'keywords'    => 'promoções infantil whatsapp, brinquedos baratos',
		),
		array(
			'slug'        => 'tech-games',
			'name'        => __( 'Tech & Games', 'guru-do-desconto' ),
			'tagline'     => __( 'Eletrônicos, games e PC', 'guru-do-desconto' ),
			'description' => __( 'Alertas de PS5, notebooks, celulares, periféricos, jogos e hardware com melhor preço.', 'guru-do-desconto' ),
			'url'         => 'https://chat.whatsapp.com/D6PPWCGnz4n1YIP4LepI5e',
			'image'       => $base . 'grupo-tech-games.png',
			'keywords'    => 'promoções games whatsapp, tech mercado livre',
		),
		array(
			'slug'        => 'ate-50',
			'name'        => __( 'Até R$ 50', 'guru-do-desconto' ),
			'tagline'     => __( 'Achadinhos de bolso', 'guru-do-desconto' ),
			'description' => __( 'Ofertas abaixo de R$ 50 — ideal para compras rápidas, presentes e utilidades do dia a dia.', 'guru-do-desconto' ),
			'url'         => 'https://chat.whatsapp.com/LBhG35evJyE8m6TTRJqtbj',
			'image'       => $base . 'grupo-ate-50.png',
			'keywords'    => 'promoções baratas whatsapp, achadinhos shopee',
		),
		array(
			'slug'        => 'homem',
			'name'        => __( 'Homem & Estilo', 'guru-do-desconto' ),
			'tagline'     => __( 'Moda masculina e grooming', 'guru-do-desconto' ),
			'description' => __( 'Camisetas, tênis, relógios, barbearia e acessórios masculinos em promoção.', 'guru-do-desconto' ),
			'url'         => 'https://chat.whatsapp.com/L6Z7ZUNkE8oILJBGAZRGYp',
			'image'       => $base . 'grupo-homem.png',
			'keywords'    => 'promoções masculinas whatsapp, moda homem',
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
