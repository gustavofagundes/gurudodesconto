<?php
/**
 * Meta boxes for Review post type
 *
 * @package GuruDoDesconto
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register meta boxes.
 */
function guru_review_meta_boxes() {
	add_meta_box(
		'guru_review_details',
		__( 'Dados do Produto (Afiliado)', 'guru-do-desconto' ),
		'guru_review_meta_box_render',
		'review',
		'normal',
		'high'
	);

	add_meta_box(
		'guru_review_seo',
		__( 'SEO do Review', 'guru-do-desconto' ),
		'guru_review_seo_render',
		'review',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'guru_review_meta_boxes' );

/**
 * Render product meta box.
 */
function guru_review_meta_box_render( $post ) {
	wp_nonce_field( 'guru_review_save', 'guru_review_nonce' );

	$fields = array(
		'_guru_affiliate_link' => array( 'label' => __( 'Link de Afiliado', 'guru-do-desconto' ), 'type' => 'url' ),
		'_guru_price'          => array( 'label' => __( 'Preço atual (R$)', 'guru-do-desconto' ), 'type' => 'number' ),
		'_guru_price_old'      => array( 'label' => __( 'Preço anterior (R$)', 'guru-do-desconto' ), 'type' => 'number' ),
		'_guru_rating'         => array( 'label' => __( 'Nota (0 a 5)', 'guru-do-desconto' ), 'type' => 'number', 'step' => '0.1', 'max' => '5' ),
	);

	echo '<table class="form-table"><tbody>';
	foreach ( $fields as $key => $field ) {
		$value = get_post_meta( $post->ID, $key, true );
		printf(
			'<tr><th><label for="%1$s">%2$s</label></th><td><input type="%3$s" id="%1$s" name="%1$s" value="%4$s" class="regular-text" %5$s %6$s></td></tr>',
			esc_attr( $key ),
			esc_html( $field['label'] ),
			esc_attr( $field['type'] ),
			esc_attr( $value ),
			isset( $field['step'] ) ? 'step="' . esc_attr( $field['step'] ) . '"' : '',
			isset( $field['max'] ) ? 'max="' . esc_attr( $field['max'] ) . '"' : ''
		);
	}
	echo '</tbody></table>';
	echo '<p class="description">' . esc_html__( 'O link de afiliado será usado no botão "Ver Promoção". Use links das suas contas do Mercado Livre, Shopee e Amazon.', 'guru-do-desconto' ) . '</p>';
}

/**
 * Render SEO meta box.
 */
function guru_review_seo_render( $post ) {
	$desc = get_post_meta( $post->ID, '_guru_meta_description', true );
	?>
	<p>
		<label for="_guru_meta_description"><?php esc_html_e( 'Meta description (Google)', 'guru-do-desconto' ); ?></label>
		<textarea id="_guru_meta_description" name="_guru_meta_description" rows="4" class="widefat" maxlength="160"><?php echo esc_textarea( $desc ); ?></textarea>
	</p>
	<p class="description"><?php esc_html_e( 'Máximo 160 caracteres. Descreva o produto com palavras-chave naturais.', 'guru-do-desconto' ); ?></p>
	<?php
}

/**
 * Save meta box data.
 */
function guru_review_save_meta( $post_id ) {
	if ( ! isset( $_POST['guru_review_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['guru_review_nonce'] ) ), 'guru_review_save' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array( '_guru_affiliate_link', '_guru_price', '_guru_price_old', '_guru_rating', '_guru_meta_description' );

	foreach ( $fields as $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			$value = wp_unslash( $_POST[ $field ] );
			if ( '_guru_affiliate_link' === $field ) {
				$value = esc_url_raw( $value );
			} elseif ( '_guru_meta_description' === $field ) {
				$value = sanitize_textarea_field( $value );
			} else {
				$value = sanitize_text_field( $value );
			}
			update_post_meta( $post_id, $field, $value );
		}
	}
}
add_action( 'save_post_review', 'guru_review_save_meta' );
