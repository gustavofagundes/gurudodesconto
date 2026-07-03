<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="facebook-domain-verification" content="oi5i25s3z7tguw14ivvay3db3iluyc">
	<link rel="icon" href="<?php echo esc_url( GURU_THEME_URI . '/assets/images/Guru_sem_fundo.png' ); ?>" type="image/png">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
$conversion   = function_exists( 'guru_is_conversion_page' ) && guru_is_conversion_page();
$group        = function_exists( 'guru_get_whatsapp_group_from_page' ) ? guru_get_whatsapp_group_from_page() : null;
$header_cta   = $group
	? guru_whatsapp_group_link_attrs( $group, 'header' )
	: 'href="' . esc_url( home_url( '/#grupos-whatsapp' ) ) . '"';
$header_label = $group
	? guru_whatsapp_cta_label( $group, 'sticky' )
	: __( 'Entrar no Grupo Grátis', 'guru-do-desconto' );
?>

<header class="site-header<?php echo $conversion ? ' site-header--conversion' : ''; ?>" role="banner">
	<div class="container header-inner">
		<?php get_template_part( 'template-parts/brand', 'logo', array( 'context' => 'header' ) ); ?>

		<?php if ( $conversion ) : ?>
			<a <?php echo $header_cta; ?> class="btn btn-whatsapp header-cta">
				<?php echo esc_html( $header_label ); ?>
			</a>
		<?php else : ?>
			<button class="menu-toggle" aria-label="<?php esc_attr_e( 'Abrir menu', 'guru-do-desconto' ); ?>" aria-expanded="false">☰</button>
			<nav class="main-nav" role="navigation" aria-label="<?php esc_attr_e( 'Menu principal', 'guru-do-desconto' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'fallback_cb'    => 'guru_fallback_menu',
					)
				);
				?>
			</nav>
		<?php endif; ?>
	</div>
</header>

<main id="main-content">
