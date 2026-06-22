<?php
/**
 * Fallback menu when no menu is assigned.
 */
function guru_fallback_menu() {
	$whatsapp = guru_whatsapp_link();
	$msg      = get_theme_mod( 'guru_whatsapp_message', __( 'Grupo WhatsApp', 'guru-do-desconto' ) );
	?>
	<ul>
		<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Início', 'guru-do-desconto' ); ?></a></li>
		<li><a href="<?php echo esc_url( get_post_type_archive_link( 'review' ) ); ?>"><?php esc_html_e( 'Reviews', 'guru-do-desconto' ); ?></a></li>
		<li><a href="#promocoes"><?php esc_html_e( 'Promoções', 'guru-do-desconto' ); ?></a></li>
		<li><a href="<?php echo esc_url( $whatsapp ); ?>" class="nav-cta" target="_blank" rel="noopener"><?php echo esc_html( $msg ); ?></a></li>
	</ul>
	<?php
}
