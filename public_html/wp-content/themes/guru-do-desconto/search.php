<?php
/**
 * Search results template
 *
 * @package GuruDoDesconto
 */

get_header();
?>

<section class="section">
	<div class="container">
		<header class="archive-header">
			<h1>
				<?php
				printf(
					/* translators: %s: search query */
					esc_html__( 'Resultados para: %s', 'guru-do-desconto' ),
					'<span>' . esc_html( get_search_query() ) . '</span>'
				);
				?>
			</h1>
		</header>

		<div class="reviews-grid">
			<?php if ( have_posts() ) : ?>
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
			<?php else : ?>
				<p><?php esc_html_e( 'Nenhum resultado encontrado. Tente outra busca ou entre no grupo do WhatsApp.', 'guru-do-desconto' ); ?></p>
			<?php endif; ?>
		</div>

		<?php the_posts_pagination(); ?>
	</div>
</section>

<?php get_footer(); ?>
