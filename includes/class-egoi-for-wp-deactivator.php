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
		if ( !empty($opt) ) {
			Egoi_For_Wp::removeData();
		}
	}

	public static function smsnf_drop_table( $table ) {
		global $wpdb;

		$sql = 'DROP TABLE IF EXISTS ' . $wpdb->prefix . $table;

		$wpdb->query( $sql );
	}

}
