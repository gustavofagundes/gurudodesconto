<?php
/**
 * Grid de grupos WhatsApp — foco em conversão.
 *
 * @package GuruDoDesconto
 */

$groups = guru_whatsapp_groups();
if ( ! $groups ) {
	return;
}
?>

<section class="section whatsapp-groups whatsapp-groups--conversion" id="grupos-whatsapp">
	<div class="container">
		<header class="section-header">
			<h2><?php esc_html_e( 'Escolha seu grupo e entre grátis', 'guru-do-desconto' ); ?></h2>
			<p><?php esc_html_e( 'Toque no botão verde e confirme "Participar do grupo" no WhatsApp.', 'guru-do-desconto' ); ?></p>
		</header>

		<div class="whatsapp-groups-grid whatsapp-groups-grid--conversion">
			<?php
			foreach ( $groups as $index => $group ) :
				$featured   = ! empty( $group['featured'] );
				$card_class = 'whatsapp-group-card whatsapp-group-card--conversion whatsapp-group-card--v2' . ( $featured ? ' whatsapp-group-card--featured' : '' );
				$loading    = $index < 3 ? 'eager' : 'lazy';
				$teaser     = guru_whatsapp_group_teaser_items( $group );
				?>
			<article id="grupo-<?php echo esc_attr( $group['slug'] ); ?>" class="<?php echo esc_attr( $card_class ); ?>">
				<?php if ( $featured ) : ?>
					<span class="whatsapp-group-badge"><?php esc_html_e( 'Mais popular', 'guru-do-desconto' ); ?></span>
				<?php endif; ?>

				<div class="whatsapp-group-card__media">
					<img src="<?php echo esc_url( $group['image'] ); ?>"
					     alt="<?php echo esc_attr( $group['name'] ); ?>"
					     width="640"
					     height="640"
					     loading="<?php echo esc_attr( $loading ); ?>"
					     decoding="async">
				</div>
				<div class="whatsapp-group-card__body">
					<h3><?php echo esc_html( $group['name'] ); ?></h3>
					<p class="whatsapp-group-card__tagline"><?php echo esc_html( $group['tagline'] ); ?></p>
					<?php if ( $teaser ) : ?>
						<ul class="whatsapp-group-card__teaser">
							<?php foreach ( array_slice( $teaser, 0, 2 ) as $item ) : ?>
								<li><?php echo esc_html( $item ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
				<div class="whatsapp-group-card__footer">
					<a <?php echo guru_whatsapp_group_link_attrs( $group, 'card' ); ?> class="btn btn-whatsapp btn-sm">
						<?php echo guru_whatsapp_icon_svg( 18 ); ?>
						<?php echo esc_html( guru_whatsapp_cta_label( $group, 'card' ) ); ?>
					</a>
				</div>
			</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php get_template_part( 'template-parts/whatsapp', 'join-steps' ); ?>
