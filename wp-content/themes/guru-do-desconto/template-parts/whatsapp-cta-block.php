<?php
/**
 * Bloco CTA WhatsApp com instrução de entrada no grupo.
 *
 * @package GuruDoDesconto
 *
 * @var array  $args['group']     Grupo WhatsApp.
 * @var string $args['placement'] landing|sticky|card|mid.
 * @var string $args['class']     Classes extras no botão.
 */

$group     = $args['group'] ?? null;
$placement = $args['placement'] ?? 'landing';
$class     = $args['class'] ?? 'btn btn-whatsapp btn-lg btn-cta-primary';
$show_hint = ! empty( $args['show_hint'] );

if ( ! $group ) {
	return;
}
?>

<div class="wa-cta-block">
	<a <?php echo guru_whatsapp_group_link_attrs( $group, $placement ); ?> class="<?php echo esc_attr( $class ); ?>">
		<?php echo guru_whatsapp_icon_svg( 22 ); ?>
		<?php echo esc_html( guru_whatsapp_cta_label( $group, $placement === 'sticky' ? 'sticky' : ( $placement === 'card' ? 'card' : 'primary' ) ) ); ?>
	</a>
	<?php if ( $show_hint ) : ?>
		<p class="cta-join-hint">
			<span class="cta-join-hint__arrow" aria-hidden="true">↓</span>
			<?php echo esc_html( guru_whatsapp_join_hint() ); ?>
		</p>
	<?php endif; ?>
</div>
