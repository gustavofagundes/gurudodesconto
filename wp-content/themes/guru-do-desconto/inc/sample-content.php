<?php
/**
 * Sample content on theme activation
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Pretty permalinks são obrigatórios para REST API (/wp-json/) e Google Site Kit.
 */
function guru_setup_permalinks() {
	if ( get_option( 'permalink_structure' ) ) {
		return;
	}
	update_option( 'permalink_structure', '/%postname%/' );
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'guru_setup_permalinks' );

/**
 * Create default pages, menus and sample reviews.
 */
function guru_create_sample_content() {
	if ( get_option( 'guru_sample_content_created' ) ) {
		return;
	}

	// Homepage.
	$home_id = wp_insert_post( array(
		'post_title'   => 'Início',
		'post_status'  => 'publish',
		'post_type'    => 'page',
		'post_content' => '',
	) );

	// Reviews archive page content.
	wp_insert_post( array(
		'post_title'   => 'Sobre',
		'post_status'  => 'publish',
		'post_type'    => 'page',
		'post_content' => '<p>O <strong>Guru do Desconto</strong> é seu guia para economizar com inteligência. Analisamos produtos, comparamos preços e encontramos as melhores promoções no Mercado Livre, Shopee e Amazon.</p><p>Entre no nosso grupo do WhatsApp para receber ofertas em tempo real!</p>',
	) );

	if ( $home_id && ! is_wp_error( $home_id ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_id );
	}

	// Sample reviews.
	$samples = array(
		array(
			'title'    => 'Fone Bluetooth JBL Tune 510BT — Review Completo',
			'excerpt'  => 'Análise honesta do fone JBL Tune 510BT: som, conforto, bateria e se vale a pena comprar na promoção.',
			'content'  => '<h2>Visão Geral</h2><p>O JBL Tune 510BT é um dos fones Bluetooth mais vendidos do Brasil. Testamos por 2 semanas para trazer um review completo.</p><h2>Qualidade do Som</h2><p>Som equilibrado com graves presentes, ideal para uso diário e chamadas.</p><h2>Bateria</h2><p>Até 40 horas de reprodução — excelente para quem usa o dia todo.</p><h2>Vale a Pena?</h2><p>Sim! Especialmente com desconto. Um dos melhores custo-benefício da categoria.</p>',
			'price'    => '199.90',
			'old'      => '299.90',
			'rating'   => '4.5',
			'affiliate'=> 'https://www.mercadolivre.com.br/',
		),
		array(
			'title'    => 'Smartwatch Xiaomi Redmi Watch 3 — Melhor Custo-Benefício 2025',
			'excerpt'  => 'Review do Redmi Watch 3: monitoramento de saúde, GPS e autonomia. Comparativo com concorrentes.',
			'content'  => '<h2>Design e Tela</h2><p>Tela AMOLED de 1.75" com boa visibilidade ao sol.</p><h2>Funcionalidades</h2><p>GPS integrado, SpO2, monitoramento de sono e mais de 100 modos esportivos.</p><h2>Autonomia</h2><p>Até 12 dias de bateria no uso normal.</p><h2>Veredito do Guru</h2><p>Excelente opção na faixa de preço. Recomendado para quem quer smartwatch completo sem gastar muito.</p>',
			'price'    => '249.00',
			'old'      => '399.00',
			'rating'   => '4.7',
			'affiliate'=> 'https://shopee.com.br/',
		),
		array(
			'title'    => 'Echo Dot 5ª Geração — Review e Vale a Pena?',
			'excerpt'  => 'Testamos a Alexa Echo Dot 5: som melhorado, design compacto e integração com casa inteligente.',
			'content'  => '<h2>O que mudou</h2><p>A 5ª geração traz som mais potente e sensor de temperatura.</p><h2>Alexa no dia a dia</h2><p>Controle de luzes, músicas, lembretes e muito mais com comandos de voz.</p><h2>Preço e Promoções</h2><p>Fique de olho nas promoções da Amazon — frequentemente aparece com desconto significativo.</p>',
			'price'    => '349.00',
			'old'      => '449.00',
			'rating'   => '4.6',
			'affiliate'=> 'https://www.amazon.com.br/',
		),
	);

	foreach ( $samples as $sample ) {
		$post_id = wp_insert_post( array(
			'post_title'   => $sample['title'],
			'post_excerpt' => $sample['excerpt'],
			'post_content' => $sample['content'],
			'post_status'  => 'publish',
			'post_type'    => 'review',
		) );

		if ( $post_id && ! is_wp_error( $post_id ) ) {
			update_post_meta( $post_id, '_guru_affiliate_link', $sample['affiliate'] );
			update_post_meta( $post_id, '_guru_price', $sample['price'] );
			update_post_meta( $post_id, '_guru_price_old', $sample['old'] );
			update_post_meta( $post_id, '_guru_rating', $sample['rating'] );
			update_post_meta( $post_id, '_guru_meta_description', $sample['excerpt'] );
		}
	}

	update_option( 'guru_sample_content_created', true );
}
add_action( 'after_switch_theme', 'guru_create_sample_content' );
