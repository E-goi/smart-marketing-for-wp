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
        $catalogs =  $this->api->getCatalogs('GET',['page'=>$page,'limit'=>$limit]);

        if(!is_array($catalogs))
            return [];

        $store_catalogs_id = self::getWordpressCatalogs();
        foreach ($catalogs as $key => $catalog){
            if(in_array($catalog['catalog_id'],$store_catalogs_id))
                $catalogs[$key]['origin'] = 'wordpress';
            else
                $catalogs[$key]['origin'] = 'e-goi';
        }

        return $catalogs;
    }

    /*
     * Creates a catalog
     * */
    public function createCatalog($title, $language, $currency){
        $response =  $this->api->createCatalog(
            'POST',
            [
                'title'     => 'Wordpress '.$title,
                'language'  => $language,
                'currency'  => $currency
            ]
        );
        if(!is_numeric($response))
            return false;

        self::setWordpressCatalog($response);
        return true;
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
        delete_option('egoi_import_bypass');
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
        $products = self::transformArrayAbstractProductToApi([
            ['ID' => $product->get_id()]
        ],$breadCumbs);

        foreach ($catalogs as $catalog){
            foreach ($products as $single){
                if(!$this->api->createProduct($single, $catalog))
                    $this->api->patchProduct($single, $catalog, $single['product_identifier']);
            }
        }

        return true;
    }

    public static function getWordpressCatalogs(){
         $data = get_option('egoi_store_catalogs');
         $data = json_decode($data, true);
         if(empty($data))
             $data = [];

         return $data;
    }

    public static function setWordpressCatalog($catalog_id){
        $data = get_option('egoi_store_catalogs');
        $data = json_decode($data, true);
        if(empty($data) || !is_array($data))
            $data = [];

        $data[] = $catalog_id;
        update_option('egoi_store_catalogs', json_encode($data));
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
        $bypass = self::getProductsToBypass();
        $checked = in_array($catalog['catalog_id'],$arrCatalog)?'checked':'';
        $blinck = !empty($bypass)&&!empty($checked)?'egoi-pulsating':'';

        $switch = "<div class='switch-yes-no'>
                 <label class=\"form-switch\">
                <input $checked type=\"checkbox\" idgoi='{$catalog['catalog_id']}' class='sync_catalog'>
                <i class=\"form-icon\"></i>
                </label>
                </div>";
        return "<tr>
                <td>{$catalog['catalog_id']}</td>
                <td>{$catalog['title']}</td>
                <td>{$catalog['language']}</td>
                <td>{$catalog['currency']}</td>
                <td>{$switch}</td>
                <td class='flex-centered sun-margin'>
                    <div class=\"smsnf-btn ".$blinck." force_catalog\" idgoi=\"{$catalog['catalog_id']}\">".__('Import', 'egoi-for-wp')."</div>
                    <div class=\"smsnf-btn delete-adv-form remove_catalog\" idgoi=\"{$catalog['catalog_id']}\">".__('Delete', 'egoi-for-wp')."</div>
                </td>
                <td>".ucfirst($catalog['origin'])."</td>
                </tr>";
    }

    /**
     * Counts products possible to sync
     * @return int
     */
    public static function countDbProducts(){
        global $wpdb;
        $table = $wpdb->prefix.'posts';
        $sql="SELECT count(ID) as total FROM $table where (post_type = 'product' or post_type = 'product_variation') and post_status = 'publish'";
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
        $sql="SELECT ID FROM $table where (post_type = 'product' or post_type = 'product_variation') and post_status = 'publish' order by id ASC LIMIT $page,$limit ";
        $rows = $wpdb->get_results($sql,ARRAY_A);

        $breadCrumbs = self::getBreadcrumb();
        return self::transformArrayAbstractProductToApi($rows, $breadCrumbs);
    }

    /**
     * @param $arr
     * @param $breadCrumbs
     * @return array
     */
    public static function transformArrayAbstractProductToApi($arr, &$breadCrumbs){
        $mappedProducts = [];
        foreach ($arr as $product){
            $prod = wc_get_product($product['ID']);
            if($prod INSTANCEOF WC_Product_Variable ){
                $data = self::transformProductVariableObjectToApi($prod,$breadCrumbs);
                foreach ($data as $productVariation){
                    $mappedProducts[] = self::transformToCleanArray($productVariation);
                }
            }else if($prod INSTANCEOF WC_Product && ! $prod INSTANCEOF WC_Product_Variation ){
                $p = self::transformProductObjectToApi($prod,$breadCrumbs);
                if(!empty($p))
                    $mappedProducts[] = self::transformToCleanArray($p);
            }
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
     * @param bool $bypass
     * @return array|null
     */
    private static function transformProductObjectToApi($product, &$breadCrumbs, $bypass = false){

        if(!$bypass){
            if(! $product INSTANCEOF WC_Product || empty($product->get_regular_price()))
                return NULL;
        }

        return [
            'product_identifier'    => "{$product->get_id()}",
            'name'                  => $product->get_name(),
            'description'           => $product->get_description(),
            'sku'                   => $product->get_sku(),
            //'upc'                   => NULL,
            //'ean'                   => NULL,
            //'gtin'                  => NULL,
            //'mpn'                   => NULL,
            'link'                  => get_permalink($product->get_id()),
            'image_link'            => wp_get_attachment_image_url( $product->get_image_id(), 'full' ),
            'price'                 => $product->get_regular_price(),
            'sale_price'            => $product->get_sale_price(),
            //'brand'                 => NULL,
            'categories'            => self::transformArrayIdsToArrayBreadCrumbs($product->get_category_ids(),$breadCrumbs),
            'related_products'      => array_unique(array_merge($product->get_upsell_ids(), $product->get_cross_sell_ids()))
        ];

    }

    /**
     * From WC_Product_Variable to product payload APIV3
     * @param $product
     * @param $breadCrumbs
     * @return array
     */
    private static function transformProductVariableObjectToApi($product, &$breadCrumbs){
        if(! $product INSTANCEOF WC_Product_Variable )
            return [];

        $base = self::transformProductObjectToApi($product,$breadCrumbs, true);

        $variations = $product->get_available_variations();

        if(empty($variations) || !is_array($variations))
            return [];

        $output = [];
        foreach ($variations as $variation){
            if($variation['variation_is_active'] === false || $variation['variation_is_visible'] === false )
                continue;
            $prod = wc_get_product($variation['variation_id']);
            $prod_mapped = self::transformProductObjectToApi($prod,$breadCrumbs, true);

            if(empty($prod_mapped))
                continue;

            $prod_mapped['categories']  = $base['categories'];
            $prod_mapped['image_link']  = !empty($base['image_link'])?$base['image_link']:$prod_mapped['image_link'];
            $prod_mapped['description'] = !empty($base['description'])?$base['description']:$prod_mapped['description'];

            $output[] = $prod_mapped;
        }
        return $output;
    }

    private static function transformToCleanArray($array){
        return array_filter($array, function($value) { return !empty($value); });
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

    public static function getNotification($onQueue){
        return  '
        <div class="smsnf-notification">
            <div class="egoi-close-pop close-btn">&#10005;</div>
            <h2><img style="margin-right: 5px;" src="'.plugin_dir_url( __FILE__ ).'../admin/img/logo_small.png'.'">' . __('Attention!','egoi-for-wp') . '</h2>
            <p>' . sprintf(__('You have %d products behind in synchronization!','egoi-for-wp'), $onQueue) . '</p>
        </div>
	';
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