<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}
class EgoiConverter {

	protected $options;
	public function __construct( $options ) {
		$this->options = $options;
	}

	public function convertCart( $variations = false ) {
		if ( empty( $this->options['backend_order'] ) || empty( $this->options['domain'] ) ) {
			return false;
		}
		$cartObj = WC()->cart->get_cart();

		if ( count( $cartObj ) == 0 ) {
			return false;
		}

		require_once plugin_dir_path( __FILE__ ) . '../includes/class-egoi-for-wp-apiv3.php';
		$api = new EgoiApiV3( $this->getApikey() );
		$api->convertCart( $cartObj, $this->getContactObjectTE(), $this->options['domain'], $variations );
	}

	public function convertOrder( $orderid ) {
		$orderObj = wc_get_order( $orderid );
		require_once plugin_dir_path( __FILE__ ) . '../public/includes/TrackingEngageSDK.php';
		$orderHasStatus = $orderObj->has_status( str_replace( 'wc-', '', $this->options['backend_order_state'] ) ) || $orderObj->has_status( $this->options['backend_order_state'] );
		
		if ( empty( $this->options['backend_order'] ) || ! $orderHasStatus ) {
			// ignore conversion until order has status
			return false;
		}

		require_once plugin_dir_path( __FILE__ ) . '../includes/class-egoi-for-wp-apiv3.php';
		$api = new EgoiApiV3( $this->getApikey() );
		$api->convertOrder( $orderObj, $this->getContactObjectFromOrder( $orderObj ), $this->options['domain'] );
		return true;
	}

	public function getContactObjectFromOrder( $orderObj ) {
		$orderData = $orderObj->get_data();
	
		return array(
			'base' => array(
				'email'      => self::getFromBillingOrShipping( $orderData, 'email' ),
				'cellphone'  => !empty( self::getFromBillingOrShipping( $orderData, 'phone' )) ? Egoi_For_Wp::smsnf_get_valid_phone( self::getFromBillingOrShipping( $orderData, 'phone' ), self::getFromBillingOrShipping( $orderData, 'country' ) ) : '',
				'first_name' => self::getFromBillingOrShipping( $orderData, 'first_name' ),
				'last_name'  => self::getFromBillingOrShipping( $orderData, 'last_name' ),
			),
		);
	}

	private static function getFromBillingOrShipping( &$orderData, $key ) {
		return empty( $orderData['billing'][ $key ] ) ? $orderData['shipping'][ $key ] : $orderData['billing'][ $key ];
	}

	private function getContactObjectTE() {
		require_once plugin_dir_path( __FILE__ ) . '../public/includes/TrackingEngageSDK.php';
		return array( 'base' => TrackingEngageSDK::getSubInfo() );
	}

	private function getApikey() {
		$apikey = get_option( 'egoi_api_key' );
		if ( isset( $apikey['api_key'] ) && ( $apikey['api_key'] ) ) {
			return $apikey['api_key'];
		}
		return null;
	}

}
