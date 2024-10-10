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

	const EFWP_COUNTRY_CODES      = array(
		'+93’, 
		’+27',
		'+355',
		'+49',
		'+213',
		'+376',
		'+244',
		'+12684',
		'+1268',
		'+966',
		'+54',
		'+374',
		'+297',
		'+61',
		'+43',
		'+994',
		'+1242',
		'+973',
		'+880',
		'+1246',
		'+32',
		'+501',
		'+229',
		'+1441',
		'+375',
		'+591',
		'+599',
		'+387',
		'+267',
		'+55',
		'+673',
		'+359',
		'+226',
		'+257',
		'+975',
		'+238',
		'+237',
		'+855',
		'+1',
		'+7',
		'+235',
		'+56',
		'+86',
		'+357',
		'+379',
		'+57',
		'+269',
		'+243',
		'+242',
		'+850',
		'+82',
		'+225',
		'+506',
		'+385',
		'+53',
		'+599',
		'+246',
		'+45',
		'+1767',
		'+20',
		'+503',
		'+971',
		'+593',
		'+291',
		'+421',
		'+386',
		'+34',
		'+372',
		'+251',
		'+1',
		'+679',
		'+63',
		'+358',
		'+33',
		'+241',
		'+220',
		'+233',
		'+995',
		'+350',
		'+1473',
		'+30',
		'+299',
		'+590',
		'+1671',
		'+502',
		'+592',
		'+594',
		'+224',
		'+240',
		'+245',
		'+509',
		'+504',
		'+852',
		'+36',
		'+967',
		'+44',
		'+1345',
		'+682',
		'+500',
		'+298',
		'+1670',
		'+692',
		'+677',
		'+1340',
		'+1284',
		'+91',
		'+62',
		'+98',
		'+964',
		'+353',
		'+354',
		'+972',
		'+39',
		'+1876',
		'+81',
		'+253',
		'+962',
		'+965',
		'+856',
		'+266',
		'+371',
		'+961',
		'+231',
		'+218',
		'+423',
		'+370',
		'+352',
		'+853',
		'+389',
		'+261',
		'+60',
		'+265',
		'+960',
		'+223',
		'+356',
		'+212',
		'+596',
		'+230',
		'+222',
		'+262',
		'+52',
		'+95',
		'+691',
		'+258',
		'+373',
		'+377',
		'+976',
		'+382',
		'+1664',
		'+264',
		'+674',
		'+977',
		'+505',
		'+227',
		'+234',
		'+683',
		'+47',
		'+687',
		'+64',
		'+968',
		'+31',
		'+680',
		'+970',
		'+507',
		'+675',
		'+92',
		'+595',
		'+51',
		'+689',
		'+48',
		'+351',
		'+1',
		'+974',
		'+254',
		'+7',
		'+686',
		'+44',
		'+236',
		'+420',
		'+1',
		'+262',
		'+40',
		'+250',
		'+7',
		'+290',
		'+1869',
		'+508',
		'+685',
		'+1684',
		'+1758',
		'+378',
		'+239',
		'+1784',
		'+248',
		'+221',
		'+232',
		'+381',
		'+65',
		'+599',
		'+963',
		'+252',
		'+94',
		'+268',
		'+249',
		'+46',
		'+41',
		'+597',
		'+66',
		'+886',
		'+992',
		'+255',
		'+670',
		'+228',
		'+676',
		'+1868',
		'+216',
		'+1649',
		'+993',
		'+90',
		'+688',
		'+380',
		'+256',
		'+598',
		'+998',
		'+678',
		'+58',
		'+84',
		'+681',
		'+260',
		'+263',
	);
	const LAZY_METHODS = array( 'createProduct', 'patchProduct', 'convertOrder', 'convertCart' );
	const APIV3        = 'https://api.egoiapp.com';
	const PLUGINKEY    = '908361f0368fd37ffa5cc7c483ffd941';
	const APIURLS      = array(
		'deployEmailRssCampaign'   => '/campaigns/email/rss/{campaign_hash}/actions/enable',
		'createEmailRssCampaign'   => '/campaigns/email/rss',
		'getSenders'               => '/senders/{channel}?status=active',
		'getLists'                 => '/lists?limit=100&order=desc&order_by=list_id',
		'createList'               => '/lists',
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
		'importContactsBulk'       => '/lists/{list_id}/contacts/actions/import-bulk',
		'ping'					   => '/ping',
	);

	protected $apiKey;
	protected $headers;
	public function __construct( $apiKey ) {
		$this->apiKey  = $apiKey;
		$this->headers = array( 'ApiKey: ' . $this->apiKey, 'PluginKey: ' . self::PLUGINKEY, 'Content-Type: application/json' );
	}

	public function getCountriesCurrencies( $cellphone = '' ) {
		
		$phone_add = empty( $cellphone ) ? '' : "?phone=$cellphone";

		$url = self::APIV3 . self::APIURLS[ __FUNCTION__ ] . $phone_add;

		if ( $this->_hasCachedResponse( $url ) ) {
			return $this->_cachedResponse( $url );
		}

		$client    = new ClientHttp(
			$url,
			'GET',
			$this->headers
		);

		if ( $client->success() !== true || $client->getCode() < 200 || $client->getCode() >= 300 ) {
			return false;
		}

		$response = json_decode( $client->getResponse(), true );

		$this->_cacheCreatorHandler( $url, $response );

		return $response;
	}

	/**
     * @param $cellphone
     * @return array|mixed|string|string[]
     */
	public function advinhometerCellphoneCode( $cellphone ) {
		if ( empty( $cellphone ) ) {
			return '';
		}

		preg_match( '/[0-9]{1,3}-/', $cellphone, $pregged );
		if ( ! empty( $pregged ) ) {
			return $cellphone;
		}

		if ( strpos( $cellphone, '+' ) !== false ) {
			foreach ( self::EFWP_COUNTRY_CODES as $code ) {
				if ( strpos( $cellphone, $code ) !== false ) {
					$cellphone = str_replace( $code, $code . '-', $cellphone );
					return $cellphone;
				}
			}
		}

		$data = $this->getCountriesCurrencies( $cellphone );
		if ( empty( $data ) ) {
			return $cellphone;
		}
		$language = get_option( 'WPLANG' );
		foreach ( $data['items'] as $country ) {
			if ( strpos( $language, $country['iso_code'] ) !== false ) {
				return $country['country_code'] . '-' . $cellphone;
			}
		}

		return $cellphone;
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

	/**
	 * Creates a product in the E-goi API.
	 *
	 * @param array  $data    The data of the product to be created.
	 * @param string $catalog The ID of the catalog where the product will be created.
	 *
	 * @return bool Returns true if the product was created successfully, false otherwise.
	 */
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
		$url   = self::APIV3 . $this->replaceUrl( self::APIURLS[ __FUNCTION__ ], '{channel}', $channel );

		if ( $this->_hasCachedResponse( $url ) ) {
			return $this->_cachedResponse( $url );
		}

		$client = new ClientHttp(
			$url,
			'GET',
			$this->headers
		);

		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}
		$resp = json_decode( $client->getResponse(), true );

		if($client->getCode() == 200 && isset( $resp['items'] )){
			$return = wp_json_encode( $resp['items'] );
			$this->_cacheCreatorHandler( $url, $return );
			return $return;
		} else {
			return $this->processErrors( $client->getResponse() );
		}
	}

	/**
	 * @return false|string
	 */
	public function getLists() {

		$url = self::APIV3 . self::APIURLS[ __FUNCTION__ ];

		$client = new ClientHttp(
			$url,
			'GET',
			$this->headers
		);

		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}

		$resp = json_decode( $client->getResponse(), true );

		if($client->getCode() == 200 && isset( $resp['items'] )){
			$return = $resp['items'];
			return $return;
		} else {
			return $this->processErrors( $client->getResponse() );
		}

	}

	/**
	 * @return false|string
	 */
	public function getMyAccount( $clientId = true) {
		$url = self::APIV3 . self::APIURLS[ __FUNCTION__ ];

		if ( $this->_hasCachedResponse( $url ) ) {
			return $this->_cachedResponse( $url );
		}

		$client = new ClientHttp(
			$url,
			'GET',
			$this->headers
		);

		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}

		$resp = json_decode( $client->getResponse(), true );

		if($client->getCode() == 200 && isset( $resp['general_info']['client_id'])){
			$return = $clientId ? $resp['general_info']['client_id'] : $resp;
			$this->_cacheCreatorHandler( $url, $return );
			return $return;
		} else {
			return $this->processErrors();
		}
	}

	public function ping( ) {

		$url = self::APIV3 . self::APIURLS[ __FUNCTION__ ];

		if ( $this->_hasCachedResponse( $url ) ) {
			return $this->_cachedResponse( $url );
		}

		try {
			wp_remote_post(
				$url,
				array(
					'body'    => wp_json_encode( array() ),
					'headers' => array(
						'Content-Type' => 'application/json',
						'Pluginkey'    => self::PLUGINKEY,
						'Apikey'       => $this->apiKey,
					),
				)
			);

			$this->_cacheCreatorHandler( $url, true );
	
			return true;

		} catch ( Exception $e ) {
			return false;
		}
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
	 * Import Contacts Bulk
	 * @param $listId
	 * @param $data
	 * @return false|string
	 */
	public function importContactsBulk( $listId, $data ) {
		$path   = self::APIV3 . $this->replaceUrl( self::APIURLS[ __FUNCTION__ ], '{list_id}', $listId );

      $ch = curl_init();

      // Set the URL
      curl_setopt($ch, CURLOPT_URL, $path);

      // Set the request method
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

      // Set the timeout
      curl_setopt($ch, CURLOPT_TIMEOUT, 30);

      // Set the request body if provided
      if (!empty($data)) {
         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
      }

      // Set headers if provided
      curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);


      // Return the response instead of outputting it
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      // Execute the request
      curl_exec($ch);

      // Get the HTTP status code
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

      // Close the cURL session
      curl_close($ch);

		return $httpCode == 200
			? true
			: false;
	}

	/**
	 * Create List
	 * @param $name
	 * @return false|string
	 */
	public function createList( $name )
	{
		$path   = self::APIV3 . self::APIURLS[ __FUNCTION__ ];
		$client = new ClientHttp(
			$path,
			'POST',
			$this->headers,
			array(
				'internal_name' => $name,
				'public_name'   => $name,
			)
		);

		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}
		$resp = json_decode( $client->getResponse(), true );

		if($client->getCode() == 201 && isset( $resp['list_id'] )){
			$urlGetLists = self::APIV3 . self::APIURLS[ 'getLists' ];

			if ( $this->_hasCachedResponse( $urlGetLists ) && ! empty( $this->_cachedResponse( $urlGetLists ) ) ) {
				$this->_purgeCache( $urlGetLists );
			}

			return $resp['list_id'];
		} else {
			return $this->processErrors( $client->getResponse() );
		}
	}

	/**
	 * Add contact using API v3
	*/
	public function addContact( $listID, $email, $name = '', $lname = '', $extra_fields = array(), $option = 0, $ref_fields = array(), $status = 'active', $tags = array() ) {

		$full_name = explode( ' ', $name );
		$fname = $name;

		if(! $lname  && sizeof($full_name) > 1 ){

			$lname = end($full_name);
			$fname = implode(' ', array_slice($full_name,0,-1));
			
		}

		$tel  = (isset($ref_fields['tel']) && !empty($ref_fields['tel']) && $ref_fields['tel'] != '-') ? $this->advinhometerCellphoneCode($ref_fields['tel']) : '';
		$cell = (isset($ref_fields['cell']) && !empty($ref_fields['cell']) && $ref_fields['cell'] != '-' ) ? $this->advinhometerCellphoneCode($ref_fields['cell']) : '';
		$bd   = isset($ref_fields['bd']) ? $ref_fields['bd'] : '';
		$lang = isset($ref_fields['lang']) ? $ref_fields['lang'] : '';

		$params = array(
			'email'      => $email,
			'first_name' => $fname,
			'last_name'  => $lname,
			'status'     => $status,
		);

		// telephone
		if ( !empty($tel) ) {
			$params['cellphone'] = $tel;
		}
		// cellphone
		if ( !empty($cell) && empty($tel) ) {
			$params['cellphone'] = $cell;
		}
		// birthdate
		if ( !empty($bd) ) {
			$params['birth_date'] = $bd;
		}
		// language
		if ( !empty($lang) ) {
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

		$path = self::APIV3 . '/lists/' . $listID . '/contacts';

		$ch = curl_init();
  
		// Set the URL
		curl_setopt($ch, CURLOPT_URL, $path);
  
		// Set the request method
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  
		// Set the timeout
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  
		// Set the request body if provided
		if (!empty($body)) {
		   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
		}
  
		// Set headers if provided
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
  
  
		// Return the response instead of outputting it
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
		// Execute the request
		$resp = curl_exec($ch);

		$resp = json_decode($resp, true);
  
		// Get the HTTP status code
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  
		// Close the cURL session
		curl_close($ch);
  
		if($httpCode == 409){
			return  $this->editContact( $listID, $resp['errors']['contacts'][0], $name, $lname, $extra_fields, $option, $ref_fields, $status, $tags );
		} elseif ( $httpCode == 201){
			if ( ! empty( $tags ) && isset( $resp['contact_id'] ) ) {
				$this->attachTag( $listID, $resp['contact_id'], $tags );
			}
			return $resp['contact_id'];
		}

			
		return false;
		
	}

	public function attachTag( $list_id, $contact_id, $tags = array() ) {
		$path = self::APIV3 . '/lists/' . $list_id . '/contacts/actions/attach-tag';

		foreach ( $tags as $tag ) {
			$body = array(
				'contacts' => array( $contact_id ),
				'tag_id'   => $tag,
			);

			$ch = curl_init();
	  
			// Set the URL
			curl_setopt($ch, CURLOPT_URL, $path);
	  
			// Set the request method
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	  
			// Set the timeout
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	  
			// Set the request body if provided
			if (!empty($body)) {
			   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
			}
	  
			// Set headers if provided
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
	  
	  
			// Return the response instead of outputting it
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  
			// Execute the request
			$resp = curl_exec($ch);
	
			$resp = json_decode($resp, true);
	  
			// Get the HTTP status code
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			if($httpCode != 200){
				return false;
			}
	  
			// Close the cURL session
			curl_close($ch);
	  
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
	 * Get Tag
	 * If doesnt exists creates one tag
	 */
	public function getTagById( $id ) {
		$tags = json_decode( $this->getTags() );

		$data = array();
		if ( isset( $tags['status'] ) || isset( $tags['error'] ) ) {
			return $data;
		} else {
			foreach ( $tags as $key => $value ) {
				if ( strcasecmp( $value->tag_id, $id ) == 0 ) {
					$data = $value;
				}
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
	public function getExtraFields( $listID, $type = 'id' ) {
		$url = self::APIV3 . '/lists/' . $listID . '/fields';

		$client = new ClientHttp( $url, 'GET', $this->headers );

		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}

		$result_client = json_decode( $client->getResponse(), true );

		$extra_fields = array();
		foreach ( $result_client as $fields ) {
			if ( $fields['type'] == 'extra' && $type == 'id' ) {
				array_push( $extra_fields, $fields['field_id'] );
			} else if ( $fields['type'] == 'extra' ){
				array_push( $extra_fields, $fields );
			}
		}
		return $extra_fields;
	}

	public function editContact( $listID, $contact_id, $fname = '', $lname = '', $extra_fields = array(), $option = 0, $ref_fields = array(), $status = 'active', $tags = array() ) {

		if(! $lname){
			$full_name = explode( ' ', $fname );
			$lname =  sizeof($full_name) == 1 ? ' ' : end($full_name);
			$fname = sizeof($full_name) > 1 ? implode(' ', array_slice($full_name,0,-1)) : $fname;
			$lname = trim($lname);
			$fname = trim($fname);
		}

		$params = array();

		$tel  = isset($ref_fields['tel']) ? $ref_fields['tel'] : '';
		$cell = isset($ref_fields['cell']) ? $ref_fields['cell'] : '';
		$bd   = isset($ref_fields['bd']) ? $ref_fields['bd'] : '';
		$lang = isset($ref_fields['lang']) ? $ref_fields['lang'] : '';

		//status
		if(!empty($status)) {
			$params['status'] = $status;
		}

		// first name
		if(!empty($fname)) {
			$params['first_name'] = $fname;
		}

		// last name
		if(!empty($lname)) {
			$params['last_name'] = $lname;
		}

		// telephone
		if (!empty($tel) ) {
			$params['cellphone'] = $tel;
		}
		// cellphone
		if (!empty($cell) && empty($tel) ) {
			$params['cellphone'] = $cell;
		}
		// birthdate
		if (!empty($bd) ) {
			$params['birth_date'] = $bd;
		}
		// language
		if (!empty($lang) ) {
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

	public function getTotalContacts( $listId )
	{
		$url = self::APIV3 . '/lists/' . $listId . '/contacts?limit=1';

		$client = new ClientHttp( $url, 'GET', $this->headers );
		if ( $client->success() !== true ) {
			return $this->processErrors( $client->getError() );
		}

		$result_client = json_decode( $client->getResponse(), true );

		return $client->getCode() == 200 && isset( $result_client['total_items'] ) ? $result_client['total_items'] : $this->processErrors();
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

	private function _cacheCreatorHandler( $url, $body ) {
		$this->_saveCache( $url, $body );
	}

	private function _cachedResponse( $url ) {
		$cached = get_option( self::generateCacheKey( $url ) );
		return empty( $cached['data'] ) ? array() : $cached['data'];
	}

	private function _hasCachedResponse( $url ) {

		$cached = get_option( self::generateCacheKey( $url ) );

		if( $cached && isset( $cached['ttl'] ) && $cached['ttl'] < time() ){
			$this->_purgeCache( $url );
			return false;
		}

		return ! empty( $cached ) && $cached['ttl'] > time();
	}

	private function _saveCache( $url, $resp, $ttl = 3600 ) {
		$key = self::generateCacheKey( $url );
		update_option(
			$key,
			array(
				'data' => $resp,
				'ttl'  => time() + $ttl,
			)
		);
	}

	private function _purgeCache( $url ) {
		delete_option(
			self::generateCacheKey( $url )
		);
	}

	private static function generateCacheKey( $url ) {
		return 'egoi:cache:' . hash( 'sha256', $url );
	}

	/**
	 * @param $url
	 * @param array $headers
	 * @return string
	 */
	protected function _getContent( $url, $headers = array() ) {

		if ( $this->_hasCachedResponse( $url ) ) {
			return $this->_cachedResponse( $url );
		}

		$res = wp_remote_request(
			$url,
			array(
				'method'  => 'GET',
				'timeout' => 30,
				'headers' => $headers,
			)
		);

		if ( is_wp_error( $res ) ) {
			return '{}';
		}

		$this->_cacheCreatorHandler( $url, $res['body'] );
		return $res['body'];
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
