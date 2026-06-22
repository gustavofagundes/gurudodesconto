<?php
/**
 * Main template fallback
 *
 * @package GuruDoDesconto
 */

get_header();
?>

<section class="section">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="reviews-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					if ( 'review' === get_post_type() ) {
						get_template_part( 'template-parts/review', 'card' );
					} else {
						?>
						<article class="review-card">
							<div class="review-card-body">
								<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<?php the_excerpt(); ?>
							</div>
						</article>
						<?php
					}
				endwhile;
				?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'Nenhum conteúdo encontrado.', 'guru-do-desconto' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
