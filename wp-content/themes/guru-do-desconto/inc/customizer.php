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
		'default'           => '7 grupos de promoções no WhatsApp: Casa, Mulher, Kids, Tech, Até R$50, Homem e Geral. Ofertas grátis do Mercado Livre, Shopee e Amazon todos os dias.',
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
		'description' => __( 'ID GA4. Marque affiliate_click e whatsapp_click como conversões no GA4. Se usar Site Kit, deixe vazio aqui para evitar duplicar.', 'guru-do-desconto' ),
		'section'     => 'guru_settings',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'guru_google_ads_id', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'guru_google_ads_id', array(
		'label'       => __( 'Google Ads ID (AW-XXXXXXXX)', 'guru-do-desconto' ),
		'description' => __( 'Opcional. Vincule Google Ads ao GA4 no painel do Ads.', 'guru-do-desconto' ),
		'section'     => 'guru_settings',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'guru_meta_pixel_enabled', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
	) );

	$wp_customize->add_control( 'guru_meta_pixel_enabled', array(
		'label'   => __( 'Ativar Meta Pixel', 'guru-do-desconto' ),
		'section' => 'guru_settings',
		'type'    => 'checkbox',
	) );

	$wp_customize->add_setting( 'guru_meta_pixel_id', array(
		'default'           => '',
		'sanitize_callback' => 'guru_sanitize_meta_pixel_id',
	) );

	$wp_customize->add_control( 'guru_meta_pixel_id', array(
		'label'       => __( 'Meta Pixel ID', 'guru-do-desconto' ),
		'description' => __( 'ID numérico de 15–16 dígitos (Events Manager → Fontes de dados → Pixel). Alternativa: GURU_META_PIXEL_ID no .env', 'guru-do-desconto' ),
		'section'     => 'guru_settings',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'guru_meta_pixel_skip_admins', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
	) );

	$wp_customize->add_control( 'guru_meta_pixel_skip_admins', array(
		'label'       => __( 'Não rastrear admins logados', 'guru-do-desconto' ),
		'description' => __( 'Evita poluir conversões quando você navega logado no wp-admin.', 'guru-do-desconto' ),
		'section'     => 'guru_settings',
		'type'        => 'checkbox',
	) );

	$wp_customize->add_setting( 'guru_utm_source', array(
		'default'           => 'gurudodesconto',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'guru_utm_source', array(
		'label'       => __( 'UTM padrão — source', 'guru-do-desconto' ),
		'description' => __( 'Usado em links de afiliado quando o visitante não veio de campanha.', 'guru-do-desconto' ),
		'section'     => 'guru_settings',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'guru_utm_medium', array(
		'default'           => 'review',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'guru_utm_medium', array(
		'label'   => __( 'UTM padrão — medium', 'guru-do-desconto' ),
		'section' => 'guru_settings',
		'type'    => 'text',
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
