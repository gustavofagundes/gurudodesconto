<?php
/**
 * Template: hub — /grupo-whatsapp/
 *
 * @package GuruDoDesconto
 */

get_header();
?>

<section class="section whatsapp-hub">
	<div class="container">
		<header class="section-header">
			<h1><?php esc_html_e( 'Grupos de Promoções no WhatsApp', 'guru-do-desconto' ); ?></h1>
			<p><?php esc_html_e( '8 grupos gratuitos por nicho — escolha o seu e entre em menos de 1 minuto.', 'guru-do-desconto' ); ?></p>
		</header>

		<div class="whatsapp-hub-grid">
			<?php foreach ( guru_whatsapp_groups() as $group ) : ?>
				<?php $landing = guru_whatsapp_group_landing_url( $group ); ?>
			<article class="whatsapp-hub-card">
				<a href="<?php echo esc_url( $landing ); ?>" class="whatsapp-hub-card__media">
					<img src="<?php echo esc_url( $group['image'] ); ?>"
					     alt="<?php echo esc_attr( $group['name'] ); ?>"
					     width="320"
					     height="320"
					     loading="lazy"
					     decoding="async">
				</a>
				<div class="whatsapp-hub-card__body">
					<h2><a href="<?php echo esc_url( $landing ); ?>"><?php echo esc_html( $group['name'] ); ?></a></h2>
					<p><?php echo esc_html( $group['tagline'] ); ?></p>
					<a href="<?php echo esc_url( $landing ); ?>" class="btn btn-whatsapp btn-sm">
						<?php echo guru_whatsapp_icon_svg( 18 ); ?>
						<?php esc_html_e( 'Quero Entrar no Grupo', 'guru-do-desconto' ); ?>
					</a>
				</div>
			</article>
			<?php endforeach; ?>
		</div>

		<p class="whatsapp-hub-back">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( '← Voltar para a página inicial', 'guru-do-desconto' ); ?></a>
		</p>
	</div>
</section>

<?php get_footer(); ?>
