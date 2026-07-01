<?php
/**
 * Como funciona — 3 passos para entrar nos grupos
 *
 * @package GuruDoDesconto
 */

$steps = array(
	array(
		'num'   => '1',
		'title' => __( 'Escolha seus nichos', 'guru-do-desconto' ),
		'desc'  => __( 'Casa, Tech, Kids, Moda e mais — veja qual grupo combina com o que você compra.', 'guru-do-desconto' ),
	),
	array(
		'num'   => '2',
		'title' => __( 'Marque um ou vários', 'guru-do-desconto' ),
		'desc'  => __( 'Use os checkboxes e entre em todos os grupos de uma vez, se quiser.', 'guru-do-desconto' ),
	),
	array(
		'num'   => '3',
		'title' => __( 'Entre pelo WhatsApp', 'guru-do-desconto' ),
		'desc'  => __( 'Toque em entrar, confirme no app e comece a receber ofertas do dia.', 'guru-do-desconto' ),
	),
);
?>

<section class="section landing-steps" id="como-funciona">
	<div class="container">
		<header class="section-header">
			<h2><?php esc_html_e( 'Como funciona', 'guru-do-desconto' ); ?></h2>
			<p><?php esc_html_e( 'Em menos de 1 minuto você já está recebendo promoções no celular.', 'guru-do-desconto' ); ?></p>
		</header>

		<ol class="landing-steps__grid">
			<?php foreach ( $steps as $step ) : ?>
				<li class="landing-steps__item">
					<span class="landing-steps__num" aria-hidden="true"><?php echo esc_html( $step['num'] ); ?></span>
					<h3><?php echo esc_html( $step['title'] ); ?></h3>
					<p><?php echo esc_html( $step['desc'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>

		<p class="landing-steps__cta">
			<a href="#grupos-whatsapp" class="btn btn-whatsapp">
				<?php esc_html_e( 'Ver os 7 grupos', 'guru-do-desconto' ); ?>
			</a>
		</p>
	</div>
</section>
