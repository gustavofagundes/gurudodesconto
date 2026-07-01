<?php
/**
 * Guru do Desconto — Theme functions
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

define( 'GURU_THEME_VERSION', '1.0.17' );
define( 'GURU_THEME_DIR', get_template_directory() );
define( 'GURU_THEME_URI', get_template_directory_uri() );

require_once GURU_THEME_DIR . '/inc/custom-post-types.php';
require_once GURU_THEME_DIR . '/inc/meta-pixel.php';
require_once GURU_THEME_DIR . '/inc/customizer.php';

/**
 * Site Kit já injeta GA4/GTM — evita tags duplicadas no tema.
 */
function guru_site_kit_handles_analytics() {
	return defined( 'GOOGLESITEKIT_VERSION' ) || class_exists( 'Google\\Site_Kit\\Plugin' );
}

require_once GURU_THEME_DIR . '/inc/tracking.php';
require_once GURU_THEME_DIR . '/inc/whatsapp-groups.php';
require_once GURU_THEME_DIR . '/inc/seo-whatsapp.php';
require_once GURU_THEME_DIR . '/inc/seo.php';
require_once GURU_THEME_DIR . '/inc/crawling-indexing.php';
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
 * Classe no body para estilos/scripts da landing (home).
 */
function guru_body_classes( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'guru-front-landing';
	}
	return $classes;
}
add_filter( 'body_class', 'guru_body_classes' );

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
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_enqueue_script(
		'guru-tracking',
		GURU_THEME_URI . '/assets/js/tracking.js',
		array(),
		GURU_THEME_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	if ( is_front_page() ) {
		wp_enqueue_script(
			'guru-whatsapp-groups',
			GURU_THEME_URI . '/assets/js/whatsapp-groups.js',
			array( 'guru-tracking' ),
			GURU_THEME_VERSION,
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);

		wp_localize_script(
			'guru-whatsapp-groups',
			'guruWhatsappPicker',
			array(
				'strings' => array(
					'noneSelected'  => __( 'Nenhum grupo selecionado', 'guru-do-desconto' ),
					'oneSelected'   => __( '1 grupo selecionado', 'guru-do-desconto' ),
					'manySelected'  => __( '%d grupos selecionados', 'guru-do-desconto' ),
					'joinOne'       => __( 'Entrar em 1 grupo no WhatsApp', 'guru-do-desconto' ),
					'joinMany'      => __( 'Entrar em %d grupos no WhatsApp', 'guru-do-desconto' ),
					'fallbackTitle' => __( 'Algumas abas foram bloqueadas pelo navegador. Toque em cada link abaixo:', 'guru-do-desconto' ),
				),
			)
		);

		wp_enqueue_script(
			'guru-landing',
			GURU_THEME_URI . '/assets/js/landing.js',
			array( 'guru-main' ),
			GURU_THEME_VERSION,
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'guru_enqueue_assets' );

/**
 * URL de imagem do tema com variante otimizada (ex.: logo-com-texto-320.png).
 */
function guru_theme_image_url( $filename, $variant = '' ) {
	$info = pathinfo( $filename );
	$base = $info['filename'] ?? $filename;
	$ext  = $info['extension'] ?? 'png';

	$candidates = array();
	if ( $variant ) {
		$candidates[] = $base . '-' . $variant . '.' . $ext;
	}
	$candidates[] = $filename;

	foreach ( $candidates as $file ) {
		if ( file_exists( GURU_THEME_DIR . '/assets/images/' . $file ) ) {
			return GURU_THEME_URI . '/assets/images/' . $file;
		}
	}

	return GURU_THEME_URI . '/assets/images/' . $filename;
}

/**
 * URL da imagem hero (LCP) — variante redimensionada quando disponível.
 */
function guru_hero_image_url() {
	return guru_theme_image_url( 'Guru_sem_fundo.png', '512' );
}

/**
 * Link do grupo WhatsApp (com UTMs padrão).
 */
function guru_whatsapp_link() {
	return esc_url( guru_whatsapp_tracked_url( 'link' ) );
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
 * Lojas exibidas na home (logos oficiais em assets/images/marketplaces/).
 *
 * @return array<string, array{class: string, logo: string, name: string, desc: string}>
 */
function guru_home_marketplaces() {
	return array(
		'mercado-livre' => array(
			'class' => 'ml',
			'logo'  => 'mercado-livre-logo.png',
			'name'  => 'Mercado Livre',
			'desc'  => __( 'Eletrônicos, casa, moda e muito mais com frete grátis e cupons exclusivos.', 'guru-do-desconto' ),
		),
		'shopee'        => array(
			'class' => 'shopee',
			'logo'  => 'shopee-logo.webp',
			'name'  => 'Shopee',
			'desc'  => __( 'Ofertas relâmpago, cashback e produtos importados com preços imbatíveis.', 'guru-do-desconto' ),
		),
		'amazon'        => array(
			'class' => 'amazon',
			'logo'  => 'amazon-logo.webp',
			'name'  => 'Amazon',
			'desc'  => __( 'Prime Day, Black Friday e promoções diárias em milhares de categorias.', 'guru-do-desconto' ),
		),
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
 * URL da imagem do review — prioriza CDN do ML (sobrevive a deploys).
 *
 * @param int|null $post_id Post ID.
 * @param string   $size    Tamanho WP (fallback local).
 */
function guru_get_review_image_url( $post_id = null, $size = 'large' ) {
	$post_id = $post_id ?: get_the_ID();
	if ( ! $post_id ) {
		return '';
	}

	$external = get_post_meta( $post_id, '_guru_featured_image_url', true );
	if ( $external ) {
		return esc_url( $external );
	}

	$thumb_id = get_post_thumbnail_id( $post_id );
	if ( $thumb_id ) {
		$file = get_attached_file( $thumb_id );
		if ( $file && file_exists( $file ) ) {
			$local = wp_get_attachment_image_url( $thumb_id, $size );
			if ( $local ) {
				return esc_url( $local );
			}
		}
	}

	return '';
}

/**
 * Verifica se o review tem imagem exibível.
 */
function guru_review_has_image( $post_id = null ) {
	return (bool) guru_get_review_image_url( $post_id );
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

/**
 * Imagens no corpo do review: lazy-load e dimensões padrão (evita CLS).
 */
function guru_optimize_review_content_images( $content ) {
	if ( ! is_singular( 'review' ) || false === stripos( $content, '<img' ) ) {
		return $content;
	}

	return preg_replace_callback(
		'/<img\b([^>]*)>/i',
		static function ( $matches ) {
			$attrs = $matches[1];

			if ( false === stripos( $attrs, 'loading=' ) ) {
				$attrs .= ' loading="lazy"';
			}
			if ( false === stripos( $attrs, 'decoding=' ) ) {
				$attrs .= ' decoding="async"';
			}
			if ( false === stripos( $attrs, 'width=' ) ) {
				$attrs .= ' width="600" height="400"';
			}

			return '<img' . $attrs . '>';
		},
		$content
	);
}
add_filter( 'the_content', 'guru_optimize_review_content_images', 26 );
