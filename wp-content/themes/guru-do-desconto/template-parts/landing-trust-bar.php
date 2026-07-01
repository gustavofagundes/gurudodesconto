<?php
/**
 * Barra de confiança — landing page
 *
 * @package GuruDoDesconto
 */

$items = array(
	array(
		'icon'  => '✓',
		'label' => __( '100% gratuito', 'guru-do-desconto' ),
	),
	array(
		'icon'  => '🛒',
		'label' => __( 'Mercado Livre, Shopee e Amazon', 'guru-do-desconto' ),
	),
	array(
		'icon'  => '7',
		'label' => __( '7 grupos por nicho', 'guru-do-desconto' ),
	),
	array(
		'icon'  => '🔕',
		'label' => __( 'Sem spam — saia quando quiser', 'guru-do-desconto' ),
	),
);
?>

<div class="landing-trust-bar" aria-label="<?php esc_attr_e( 'Por que entrar nos grupos', 'guru-do-desconto' ); ?>">
	<div class="container">
		<ul class="landing-trust-bar__list">
			<?php foreach ( $items as $item ) : ?>
				<li class="landing-trust-bar__item">
					<span class="landing-trust-bar__icon" aria-hidden="true"><?php echo esc_html( $item['icon'] ); ?></span>
					<span><?php echo esc_html( $item['label'] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
