<?php
/**
 * REST API — expõe meta fields dos reviews para automação (n8n).
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registra meta fields do CPT review na REST API.
 */
function guru_register_review_meta_rest() {
	$meta_fields = array(
		'_guru_affiliate_link'  => 'string',
		'_guru_price'           => 'string',
		'_guru_price_old'       => 'string',
		'_guru_rating'          => 'string',
		'_guru_meta_description'=> 'string',
		'_guru_focus_keyword'   => 'string',
	);

	foreach ( $meta_fields as $key => $type ) {
		register_post_meta(
			'review',
			$key,
			array(
				'single'       => true,
				'type'         => $type,
				'show_in_rest' => true,
				'auth_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}
}
add_action( 'init', 'guru_register_review_meta_rest' );
