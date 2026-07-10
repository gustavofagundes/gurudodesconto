<?php
/**
 * SEO enhancements — meta tags, schema.org, sitemap tweaks
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Entidade Organization — autor/editorial do site.
 */
function guru_schema_author() {
	return array(
		'@type' => 'Organization',
		'name'  => get_bloginfo( 'name' ),
		'url'   => home_url( '/' ),
	);
}

/**
 * Entidade publisher para Article/Review.
 */
function guru_schema_publisher() {
	return array(
		'@type' => 'Organization',
		'name'  => get_bloginfo( 'name' ),
		'url'   => home_url( '/' ),
		'logo'  => array(
			'@type' => 'ImageObject',
			'url'   => GURU_THEME_URI . '/assets/images/guru_fundo_branco_texto.png',
		),
	);
}

/**
 * Retorna meta description para a página atual (sempre com fallback).
 */
function guru_get_meta_description() {
	if ( is_front_page() || guru_is_whatsapp_landing_page() ) {
		return guru_default_seo_description();
	}

	if ( function_exists( 'guru_is_whatsapp_group_landing_page' ) && guru_is_whatsapp_group_landing_page() ) {
		$group = guru_get_whatsapp_group_from_page();
		if ( $group ) {
			return guru_whatsapp_group_meta_description( $group );
		}
	}

	if ( function_exists( 'guru_is_whatsapp_groups_hub_page' ) && guru_is_whatsapp_groups_hub_page() ) {
		return __( '6 grupos de achadinhos no WhatsApp: Mega Achadinhos, Shô, Casa e Decoração, Moda e Beleza, Maternidade e Tecnologia. Ofertas grátis todo dia!', 'guru-do-desconto' );
	}

	if ( is_post_type_archive( 'review' ) ) {
		return __( 'Reviews comparativos e análises honestas de produtos com promoções no Mercado Livre, Shopee e Amazon.', 'guru-do-desconto' );
	}

	if ( is_singular() ) {
		$desc = get_post_meta( get_the_ID(), '_guru_meta_description', true );
		if ( $desc ) {
			return $desc;
		}
		if ( has_excerpt() ) {
			return get_the_excerpt();
		}
		$content_desc = wp_trim_words( wp_strip_all_tags( get_the_content() ), 30 );
		if ( $content_desc ) {
			return $content_desc;
		}
	}

	$custom = get_theme_mod( 'guru_site_description', '' );
	if ( $custom ) {
		return $custom;
	}

	return guru_default_seo_description();
}

/**
 * Output meta description.
 */
function guru_meta_description() {
	$desc = guru_get_meta_description();
	$desc = wp_strip_all_tags( $desc );

	if ( $desc ) {
		if ( function_exists( 'mb_substr' ) ) {
			$desc = mb_substr( $desc, 0, 160 );
		} else {
			$desc = substr( $desc, 0, 160 );
		}
		echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'guru_meta_description', 2 );

/**
 * Open Graph and Twitter Card tags.
 */
function guru_social_meta() {
	$title = wp_get_document_title();
	$desc  = wp_strip_all_tags( guru_get_meta_description() );
	$url   = is_singular() ? get_permalink() : home_url( '/' );
	$image = GURU_THEME_URI . '/assets/images/guru_fundo_branco_texto.png';

	if ( is_singular() && has_post_thumbnail() ) {
		$image = get_the_post_thumbnail_url( null, 'large' );
	} elseif ( function_exists( 'guru_get_whatsapp_group_from_page' ) && ( $group = guru_get_whatsapp_group_from_page() ) ) {
		$image = $group['image'] ?? $image;
	} elseif ( is_singular( 'review' ) ) {
		$image = guru_review_og_image( $image );
	}

	$type = is_singular( 'review' ) ? 'article' : 'website';
	?>
	<meta property="og:type" content="<?php echo esc_attr( $type ); ?>">
	<meta property="og:title" content="<?php echo esc_attr( $title ); ?>">
	<meta property="og:description" content="<?php echo esc_attr( wp_strip_all_tags( $desc ) ); ?>">
	<meta property="og:url" content="<?php echo esc_url( $url ); ?>">
	<meta property="og:site_name" content="<?php bloginfo( 'name' ); ?>">
	<meta property="og:image" content="<?php echo esc_url( $image ); ?>">
	<meta property="og:locale" content="pt_BR">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?php echo esc_attr( $title ); ?>">
	<meta name="twitter:description" content="<?php echo esc_attr( wp_strip_all_tags( $desc ) ); ?>">
	<meta name="twitter:image" content="<?php echo esc_url( $image ); ?>">
	<?php
}
add_action( 'wp_head', 'guru_social_meta', 3 );

/**
 * Google Search Console verification.
 */
function guru_search_console_verification() {
	$code = get_theme_mod( 'guru_google_search_console' );
	if ( $code ) {
		echo '<meta name="google-site-verification" content="' . esc_attr( $code ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'guru_search_console_verification', 1 );

/**
 * Google Analytics 4.
 */
function guru_google_analytics() {
	if ( guru_site_kit_handles_analytics() ) {
		return;
	}

	$ga_id = get_theme_mod( 'guru_google_analytics' );
	if ( ! $ga_id || is_user_logged_in() ) {
		return;
	}
	?>
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $ga_id ); ?>"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', '<?php echo esc_js( $ga_id ); ?>', {
			send_page_view: true,
			allow_google_signals: true
		});
	</script>
	<?php
}
add_action( 'wp_head', 'guru_google_analytics', 99 );

/**
 * JSON-LD Organization + WebSite schema (homepage).
 */
function guru_schema_organization() {
	if ( ! is_front_page() ) {
		return;
	}

	$schema = array(
		'@context' => 'https://schema.org',
		'@graph'   => array(
			array(
				'@type' => 'Organization',
				'@id'   => home_url( '/#organization' ),
				'name'  => get_bloginfo( 'name' ),
				'url'   => home_url( '/' ),
				'logo'  => GURU_THEME_URI . '/assets/images/guru_fundo_branco_texto.png',
				'description' => guru_default_seo_description(),
				'knowsAbout'  => array(
					'Grupo de promoções no WhatsApp',
					'Cupons Mercado Livre',
					'Ofertas Shopee',
					'Promoções Amazon',
					'Reviews de produtos',
				),
				'sameAs' => array(),
			),
			array(
				'@type'           => 'WebSite',
				'@id'             => home_url( '/#website' ),
				'name'            => get_bloginfo( 'name' ),
				'url'             => home_url( '/' ),
				'publisher'       => array( '@id' => home_url( '/#organization' ) ),
				'potentialAction' => array(
					'@type'       => 'SearchAction',
					'target'      => home_url( '/?s={search_term_string}' ),
					'query-input' => 'required name=search_term_string',
				),
			),
		),
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'guru_schema_organization', 10 );

/**
 * JSON-LD Product + Review schema for review posts.
 */
function guru_schema_review() {
	if ( ! is_singular( 'review' ) ) {
		return;
	}

	$id            = get_the_ID();
	$affiliate     = get_post_meta( $id, '_guru_affiliate_link', true );
	$price         = get_post_meta( $id, '_guru_price', true );
	$rating        = get_post_meta( $id, '_guru_rating', true );
	$focus_keyword = get_post_meta( $id, '_guru_focus_keyword', true );
	$brand         = get_bloginfo( 'name' );
	$permalink     = get_permalink();
	$author        = guru_schema_author();
	$publisher     = guru_schema_publisher();
	$image_url     = guru_get_review_image_url( $id, 'large' );
	$review_rating = array(
		'@type'       => 'Rating',
		'ratingValue' => $rating ?: '4',
		'bestRating'  => '5',
	);

	$graph = array(
		array(
			'@type'            => 'Article',
			'@id'              => $permalink . '#article',
			'headline'         => get_the_title(),
			'description'      => wp_strip_all_tags( get_the_excerpt() ?: wp_trim_words( get_the_content(), 40 ) ),
			'url'              => $permalink,
			'datePublished'    => get_the_date( 'c' ),
			'dateModified'     => get_the_modified_date( 'c' ),
			'author'           => $author,
			'publisher'        => $publisher,
			'image'            => $image_url ?: null,
			'inLanguage'       => 'pt-BR',
			'mainEntityOfPage' => $permalink,
			'keywords'         => $focus_keyword ?: '',
		),
		array(
			'@type'       => 'Product',
			'@id'         => $permalink . '#product',
			'name'        => get_the_title(),
			'image'       => $image_url ?: null,
			'description' => wp_strip_all_tags( get_the_excerpt() ?: wp_trim_words( get_the_content(), 40 ) ),
			'brand'       => array(
				'@type' => 'Brand',
				'name'  => $brand,
			),
			'offers'      => array(
				'@type'         => 'Offer',
				'url'           => $affiliate ?: $permalink,
				'priceCurrency' => 'BRL',
				'price'         => $price ?: '0',
				'availability'  => 'https://schema.org/InStock',
			),
			'review'      => array(
				'@type'         => 'Review',
				'author'        => $author,
				'datePublished' => get_the_date( 'c' ),
				'reviewBody'    => wp_strip_all_tags( get_the_excerpt() ?: wp_trim_words( get_the_content(), 40 ) ),
				'reviewRating'  => $review_rating,
			),
		),
		array(
			'@type'           => 'BreadcrumbList',
			'itemListElement' => array(
				array(
					'@type'    => 'ListItem',
					'position' => 1,
					'name'     => 'Início',
					'item'     => home_url( '/' ),
				),
				array(
					'@type'    => 'ListItem',
					'position' => 2,
					'name'     => 'Reviews',
					'item'     => get_post_type_archive_link( 'review' ),
				),
				array(
					'@type'    => 'ListItem',
					'position' => 3,
					'name'     => get_the_title(),
				),
			),
		),
	);

	$item_list = guru_review_meta_json( '_guru_item_list_json' );
	if ( $item_list ) {
		$list_elements = array();
		foreach ( $item_list as $item ) {
			if ( empty( $item['name'] ) ) {
				continue;
			}
			$list_elements[] = array(
				'@type'    => 'ListItem',
				'position' => (int) ( $item['position'] ?? 0 ),
				'name'     => wp_strip_all_tags( $item['name'] ),
				'url'      => ! empty( $item['url'] ) ? esc_url_raw( $item['url'] ) : $permalink,
			);
		}
		if ( $list_elements ) {
			$graph[] = array(
				'@type'           => 'ItemList',
				'name'            => sprintf(
					/* translators: %s: product keyword */
					__( 'Melhores %s comparados', 'guru-do-desconto' ),
					$focus_keyword ?: get_the_title()
				),
				'itemListElement' => $list_elements,
			);
		}
	}

	$faq_items = guru_review_meta_json( '_guru_faq_json' );
	if ( $faq_items ) {
		$main_entity = array();
		foreach ( $faq_items as $faq ) {
			if ( empty( $faq['question'] ) || empty( $faq['answer'] ) ) {
				continue;
			}
			$main_entity[] = array(
				'@type'          => 'Question',
				'name'           => wp_strip_all_tags( $faq['question'] ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => wp_strip_all_tags( $faq['answer'] ),
				),
			);
		}
		if ( $main_entity ) {
			$graph[] = array(
				'@type'      => 'FAQPage',
				'mainEntity' => $main_entity,
			);
		}
	}

	$schema = array(
		'@context' => 'https://schema.org',
		'@graph'   => $graph,
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'guru_schema_review', 10 );

/**
 * Enhance WordPress core sitemap — reviews habilitados (detalhes em crawling-indexing.php).
 */
function guru_sitemap_post_types( $post_types ) {
	$post_types['review'] = true;
	return $post_types;
}
add_filter( 'wp_sitemaps_post_types', 'guru_sitemap_post_types' );

/**
 * Título SEO customizado para reviews.
 */
function guru_review_document_title( $parts ) {
	if ( ! is_singular( 'review' ) ) {
		return $parts;
	}

	$seo_title = get_post_meta( get_the_ID(), '_guru_seo_title', true );
	if ( $seo_title ) {
		$parts['title'] = wp_strip_all_tags( $seo_title );
	}

	return $parts;
}
add_filter( 'document_title_parts', 'guru_review_document_title', 15 );

/**
 * Garante indexação de reviews publicados.
 */
function guru_review_robots( $robots ) {
	if ( is_singular( 'review' ) && 'publish' === get_post_status() ) {
		$robots['index']              = true;
		$robots['follow']             = true;
		$robots['max-image-preview']  = 'large';
		$robots['max-snippet']        = '-1';
	}

	return $robots;
}
add_filter( 'wp_robots', 'guru_review_robots' );

/**
 * URL da imagem OG para reviews (produto vencedor).
 */
function guru_review_og_image( $image ) {
	if ( ! is_singular( 'review' ) ) {
		return $image;
	}

	$featured_url = guru_get_review_image_url( get_the_ID(), 'large' );
	return $featured_url ? $featured_url : $image;
}

/**
 * Decodifica JSON armazenado no meta do review.
 */
function guru_review_meta_json( $key ) {
	$raw = get_post_meta( get_the_ID(), $key, true );
	if ( ! $raw ) {
		return array();
	}

	$decoded = json_decode( $raw, true );
	return is_array( $decoded ) ? $decoded : array();
}
