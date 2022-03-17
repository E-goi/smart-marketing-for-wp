<?php
require_once plugin_dir_path( __FILE__ ) . 'class-egoi-for-wp-apiv3.php';

class EgoiLazyConverter {

	const TABLE_NAME = 'egoi_lazy_request';

	public static function createRequest( $url, $type, $body, $headers = '' ) {

		if ( ! in_array( $type, array( 'GET', 'POST', 'PATCH', 'PUT', 'DELETE', 'SOAP' ) ) ) {
			return false;
		}

		return array(
			'url'     => $url,
			'type'    => $type,
			'body'    => is_array( $body ) ? json_encode( $body ) : $body,
			'headers' => is_array( $headers ) ? json_encode( $headers ) : $headers,
		);
	}

	/**
	 * @param $request
	 * @return void
	 */
	public function saveRequest( $request ) {
		global $wpdb;
		$wpdb->insert( $wpdb->prefix . self::TABLE_NAME, $request );
		return $wpdb->insert_id;
	}

	public function countRequestsWaiting() {
		global $wpdb;
		$sql = 'SELECT COUNT(*) AS total FROM ' . $wpdb->prefix . self::TABLE_NAME;
		return $wpdb->get_row( $sql )->total;
	}

	public function getRequests( $num = 10 ) {
		global $wpdb;
		return $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . self::TABLE_NAME . " ORDER BY 1 ASC LIMIT $num", ARRAY_A );
	}

	public static function methodIsLazyApiv3( $url ) {
		$options = get_option( 'egoi_sync' );
		$method  = self::getApiv3MethodByUrl( $url );
		return ! empty( $options['lazy_sync'] ) && in_array( $method, EgoiApiV3::LAZY_METHODS ) && ! wp_doing_cron();
	}

	private static function getApiv3MethodByUrl( $url ) {
		$url = str_replace( EgoiApiV3::APIV3, '', $url );
		foreach ( EgoiApiV3::APIURLS as $method => $urlPlace ) {
			$regex = preg_replace( '/\{[a-zA-Z0-9\_]+\}/', '[a-zA-Z0-9_-]+', $urlPlace );
			$regex = str_replace( '/', '\/', $regex );
			preg_match( "/^$regex$/", $url, $output_array );
			if ( ! empty( $output_array ) && count( $output_array ) == 1 ) {
				return $method;
			}
		}
		return false;
	}

	public function cleanRequestByID( $id ) {
		global $wpdb;
		$wpdb->delete( $wpdb->prefix . self::TABLE_NAME, array( 'id' => $id ) );
	}

	public function cleanRequestsBulk( $ids = array() ) {
		if ( empty( $ids ) || ! is_array( $ids ) ) {
			return false;
		}
		foreach ( $ids as $id ) {
			$this->cleanRequestByID( $id );
		}

		return true;
	}

}
