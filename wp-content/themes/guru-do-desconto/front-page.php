<?php
/**
 * Front page template — foco em conversão (entrada nos grupos).
 *
 * @package GuruDoDesconto
 */

get_header();

$geral = guru_get_whatsapp_group( 'geral' );
?>

<section class="hero hero--groups hero--conversion">
	<div class="container hero-grid">
		<div class="hero-content">
			<span class="hero-badge"><?php esc_html_e( '100% grátis · Sem spam', 'guru-do-desconto' ); ?></span>
			<h1>
				<?php esc_html_e( 'Receba achadinhos e promoções', 'guru-do-desconto' ); ?>
				<span><?php esc_html_e( 'todos os dias no WhatsApp', 'guru-do-desconto' ); ?></span>
			</h1>
			<p class="hero-lead hero-promise">
				<?php esc_html_e( '7 grupos por nicho — Mercado Livre, Shopee e Amazon. Escolha o seu e entre em menos de 1 minuto.', 'guru-do-desconto' ); ?>
			</p>

			<p class="conversion-social-proof">
				<?php esc_html_e( 'Comunidade gratuita para quem ama economizar — promoções selecionadas todos os dias.', 'guru-do-desconto' ); ?>
			</p>

			<div class="hero-actions hero-actions--single">
				<a href="#grupos-whatsapp" class="btn btn-whatsapp btn-lg btn-cta-primary">
					<?php echo guru_whatsapp_icon_svg( 22 ); ?>
					<?php esc_html_e( 'Entrar no Grupo Grátis', 'guru-do-desconto' ); ?>
				</a>
			</div>

			<p class="conversion-urgency">
				<?php esc_html_e( 'Ofertas podem acabar a qualquer momento — entre para não perder os achadinhos do dia.', 'guru-do-desconto' ); ?>
			</p>

			<p class="hero-microcopy">
				<?php esc_html_e( 'Sem cartão · Sem cadastro · Saia quando quiser', 'guru-do-desconto' ); ?>
			</p>
		</div>
		<div class="hero-image">
			<?php $hero_img = $geral ? $geral['image'] : guru_hero_image_url(); ?>
			<img src="<?php echo esc_url( $hero_img ); ?>"
			     alt="<?php esc_attr_e( 'Grupos de promoções no WhatsApp — Guru do Desconto', 'guru-do-desconto' ); ?>"
			     width="380" height="380"
			     loading="eager"
			     fetchpriority="high"
			     decoding="async">
		</div>
	</div>
</section>

<?php get_template_part( 'template-parts/whatsapp', 'groups-grid' ); ?>

<div class="landing-sticky-cta landing-sticky-cta--always" data-landing-sticky>
	<a href="#grupos-whatsapp" class="btn btn-whatsapp">
		<?php echo guru_whatsapp_icon_svg( 20 ); ?>
		<?php esc_html_e( 'Entrar no Grupo Grátis', 'guru-do-desconto' ); ?>
	</a>
</div>

<?php get_footer(); ?>
