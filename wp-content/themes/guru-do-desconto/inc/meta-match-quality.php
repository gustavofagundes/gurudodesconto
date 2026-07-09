<?php
/**
 * Meta Pixel — melhora automática da correspondência de eventos (sem formulários).
 *
 * - Persiste fbclid → cookie _fbc (Click ID)
 * - ID anônimo persistente (external_id / pbid compatível com PixelYourSite)
 * - Script antecipado no <head> antes do pixel carregar
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Define cookie de first-party (não HttpOnly — Meta lê via JS).
 */
function guru_meta_set_cookie( $name, $value, $max_age ) {
	if ( headers_sent() || is_admin() ) {
		return;
	}

	setcookie(
		$name,
		$value,
		time() + (int) $max_age,
		COOKIEPATH ?: '/',
		COOKIE_DOMAIN,
		is_ssl(),
		false
	);

	$_COOKIE[ $name ] = $value;
}

/**
 * ID anônimo persistente — compatível com cookie pbid do PixelYourSite.
 */
function guru_get_anonymous_external_id() {
	if ( ! empty( $_COOKIE['pbid'] ) ) {
		return sanitize_text_field( wp_unslash( $_COOKIE['pbid'] ) );
	}

	if ( ! empty( $_COOKIE['guru_ext_id'] ) ) {
		return sanitize_text_field( wp_unslash( $_COOKIE['guru_ext_id'] ) );
	}

	return '';
}

/**
 * Garante external_id anônimo (executado no init).
 */
function guru_ensure_anonymous_external_id() {
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return '';
	}

	$existing = guru_get_anonymous_external_id();
	if ( $existing ) {
		return $existing;
	}

	$id = wp_generate_uuid4();
	guru_meta_set_cookie( 'guru_ext_id', $id, YEAR_IN_SECONDS );
	guru_meta_set_cookie( 'pbid', $id, YEAR_IN_SECONDS );

	return $id;
}
add_action( 'init', 'guru_ensure_anonymous_external_id', 7 );

/**
 * Persiste fbclid da URL → cookies _fbc e guru_fbclid (90 dias).
 */
function guru_capture_meta_click_id() {
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	$fbclid = isset( $_GET['fbclid'] ) ? sanitize_text_field( wp_unslash( $_GET['fbclid'] ) ) : '';

	if ( ! $fbclid && ! empty( $_COOKIE['guru_fbclid'] ) && empty( $_COOKIE['_fbc'] ) ) {
		$fbclid = sanitize_text_field( wp_unslash( $_COOKIE['guru_fbclid'] ) );
	}

	if ( ! $fbclid ) {
		return;
	}

	$stored   = ! empty( $_COOKIE['guru_fbclid'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['guru_fbclid'] ) ) : '';
	$needs_fbc = empty( $_COOKIE['_fbc'] ) || $fbclid !== $stored;

	if ( $needs_fbc ) {
		$fbc = 'fb.1.' . time() . '.' . $fbclid;
		guru_meta_set_cookie( '_fbc', $fbc, 90 * DAY_IN_SECONDS );
	}

	guru_meta_set_cookie( 'guru_fbclid', $fbclid, 90 * DAY_IN_SECONDS );
}
add_action( 'init', 'guru_capture_meta_click_id', 0 );

/**
 * Advanced matching para PixelYourSite (external_id anônimo).
 *
 * @param array<string, string> $params
 * @return array<string, string>
 */
function guru_pys_fb_advanced_matching( $params ) {
	$ext_id = guru_get_anonymous_external_id();
	if ( $ext_id ) {
		$params['external_id'] = $ext_id;
	}
	return $params;
}
add_filter( 'pys_fb_advanced_matching', 'guru_pys_fb_advanced_matching' );

/**
 * Script mínimo no head — captura fbclid antes do pixel/PYS carregar.
 */
function guru_meta_match_early_script() {
	if ( is_admin() || wp_doing_ajax() ) {
		return;
	}

	if ( function_exists( 'guru_meta_pixel_skip_for_user' ) && guru_meta_pixel_skip_for_user() ) {
		return;
	}
	?>
	<script>
	(function () {
		try {
			var params = new URLSearchParams(window.location.search);
			var fbclid = params.get('fbclid');
			if (!fbclid) {
				return;
			}
			sessionStorage.setItem('guru_fbclid', fbclid);
			var secure = location.protocol === 'https:' ? ';Secure' : '';
			var maxAge = 90 * 24 * 60 * 60;
			document.cookie = 'guru_fbclid=' + encodeURIComponent(fbclid) + ';path=/;max-age=' + maxAge + ';SameSite=Lax' + secure;
			var fbc = 'fb.1.' + Date.now() + '.' + fbclid;
			document.cookie = '_fbc=' + encodeURIComponent(fbc) + ';path=/;max-age=' + maxAge + ';SameSite=Lax' + secure;
		} catch (e) {}
	})();
	</script>
	<?php
}
add_action( 'wp_head', 'guru_meta_match_early_script', 3 );
