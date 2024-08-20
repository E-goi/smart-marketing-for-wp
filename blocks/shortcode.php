<?php
/**
 * Functions to register client-side assets (scripts and stylesheets) for the
 * Gutenberg block.
 *
 * @package blocos
 */

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * @see https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type/#enqueuing-block-scripts
 */
function efwp_shortcode_block_init() {
	// Skip block registration if Gutenberg is not enabled/merged.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	$dir = dirname( __FILE__ );

	$index_js = './build/shortcode.js';
	wp_register_script( 'shortcode-block-editor', plugins_url( $index_js, __FILE__ ), array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components' ), filemtime( "$dir/$index_js" ) );
	wp_localize_script( 'shortcode-block-editor', 'ajax_url', array(admin_url( 'admin-ajax.php' )));
	register_block_type( 'egoi-for-wp/shortcode', array( 'editor_script' => 'shortcode-block-editor' ) );
}
add_action( 'init', 'efwp_shortcode_block_init' );

// Função que obtém os formulários via ajax
add_action( 'wp_ajax_efwp_get_egoi_forms', 'efwp_get_egoi_forms' );

function efwp_get_egoi_forms() {
	global $wpdb;
	$rows = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . "posts WHERE post_type = 'egoi-simple-form'" );

	// $forms = array(1 => 'ola');
	foreach ( $rows as $form ) {
		$forms[] = array(
			'id'        => $form->ID,
			'shortcode' => '[egoi-simple-form id="' . $form->ID . '"]',
			'title'     => $form->post_title,
		);
	}

	for ( $i = 1; $i <= 5; $i++ ) {
		$form = get_option( 'egoi_form_sync_' . $i );

		if (!isset($form['egoi_form_sync']) || !$form['egoi_form_sync']['form_id'] ) {
			continue;
		}

		$forms[] = array(
			'id'        => $i,
			'shortcode' => "[egoi_form_sync_$i]",
			'title'     => $form['egoi_form_sync']['form_name'],
		);
	}

	echo wp_json_encode( $forms );

	die();
}
