<?php
/**
 * Landing de um grupo WhatsApp — página de conversão enxuta.
 *
 * @package GuruDoDesconto
 *
 * @var array $args
 */

$group = $args['group'] ?? guru_get_whatsapp_group_from_page();
if ( ! $group ) {
	return;
}

$benefits = $group['benefits'] ?? array();
?>

<article class="wa-group-landing wa-group-landing--conversion" itemscope itemtype="https://schema.org/WebPage">
	<section class="wa-group-hero">
		<div class="container wa-group-hero__grid">
			<div class="wa-group-hero__content">
				<span class="hero-badge"><?php esc_html_e( '100% grátis', 'guru-do-desconto' ); ?></span>

				<p class="wa-group-hook conversion-hook">
					<?php echo esc_html( guru_whatsapp_group_hook( $group ) ); ?>
				</p>

				<h1 itemprop="name"><?php echo esc_html( guru_whatsapp_group_headline( $group ) ); ?></h1>

				<p class="wa-group-hero__promise hero-promise" itemprop="description">
					<?php echo esc_html( guru_whatsapp_group_promise( $group ) ); ?>
				</p>

				<p class="conversion-social-proof">
					<?php echo esc_html( guru_whatsapp_group_social_proof( $group ) ); ?>
				</p>

				<div class="wa-group-hero__actions hero-actions--single">
					<a <?php echo guru_whatsapp_group_link_attrs( $group, 'landing' ); ?> class="btn btn-whatsapp btn-lg btn-cta-primary">
						<?php echo guru_whatsapp_icon_svg( 22 ); ?>
						<?php echo esc_html( guru_whatsapp_cta_label( $group, 'primary' ) ); ?>
					</a>
				</div>

				<p class="conversion-urgency">
					<?php echo esc_html( guru_whatsapp_group_urgency( $group ) ); ?>
				</p>

				<?php if ( $benefits ) : ?>
					<ul class="wa-group-benefits">
						<?php foreach ( array_slice( $benefits, 0, 4 ) as $benefit ) : ?>
							<li><?php echo esc_html( $benefit ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<p class="hero-microcopy">
					<?php esc_html_e( 'Mercado Livre, Shopee e Amazon · Saia quando quiser', 'guru-do-desconto' ); ?>
				</p>
			</div>
			<div class="wa-group-hero__image">
				<img src="<?php echo esc_url( $group['image'] ); ?>"
				     alt="<?php echo esc_attr( $group['name'] ); ?>"
				     width="420"
				     height="420"
				     loading="eager"
				     fetchpriority="high"
				     decoding="async"
				     itemprop="image">
			</div>
		</div>
	</section>

	<section class="wa-group-steps-compact">
		<div class="container">
			<ol class="wa-group-steps-compact__list">
				<li><strong>1.</strong> <?php esc_html_e( 'Toque no botão verde', 'guru-do-desconto' ); ?></li>
				<li><strong>2.</strong> <?php esc_html_e( 'Confirme no WhatsApp', 'guru-do-desconto' ); ?></li>
				<li><strong>3.</strong> <?php esc_html_e( 'Receba as ofertas do dia', 'guru-do-desconto' ); ?></li>
			</ol>
		</div>
	</section>
</article>

<div class="landing-sticky-cta landing-sticky-cta--always wa-group-sticky">
	<a <?php echo guru_whatsapp_group_link_attrs( $group, 'sticky' ); ?> class="btn btn-whatsapp">
		<?php echo guru_whatsapp_icon_svg( 20 ); ?>
		<?php echo esc_html( guru_whatsapp_cta_label( $group, 'sticky' ) ); ?>
	</a>
</div>
