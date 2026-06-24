<?php
/**
 * Custom Post Types and Taxonomies
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Review post type and Marketplace taxonomy.
 */
function guru_register_post_types() {
	register_post_type( 'review', array(
		'labels' => array(
			'name'               => __( 'Reviews', 'guru-do-desconto' ),
			'singular_name'      => __( 'Review', 'guru-do-desconto' ),
			'add_new'            => __( 'Adicionar Review', 'guru-do-desconto' ),
			'add_new_item'       => __( 'Adicionar Novo Review', 'guru-do-desconto' ),
			'edit_item'          => __( 'Editar Review', 'guru-do-desconto' ),
			'new_item'           => __( 'Novo Review', 'guru-do-desconto' ),
			'view_item'          => __( 'Ver Review', 'guru-do-desconto' ),
			'search_items'       => __( 'Buscar Reviews', 'guru-do-desconto' ),
			'not_found'          => __( 'Nenhum review encontrado', 'guru-do-desconto' ),
			'not_found_in_trash' => __( 'Nenhum review na lixeira', 'guru-do-desconto' ),
			'menu_name'          => __( 'Reviews', 'guru-do-desconto' ),
		),
		'public'             => true,
		'has_archive'        => true,
		'rewrite'            => array( 'slug' => 'reviews' ),
		'menu_icon'          => 'dashicons-star-filled',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'show_in_rest'       => true,
	) );
}
add_action( 'init', 'guru_register_post_types' );

/**
 * Flush rewrite rules on theme activation.
 */
function guru_rewrite_flush() {
	guru_register_post_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'guru_rewrite_flush' );
