<?php
/**
 * Created by PhpStorm.
 * User: tmota
 * Date: 25/07/2019
 * Time: 16:44
 */

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die();
}


class EgoiValidators {

	/**
	 * validate id
	 *
	 * @param $id
	 * @return string
	 */
	public static function validate_id( $id ) {
		$id = trim( $id );
		if ( empty( $id ) ) {
			wp_send_json_error( __( 'The id can\'t be empty!', 'egoi-for-wp' ) );
		}
		if ( ! is_numeric( $id ) || $id <= 0 ) {
			wp_send_json_error( sprintf( __( 'The id %s is not valid', 'egoi-for-wp' ), $id ) );
		}
		return $id;
	}

	/**
	 * validate page number
	 *
	 * @param $page
	 * @return int
	 */
	public static function validate_page( $page ) {
		if ( empty( $page ) ) {
			return 0;
		}
		if ( ! is_numeric( $page ) || $page <= 0 ) {
			wp_send_json_error( sprintf( __( 'The page %s is not valid', 'egoi-for-wp' ), $page ) );
		}
		return $page;
	}
}
