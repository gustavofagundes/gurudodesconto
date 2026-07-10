<?php
/**
 * Front page template — foco em conversão (entrada nos grupos).
 *
 * @package GuruDoDesconto
 */

get_header();

$geral = guru_get_whatsapp_group( 'geral' );
?>

<section class="wa-hero-v3 wa-hero-v3--home">
	<div class="container wa-hero-v3__inner">
		<?php $hero_img = $geral ? $geral['image'] : guru_hero_image_url(); ?>
		<img src="<?php echo esc_url( $hero_img ); ?>"
		     alt="<?php esc_attr_e( 'Grupos de promoções no WhatsApp — Guru do Desconto', 'guru-do-desconto' ); ?>"
		     width="112"
		     height="112"
		     loading="eager"
		     fetchpriority="high"
		     decoding="async"
		     class="wa-hero-v3__avatar">

		<span class="wa-hero-v3__badge"><?php esc_html_e( '100% grátis · Sem spam', 'guru-do-desconto' ); ?></span>

		<h1 class="wa-hero-v3__title">
			<?php esc_html_e( 'E se você soubesse das promoções', 'guru-do-desconto' ); ?>
			<span class="wa-hero-v3__title-highlight"><?php esc_html_e( 'antes de todo mundo?', 'guru-do-desconto' ); ?></span>
		</h1>

		<p class="wa-hero-v3__subtitle">
			<?php esc_html_e( 'Achadinhos selecionados do Mercado Livre, Shopee e Amazon — direto no seu WhatsApp, sem pagar nada.', 'guru-do-desconto' ); ?>
		</p>

		<div class="wa-cta-block">
			<a href="#grupos-whatsapp" class="btn btn-cta-hero">
				<?php echo guru_whatsapp_icon_svg( 22 ); ?>
				<?php esc_html_e( 'Escolher Meu Grupo Grátis', 'guru-do-desconto' ); ?>
			</a>
		</div>

		<ul class="wa-hero-v3__trust">
			<li><?php esc_html_e( 'Sem cadastro', 'guru-do-desconto' ); ?></li>
			<li><?php esc_html_e( 'Saia quando quiser', 'guru-do-desconto' ); ?></li>
			<li><?php esc_html_e( 'ML, Shopee e Amazon', 'guru-do-desconto' ); ?></li>
		</ul>

		<?php if ( function_exists( 'guru_has_achadinhos_page' ) && guru_has_achadinhos_page() ) : ?>
			<a href="<?php echo esc_url( guru_achadinhos_page_url() ); ?>" class="wa-hero-v3__secondary-link">
				<?php esc_html_e( 'Prefere só olhar? Veja os Achadinhos Amazon do dia →', 'guru-do-desconto' ); ?>
			</a>
		<?php endif; ?>
	</div>
</section>

<?php get_template_part( 'template-parts/whatsapp', 'groups-grid' ); ?>

<div class="landing-sticky-cta landing-sticky-cta--always" data-landing-sticky>
	<div class="landing-sticky-cta__inner">
		<a href="#grupos-whatsapp" class="btn btn-whatsapp">
			<?php echo guru_whatsapp_icon_svg( 20 ); ?>
			<?php esc_html_e( 'Participar Gratuitamente', 'guru-do-desconto' ); ?>
		</a>
	</div>
</div>

<?php get_footer(); ?>
