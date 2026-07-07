<?php
/**
 * Landing de um grupo WhatsApp — conversão otimizada.
 *
 * @package GuruDoDesconto
 *
 * @var array $args
 */

$group  = $args['group'] ?? guru_get_whatsapp_group_from_page();
$teaser = $group ? guru_whatsapp_group_teaser_items( $group ) : array();

if ( ! $group ) {
	return;
}
?>

<article class="wa-group-landing wa-group-landing--v2" itemscope itemtype="https://schema.org/WebPage">
	<section class="wa-group-hero wa-group-hero--v2">
		<div class="container">
			<div class="wa-group-hero__top">
				<span class="hero-badge"><?php esc_html_e( '100% grátis · Sem spam', 'guru-do-desconto' ); ?></span>

				<h1 class="wa-group-hero__hook" itemprop="name">
					<?php echo esc_html( guru_whatsapp_group_hook( $group ) ); ?>
				</h1>

				<p class="wa-group-hero__headline" itemprop="description">
					<?php echo esc_html( guru_whatsapp_group_headline( $group ) ); ?>
				</p>

				<?php
				get_template_part(
					'template-parts/whatsapp',
					'cta-block',
					array(
						'group'     => $group,
						'placement' => 'landing',
						'show_hint' => true,
					)
				);
				?>

				<?php if ( $teaser ) : ?>
					<div class="wa-teaser">
						<p class="wa-teaser__label"><?php esc_html_e( 'Achadinhos que já apareceram no grupo:', 'guru-do-desconto' ); ?></p>
						<ul class="wa-teaser__list">
							<?php foreach ( $teaser as $item ) : ?>
								<li><?php echo esc_html( $item ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>

			<div class="wa-group-hero__visual">
				<img src="<?php echo esc_url( $group['image'] ); ?>"
				     alt="<?php echo esc_attr( $group['name'] ); ?>"
				     width="320"
				     height="320"
				     loading="eager"
				     fetchpriority="high"
				     decoding="async"
				     itemprop="image"
				     class="wa-group-hero__image">
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/whatsapp', 'join-steps' ); ?>

	<section class="wa-group-trust">
		<div class="container wa-group-trust__inner">
			<p class="conversion-social-proof">
				<?php echo esc_html( guru_whatsapp_group_social_proof( $group ) ); ?>
			</p>

			<p class="conversion-urgency">
				<?php echo esc_html( guru_whatsapp_group_urgency( $group ) ); ?>
			</p>

			<ul class="wa-trust-badges">
				<li><?php esc_html_e( 'Sem cartão de crédito', 'guru-do-desconto' ); ?></li>
				<li><?php esc_html_e( 'Sem cadastro', 'guru-do-desconto' ); ?></li>
				<li><?php esc_html_e( 'Saia quando quiser', 'guru-do-desconto' ); ?></li>
				<li><?php esc_html_e( 'ML, Shopee e Amazon', 'guru-do-desconto' ); ?></li>
			</ul>

			<?php
			get_template_part(
				'template-parts/whatsapp',
				'cta-block',
				array(
					'group'     => $group,
					'placement' => 'mid',
					'show_hint' => true,
					'class'     => 'btn btn-whatsapp btn-lg btn-cta-primary btn-cta-secondary',
				)
			);
			?>
		</div>
	</section>
</article>

<div class="landing-sticky-cta landing-sticky-cta--always wa-group-sticky">
	<div class="landing-sticky-cta__inner">
		<p class="landing-sticky-cta__hint"><?php esc_html_e( 'Toque e depois confirme "Participar do grupo"', 'guru-do-desconto' ); ?></p>
		<a <?php echo guru_whatsapp_group_link_attrs( $group, 'sticky' ); ?> class="btn btn-whatsapp">
			<?php echo guru_whatsapp_icon_svg( 20 ); ?>
			<?php echo esc_html( guru_whatsapp_cta_label( $group, 'sticky' ) ); ?>
		</a>
	</div>
</div>
