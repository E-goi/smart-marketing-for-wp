<?php
require_once(ABSPATH . '/wp-admin/includes/plugin.php');

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       https://www.e-goi.com
 * @package    Egoi_For_Wp
 * @subpackage Egoi_For_Wp/admin
 * @author     E-goi <info@e-goi.com>
 */
class Egoi_For_Wp_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	const API_OPTION = 'egoi_api_key';
	const OPTION_NAME = 'egoi_sync';
	const BAR_OPTION_NAME = 'egoi_bar_sync';
	const FORM_OPTION_1 = 'egoi_form_sync_1';
	const FORM_OPTION_2 = 'egoi_form_sync_2';
	const FORM_OPTION_3 = 'egoi_form_sync_3';
	const FORM_OPTION_4 = 'egoi_form_sync_4';
	const FORM_OPTION_5 = 'egoi_form_sync_5';
	const FORM_OPTION_6 = 'egoi_form_sync_6';
	const FORM_OPTION_7 = 'egoi_form_sync_7';
	const FORM_OPTION_8 = 'egoi_form_sync_8';
	const FORM_OPTION_9 = 'egoi_form_sync_9';
	const FORM_OPTION_10 = 'egoi_form_sync_10';

	/**
	 * Limit Subscribers
	 * 
	 * @var integer
	 */
	private $limit_subs = 10000;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Server Protocol
	 * 
	 * @var string
	 */
	protected $protocol;

	/**
	 * Server Port if is in use
	 * 
	 * @var string
	 */
	protected $port;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version, $debug = false) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->server_url = $_SERVER['REQUEST_URI'];
		$this->protocol = $_SERVER['HTTPS'] ?: 'http://';
		$this->port = ':'.$_SERVER['SERVER_PORT'];

		if (!session_id()){
    		session_start();
		}

		//settings pages
		$this->load_api = $this->load_api();
		$this->options_list = $this->load_options();
		$this->bar_post = $this->load_options_bar();
		if(isset($_GET['form'])){
			$id = $_GET['form'];
			$this->form_post = $this->load_options_forms($id);
		}

		// register options
		register_setting( Egoi_For_Wp_Admin::API_OPTION, Egoi_For_Wp_Admin::API_OPTION);
		register_setting( Egoi_For_Wp_Admin::OPTION_NAME, Egoi_For_Wp_Admin::OPTION_NAME);
		register_setting( Egoi_For_Wp_Admin::BAR_OPTION_NAME, Egoi_For_Wp_Admin::BAR_OPTION_NAME);
		
		// register forms
		register_setting( Egoi_For_Wp_Admin::FORM_OPTION_1, Egoi_For_Wp_Admin::FORM_OPTION_1);
		register_setting( Egoi_For_Wp_Admin::FORM_OPTION_2, Egoi_For_Wp_Admin::FORM_OPTION_2);
		register_setting( Egoi_For_Wp_Admin::FORM_OPTION_3, Egoi_For_Wp_Admin::FORM_OPTION_3);
		register_setting( Egoi_For_Wp_Admin::FORM_OPTION_4, Egoi_For_Wp_Admin::FORM_OPTION_4);
		register_setting( Egoi_For_Wp_Admin::FORM_OPTION_5, Egoi_For_Wp_Admin::FORM_OPTION_5);
		
		// hooks Core
		add_action('wp_loaded', array($this, 'hookEcommerce'), 10, 1);

		if(strpos($this->server_url, 'egoi') !== false){
			// HOOK TO CHANGE DEFAULT FOOTER TEXT
			add_filter('admin_footer_text', array($this, 'remove_footer_admin'), 1, 2);
		}

		// hooks Woocommerce
		add_action('woocommerce_add_to_cart', array($this, 'hookEcommerce'), 10, 3);
		add_action('woocommerce_after_cart_item_quantity_update', array($this, 'hookCartQuantityUpdate'), 10, 3);
		add_action('woocommerce_before_cart_item_quantity_zero', array($this, 'hookCartQuantityUpdate'), 10, 3);
		add_action('woocommerce_cart_updated', array($this, 'hookRemoveItem'), 10, 3);
		add_action('woocommerce_checkout_order_processed', array($this, 'hookProcessOrder'), 10, 1);

		// paypal
		add_action('valid-paypal-standard-ipn-request', array($this, 'hookIpnResponse'), 10, 1);

		// after billing form
		add_action('woocommerce_after_checkout_billing_form', array($this, 'hookWoocommercePostBilling'), 10);
		
		// hook contact form 7
		add_action('wpcf7_submit', array($this, 'getContactForm'), 10, 1);
		
		// hook comment form
		add_action('comment_post', array($this, 'insertCommentHook'), 10, 3);
		add_action('comment_form_after_fields', array($this, 'checkNewsletterPostComment'), 10, 1);

		//Sets up a JSON endpoint at /wp-json/egoi/v1/products_data/
		add_action( 'rest_api_init', array($this, 'egoi_products_data_api_init'), 10, 3) ;


		// Map shortcode to Visual Composer
		if ( function_exists( 'vc_lean_map' ) ) {
			vc_lean_map( 'egoi_vc_shortcode', array( $this, 'egoi_vc_shortcode_map' ) );
		}

		// hook map fields to E-goi
		$this->mapFieldsEgoi();

		$rmdata = $_POST['rmdata'];
		if(isset($rmdata) && ($rmdata)){
			$this->saveRMData($rmdata);
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/egoi-for-wp-admin.css', array(), $this->version, 'all' );	
		wp_enqueue_style('wp-color-picker');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/egoi-for-wp-admin.js', array('jquery'), $this->version, false);
		
		wp_register_script('custom-script1', plugin_dir_url(__FILE__) . 'js/capture.min.js', array('jquery'));
		wp_enqueue_script('custom-script1');
		
		wp_register_script('custom-script2', plugin_dir_url(__FILE__) . 'js/forms.min.js', array('jquery'));
		wp_enqueue_script('custom-script2');

		wp_register_script('custom-script3', plugin_dir_url(__FILE__) . 'js/egoi-for-wp-map.js', array('jquery'));
		wp_enqueue_script('custom-script3');

		wp_register_script('custom-script4', plugin_dir_url(__FILE__) . 'js/egoi-for-wp-widget.js', array('jquery'));
		wp_enqueue_script('custom-script4');

		wp_register_script('custom-script5', plugin_dir_url(__FILE__) . 'js/clipboard.min.js', array('jquery'));
		wp_enqueue_script('custom-script5');
		
		wp_enqueue_script('wp-color-picker');

		wp_localize_script($this->plugin_name, 'url_egoi_script', array('ajaxurl' => admin_url('admin-ajax.php')));
	}

	/**
	 * Remove footer for the admin area.
	 *
	 * @since    1.1.0
	 */
	public function remove_footer_admin(){

		$url = 'https://wordpress.org/support/plugin/smart-marketing-for-wp/reviews/?filter=5';
        $text = sprintf( esc_html__( 'Please rate %sSmart Marketing SMS and Newsletters Forms%s %s on %sWordPress.org%s to help us spread the word. Thank you from the E-goi team!', 'egoi-for-wp' ), '<strong>', '</strong>', '<a class="" href="' .  $url . '" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a>', '<a href="' . $url . '" target="_blank" rel="noopener noreferrer">', '</a>' );
        return $text;
	}

	/**
	 * Add Admin menu
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		
		add_menu_page( 'Smart Marketing - Main Page', 'Smart Marketing', 'Egoi_Plugin', $this->plugin_name, array($this, 'display_plugin_setup_page'), plugin_dir_url( __FILE__ ).'img/logo_small.png');

		$capability = 'manage_options';
		add_submenu_page($this->plugin_name, __('Account', 'egoi-for-wp'), __('Account', 'egoi-for-wp'), $capability, 'egoi-4-wp-account', array($this, 'display_plugin_setup_page'));

		$apikey = get_option('egoi_api_key');
		$haslists = get_option('egoi_has_list');
		if($apikey['api_key'] && $haslists){

			add_submenu_page($this->plugin_name, __('Capture Contacts', 'egoi-for-wp'), __('Capture Contacts', 'egoi-for-wp'), $capability, 'egoi-4-wp-form', array($this, 'display_plugin_subscriber_form'));

			add_submenu_page($this->plugin_name, __('Sync Contacts', 'egoi-for-wp'), __('Sync Contacts', 'egoi-for-wp'), $capability, 'egoi-4-wp-subscribers', array($this, 'display_plugin_subscriber_page'));

			add_submenu_page($this->plugin_name, __('Ecommerce', 'egoi-for-wp'), __('Ecommerce', 'egoi-for-wp'), $capability, 'egoi-4-wp-ecommerce', array($this, 'display_plugin_subscriber_ecommerce'));

			add_submenu_page($this->plugin_name, __('Integrations', 'egoi-for-wp'), __('Integrations', 'egoi-for-wp'), $capability, 'egoi-4-wp-integrations', array($this, 'display_plugin_integrations'));
		}
	}

	public function add_action_links($links) {
		
		$link_account = 'egoi-4-wp-account';
	   	$settings_link = array(
	    '<a href="'.admin_url('admin.php?page='.$link_account).'">'.__('Settings', $this->plugin_name).'</a>');
	   	return array_merge(  $settings_link, $links );
	}
	
	public function del_action_link($actions) {
			
		if (array_key_exists('edit', $actions )){
			unset($actions ['edit']);
		}
		return $actions;
	}

	public function display_plugin_setup_page() {
	    if (!current_user_can('manage_options')) {
	        wp_die('You do not have sufficient permissions to access this page.');
	    } else {
	    	include_once( 'partials/egoi-for-wp-admin-display.php' );
	    }
	}

	public function display_plugin_lists_page() {
		if (!current_user_can('manage_options')) {
	        wp_die('You do not have sufficient permissions to access this page.');
	    } else {
			include_once( 'partials/egoi-for-wp-admin-lists.php' );
		}
	}

	public function display_plugin_subscriber_page() {

		if (!current_user_can('manage_options')) {
	        wp_die('You do not have sufficient permissions to access this page.');
	    } else {
			include_once( 'partials/egoi-for-wp-admin-subscribers.php' );
		}
	    
	}

	public function display_plugin_subscriber_bar_page() {

		if (!current_user_can('manage_options')) {
	        wp_die('You do not have sufficient permissions to access this page.');
	    } else {
			include_once( 'partials/egoi-for-wp-admin-bar.php' );
		}
	    
	}

	public function display_plugin_subscriber_form() {

		if (!current_user_can('manage_options')) {
	        wp_die('You do not have sufficient permissions to access this page.');
	    } else {
			include_once( 'partials/egoi-for-wp-admin-forms.php' );
		}
	    
	}

	public function display_plugin_subscriber_widget() {

		if (!current_user_can('manage_options')) {
	        wp_die('You do not have sufficient permissions to access this page.');
	    } else {
			include_once( 'partials/egoi-for-wp-admin-widget.php' );
		}
	    
	}

	public function display_plugin_subscriber_ecommerce() {

		if (!current_user_can('manage_options')) {
	        wp_die('You do not have sufficient permissions to access this page.');
	    } else {
			include_once( 'partials/egoi-for-wp-admin-ecommerce.php' );
		}
	    
	}

	public function display_plugin_integrations() {

		if (!current_user_can('manage_options')) {
	        wp_die('You do not have sufficient permissions to access this page.');
	    } else {
			include_once( 'partials/egoi-for-wp-admin-integrations.php' );
		}

	}

	private function load_api() {

		static $api_defaults = array(
			'api_key' => ''
		);

    	if(!get_option( self::API_OPTION, array() )) {
    		add_option( self::API_OPTION, array($api_defaults) );
    	}else{
			$options = (array) get_option( self::API_OPTION, array() );

			$options = array_merge($api_defaults, $options);
			return (array) apply_filters( 'egoi_api_key', $options );
		}
	}

	private function load_options() {

		static $defaults = array(
			'list' => '',
			'enabled' => 0,
			'track' => 1,
			'role' => 'All'
		);

    	if(!get_option( self::OPTION_NAME, array() )) {
    		add_option( self::OPTION_NAME, array($defaults) );
    	}else{
			$options = (array) get_option( self::OPTION_NAME, array() );

			$options = array_merge($defaults, $options);
			return (array) apply_filters( 'egoi_sync_options', $options );
		}
	}

	private function load_options_bar() {

		static $bar_defaults = array(
			'list' => '',
			'double_optin' => 0,
			'send_welcome' => 0,
			'enabled' => 0,
			'open' => 0,
			'text_bar' => '',
			'text_email_placeholder' => '',
			'text_button' => '',
			'position' => 'top',
			'size' => '',
			'color_bar' => '',
			'border_color' => '#ccc',
			'border_px' => '1px',
			'color_text' => '',
			'bar_text_color' => '',
			'sticky' => 0,
			'color_button' => '',
			'color_button_text' => '',
			'success_bgcolor' => '#5cb85c',
			'error_bgcolor' => '#d9534f',
			'text_subscribed' => '',
			'text_invalid_email' => '',
			'text_already_subscribed' => '',
			'text_error' => '',
			'redirect' => ''	
		);

    	if(!get_option( self::BAR_OPTION_NAME, array() )) {
    		add_option( self::BAR_OPTION_NAME, array($bar_defaults) );
    	}else{
			$bar_post = (array) get_option( self::BAR_OPTION_NAME, array() );

			$bar_post = array_merge($bar_defaults, $bar_post);
			return (array) apply_filters( 'egoi_bar_sync_options', $bar_post );
		}
	}

	private function load_options_forms($id) {

		static $form_defaults = array(
			'list' => '',
			'enabled' => 1,
			'show_title' => 0,
			'egoi' => '',
			'form_id' => '',
			'form_name' => '',
			'form_content' => '',
			'width' => '',
			'height' => '',
			'border_color' => '',
			'border' => ''
		);
			
		switch($id) {
			case '1':
				$foption = self::FORM_OPTION_1;
				break;
			case '2':
				$foption = self::FORM_OPTION_2;
				break;
			case '3':
				$foption = self::FORM_OPTION_3;
				break;
			case '4':
				$foption = self::FORM_OPTION_4;
				break;
			case '5':
				$foption = self::FORM_OPTION_5;
				break;
		}

    	if(!get_option( $foption, array() )) {
    		add_option( $foption, array($form_defaults) );
    	}else{

			$form_post = (array) get_option( $foption, array() );
			$form_post = array_merge($form_defaults, $form_post);
			return (array) apply_filters( 'egoi_form_sync', $form_post );
		}
	}

	public function execEc($client_id, $list_id, $user_email, $products = array(), $order_items = array(), $sum_price = false, $cart_zero = 0){

		return include dirname( __DIR__ ) . '/includes/ecommerce/t&e.php';
	}

	/* 
	* -- HOOKS ---
	*/
	public function users_queue(){

		if(isset($_POST['submit']) && ($_POST['submit'])){
		    
		    try {

			    $api = new Egoi_For_Wp(); 
			    $listID = $_POST['listID'];
			    $count_users = count_users();

			    if($count_users['total_users'] > $this->limit_subs){
			    	global $wpdb;
					$sql = "SELECT user_login, user_email, user_url, display_name FROM ".$wpdb->prefix."users LIMIT 100000";
					$users = $wpdb->get_results($sql);
			    }else{
					$users = get_users($args);
			    }
			    
			    $current_user = wp_get_current_user();
			    $current_email = $current_user->data->user_email;

		    	if (class_exists('WooCommerce')) {
					$wc = new WC_Admin_Profile();
					foreach ($wc->get_customer_meta_fields() as $key => $value_field) {
						foreach($value_field['fields'] as $key_value => $label){
							$row_new_value = $api->getFieldMap(0, $key_value);
			                if($row_new_value){
								$woocommerce[$row_new_value] = $key_value;
							}
						}
					}
				}

		    	foreach ($users as $user) {
			        if($current_email != $user->user_email){
			            
			            $name = $user->display_name ? $user->display_name : $user->user_login;
			            $email = $user->user_email;
			            $url = $user->user_url;

			            $full_name = explode(' ', $name);
						$fname = $full_name[0];
						$lname = $full_name[1];

			            $subscribers['status'] = 1;
		                $subscribers['email'] = $email;
		                $subscribers['cellphone'] = '';
		                $subscribers['fax'] = '';
		                $subscribers['telephone'] = '';
		                $subscribers['first_name'] = $fname;
		                $subscribers['last_name'] = $lname;
		                $subscribers['birth_date'] = '';
		                $subscribers['lang'] = '';

		                foreach($woocommerce as $key => $value){
		                    $subscribers[str_replace('key', 'extra', $key)] = $user->$value;
		                }

	                	$subs[] = $subscribers;
			        }
			    }
			    
			    if($count_users['total_users'] >= $this->limit_subs){
				    $subs = array_chunk($subs, $this->limit_subs, true);
				    for($x=0; $x<=9; $x++){
				    	$api->addSubscriberBulk($listID, $tags, $subs[$x]);
				    }
				}else{
					$api->addSubscriberBulk($listID, $tags, $subs);
				}

		    } catch(Exception $e) {
		    	$this->sendError('Bulk Subscription ERROR', $e->getMessage());
		    }

		}

		wp_die();
	}

	public function hookWoocommercePostBilling(){

		try {

			if (!is_user_logged_in()){
				echo '<p class="form-row form-row-wide">
				<input class="input-checkbox" type="checkbox" name="egoi_check_sync" id="egoi_check" value="1" checked>
				<label for="egoi_check" class="checkbox">'.__('Subscribe Newsletter', 'egoi-for-wp').'</label></p>';
			}

		} catch(Exception $e) {
	    	echo $e->getMessage();
	    	return false;
	    }
	}

	/**
	 * Global hook for deploy E-commerce events.
	 *
	 * @param 	 $cart_id
	 * @since    1.1.2
	 */
	public function hookEcommerce($cart_id = false){

		// for security reasons
		if(strpos($this->server_url, 'wp-json') !== false){
			return;
		}

		if(!is_admin()){

			if($cart_id){
				$this->hookCartUpdate();
			}else{

				if(!$_GET['wc-ajax']){

					if(!$_GET['remove_item'] && !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {

						$list_id = $this->options_list['list'];
						$track = $this->options_list['track'];

						if($track && $list_id){

							// check for saved addtocart event
							$validated_cart = $this->checkForCart();
							
							// check for saved Order event
							$validated_order = $this->checkForOrder();

							if($validated_cart && $validated_order){

								if(substr($_GET['key'], 0, 8) != 'wc_order' || $_SESSION[$_GET['key']] == 1 ) {

									if ($_SESSION[$_GET['key']] == 1) {
										//echo 'TESTE';
										$test = get_option('egoi_track_order_'.$_SESSION['egoi_order_id']);
										echo html_entity_decode($test[0], ENT_QUOTES);

									}else{

										// check && execute now for page view
										$client_info = get_option('egoi_client');
										if($client_info){

											$client_id = $client_info->CLIENTE_ID;

											$user = wp_get_current_user();
											$user_email = $user->data->user_email;

											echo $this->execEc($client_id, $list_id, $user_email);
										}
									}
								} else {
									$_SESSION[$_GET['key']] = 1;
									
								}
							}
						}
					} 
				}
			}
		}
	}

	/**
	 * Remove product from E-commerce event AddToCart.
	 *
	 * @param 	 $cart_id
	 * @since    1.1.0
	 */
	public function hookRemoveItem(){
		if(isset($_GET['removed_item']) && ($_GET['removed_item'])){
			$this->hookCartUpdate();
		} 
	}

	public function hookCartQuantityUpdate(){
		$this->hookCartUpdate();
	}

	/**
	 * Process E-commerce event AddToCart.
	 *
	 * @since    1.1.0
	 */
	public function hookCartUpdate(){

		$list_id = $this->options_list['list'];
		$track = $this->options_list['track'];
		
		if($track && $list_id){

			$client_info = get_option('egoi_client');
			if($client_info){

				// if it is a guest
				$session = base64_encode('guest_'.time());

				$user = wp_get_current_user();
			 	$user_id = $user->data->ID;
			 	$user_email = $user->data->user_email;

				$client_id = $client_info->CLIENTE_ID;

			 	$data = array(
			 		'client_id' => $client_id,
			 		'list_id' => $list_id,
			 		'user_email' => $user_email,
			 		'hash_cart' => $user_id ?: $session
			 	);

			 	$_SESSION['egoi_session_cart'] = $data['hash_cart'];

			 	$args = array(
			 		'post_type' => 'product'
			 	);
			 	$products = get_posts($args);

				$this->hookProcessCart($data, $products);
			}
		}
	}

	/**
	 * Process && Execute E-commerce events.
	 *
	 * @param 	 $data
	 * @param 	 $products
	 * @since    1.1.0
	 */
	public function hookProcessCart($data, $products = array()) {


		if(!empty($products) && ($data)){

			global $woocommerce;

			$client_id = $data['client_id'];
			$list_id = $data['list_id'];
			$user_email = $data['user_email'];
			$hash_cart = $data['hash_cart'];

			$sum_price = 0;
			$products = array();


			foreach($woocommerce->cart->get_cart() as $k => $product){

				$product_info = wc_get_product($product['data']->get_id());
				$price = get_post_meta($product['product_id'], '_sale_price', true) ?: get_post_meta($product['product_id'], '_regular_price', true);

				
				$products[$k]['id'] = $product['product_id'];
				$products[$k]['name'] = $product_info->get_title();
				$products[$k]['cat'] = ' - ';
				$products[$k]['price'] = $price;
				$products[$k]['quantity'] = $product['quantity'];
				$sum_price += floatval(round($price * $product['quantity'],2));
			}

			$cart_zero = empty($products) ? 1 : 0;

			$te = $this->execEc($client_id, $list_id, $user_email, $products, array(), $sum_price, $cart_zero);
			$content = stripslashes(htmlspecialchars($te, ENT_QUOTES, 'UTF-8'));
			update_option('egoi_track_addtocart_'.$hash_cart, array($content));

			
			/*
			if(empty($products)){
				echo 'teste';
				echo html_entity_decode($content[0], ENT_QUOTES);
				//$_SESSION['reloadCartPage'] = 1;
			}
			*/
			//update_option('egoi_track_addtocart_'.$hash_cart, array($content));
		}
	}

	

	/**
	 * Process E-commerce event Order for Paypal.
	 *
	 * @param 	 $data
	 * @since    1.0.14
	 */
	public function hookIpnResponse($data){

		$order_data = unserialize(str_replace('\"', '"', $data['custom']));
        $order_id = $order_data[0];

		return $this->hookProcessOrder($order_id);
	}

	/**
	 * Process E-commerce event Order.
	 *
	 * @param 	 $data
	 * @since    1.0.5
	 */
	public function hookProcessOrder($order_id){

		try {

			$api = new Egoi_For_Wp();

			if (!is_user_logged_in()){
				if(!$_POST['createaccount']){
					if($_POST['egoi_check_sync']){
						$first_name = $_POST['billing_first_name'];
						$last_name = $_POST['billing_last_name'];
						$guest_email = $_POST['billing_email'];
						$name = $first_name.' '.$last_name;
						
						$api->addSubscriber($this->options_list['list'], $name, $guest_email, 1, '', 'Guest');
					}
				}
			}

			$track = $this->options_list['track'];
			$list_id = $this->options_list['list'];
			if($track && $list_id){

			 	$client_info = get_option('egoi_client');
				if($client_info){
				
					$user = wp_get_current_user();
					$user_email = $user->data->user_email;
					$client_id = $client_info->CLIENTE_ID;

					$order = new WC_Order($order_id);
					$items = $order->get_items();

					$products = array();
					foreach($items as $k => $item){
						
						$sale_price = get_post_meta($item['product_id'] , '_sale_price', true);
						$regular_price = get_post_meta($item['product_id'] , '_regular_price', true);

						$products[$k]['id'] = $item['product_id'];
						$products[$k]['name'] = $item['name'];
						$products[$k]['cat'] = ' - ';
						$products[$k]['price'] = $sale_price ?: $regular_price;
						$products[$k]['quantity'] = $item['qty'];
					}

					$order_items = array(
						'order_id' => $order_id,
						'order_total' => $order->get_total(),
						'order_subtotal' => $order->get_subtotal(),
						'order_tax' => $order->get_total_tax(),
						'order_shipping' => $order->get_total_shipping(),
						'order_discount' => $order->get_total_discount()
					);

					$te = $this->execEc($client_id, $list_id, $user_email, $products, $order_items);
					$content = stripslashes(htmlspecialchars($te, ENT_QUOTES, 'UTF-8'));
					update_option('egoi_track_order_'.$order_id, array($content));
					$_SESSION['egoi_order_id'] = $order_id;

				}
			}
			
			return false;

		} catch(Exception $e) {
	    	$this->sendError('WooCommerce - Order ERROR', $e->getMessage());
	    }
		
	}

	/**
	 * Checks && Executes E-commerce event if exists.
	 *
	 * @param 	 $data
	 * @since    1.1.2
	 */
	public function checkForCart(){
			
		$cart = $_SESSION['egoi_session_cart'];

		if(isset($cart) && ($cart)){

			$option = 'egoi_track_addtocart_'.$cart;
			
			$content = get_option($option);
			//var_dump($content);
			echo html_entity_decode($content[0], ENT_QUOTES);
			
			delete_option($option);
			unset($_SESSION['egoi_session_cart']);
			return false;
		}

		return true;
	}

	/**
	 * Checks && Executes E-commerce event if exists.
	 *
	 * @param 	 $data
	 * @since    1.1.2
	 */
	public function checkForOrder(){

		if(substr($_GET['key'], 0, 8) != 'wc_order'){
			if(isset($_SESSION['egoi_order_id']) && ($_SESSION['egoi_order_id'])){
				
				$order_id = $_SESSION['egoi_order_id'];
				$content = get_option('egoi_track_order_'.$order_id);
				
				echo html_entity_decode($content[0], ENT_QUOTES);
				delete_option('egoi_track_order_'.$order_id);

				unset($_SESSION['egoi_order_id']);
				return false;
			}
		}

		return true;
	}

	/**
	 * Process data from ContactForm7 POST events.
	 *
	 * @param 	 $result
	 * @since    1.0.1
	 */
	public function getContactForm($result){

		try {

			if(class_exists('WPCF7_ContactForm')){

				$opt = get_option('egoi_int');
				$egoi_int = $opt['egoi_int'];

				if($egoi_int['enable_cf']) {
					
					$api = new Egoi_For_Wp();

					$form_id = $_POST['_wpcf7'];
					if(in_array($form_id, $opt['contact_form'])) {
						
						$key_name = 'your-name';
						$key_email = 'your-email';
						if(strpos($result->form, $key_name) !== false){
							$name = $_POST[$key_name];
						}else{
							if($_POST['first_name']){
								$name = $_POST['first_name'];
							}
						}

						if($_POST['last_name']){
							$lname = $_POST['last_name'];
						}

						if(strpos($result->form, $key_email) !== false){
							$email = $_POST[$key_email];
						}else{
							$match = array_filter(
								$_POST, 
									function($value) {
										return preg_match("/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i", $value); 
									}
								);

							$key = array_keys($match);
							$email = $_POST[$key[0]];
						}

						// telephone
						$get_tel = explode('[tel ', $result->form);
						$str_tel = explode(' ', strstr($get_tel[1], ']', true));
						$tel = $_POST[$str_tel[0]];

						// cellphone
						foreach ($_POST as $key_cell => $value_cell) {
							$cell = strpos($key_cell, 'cell');
							if ($cell !== false) {
								$mobile[] = $value_cell;
							}
						}
						$cell = $mobile[0];

						// birthdate
						$get_bd = explode('[date ', $result->form);
						$str_bd = explode(' ', strstr($get_bd[1], ']', true));
						$bd = $_POST[$str_bd[0]];

						// fax
						if($_POST['egoi-fax']){
							$fax = $_POST['egoi-fax'];
						}

						// lang
						if($_POST['egoi-lang']){
							$lang = $_POST['egoi-lang'];
						}

						// extra fields
						foreach ($_POST as $key => $value) {
							if(is_array($value)){
								$indval = 0;
								foreach ($value as $option_val) {
									$extra_fields[$key] .= $option_val.'; ';
								}
							}else{
								$exra = strpos($key, 'extra_');
								if ($exra !== false) {
									$extra_fields[$key] = $value;
								}
							}
						}

						if(!empty($extra_fields)){
							$option = 1;
						}

						$ref_fields = array('tel' => $tel, 'cell' => $cell, 'bd' => $bd, 'fax' => $fax, 'lang' => $lang);
						
						$subject = $_POST['your-subject'];
						$status = $_POST['status-egoi'];
						$error_msg = $result->prop('messages');
						$error_sent = $error_msg['mail_sent_ng'];

						// get contact form 7 name tag
						$cf7 = $api->getContactFormInfo($form_id);

						// check if subscriber exists
						$get = $api->getSubscriber($egoi_int['list_cf'], $email);
						if($get->subscriber->STATUS != '2'){

							if($get->subscriber->EMAIL == $email){
								$update = $egoi_int['edit'];
								if($update){

									if($subject){ // check if tag exists in E-goi
										$get_tags = $api->getTag($subject);
						                $tag = isset($get_tags['ID']) ? $get_tags['ID'] : $get_tags['NEW_ID'];
							        }

						       		// check if tag cf7 exists in E-goi
									$get_tg = $api->getTag($cf7[0]->post_title);
				                	$cf7tag = isset($get_tg['ID']) ? $get_tg['ID'] : $get_tg['NEW_ID'];

									$api->editSubscriber(
										$egoi_int['list_cf'], 
										$email, 
										array($cf7tag, $tag ? $tag : 0), 
										$name, 
										$lname, 
										$extra_fields, 
										$option, 
										$ref_fields
									);
								}

							}else{
								
								if($subject){ // check if tag exists in E-goi
									$get_tags = $api->getTag($subject);
					                $tag = isset($get_tags['ID']) ? $get_tags['ID'] : $get_tags['NEW_ID'];
						        }

						        // check if tag cf7 exists in E-goi
								$get_tg = $api->getTag($cf7[0]->post_title);
				                $cf7tag = isset($get_tg['ID']) ? $get_tg['ID'] : $get_tg['NEW_ID'];

								$api->addSubscriberTags(
									$egoi_int['list_cf'], 
									$email, 
									array($cf7tag, $tag ? $tag : 0), 
									$name, 
									$lname, 
									1, 
									$extra_fields, 
									$option, 
									$ref_fields, 
									$status
								);
							}
						}else{
							echo $error_sent;
						}
					}

				}
			}

		} catch(Exception $e) {
		    $this->sendError('ContactForm7 ERROR', $e->getMessage());
		}

	}

	/**
	 * Process data from CorePostComments.
	 *
	 * @param 	 $id
	 * @param 	 $approved
	 * @param 	 $data
	 * @since    1.0.0
	 */	
	public function insertCommentHook($id, $approved = false, $data) {

		$opt = get_option('egoi_int');
		$egoi_int = $opt['egoi_int'];

		if($egoi_int['enable_pc']) {

			$name = $data['comment_author'];
			$email = $data['comment_author_email'];
		 	$check = $_POST['check_newsletter'];

		 	if($check == 'on') {

				$api = new Egoi_For_Wp();	
				$add = $api->addSubscriberTags($egoi_int['list_cp'], $email, array($tag), $name, 1);
						
				if($add->UID){
					if($egoi_int['redirect']){
						wp_redirect($egoi_int['redirect']);
						exit;
					}
				}

			}else{
				return false;
			}

		}
	}

	/**
	 * Check if form is available for newsletter.
	 *
	 * @since    1.0.0
	 */	
	public function checkNewsletterPostComment() {

		$opt = get_option('egoi_int');
		$egoi_int = $opt['egoi_int'];

		if($egoi_int['enable_pc']) {
			$check =  "<p class='comment-form-check-newsletter'><label for='check_newsletter'>".__('I want to receive newsletter', 'egoi-for-wp')."</label>
						<input type='checkbox' name='check_newsletter' id='check_newsletter'><p>";
			echo $check;
			return '';
		}
	}
	
	/**
	 * Map custom fields with Core / Woocommerce to E-goi.
	 *
	 * @since    1.0.6
	 */	
	public function mapFieldsEgoi() {

		$id = (int)$_POST["id_egoi"];
		$token = (int)$_POST["token_egoi_api"];
		$wp = $_POST["wp"];
		$egoi = $_POST["egoi"];

		if(isset($token) && ($wp) && ($egoi)){

			global $wpdb;

			$table = $wpdb->prefix."egoi_map_fields";
			$wp_name = $_POST["wp_name"];
			$egoi_name = $_POST["egoi_name"];

			$wp = sanitize_text_field($wp);
			$egoi = sanitize_text_field($egoi);

			$values = array(
				'wp' => $wp,
				'wp_name' => $wp_name,
				'egoi' => $egoi,
				'egoi_name' => $egoi_name,
				'status' => '1'
			);

			$sql_exists = "SELECT COUNT(*) AS COUNT FROM $table WHERE wp='$wp' OR egoi='$egoi'";
			$exists = $wpdb->get_results($sql_exists);

			if(!$exists[0]->COUNT){
				$wpdb->insert($table, $values);

				if($wpdb->insert_id){
					if(!get_option('egoi_mapping')){
						add_option('egoi_mapping', 'true');
					}

				 	$sql="SELECT * FROM $table order by id DESC LIMIT 1";
                 	$rows = $wpdb->get_results($sql);
                 	foreach ($rows as $post){
						echo "<tr id='egoi_fields_$post->id'>";
						$wc = explode('_', $post->wp);
						if(($wc[0] == 'billing') || ($wc[0] == 'shipping')){
							echo "<td style='border-bottom: 1px solid #ccc;font-size: 16px;'>".$post->wp_name." (WooCommerce)</td>";
						}else{
							echo "<td style='border-bottom: 1px solid #ccc;font-size: 16px;'>".$post->wp_name."</td>";
						}
						echo "<td style='border-bottom: 1px solid #ccc;font-size: 16px;'>".$post->egoi_name."</td>
						<td><button type='button' id='field_$post->id' class='egoi_fields button button-secondary' data-target='$post->id'>
							<span class='dashicons dashicons-trash'></span>
							</button>
						</td></tr>";
                 	}
				}

			}else{
				echo "ERROR";
			}
			exit;
			//return '';
			
		}else if(isset($id) && ($id != '')){

			global $wpdb;

			$values = array(
				'id' => $id
			);

			$table = $wpdb->prefix."egoi_map_fields";
			$wpdb->delete($table, $values);

			$sql = "SELECT COUNT(*) FROM $table";
			$count = $wpdb->get_results($sql);
			if($count[0]->COUNT == 0){
				delete_option('egoi_mapping');
			}

			exit;
		}
	}

	private function saveRMData($post = false) {
		
		if(!get_option('egoi_data'))
			add_option('egoi_data', $post);

		update_option('egoi_data', $post);
		exit;
	}

	/*
	* Debug
	*/
	private function sendError($subject, $message) {

		$path = dirname(__FILE__).'/logs/';

		$fp = fopen($path.'logs.txt', 'a+');
		fwrite($fp, $subject.': '.$message."\xA");
		fclose($fp);

		return '';
	}

	public function get_form_processed() {
        
        if(!empty($_POST)){
            $api = new Egoi_For_Wp();
            echo json_encode($api->getForms($_POST['listID']));
        }
        wp_die();
    }

    public function get_lists() {

        if(!empty($_POST)){
            $api = new Egoi_For_Wp();
            echo json_encode($api->getLists($_POST['listID']));
        }
        wp_die();
    }

    // ADD A SIMPLE FORM SUBSCRIBER
	public function subscribe_egoi_simple_form_add() {
		
		$apikey = get_option('egoi_api_key');	
		$list_id = $this->options_list['list'];

		$client = new SoapClient('http://api.e-goi.com/v2/soap.php?wsdl');
		
		$params = array( 
			'apikey'    => $apikey['api_key'],
			'name' => 'addSubscriber'
		);
		
		$tag = $client->addTag($params);

		$params = array( 
			'apikey'    => $apikey['api_key'],
			'listID' => $list_id,
			'email' => filter_var($_POST['egoi_email'], FILTER_SANITIZE_EMAIL),
			'cellphone' => filter_var($_POST['egoi_country_code']."-".$_POST['egoi_mobile'], FILTER_SANITIZE_STRING),
			'first_name' => filter_var($_POST['egoi_name'], FILTER_SANITIZE_STRING),
			'status' => 1
		);

		$result = $client->addSubscriber($params);

		if (!isset($result['ERROR']) && !isset($result['MODIFICATION_DATE']) ) {
			$error = 'Subscriber '.$this->check_subscriber($result).' is now registered on E-goi!';
		} else if (isset($result['MODIFICATION_DATE'])) {
			$error = 'Subscriber data from '.$this->check_subscriber($result).' has been updated on E-goi!';
		} else if (isset($result['ERROR'])) {
			$error = 'ERROR: '.strtolower(str_replace('_',' ',$result['ERROR']));
		}
		
		_e($error, 'egoi-for-wp');
		
		wp_die(); // this is required to terminate immediately and return a proper response
		
	}

	public function check_subscriber($subscriber_data) {
		$data = array('FIRST_NAME','EMAIL','CELLPHONE');
		foreach ($data as $value) {
			if ($subscriber_data[$value]) {
				$subscriber = $subscriber_data[$value];
				break;
			}
		}
		return $subscriber;
	}

	//Sets up a JSON endpoint at /wp-json/egoi/v1/products_data/
	public function egoi_products_data_api_init() {
		$namespace = 'egoi/v1';
		register_rest_route( $namespace, '/products_data/', array(
			'methods' => 'GET',
			'callback' => array( $this, 'egoi_products_data_return' ),
			'args' => array(
				'ids' => array(
					'sanitize_callback'  => 'sanitize_text_field'
				),
			),
		) );
	}

	//Outputs Easy Post data on the JSON endpoint
	public function egoi_products_data_return( WP_REST_Request $request ) {
		
		// Get query strings params from request
		$params = $request->get_query_params('ids');
		
		$params["ids"] = filter_var($params["ids"], FILTER_SANITIZE_STRING);
		$ids = str_replace(" ","",$params["ids"]);
		$ids = explode(",",$ids); 
		foreach ($ids as $value) {
			if (!is_numeric($value)) 
				die();
		}
		$args = array( 'post_type' => array('product', 'product_variation') ,'posts_per_page' => -1);
		$products = get_posts( $args );
	
		$products_data = array();
		foreach ($products as $product) {
			if ( in_array($product->ID, $ids) ) {
	
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->ID ), 'single-post-thumbnail' );
				$sku = get_post_meta( $product->ID, '_sku', true);
				$price = get_post_meta( $product->ID, '_regular_price', true);
				$sale = get_post_meta( $product->ID, '_sale_price', true);
				$sale_dates_from = get_post_meta( $product->ID, '_sale_price_dates_from', true);
				$sale_dates_to = get_post_meta( $product->ID, '_sale_price_dates_to', true);
				$image_gallery = $image[0];
				$upsell_ids = get_post_meta( $product->ID, '_upsell_ids', true);
				$crosssell_ids = get_post_meta( $product->ID, '_crosssell_ids', true);
				$manage_stock = get_post_meta( $product->ID, '_manage_stock', true);
				$stock_quantity = get_post_meta( $product->ID, '_stock', true);
				$stock_status = get_post_meta( $product->ID, '_stock_status', true);
				$weight = get_post_meta( $product->ID, '_weight', true);
				$length = get_post_meta( $product->ID, '_length', true);
				$width = get_post_meta( $product->ID, '_width', true);
				$height = get_post_meta( $product->ID, '_height', true);
				$shipping_class = get_the_terms($product->ID, 'product_shipping_class');
				$categories = get_the_terms( $product->ID, 'product_cat' );
				$tags = get_the_terms( $product->ID, 'product_tag' );
				$virtual = get_post_meta( $product->ID, '_virtual', true);
				$downloadable = get_post_meta( $product->ID, '_downloadable', true);
				$download_limit = get_post_meta( $product->ID, '_download_limit', true);
				$download_expiry = get_post_meta( $product->ID, '_download_expiry', true);
				
				$products_data['items']['item'][] = array(
					'id' => $product->ID,
					'name' => $product->post_title,
					'sku' => $sku,
					'regular_price' => $price,
					'sale_price' => $sale,
					'sale_dates_from' => $sale_dates_from,
					'sale_dates_to' => $sale_dates_to,
					'image_gallery' => $image_gallery,
					'upsell_ids' => $upsell_ids,
					'crosssell_ids' => $crosssell_ids,
					'manage_stock' => $manage_stock,
					'stock_quantity' => $stock_quantity,
					'stock_status' => $stock_status,
					'weight' => $weight,
					'length' => $length,
					'width' => $width,
					'height' => $height,
					'shipping_class' => $shipping_class[0],
					'excerpt' => $product->post_excerpt,
					'categories' => $categories,
					'tags' => $tags,
					'virtual' => $virtual,
					'downloadable' => $downloadable,
					'download_limit' => $download_limit,
					'download_expiry' => $download_expiry
				);
			}
		}
		return $products_data; 
	}


	//Map shortcode to Visual Composer
	public function egoi_vc_shortcode_map() {

        global $wpdb;

        $rows = $wpdb->get_results( " SELECT ID, post_title FROM ".$wpdb->prefix."posts WHERE post_type = 'egoi-simple-form'" );
        $shortcode_ids = array();
        foreach ($rows as $row) {
        	$shortcode_ids[$row->ID." - ".$row->post_title] = $row->ID;
        }
		return array(
			'name'        => 'E-goi',
      		'icon' 		  => plugin_dir_url( __FILE__ ) . "img/logo.png", 
			'description' => 'Shortcode E-goi.',
			'base'        => 'egoi_vc_shortcode',
			'params'      => array(
				array(
					
					'type'       => 'dropdown',
					'heading'    => 'Shortcode ID',
					'param_name' => 'shortcode_id',
					'value'      => $shortcode_ids,
				),
			)
		);

	}
	

}
