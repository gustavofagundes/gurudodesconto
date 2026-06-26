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
 * Carrega o script do AdSense após idle/interação (não bloqueia LCP).
 */
function guru_adsense_head_script() {
	if ( ! guru_adsense_should_render() ) {
		return;
	}

	$client = esc_js( guru_adsense_client_id() );
	?>
	<script>
	(function () {
		var loaded = false;
		function loadAdsense() {
			if (loaded) {
				return;
			}
			loaded = true;
			var s = document.createElement('script');
			s.async = true;
			s.crossOrigin = 'anonymous';
			s.src = 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=<?php echo $client; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>';
			document.head.appendChild(s);
		}
		if ('requestIdleCallback' in window) {
			requestIdleCallback(loadAdsense, { timeout: 3500 });
		} else {
			window.addEventListener('load', function () {
				setTimeout(loadAdsense, 2000);
			});
		}
		['scroll', 'click', 'touchstart', 'keydown'].forEach(function (evt) {
			window.addEventListener(evt, loadAdsense, { once: true, passive: true });
		});
	})();
	</script>
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
