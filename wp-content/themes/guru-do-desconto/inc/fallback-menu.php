<?php
/**
 * Fallback menu when no menu is assigned.
 */
function guru_fallback_menu() {
	$is_landing = is_front_page();
	$msg        = get_theme_mod( 'guru_whatsapp_message', __( 'Entrar nos grupos', 'guru-do-desconto' ) );
	?>
	<ul>
		<?php if ( $is_landing ) : ?>
			<li><a href="#grupos-whatsapp"><?php esc_html_e( 'Grupos', 'guru-do-desconto' ); ?></a></li>
			<li><a href="#como-funciona"><?php esc_html_e( 'Como funciona', 'guru-do-desconto' ); ?></a></li>
			<li><a href="#grupo-whatsapp"><?php esc_html_e( 'Dúvidas', 'guru-do-desconto' ); ?></a></li>
			<li><a href="<?php echo esc_url( get_post_type_archive_link( 'review' ) ); ?>"><?php esc_html_e( 'Reviews', 'guru-do-desconto' ); ?></a></li>
			<li><a href="#grupos-whatsapp" class="nav-cta"><?php echo esc_html( $msg ); ?></a></li>
		<?php else : ?>
			<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Início', 'guru-do-desconto' ); ?></a></li>
			<li><a href="<?php echo esc_url( get_post_type_archive_link( 'review' ) ); ?>"><?php esc_html_e( 'Reviews', 'guru-do-desconto' ); ?></a></li>
			<?php
			$wa_page_id = (int) get_option( 'guru_whatsapp_page_id', 0 );
			$wa_page    = $wa_page_id ? get_permalink( $wa_page_id ) : home_url( '/grupo-promocoes-whatsapp/' );
			?>
			<li><a href="<?php echo esc_url( home_url( '/#grupos-whatsapp' ) ); ?>"><?php esc_html_e( 'Grupos WhatsApp', 'guru-do-desconto' ); ?></a></li>
			<li><a href="<?php echo esc_url( $wa_page ); ?>"><?php esc_html_e( 'Promoções WhatsApp', 'guru-do-desconto' ); ?></a></li>
			<?php
			$geral = function_exists( 'guru_get_whatsapp_group' ) ? guru_get_whatsapp_group( 'geral' ) : null;
			if ( $geral ) :
				?>
			<li><a <?php echo guru_whatsapp_group_link_attrs( $geral, 'nav' ); ?> class="nav-cta"><?php echo esc_html( $msg ); ?></a></li>
			<?php else : ?>
			<li><a <?php echo guru_whatsapp_link_attrs( 'nav' ); ?> class="nav-cta"><?php echo esc_html( $msg ); ?></a></li>
			<?php endif; ?>
		<?php endif; ?>
	</ul>
	<?php
}
