<?php
/**
 * Review card template part
 *
 * @package GuruDoDesconto
 */

$id       = get_the_ID();
$price    = get_post_meta( $id, '_guru_price', true );
$old      = get_post_meta( $id, '_guru_price_old', true );
$rating   = get_post_meta( $id, '_guru_rating', true );
$affiliate = get_post_meta( $id, '_guru_affiliate_link', true );
$terms    = get_the_terms( $id, 'marketplace' );
$mp_slug  = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->slug : '';
$mp       = guru_marketplace_info( $mp_slug );

$discount = '';
if ( $price && $old && (float) $old > (float) $price ) {
	$discount = round( ( ( (float) $old - (float) $price ) / (float) $old ) * 100 );
}
?>

<article class="review-card" itemscope itemtype="https://schema.org/Product">
	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" class="review-card-image">
			<?php the_post_thumbnail( 'review-card', array( 'itemprop' => 'image', 'loading' => 'lazy' ) ); ?>
		</a>
	<?php endif; ?>

	<div class="review-card-body">
		<?php if ( $mp_slug ) : ?>
			<span class="review-badge <?php echo esc_attr( $mp['class'] ); ?>"><?php echo esc_html( $mp['label'] ); ?></span>
		<?php endif; ?>

		<h3 itemprop="name">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<div class="review-meta">
			<?php echo guru_render_stars( $rating ); ?>
		</div>

		<p itemprop="description"><?php echo esc_html( get_the_excerpt() ); ?></p>

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
				<?php esc_html_e( 'Ver Promoção', 'guru-do-desconto' ); ?>
			</a>
		<?php else : ?>
			<a href="<?php the_permalink(); ?>" class="btn-affiliate">
				<?php esc_html_e( 'Ler Review', 'guru-do-desconto' ); ?>
			</a>
		<?php endif; ?>
	</div>
</article>
