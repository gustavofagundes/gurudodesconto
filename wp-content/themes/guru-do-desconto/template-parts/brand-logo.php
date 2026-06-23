<?php
/**
 * Logo da marca — imagem oficial (header e footer)
 *
 * @package GuruDoDesconto
 *
 * @var array $args
 */

$context = isset( $args['context'] ) ? $args['context'] : 'header';
$tag     = 'header' === $context ? 'a' : 'div';
$class   = 'brand-logo brand-logo--' . esc_attr( $context );
$src     = GURU_THEME_URI . '/assets/images/guru_fundo_branco_texto.png';
$alt     = get_bloginfo( 'name' ) . ' — ' . __( 'Economia e Alegria', 'guru-do-desconto' );
?>

<<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php if ( 'a' === $tag ) : ?>
		href="<?php echo esc_url( home_url( '/' ) ); ?>"
		aria-label="<?php echo esc_attr( $alt ); ?>"
	<?php endif; ?>
	class="<?php echo esc_attr( $class ); ?>">

	<img class="brand-logo__img"
	     src="<?php echo esc_url( $src ); ?>"
	     alt="<?php echo esc_attr( $alt ); ?>"
	     width="220"
	     height="88"
	     decoding="async"
	     <?php echo 'header' === $context ? 'loading="eager" fetchpriority="high"' : 'loading="lazy"'; ?>>

</<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
