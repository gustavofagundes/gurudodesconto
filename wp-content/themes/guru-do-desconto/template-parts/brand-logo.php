<?php
/**
 * Logo horizontal oficial — header e footer
 *
 * @package GuruDoDesconto
 *
 * @var array $args
 */

$context = isset( $args['context'] ) ? $args['context'] : 'header';
$tag     = 'header' === $context ? 'a' : 'div';
$class   = 'brand-logo brand-logo--' . esc_attr( $context );
$alt     = get_bloginfo( 'name' ) . ' — ' . __( 'economia de verdade', 'guru-do-desconto' );

$src_full = guru_theme_image_url( 'logo-com-texto.png' );
$src_opt  = guru_theme_image_url( 'logo-com-texto.png', '320' );
$src      = $src_opt;
$srcset   = $src_opt !== $src_full ? $src_opt . ' 320w, ' . $src_full . ' 599w' : '';
$sizes    = '(max-width: 600px) 140px, 156px';
?>

<<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php if ( 'a' === $tag ) : ?>
		href="<?php echo esc_url( home_url( '/' ) ); ?>"
		aria-label="<?php echo esc_attr( $alt ); ?>"
	<?php endif; ?>
	class="<?php echo esc_attr( $class ); ?>">

	<img class="brand-logo__img"
	     src="<?php echo esc_url( $src ); ?>"
	     <?php if ( $srcset ) : ?>
	     srcset="<?php echo esc_attr( $srcset ); ?>"
	     sizes="<?php echo esc_attr( $sizes ); ?>"
	     <?php endif; ?>
	     alt="<?php echo esc_attr( $alt ); ?>"
	     width="599"
	     height="292"
	     decoding="async"
	     <?php echo 'header' === $context ? 'loading="eager" fetchpriority="high"' : 'loading="lazy"'; ?>>

</<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
