<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/*
Widget Name: E-goi Widget
Description: Form Shortcode.
Author: E-goi
*/


class Egoi_PB_Widget extends SiteOrigin_Widget {

	public function __construct() {
		global $wpdb;

		$rows          = $wpdb->get_results( ' SELECT ID, post_title FROM ' . $wpdb->prefix . "posts WHERE post_type = 'egoi-simple-form'" );
		$shortcode_ids = array();
		foreach ( $rows as $row ) {
			$shortcode_ids[ $row->ID ] = $row->ID . ' - ' . $row->post_title;
		}

		// Call the parent constructor with the required arguments.
		parent::__construct(
			// The unique id for your widget.
			'egoi-pb-widget',
			// The name of the widget for display purposes.
			__( 'E-goi Widget', 'egoi-pb-widget-text-domain' ),
			// The $widget_options array, which is passed through to WP_Widget.
			// It has a couple of extras like the optional help URL, which should link to your sites help or support page.
			array(
				'description' => __( 'A form shortcode.', 'egoi-pb-widget-text-domain' ),
			),
			// The $control_options array, which is passed through to WP_Widget
			array(),
			// The $form_options array, which describes the form fields used to configure SiteOrigin widgets. We'll explain these in more detail later.
			array(
				'shortcode_id' => array(
					'type'    => 'select',
					'label'   => __( 'Shortcode ID:', 'siteorigin-widgets' ),
					'options' => $shortcode_ids,
				),
			),
			// The $base_folder path string.
			plugin_dir_path( __FILE__ )
		);
	}

	public function get_template_name( $instance ) {
		return 'egoi-pb-templates';
	}

	public function get_template_dir( $instance ) {
		return 'egoi-pb-templates';
	}
}

siteorigin_widget_register( 'egoi-pb-widget', __FILE__, 'Egoi_PB_Widget' );


function egoi_pb_widget_banner_img_src( $banner_url, $widget_meta ) {
	if ( $widget_meta['ID'] == 'egoi-pb-widget' ) {
		$banner_url = plugin_dir_url( __FILE__ ) . 'img/logo.png';
	}
	return $banner_url;
}
add_filter( 'siteorigin_widgets_widget_banner', 'egoi_pb_widget_banner_img_src', 10, 2 );


