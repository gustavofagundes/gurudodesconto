<?php
/**
 * Review archive template
 *
 * @package GuruDoDesconto
 */

get_header();
?>

<div class="archive-header container">
	<h1>
		<?php
		if ( is_tax( 'marketplace' ) ) {
			single_term_title( __( 'Reviews — ', 'guru-do-desconto' ) );
		} else {
			esc_html_e( 'Todos os Reviews', 'guru-do-desconto' );
		}
		?>
	</h1>
	<p><?php esc_html_e( 'Análises comparativas das melhores promoções.', 'guru-do-desconto' ); ?></p>
</div>

<div class="container archive-filters" role="navigation" aria-label="<?php esc_attr_e( 'Filtrar por loja', 'guru-do-desconto' ); ?>">
	<a href="<?php echo esc_url( get_post_type_archive_link( 'review' ) ); ?>"
	   class="filter-btn <?php echo is_post_type_archive( 'review' ) && ! is_tax() ? 'active' : ''; ?>">
		<?php esc_html_e( 'Todos', 'guru-do-desconto' ); ?>
	</a>
	<?php
	$marketplaces = array( 'mercado-livre', 'shopee', 'amazon' );
	foreach ( $marketplaces as $slug ) {
		$info = guru_marketplace_info( $slug );
		$link = get_term_link( $slug, 'marketplace' );
		if ( is_wp_error( $link ) ) {
			continue;
		}
		$active = is_tax( 'marketplace', $slug ) ? 'active' : '';
		printf(
			'<a href="%s" class="filter-btn %s">%s</a>',
			esc_url( $link ),
			esc_attr( $active ),
			esc_html( $info['label'] )
		);
	}
	?>
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
				<p><?php esc_html_e( 'Nenhum review encontrado nesta categoria.', 'guru-do-desconto' ); ?></p>
			<?php endif; ?>
		</div>

		<?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
	</div>
</section>

<?php get_footer(); ?>
