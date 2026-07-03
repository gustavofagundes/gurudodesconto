<?php
/**
 * Landing de um grupo WhatsApp por nicho
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
$others   = array_filter(
	guru_whatsapp_groups(),
	function ( $item ) use ( $group ) {
		return ( $item['slug'] ?? '' ) !== ( $group['slug'] ?? '' );
	}
);
?>

<article class="wa-group-landing" itemscope itemtype="https://schema.org/WebPage">
	<section class="wa-group-hero">
		<div class="container wa-group-hero__grid">
			<div class="wa-group-hero__content">
				<span class="hero-badge"><?php esc_html_e( '100% grátis', 'guru-do-desconto' ); ?></span>
				<h1 itemprop="name">
					<?php
					printf(
						/* translators: %s: group name */
						esc_html__( 'Grupo %s no WhatsApp', 'guru-do-desconto' ),
						esc_html( $group['name'] )
					);
					?>
				</h1>
				<p class="wa-group-hero__tagline"><?php echo esc_html( $group['tagline'] ); ?></p>
				<p class="wa-group-hero__desc" itemprop="description"><?php echo esc_html( $group['description'] ); ?></p>

				<?php if ( $benefits ) : ?>
					<ul class="wa-group-benefits">
						<?php foreach ( $benefits as $benefit ) : ?>
							<li><?php echo esc_html( $benefit ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<div class="wa-group-hero__actions">
					<a <?php echo guru_whatsapp_group_link_attrs( $group, 'landing' ); ?> class="btn btn-whatsapp btn-lg">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
						<?php esc_html_e( 'Entrar no grupo no WhatsApp', 'guru-do-desconto' ); ?>
					</a>
				</div>
				<p class="hero-microcopy">
					<?php esc_html_e( 'Mercado Livre, Shopee e Amazon · Sem spam · Saia quando quiser', 'guru-do-desconto' ); ?>
				</p>
			</div>
			<div class="wa-group-hero__image">
				<img src="<?php echo esc_url( $group['image'] ); ?>"
				     alt="<?php echo esc_attr( sprintf( __( 'Grupo %s — Guru do Desconto', 'guru-do-desconto' ), $group['name'] ) ); ?>"
				     width="420"
				     height="420"
				     loading="eager"
				     fetchpriority="high"
				     decoding="async"
				     itemprop="image">
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/landing', 'trust-bar' ); ?>

	<section class="section wa-group-steps">
		<div class="container">
			<header class="section-header">
				<h2><?php esc_html_e( 'Como entrar', 'guru-do-desconto' ); ?></h2>
			</header>
			<ol class="landing-steps__grid wa-group-steps__grid">
				<li class="landing-steps__item">
					<span class="landing-steps__num" aria-hidden="true">1</span>
					<h3><?php esc_html_e( 'Toque no botão verde', 'guru-do-desconto' ); ?></h3>
					<p><?php esc_html_e( 'O link abre o WhatsApp no seu celular ou no computador.', 'guru-do-desconto' ); ?></p>
				</li>
				<li class="landing-steps__item">
					<span class="landing-steps__num" aria-hidden="true">2</span>
					<h3><?php esc_html_e( 'Confirme a entrada', 'guru-do-desconto' ); ?></h3>
					<p><?php esc_html_e( 'Toque em "Entrar no grupo" dentro do WhatsApp.', 'guru-do-desconto' ); ?></p>
				</li>
				<li class="landing-steps__item">
					<span class="landing-steps__num" aria-hidden="true">3</span>
					<h3><?php esc_html_e( 'Receba as ofertas', 'guru-do-desconto' ); ?></h3>
					<p><?php esc_html_e( 'Pronto! Promoções do seu nicho direto no celular.', 'guru-do-desconto' ); ?></p>
				</li>
			</ol>
		</div>
	</section>

	<section class="section wa-group-faq">
		<div class="container">
			<header class="section-header">
				<h2><?php esc_html_e( 'Dúvidas sobre este grupo', 'guru-do-desconto' ); ?></h2>
			</header>
			<div class="faq-list wa-group-faq__list">
				<?php foreach ( guru_whatsapp_group_faq_items( $group ) as $item ) : ?>
					<details class="faq-item">
						<summary><?php echo esc_html( $item['question'] ); ?></summary>
						<div class="faq-answer">
							<p><?php echo esc_html( $item['answer'] ); ?></p>
						</div>
					</details>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php if ( $others ) : ?>
	<section class="section wa-group-others">
		<div class="container">
			<header class="section-header">
				<h2><?php esc_html_e( 'Outros grupos', 'guru-do-desconto' ); ?></h2>
				<p><?php esc_html_e( 'Entre em quantos nichos quiser — todos são gratuitos.', 'guru-do-desconto' ); ?></p>
			</header>
			<div class="group-chips">
				<?php foreach ( $others as $other ) : ?>
					<a href="<?php echo esc_url( guru_whatsapp_group_landing_url( $other ) ); ?>" class="group-chip">
						<?php echo esc_html( $other['name'] ); ?>
					</a>
				<?php endforeach; ?>
				<a href="<?php echo esc_url( home_url( '/#grupos-whatsapp' ) ); ?>" class="group-chip">
					<?php esc_html_e( 'Ver todos', 'guru-do-desconto' ); ?>
				</a>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<section class="whatsapp-band">
		<div class="container">
			<h2><?php echo esc_html( $group['name'] ); ?></h2>
			<p><?php echo esc_html( $group['tagline'] ); ?> — <?php esc_html_e( 'entre agora e comece a economizar.', 'guru-do-desconto' ); ?></p>
			<a <?php echo guru_whatsapp_group_link_attrs( $group, 'band' ); ?> class="btn btn-whatsapp">
				<?php esc_html_e( 'Entrar no grupo no WhatsApp', 'guru-do-desconto' ); ?>
			</a>
		</div>
	</section>
</article>

<div class="landing-sticky-cta wa-group-sticky" data-wa-group-sticky>
	<a <?php echo guru_whatsapp_group_link_attrs( $group, 'sticky' ); ?> class="btn btn-whatsapp">
		<?php esc_html_e( 'Entrar no grupo grátis', 'guru-do-desconto' ); ?>
	</a>
</div>
