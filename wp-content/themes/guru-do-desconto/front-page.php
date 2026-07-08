<?php
/**
 * Front page template — foco em conversão (entrada nos grupos).
 *
 * @package GuruDoDesconto
 */

get_header();

$geral = guru_get_whatsapp_group( 'geral' );
?>

<section class="hero hero--groups hero--conversion hero--v2">
	<div class="container hero-grid">
		<div class="hero-content">
			<span class="hero-badge"><?php esc_html_e( '100% grátis · Sem spam', 'guru-do-desconto' ); ?></span>

			<h1 class="hero-hook">
				<?php esc_html_e( 'E se você soubesse das promoções', 'guru-do-desconto' ); ?>
				<span><?php esc_html_e( 'antes de todo mundo?', 'guru-do-desconto' ); ?></span>
			</h1>

			<p class="hero-lead hero-promise">
				<?php esc_html_e( 'Achadinhos selecionados do Mercado Livre, Shopee e Amazon — direto no seu WhatsApp, sem pagar nada.', 'guru-do-desconto' ); ?>
			</p>

			<div class="hero-actions hero-actions--single">
				<a href="#grupos-whatsapp" class="btn btn-whatsapp btn-lg btn-cta-primary">
					<?php echo guru_whatsapp_icon_svg( 22 ); ?>
					<?php esc_html_e( 'Escolher Meu Grupo Grátis', 'guru-do-desconto' ); ?>
				</a>
				<?php if ( function_exists( 'guru_has_achadinhos_page' ) && guru_has_achadinhos_page() ) : ?>
				<a href="<?php echo esc_url( guru_achadinhos_page_url() ); ?>" class="btn btn-secondary btn-lg hero-achadinhos-cta">
					<?php esc_html_e( 'Ver Achadinhos Amazon do dia', 'guru-do-desconto' ); ?>
				</a>
				<?php endif; ?>
				<p class="cta-join-hint">
					<span class="cta-join-hint__arrow" aria-hidden="true">↓</span>
					<?php echo esc_html( guru_whatsapp_join_hint() ); ?>
				</p>
			</div>

			<?php if ( $geral ) : ?>
				<div class="wa-teaser wa-teaser--hero">
					<p class="wa-teaser__label"><?php esc_html_e( 'Exemplos de achadinhos que já rolam nos grupos:', 'guru-do-desconto' ); ?></p>
					<ul class="wa-teaser__list">
						<?php foreach ( guru_whatsapp_group_teaser_items( $geral ) as $item ) : ?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<p class="conversion-urgency">
				<?php esc_html_e( 'Promoções boas duram poucas horas. Quem está no grupo recebe primeiro.', 'guru-do-desconto' ); ?>
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
	<div class="landing-sticky-cta__inner">
		<p class="landing-sticky-cta__hint"><?php esc_html_e( 'Escolha seu grupo e confirme no WhatsApp', 'guru-do-desconto' ); ?></p>
		<a href="#grupos-whatsapp" class="btn btn-whatsapp">
			<?php echo guru_whatsapp_icon_svg( 20 ); ?>
			<?php esc_html_e( 'Participar Gratuitamente', 'guru-do-desconto' ); ?>
		</a>
	</div>
</div>

<?php get_footer(); ?>
