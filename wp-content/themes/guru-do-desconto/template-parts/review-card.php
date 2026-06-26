<?php
/**
 * Review card template part
 *
 * @package GuruDoDesconto
 */

$id        = get_the_ID();
$price     = get_post_meta( $id, '_guru_price', true );
$old       = get_post_meta( $id, '_guru_price_old', true );
$rating    = get_post_meta( $id, '_guru_rating', true );
$affiliate = get_post_meta( $id, '_guru_affiliate_link', true );

$discount = '';
if ( $price && $old && (float) $old > (float) $price ) {
	$discount = round( ( ( (float) $old - (float) $price ) / (float) $old ) * 100 );
}
?>

<article class="review-card" itemscope itemtype="https://schema.org/Product">
	<?php
	$image_url = guru_get_review_image_url( $id, 'review-card' );
	if ( $image_url ) :
		?>
		<a href="<?php the_permalink(); ?>" class="review-card-image">
			<img src="<?php echo esc_url( $image_url ); ?>"
			     alt="<?php echo esc_attr( get_the_title() ); ?>"
			     width="600" height="375"
			     loading="lazy"
			     decoding="async"
			     itemprop="image">
		</a>
	<?php endif; ?>

	<div class="review-card-body">
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
			<a <?php echo guru_affiliate_link_attrs( $affiliate, 'card' ); ?> class="btn-affiliate">
				<?php esc_html_e( 'Ver Promoção', 'guru-do-desconto' ); ?>
			</a>
		<?php else : ?>
			<a href="<?php the_permalink(); ?>" class="btn-affiliate">
				<?php esc_html_e( 'Ler Review', 'guru-do-desconto' ); ?>
			</a>
		<?php endif; ?>
	</div>
</article>
