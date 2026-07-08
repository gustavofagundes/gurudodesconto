<?php
/**
 * Template: Achadinhos do dia
 *
 * @package GuruDoDesconto
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>

	<article class="achadinhos-page container" id="achadinhos-do-dia">
		<header class="achadinhos-page__header">
			<p class="achadinhos-page__badge"><?php esc_html_e( 'Atualizado diariamente', 'guru-do-desconto' ); ?></p>
			<h1><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="achadinhos-page__intro"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</header>

		<div class="achadinhos-page__content review-prose">
			<?php the_content(); ?>
		</div>

		<aside class="achadinhos-page__cta">
			<h2><?php esc_html_e( 'Quer receber achadinhos no WhatsApp?', 'guru-do-desconto' ); ?></h2>
			<p><?php esc_html_e( 'Entre no grupo gratuito do Guru do Desconto e receba ofertas selecionadas todo dia.', 'guru-do-desconto' ); ?></p>
			<a href="<?php echo esc_url( guru_whatsapp_link() ); ?>" class="btn-affiliate" target="_blank" rel="noopener">
				<?php esc_html_e( 'Entrar no grupo grátis', 'guru-do-desconto' ); ?>
			</a>
		</aside>
	</article>

	<?php
endwhile;

get_footer();
