<?php
// Preparar para futuras customizações do template
$settings = array(
	'template'          => 'boxed',
	'body_bg'           => class_exists( 'WooCommerce' ) ? get_option( 'woocommerce_email_body_background_color' ) : '#FAFAFA',
	'body_size'         => '680',
	'footer_aligment'   => 'center',
	'footer_bg'         => class_exists( 'WooCommerce' ) ? get_option( 'woocommerce_email_base_color' ) : '#FAFAFA',
	'footer_text_size'  => '12',
	'footer_text_color' => '#777',
	'footer_powered_by' => 'off',
	'header_aligment'   => 'left',
	'header_bg'         => class_exists( 'WooCommerce' ) ? get_option( 'woocommerce_email_base_color' ) : '#FAFAFA',
	'header_text_size'  => '30',
	'header_text_color' => class_exists( 'WooCommerce' ) ? '#f1f1f1' : '#000000',
	'logo_text_color'   => class_exists( 'WooCommerce' ) ? get_option( 'woocommerce_email_base_color' ) : '#000000',
	'email_body_bg'     => class_exists( 'WooCommerce' ) ? get_option( 'woocommerce_email_background_color' ) : '#FAFAFA',
	'body_text_size'    => '14',
	'body_text_color'   => class_exists( 'WooCommerce' ) ? get_option( 'woocommerce_email_text_color' ) : '#000000',
	'body_href_color'   => '#202020',
	'custom_css'        => '',
);

require 'email_header.php';
require 'email_content.php';
require 'email_footer.php';
