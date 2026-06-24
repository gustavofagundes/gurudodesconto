<?php
/**
 * Review archive template
 *
 * @package GuruDoDesconto
 */

get_header();
?>

<div class="archive-header container">
	<h1><?php esc_html_e( 'Todos os Reviews', 'guru-do-desconto' ); ?></h1>
	<p><?php esc_html_e( 'Análises comparativas das melhores promoções.', 'guru-do-desconto' ); ?></p>
</div>

<section class="section">
	<div class="container">
		<div class="reviews-grid">
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/review', 'card' );
				endwhile;
			else :
				?>
				<p><?php esc_html_e( 'Nenhum review encontrado.', 'guru-do-desconto' ); ?></p>
			<?php endif; ?>
		</div>

		<?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
	</div>
</section>

<?php get_footer(); ?>
