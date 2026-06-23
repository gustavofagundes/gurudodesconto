<?php
/**
 * Logo horizontal — mascote + texto (header e footer)
 *
 * @package GuruDoDesconto
 *
 * @var array $args
 */

$context = isset( $args['context'] ) ? $args['context'] : 'header';
$tag     = 'header' === $context ? 'a' : 'div';
$class   = 'brand-logo brand-logo--' . esc_attr( $context );
$icon    = GURU_THEME_URI . '/assets/images/Guru_sem_fundo.png';
$alt     = get_bloginfo( 'name' ) . ' — ' . __( 'Economia e Alegria', 'guru-do-desconto' );
?>

<<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php if ( 'a' === $tag ) : ?>
		href="<?php echo esc_url( home_url( '/' ) ); ?>"
		aria-label="<?php echo esc_attr( $alt ); ?>"
	<?php endif; ?>
	class="<?php echo esc_attr( $class ); ?>">

	<img class="brand-logo__icon"
	     src="<?php echo esc_url( $icon ); ?>"
	     alt=""
	     width="64"
	     height="64"
	     aria-hidden="true"
	     decoding="async"
	     <?php echo 'header' === $context ? 'loading="eager" fetchpriority="high"' : 'loading="lazy"'; ?>>

	<span class="brand-logo__text">
		<span class="brand-logo__title">
			<span class="brand-logo__guru">Guru</span>
			<span class="brand-logo__name"><?php esc_html_e( 'do Desconto', 'guru-do-desconto' ); ?></span>
		</span>
		<em class="brand-logo__tagline"><?php esc_html_e( 'Economia e Alegria', 'guru-do-desconto' ); ?></em>
	</span>

</<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
