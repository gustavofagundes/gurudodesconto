<?php
/**
 * Copy de conversão — grupos WhatsApp.
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Página focada em lead (home ou landing de grupo).
 */
function guru_is_conversion_page() {
	if ( is_front_page() ) {
		return true;
	}
	return function_exists( 'guru_is_whatsapp_group_landing_page' ) && guru_is_whatsapp_group_landing_page();
}

/**
 * Headline principal (H1).
 */
function guru_whatsapp_group_headline( $group ) {
	if ( ! empty( $group['headline'] ) ) {
		return $group['headline'];
	}
	return sprintf(
		/* translators: %s: group name */
		__( 'Grupo %s no WhatsApp', 'guru-do-desconto' ),
		$group['name'] ?? ''
	);
}

/**
 * Gancho curioso — chamada de conversão (curiosidade + grátis + curadoria).
 */
function guru_whatsapp_group_hook( $group ) {
	if ( ! empty( $group['hook'] ) ) {
		return $group['hook'];
	}

	return __( 'As melhores ofertas duram pouco — por isso selecionamos só as melhores oportunidades do dia. Grupo 100% grátis, direto no seu WhatsApp.', 'guru-do-desconto' );
}

/**
 * Promessa / subtítulo.
 */
function guru_whatsapp_group_promise( $group ) {
	if ( ! empty( $group['promise'] ) ) {
		return $group['promise'];
	}
	return $group['description'] ?? '';
}

/**
 * Texto do botão CTA.
 *
 * @param string $type primary|sticky|card|bulk
 */
function guru_whatsapp_cta_label( $group, $type = 'primary' ) {
	$key = 'cta_' . $type;
	if ( ! empty( $group[ $key ] ) ) {
		return $group[ $key ];
	}

	$defaults = array(
		'primary' => __( 'Entrar no Grupo Grátis', 'guru-do-desconto' ),
		'sticky'  => __( 'Entrar no Grupo Grátis', 'guru-do-desconto' ),
		'card'    => __( 'Receber Ofertas no WhatsApp', 'guru-do-desconto' ),
		'bulk'    => __( 'Entrar nos Grupos Selecionados', 'guru-do-desconto' ),
	);

	return $defaults[ $type ] ?? $defaults['primary'];
}

/**
 * Prova social.
 */
function guru_whatsapp_group_social_proof( $group ) {
	if ( ! empty( $group['social_proof'] ) ) {
		return $group['social_proof'];
	}
	return __( 'Comunidade gratuita para quem ama economizar — promoções selecionadas todos os dias.', 'guru-do-desconto' );
}

/**
 * Urgência real.
 */
function guru_whatsapp_group_urgency( $group ) {
	if ( ! empty( $group['urgency'] ) ) {
		return $group['urgency'];
	}
	return __( 'Ofertas podem acabar a qualquer momento. Entre no grupo para não perder os achadinhos do dia.', 'guru-do-desconto' );
}

/**
 * Ícone SVG WhatsApp inline.
 */
function guru_whatsapp_icon_svg( $size = 22 ) {
	return '<svg width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>';
}
