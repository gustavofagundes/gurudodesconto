<?php
/**
 * Rastreamento: UTM, GA4, Meta Pixel, conversões.
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Captura UTMs da URL de entrada (ex.: Google Ads) em cookie por 24h.
 */
function guru_capture_utm_params() {
	if ( is_admin() ) {
		return;
	}

	$keys = array( 'utm_source', 'utm_medium', 'utm_campaign', 'utm_content' );

	foreach ( $keys as $key ) {
		if ( empty( $_GET[ $key ] ) ) {
			continue;
		}

		$value = sanitize_text_field( wp_unslash( $_GET[ $key ] ) );
		setcookie(
			'guru_' . $key,
			$value,
			time() + DAY_IN_SECONDS,
			COOKIEPATH,
			COOKIE_DOMAIN,
			is_ssl(),
			true
		);
		$_COOKIE[ 'guru_' . $key ] = $value;
	}
}
add_action( 'init', 'guru_capture_utm_params', 1 );

/**
 * Lê UTM armazenado (cookie da visita).
 */
function guru_get_stored_utm( $key ) {
	if ( ! empty( $_COOKIE[ 'guru_' . $key ] ) ) {
		return sanitize_text_field( wp_unslash( $_COOKIE[ 'guru_' . $key ] ) );
	}
	return '';
}

/**
 * Slug da campanha do review atual.
 */
function guru_review_campaign_slug() {
	if ( is_singular( 'review' ) ) {
		return 'review_' . get_post_field( 'post_name', get_the_ID() );
	}
	return 'site';
}

/**
 * Anexa UTMs a URLs de afiliado / anúncio.
 *
 * @param string $url     URL de destino.
 * @param string $content utm_content (ex.: sidebar, product_1).
 */
function guru_append_utm_params( $url, $content = 'cta' ) {
	$url = trim( (string) $url );
	if ( '' === $url || '#' === $url ) {
		return $url;
	}

	$incoming_source   = guru_get_stored_utm( 'utm_source' );
	$incoming_medium   = guru_get_stored_utm( 'utm_medium' );
	$incoming_campaign = guru_get_stored_utm( 'utm_campaign' );

	$params = array(
		'utm_source'   => $incoming_source ?: get_theme_mod( 'guru_utm_source', 'gurudodesconto' ),
		'utm_medium'   => $incoming_medium ?: get_theme_mod( 'guru_utm_medium', 'review' ),
		'utm_campaign' => $incoming_campaign ?: guru_review_campaign_slug(),
		'utm_content'  => $content,
	);

	return add_query_arg( $params, $url );
}

/**
 * Link WhatsApp com UTMs e atributos de rastreamento.
 *
 * @param string $placement floating, hero, band, faq, nav, etc.
 */
function guru_whatsapp_tracked_url( $placement = 'cta' ) {
	$base = get_theme_mod( 'guru_whatsapp_link', 'https://chat.whatsapp.com/I5Ln1bvpIP89FpaxRO4VJG?mode=gi_t' );
	return guru_append_utm_params( $base, 'whatsapp_' . $placement );
}

/**
 * Atributos HTML para link de afiliado rastreado.
 */
function guru_affiliate_link_attrs( $url, $content = 'sidebar' ) {
	$tracked = guru_append_utm_params( $url, $content );
	return sprintf(
		'href="%s" target="_blank" rel="nofollow sponsored noopener" data-guru-track="affiliate" data-guru-utm-content="%s"',
		esc_url( $tracked ),
		esc_attr( $content )
	);
}

/**
 * Atributos HTML para botão WhatsApp rastreado.
 */
function guru_whatsapp_link_attrs( $placement = 'cta' ) {
	$url = guru_whatsapp_tracked_url( $placement );
	return sprintf(
		'href="%s" target="_blank" rel="noopener" data-guru-track="whatsapp" data-guru-utm-content="%s"',
		esc_url( $url ),
		esc_attr( 'whatsapp_' . $placement )
	);
}

/**
 * Injeta UTMs em links .btn-affiliate no conteúdo do review.
 */
function guru_add_utm_to_content_affiliate_links( $content ) {
	if ( ! is_singular( 'review' ) ) {
		return $content;
	}

	return preg_replace_callback(
		'/<a\s+([^>]*?)href=(["\'])([^"\']+)\2([^>]*)>/i',
		function ( $matches ) {
			$before = $matches[1];
			$url    = $matches[3];
			$after  = $matches[4];
			$full   = $before . $after;

			if ( false === stripos( $full, 'btn-affiliate' ) ) {
				return $matches[0];
			}

			$content_id = 'content';
			if ( preg_match( '/id="produto-(\d+)"/', $full, $id_match ) ) {
				$content_id = 'product_' . $id_match[1];
			} elseif ( false !== stripos( $full, 'Ver melhor oferta' ) ) {
				$content_id = 'winner_cta';
			} elseif ( false !== stripos( $full, 'Ver oferta' ) ) {
				$content_id = 'table_quick';
			} elseif ( false !== stripos( $full, 'Ir ao produto' ) ) {
				$content_id = 'table_matrix';
			}

			$tracked = guru_append_utm_params( $url, $content_id );

			if ( false === stripos( $full, 'data-guru-track' ) ) {
				$after .= ' data-guru-track="affiliate"';
			}
			if ( false === stripos( $full, 'data-guru-utm-content' ) ) {
				$after .= ' data-guru-utm-content="' . esc_attr( $content_id ) . '"';
			}

			return '<a ' . $before . 'href="' . esc_url( $tracked ) . '"' . $after . '>';
		},
		$content
	);
}
add_filter( 'the_content', 'guru_add_utm_to_content_affiliate_links', 30 );

/**
 * Configuração JS para eventos de conversão (GA4 + Meta Pixel).
 */
function guru_tracking_script_config() {
	$campaign = guru_review_campaign_slug();
	$keyword  = is_singular( 'review' ) ? get_post_meta( get_the_ID(), '_guru_focus_keyword', true ) : '';
	$pixel    = function_exists( 'guru_meta_pixel_page_data' ) ? guru_meta_pixel_page_data() : array();

	wp_localize_script(
		'guru-tracking',
		'guruTracking',
		array(
			'campaign'        => $campaign,
			'keyword'         => $keyword,
			'postType'        => is_singular( 'review' ) ? 'review' : 'page',
			'gaId'            => get_theme_mod( 'guru_google_analytics', '' ),
			'adsId'           => get_theme_mod( 'guru_google_ads_id', '' ),
			'pixelId'         => function_exists( 'guru_meta_pixel_id' ) ? guru_meta_pixel_id() : '',
			'pixelEnabled'    => function_exists( 'guru_meta_pixel_events_enabled' ) ? guru_meta_pixel_events_enabled() : false,
			'pixelPage'       => $pixel,
			'utmSource'       => guru_get_stored_utm( 'utm_source' ),
			'utmMedium'       => guru_get_stored_utm( 'utm_medium' ),
			'utmCampaign'     => guru_get_stored_utm( 'utm_campaign' ),
			'utmContent'      => guru_get_stored_utm( 'utm_content' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'guru_tracking_script_config', 20 );

/**
 * Google Ads tag (opcional) — vincula campanhas ao GA4.
 */
function guru_google_ads_script() {
	if ( guru_site_kit_handles_analytics() ) {
		return;
	}

	$ads_id = get_theme_mod( 'guru_google_ads_id', '' );
	if ( ! $ads_id || is_user_logged_in() ) {
		return;
	}
	?>
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $ads_id ); ?>"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', '<?php echo esc_js( $ads_id ); ?>');
	</script>
	<?php
}
add_action( 'wp_head', 'guru_google_ads_script', 98 );
