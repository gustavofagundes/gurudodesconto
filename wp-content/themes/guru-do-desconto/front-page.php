<?php
/**
 * Front page template
 *
 * @package GuruDoDesconto
 */

get_header();

$whatsapp = guru_whatsapp_link();
$wa_msg   = get_theme_mod( 'guru_whatsapp_message', __( 'Entrar no Grupo de Promoções', 'guru-do-desconto' ) );
?>

<section class="hero">
	<div class="container hero-grid">
		<div class="hero-content">
			<span class="hero-badge"><?php esc_html_e( 'Economia e Alegria', 'guru-do-desconto' ); ?></span>
			<h1><?php esc_html_e( 'Grupo de', 'guru-do-desconto' ); ?> <span><?php esc_html_e( 'Promoções no WhatsApp', 'guru-do-desconto' ); ?></span> <?php esc_html_e( '— Mercado Livre, Shopee e Amazon', 'guru-do-desconto' ); ?></h1>
			<p><?php echo esc_html( get_theme_mod( 'guru_site_description', guru_default_seo_description() ) ); ?></p>
			<div class="hero-actions">
				<a href="<?php echo esc_url( $whatsapp ); ?>" class="btn btn-whatsapp" target="_blank" rel="noopener">
					<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
					<?php echo esc_html( $wa_msg ); ?>
				</a>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'review' ) ); ?>" class="btn btn-outline">
					<?php esc_html_e( 'Ver Reviews', 'guru-do-desconto' ); ?>
				</a>
			</div>
		</div>
		<div class="hero-image">
			<img src="<?php echo esc_url( GURU_THEME_URI . '/assets/images/Guru_sem_fundo.png' ); ?>"
			     alt="<?php esc_attr_e( 'Guru do Desconto — grupo de promoções no WhatsApp', 'guru-do-desconto' ); ?>"
			     width="400" height="400"
			     loading="eager">
		</div>
	</div>
</section>

<?php get_template_part( 'template-parts/whatsapp', 'faq' ); ?>

<section class="section marketplaces" id="promocoes">
	<div class="container">
		<div class="section-header">
			<h2><?php esc_html_e( 'Promoções nas Melhores Lojas', 'guru-do-desconto' ); ?></h2>
			<p><?php esc_html_e( 'Acompanhamos ofertas do Mercado Livre, Shopee e Amazon para você economizar.', 'guru-do-desconto' ); ?></p>
		</div>

		<div class="marketplace-grid">
			<?php
			$stores = array(
				'mercado-livre' => array( 'class' => 'ml', 'icon' => 'ML', 'name' => 'Mercado Livre', 'desc' => __( 'Eletrônicos, casa, moda e muito mais com frete grátis e cupons exclusivos.', 'guru-do-desconto' ), 'btn' => __( 'Ver Reviews ML', 'guru-do-desconto' ) ),
				'shopee'        => array( 'class' => 'shopee', 'icon' => 'S', 'name' => 'Shopee', 'desc' => __( 'Ofertas relâmpago, cashback e produtos importados com preços imbatíveis.', 'guru-do-desconto' ), 'btn' => __( 'Ver Reviews Shopee', 'guru-do-desconto' ) ),
				'amazon'        => array( 'class' => 'amazon', 'icon' => 'A', 'name' => 'Amazon', 'desc' => __( 'Prime Day, Black Friday e promoções diárias em milhares de categorias.', 'guru-do-desconto' ), 'btn' => __( 'Ver Reviews Amazon', 'guru-do-desconto' ) ),
			);
			foreach ( $stores as $slug => $store ) :
				$link = get_term_link( $slug, 'marketplace' );
				if ( is_wp_error( $link ) ) {
					$link = get_post_type_archive_link( 'review' );
				}
				?>
			<article class="marketplace-card <?php echo esc_attr( $store['class'] ); ?>">
				<div class="marketplace-icon" aria-hidden="true"><?php echo esc_html( $store['icon'] ); ?></div>
				<h3><?php echo esc_html( $store['name'] ); ?></h3>
				<p><?php echo esc_html( $store['desc'] ); ?></p>
				<a href="<?php echo esc_url( $link ); ?>" class="btn btn-outline"><?php echo esc_html( $store['btn'] ); ?></a>
			</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section" id="reviews">
	<div class="container">
		<div class="section-header">
			<h2><?php esc_html_e( 'Reviews do Guru', 'guru-do-desconto' ); ?></h2>
			<p><?php esc_html_e( 'Análises honestas para você comprar com confiança — com link de afiliado e melhor preço.', 'guru-do-desconto' ); ?></p>
		</div>

		<div class="reviews-grid">
			<?php
			$reviews = new WP_Query( array(
				'post_type'      => 'review',
				'posts_per_page' => 6,
				'post_status'    => 'publish',
			) );

			if ( $reviews->have_posts() ) :
				while ( $reviews->have_posts() ) :
					$reviews->the_post();
					get_template_part( 'template-parts/review', 'card' );
				endwhile;
				wp_reset_postdata();
			else :
				?>
				<p><?php esc_html_e( 'Em breve novos reviews! Enquanto isso, entre no grupo do WhatsApp.', 'guru-do-desconto' ); ?></p>
			<?php endif; ?>
		</div>

		<p style="text-align:center; margin-top:2rem;">
			<a href="<?php echo esc_url( get_post_type_archive_link( 'review' ) ); ?>" class="btn btn-outline">
				<?php esc_html_e( 'Ver Todos os Reviews', 'guru-do-desconto' ); ?>
			</a>
		</p>
	</div>
</section>

<section class="whatsapp-band">
	<div class="container">
		<h2><?php esc_html_e( 'Não perca nenhuma promoção!', 'guru-do-desconto' ); ?></h2>
		<p><?php esc_html_e( 'Entre no nosso grupo exclusivo do WhatsApp e receba as melhores ofertas em tempo real.', 'guru-do-desconto' ); ?></p>
		<a href="<?php echo esc_url( $whatsapp ); ?>" class="btn btn-whatsapp" target="_blank" rel="noopener">
			<?php echo esc_html( $wa_msg ); ?>
		</a>
	</div>
</section>

<?php get_footer(); ?>
