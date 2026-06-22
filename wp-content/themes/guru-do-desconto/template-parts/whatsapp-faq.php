<?php
/**
 * FAQ — Grupo de promoções WhatsApp (conteúdo + SEO)
 *
 * @package GuruDoDesconto
 */

$whatsapp = guru_whatsapp_link();
$wa_msg   = get_theme_mod( 'guru_whatsapp_message', __( 'Entrar no Grupo de Promoções', 'guru-do-desconto' ) );
?>

<section class="section whatsapp-seo" id="grupo-whatsapp">
	<div class="container">
		<div class="section-header">
			<h2><?php esc_html_e( 'Grupo de Promoções no WhatsApp — Grátis', 'guru-do-desconto' ); ?></h2>
			<p><?php esc_html_e( 'Receba ofertas do Mercado Livre, Shopee e Amazon direto no seu celular. O melhor grupo de cupons e descontos no WhatsApp.', 'guru-do-desconto' ); ?></p>
		</div>

		<div class="whatsapp-seo-grid">
			<div class="whatsapp-seo-content review-prose">
				<p>
					<?php esc_html_e( 'Procurando um', 'guru-do-desconto' ); ?>
					<strong><?php esc_html_e( 'grupo de promoção no WhatsApp', 'guru-do-desconto' ); ?></strong>
					<?php esc_html_e( 'confiável? O Guru do Desconto envia diariamente as melhores ofertas, cupons exclusivos e alertas de preço baixo nas maiores lojas online do Brasil.', 'guru-do-desconto' ); ?>
				</p>
				<ul>
					<li><?php esc_html_e( 'Promoções Mercado Livre no WhatsApp', 'guru-do-desconto' ); ?></li>
					<li><?php esc_html_e( 'Ofertas Shopee com desconto', 'guru-do-desconto' ); ?></li>
					<li><?php esc_html_e( 'Cupons Amazon e Prime Day', 'guru-do-desconto' ); ?></li>
					<li><?php esc_html_e( 'Reviews comparativos com link de afiliado', 'guru-do-desconto' ); ?></li>
				</ul>
				<a href="<?php echo esc_url( $whatsapp ); ?>" class="btn btn-whatsapp" target="_blank" rel="noopener">
					<?php echo esc_html( $wa_msg ); ?>
				</a>
			</div>

			<div class="faq-list" itemscope itemtype="https://schema.org/FAQPage">
				<h3><?php esc_html_e( 'Perguntas frequentes', 'guru-do-desconto' ); ?></h3>
				<?php foreach ( guru_whatsapp_faq_items() as $item ) : ?>
					<details class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
						<summary itemprop="name"><?php echo esc_html( $item['question'] ); ?></summary>
						<div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
							<p itemprop="text"><?php echo esc_html( $item['answer'] ); ?></p>
						</div>
					</details>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
