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
require_once plugin_dir_path( __FILE__ ) . 'class-egoi-for-wp-apiv3.php';

class EgoiProductsBo {

	protected $api;
	const DEFAULT_CATALOG_OPTIONS = array( 'variations' => 0 );

    /**
     * construct
     */
	public function __construct() {
		$this->api = new EgoiApiV3( self::getApikey() );
	}

    /**
     * @param $cellphone
     * @return array|mixed|string|string[]
     */
	public function advinhometerCellphoneCode( $cellphone ) {
		return $this->api->advinhometerCellphoneCode( $cellphone );
	}

	/**
	 * From api response to Ajax response (Countries and Languages)
	 *
	 * @return array|bool|mixed
	 */
	public function getCountriesCurrencies() {
		$data = $this->api->getCountriesCurrencies();
		if ( ! empty( $data['items'] ) ) {
			$return = array(
				'countries'  => array(),
				'currencies' => array(),
			);
			foreach ( $data['items'] as $item ) {
				$return['countries'][]  = array(
					'value' => $item['iso_code'],
					'name'  => $item['name'],
				);	

				if ($item['name'] == 'Angola') {
					$return['currencies'][] = 'AOA';
				} else {
					$return['currencies'][] = $item['currency'];
				}
			}

			$return['currencies'] = array_unique( $return['currencies'] );
			return $return;
		}
		return $data;
	}

	/*
	 * Gets Catalogs by page
	 * */
	public function getCatalogsTable( $page = 0, $limit = 10 ) {
		$catalogs = $this->api->getCatalogs(
			'GET',
			array(
				'page'  => $page,
				'limit' => $limit,
			)
		);

		if ( ! is_array( $catalogs ) ) {
			return array();
		}

		$store_catalogs_id = self::getWordpressCatalogs();
		foreach ( $catalogs as $key => $catalog ) {
			if ( in_array( $catalog['catalog_id'], $store_catalogs_id ) ) {
				$catalogs[ $key ]['origin']  = 'WordPress';
				$catalogs[ $key ]['options'] = self::getCatalogOptions( $catalog['catalog_id'] );
			} else {
				$catalogs[ $key ]['origin']  = 'e-goi';
				$catalogs[ $key ]['options'] = self::getCatalogOptions( $catalog['catalog_id'] );
			}
		}

		return $catalogs;
	}

    /**
     * Creates a catalog
     *
     * @param $title
     * @param $language
     * @param $currency
     * @param $options
     * @return bool
     */
	public function createCatalog( $title, $language, $currency, $options ) {
		$response = $this->api->createCatalog(
			'POST',
			array(
				'title'    => 'Wordpress ' . $title,
				'language' => $language,
				'currency' => $currency
			)
		);
		if ( ! is_numeric( $response ) ) {
			return false;
		}

		self::setWordpressCatalog( $response );
		self::setCatalogOptions( $response, $options );
		$this->api->updateSocialTrack( 'update' );

		return $response;
	}

    /**
     * @param $id
     * @return mixed
     */
	public function deleteCatalog( $id ) {
		$response = $this->api->deleteCatalog(
			'DELETE',
			$id
		);

		$this->api->updateSocialTrack( 'update' );

		return $response;
	}

	/**
	 * Imports 1000 products to a catalog
	 *
	 * @param int $catalog_id
	 * @param int $page
	 * @return mixed
	 */
	public function importProductsCatalog( $catalog_id, $page = 0, $tax = 0) {

		$products = self::getDbProducts( $page );

		if ( $tax > 0 ) {
			foreach( $products as $key => $product ) {
				if( $product['price'] ){
					$products[$key]['price'] = (String) $product['price'] * ( (float) ($tax / 100) + 1);
				}

				if( $product['sale_price']) {
					$product[$key]['sale_price'] = (String) $product['sale_price'] * ( (float) ($tax / 100) + 1);
				}
			}
		}

		delete_option( 'egoi_import_bypass' );
		return $this->api->importProducts(
			'POST',
			array(
				'mode'     => 'update',
				'products' => $products,
			),
			$catalog_id
		);
	}

	public function importProductsCatalogNoVariations( $catalog_id, $page = 0, $tax = 0 ) {
		
		$products = self::getDbProductsNoVariations( $page );

		if ( $tax > 0 ) {
			foreach( $products as $key => $product ) {
				if( $product['price'] ){
					$products[$key]['price'] = (String) $product['price'] * ( (float) ($tax / 100) + 1);
				}

				if( $product['sale_price']) {
					$product[$key]['sale_price'] = (String) $product['sale_price'] * ( (float) ($tax / 100) + 1);
				}
			}
		}

		delete_option( 'egoi_import_bypass' );
		return $this->api->importProducts(
			'POST',
			array(
				'mode'     => 'update',
				'products' => $products,
			),
			$catalog_id
		);
	}

	/**
	 * Delete product in all catalogs synchronized
	 *
	 * @param int $post_id
	 * @return bool
	 */
	public function deleteProduct( $post_id ) {

		$catalogs = self::getCatalogsToSync();// apply rules if needed here (which products to delete)

		foreach ( $catalogs as $catalog ) {
			$this->api->deleteProduct( $catalog, $post_id );
		}

		return true;
	}


	/**
	 * Save a product object to a E-goi catalog
	 *
	 * @param WC_Product $product
	 * @return bool
	 */
	public function syncProduct( $product ) {
		$breadCumbs = self::getBreadcrumb();
		$catalogs   = self::getCatalogsToSync();// apply rules if needed here (which products to sync)
		$products   = self::preTransformArrayAbstractProductToApiBoth(
			array(
				array( 'ID' => $product->get_id() ),
			),
			$breadCumbs
		);

		foreach ( $catalogs as $catalog ) {
			$option = self::getCatalogOptions( $catalog );
			if ( $option['variations'] == 0 ) {
				$products = $products['no_variations'];
			} else {
				$products = $products['variations'];
			}
			foreach ( $products as $single ) {
                $single['name'] =  !empty($single['name']) ? $single['name'] : '';
                $single['description'] = !empty($single['description']) ? $single['description'] : '';

				$tax = !empty($option['tax']) ? (float) $option['tax'] : 0;
                if ($tax > 0) {
                    if (!empty($single['price'])) {
                        $single['price'] = $single['price'] * ((float)($tax / 100) + 1);
                    }

                    if (!empty($single['sale_price'])) {
                        $single['sale_price'] = $single['sale_price'] * ((float)($tax / 100) + 1);
                    }
                }

                if (!$this->api->createProduct($single, $catalog)) {
                    $this->api->patchProduct($single, $catalog, $single['product_identifier']);
                }
			}
		}

		return true;
}

public static function getWordpressCatalogs() {
	 $data = get_option( 'egoi_store_catalogs' );
	 $data = json_decode( $data, true );
	if ( empty( $data ) ) {
		$data = array();
	}

	 return $data;
}

public static function setWordpressCatalog( $catalog_id ) {
	$data = get_option( 'egoi_store_catalogs' );
	$data = json_decode( $data, true );
	if ( empty( $data ) || ! is_array( $data ) ) {
		$data = array();
	}

	$data[] = $catalog_id;
	update_option( 'egoi_store_catalogs', wp_json_encode( $data ) );
	return true;
}

public function setCatalogOptions( $catalog_id, $options ) {
	$data = get_option( 'egoi_catalogs_options' );
	$data = json_decode( $data, true );
	if ( empty( $data ) || ! is_array( $data ) ) {
		$data = array();
	}
	$data[ $catalog_id ] = $options;
	update_option( 'egoi_catalogs_options', wp_json_encode( $data ) );
}

public static function getCatalogOptions( $catalog_id = false ) {
	$data = get_option( 'egoi_catalogs_options' );
	$data = json_decode( $data, true );

	if ( empty( $catalog_id ) ) {
		return $data;
	}

	if ( empty( $data[ $catalog_id ] ) ) {
		return self::DEFAULT_CATALOG_OPTIONS;
	}

	return $data[ $catalog_id ];
}

	/**
	 * Get catalogs to sync product
	 *
	 * @return array|mixed
	 */
public static function getCatalogsToSync() {
	$result = get_option( 'egoi_catalog_sync' );
	if ( $result == false ) {
		return array();
	}
	$result = json_decode( $result, true );
	if ( json_last_error() != JSON_ERROR_NONE ) {
		return array();
	}
	return $result;
}

	/**
	 * @param $catalog
	 * @return string
	 */
public static function genTableCatalog( $catalog ) {
	$arrCatalog       = self::getCatalogsToSync();
	$bypass           = self::getProductsToBypass();
	$checked          = in_array( $catalog['catalog_id'], $arrCatalog ) ? 'checked' : '';
	$checkedVariation = ! empty( $catalog['options']['variations'] ) ? 'checked' : '';
	$blinck           = ! empty( $bypass ) && ! empty( $checked ) ? 'egoi-pulsating' : '';

	$switch = "<div class='switch-yes-no'>
                 <label class=\"form-switch\">
                <input $checked type=\"checkbox\" idgoi='{$catalog['catalog_id']}' class='sync_catalog'>
                <i class=\"form-icon\"></i>
                </label>
                </div>";

	$switchVariations = "<div class='switch-yes-no'>
                 <label class=\"form-switch\">
                <input $checkedVariation type=\"checkbox\" idgoi='{$catalog['catalog_id']}' class='variations_catalog'>
                <i class=\"form-icon\"></i>
                </label>
                </div>";

	return "<tr>
                <td>{$catalog['catalog_id']}</td>
                <td>{$catalog['title']}</td>
                <td>{$catalog['language']}</td>
                <td>{$catalog['currency']}</td>
                <td>{$switch}</td>
                <td>{$switchVariations}</td>
                <td class='flex-centered sun-margin'>
                    <div class=\"smsnf-btn " . $blinck . " force_catalog\" idgoi=\"{$catalog['catalog_id']}\">" . __( 'Import', 'egoi-for-wp' ) . "</div>
                    <div class=\"smsnf-btn delete-adv-form remove_catalog\" idgoi=\"{$catalog['catalog_id']}\">" . __( 'Delete', 'egoi-for-wp' ) . '</div>
                </td>
                <td>' . ucfirst( $catalog['origin'] ) . '</td>
                </tr>';
}

	/**
	 * Counts products possible to sync
	 *
	 * @return int
	 */
public static function countDbProducts() {
	global $wpdb;
	$table = $wpdb->prefix . 'posts';
	$sql   = "SELECT count(ID) as total FROM $table where (post_type = 'product' or post_type = 'product_variation') and post_status = 'publish'";
	$rows  = $wpdb->get_results( $sql, ARRAY_A );
	if ( ! empty( $rows[0]['total'] ) ) {
		return $rows[0]['total'];
	}
	return 0;
}

public static function countDbProductsNoVariations() {
	global $wpdb;
	$table = $wpdb->prefix . 'posts';
	$sql   = "SELECT count(ID) as total FROM $table where (post_type = 'product') and post_status = 'publish'";
	$rows  = $wpdb->get_results( $sql, ARRAY_A );
	if ( ! empty( $rows[0]['total'] ) ) {
		return $rows[0]['total'];
	}
	return 0;
}

	/**
	 * @return array
	 */
public static function getProductsToBypass() {
	$bypass = get_option( 'egoi_import_bypass' );

	if ( empty( $bypass ) ) {
		$bypass = array();
	} else {
		$bypass = json_decode( $bypass, true );
	}

	return $bypass;
}

	/**
	 * Returns
	 *
	 * @param int $page
	 * @param int $limit
	 * @return array
	 */
private static function getDbProducts( $page = 0, $limit = 100 ) {

	global $wpdb;
	$page  = $page * $limit;
	$table = $wpdb->prefix . 'posts';
	$sql   = "SELECT ID FROM $table where (post_type = 'product' or post_type = 'product_variation') and post_status = 'publish' order by id ASC LIMIT $page,$limit ";
	$rows  = $wpdb->get_results( $sql, ARRAY_A );

	$breadCrumbs = self::getBreadcrumb();
	return self::transformArrayAbstractProductToApi( $rows, $breadCrumbs );
}

private static function getDbProductsNoVariations( $page = 0, $limit = 100 ) {
	global $wpdb;
	$page  = $page * $limit;
	$table = $wpdb->prefix . 'posts';
	$sql   = "SELECT ID FROM $table where (post_type = 'product') and post_status = 'publish' order by id ASC LIMIT $page,$limit ";
	$rows  = $wpdb->get_results( $sql, ARRAY_A );

	$breadCrumbs = self::getBreadcrumb();
	return self::transformArrayAbstractProductToApi( $rows, $breadCrumbs, false );
}

	/**
	 * @param $arr
	 * @param $breadCrumbs
	 * @param bool        $variations
	 * @return array
	 */
public static function transformArrayAbstractProductToApi( $arr, &$breadCrumbs, $variations = true ) {
	$mappedProducts = array();
	foreach ( $arr as $product ) {
		$prod = wc_get_product( $product['ID'] );
		if ( $prod instanceof WC_Product_Variable ) {
			if ( $variations ) {
				$data = self::transformProductVariableObjectToApi( $prod, $breadCrumbs );
				foreach ( $data as $productVariation ) {
					$mappedProducts[] = self::transformToCleanArray( $productVariation );
				}
			} else {
				$mappedProducts[] = self::transformProductVariableObjectToApiNoVariations( $prod, $breadCrumbs );
			}
		} elseif ( $prod instanceof WC_Product && ! $prod instanceof WC_Product_Variation ) {
			$p = self::transformProductObjectToApi( $prod, $breadCrumbs );
			if ( ! empty( $p ) ) {
				$mappedProducts[] = self::transformToCleanArray( $p );
			}
		}
	}
	return $mappedProducts;
}

public static function preTransformArrayAbstractProductToApiBoth( $arr, &$breadCrumbs ) {
	return array(
		'no_variations' => self::transformArrayAbstractProductToApi( $arr, $breadCrumbs, false ),
		'variations'    => self::transformArrayAbstractProductToApi( $arr, $breadCrumbs ),
	);
}

	/**
	 * Generates array with id => breadcrumb of categories
	 *
	 * @param string $delimiter
	 * @return array
	 */
private static function getBreadcrumb( $delimiter = '>' ) {
	global $wpdb;
	$table  = $wpdb->prefix . 'term_taxonomy';
	$table2 = $wpdb->prefix . 'terms';

	$sql  = "SELECT $table.term_id,parent,slug FROM $table inner join $table2 on $table.term_id = $table2.term_id WHERE taxonomy = 'product_cat'";
	$rows = $wpdb->get_results( $sql, ARRAY_A );
	$rows = self::transformIDtoArrayIndex( $rows, 'term_id' );

	$breadCrumb = array();
	foreach ( $rows as $term_id => $infos ) {
		$cat = array( $infos['slug'] );
		while ( $infos['parent'] != 0 ) {
			$infos = $rows[ $infos['parent'] ];
			$cat[] = $infos['slug'];
		}
		$cat                    = array_reverse( $cat );
		$breadCrumb[ $term_id ] = implode( '>', $cat );
	}
	return $breadCrumb;
}

	// ----------------
	// TRANSFORMERS
	// ----------------

	/**
	 * From WC_Product to product payload APIV3
	 *
	 * @param $product
	 * @param $breadCrumbs
	 * @param bool        $bypass
	 * @return array|null
	 */
private static function transformProductObjectToApi( $product, &$breadCrumbs, $bypass = false ) {

	if ( ! $bypass ) {
		if ( ! $product instanceof WC_Product || empty( $product->get_regular_price() ) ) {
			return null;
		}
	}
	$description      = $product->get_description();
	$shot_description = $product->get_short_description();

	
	return array(
		'product_identifier' => "{$product->get_id()}",
		'name'               => $product->get_name(),
		'description'        => ! empty( $description ) ? $description : $shot_description,
		'sku'                => $product->get_sku(),
		// 'upc'                   => NULL,
		// 'ean'                   => NULL,
		'gtin'               => $product->get_meta( '_egoi_gtin' ),
		// 'mpn'                   => NULL,
		'link'               => get_permalink( $product->get_id() ),
		'image_link'         => wp_get_attachment_image_url( $product->get_image_id(), 'full' ),
		'price'              => $product->get_regular_price(),
		'sale_price'         => $product->get_sale_price(),
		'brand'              => $product->get_meta( '_egoi_brand' ),
		'categories'         => self::transformArrayIdsToArrayBreadCrumbs( $product->get_category_ids(), $breadCrumbs ),
		'related_products'   => array_unique( array_merge( $product->get_upsell_ids(), $product->get_cross_sell_ids() ) ),
	);

}

	/**
	 * From WC_Product_Variable to product payload APIV3
	 *
	 * @param $product
	 * @param $breadCrumbs
	 * @return array
	 */
private static function transformProductVariableObjectToApi( $product, &$breadCrumbs ) {
	if ( ! $product instanceof WC_Product_Variable ) {
		return array();
	}

	$base = self::transformProductObjectToApi( $product, $breadCrumbs, true );

	$variations = $product->get_available_variations();

	if ( empty( $variations ) || ! is_array( $variations ) ) {
		return array();
	}

	$output = array();
	foreach ( $variations as $variation ) {
		if ( $variation['variation_is_active'] === false || $variation['variation_is_visible'] === false ) {
			continue;
		}
		$prod        = wc_get_product( $variation['variation_id'] );
		$prod_mapped = self::transformProductObjectToApi( $prod, $breadCrumbs, true );

		if ( empty( $prod_mapped ) ) {
			continue;
		}

		$prod_mapped['categories']  = $base['categories'];
		$prod_mapped['image_link']  = ! empty( $prod_mapped['image_link'] ) ? $prod_mapped['image_link'] : $base['image_link'];
		$prod_mapped['description'] = ! empty( $prod_mapped['description'] ) ? $prod_mapped['description'] : $base['description'];
		if ( empty( $prod_mapped['description'] ) ) {
			$prod_mapped['description'] = ! empty( $prod->get_short_description() ) ? $prod->get_short_description() : $product->get_short_description();
		}

		$output[] = $prod_mapped;
	}
	return $output;
}

private static function transformProductVariableObjectToApiNoVariations( $product, &$breadCrumbs ) {
	if ( ! $product instanceof WC_Product_Variable ) {
		return array();
	}

	$base          = self::transformProductObjectToApi( $product, $breadCrumbs, true );
	$base['price'] = $product->get_variation_price();
	if ( $base['price'] != $product->get_variation_sale_price() ) {
		$base['sale_price'] = $product->get_variation_sale_price();
	}
	if ( empty( $base['description'] ) ) {
		$base['description'] = $product->get_short_description();
	}
	return $base;
}

private static function transformToCleanArray( $array ) {
	return array_filter(
		$array,
		function( $value ) {
			return ! empty( $value );
		}
	);
}

	/**
	 * from category_id array to breadcrumb array
	 *
	 * @param $ids
	 * @param $breadCrumbs
	 * @return array
	 */
private static function transformArrayIdsToArrayBreadCrumbs( $ids, &$breadCrumbs ) {
	if ( ! is_array( $ids ) ) {
		return array();
	}

	$output = array();
	foreach ( $ids as $id ) {
		$output[] = $breadCrumbs[ $id ];
	}
	return $output;
}

	/**
	 * transform row taxonomy to category_id => slug
	 *
	 * @param $data
	 * @param string $id
	 * @return array
	 */
private static function transformIDtoArrayIndex( &$data, $id = 'id' ) {
	if ( ! is_array( $data ) ) {
		return array();
	}
	$output = array();
	foreach ( $data as $item ) {
		$output[ $item[ $id ] ] = $item;
	}
	return $output;
}

public static function getNotification( $onQueue ) {
	return '
        <div class="smsnf-notification">
            <div class="egoi-close-pop close-btn">&#10005;</div>
            <h2><img style="margin-right: 5px;" src="' . plugin_dir_url( __FILE__ ) . '../admin/img/logo_small.png' . '">' . __( 'Attention!', 'egoi-for-wp' ) . '</h2>
            <p>' . sprintf( __( 'You have %d products behind in synchronization!', 'egoi-for-wp' ), $onQueue ) . '</p>
        </div>
	';
}


	/**
	 * @return string|null
	 */
public static function getApikey() {
	$apikey = get_option( 'egoi_api_key' );
	if ( isset( $apikey['api_key'] ) && ( $apikey['api_key'] ) ) {
		return $apikey['api_key'];
	}
	return null;
}
}
