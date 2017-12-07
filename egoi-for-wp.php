<?php
error_reporting(0);
/**
 *
 * @link              https://www.e-goi.com
 * @since             1.0.0
 * @package           Egoi_For_Wp
 *
 * @wordpress-plugin
 * Plugin Name:       Smart Marketing SMS and Newsletters Forms
 * Plugin URI:        https://www.e-goi.com/en/o/smart-marketing-wordpress/
 * Description:       Smart Marketing for WP adds E-goi's multichannel automation features to WordPress.
 * Version:           1.1.2
 * Author:            E-goi
 * Author URI:        https://www.e-goi.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       egoi-for-wp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined( 'WPINC' )) {
	exit;
}

define('SELF_VERSION', '1.1.2');

function activate_egoi_for_wp() {
	
	if (!version_compare(PHP_VERSION, '5.3.0', '>=')) {
	    echo 'This PHP Version - '.PHP_VERSION.' is obsolete, please update your PHP version to run this plugin';
	    exit;
	}

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-egoi-for-wp-activator.php';
	Egoi_For_Wp_Activator::activate();
}

function deactivate_egoi_for_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-egoi-for-wp-deactivator.php';
	Egoi_For_Wp_Deactivator::deactivate();
	remove_action('widgets_init', 'egoi_widget_init');
}

register_activation_hook( __FILE__, 'activate_egoi_for_wp');
register_deactivation_hook( __FILE__, 'deactivate_egoi_for_wp');


// HOOK FATAL
register_shutdown_function('fatalErrorShutdownHandler');
function WPErrorHandler($code, $message, $file, $line) {
  	echo $code.' - '.$message.' - '.$file.' - '.$line;
  	exit;
}
function fatalErrorShutdownHandler(){
  	$last_error = error_get_last();
  	if ($last_error['type'] === E_ERROR) {
    	WPErrorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
  	}
}

// HOOK TO REMOVE UNNECESSARY AJAX
add_action('wp_enqueue_scripts', 'dequeue_woocommerce_cart_fragments', 11); 
function dequeue_woocommerce_cart_fragments() {
	wp_dequeue_script('wc-cart-fragments'); 
}

// HOOK SYNC USERS
add_action('wp_ajax_add_users', 'add_users');
function add_users(){
	$admin = new Egoi_For_Wp_Admin('smart-marketing-for-wp', SELF_VERSION);
	return $admin->users_queue();
}

// HOOK GET LISTS
add_action('wp_ajax_egoi_get_lists', 'egoi_get_lists');
function egoi_get_lists(){
	$admin = new Egoi_For_Wp_Admin('smart-marketing-for-wp', SELF_VERSION);
    return $admin->get_lists();
}

// HOOK E-GOI LIST GET FORM
add_action('wp_ajax_get_form_from_list', 'get_form_from_list');
function get_form_from_list(){
    $admin = new Egoi_For_Wp_Admin('smart-marketing-for-wp', SELF_VERSION);
    return $admin->get_form_processed();
}

// HOOK BAR GENERATION
add_action('wp_ajax_generate_subscription_bar', 'generate_subscription_bar');
add_action('wp_ajax_nopriv_generate_subscription_bar', 'generate_subscription_bar');
function generate_subscription_bar(){
	$public_area = new Egoi_For_Wp_Public();
	return $public_area->generate_bar($_POST['regenerate']);
}

// HOOK BAR SUBSCRIPTION 
add_action('wp_ajax_process_subscription', 'process_subscription');
add_action('wp_ajax_nopriv_process_subscription', 'process_subscription');
function process_subscription(){
	$public_area = new Egoi_For_Wp_Public();
	return $public_area->subscribe();
}

// HOOK E-GOI FORM SUBSCRIPTION
add_action('wp_ajax_process_egoi_form', 'process_egoi_form');
function process_egoi_form(){
	$public_area = new Egoi_For_Wp_Public();
	return $public_area->subscribe_egoi_form();
}


add_action('widgets_init', 'egoi_widget_init');
function egoi_widget_init(){
	wp_enqueue_script('canvas-loader', plugin_dir_url(__FILE__) . 'admin/js/egoi-for-wp-canvas.js');
	register_widget('Egoi4Widget');
	add_action('init', 'egoi_widget_request'); 
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-egoi-for-wp.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-egoi-for-wp-widget.php';

// HOOK API KEY CHANGES
add_action('wp_ajax_apikey_changes', 'apikey_changes');
function apikey_changes(){
	return Egoi_For_Wp::removeData(true, true);
}

// INITIALIZE PLUGIN
function run_egoi_for_wp() {

	$plugin = new Egoi_For_Wp();
	$plugin->run();

}
run_egoi_for_wp();


function egoi_simple_form( $atts ){
	global $wpdb;

	$id = $atts['id'];

	$post = '<form id="egoi_simple_form" method="post" action="/">';
	$table = $wpdb->prefix.'posts';

	$html_code = $wpdb->get_var(" SELECT post_content FROM $table WHERE ID = '$id' ");
	$tags = array('Name','Email','Mobile','Submit');
	foreach ($tags as $tag) {
		$html_code = str_replace('[Egoi-'.$tag.']','',$html_code);
		$html_code = str_replace('[/Egoi-'.$tag.']','',$html_code);
	}

	$post .= str_replace('\"', '"', $html_code);
	$post .= '<div id="simple_form_result" style="margin:10px 0px; padding:12px; display:none;"></div>';
	$post .= '</form>';
	
	$post .= '
		<script type="text/javascript" >
			jQuery("#egoi_simple_form").submit(function(event) {
				
				event.preventDefault(); // Stop form from submitting normally

				jQuery( "#simple_form_result" ).hide();

				var ajaxurl = "'.admin_url('admin-ajax.php').'";
				var egoi_name = jQuery("#egoi_name").val();
				var egoi_email = jQuery("#egoi_email").val();
				var egoi_mobile	= jQuery("#egoi_mobile").val();

				var data = {
					"action": "my_action",
					"egoi_name": egoi_name,
					"egoi_email": egoi_email,
					"egoi_mobile": egoi_mobile
				};
		
				var posting = jQuery.post(ajaxurl, data);

				posting.done(function( data ) {
					if (data.substring(0, 5) != "ERROR") {
						jQuery( "#simple_form_result" ).css({
							"color": "#4F8A10",
							"background-color": "#DFF2BF"
						});
					} else {
						jQuery( "#simple_form_result" ).css({
							"color": "#9F6000",
							"background-color": "#FFD2D2"
						});
					}

					jQuery( "#simple_form_result" ).empty().append( data ).slideDown( "slow" );
				});
			});
		</script>
	';

	return $post;
}
add_shortcode( 'egoi-simple-form', 'egoi_simple_form' );

add_action( 'wp_ajax_my_action', 'simple_form_add_subscriber' );
add_action( 'wp_ajax_nopriv_my_action', 'simple_form_add_subscriber' );

function simple_form_add_subscriber() {
	
	$client = new SoapClient('http://api.e-goi.com/v2/soap.php?wsdl');
	
	$params = array( 
		'apikey'    => '0a472eba225447a04f417b9c8a00826973f8923b',
		'name' => 'addSubscriber'
	);
	
	$tag = $client->addTag($params);

	$apikey = get_option('egoi_api_key');
	$haslists = get_option('egoi_has_list');
	
	$params = array( 
		'apikey'    => $apikey['api_key'],
		'listID' => $haslists,
		'email' => $_POST['egoi_email'],
		'cellphone' => $_POST['egoi_mobile'],
		'first_name' => $_POST['egoi_name'],
		'status' => 1
	);

	$result = $client->addSubscriber($params);
	
	if (!isset($result['ERROR']) && !isset($result['MODIFICATION_DATE']) ) {
		$error = 'Subscriber '.check_subscriber($result).' is now registered on E-goi!';
	} else if (isset($result['MODIFICATION_DATE'])) {
		$error = 'Subscriber data from '.check_subscriber($result).' has been updated on E-goi!';
	} else if (isset($result['ERROR'])) {
		$error = 'ERROR: '.strtolower(str_replace('_',' ',$result['ERROR']));
	}
	_e($error, 'egoi-for-wp');

	wp_die(); // this is required to terminate immediately and return a proper response
	
}

function check_subscriber($subscriber_data) {
	$data = array('FIRST_NAME','EMAIL','CELLPHONE');
	foreach ($data as $value) {
		if ($subscriber_data[$value]) {
			$subscriber = $subscriber_data[$value];
			break;
		}
	}
	return $subscriber;
}