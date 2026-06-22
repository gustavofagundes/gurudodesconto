<?php
/**
 * Logo da marca — mascote + texto (header e footer)
 *
 * @package GuruDoDesconto
 *
 * @var array $args
 */

$context = isset( $args['context'] ) ? $args['context'] : 'header';
$tag     = 'header' === $context ? 'a' : 'div';
$href    = 'header' === $context ? home_url( '/' ) : '';
$class   = 'brand-logo brand-logo--' . esc_attr( $context );
?>

<<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php if ( 'a' === $tag ) : ?>
		href="<?php echo esc_url( $href ); ?>"
		aria-label="<?php bloginfo( 'name' ); ?> — <?php esc_attr_e( 'Início', 'guru-do-desconto' ); ?>"
	<?php endif; ?>
	class="<?php echo esc_attr( $class ); ?>">

	<img class="brand-logo__icon"
	     src="<?php echo esc_url( GURU_THEME_URI . '/assets/images/Guru_sem_fundo.png' ); ?>"
	     alt=""
	     width="72"
	     height="72"
	     aria-hidden="true"
	     loading="eager">

	<span class="brand-logo__text">
		<span class="brand-logo__guru">Guru</span>
		<span class="brand-logo__name"><?php esc_html_e( 'do Desconto', 'guru-do-desconto' ); ?></span>
		<em class="brand-logo__tagline"><?php esc_html_e( 'Economia e Alegria', 'guru-do-desconto' ); ?></em>
	</span>

</<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
