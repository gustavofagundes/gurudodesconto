<?php
/**
 * FAQ — Grupo de promoções WhatsApp (conteúdo + SEO)
 *
 * @package GuruDoDesconto
 */
?>

<section class="section whatsapp-seo" id="grupo-whatsapp">
	<div class="container">
		<div class="section-header">
			<h2><?php esc_html_e( 'Dúvidas sobre os grupos', 'guru-do-desconto' ); ?></h2>
			<p><?php esc_html_e( 'Tudo o que você precisa saber antes de entrar — é grátis e sem compromisso.', 'guru-do-desconto' ); ?></p>
		</div>

		<div class="whatsapp-seo-grid">
			<div class="whatsapp-seo-content review-prose">
				<p>
					<?php esc_html_e( 'O Guru do Desconto organiza', 'guru-do-desconto' ); ?>
					<strong><?php esc_html_e( '7 grupos de promoção no WhatsApp', 'guru-do-desconto' ); ?></strong>
					<?php esc_html_e( 'por nicho. Você recebe ofertas do Mercado Livre, Shopee e Amazon sem pagar nada — só o que importa para você.', 'guru-do-desconto' ); ?>
				</p>
				<ul>
					<li><?php esc_html_e( 'Promoções Mercado Livre no WhatsApp', 'guru-do-desconto' ); ?></li>
					<li><?php esc_html_e( 'Ofertas Shopee com desconto', 'guru-do-desconto' ); ?></li>
					<li><?php esc_html_e( 'Cupons Amazon e Prime Day', 'guru-do-desconto' ); ?></li>
					<li><?php esc_html_e( 'Entre em um ou vários grupos de uma vez', 'guru-do-desconto' ); ?></li>
				</ul>
				<a href="#grupos-whatsapp" class="btn btn-whatsapp">
					<?php esc_html_e( 'Ver grupos e entrar grátis', 'guru-do-desconto' ); ?>
				</a>
			</div>

			<div class="faq-list">
				<h3><?php esc_html_e( 'Perguntas frequentes', 'guru-do-desconto' ); ?></h3>
				<?php foreach ( guru_whatsapp_faq_items() as $item ) : ?>
					<details class="faq-item">
						<summary><?php echo esc_html( $item['question'] ); ?></summary>
						<div class="faq-answer">
							<p><?php echo esc_html( $item['answer'] ); ?></p>
						</div>
					</details>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
