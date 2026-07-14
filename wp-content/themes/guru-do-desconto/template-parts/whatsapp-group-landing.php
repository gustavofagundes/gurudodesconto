<?php
/**
 * Landing de um grupo WhatsApp — conversão limpa (v3).
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

<article class="wa-group-landing wa-group-landing--v3" itemscope itemtype="https://schema.org/WebPage">
	<section class="wa-hero-v3">
		<div class="container wa-hero-v3__inner">
			<img src="<?php echo esc_url( $group['image'] ); ?>"
			     alt="<?php echo esc_attr( $group['name'] . ' — Guru do Desconto' ); ?>"
			     width="112"
			     height="112"
			     loading="eager"
			     fetchpriority="high"
			     decoding="async"
			     itemprop="image"
			     class="wa-hero-v3__avatar">

			<p class="wa-hero-v3__brand"><?php echo esc_html( $group['name'] ); ?></p>
			<span class="wa-hero-v3__badge"><?php echo esc_html( '100%' ); ?> <?php esc_html_e( 'grátis · Sem spam', 'guru-do-desconto' ); ?></span>

			<h1 class="wa-hero-v3__title" itemprop="name">
				<?php echo esc_html( guru_whatsapp_group_hook( $group ) ); ?>
			</h1>

			<p class="wa-hero-v3__subtitle" itemprop="description">
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
					'class'     => 'btn btn-cta-hero',
				)
			);
			?>

			<ul class="wa-hero-v3__trust">
				<li><?php esc_html_e( 'Sem cadastro', 'guru-do-desconto' ); ?></li>
				<li><?php esc_html_e( 'Saia quando quiser', 'guru-do-desconto' ); ?></li>
				<li><?php echo esc_html( $group['tagline'] ); ?></li>
			</ul>
		</div>
	</section>

	<?php get_template_part( 'template-parts/whatsapp', 'join-steps' ); ?>

	<section class="wa-final-v3">
		<div class="container wa-final-v3__inner">
			<p class="wa-final-v3__proof">
				<?php echo esc_html( guru_whatsapp_group_social_proof( $group ) ); ?>
			</p>

			<?php if ( $teaser ) : ?>
				<ul class="wa-final-v3__teaser">
					<?php foreach ( $teaser as $item ) : ?>
						<li><?php echo esc_html( $item ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<?php
			get_template_part(
				'template-parts/whatsapp',
				'cta-block',
				array(
					'group'     => $group,
					'placement' => 'mid',
					'show_hint' => false,
					'class'     => 'btn btn-whatsapp btn-lg btn-cta-primary',
				)
			);
			?>

			<p class="wa-final-v3__urgency">
				<?php echo esc_html( guru_whatsapp_group_urgency( $group ) ); ?>
			</p>
		</div>
	</section>
</article>

<div class="landing-sticky-cta landing-sticky-cta--always wa-group-sticky">
	<div class="landing-sticky-cta__inner">
		<a <?php echo guru_whatsapp_group_link_attrs( $group, 'sticky' ); ?> class="btn btn-whatsapp <?php echo esc_attr( guru_whatsapp_btn_id( $group ) ); ?>">
			<?php echo guru_whatsapp_icon_svg( 20 ); ?>
			<?php echo esc_html( guru_whatsapp_cta_label( $group, 'sticky' ) ); ?>
		</a>
	</div>
</div>
