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
		<?php
		$wa_page_id = (int) get_option( 'guru_whatsapp_page_id', 0 );
		$wa_page    = $wa_page_id ? get_permalink( $wa_page_id ) : home_url( '/grupo-promocoes-whatsapp/' );
		?>
		<li><a href="<?php echo esc_url( home_url( '/#grupo-whatsapp' ) ); ?>"><?php esc_html_e( 'Grupo WhatsApp', 'guru-do-desconto' ); ?></a></li>
		<li><a href="<?php echo esc_url( $wa_page ); ?>"><?php esc_html_e( 'Promoções WhatsApp', 'guru-do-desconto' ); ?></a></li>
		<li><a <?php echo guru_whatsapp_link_attrs( 'nav' ); ?> class="nav-cta"><?php echo esc_html( $msg ); ?></a></li>
	</ul>
	<?php
}
