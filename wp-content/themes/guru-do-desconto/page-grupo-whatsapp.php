<?php
/**
 * Template: landing SEO — Grupo de Promoções no WhatsApp
 *
 * @package GuruDoDesconto
 */

get_header();

$geral    = function_exists( 'guru_get_whatsapp_group' ) ? guru_get_whatsapp_group( 'geral' ) : null;
$wa_msg   = get_theme_mod( 'guru_whatsapp_message', __( 'Entrar no Grupo de Promoções', 'guru-do-desconto' ) );
$btn_id   = $geral ? guru_whatsapp_btn_id( $geral ) : 'btn-geral';
$btn_attrs = $geral
	? guru_whatsapp_group_link_attrs( $geral, 'landing' )
	: guru_whatsapp_link_attrs( 'landing' );
?>

<section class="section whatsapp-landing">
	<div class="container">
		<header class="section-header">
			<h1><?php esc_html_e( 'Grupo de Promoções no WhatsApp', 'guru-do-desconto' ); ?></h1>
			<p><?php echo esc_html( guru_default_seo_description() ); ?></p>
		</header>

		<div class="review-prose">
			<?php
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;
			?>
		</div>

		<div class="hero-actions" style="justify-content:center; margin: 2rem 0;">
			<a <?php echo $btn_attrs; ?> class="btn btn-whatsapp <?php echo esc_attr( $btn_id ); ?>">
				<?php echo esc_html( $wa_msg ); ?>
			</a>
		</div>

		<?php get_template_part( 'template-parts/whatsapp', 'faq' ); ?>
	</div>
</section>

<?php get_footer(); ?>
