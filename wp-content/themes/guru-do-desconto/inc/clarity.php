<?php
/**
 * Microsoft Clarity — heatmaps, gravações e insights de comportamento.
 *
 * @see https://clarity.microsoft.com/
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Project ID do Clarity (Customizer ou .env GURU_CLARITY_ID).
 */
function guru_clarity_id() {
	$id = sanitize_text_field( get_theme_mod( 'guru_clarity_id', '' ) );

	if ( ! $id && function_exists( 'guru_env' ) ) {
		$id = sanitize_text_field( guru_env( 'GURU_CLARITY_ID', '' ) );
	}

	$id = preg_replace( '/[^a-zA-Z0-9]/', '', $id );

	return strlen( $id ) >= 8 ? $id : '';
}

/**
 * Clarity ativo no Personalizar.
 */
function guru_clarity_enabled() {
	if ( ! get_theme_mod( 'guru_clarity_enabled', true ) ) {
		return false;
	}

	return (bool) guru_clarity_id();
}

/**
 * Evita carregar para admins logados (mesma lógica do Meta Pixel).
 */
function guru_clarity_skip_for_user() {
	if ( ! is_user_logged_in() ) {
		return false;
	}

	if ( ! get_theme_mod( 'guru_clarity_skip_admins', true ) ) {
		return false;
	}

	return current_user_can( 'edit_posts' );
}

/**
 * Deve injetar o Clarity nesta requisição?
 */
function guru_clarity_should_load() {
	if ( is_admin() || wp_doing_ajax() || wp_is_json_request() ) {
		return false;
	}

	if ( ! guru_clarity_enabled() ) {
		return false;
	}

	if ( guru_clarity_skip_for_user() ) {
		return false;
	}

	return true;
}

/**
 * Código base do Microsoft Clarity no <head>.
 */
function guru_clarity_head_script() {
	if ( ! guru_clarity_should_load() ) {
		return;
	}

	$project_id = guru_clarity_id();
	?>
	<!-- Microsoft Clarity -->
	<script type="text/javascript">
	(function(c,l,a,r,i,t,y){
		c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
		t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
		y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
	})(window, document, "clarity", "script", "<?php echo esc_js( $project_id ); ?>");
	</script>
	<!-- End Microsoft Clarity -->
	<?php
}
add_action( 'wp_head', 'guru_clarity_head_script', 6 );

/**
 * Sanitiza Clarity Project ID (alfanumérico).
 */
function guru_sanitize_clarity_id( $value ) {
	$id = preg_replace( '/[^a-zA-Z0-9]/', '', (string) $value );
	return strlen( $id ) >= 8 ? $id : '';
}
