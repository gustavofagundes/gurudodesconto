<?php
/**
 * Meta Pixel (Facebook / Instagram) — base code + dados para eventos.
 *
 * @see https://developers.facebook.com/docs/meta-pixel
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * ID numérico do Pixel (Customizer ou .env GURU_META_PIXEL_ID).
 */
function guru_meta_pixel_id() {
	$id = sanitize_text_field( get_theme_mod( 'guru_meta_pixel_id', '' ) );

	if ( ! $id && function_exists( 'guru_env' ) ) {
		$id = sanitize_text_field( guru_env( 'GURU_META_PIXEL_ID', '' ) );
	}

	$id = preg_replace( '/\D/', '', $id );

	return strlen( $id ) >= 15 ? $id : '';
}

/**
 * Pixel ativo no Personalizar.
 */
function guru_meta_pixel_enabled() {
	if ( ! get_theme_mod( 'guru_meta_pixel_enabled', true ) ) {
		return false;
	}

	return (bool) guru_meta_pixel_id();
}

/**
 * Evita carregar para admins logados (testes sem poluir dados).
 */
function guru_meta_pixel_skip_for_user() {
	if ( ! is_user_logged_in() ) {
		return false;
	}

	if ( ! get_theme_mod( 'guru_meta_pixel_skip_admins', true ) ) {
		return false;
	}

	return current_user_can( 'edit_posts' );
}

/**
 * Deve injetar o Pixel nesta requisição?
 */
function guru_meta_pixel_should_load() {
	if ( is_admin() || wp_doing_ajax() || wp_is_json_request() ) {
		return false;
	}

	if ( function_exists( 'guru_theme_handles_meta_pixel_base' ) && ! guru_theme_handles_meta_pixel_base() ) {
		return false;
	}

	if ( ! guru_meta_pixel_enabled() ) {
		return false;
	}

	if ( guru_meta_pixel_skip_for_user() ) {
		return false;
	}

	return true;
}

/**
 * Dados de contexto da página para eventos Meta.
 *
 * @return array<string, mixed>
 */
function guru_meta_pixel_page_data() {
	$data = array(
		'pageType' => 'other',
		'currency' => 'BRL',
	);

	if ( is_front_page() ) {
		$data['pageType']        = 'home';
		$data['contentName']     = __( 'Grupos de Promoções WhatsApp', 'guru-do-desconto' );
		$data['contentCategory'] = 'whatsapp_grupos';
		return $data;
	}

	if ( function_exists( 'guru_get_whatsapp_group_from_page' ) && ( $group = guru_get_whatsapp_group_from_page() ) ) {
		$data['pageType']        = 'whatsapp_group_landing';
		$data['contentName']     = $group['name'];
		$data['contentCategory'] = 'whatsapp_' . ( $group['slug'] ?? 'grupo' );
		return $data;
	}

	if ( is_singular( 'review' ) ) {
		$id    = get_the_ID();
		$price = get_post_meta( $id, '_guru_price', true );
		$keyword = get_post_meta( $id, '_guru_focus_keyword', true );

		$data['pageType']        = 'review';
		$data['contentName']     = get_the_title();
		$data['contentCategory'] = $keyword ?: 'review';
		$data['contentIds']      = array( get_post_field( 'post_name', $id ) );
		$data['contentType']     = 'product';

		if ( $price && is_numeric( $price ) ) {
			$data['value'] = (float) $price;
		}

		return $data;
	}

	if ( is_post_type_archive( 'review' ) ) {
		$data['pageType']        = 'review_archive';
		$data['contentName']     = __( 'Reviews', 'guru-do-desconto' );
		$data['contentCategory'] = 'reviews';
		return $data;
	}

	if ( function_exists( 'guru_is_whatsapp_landing_page' ) && guru_is_whatsapp_landing_page() ) {
		$data['pageType']        = 'whatsapp_landing';
		$data['contentName']     = __( 'Grupo WhatsApp', 'guru-do-desconto' );
		$data['contentCategory'] = 'whatsapp';
		return $data;
	}

	if ( is_singular( 'page' ) ) {
		$data['pageType']        = 'page';
		$data['contentName']     = get_the_title();
		$data['contentCategory'] = 'page';
		return $data;
	}

	return $data;
}

/**
 * Código base do Meta Pixel no <head>.
 */
function guru_meta_pixel_head_script() {
	if ( ! guru_meta_pixel_should_load() ) {
		return;
	}

	$pixel_id = guru_meta_pixel_id();
	?>
	<!-- Meta Pixel Code -->
	<script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];
	s.parentNode.insertBefore(t,s)}(window, document,'script',
	'https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '<?php echo esc_js( $pixel_id ); ?>');
	fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none" alt=""
	src="https://www.facebook.com/tr?id=<?php echo esc_attr( $pixel_id ); ?>&ev=PageView&noscript=1"
	/></noscript>
	<!-- End Meta Pixel Code -->
	<?php
}
add_action( 'wp_head', 'guru_meta_pixel_head_script', 5 );

/**
 * ViewContent automático em páginas de review (intenção de compra).
 */
function guru_meta_pixel_view_content_script() {
	if ( ! guru_meta_pixel_should_load() || ! is_singular( 'review' ) ) {
		return;
	}

	if ( function_exists( 'guru_pixelyoursite_active' ) && guru_pixelyoursite_active() ) {
		return;
	}

	$page = guru_meta_pixel_page_data();
	$payload = array(
		'content_name'     => wp_strip_all_tags( $page['contentName'] ?? '' ),
		'content_category' => wp_strip_all_tags( $page['contentCategory'] ?? 'review' ),
		'content_type'     => 'product',
		'currency'         => 'BRL',
	);

	if ( ! empty( $page['contentIds'] ) ) {
		$payload['content_ids'] = $page['contentIds'];
	}

	if ( ! empty( $page['value'] ) ) {
		$payload['value'] = $page['value'];
	}

	?>
	<script>
	document.addEventListener('DOMContentLoaded', function () {
		if (typeof fbq !== 'function') { return; }
		fbq('track', 'ViewContent', <?php echo wp_json_encode( $payload, JSON_UNESCAPED_UNICODE ); ?>);
	});
	</script>
	<?php
}
add_action( 'wp_footer', 'guru_meta_pixel_view_content_script', 5 );

/**
 * Sanitiza Pixel ID (somente dígitos).
 */
function guru_sanitize_meta_pixel_id( $value ) {
	$id = preg_replace( '/\D/', '', (string) $value );
	return strlen( $id ) >= 15 ? $id : '';
}
