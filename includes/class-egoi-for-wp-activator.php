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
	
	public static $version = SELF_VERSION;
	
	public static function activate() {

	    self::smsnf_create_table('egoi_map_fields', "
	        id INT(11) NOT NULL AUTO_INCREMENT, 
            wp VARCHAR(255) NOT NULL, 
            wp_name VARCHAR(255) NOT NULL, 
            egoi VARCHAR(255) NOT NULL, 
            egoi_name VARCHAR(255) NOT NULL, 
            status INT(1) NOT NULL, 
            PRIMARY KEY (id)"
        );

        self::smsnf_create_table('egoi_form_subscribers', "
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
            PRIMARY KEY (id)"
        );

		$email = wp_get_current_user();
		$email = $email->data->user_email;

		self::serviceActivate(array('email' => $email));
	}

	public static function smsnf_create_table($table, $fields) {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix.$table." (".$fields.") ".$charset_collate."; ";

        require_once(ABSPATH.'wp-admin/includes/upgrade.php');
        dbDelta($sql);

    }

	public static function serviceActivate($data = array()) {

		try{

			$params = array(
				'email' => $data['email'],
				'smegoi_v' => 'Wordpress_'.self::$version,
				'smegoi_h' => isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'],
				'smegoi_e' => get_locale(),
				'smegoi_u' => (function_exists('posix_uname') && (is_array(posix_uname()))) ? posix_uname() : ''
			);

			require 'service/post_wsdl.php';
			if(class_exists("SoapClient")){
				$response = new SoapClient(NULL, $options);
				$response->call($params);
			}else{
				$response = self::_postContent($options['location'], $params);
			}

		}catch(Exception $e){
			//continue
		}

		return true;
	}

	private static function _postContent($url, $rows) {

		$url = str_replace('service', 'post', $url);
        $ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($rows));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

        return $server_output;
    }

}
