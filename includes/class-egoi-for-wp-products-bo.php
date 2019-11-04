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
require_once(plugin_dir_path( __FILE__ ) . 'class-egoi-for-wp-apiv3.php');

class EgoiProductsBo
{
    protected $api;
    public function __construct()
    {
        $this->api =  new EgoiApiV3(self::getApikey());
    }

    /**
     * From api response to Ajax response (Countries and Languages)
     * @return array|bool|mixed
     */
    public function getCountriesCurrencies(){
        $data = $this->api->getCountriesCurrencies();
        if(!empty($data['items'])){
            $return = ['countries' => [],'currencies' => []];
            foreach ($data['items'] as $item){
                $return['countries'][] = [
                    'value' => $item['iso_code'],
                    'name'  => $item['name']
                ];
                $return['currencies'][] = $item['currency'];
            }
            $return['currencies'] = array_unique($return['currencies']);
            return $return;
        }
        return $data;
    }

    /*
     * Gets Catalogs by page
     * */
    public function getCatalogsTable($page=0,$limit=10){
        return $this->api->getCatalogs('GET',['page'=>$page,'limit'=>$limit]);
    }

    /*
     * Creates a catalog
     * */
    public function createCatalog($title, $language, $currency){
        return $this->api->createCatalog(
            'POST',
            [
                'title'     => $title,
                'language'  => $language,
                'currency'  => $currency
            ]
        );
    }

    public function deleteCatalog($id){
        return $this->api->deleteCatalog(
            'DELETE',
            $id
        );
    }

    /**
     * Imports 1000 products to a catalog
     * @param int $catalog_id
     * @param int $page
     * @return mixed
     */
    public function importProductsCatalog($catalog_id, $page=0){
        return $this->api->importProducts(
            'POST',
            ['products' => self::getDbProducts($page)],
            $catalog_id
        );
    }

    /**
     * Delete product in all catalogs synchronized
     * @param int $post_id
     * @return bool
     */
    public function deleteProduct($post_id){

        $catalogs = self::getCatalogsToSync();//apply rules if needed here (which products to delete)

        foreach ($catalogs as $catalog){
            $this->api->deleteProduct($catalog, $post_id);
        }

        return true;
    }


    /**
     * Save a product object to a E-goi catalog
     * @param WC_Product $product
     * @return bool
     */public function syncProduct($product){
        $breadCumbs = self::getBreadcrumb();

        $catalogs = self::getCatalogsToSync();//apply rules if needed here (which products to sync)

        foreach ($catalogs as $catalog){
            if(!$this->api->createProduct(self::transformProductObjectToApi($product,$breadCumbs), $catalog))
                $this->api->patchProduct(self::transformProductObjectToApi($product,$breadCumbs), $catalog, $product->get_id());
        }

        return true;
    }

    /**
     * Get catalogs to sync product
     * @return array|mixed
     */
    public static function getCatalogsToSync(){
        $result = get_option('egoi_catalog_sync');
        if($result == false)
            return [];
        $result = json_decode($result, true);
        if(json_last_error() != JSON_ERROR_NONE)
            return [];
        return $result;
    }

    /**
     * @param $catalog
     * @return string
     */
    public static function genTableCatalog($catalog){
        $arrCatalog = self::getCatalogsToSync();
        $checked = in_array($catalog['catalog_id'],$arrCatalog)?'checked':'';
        return "<tr>
                <td>{$catalog['catalog_id']}</td>
                <td>{$catalog['title']}</td>
                <td>{$catalog['language']}</td>
                <td>{$catalog['currency']}</td>
                <td><input class='sync_catalog' idgoi='{$catalog['catalog_id']}' type=\"checkbox\" ".$checked."></td>
                <td class='flex-centered sun-margin'>
                    <div class=\"button force_catalog\" idgoi=\"{$catalog['catalog_id']}\"><i class=\"fas fa-sync\"></i></div>
                    <div class=\"button remove_catalog egoi-remove-button\" idgoi=\"{$catalog['catalog_id']}\">x</div>
                </td>
                </tr>";
    }

    /**
     * Counts products possible to sync
     * @return int
     */
    public static function countDbProducts(){
        global $wpdb;
        $table = $wpdb->prefix.'posts';
        $sql="SELECT count(ID) as total FROM $table where post_type = 'product' and post_status = 'publish'";
        $rows = $wpdb->get_results($sql,ARRAY_A);
        if(!empty($rows[0]['total'])){
            return $rows[0]['total'];
        }
        return 0;
    }

    /**
     * @return array
     */
    public static function getProductsToBypass(){
        $bypass = get_option('egoi_import_bypass');

        if(empty($bypass))
            $bypass = [];
        else
            $bypass = json_decode($bypass,true);

        return $bypass;
    }

    /**
     * Returns
     * @param int $page
     * @param int $limit
     * @return array
     */
    private static function getDbProducts($page = 0, $limit = 1000){

        global $wpdb;
        $page = $page * $limit;
        $table = $wpdb->prefix.'posts';
        $sql="SELECT ID FROM $table where post_type = 'product' and post_status = 'publish' order by id ASC LIMIT $page,$limit ";
        $rows = $wpdb->get_results($sql,ARRAY_A);

        $breadCumbs = self::getBreadcrumb();
        $mappedProducts = [];
        foreach ($rows as $product){
            $mappedProducts[] = self::transformProductObjectToApi(wc_get_product($product['ID']),$breadCumbs);
        }
        return $mappedProducts;
    }

/*
    private static function getProductsCategoryNumbers($productNumbers=[]){
        $productNumbers = implode(',',$productNumbers);

        global $wpdb;
        $term_taxonomy      = $wpdb->prefix."term_taxonomy";
        $term_relationships = $wpdb->prefix."term_relationships";
        $posts              = $wpdb->prefix."posts";

        $sql = "SELECT ID,$term_taxonomy.term_id FROM $posts 
                inner join $term_relationships on $posts.ID = $term_relationships.object_id 
                inner join $term_taxonomy on $term_relationships.term_taxonomy_id = $term_taxonomy.term_id
                where $posts.post_type = 'product' AND $posts.post_status = 'publish' AND $term_taxonomy.taxonomy = 'product_cat' and ID in($productNumbers)
                order by ID ASC;";

        return $wpdb->get_results($sql,ARRAY_A);
    }
*/
    /**
     * Generates array with id => breadcrumb of categories
     * @param string $delimiter
     * @return array
     */
    private static function getBreadcrumb($delimiter = '>'){
        global $wpdb;
        $table = $wpdb->prefix.'term_taxonomy';
        $table2 = $wpdb->prefix.'terms';

        $sql="SELECT $table.term_id,parent,slug FROM $table inner join $table2 on $table.term_id = $table2.term_id WHERE taxonomy = 'product_cat'";
        $rows = $wpdb->get_results($sql,ARRAY_A);
        $rows = self::transformIDtoArrayIndex($rows, 'term_id');

        $breadCrumb = [];
        foreach ($rows as $term_id => $infos){
            $cat=[$infos['slug']];
            while($infos['parent'] != 0){
                $infos = $rows[$infos['parent']];
                $cat[] = $infos['slug'];
            }
            $cat = array_reverse($cat);
            $breadCrumb[$term_id] = implode('>', $cat);
        }
        return $breadCrumb;
    }

    # ----------------
    ##  TRANSFORMERS
    # ----------------

    /**
     * From WC_Product to product payload APIV3
     * @param $product
     * @param $breadCrumbs
     * @return array|null
     */
    private function transformProductObjectToApi($product, &$breadCrumbs){
        if(! $product INSTANCEOF WC_Product)
            return NULL;

        return [
            'product_identifier'    => "{$product->get_id()}",
            'name'                  => $product->get_name(),
            'description'           => $product->get_description(),
            'sku'                   => $product->get_sku(),
            //'upc'                   => NULL,
            //'ean'                   => NULL,
            //'gtin'                  => NULL,
            //'mpn'                   => NULL,
            'link'                  => $product->add_to_cart_url(),
            'image_link'            => get_the_post_thumbnail_url($product->get_id()),
            'price'                 => $product->get_regular_price(),
            'sale_price'            => $product->get_sale_price(),
            //'brand'                 => NULL,
            'categories'            => self::transformArrayIdsToArrayBreadCrumbs($product->get_category_ids(),$breadCrumbs),
            'related_products'      => array_unique(array_merge($product->get_upsell_ids(), $product->get_cross_sell_ids()))
        ];

    }

    /**
     * from category_id array to breadcrumb array
     * @param $ids
     * @param $breadCrumbs
     * @return array
     */
    private static function transformArrayIdsToArrayBreadCrumbs($ids, &$breadCrumbs){
        if(!is_array($ids))
            return [];

        $output = [];
        foreach ($ids as $id){
            $output[] = $breadCrumbs[$id];
        }
        return $output;
    }

    /**
     * transform row taxonomy to category_id => slug
     * @param $data
     * @param string $id
     * @return array
     */
    private static function transformIDtoArrayIndex(&$data, $id = 'id'){
        if(!is_array($data))
            return [];
        $output = [];
        foreach ($data as $item){
            $output[$item[$id]] = $item;
        }
        return $output;
    }


    /**
     * @return string|null
     */
    public static function getApikey(){
        $apikey = get_option('egoi_api_key');
        if(isset($apikey['api_key']) && ($apikey['api_key'])) {
            return $apikey['api_key'];
        }
        return null;
    }
}