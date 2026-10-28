<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die();
}
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @link       https://www.e-goi.com
 * @package    Egoi_For_Wp
 * @subpackage Egoi_For_Wp/includes
 * @author     E-goi <admin@e-goi.com>
 */
class Egoi_For_Wp_Activator {

	public static $version = EFWP_SELF_VERSION;

	public static function activate() {

		self::smsnf_create_table(
			'egoi_map_fields',
			'
	        id INT(11) NOT NULL AUTO_INCREMENT, 
            wp VARCHAR(255) NOT NULL, 
            wp_name VARCHAR(255) NOT NULL, 
            egoi VARCHAR(255) NOT NULL, 
            egoi_name VARCHAR(255) NOT NULL, 
            status INT(1) NOT NULL, 
            PRIMARY KEY (id)'
		);

		self::smsnf_create_table(
			'egoi_form_subscribers',
			'
            id INT(11) NOT NULL AUTO_INCREMENT, 
            form_id INT(11) NOT NULL, 
            form_type VARCHAR(255) NOT NULL, 
            form_title VARCHAR(255) NOT NULL,
            subscriber_id VARCHAR(255) NULL,
            subscriber_name VARCHAR(255) NULL,
            subscriber_email VARCHAR(255) NULL, 
            list_id INT(11) NOT NULL, 
            list_title VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id)'
		);

		self::smsnf_create_table(
			'egoi_lazy_request',
			'
	        id INT(11) NOT NULL AUTO_INCREMENT, 
            url TEXT NOT NULL, 
            type VARCHAR(12) NOT NULL, 
            body LONGTEXT NOT NULL, 
            headers TEXT NOT NULL, 
            PRIMARY KEY (id)'
		);
	}

	public static function smsnf_create_table( $table, $fields ) {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		$sql = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . $table . ' (' . $fields . ') ' . $charset_collate . '; ';

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

	}

}
