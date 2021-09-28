<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die();
}
/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @link       https://www.e-goi.com
 * @since      1.0.0
 * @package    Egoi_For_Wp
 * @subpackage Egoi_For_Wp/includes
 * @author     E-goi <admin@e-goi.com>
 */
class Egoi_For_Wp_Deactivator {

	public static $version = EFWP_SELF_VERSION;

	public static function deactivate() {

		$opt = get_option( 'egoi_data' );
		if ( $opt ) {
			Egoi_For_Wp::removeData();
		}

		$email = wp_get_current_user();
		$email = $email->data->user_email;

		self::serviceDeactivate( array( 'email' => $email ) );

		// self::smsnf_drop_table('egoi_form_subscribers');
	}

	public static function serviceDeactivate( $data = array() ) {

		try {

			$params = array(
				'email'    => $data['email'],
				'smegoi_v' => 'Wordpress_' . self::$version,
				'smegoi_h' => isset( $_SERVER['SERVER_NAME'] ) ? esc_url_raw( $_SERVER['SERVER_NAME'] ) : esc_url_raw( $_SERVER['HTTP_HOST'] ),
				'smegoi_m' => 1,
				'smegoi_e' => get_locale(),
				'smegoi_u' => ( function_exists( 'posix_uname' ) && ( is_array( posix_uname() ) ) ) ? posix_uname() : '',
			);

			require 'service/post_wsdl.php';
			if ( class_exists( 'SoapClient' ) ) {
				$response = new SoapClient( null, $options );
				$response->call( $params );
			} else {
				$response = self::_postContent( $options['location'], $params );
			}
		} catch ( Exception $e ) {
			// continue
		}

		return true;
	}

	private static function _postContent( $url, $rows ) {

		$res = wp_remote_request(
			$url,
			array(
				'method'  => 'POST',
				'timeout' => 30,
				'body'    => $rows,
				'headers' => array(),
			)
		);

		return $res['body'];
	}

	public static function smsnf_drop_table( $table ) {
		global $wpdb;

		$sql = 'DROP TABLE IF EXISTS ' . $wpdb->prefix . $table;

		$wpdb->query( $sql );
	}

}
