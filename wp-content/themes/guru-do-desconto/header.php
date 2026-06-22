<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?php echo esc_url( GURU_THEME_URI . '/assets/images/Guru_sem_fundo.png' ); ?>" type="image/png">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header" role="banner">
	<div class="container header-inner">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" aria-label="<?php bloginfo( 'name' ); ?> — Início">
			<img src="<?php echo esc_url( GURU_THEME_URI . '/assets/images/Guru_sem_fundo.png' ); ?>"
			     alt=""
			     width="56" height="56"
			     aria-hidden="true">
			<span class="site-logo-text">
				<strong>Guru</strong> do Desconto
				<small><?php esc_html_e( 'Economia e Alegria', 'guru-do-desconto' ); ?></small>
			</span>
		</a>

		<button class="menu-toggle" aria-label="<?php esc_attr_e( 'Abrir menu', 'guru-do-desconto' ); ?>" aria-expanded="false">☰</button>

		<nav class="main-nav" role="navigation" aria-label="<?php esc_attr_e( 'Menu principal', 'guru-do-desconto' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'fallback_cb'    => 'guru_fallback_menu',
			) );
			?>
		</nav>
	</div>
</header>

<main id="main-content">
