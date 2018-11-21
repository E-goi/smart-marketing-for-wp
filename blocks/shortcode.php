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
function shortcode_block_init() {
	// Skip block registration if Gutenberg is not enabled/merged.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	$dir = dirname( __FILE__ );

	$index_js = 'shortcode.js';
	wp_register_script('shortcode-block-editor',plugins_url( $index_js, __FILE__ ),array('wp-blocks','wp-i18n','wp-element'),filemtime( "$dir/$index_js" ));
	wp_localize_script( 'shortcode-block-editor', 'ajax_url', admin_url( 'admin-ajax.php' ) );
	register_block_type( 'blocos/shortcode', array('editor_script' => 'shortcode-block-editor') );
}
add_action( 'init', 'shortcode_block_init' );

// Função que obtém os formulários via ajax
add_action( 'wp_ajax_get_egoi_forms', 'get_egoi_forms' );

function get_egoi_forms() {
    global $wpdb;
    $rows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."posts WHERE post_type = 'egoi-simple-form'");

    //$forms = array(1 => 'ola');
    foreach($rows as $form) {
		$forms[] = array(
			'id' => $form->ID,
			'shortcode' => '[egoi-simple-form id="'.$form->ID.'"]',
			'title' => $form->post_title
		);
    }

    /* FALTAM OS ADVANCED FORMS */

    echo json_encode($forms);

	die();
}