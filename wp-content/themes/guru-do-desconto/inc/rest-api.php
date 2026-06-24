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

/**
 * Webhook REST para sincronizar reviews após deploy (n8n, GitHub Actions, etc.).
 */
function guru_register_review_sync_rest() {
	register_rest_route(
		'guru/v1',
		'/sync-reviews',
		array(
			'methods'             => 'POST',
			'callback'            => 'guru_rest_sync_reviews',
			'permission_callback' => 'guru_rest_sync_reviews_permission',
		)
	);
}
add_action( 'rest_api_init', 'guru_register_review_sync_rest' );

/**
 * Valida token do webhook.
 */
function guru_rest_sync_reviews_permission( WP_REST_Request $request ) {
	$secret = guru_review_sync_secret();
	$token  = $request->get_header( 'x_guru_sync_token' );

	if ( ! $token ) {
		$token = $request->get_param( 'token' );
	}

	return $secret && is_string( $token ) && hash_equals( $secret, (string) $token );
}

/**
 * Executa sincronização via REST.
 */
function guru_rest_sync_reviews() {
	if ( ! function_exists( 'guru_run_review_sync' ) ) {
		return new WP_Error( 'guru_sync_unavailable', __( 'Sincronização não disponível.', 'guru-do-desconto' ), array( 'status' => 500 ) );
	}

	$result = guru_run_review_sync();

	return rest_ensure_response(
		array(
			'success' => true,
			'synced'  => $result['synced'],
			'files'   => $result['files'],
			'dir'     => $result['dir'],
		)
	);
}
