<?php
require_once(ABSPATH . '/wp-admin/includes/plugin.php');

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.e-goi.com
 * @since      1.0.0
 *
 * @package    Egoi_For_Wp
 * @subpackage Egoi_For_Wp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
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

		if(strpos($this->server_url, 'egoi') !== false){
			// HOOK TO REMOVE FOOTER DEFAULT TEXT
			add_filter('admin_footer_text', array($this, 'remove_footer_admin'), 1, 2);
		}

		//settings pages
		$this->load_api = $this->load_api();
		$this->options_list = $this->load_options();
		$this->bar_post = $this->load_options_bar();
		if(isset($_GET['form'])){
			$id = $_GET['form'];
			$this->form_post = $this->load_options_forms($id);
		}

		register_setting( Egoi_For_Wp_Admin::API_OPTION, Egoi_For_Wp_Admin::API_OPTION);
		register_setting( Egoi_For_Wp_Admin::OPTION_NAME, Egoi_For_Wp_Admin::OPTION_NAME);
		register_setting( Egoi_For_Wp_Admin::BAR_OPTION_NAME, Egoi_For_Wp_Admin::BAR_OPTION_NAME);
		
		//forms
		register_setting( Egoi_For_Wp_Admin::FORM_OPTION_1, Egoi_For_Wp_Admin::FORM_OPTION_1);
		register_setting( Egoi_For_Wp_Admin::FORM_OPTION_2, Egoi_For_Wp_Admin::FORM_OPTION_2);
		register_setting( Egoi_For_Wp_Admin::FORM_OPTION_3, Egoi_For_Wp_Admin::FORM_OPTION_3);
		register_setting( Egoi_For_Wp_Admin::FORM_OPTION_4, Egoi_For_Wp_Admin::FORM_OPTION_4);
		register_setting( Egoi_For_Wp_Admin::FORM_OPTION_5, Egoi_For_Wp_Admin::FORM_OPTION_5);
		
		//hooks Woocommerce
		add_action('woocommerce_add_to_cart', array($this, 'hookCartUpdate'), 10, 3);
		add_action('woocommerce_checkout_order_processed', array($this, 'hookOrderCheck'), 10, 1);

		//paypal
		add_action('valid-paypal-standard-ipn-request', array($this, 'hookIpnResponse'), 10, 1);

		// after billing form
		add_action('woocommerce_after_checkout_billing_form', array($this, 'hookWoocommercePostBilling'), 10);
		
		//hook contact form 7
		add_action('wpcf7_submit', array($this, 'getContactForm'), 10, 1);
		//hook comment form
		add_action('comment_post', array($this, 'insertCommentHook'), 10, 3);
		add_action('comment_form_after_fields', array($this, 'checkNewsletterPostComment'), 10, 1);

		//hook map fields to E-goi
		$this->mapFieldsEgoi();
		
		if(isset($_GET['key']) && (substr($_GET['key'],0,8) == 'wc_order')) {
			$this->execTrackEngage();
		}

		// execute track&engage
		$this->execTrackEngage('addtocartid');

		$rmdata = $_POST['rmdata'];
		if(isset($rmdata)){
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


	public function remove_footer_admin(){

		$url = 'https://wordpress.org/support/plugin/smart-marketing-for-wp/reviews/?filter=5';
        $text = sprintf( esc_html__( 'Please rate %sSmart Marketing SMS and Newsletters Forms%s %s on %sWordPress.org%s to help us spread the word. Thank you from the E-goi team!', 'egoi-for-wp' ), '<strong>', '</strong>', '<a class="" href="' .  $url . '" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a>', '<a href="' . $url . '" target="_blank" rel="noopener noreferrer">', '</a>' );
        return $text;
	}


	public function add_plugin_admin_menu() {
		
		add_menu_page( 'Smart Marketing - Main Page', 'Smart Marketing', 'Egoi_Plugin', $this->plugin_name, array($this, 'display_plugin_setup_page'), plugin_dir_url( __FILE__ ).'img/logo_small.png');

			$capability = 'manage_options';
			add_submenu_page($this->plugin_name, __('Account', 'egoi-for-wp'), __('Account', 'egoi-for-wp'), $capability, 'egoi-4-wp-account', array($this, 'display_plugin_setup_page'));

		$apikey = get_option('egoi_api_key');
		$haslists = get_option('egoi_has_list');
		if($apikey['api_key'] || $haslists){

			add_submenu_page($this->plugin_name, __('Captura', 'egoi-for-wp'), __('Captura', 'egoi-for-wp'), $capability, 'egoi-4-wp-form', array($this, 'display_plugin_subscriber_form'));

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
			'enabled' => '',
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


	public function hookCartUpdate($cart_id_hash, $product_id, $quantity){

		try {

			$list_id = $this->options_list['list'];
			$track = $this->options_list['track'];
			
			if($track){

				$api = new Egoi_For_Wp();
			 	$client = $api->getClient();
				$client_id = $client->CLIENTE_ID;

				$user = wp_get_current_user();
			 	$user_email = $user->data->user_email;
				
			 	$data = array(
			 		'client_id' => $client_id,
			 		'list_id' => $list_id,
			 		'user_email' => $user_email,
			 		'product_id' => $product_id,
			 		'quantity' => $quantity,
			 		'cart_id_hash' => $cart_id_hash
			 	);

			 	$product = get_post($product_id);

			 	/*$args = array(
			 		'post_type' => 'product',
			 		'posts_per_page' => 10
			 	);
			 	$products = get_posts($args);*/
				$this->trackEngage($data, [$product], 1);

			}else{
				return false;
			}

		} catch(Exception $e) {
	    	$this->sendError('WooCommerce - Carrinho Abandonado ERROR', $e->getMessage());
	    }
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

	public function trackEngage($data = array(), $product, $option = false) {

		$client_id = $data['client_id'];
		$list_id = $data['list_id'];
		$user_email = $data['user_email'];
		$product_id = $data['product_id'];
		$quantity = $data['quantity'];

		//extra
		$cart_id_hash = $data['cart_id_hash'];

		$te .= "<script type='text/javascript'>
		var _egoiaq = _egoiaq || [];
		(function(){
			var u=((\"https:\" == document.location.protocol) ? \"https://egoimmerce.e-goi.com/\" : \"http://egoimmerce.e-goi.com/\");
			_egoiaq.push(['setClientId', \"$client_id\"]);
			_egoiaq.push(['setListId', \"$list_id\"]);
			_egoiaq.push(['setSubscriber', \"$user_email\"]);
			_egoiaq.push(['setTrackerUrl', u+'collect']);\n";

		$sale_price = get_post_meta($product_id, '_sale_price', true);
		if(!$sale_price){
			$price = get_post_meta($product_id, '_regular_price', true);
		}

		$sum_price = '';
		foreach($product as $item){
			//if($product_id == $product->ID){
				
				$product_name = $item->post_title;
				$product_cat = ' - ';
				$product_price = $price ? $price : $sale_price;
				$product_quantity = $quantity;

				$sum_price += number_format(($product_price * $product_quantity),2);

				$te .= "_egoiaq.push(['addEcommerceItem',
			    \"$product_id\",
			    \"$product_name\",
			    \"$product_cat\",
			    $product_price,
			    $product_quantity]);\n";
			//}
		}

		$te .= "_egoiaq.push(['trackEcommerceCartUpdate',
			    	$sum_price\n
			    ]);\n";

		$te .= "_egoiaq.push(['trackPageView']);
			var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
			g.type='text/javascript';
			g.defer=true;
			g.async=true;
			g.src=u+'egoimmerce.js';
			s.parentNode.insertBefore(g,s);

			})();
		</script>";

		if(isset($_GET['wc-ajax']) && ($_GET['wc-ajax'] == 'add_to_cart')){

			$content = stripslashes(htmlspecialchars($te, ENT_QUOTES, 'UTF-8'));
			add_option('egoi_track_addtocart_'.$cart_id_hash, array($content));
			if(get_option('addtocartid')){
				delete_option('addtocartid');
			}
			
			add_option('addtocartid', array($cart_id_hash));

			return;

		}else{
			$public = new Egoi_For_Wp_Public($this->plugin_name, $this->version);
			return $public->getTrackEngage($te);
		}
	}

	public function hookIpnResponse($data){

		$order_data = unserialize(str_replace('\"', '"', $data['custom']));
        $order_id = $order_data[0];

		return $this->hookOrderCheck($order_id);
	}


	public function hookOrderCheck($order_id){

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
			if($track){

			 	$client = $api->getClient();
				$client_id = $client->CLIENTE_ID;

				$list_id = $this->options_list['list'];

				$user = wp_get_current_user();
				$user_email = $user->data->user_email;

				$te .= "<script type='text/javascript'>
				var _egoiaq = _egoiaq || [];
				(function(){
				var u=((\"https:\" == document.location.protocol) ? \"https://egoimmerce.e-goi.com/\" : \"http://egoimmerce.e-goi.com/\");
				_egoiaq.push(['setClientId', \"$client_id\"]);
				_egoiaq.push(['setListId', \"$list_id\"]);
				_egoiaq.push(['setSubscriber', \"$user_email\"]);
				_egoiaq.push(['setTrackerUrl', u+'collect']);\n";

				$order = new WC_Order($order_id);
				$items = $order->get_items();

				$order_total = $order->get_total();
				$order_subtotal = $order->get_subtotal();
				$order_tax = $order->get_total_tax();
				$order_shipping = $order->get_total_shipping();
				$order_discount = $order->get_total_discount();

				foreach($items as $item){

					$product_id = $item['product_id'];
					$sale_price = get_post_meta($product_id, '_sale_price', true);
					if(!$sale_price){
						$price = get_post_meta($product_id, '_regular_price', true);
					}

					$product_name = $item['name'];
					$product_cat = ' - ';
					$product_price = $price ? $price : $sale_price;
					$product_quantity = $item['qty'];

					$te .= "_egoiaq.push(['addEcommerceItem',
				    \"$product_id\",
				    \"$product_name\",
				    \"$product_cat\",
				    $product_price,
				    $product_quantity]);\n";
				    
				}
				
				$te .= "_egoiaq.push(['trackEcommerceOrder',
				    \"$order_id\",
				    \"$order_total\",
				    \"$order_subtotal\",
				    $order_tax,
				    $order_shipping,
				    $order_discount]);\n";

				$te .= "_egoiaq.push(['trackPageView']);
					var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
					g.type='text/javascript';
					g.defer=true;
					g.async=true;
					g.src=u+'egoimmerce.js';
					s.parentNode.insertBefore(g,s);

					})();
					</script>";

				$content = stripslashes(htmlspecialchars($te, ENT_QUOTES, 'UTF-8'));
				add_option('egoi_track_order_'.$order_id, array($content));

				//when order is completed
				delete_option('addtocartid');

			}else{
				return false;
			}

		} catch(Exception $e) {
	    	$this->sendError('WooCommerce - Order ERROR', $e->getMessage());
	    }
		
	}

	public function execTrackEngage($arg = ''){
			
		if($arg){
			$cart = get_option($arg);
			if(isset($cart) && ($cart)){
				$content = get_option('egoi_track_addtocart_'.$cart[0]);
				echo html_entity_decode($content[0], ENT_QUOTES);
				delete_option('egoi_track_addtocart_'.$cart[0]);
			}
			
		}else{
			$url = $_SERVER['REQUEST_URI'];
			$split = explode('/', $url);
			$order_id = $split[3];

			$content = get_option('egoi_track_order_'.$order_id);
			echo html_entity_decode($content[0], ENT_QUOTES);

			delete_option('egoi_track_order_'.$order_id);
			return true;
		}
	}

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

									$api->editSubscriber($egoi_int['list_cf'], $email, array($cf7tag, $tag ? $tag : 0), $name, $lname, $extra_fields, $option, $ref_fields);
								}

							}else{
								
								if($subject){ // check if tag exists in E-goi
									$get_tags = $api->getTag($subject);
					                $tag = isset($get_tags['ID']) ? $get_tags['ID'] : $get_tags['NEW_ID'];
						        }

						        // check if tag cf7 exists in E-goi
								$get_tg = $api->getTag($cf7[0]->post_title);
				                $cf7tag = isset($get_tg['ID']) ? $get_tg['ID'] : $get_tg['NEW_ID'];

								$api->addSubscriberTags($egoi_int['list_cf'], $email, array($cf7tag, $tag ? $tag : 0), $name, $lname, 1, $extra_fields, $option, $ref_fields, $status);
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

	//E-goi Map Fields
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


}
