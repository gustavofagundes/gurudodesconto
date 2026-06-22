<?php
/**
 * Google AdSense
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Client ID do AdSense.
 */
function guru_adsense_client_id() {
	return sanitize_text_field( get_theme_mod( 'guru_adsense_client', 'ca-pub-2824875854264000' ) );
}

/**
 * AdSense está ativo?
 */
function guru_adsense_enabled() {
	if ( ! get_theme_mod( 'guru_adsense_enabled', true ) ) {
		return false;
	}

	$client = guru_adsense_client_id();
	return $client && preg_match( '/^ca-pub-\d+$/', $client );
}

/**
 * Não exibir anúncios para editores logados.
 */
function guru_adsense_should_render() {
	return guru_adsense_enabled() && ! ( is_user_logged_in() && current_user_can( 'edit_posts' ) );
}

/**
 * Script global do AdSense no <head>.
 */
function guru_adsense_head_script() {
	if ( ! guru_adsense_should_render() ) {
		return;
	}

	$client = esc_attr( guru_adsense_client_id() );
	?>
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=<?php echo $client; ?>"
	        crossorigin="anonymous"></script>
	<?php
}
add_action( 'wp_head', 'guru_adsense_head_script', 5 );

/**
 * Renderiza unidade de anúncio manual (opcional — requer data-ad-slot).
 *
 * @param string $slot_key Chave do customizer: home_mid, review_sidebar, etc.
 */
function guru_render_adsense_unit( $slot_key ) {
	if ( ! guru_adsense_should_render() ) {
		return;
	}

	$slot = sanitize_text_field( get_theme_mod( 'guru_adsense_slot_' . $slot_key, '' ) );
	if ( ! $slot || ! preg_match( '/^\d+$/', $slot ) ) {
		return;
	}

	$client = esc_attr( guru_adsense_client_id() );
	$uid    = 'guru-ad-' . esc_attr( $slot_key );
	?>
	<div class="guru-ad" id="<?php echo esc_attr( $uid ); ?>">
		<p class="guru-ad-label"><?php esc_html_e( 'Publicidade', 'guru-do-desconto' ); ?></p>
		<ins class="adsbygoogle"
		     style="display:block"
		     data-ad-client="<?php echo $client; ?>"
		     data-ad-slot="<?php echo esc_attr( $slot ); ?>"
		     data-ad-format="auto"
		     data-full-width-responsive="true"></ins>
		<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
	</div>
	<?php
}

/**
 * Ativa anúncios automáticos via filtro (Auto ads gerenciado no painel AdSense).
 */
function guru_adsense_auto_ads_note() {
	// O script no head é suficiente para Auto ads quando habilitado em adsense.google.com.
}
