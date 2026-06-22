<?php
/**
 * Theme Customizer settings
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register customizer settings.
 */
function guru_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'guru_settings', array(
		'title'    => __( 'Guru do Desconto', 'guru-do-desconto' ),
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'guru_whatsapp_link', array(
		'default'           => 'https://chat.whatsapp.com/I5Ln1bvpIP89FpaxRO4VJG?mode=gi_t',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'guru_whatsapp_link', array(
		'label'       => __( 'Link do Grupo WhatsApp', 'guru-do-desconto' ),
		'description' => __( 'Cole o link de convite do seu grupo de promoções.', 'guru-do-desconto' ),
		'section'     => 'guru_settings',
		'type'        => 'url',
	) );

	$wp_customize->add_setting( 'guru_whatsapp_message', array(
		'default'           => __( 'Quero entrar no grupo de promoções!', 'guru-do-desconto' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'guru_whatsapp_message', array(
		'label'   => __( 'Texto do botão WhatsApp', 'guru-do-desconto' ),
		'section' => 'guru_settings',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'guru_site_description', array(
		'default'           => 'Grupo de promoções no WhatsApp com ofertas do Mercado Livre, Shopee e Amazon. Cupons, descontos e reviews do Guru do Desconto.',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );

	$wp_customize->add_control( 'guru_site_description', array(
		'label'   => __( 'Descrição SEO do site', 'guru-do-desconto' ),
		'section' => 'guru_settings',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'guru_google_analytics', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'guru_google_analytics', array(
		'label'       => __( 'Google Analytics ID (G-XXXXXXXX)', 'guru-do-desconto' ),
		'description' => __( 'Opcional. Cole o ID de medição do GA4.', 'guru-do-desconto' ),
		'section'     => 'guru_settings',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'guru_google_search_console', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'guru_google_search_console', array(
		'label'       => __( 'Google Search Console — código de verificação', 'guru-do-desconto' ),
		'description' => __( 'Cole apenas o conteúdo do atributo content da meta tag.', 'guru-do-desconto' ),
		'section'     => 'guru_settings',
		'type'        => 'text',
	) );

	// Google AdSense
	$wp_customize->add_setting( 'guru_adsense_enabled', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
	) );

	$wp_customize->add_control( 'guru_adsense_enabled', array(
		'label'   => __( 'Ativar Google AdSense', 'guru-do-desconto' ),
		'section' => 'guru_settings',
		'type'    => 'checkbox',
	) );

	$wp_customize->add_setting( 'guru_adsense_client', array(
		'default'           => 'ca-pub-2824875854264000',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'guru_adsense_client', array(
		'label'       => __( 'AdSense — ID do editor (ca-pub-...)', 'guru-do-desconto' ),
		'description' => __( 'Ex.: ca-pub-2824875854264000', 'guru-do-desconto' ),
		'section'     => 'guru_settings',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'guru_adsense_slot_home_mid', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'guru_adsense_slot_home_mid', array(
		'label'       => __( 'AdSense — Slot da home (opcional)', 'guru-do-desconto' ),
		'description' => __( 'ID numérico do bloco de anúncio na home. Deixe vazio para usar só Auto ads.', 'guru-do-desconto' ),
		'section'     => 'guru_settings',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'guru_adsense_slot_review', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'guru_adsense_slot_review', array(
		'label'       => __( 'AdSense — Slot dos reviews (opcional)', 'guru-do-desconto' ),
		'description' => __( 'ID numérico do bloco em páginas de review.', 'guru-do-desconto' ),
		'section'     => 'guru_settings',
		'type'        => 'text',
	) );
}
add_action( 'customize_register', 'guru_customize_register' );
