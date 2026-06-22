<?php
/**
 * Single Review template
 *
 * @package GuruDoDesconto
 */

get_header();

while ( have_posts() ) :
	the_post();

	$id        = get_the_ID();
	$price     = get_post_meta( $id, '_guru_price', true );
	$old       = get_post_meta( $id, '_guru_price_old', true );
	$rating    = get_post_meta( $id, '_guru_rating', true );
	$affiliate = get_post_meta( $id, '_guru_affiliate_link', true );
	$terms     = get_the_terms( $id, 'marketplace' );
	$mp_slug   = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->slug : '';
	$mp        = guru_marketplace_info( $mp_slug );

	$discount = '';
	if ( $price && $old && (float) $old > (float) $price ) {
		$discount = round( ( ( (float) $old - (float) $price ) / (float) $old ) * 100 );
	}
	?>

	<article class="review-single container" itemscope itemtype="https://schema.org/Review">
		<header class="review-single-header">
			<?php if ( $mp_slug ) : ?>
				<span class="review-badge <?php echo esc_attr( $mp['class'] ); ?>"><?php echo esc_html( $mp['label'] ); ?></span>
			<?php endif; ?>
			<h1 itemprop="name"><?php the_title(); ?></h1>
			<div class="review-meta">
				<?php echo guru_render_stars( $rating ); ?>
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" itemprop="datePublished">
					<?php echo esc_html( get_the_date() ); ?>
				</time>
			</div>
		</header>

		<div class="review-content">
			<div class="review-prose" itemprop="reviewBody">
				<?php the_content(); ?>
			</div>

			<aside class="review-sidebar">
				<div class="buy-box" itemprop="itemReviewed" itemscope itemtype="https://schema.org/Product">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'review-single', array( 'itemprop' => 'image' ) ); ?>
					<?php endif; ?>

					<h3 itemprop="name"><?php the_title(); ?></h3>

					<?php if ( $price ) : ?>
						<div class="review-price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
							<meta itemprop="priceCurrency" content="BRL">
							<span class="price-current" itemprop="price" content="<?php echo esc_attr( $price ); ?>">
								<?php echo esc_html( guru_format_price( $price ) ); ?>
							</span>
							<?php if ( $old ) : ?>
								<span class="price-old"><?php echo esc_html( guru_format_price( $old ) ); ?></span>
							<?php endif; ?>
							<?php if ( $discount ) : ?>
								<span class="discount-tag">-<?php echo esc_html( $discount ); ?>%</span>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( $affiliate ) : ?>
						<a <?php echo guru_affiliate_link_attrs( $affiliate ); ?> class="btn-affiliate">
							<?php esc_html_e( 'Comprar com Desconto', 'guru-do-desconto' ); ?>
						</a>
					<?php endif; ?>

					<p class="affiliate-disclaimer">
						<?php esc_html_e( 'Link de afiliado. O preço pode variar. Verifique na loja antes de comprar.', 'guru-do-desconto' ); ?>
					</p>
				</div>
			</aside>
		</div>
	</article>

	<?php
endwhile;

get_footer();
