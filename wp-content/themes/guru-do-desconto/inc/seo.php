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
 * Output meta description.
 */
function guru_meta_description() {
	if ( is_singular() ) {
		$desc = get_post_meta( get_the_ID(), '_guru_meta_description', true );
		if ( ! $desc ) {
			$desc = has_excerpt() ? get_the_excerpt() : wp_trim_words( get_the_content(), 30 );
		}
	} elseif ( is_post_type_archive( 'review' ) ) {
		$desc = __( 'Reviews comparativos e análises honestas de produtos com promoções no Mercado Livre, Shopee e Amazon.', 'guru-do-desconto' );
	} elseif ( is_front_page() || guru_is_whatsapp_landing_page() ) {
		$desc = guru_default_seo_description();
	} else {
		$desc = get_theme_mod( 'guru_site_description', guru_default_seo_description() );
	}

	if ( $desc ) {
		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $desc ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'guru_meta_description', 2 );

/**
 * Open Graph and Twitter Card tags.
 */
function guru_social_meta() {
	$title = wp_get_document_title();
	$desc  = is_front_page() || guru_is_whatsapp_landing_page()
		? guru_default_seo_description()
		: get_theme_mod( 'guru_site_description', guru_default_seo_description() );
	$url   = is_singular() ? get_permalink() : home_url( '/' );
	$image = GURU_THEME_URI . '/assets/images/guru_fundo_branco_texto.png';

	if ( is_singular() && has_post_thumbnail() ) {
		$image = get_the_post_thumbnail_url( null, 'large' );
	}

	if ( is_singular() ) {
		$custom_desc = get_post_meta( get_the_ID(), '_guru_meta_description', true );
		if ( $custom_desc ) {
			$desc = $custom_desc;
		} elseif ( has_excerpt() ) {
			$desc = get_the_excerpt();
		}
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
		gtag('config', '<?php echo esc_js( $ga_id ); ?>');
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
	$marketplaces  = get_the_terms( $id, 'marketplace' );
	$brand         = $marketplaces && ! is_wp_error( $marketplaces ) ? $marketplaces[0]->name : 'Marketplace';
	$permalink     = get_permalink();
	$author        = guru_schema_author();
	$publisher     = guru_schema_publisher();
	$review_rating = array(
		'@type'       => 'Rating',
		'ratingValue' => $rating ?: '4',
		'bestRating'  => '5',
	);

	$schema = array(
		'@context' => 'https://schema.org',
		'@graph'   => array(
			array(
				'@type'           => 'Article',
				'@id'             => $permalink . '#article',
				'headline'        => get_the_title(),
				'description'     => wp_strip_all_tags( get_the_excerpt() ?: wp_trim_words( get_the_content(), 40 ) ),
				'url'             => $permalink,
				'datePublished'   => get_the_date( 'c' ),
				'dateModified'    => get_the_modified_date( 'c' ),
				'author'          => $author,
				'publisher'       => $publisher,
				'image'           => get_the_post_thumbnail_url( null, 'large' ),
				'inLanguage'      => 'pt-BR',
				'mainEntityOfPage' => $permalink,
			),
			array(
				'@type'       => 'Product',
				'@id'         => $permalink . '#product',
				'name'        => get_the_title(),
				'image'       => get_the_post_thumbnail_url( null, 'large' ),
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
		),
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'guru_schema_review', 10 );

/**
 * Enhance WordPress core sitemap.
 */
function guru_sitemap_post_types( $post_types ) {
	$post_types['review'] = true;
	return $post_types;
}
add_filter( 'wp_sitemaps_post_types', 'guru_sitemap_post_types' );

function guru_sitemap_taxonomies( $taxonomies ) {
	$taxonomies['marketplace'] = true;
	return $taxonomies;
}
add_filter( 'wp_sitemaps_taxonomies', 'guru_sitemap_taxonomies' );

/**
 * Add canonical link (WordPress handles most cases; reinforce for reviews).
 */
function guru_canonical_url() {
	if ( is_singular() ) {
		echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'guru_canonical_url', 1 );
