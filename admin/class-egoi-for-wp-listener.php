<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die();
}
/**
 *  E-goi Listener 4 Syncronize Users with WP
 */
class Egoi_For_Wp_Listener {

	private $options_listen = '';
	private $user           = '';
	private $enabled        = '';

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	public function init( $user_id ) {
		if ( $this->isActive() ) {
			if ( $user_id ) {
				$this->Listen( $user_id );
			}
		}

	}

	public function isActive() {

		$options              = get_option( Egoi_For_Wp_Admin::OPTION_NAME );
		$this->options_listen = $options;

		if ( $options['enabled'] ) {
			$this->enabled = $options['enabled'];
			return $this->enabled;
		}

	}

	private function Listen( $user_id ) {
		$role = $this->options_listen['role'];

		$user = get_userdata( $user_id );

		if ( ! empty( $role ) && $role != $user->roles[0] ) {
			return $user_id; }

		$list = $this->options_listen['list'];

		$admin = new Egoi_For_Wp();

		$api = new EgoiApiV3( $this->getApikey() );

		$subscriber_tags = array();
		if ( ! empty( $user->egoi_newsletter_active ) || ! empty( $_POST['egoi_newsletter_active'] ) ) {
			$tag = $api->getTag( Egoi_For_Wp::TAG_NEWSLETTER );

			if(isset($tag->tag_id)){
				array_push($subscriber_tags, $tag->tag_id);
			}
		}

		$fields = $this->get_default_map( array_merge( $_POST, (array) $user->data ) );
		if ( get_option( 'egoi_mapping' ) ) {
			$fields = $this->mapping_extras_subscriber( $user, $user_id, $fields );
		}

		$fields['base']['email'] = ! empty( $fields['email'] ) ? $fields['email'] : $user->user_email;

		$tag = $api->getTag( $user->roles[0] );
		if(isset($tag->tag_id)){
			array_push($subscriber_tags, $tag->tag_id);
		}

		$api->addContact(
			$list,
			$fields['base']['email'],
			$fields['base']['first_name'],
			$fields['base']['last_name'],
			!empty($fields['extra']) ? $fields['extra'] : array(),
			1,
			array( 'cell' => $fields['base']['cellphone'] ),
			'active',
			$subscriber_tags
		);
	}

	private function get_default_map( $subscriber ) {
		if ( ! empty( $subscriber['billing_country'] ) ) {
			$country = $subscriber['billing_country'];
		} elseif ( isset($subscriber['shipping_country']) ) {
			$country = $subscriber['shipping_country'];
		} else {
			$country = null;
		}

		return ! empty($country) ? array(// basic info w/woocommerce
			'base'=>[
				'status'     => 'active',
				'email'      => empty( $subscriber['billing_email'] ) ? $subscriber['user_email'] : $subscriber['billing_email'],
				'cellphone'  => empty( $subscriber['billing_phone'] ) ? (empty($subscriber['shipping_phone']) ? '' : Egoi_For_Wp::smsnf_get_valid_phone( $subscriber['shipping_phone'], $country )) : Egoi_For_Wp::smsnf_get_valid_phone( $subscriber['billing_phone'], $country ),
				'first_name' => empty( $subscriber['first_name'] ) ? (empty($subscriber['billing_first_name']) ?  : $subscriber['billing_first_name']) : $subscriber['first_name'],
				'last_name'  => empty( $subscriber['last_name'] ) ? (empty($subscriber['billing_last_name']) ? '' : $subscriber['billing_last_name'] ) : $subscriber['last_name']
			]
		) : 
		array(// basic info without woocommerce
			'base'=>[
				'status'     => 'active',
				'email'      => empty( $subscriber['email'] ) ? $subscriber['user_email'] : $subscriber['email'],
				'first_name' => ! empty( $subscriber['first_name'] ) ?  $subscriber['first_name'] : '',
				'last_name'  => ! empty( $subscriber['last_name'] ) ? $subscriber['last_name'] : '',
				'cellphone'  => ''
			]
		);
	}

	private function mapping_extras_subscriber( $user, $user_id, $fields = array() ) {
		$admin = new Egoi_For_Wp();

		$all_fields                  = get_user_meta( $user_id );
		$all_fields['user_email'][0] = $user->user_email;
		$all_fields['user_url'][0]   = $user->user_url;
		$all_fields['user_login'][0] = $user->user_login;

		if ( is_array( $_POST ) ) {
			foreach ( $_POST as $key => $item ) {
				if ( strpos( $key, 'billing_' ) === false ) {
					continue; }
				$all_fields[ $key ][0] = sanitize_text_field( $item );
			}
		}

		if ( ! empty( $_POST['billing_first_name'] ) ) {
			$all_fields['billing_first_name'][0] = sanitize_text_field( $_POST['billing_first_name'] );
		}
		if ( ! empty( $_POST['billing_last_name'] ) ) {
			$all_fields['billing_last_name'][0] = sanitize_text_field( $_POST['billing_last_name'] );
		}
		if ( ! empty( $_POST['billing_phone'] ) ) {
			$all_fields['billing_phone'][0] = sanitize_text_field( $_POST['billing_phone'] );
		}
		if ( ! empty( $_POST['billing_cellphone'] ) ) {
			$all_fields['billing_cellphone'][0] = sanitize_text_field( $_POST['billing_cellphone'] );
		}

		$woocommerce = array();

		if ( class_exists( 'WooCommerce' ) ) {
			$wc = new WC_Admin_Profile();
			foreach ( $wc->get_customer_meta_fields() as $key => $value_field ) {
				foreach ( $value_field['fields'] as $key_value => $label ) {
					$row_new_value = $admin->getFieldMap( 0, $key_value );

                    if(!empty($row_new_value)) {
                        if(is_array($row_new_value) && !empty($row_new_value['egoi'])) {
                            $woocommerce[ $row_new_value['egoi'] ] = $key_value;
                        } elseif(is_object($row_new_value) && !empty($row_new_value->egoi)) {
                            $woocommerce[ $row_new_value->egoi ] = $key_value;
                        } elseif ( !is_array($row_new_value) && !is_object($row_new_value)) {
                            $woocommerce[ $row_new_value ] = $key_value;
                        }
                    }

				}
			}
		}

		foreach ( $woocommerce as $key => $value ) {
			if ( isset( $user->$value ) && ! isset( $all_fields[ $value ][0] ) ) {
				$fields['extra'][ str_replace( 'key', 'extra', $key ) ] = $user->$value;
			} elseif ( isset( $all_fields[ $value ][0] ) ) {
				$fields['extra'][ str_replace( 'key', 'extra', $key ) ] = $all_fields[ $value ][0];
			}
		}

		foreach ( $all_fields as $key => $value ) {
			$row = $admin->getFieldMap( 0, $key );

            if(!empty($row)) {
                if(is_array($row) && !empty($row['egoi'])) {
                    $row = $row['egoi'];
                } elseif(is_object($row) && !empty($row->egoi)) {
                    $row = $row->egoi;
                }
            }

			if ( !empty($row) && !is_array($row) && !is_object($row)) {
				preg_match( '/^key_[0-9]+/', $row, $output );
				if ( count( $output ) > 0 ) {
					$fields['extra'][ str_replace( 'key_', 'extra_', $row ) ] = $value[0];
				} else {
					$fields['extra'][ $row ] = $value[0];
				}
			}
		}

		return $fields;

	}

	private function getApikey()
	{
		$apikey = get_option( 'egoi_api_key' );
		if ( ! empty( $apikey['api_key'] ) ) {
			return $apikey['api_key'];
		}
		return false;
	}

}
