<?php
/**
 * Navegação por âncoras — landing page (home)
 *
 * @package GuruDoDesconto
 */

$sections = array(
	array(
		'id'    => 'grupos-whatsapp',
		'label' => __( 'Grupos', 'guru-do-desconto' ),
	),
	array(
		'id'    => 'como-funciona',
		'label' => __( 'Como funciona', 'guru-do-desconto' ),
	),
	array(
		'id'    => 'grupo-whatsapp',
		'label' => __( 'Dúvidas', 'guru-do-desconto' ),
	),
	array(
		'id'    => 'reviews',
		'label' => __( 'Reviews', 'guru-do-desconto' ),
	),
);
?>

<nav class="landing-section-nav" aria-label="<?php esc_attr_e( 'Seções da página', 'guru-do-desconto' ); ?>">
	<div class="container landing-section-nav__inner">
		<div class="landing-section-nav__links">
			<?php foreach ( $sections as $section ) : ?>
				<a href="#<?php echo esc_attr( $section['id'] ); ?>"
				   class="landing-section-nav__link"
				   data-landing-section="<?php echo esc_attr( $section['id'] ); ?>">
					<?php echo esc_html( $section['label'] ); ?>
				</a>
			<?php endforeach; ?>
		</div>
		<a href="#grupos-whatsapp" class="landing-section-nav__cta btn btn-whatsapp btn-sm">
			<?php esc_html_e( 'Entrar grátis', 'guru-do-desconto' ); ?>
		</a>
	</div>
</nav>
