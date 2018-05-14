<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, API and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @link       https://www.e-goi.com
 * @since      1.0.0
 * @package    Egoi_For_Wp
 * @subpackage Egoi_For_Wp/includes
 * @author     E-goi <admin@e-goi.com>
 */
class Egoi_For_Wp {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Egoi_For_Wp_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version = SELF_VERSION;
	
	/**
	 * @var string
	 */
	protected $host;

	/**
	 * The default options to be created.
	 * 
	 * @var array
	 */
	protected static $options = array(
		'Egoi4WpBuilderObject',
		'egoi_sync',
		'egoi_bar_sync',
		'egoi_api_key',
		'widget_egoi4widget',
		'egoi_widget',
		'egoi_form_sync_1',
		'egoi_form_sync_2',
		'egoi_form_sync_3',
		'egoi_form_sync_4',
		'egoi_form_sync_5',
		'egoi_int',
		'egoi_data',
		'egoi_mapping',
		'egoi_client',
        'egoi_has_list'
	);

	/**
	 * Soap Client
	 * 
	 * @var string
	 */
	protected $url = 'https://api.e-goi.com/v2/soap.php?wsdl';

	/**
	 * Rest Client
	 * 
	 * @var string
	 */
	protected $restUrl = 'http://api.e-goi.com/v2/rest.php?type=json&method=';

	/**
	 * Plugin Key
	 * 
	 * @var string
	 */
	protected $plugin = '908361f0368fd37ffa5cc7c483ffd941';

	/**
	 * Define the preview in specific area of the plugin.
	 *
	 * @since  1.0.0
	 */
	const PAGE_SLUG = 'egoi4wp-form-preview';

	/**
	 * Constructor
	 * 
	 * @since 1.0.0
	 */
	public function __construct($debug = false) {

		$this->plugin_name = 'egoi-for-wp';
		$this->debug = $debug;
		$this->host = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'];

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_listen_hooks();

		//Contact Form 7
		$this->getContactFormInfo();

		$this->getClientAPI();
		$this->syncronizeEgoi($_POST);

		$this->setClient();
	}

	/**
	 * Set E-goi Client ID
	 * 
	 * @since    1.1.2
	 */
	protected function setClient() {

		if(!is_admin()){
			if(!get_option('egoi_client')){
				add_option('egoi_client', $this->getClient());
			}
		}
	}

	/**
	 * Remove all data from this plugin
	 *
	 * @since    1.1.0
	 * @access   public
	 */
	public static function removeData($rmOnlyMappedFields = false, $returnContent = false) {

		try{
			global $wpdb;

            $egoi_options = self::$options;

            $all_options = wp_load_alloptions();

            //to get all options that are egoi simple forms
            foreach ($all_options as $key => $value) {
                if(strpos($key, 'egoi_simple_') !== false ){
                    $egoi_options[] = $key; //add to egoi options

                    //to delete simple form on posts table in BD
                    $post_id = str_replace('egoi_simple_form_', "", $key);
                    wp_delete_post($post_id);
                }
            }

            foreach ($egoi_options as $opt) {
                delete_option($opt);
            }
			
			$wpdb->hide_errors();
			$table = $wpdb->prefix."egoi_map_fields";

			if($rmOnlyMappedFields){
				$sql = "TRUNCATE TABLE $table";
				$wpdb->query($sql);
			}else{
				$sql = "DROP TABLE $table";
				$wpdb->query($sql);
			}
			
			if($returnContent){
				echo json_encode(array('result' => 'ok'));
				exit;
			}

			return true;

		}catch(Exception $e){
			return false;
		}
	}

	/**
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-egoi-for-wp-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-egoi-for-wp-i18n.php';

		/**
		 * Admin funcionality
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-egoi-for-wp-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-egoi-for-wp-listener.php';

		/**
		 * Public funcionality
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-egoi-for-wp-public.php';

		$this->loader = new Egoi_For_Wp_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Egoi_For_Wp_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Egoi_For_Wp_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Egoi_For_Wp_Admin($this->get_plugin_name(), $this->get_version(), $this->debug);

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		$this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_admin_menu');
		$this->define_apikey();

		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php');
		$this->loader->add_filter('plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links');
		$this->loader->add_filter('plugin_action_links_' . $plugin_basename, $plugin_admin, 'del_action_link');

	}

	/**
	 * Register API Key on runtime
	 * 
	 * @since 1.0.13
	 */
	public function define_apikey() {
		
		$apikey = get_option('egoi_api_key');
		if(isset($apikey['api_key']) && ($apikey['api_key'])) {
			$this->_valid['api_key'] = $apikey['api_key'];
		}

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Egoi_For_Wp_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

		//handle bar
		$bar_post = get_option(Egoi_For_Wp_Admin::BAR_OPTION_NAME);
		if(isset($bar_post['enabled']) && ($bar_post['enabled'])){
			if($bar_post['position']=='top'){
				$this->loader->add_filter('wp_head', $plugin_public, 'get_bar');
			}else{
				$this->loader->add_filter('wp_footer', $plugin_public, 'get_bar');
			}
			$this->loader->add_action('admin_post_bar_handler', $plugin_public, 'bar_handler');
		}

		//handle form
		if(isset($_GET['form_id'])){
			$this->loader->add_filter('the_content', $plugin_public, 'get_html');
		}

		add_shortcode('egoi_form_sync_1', array($plugin_public, 'shortcode_default'));
		add_shortcode('egoi_form_sync_2', array($plugin_public, 'shortcode_second'));
		add_shortcode('egoi_form_sync_3', array($plugin_public, 'shortcode_last1'));
		add_shortcode('egoi_form_sync_4', array($plugin_public, 'shortcode_last2'));
		add_shortcode('egoi_form_sync_5', array($plugin_public, 'shortcode_last3'));

		$this->loader->add_action('admin_post_form_handler', $plugin_public, 'form_handler');
		
	}

	/**
	 * Register Profile Hooks
	 * 
	 * @since   1.0.5
	 */
	public function define_listen_hooks() {

		$this->loader->add_action('user_register', $this, 'get_listener', 999);
		$this->loader->add_action('profile_update', $this, 'get_user_update', 999);
		
		$this->loader->add_action('delete_user', $this, 'get_user_del');

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Egoi_For_Wp_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Initialize E-goi form.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	private function init_form(){

		$form_id = (int)$_GET['form_id'];
		$is_preview = isset($_GET['preview']);

		$instance = new self($form_id, $is_preview);
	}

	public function getClient($apikey = false) {
		
		$url = $this->restUrl.'getClientData&'.http_build_query(array(
				'functionOptions' => array(
					'apikey' => $apikey ?: $this->_valid['api_key'],
					'plugin_key' => $this->plugin
					)
				),'','&');
       
       	$result_client = json_decode($this->_getContent($url));
        if($result_client->Egoi_Api->getClientData->status=='success'){
        	return $result_client->Egoi_Api->getClientData;
        }
	}

    public function getLists($start = false, $limit = false) {

		$url = $this->restUrl.'getLists&'.http_build_query(array(
				'functionOptions' => array(
					'apikey' => $this->_valid['api_key'],
					'plugin_key' => $this->plugin
					)
				),'','&');
		
		$result_client = json_decode($this->_getContent($url));
        return $result_client->Egoi_Api->getLists;
	}
	
	public function createList($name, $lang) {

		$url = $this->restUrl.'createList&'.http_build_query(array(
				'functionOptions' => array(
					'apikey' => $this->_valid['api_key'],
					'plugin_key' => $this->plugin,
					'nome' => $name,
					'idioma_lista' => $lang
					)
				),'','&');

		$result_client = json_decode($this->_getContent($url));
		$created_list = $result_client->Egoi_Api->createList;

        if($created_list->ERROR){
        	return $created_list->ERROR;
        }
        
        return $created_list;
	}

	public function addSubscriberForms($listID, $name, $email, $formID = false) {

		$full_name = explode(' ', $name);
		$fname = $full_name[0];
		$lname = $full_name[1];

		$url = $this->restUrl.'addSubscriber&'.http_build_query(array(
				'functionOptions' => array(
					'apikey' => $this->_valid['api_key'],
					'plugin_key' => $this->plugin, 
					'listID' => $listID, 
					'first_name' => $fname, 
					'last_name' => $lname,
					'email' => $email,
					'formID' => $formID, 
					'status' => $formID ? 0 : 1
					)
				),'','&');

       	$result_client = json_decode($this->_getContent($url));
        return $result_client->Egoi_Api->addSubscriber;
	}

	public function addSubscriber($listID, $name = '', $email, $lang = '', $status = false, $mobile = '', $tag = false) {

		$full_name = explode(' ', $name);
		$fname = $full_name[0];
		$lname = $full_name[1];

		if ($status === false) {
		    $status = 1;
        }
		if($tag){
			$url = $this->restUrl.'addSubscriber&'.http_build_query(array(
					'functionOptions' => array(
						'apikey' => $this->_valid['api_key'], 
						'plugin_key' => $this->plugin, 
						'listID' => $listID, 
						'lang' => $lang,
						'first_name' => $fname, 
						'last_name' => $lname,
						'email' => $email, 
						'status' => $status,
						'tags' => array($tag)
						)
					),'','&');
		}else{
			$url = $this->restUrl.'addSubscriber&'.http_build_query(array(
					'functionOptions' => array(
						'apikey' => $this->_valid['api_key'], 
						'plugin_key' => $this->plugin, 
						'listID' => $listID,
						'lang' => $lang,
						'first_name' => $fname, 
						'last_name' => $lname,
						'email' => $email, 
						'status' => $status
						)
					),'','&');
		}

       	$result_client = json_decode($this->_getContent($url));
       	if($result_client->Egoi_Api->addSubscriber->status=='success'){
        	return $result_client->Egoi_Api->addSubscriber;
        }
	}

	public function addSubscriberBulk($listID, $tag, $subscribers = array()) {

		$api = new SoapClient($this->url);
		$params = array(
			'apikey' => $this->_valid['api_key'], 
			'plugin_key' => $this->plugin, 
			'listID' => $listID, 
			'subscribers' => $subscribers,
			'compareField' => 'email',
			'operation' => '2',
			'tags' => array($tag)
		);
		$result = $api->addSubscriberBulk($params);
       	return $result;
	}

	public function addSubscriberTags($listID, $email, $tags = array(), $name = '', $lname = '', $role = 0, $extra_fields = array(), $option = 0, $ref_fields = array(), $status = 1
	) {

		$full_name = explode(' ', $name);
		$fname = $full_name[0];
		if(!$lname){
			$lname = $full_name[1];
		}

		$tel = $ref_fields['tel'];
		$cell = $ref_fields['cell'];
		$bd = $ref_fields['bd'];
		$fax = $ref_fields['fax'];
		$lang = $ref_fields['lang'];

		$params = array(
			'apikey' => $this->_valid['api_key'],
			'plugin_key' => $this->plugin,
		    'listID' => $listID,
		    'email' => $email,
		    'first_name' => $fname,
		    'last_name' => $lname,
		    'tags' => $tags,
		    'lang' => $lang ?: null,
		    'status' => $status
		);

		// telephone
		if($tel){
			$params['telephone'] = $tel;
		}
		// cellphone
		if($cell){
			$params['cellphone'] = $cell;
		}
		// birthdate
		if($bd){
			$params['birth_date'] = $bd;
		}
		// fax
		if($fax){
			$params['fax'] = $fax;
		}

		if($option){
			$all_extra_fields = get_object_vars($this->getExtraFields($listID));
			if($all_extra_fields){

				foreach ($extra_fields as $key => $value) {
					$filtered_key = str_replace(array('key_', 'extra_'), '', $key);
					if(array_key_exists('key_'.$filtered_key, $all_extra_fields)){
						$params['extra_'.$filtered_key] = $value;
					}
				}
			}
		}

		$url = $this->restUrl.'addSubscriber&'.http_build_query(array('functionOptions' => $params),'','&');
		$result_client = json_decode($this->_getContent($url));
		
		return $result_client->Egoi_Api->addSubscriber;
	}

	public function editSubscriber($listID, $subscriber, $role = 0, $fname = '', $lname = '', $fields = array(), $option = 0, $ref_fields = array()) {
		
		$apikey = $this->_valid['api_key'];
		$plugin_key = $this->plugin;

		$telephone = $ref_fields['tel'];
		$cellphone = $ref_fields['cell'];
		$birth_date = $ref_fields['bd'];
		$fax = $ref_fields['fax'];
		$lang = $ref_fields['lang'];
		
		$params = compact(
			'apikey',
			'plugin_key',
			'listID',
			'subscriber',
			'telephone', 
			'cellphone', 
			'birth_date', 
			'fax', 
			'lang', 
			$params
		);

		// role
		if($role){
			$params['tags'] = $role;
		}
		// first name
		if($fname){
			$params['first_name'] = $fname;
		}
		// last name
		if($lname){
			$params['last_name'] = $lname;
		}

		if($option){
			foreach ($fields as $key => $value) {
				$params[str_replace('key_', 'extra_', $key)] = $value;	
			}
		}

		$url = $this->restUrl.'editSubscriber&'.http_build_query(array('functionOptions' => $params),'','&');
		$result_client = json_decode($this->_getContent($url));

		return $result_client->Egoi_Api->editSubscriber;	
	}

	public function delSubscriber($listID,$email) {

		$url = $this->restUrl.'removeSubscriber&'.http_build_query(array(
				'functionOptions' => array(
					'apikey' => $this->_valid['api_key'],
					'plugin_key' => $this->plugin,
					'listID' => $listID,
					'subscriber' => $email, 
					'removeMethod' => 'API'
					)
				),'','&');

       	$result_client = json_decode($this->_getContent($url));
        return $result_client->Egoi_Api->removeSubscriber;
	}

	public function getAllSubscribers($listID, $start = 0) {

		$url = $this->restUrl.'subscriberData&'.http_build_query(array(
				'functionOptions' => array(
					'apikey' => $this->_valid['api_key'],
					'plugin_key' => $this->plugin,
					'listID' => $listID,
					'subscriber' => 'all_subscribers',
					'limit' => 1000,
					'start' => $start
					)
				),'','&');

       	$result_client = json_decode($this->_getContent($url));
       	if($result_client->Egoi_Api->subscriberData->status=='success'){
        	return $result_client->Egoi_Api->subscriberData;
        }
	}

	public function getSubscriber($listID, $email) {

		$url = $this->restUrl.'subscriberData&'.http_build_query(array(
				'functionOptions' => array(
					'apikey' => $this->_valid['api_key'],
					'plugin_key' => $this->plugin,
					'listID' => $listID,
					'subscriber' => $email
					)
				),'','&');

       	$result_client = json_decode($this->_getContent($url));
       	if($result_client->Egoi_Api->subscriberData->status=='success'){
        	return $result_client->Egoi_Api->subscriberData;
        }
	}

	public function getForms($listID = false, $option = false) {

		$url = $this->restUrl.'getForms&'.http_build_query(array(
				'functionOptions' => array(
					'apikey' => $this->_valid['api_key'],
					'plugin_key' => $this->plugin,
					'listID' => $listID
					)
				),'','&');
       
       	$result_client = json_decode($this->_getContent($url));
    	$forms = $result_client->Egoi_Api->getForms;
		return $forms;
	}

	public function getExtraFields($listID) {

		$url = $this->restUrl.'getExtraFields&'.http_build_query(array(
				'functionOptions' => array(
					'apikey' => $this->_valid['api_key'],
					'plugin_key' => $this->plugin,
					'listID' => $listID
					)
				),'','&');
       
       	$result_client = json_decode($this->_getContent($url));
    	$extra_fields = $result_client->Egoi_Api->getExtraFields->extra_fields;
    	if($extra_fields == '0'){
    		return '';
    	}
		return $extra_fields;
	}

	public function getFieldMap($name = false, $field = false){
		
		global $wpdb;

		$table = $wpdb->prefix."egoi_map_fields";
		if($field){
			$sql="SELECT * FROM $table WHERE wp='$field'";
		}else{
			$sql="SELECT * FROM $table WHERE egoi='$name'";
		}
        $rows = $wpdb->get_results($sql);

        return $rows[0]->egoi;
	}

	public function getMappedFields() {

		global $wpdb;

		$table = $wpdb->prefix."egoi_map_fields";
		$sql="SELECT * FROM $table order by id DESC";
        $rows = $wpdb->get_results($sql);

        return $rows;
	}

	private function getEgoiSubscribers($listID){

		$count = 0;
		$result = $this->getLists();
		foreach ($result as $key => $value) {
			if($value->listnum == $listID){
				$count = $value->subs_activos;
			}
		}

	    return $count;
	}

	public function syncronizeEgoi($post = array()){

		if(!empty($post)){
			if(isset($post['action']) && ($post['action'] == 'synchronize')){
				$all_subscribers = $this->getEgoiSubscribers($post['list']);
				
				$users = count_users();
				$total_users = '';
				foreach($users['avail_roles'] as $role => $count){
					if($role == $post['role']){
						$total_users .= $count;
					}
				}

				if($post['role'] == ''){
					global $wpdb;
					$table = $wpdb->prefix.'users';
					$sql = "SELECT COUNT(*) AS COUNT FROM $table";
					$row = $wpdb->get_row($sql);
					$total_users = $row->COUNT;
				}

				$total_users = ($total_users-1);
				$total[] = $all_subscribers;
				$total[] = $total_users;

				echo json_encode($total);
				exit;
			}
		}
	}

	public function addTag($name) {

		$url = $this->restUrl.'addTag&'.http_build_query(array(
				'functionOptions' => array(
					'apikey' => $this->_valid['api_key'],
					'plugin_key' => $this->plugin,
					'name' => $name
					)
				),'','&');

       	$result_client = json_decode($this->_getContent($url));

       	return $result_client->Egoi_Api->addTag;
	}

	public function getTag($name) {

		$url = $this->restUrl.'getTags&'.http_build_query(array(
				'functionOptions' => array(
					'apikey' => $this->_valid['api_key'],
					'plugin_key' => $this->plugin
					)
				),'','&');

       	$result_client = json_decode($this->_getContent($url));
       	$result = $result_client->Egoi_Api->getTags->TAG_LIST;
		
		foreach ($result as $key => $value) {
			if($value->NAME == $name){
				$data['NAME'] = $value->NAME;
				$data['ID'] = $value->ID;
			}
		}

		if(empty($data)){
			$rest = $this->addTag($name);
			$data['NEW_ID'] = $rest->ID;
			$data['NEW_NAME'] = $rest->NAME;
		}

       	return $data;
	}

	public function getTags() {

		$url = $this->restUrl.'getTags&'.http_build_query(array(
				'functionOptions' => array(
					'apikey' => $this->_valid['api_key'],
					'plugin_key' => $this->plugin
					)
				),'','&');

		$result_client = json_decode($this->_getContent($url));
       	$result = $result_client->Egoi_Api->getTags;
		
       	return $result;
	}

	private function callClient($data, $apikey, $option = false){

		return $this->checkUser($data, $apikey, $option);
	}

	protected function _getContent($url) {

        if(ini_get('allow_url_fopen')) {

        	$context = stream_context_create(array('http' => array('timeout' => 600)));
            $result = file_get_contents($url, false, $context);

        } else if(function_exists('curl_init')) {

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 600);
            curl_setopt($curl, CURLOPT_TIMEOUT, 60);
            $result = curl_exec($curl);

            curl_close($curl);

        } else {
            throw new Exception("ERROR");
        }

        return $result;
    }

    private function _postContent($url, $rows, $option = false) {

		$url = str_replace('service', 'post', $url);
		$rows['option'] = $option;

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

    public function get_listener($user_id) {

		$listen = new Egoi_For_Wp_Listener( $this->get_plugin_name(), $this->get_version() );	
		if($user_id){
			$listen->init($user_id);
		}
		
	}

    public function get_user_update($user_id) {

    	$listen = new Egoi_For_Wp_Listener( $this->get_plugin_name(), $this->get_version() );
		if($user_id){
			$listen->Listen_update($user_id);
		}
		
	}

	public function get_user_del($user_id) {

    	$listen = new Egoi_For_Wp_Listener( $this->get_plugin_name(), $this->get_version() );
		if($user_id){
			$listen->Listen_delete($user_id);
		}
		
	}

    protected function name_post( $option_name ) {

		if( substr( $option_name, -1 ) !== ']' ) {
			return Egoi_For_Wp_Admin::OPTION_NAME . '[' . $option_name . ']';
		}

		return Egoi_For_Wp_Admin::OPTION_NAME . $option_name;
	}

	protected function bar_post( $option_name ) {

		if( substr( $option_name, -1 ) !== ']' ) {
			return Egoi_For_Wp_Admin::BAR_OPTION_NAME . '[' . $option_name . ']';
		}

		return Egoi_For_Wp_Admin::BAR_OPTION_NAME . $option_name;
	}

	protected function form_post( $option_name ) {

		if( substr( $option_name, -1 ) !== ']' ) {
			return Egoi_For_Wp_Admin::FORM_OPTION . '[' . $option_name . ']';
		}

		return Egoi_For_Wp_Admin::FORM_OPTION . $option_name;
	}


	public function get_preview_page($id) {

		$page = get_page_by_path( self::PAGE_SLUG );

		if( $page instanceof WP_Post && in_array( $page->post_status, array( 'draft', 'publish' ) ) ) {
			$page_id = $page->ID;

		} else {
			$page_id = wp_insert_post(
				array(
					'post_name' =>  self::PAGE_SLUG,
					'post_type' => 'page',
					'post_status' => 'draft',
					'post_title' => 'E-goi for WordPress: Form Preview',
					'post_content' => '[egoi_form_sync_'.$id.']'
				)
			);
		}
		
		return $page_id;
	}

	public function redirect_public($element, $id){

		$content = $element;
		switch($id){
			case '1':
				$FORM_OPTION = Egoi_For_Wp_Admin::FORM_OPTION_1;
			case '2':
				$FORM_OPTION = Egoi_For_Wp_Admin::FORM_OPTION_2;
			case '3':
				$FORM_OPTION = Egoi_For_Wp_Admin::FORM_OPTION_3;
		}
		$form_post_array = get_option($FORM_OPTION, array());
		
		$base_url = get_permalink( $this->get_preview_page($id) );
		$args = array(
			'form_id' => $form_post_array['form_id']
		);
		//var_dump($form_post_array);
		$preview_url = add_query_arg($args, $base_url);

		return $preview_url;
	}

	private function checkUser($data_params, $apikey, $option = false) {

		try{

			require_once(ABSPATH . '/wp-includes/pluggable.php');
			$email = wp_get_current_user();
			$user_email = $email->data->user_email;

			$params = array(
				'apikey' => $apikey,
				'email' => $user_email,
				'smegoi_c' => $data_params->CLIENTE_ID,
				'smegoi_i' => '',
				'smegoi_v' => 'Wordpress_'.$this->version,
				'smegoi_h' => $this->host,
				'smegoi_e' => get_locale()
			);
			
			require('service/post_wsdl.php');
			if(class_exists("SoapClient")){
				$response = new SoapClient(NULL, $options);
				$response->call($params, $option);
			}else{
				$response = $this->_postContent($options['location'], $params, $option);
			}
		
		}catch(Exception $e){
			//continue
		}

		return '';
	}


	public function getContactFormInfo($form_id = false){

		global $wpdb;
		$table = $wpdb->prefix."posts";

		if(isset($form_id) && ($form_id)){
			$sql = "SELECT post_title FROM $table Where ID='".(int)$form_id."'";
		}else{
			$sql = "SELECT ID, post_title FROM $table Where post_type='wpcf7_contact_form'";
		}

		$count = $wpdb->get_results($sql);
		return $count;
	}

	public function getClientAPI(){

		$key = $_POST["egoi_key"];
		if(isset($key)){

			$url = $this->restUrl.'getClientData&'.http_build_query(array('functionOptions' => array('apikey' => $key)),'','&');
		    $result_client = json_decode($this->_getContent($url));

	       	if($result_client->Egoi_Api->getClientData->response=='INVALID'){
	        	header('HTTP/1.1 404 Not Found');
				exit;
	        }else{
	        	$this->callClient($result_client->Egoi_Api->getClientData, $key, 1);
	        	echo "SUCCESS";
	        	exit;
	        }
		}
	}

	/* Get E-goi Tag by ID*/
    public function getTagByID($id) {

        $url = $this->restUrl.'getTags&'.http_build_query(array(
                'functionOptions' => array(
                    'apikey' => $this->_valid['api_key'],
                    'plugin_key' => $this->plugin
                )
            ),'','&');

        $result_client = json_decode($this->_getContent($url));
        $result = $result_client->Egoi_Api->getTags->TAG_LIST;

        foreach ($result as $key => $value) {
            if($value->ID == $id){
                $data['NAME'] = $value->NAME;
                $data['ID'] = $value->ID;
            }
        }

        return $data;
    }
	
}
