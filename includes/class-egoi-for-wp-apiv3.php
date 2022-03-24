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

class EgoiApiV3 {

	const LAZY_METHODS = array( 'createProduct', 'patchProduct', 'convertOrder', 'convertCart' );
	const APIV3        = 'https://api.egoiapp.com';
	const PLUGINKEY    = '908361f0368fd37ffa5cc7c483ffd941';
	const APIURLS      = array(
		'deployEmailRssCampaign'   => '/campaigns/email/rss/{campaign_hash}/actions/enable',
		'createEmailRssCampaign'   => '/campaigns/email/rss',
		'getSenders'               => '/senders/{channel}?status=active',
		'getLists'                 => '/lists?limit=10&order=desc&order_by=list_id',
		'createWebPushRssCampaign' => '/campaigns/webpush/rss',
		'deployWebPushRssCampaign' => '/campaigns/webpush/rss/{campaign_hash}/actions/enable',
		'getWebPushSites'          => '/webpush/sites',
		'getCatalogs'              => '/catalogs',
		'importProducts'           => '/catalogs/{id}/products/actions/import',
		'createCatalog'            => '/catalogs',
		'createProduct'            => '/catalogs/{catalog_id}/products',
		'patchProduct'             => '/catalogs/{catalog_id}/products/{product_id}',
		'deleteCatalog'            => '/catalogs/{id}',
		'getCountriesCurrencies'   => '/utilities/countries',
		'deleteProduct'            => '/catalogs/{catalog_id}/products/{product_id}',
		'getMyAccount'             => '/my-account',
		'createWebPushSite'        => '/webpush/sites',
		'activateTrackingEngage'   => '/my-account/actions/enable-te',
		'getConnectedSites'        => '/connectedsites',
		'createConnectedSites'     => '/connectedsites',
		'getConnectedSite'         => '/connectedsites/{domain}',
		'convertOrder'             => '/{domain}/orders',
		'convertCart'              => '/{domain}/carts',
	);
	protected $apiKey;
	protected $headers;
	public function __construct( $apiKey ) {
		$this->apiKey  = $apiKey;
		$this->headers = array( 'ApiKey: ' . $this->apiKey, 'PluginKey: ' . self::PLUGINKEY, 'Content-Type: application/json' );
	}

	public function getCountriesCurrencies( $cellphone = '' ) {
		$phone_add = empty( $cellphone ) ? '' : "?phone=$cellphone";
		$client    = new ClientHttp(
			self::APIV3 . self::APIURLS[ __FUNCTION__ ] . $phone_add,
			'GET',
			$this->headers
		);

		if ( $client->success() !== true || $client->getCode() < 200 || $client->getCode() >= 300 ) {
			return false;
		}

		return json_decode( $client->getResponse(), true );
	}

	/*
	 * 1st argument is type (POST | GET)
	 * 2nd argument is data (body | query)
	 * */
	public function __call( $name, $arguments ) {
		$path = self::APIV3 . self::APIURLS[ $name ];

		switch ( $arguments[0] ) {
			case 'DELETE':
				$client = new ClientHttp(
					$this->replaceUrl( $path, '{id}', $arguments[1] ),
					'DELETE',
					$this->headers
				);
				break;
			case 'POST':
				$client = new ClientHttp(
					$this->replaceUrl( $path, '{id}', $arguments[2] ),
					'POST',
					$this->headers,
					empty( $arguments[1] ) ? array() : $arguments[1]
				);
				break;
			case 'GET':
			default:
				if ( ! empty( $arguments[1] ) ) {
					$concat = '?' . http_build_query( $arguments[1] );
				} else {
					$concat = '';
				}
				$client = new ClientHttp(
					$path . $concat,
					'GET',
					$this->headers
				);
				break;
		}

		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}

		$resp = json_decode( $client->getResponse(), true );
		if ( $client->getCode() >= 200 && $client->getCode() < 300 ) {
			if ( isset( $resp['items'] ) ) {
				return $resp['items'];
			} elseif ( isset( $resp['catalog_id'] ) ) {
				return $resp['catalog_id'];
			} else {
				return true;
			}
		} else {
			if ( $client->getCode() == 422 ) {
				return $this->processErrors( $resp['validation_messages'] );
			}
			if ( $client->getCode() == 409 ) {
				return $this->processErrors( $resp['errors'] );
			}
			return $this->processErrors();
		}
	}

	public function deleteProduct( $catalog_id, $product_id ) {
		$path   = self::APIV3 . $this->replaceUrl( self::APIURLS[ __FUNCTION__ ], array( '{catalog_id}', '{product_id}' ), array( $catalog_id, $product_id ) );
		$client = new ClientHttp(
			$path,
			'DELETE',
			$this->headers
		);

		if ( $client->success() !== true || ( $client->getCode() >= 200 && $client->getCode() < 300 ) ) {
			return false;
		}
		return true;
	}

	public function createProduct( $data, $catalog ) {

		$path   = self::APIV3 . $this->replaceUrl( self::APIURLS[ __FUNCTION__ ], '{catalog_id}', $catalog );
		$client = new ClientHttp(
			$path,
			'POST',
			$this->headers,
			$data
		);

		if ( $client->success() !== true || $client->getCode() <= 200 || $client->getCode() > 300 ) {
			return false;
		}
		return true;
	}

	public function patchProduct( $data, $catalog, $product_id ) {
		$path   = self::APIV3 . $this->replaceUrl( self::APIURLS[ __FUNCTION__ ], array( '{catalog_id}', '{product_id}' ), array( $catalog, $product_id ) );
		$client = new ClientHttp(
			$path,
			'PATCH',
			$this->headers,
			$data
		);

		if ( $client->success() !== true || $client->getCode() <= 200 || $client->getCode() > 300 ) {
			return false;
		}
		return true;
	}

	/**
	 * @param $data
	 * @return false|string
	 */
	public function createWebPushRssCampaign( $data ) {

		$client = new ClientHttp(
			self::APIV3 . self::APIURLS[ __FUNCTION__ ],
			'POST',
			$this->headers,
			$data
		);

		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}

		return $client->getCode() == 200
			? $client->getResponse()
			: $this->processErrors( $client->getResponse() );
	}

	/**
	 * @param $id
	 * @return false|string
	 */
	public function deployWebPushRssCampaign( $id ) {
		$path   = self::APIV3 . $this->replaceUrl( self::APIURLS[ __FUNCTION__ ], '{campaign_hash}', $id );
		$client = new ClientHttp(
			$path,
			'POST',
			$this->headers,
			array()
		);

		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}

		return $client->getCode() == 200
			? $client->getResponse()
			: $this->processErrors( $client->getResponse() );
	}

	public function getWebPushSites() {
		$path = self::APIV3 . self::APIURLS[ __FUNCTION__ ];

		$client = new ClientHttp(
			$path,
			'GET',
			$this->headers
		);

		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}

		$resp = json_decode( $client->getResponse(), true );
		return $client->getCode() == 200 && isset( $resp['items'] )
			? wp_json_encode( $resp['items'] )
			: $this->processErrors();
	}

	/**
	 * @param $data
	 * @return false|string
	 */
	public function createEmailRssCampaign( $data ) {
		$client = new ClientHttp(
			self::APIV3 . self::APIURLS[ __FUNCTION__ ],
			'POST',
			$this->headers,
			$data
		);

		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}

		return $client->getCode() == 200
			? $client->getResponse()
			: $this->processErrors( $client->getError() );

	}

	/**
	 * @param $id
	 * @return false|string
	 */
	public function deployEmailRssCampaign( $id ) {
		$path   = self::APIV3 . $this->replaceUrl( self::APIURLS[ __FUNCTION__ ], '{campaign_hash}', $id );
		$client = new ClientHttp(
			$path,
			'POST',
			$this->headers,
			array()
		);

		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}

		return $client->getCode() == 200
			? $client->getResponse()
			: $this->processErrors();
	}

	/**
	 * @param string $channel
	 * @return false|string
	 */
	public function getSenders( $channel = 'email' ) {
		$path   = self::APIV3 . $this->replaceUrl( self::APIURLS[ __FUNCTION__ ], '{channel}', $channel );
		$client = new ClientHttp(
			$path,
			'GET',
			$this->headers
		);

		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}
		$resp = json_decode( $client->getResponse(), true );
		return $client->getCode() == 200 && isset( $resp['items'] )
			? wp_json_encode( $resp['items'] )
			: $this->processErrors( $client->getResponse() );
	}

	/**
	 * @return false|string
	 */
	public function getLists() {

		$client = new ClientHttp(
			self::APIV3 . self::APIURLS[ __FUNCTION__ ],
			'GET',
			$this->headers
		);

		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}

		$resp = json_decode( $client->getResponse(), true );
		return $client->getCode() == 200 && isset( $resp['items'] )
			? wp_json_encode( $resp['items'] )
			: $this->processErrors();

	}

	/**
	 * @return false|string
	 */
	public function getMyAccount() {
		$path = self::APIV3 . self::APIURLS[ __FUNCTION__ ];

		$client = new ClientHttp(
			$path,
			'GET',
			$this->headers
		);

		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}

		$resp = json_decode( $client->getResponse(), true );
		return $client->getCode() == 200 && isset( $resp['general_info']['client_id'] )
			? $resp['general_info']['client_id']
			: $this->processErrors();
	}

	/**
	 * @param $data
	 *
	 * @return false|string
	 */
	public function createWebPushSite( $data ) {
		$path   = self::APIV3 . self::APIURLS[ __FUNCTION__ ];
		$client = new ClientHttp( $path, 'POST', $this->headers, $data );

		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}

		$resp = json_decode( $client->getResponse(), true );

		if ( $client->getCode() == 201 && ! empty( $resp['app_code'] ) ) {
			return $resp['app_code'];
		} elseif ( ! empty( $resp['errors']['name_already_exists'] ) ) {
			return $this->processErrors( $resp['errors']['name_already_exists'] );
		}

		return $this->processErrors();
	}

	/**
	 * @return false|string
	 */
	public function updateSocialTrack( $method ) {
		if ( $method == 'update' ) {
			$check = get_option( 'egoi_sync' );
			if ( ! isset( $check['social_track'] ) || $check['social_track'] == 0 ) {
				return false;
			}
		}

		$domain    = preg_replace( '(^https?://)', '', get_site_url() );
		$accountId = $this->getMyAccount();
		$catalogs  = EgoiProductsBo::getCatalogsToSync();

		$data = array(
			'account_id' => $accountId,
			'domain'     => $domain,
			'catalogs'   => array(),
		);

		for ( $i = 0; $i < count( $catalogs ); $i++ ) {
			$data['catalogs'][] = $catalogs[ $i ];
		}

		if ( class_exists( 'woocommerce' ) ) {
			$data['productsPath'] = wc_get_page_permalink( 'shop' );
		}

		$requestString = 'https://egoiapp2.com/ads/' . $method . 'Pixel?' . http_build_query( $data );

		$client = new ClientHttp(
			$requestString,
			'GET'
		);
		if ( $client->success() !== true ) {
			return false;
		}
		$resp = json_decode( $client->getResponse(), true );

		return $client->getCode() == 200 && isset( $resp['data']['code'] )
			? $resp['data']['code']
			: false;
	}

		/**
		 * Add contact using API v3
		 */
	public function addContact( $listID, $email, $name = '', $lname = '', $extra_fields = array(), $option = 0, $ref_fields = array(), $status = 'active', $tags = array() ) {

		$full_name = explode( ' ', $name );
		$fname     = $full_name[0];
		if ( ! $lname ) {
			$lname = $full_name[1];
		}

		$tel  = $ref_fields['tel'];
		$cell = $ref_fields['cell'];
		$bd   = $ref_fields['bd'];
		$lang = $ref_fields['lang'];

		$params = array(
			'email'      => $email,
			'first_name' => $fname,
			'last_name'  => $lname,
			'status'     => $status,
		);

		// telephone
		if ( $tel ) {
			$params['cellphone'] = $tel;
		}
		// cellphone
		if ( $cell && ! $tel ) {
			$params['cellphone'] = $cell;
		}
		// birthdate
		if ( $bd ) {
			$params['birth_date'] = $bd;
		}
		// language
		if ( $lang ) {
			$params['language'] = $lang;
		}

		$params_extra = array();
		if ( $option ) {
			$all_extra_fields = $this->getExtraFields( $listID );
			if ( $all_extra_fields ) {

				foreach ( $extra_fields as $key => $value ) {
					$filtered_key = str_replace( array( 'key_', 'extra_' ), '', $key );
					if ( in_array( $filtered_key, $all_extra_fields ) ) {
						array_push(
							$params_extra,
							array(
								'field_id' => $filtered_key,
								'value'    => $value,
							)
						);
					}
				}
			}
		}

		if ( empty( $params_extra ) ) {
			$body = array( 'base' => $params );
		} else {
			$body = array(
				'base'  => $params,
				'extra' => $params_extra,
			);
		}

		$url = self::APIV3 . '/lists/' . $listID . '/contacts';

		$client = new ClientHttp( $url, 'POST', $this->headers, $body );

		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}

		$resp = json_decode( $client->getResponse(), true );

		if ( ! empty( $tags ) && isset( $resp['contact_id'] ) ) {
			$this->attachTag( $listID, $resp['contact_id'], $tags );
		}

		return $resp['contact_id'];
	}

	/**
	 * Attach tag to a contact using API V3
	 */
	public function attachTag( $list_id, $contact_id, $tags = array() ) {
		$url = self::APIV3 . '/lists/' . $list_id . '/contacts/actions/attach-tag';

		foreach ( $tags as $tag ) {
			$body = array(
				'contacts' => array( $contact_id ),
				'tag_id'   => $tag,
			);

			$client = new ClientHttp( $url, 'POST', $this->headers, $body );

			if ( $client->success() !== true ) {
				return $this->processErrors( $client->getError() );
			}
		}
		return true;
	}

	/**
	 * Get Tag
	 * If doesnt exists creates one tag
	 */
	public function getTag( $name ) {
		$tags = json_decode( $this->getTags() );

		if ( isset( $tags['status'] ) || isset( $tags['error'] ) ) {
			return $tags;
		} else {
			foreach ( $tags as $key => $value ) {
				if ( strcasecmp( $value->name, $name ) == 0 ) {
					$data = $value;
				}
			}

			if ( empty( $data ) ) {
				return $this->addTag( $name );
			}

			return $data;
		}
	}

	/**
	 * Get Tags
	 */
	public function getTags() {
		$url = self::APIV3 . '/tags';

			$client = new ClientHttp( $url, 'GET', $this->headers );

		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}
			$resp = json_decode( $client->getResponse(), true );

		return $client->getCode() == 200 && isset( $resp['items'] ) ? wp_json_encode( $resp['items'] ) : $this->processErrors();

	}

	/**
	 * Create new tag
	 */
	public function addTag( $name = '', $color = '#00AEDA' ) {
		$url = self::APIV3 . '/tags';

		$body = array(
			'name'  => $name,
			'color' => $color,
		);

		$client = new ClientHttp( $url, 'POST', $this->headers, $body );

		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}

		$resp = json_decode( $client->getResponse(), true );

		return $resp;
	}


		/**
		 * Check if a contact exists using API V3
		 */
	public function searchContact( $listID, $email ) {
		$url = self::APIV3 . '/contacts/search?type=email&contact=' . $email;

		$client = new ClientHttp( $url, 'GET', $this->headers );

		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}

		$result_client = json_decode( $client->getResponse(), true );

		$result = '';
		if ( ! empty( $result_client['items'] ) ) {
			foreach ( $result_client['items'] as $contact ) {
				if ( $contact['list_id'] == $listID ) {
					$result = $contact['contact_id'];
				}
			}
		}

		return $result;
	}

	/**
	 * Check if a contact exists in a list using API V3
	 */
	public function searchContactFromList( $listID, $email ) {
		$url = self::APIV3 . '/lists/' . $listID . '/contacts';

		$client = new ClientHttp( $url, 'GET', $this->headers );
		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}

		$result_client = json_decode( $client->getResponse(), true );

		$result = '';
		if ( ! empty( $result_client['items'] ) ) {
			foreach ( $result_client['items'] as $contact ) {
				if ( $contact['base']['email'] == $email ) {
					$result = $contact['base']['contact_id'];
				}
			}
		}

		return $result;
	}

	   /**
		* Check if a contact exists using API V3
		*/
	public function getExtraFields( $listID ) {
		$url = self::APIV3 . '/lists/' . $listID . '/fields';

		$client = new ClientHttp( $url, 'GET', $this->headers );

		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}

		$result_client = json_decode( $client->getResponse(), true );

		$extra_fields = array();
		foreach ( $result_client as $fields ) {
			if ( $fields['type'] == 'extra' ) {
				array_push( $extra_fields, $fields['field_id'] );
			}
		}
		return $extra_fields;
	}

	public function editContact( $listID, $contact_id, $fname = '', $lname = '', $extra_fields = array(), $option = 0, $ref_fields = array(), $status = 'active', $tags = array() ) {
		$params = array(
			'status' => $status,
		);

		// first name
		if ( $fname ) {
			$params['first_name'] = $fname;
		}

		// last name
		if ( $lname ) {
			$params['last_name'] = $lname;
		}

		$tel  = $ref_fields['tel'];
		$cell = $ref_fields['cell'];
		$bd   = $ref_fields['bd'];
		$lang = $ref_fields['lang'];

		// telephone
		if ( $tel ) {
			$params['cellphone'] = $tel;
		}
		// cellphone
		if ( $cell && ! $tel ) {
			$params['cellphone'] = $cell;
		}
		// birthdate
		if ( $bd ) {
			$params['birth_date'] = $bd;
		}
		// language
		if ( $lang ) {
			$params['language'] = $lang;
		}

		$params_extra = array();
		if ( $option ) {
			$all_extra_fields = $this->getExtraFields( $listID );
			if ( $all_extra_fields ) {

				foreach ( $extra_fields as $key => $value ) {
					$filtered_key = str_replace( array( 'key_', 'extra_' ), '', $key );
					if ( in_array( $filtered_key, $all_extra_fields ) ) {
						array_push(
							$params_extra,
							array(
								'field_id' => $filtered_key,
								'value'    => $value,
							)
						);
					}
				}
			}
		}

		if ( empty( $params_extra ) ) {
			$body = array( 'base' => $params );
		} else {
			$body = array(
				'base'  => $params,
				'extra' => $params_extra,
			);
		}

		$url = self::APIV3 . '/lists/' . $listID . '/contacts/' . $contact_id;

		$client = new ClientHttp( $url, 'PATCH', $this->headers, $body );
		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}

		if ( ! empty( $tags ) && isset( $contact_id ) ) {
			$this->attachTag( $listID, $contact_id, $tags );
		}

		return $contact_id;

	}

	public function getConnectedSite( $domain ) {
		$path   = self::APIV3 . $this->replaceUrl( self::APIURLS[ __FUNCTION__ ], array( '{domain}' ), array( $domain ) );
		$client = new ClientHttp(
			$path,
			'GET',
			$this->headers
		);

		if ( $client->success() !== true || $client->getCode() != 200 ) {
			return false;
		}
		return json_decode( $client->getResponse(), true );
	}

	private static function getProductsFromOrder( $order ) {
		$output = array();
		$items  = $order->get_items();
		if ( empty( $items ) || ! is_array( $items ) ) {
			$items = array();
		}
		foreach ( $items as $item ) {
			$output[] = array(
				'product_identifier' => $item->get_product_id(),
				'name'               => $item->get_name(),
				'price'              => number_format( $item->get_subtotal(), 2 ),
			);
		}
		return $output;
	}

	public function convertOrder( $order, $contact, $domain ) {
		$path = self::APIV3 . $this->replaceUrl( self::APIURLS[ __FUNCTION__ ], '{domain}', $domain );

		$products = self::getProductsFromOrder( $order );

		$payload = array(
			'order_total' => number_format( $order->get_total(), 2 ),
			'order_id'    => $order->get_id(),
			'cart_id'     => '',
			'contact'     => $contact,
			'products'    => $products,
		);

		$client = new ClientHttp(
			$path,
			'POST',
			$this->headers,
			$payload
		);

		if ( $client->success() !== true || $client->getCode() != 202 ) {
			return false;
		}
		return json_decode( $client->getResponse(), true );
	}

	private static function getProductsFromCart( $cartObj, $variations = false ) {
		$cart = array(
			'total'    => 0,
			'products' => array(),
		);
		foreach ( $cartObj as $cart_item ) {
			$cart['products'][] = array(
				'product_identifier' => ( $variations && ! empty( $cart_item['variation_id'] ) ) ? $cart_item['variation_id'] : $cart_item['product_id'],
				'name'               => str_replace( '"', '\\"', $cart_item['data']->get_title() ),
				'price'              => number_format( $cart_item['data']->get_price(), 2 ),
			);

			$cart['total'] += ( number_format( $cart_item['data']->get_price(), 2 ) * (int) $cart_item['quantity'] );
		}

		return $cart;
	}

	public function convertCart( $cart, $contact, $domain, $variations = false ) {
		$path = self::APIV3 . $this->replaceUrl( self::APIURLS[ __FUNCTION__ ], '{domain}', $domain );
		if ( empty( $contact ) || empty( $contact['base'] ) ) {
			return false;
		}
		$cartData = self::getProductsFromCart( $cart, $variations );
		$payload  = array(
			'cart_total' => $cartData['total'],
			'cart_id'    => '',
			'contact'    => $contact,
			'products'   => $cartData['products'],
		);

		$client = new ClientHttp(
			$path,
			'POST',
			$this->headers,
			$payload
		);

		if ( $client->success() !== true || $client->getCode() != 202 ) {
			return false;
		}
		return json_decode( $client->getResponse(), true );
	}

    public function getWebpushSiteIdFromCS($domain, $list){
        $domainData = $this->getConnectedSite( $domain );

        if(!empty($domainData) && !empty($domainData['features']) && !empty($domainData['features']['web_push']) && !empty($domainData['features']['web_push']['enabled'])){
            return [
                'list_id' => $list,
                'site_id' => $domainData['features']['web_push']['items'][0]['site_id'],
                'site' => $domain
            ];
        }
        return false;
    }



	/**
	 * @param $url
	 * @param $search
	 * @param $replace
	 * @return null|string|string[]
	 */
	protected function replaceUrl( $url, $search, $replace ) {
		if ( is_array( $replace ) ) {
			foreach ( $replace as $key => $value ) {
				$url = $this->privReplaceUrl( $url, $search[ $key ], $replace[ $key ] );
			}
			return $url;
		} else {
			return $this->privReplaceUrl( $url, $search, $replace );
		}
	}

	/**
	 * @param bool $error
	 * @return false|string
	 */
	private function processErrors( $error = false ) {
		if ( $error == false ) {
			return wp_json_encode( array( 'status' => 'error' ) );
		} else {
			return wp_json_encode( array( 'error' => $error ) );
		}
	}

	/**
	 * @param $url
	 * @param $search
	 * @param $replace
	 * @return null|string|string[]
	 */
	private function privReplaceUrl( $url, $search, $replace ) {
		return preg_replace( "/$search/", "$replace", $url );
	}

}
require_once plugin_dir_path( __FILE__ ) . 'class-egoi-for-wp-lazy.php';
class ClientHttp {

	protected $headers;
	protected $response;
	protected $err;
	protected $http_code;


	public function __construct( $url, $method = 'GET', $headers = array( 'Accept: application/json' ), $body = '' ) {
		if ( EgoiLazyConverter::methodIsLazyApiv3( $url ) ) {
			$lazy = new EgoiLazyConverter();
			$lazy->saveRequest( EgoiLazyConverter::createRequest( $url, $method, $body, $headers ) );
			$this->http_code = 101;
			$this->response  = '{}';
			$this->headers   = array();
			return;
		}
		$res = wp_remote_request(
			$url,
			array(
				'method'  => $method,
				'timeout' => 30,
				'body'    => $body,
				'headers' => $headers,
			)
		);

		if ( is_wp_error( $res ) ) {
			$this->http_code = 400;
			$this->response  = '{}';
			$this->headers   = array();
		} else {
			$this->http_code = $res['response']['code'];
			$this->response  = $res['body'];
			$this->headers   = $res['headers'];
		}

	}

	public function success() {
		if ( empty( $this->err ) ) {
			return true;
		}
		return $this->err;
	}

	public function getError() {
		return $this->err;
	}

	public function getCode() {
		return $this->http_code;
	}

	public function getResponse() {
		return $this->response;
	}
	public function getHeaders() {
		return $this->headers;
	}

	public function __toString() {
		return wp_json_encode(
			array(
				'code'     => $this->getCode(),
				'response' => $this->getResponse(),
				'error'    => $this->success(),
			)
		);
	}

}
