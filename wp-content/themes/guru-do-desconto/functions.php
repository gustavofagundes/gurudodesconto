<?php
/**
 * Guru do Desconto — Theme functions
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

define( 'GURU_THEME_VERSION', '1.0.7' );
define( 'GURU_THEME_DIR', get_template_directory() );
define( 'GURU_THEME_URI', get_template_directory_uri() );

require_once GURU_THEME_DIR . '/inc/custom-post-types.php';
require_once GURU_THEME_DIR . '/inc/customizer.php';
require_once GURU_THEME_DIR . '/inc/seo-whatsapp.php';
require_once GURU_THEME_DIR . '/inc/seo.php';
require_once GURU_THEME_DIR . '/inc/meta-boxes.php';
require_once GURU_THEME_DIR . '/inc/rest-api.php';
require_once GURU_THEME_DIR . '/inc/content-sync.php';
require_once GURU_THEME_DIR . '/inc/sample-content.php';
require_once GURU_THEME_DIR . '/inc/fallback-menu.php';
require_once GURU_THEME_DIR . '/inc/adsense.php';

/**
 * Theme setup.
 */
function guru_theme_setup() {
	load_theme_textdomain( 'guru-do-desconto', GURU_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 120,
		'width'       => 300,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );

	add_image_size( 'review-card', 600, 375, true );
	add_image_size( 'review-single', 800, 600, true );

	register_nav_menus( array(
		'primary' => __( 'Menu Principal', 'guru-do-desconto' ),
		'footer'  => __( 'Menu Rodapé', 'guru-do-desconto' ),
	) );
}
add_action( 'after_setup_theme', 'guru_theme_setup' );

/**
 * Enqueue styles and scripts.
 */
function guru_enqueue_assets() {
	wp_enqueue_style(
		'guru-style',
		get_stylesheet_uri(),
		array(),
		GURU_THEME_VERSION
	);

	wp_enqueue_script(
		'guru-main',
		GURU_THEME_URI . '/assets/js/main.js',
		array(),
		GURU_THEME_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'guru_enqueue_assets' );

/**
 * Get WhatsApp group link from customizer.
 */
function guru_whatsapp_link() {
	return esc_url( get_theme_mod( 'guru_whatsapp_link', 'https://chat.whatsapp.com/I5Ln1bvpIP89FpaxRO4VJG?mode=gi_t' ) );
}

/**
 * Get marketplace label and badge class.
 */
function guru_marketplace_info( $slug ) {
	$map = array(
		'mercado-livre' => array(
			'label' => 'Mercado Livre',
			'class' => 'badge-ml',
			'icon'  => 'ML',
		),
		'shopee'        => array(
			'label' => 'Shopee',
			'class' => 'badge-shopee',
			'icon'  => 'S',
		),
		'amazon'        => array(
			'label' => 'Amazon',
			'class' => 'badge-amazon',
			'icon'  => 'A',
		),
	);

	return $map[ $slug ] ?? array(
		'label' => ucfirst( str_replace( '-', ' ', $slug ) ),
		'class' => 'badge-ml',
		'icon'  => '?',
	);
}

/**
 * Format price in BRL.
 */
function guru_format_price( $value ) {
	if ( empty( $value ) ) {
		return '';
	}
	return 'R$ ' . number_format( (float) $value, 2, ',', '.' );
}

/**
 * Render star rating.
 */
function guru_render_stars( $rating ) {
	$rating = max( 0, min( 5, (float) $rating ) );
	$full   = (int) floor( $rating );
	$half   = ( $rating - $full ) >= 0.5 ? 1 : 0;
	$empty  = 5 - $full - $half;

	$out = str_repeat( '★', $full );
	if ( $half ) {
		$out .= '½';
	}
	$out .= str_repeat( '☆', $empty );

	return '<span class="stars" aria-label="' . esc_attr( sprintf( __( '%s de 5 estrelas', 'guru-do-desconto' ), $rating ) ) . '">' . esc_html( $out ) . '</span>';
}

/**
 * Add nofollow to affiliate links in content.
 */
function guru_affiliate_link_attrs( $url ) {
	return 'href="' . esc_url( $url ) . '" target="_blank" rel="nofollow sponsored noopener"';
}

/**
 * Widget areas.
 */
function guru_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar Reviews', 'guru-do-desconto' ),
		'id'            => 'sidebar-reviews',
		'before_widget' => '<div class="widget">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'guru_widgets_init' );

/**
 * Excerpt length for review cards.
 */
function guru_excerpt_length( $length ) {
	if ( is_post_type_archive( 'review' ) || is_front_page() ) {
		return 22;
	}
	return $length;
}
add_filter( 'excerpt_length', 'guru_excerpt_length' );

/**
 * Add preconnect for performance.
 */
function guru_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.googleapis.com',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'guru_resource_hints', 10, 2 );

/**
 * Remove menções a link/comissão de afiliado no corpo dos reviews.
 */
function guru_clean_review_body_copy( $content ) {
	if ( ! is_singular( 'review' ) ) {
		return $content;
	}

	$content = preg_replace( '/<p class="affiliate-disclaimer">.*?<\/p>/is', '', $content );
	$content = preg_replace( '/<h3>\s*Os links são de afiliado\?\s*<\/h3>\s*<p>.*?<\/p>/is', '', $content );
	$content = str_ireplace(
		array( ' — link de afiliado', ' - link de afiliado', 'links de afiliado', 'link de afiliado', 'tag de afiliado', 'comissão de afiliado' ),
		'',
		$content
	);

	return $content;
}
add_filter( 'the_content', 'guru_clean_review_body_copy', 25 );
