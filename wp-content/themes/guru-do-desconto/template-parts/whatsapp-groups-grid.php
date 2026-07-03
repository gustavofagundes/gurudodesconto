<?php
/**
 * Grid de grupos WhatsApp por nicho
 *
 * @package GuruDoDesconto
 */

$groups = guru_whatsapp_groups();
if ( ! $groups ) {
	return;
}
?>

<section class="section whatsapp-groups" id="grupos-whatsapp">
	<div class="container">
		<header class="section-header">
			<h2><?php esc_html_e( 'Escolha seu grupo de promoções', 'guru-do-desconto' ); ?></h2>
			<p><?php esc_html_e( 'Toque no nicho que combina com você — ou marque vários e entre em todos de uma vez.', 'guru-do-desconto' ); ?></p>
		</header>

		<nav class="group-chips" aria-label="<?php esc_attr_e( 'Atalhos para grupos', 'guru-do-desconto' ); ?>">
			<?php foreach ( $groups as $group ) : ?>
				<a href="<?php echo esc_url( guru_whatsapp_group_landing_url( $group ) ); ?>" class="group-chip">
					<?php echo esc_html( $group['name'] ); ?>
				</a>
			<?php endforeach; ?>
		</nav>

		<div class="whatsapp-groups-picker" data-whatsapp-picker>
			<div class="whatsapp-groups-toolbar">
				<p class="whatsapp-groups-toolbar__hint">
					<?php esc_html_e( 'Use os checkboxes para selecionar vários grupos e abrir todos em abas separadas.', 'guru-do-desconto' ); ?>
				</p>
				<div class="whatsapp-groups-toolbar__actions">
					<button type="button" class="btn btn-outline btn-sm" data-wa-select-all>
						<?php esc_html_e( 'Marcar todos', 'guru-do-desconto' ); ?>
					</button>
					<button type="button" class="btn btn-outline btn-sm" data-wa-clear-all disabled>
						<?php esc_html_e( 'Desmarcar todos', 'guru-do-desconto' ); ?>
					</button>
					<span class="whatsapp-groups-toolbar__count" data-wa-count aria-live="polite">
						<?php esc_html_e( 'Nenhum grupo selecionado', 'guru-do-desconto' ); ?>
					</span>
				</div>
			</div>

			<div class="whatsapp-groups-grid">
				<?php
				foreach ( $groups as $index => $group ) :
					$featured     = ! empty( $group['featured'] );
					$card_class   = 'whatsapp-group-card' . ( $featured ? ' whatsapp-group-card--featured' : '' );
					$loading      = $index < 3 ? 'eager' : 'lazy';
					$group_url    = guru_whatsapp_group_url( $group, 'bulk' );
					$landing_url  = guru_whatsapp_group_landing_url( $group );
					$utm_content  = 'whatsapp_bulk_' . $group['slug'];
					?>
				<article id="grupo-<?php echo esc_attr( $group['slug'] ); ?>" class="<?php echo esc_attr( $card_class ); ?>" data-wa-card itemscope itemtype="https://schema.org/Organization">
					<meta itemprop="name" content="<?php echo esc_attr( $group['name'] ); ?>">

					<label class="whatsapp-group-card__select" title="<?php echo esc_attr( sprintf( __( 'Selecionar %s', 'guru-do-desconto' ), $group['name'] ) ); ?>">
						<input type="checkbox"
						       class="whatsapp-group-card__checkbox"
						       data-wa-group
						       data-url="<?php echo esc_url( $group_url ); ?>"
						       data-slug="<?php echo esc_attr( $group['slug'] ); ?>"
						       data-name="<?php echo esc_attr( $group['name'] ); ?>"
						       data-utm-content="<?php echo esc_attr( $utm_content ); ?>">
						<span class="whatsapp-group-card__checkmark" aria-hidden="true"></span>
						<span class="screen-reader-text"><?php echo esc_html( sprintf( __( 'Selecionar grupo %s', 'guru-do-desconto' ), $group['name'] ) ); ?></span>
					</label>

					<?php if ( $featured ) : ?>
						<span class="whatsapp-group-badge"><?php esc_html_e( 'Mais popular', 'guru-do-desconto' ); ?></span>
					<?php endif; ?>

					<a href="<?php echo esc_url( $landing_url ); ?>" class="whatsapp-group-card__link">
						<div class="whatsapp-group-card__media">
							<img src="<?php echo esc_url( $group['image'] ); ?>"
							     alt="<?php echo esc_attr( sprintf( __( 'Grupo de promoções %s no WhatsApp — Guru do Desconto', 'guru-do-desconto' ), $group['name'] ) ); ?>"
							     width="640"
							     height="640"
							     loading="<?php echo esc_attr( $loading ); ?>"
							     decoding="async">
						</div>
						<div class="whatsapp-group-card__body">
							<h3><?php echo esc_html( $group['name'] ); ?></h3>
							<p class="whatsapp-group-card__tagline"><?php echo esc_html( $group['tagline'] ); ?></p>
							<p class="whatsapp-group-card__desc"><?php echo esc_html( $group['description'] ); ?></p>
						</div>
					</a>
					<div class="whatsapp-group-card__footer">
						<a <?php echo guru_whatsapp_group_link_attrs( $group, 'card' ); ?> class="btn btn-whatsapp btn-sm">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
							<?php esc_html_e( 'Entrar no grupo', 'guru-do-desconto' ); ?>
						</a>
					</div>
				</article>
				<?php endforeach; ?>
			</div>

			<div class="whatsapp-groups-bulk-bar" data-wa-bulk-bar hidden>
				<div class="whatsapp-groups-bulk-bar__inner">
					<button type="button" class="btn btn-whatsapp" data-wa-bulk-join disabled>
						<?php esc_html_e( 'Entrar nos grupos selecionados', 'guru-do-desconto' ); ?>
					</button>
				</div>
			</div>

			<div class="whatsapp-groups-fallback" data-wa-fallback hidden>
				<p><?php esc_html_e( 'Algumas abas foram bloqueadas pelo navegador. Toque em cada link abaixo:', 'guru-do-desconto' ); ?></p>
				<ul class="whatsapp-groups-fallback__links" data-wa-fallback-links></ul>
			</div>
		</div>

		<div class="whatsapp-groups-seo review-prose">
			<h2><?php esc_html_e( 'Grupos de cupons e descontos no WhatsApp — por categoria', 'guru-do-desconto' ); ?></h2>
			<p>
				<?php esc_html_e( 'O Guru do Desconto organizou grupos de promoções no WhatsApp por nicho para você receber só o que importa: ofertas de casa, moda feminina, infantil, tecnologia, achadinhos até R$ 50, moda masculina ou um grupo geral com tudo.', 'guru-do-desconto' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'Todos os grupos são gratuitos, com alertas diários do Mercado Livre, Shopee e Amazon. Escolha um ou entre em vários — sem spam, só economia de verdade.', 'guru-do-desconto' ); ?>
			</p>
		</div>
	</div>
</section>
