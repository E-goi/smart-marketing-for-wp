<?php

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
 * Version:           5.0.9

 * Author:            E-goi
 * Author URI:        https://www.e-goi.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       egoi-for-wp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	exit;
}


define( 'EFWP_SELF_VERSION', '5.0.9' );

function activate_egoi_for_wp() {

	if ( ! version_compare( PHP_VERSION, '7.4.0', '>=' ) ) {
		echo 'This PHP Version - ' . PHP_VERSION . ' is obsolete, please update your PHP version to run this plugin';
		exit;
	}

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-egoi-for-wp-activator.php';
	Egoi_For_Wp_Activator::activate();
}

function deactivate_egoi_for_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-egoi-for-wp-deactivator.php';
	Egoi_For_Wp_Deactivator::deactivate();
	remove_action( 'widgets_init', 'egoi_widget_init' );
}

register_activation_hook( __FILE__, 'activate_egoi_for_wp' );
register_deactivation_hook( __FILE__, 'deactivate_egoi_for_wp' );


// HOOK FATAL
register_shutdown_function( 'egoiFatalErrorShutdownHandler' );
function egoiWPErrorHandler( $code, $message, $file, $line ) {
	echo esc_html( $code . ' - ' . $message . ' - ' . $file . ' - ' . $line );
	exit;
}
function egoiFatalErrorShutdownHandler() {
	$last_error = error_get_last();
	if ( !empty($last_error['type']) && $last_error['type'] === E_ERROR ) {
		egoiWPErrorHandler( E_ERROR, $last_error['message'], $last_error['file'], $last_error['line'] );
	}
}

// HOOK SYNC USERS
add_action( 'wp_ajax_efwp_add_users', 'efwp_add_users' );
function efwp_add_users() {
	$admin = new Egoi_For_Wp_Admin( 'smart-marketing-for-wp', EFWP_SELF_VERSION );
	return $admin->users_queue();
}

// add_filter( 'wp_default_editor', 'force_default_editor' );
// function force_default_editor() {
	// allowed: tinymce, html, test
// return 'tinymce';
// }

// HOOK CRON JOB
add_filter( 'cron_schedules', 'egoi_add_cron_interval' );
function egoi_add_cron_interval( $schedules ) {
	$schedules['sixty_seconds'] = array(
		'interval' => 60,
		'display'  => esc_html__( 'Every Sixty Seconds' ),
	);
	return $schedules;
}

add_action( 'egoi_cron_hook', 'egoi_cron_exec' );

function egoi_cron_exec() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-egoi-for-wp-lazy.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-egoi-for-wp-apiv3.php';

	$converter = new \EgoiLazyConverter();
	$requests  = $converter->getRequests();
	$apikey    = get_option( 'egoi_api_key' );

	if ( empty( $requests ) || ! isset( $apikey['api_key'] ) ) {
		// nothing to do
		return false;
	}

	foreach ( $requests as $request ) {
		switch ( $request['type'] ) {
			case 'SOAP':
				$api    = new SoapClient( $request['url'] );
				$result = $api->{$request['headers']}( $request['body'] );
				break;
			case 'GET':
			case 'POST':
			case 'PUT':
			case 'PATCH':
			case 'DELETE':
			default:
				$client = new ClientHttp(
					$request['url'],
					$request['type'],
					json_decode( $request['headers'], true ),
					json_decode( $request['body'], true )
				);
				// log success
				break;
		}
		$converter->cleanRequestByID( $request['id'] );
	}
}

if ( ! wp_next_scheduled( 'egoi_cron_hook' ) ) {// check next time hook will run
	wp_schedule_event( time(), 'sixty_seconds', 'egoi_cron_hook' );
}

// HOOK GET LISTS
add_action( 'wp_ajax_egoi_get_lists', 'egoi_get_lists' );
function egoi_get_lists() {
	$admin = new Egoi_For_Wp_Admin( 'smart-marketing-for-wp', EFWP_SELF_VERSION );
	return $admin->get_lists();
}

// HOOK E-GOI LIST GET FORM
add_action( 'wp_ajax_efwp_get_form_from_list', 'efwp_get_form_from_list' );
function efwp_get_form_from_list() {
	$admin = new Egoi_For_Wp_Admin( 'smart-marketing-for-wp', EFWP_SELF_VERSION );
	return $admin->get_form_processed();
}

// HOOK BAR GENERATION
add_action( 'wp_ajax_efwp_generate_subscription_bar', 'efwp_generate_subscription_bar' );
add_action( 'wp_ajax_nopriv_efwp_generate_subscription_bar', 'efwp_generate_subscription_bar' );
function efwp_generate_subscription_bar() {
	$public_area = new Egoi_For_Wp_Public();
	return $public_area->generate_bar( sanitize_text_field( $_POST['regenerate'] ) );
}

// HOOK BAR SUBSCRIPTION
add_action( 'wp_ajax_efwp_process_subscription', 'efwp_process_subscription' );
add_action( 'wp_ajax_nopriv_efwp_process_subscription', 'efwp_process_subscription' );
function efwp_process_subscription() {
	$public_area = new Egoi_For_Wp_Public();
	return $public_area->subscribe();
}

// HOOK E-GOI SIMPLE FORM SHORTCODE
function process_egoi_simple_form( $atts ) {
	global $post;
	$public_area = new Egoi_For_Wp_Public();
        // Validation of required fields with JavaScript
		if(!isset($_POST['save'])){
		?>
			<script>
			document.addEventListener('DOMContentLoaded', function() {
				// Select all forms whose ID starts with "egoi_simple_form"
				var forms = document.querySelectorAll('form[id^="egoi_simple_form"]');
				
				forms.forEach(function(form) {
					form.dataset.listenerAdded = 'true';
					if(!form.dataset.listenerAdded){
							form.addEventListener('submit', function(event) {
								var currentForm = this;
								var fields = currentForm.querySelectorAll('input, select, textarea');
								var isValid = true;
			
								fields.forEach(function(field) {
									var label = currentForm.querySelector('label[for="' + field.id + '"]');
			
									if (label && label.textContent.includes('*')) {
										if (field.value.trim() === '') {
											isValid = false;
											label.style.color = 'red'; // Highlight label in red if the field is empty
										} else {
											label.style.color = ''; // Remove highlight if filled correctly
										}
									}
								});
			
								if (!isValid) {
									event.preventDefault();
									alert('<?php _e( 'Please fill out all required fields(*).', 'egoi-for-wp' ); ?>');
								}
							});
						}
				});
			});
			</script>
		<?php
		}

	if(isset($post)){

		$qt   = (int) get_option( 'egoi_simple_form_post_increment_' . $post->ID );
		$size = (int) get_option( 'egoi_simple_form_post_' . $post->ID );
		if ( ! isset( $qt ) && isset( $size ) ) {
			$qt = add_option( 'egoi_simple_form_post_increment_' . $post->ID, 1, 'no' );
			return $public_area->subscribe_egoi_simple_form( $atts, $qt);
		} elseif ( isset( $qt ) && isset( $size ) && $qt <= $size ) {
			if ( $qt == $size ) {
				update_option( 'egoi_simple_form_post_increment_' . $post->ID, 1, 'no' );
			} else {
				update_option( 'egoi_simple_form_post_increment_' . $post->ID, $qt + 1, 'no' );
			}
			return $public_area->subscribe_egoi_simple_form( $atts, $qt );
		} else {
			return $public_area->subscribe_egoi_simple_form( $atts, 1);
		}
	}
}
add_shortcode( 'egoi-simple-form', 'process_egoi_simple_form' );

add_action( 'save_post', 'efwp_process_content_page', 10, 3 );
function efwp_process_content_page( $post_id, $post, $update ) {

	preg_match_all( '/\[egoi-simple-form .*=".*"\]/', $post->post_content, $matches );
	if ( $update && ! empty( $matches ) ) {	
		update_option( 'egoi_simple_form_post_' . sanitize_key($post_id), count($matches[0]) );
        //    undefined usage, removed until proved otherwise
        //    $_POST = array("save" => 1);
	}

}

// HOOK E-GOI VISUAL COMPOSER SHORTCODE
function process_egoi_vc_shortcode( $atts ) {
	$public_area = new Egoi_For_Wp_Public();
	return $public_area->egoi_vc_shortcode_output( $atts );
}
add_shortcode( 'egoi_vc_shortcode', 'process_egoi_vc_shortcode' );

// HOOK E-GOI PAGE BUILDER WIDGET
function add_egoi_pb_widget_folders( $folders ) {
	$folders[] = plugin_dir_path( __FILE__ ) . 'widgets/';
	return $folders;
}
add_action( 'siteorigin_widgets_widget_folders', 'add_egoi_pb_widget_folders' );


add_action( 'widgets_init', 'egoi_widget_init' );
function egoi_widget_init() {
	wp_enqueue_script( 'canvas-loader', plugin_dir_url( __FILE__ ) . 'admin/js/egoi-for-wp-canvas.js', array(), EFWP_SELF_VERSION );
	register_widget( 'Egoi4Widget' );
	add_action( 'init', 'egoi_widget_request' );

	wp_localize_script(
		'canvas-loader',
		'egoi_config_ajax_object_core',
		array(
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'egoi_core_actions' ),
		)
	);
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-egoi-for-wp.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-egoi-for-wp-widget.php';


// HOOK GET TAGS
add_action( 'wp_ajax_egoi_get_tags', 'egoi_get_tags' );
function egoi_get_tags() {
	$admin = new Egoi_For_Wp_Admin( 'smart-marketing-for-wp', EFWP_SELF_VERSION );
	return $admin->get_tags();
}

// HOOK ADD TAG
add_action( 'wp_ajax_egoi_add_tag', 'egoi_add_tag' );
function egoi_add_tag() {
	$admin = new Egoi_For_Wp_Admin( 'smart-marketing-for-wp', EFWP_SELF_VERSION );
	$admin->add_tag( isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '' );
}

add_action( 'elementor/widgets/widgets_registered', 'egoi_register_widgets_elementor' );
function egoi_register_widgets_elementor() {
	require_once plugin_dir_path( __FILE__ ) . 'admin/partials/elementor/egoi-for-wp-elementor-basic-control.php';
	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \EgoiElementorWidget() );
}

add_action( 'elementor/editor/before_enqueue_scripts', 'egoi_widget_scripts' );
function egoi_widget_scripts() {
	wp_enqueue_style( 'elementor-egoi-css', plugin_dir_url( __FILE__ ) . 'admin/css/elementor.css', array(), true, 'all' );
	wp_enqueue_script( 'elementor-egoi', plugins_url( '/admin/js/elementor-egoi-form.js', __FILE__ ), array( 'jquery' ), true, true );
}
/**
 * Hooks for RSS Feeds
 * Registers our custom feed
 */
add_action( 'wp_feed_options', 'efwp_force_feed', 10, 1 );
function efwp_force_feed( $feed ) {
	$feed->force_feed( true );
}

function register_egoi_rss_feeds() {
	$public_area = new Egoi_For_Wp_Public();
	$public_area->add_egoi_rss_feeds();
}
add_action( 'init', 'register_egoi_rss_feeds' );


function egoi_rss_feeds() {
	$admin = new Egoi_For_Wp_Admin( 'smart-marketing-for-wp', EFWP_SELF_VERSION );
	$admin->egoi_rss_feeds_content();
}

/**
 * Adding Custom GTIN Meta Field
 * Save meta data to DB
 */
// add GTIN input field
add_action( 'woocommerce_product_options_inventory_product_data', 'efwp_woocom_simple_product_gtin_field', 10, 1 );
function efwp_woocom_simple_product_gtin_field() {
	echo '<div id="gtin_attr" class="options_group">';
	woocommerce_wp_text_input(
		array(
			'id'          => '_egoi_gtin',
			'label'       => __( 'GTIN', 'egoi-for-wp' ),
			'desc_tip'    => 'true',
			'description' => __( 'Enter the Global Trade Item Number (UPC,EAN,ISBN)', 'egoi-for-wp' ),
		)
	);
	echo '</div>';
}
// save simple product GTIN
add_action( 'woocommerce_process_product_meta', 'efwp_woocom_simple_product_egoi_gtin_save' );
function efwp_woocom_simple_product_egoi_gtin_save( $post_id ) {
	if ( isset( $_POST['_egoi_gtin'] ) && ! empty( $_POST['_egoi_gtin'] ) ) {
		$gtin = sanitize_text_field( $_POST['_egoi_gtin'] );
		update_post_meta( $post_id, '_egoi_gtin', $gtin );
	}
}

// add BRAND input field
add_action( 'woocommerce_product_options_inventory_product_data', 'efwp_woocom_simple_product_brand_field', 10, 1 );
function efwp_woocom_simple_product_brand_field() {
	echo '<div id="brand_attr" class="options_group">';
	// add BRAND field for simple product
	woocommerce_wp_text_input(
		array(
			'id'          => '_egoi_brand',
			'label'       => __( 'Brand', 'egoi-for-wp' ),
			'desc_tip'    => 'true',
			'description' => __( 'Enter the brand of the product', 'egoi-for-wp' ),
		)
	);
	echo '</div>';
}
// save simple product BRAND
add_action( 'woocommerce_process_product_meta', 'efwp_woocom_simple_product_egoi_brand_save' );
function efwp_woocom_simple_product_egoi_brand_save( $post_id ) {
	if ( isset( $_POST['_egoi_brand'] ) && ! empty( $_POST['_egoi_brand'] ) ) {
		$brand = sanitize_text_field( $_POST['_egoi_brand'] );
		update_post_meta( $post_id, '_egoi_brand', $brand );
	}
}

add_action( 'wp_ajax_egoi_preview_popup', 'efwp_popup_preview' );
function efwp_popup_preview() {
	check_ajax_referer( 'egoi_capture_actions', 'security' );

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-egoi-for-wp-popup.php';
	if ( ! EgoiPopUp::isValidPreviewPost( $_POST ) ) {
		?>
			<h1>Invalid Popup Configs</h1>
		<?php
	}

	EgoiPopUp::getPreviewFromPost( $_POST );
	exit;
}

add_action( 'wp_head', 'popups_display' );
function popups_display() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-egoi-for-wp-popup.php';
	return;
}


// COUNTRY MOBILE CODES
define(
	'EFWP_COUNTRY_CODES',
	serialize(
		array(
			'AD' =>
			array(
				'iso3'       => 'AND',
				'country'    => 'Andorra',
				'country_pt' => 'Andorra',
				'prefix'     => '376',
				'language'   => 'ca',
			),
			'AE' =>
			array(
				'iso3'       => 'ARE',
				'country'    => 'United Arab Emirates',
				'country_pt' => 'Emirados Árabes Unidos',
				'prefix'     => '971',
				'language'   => 'ar-AE',
			),
			'AF' =>
			array(
				'iso3'       => 'AFG',
				'country'    => 'Afghanistan',
				'country_pt' => 'Afeganistão',
				'prefix'     => '93',
				'language'   => 'fa-AF',
			),
			'AG' =>
			array(
				'iso3'       => 'ATG',
				'country'    => 'Antigua and Barbuda',
				'country_pt' => 'Antígua e Barbuda',
				'prefix'     => '1268',
				'language'   => 'en-AG',
			),
			'AI' =>
			array(
				'iso3'       => 'AIA',
				'country'    => 'Anguilla',
				'country_pt' => 'Anguilla',
				'prefix'     => '1264',
				'language'   => 'en-AI',
			),
			'AL' =>
			array(
				'iso3'       => 'ALB',
				'country'    => 'Albania',
				'country_pt' => 'Albânia',
				'prefix'     => '355',
				'language'   => 'sq',
			),
			'AM' =>
			array(
				'iso3'       => 'ARM',
				'country'    => 'Armenia',
				'country_pt' => 'Arménia',
				'prefix'     => '374',
				'language'   => 'hy',
			),
			'AO' =>
			array(
				'iso3'       => 'AGO',
				'country'    => 'Angola',
				'country_pt' => 'Angola',
				'prefix'     => '244',
				'language'   => 'pt-AO',
			),
			'AR' =>
			array(
				'iso3'       => 'ARG',
				'country'    => 'Argentina',
				'country_pt' => 'Argentina',
				'prefix'     => '54',
				'language'   => 'es-AR',
			),
			'AS' =>
			array(
				'iso3'       => 'ASM',
				'country'    => 'American Samoa',
				'country_pt' => 'Samoa Americana',
				'prefix'     => '1684',
				'language'   => 'en-AS',
			),
			'AT' =>
			array(
				'iso3'       => 'AUT',
				'country'    => 'Austria',
				'country_pt' => 'Áustria',
				'prefix'     => '43',
				'language'   => 'de-AT',
			),
			'AU' =>
			array(
				'iso3'       => 'AUS',
				'country'    => 'Australia',
				'country_pt' => 'Austrália',
				'prefix'     => '61',
				'language'   => 'en-AU',
			),
			'AW' =>
			array(
				'iso3'       => 'ABW',
				'country'    => 'Aruba',
				'country_pt' => 'Aruba',
				'prefix'     => '297',
				'language'   => 'nl-AW',
			),
			'AX' =>
			array(
				'iso3'       => 'ALA',
				'country'    => 'Aland Islands',
				'country_pt' => 'Aland Islands',
				'prefix'     => '35818',
				'language'   => 'sv-AX',
			),
			'AZ' =>
					array(
						'iso3'       => 'AZE',
						'country'    => 'Azerbaijan',
						'country_pt' => 'Azerbeijão',
						'prefix'     => '994',
						'language'   => 'az',
					),
			'BA' =>
					array(
						'iso3'       => 'BIH',
						'country'    => 'Bosnia and Herzegovina',
						'country_pt' => 'Bósnia-Herzegovina',
						'prefix'     => '387',
						'language'   => 'bs',
					),
			'BB' =>
					array(
						'iso3'       => 'BRB',
						'country'    => 'Barbados',
						'country_pt' => 'Barbados',
						'prefix'     => '1246',
						'language'   => 'en-BB',
					),
			'BD' =>
					array(
						'iso3'       => 'BGD',
						'country'    => 'Bangladesh',
						'country_pt' => 'Bangladesh',
						'prefix'     => '880',
						'language'   => 'bn-BD',
					),
			'BE' =>
					array(
						'iso3'       => 'BEL',
						'country'    => 'Belgium',
						'country_pt' => 'Bélgica',
						'prefix'     => '32',
						'language'   => 'nl-BE',
					),
			'BF' =>
					array(
						'iso3'       => 'BFA',
						'country'    => 'Burkina Faso',
						'country_pt' => 'Burkina-Faso',
						'prefix'     => '226',
						'language'   => 'fr-BF',
					),
			'BG' =>
					array(
						'iso3'       => 'BGR',
						'country'    => 'Bulgaria',
						'country_pt' => 'Bulgária',
						'prefix'     => '359',
						'language'   => 'bg',
					),
			'BH' =>
					array(
						'iso3'       => 'BHR',
						'country'    => 'Bahrain',
						'country_pt' => 'Bahrein',
						'prefix'     => '973',
						'language'   => 'ar-BH',
					),
			'BI' =>
					array(
						'iso3'       => 'BDI',
						'country'    => 'Burundi',
						'country_pt' => 'Burundi',
						'prefix'     => '257',
						'language'   => 'fr-BI',
					),
			'BJ' =>
					array(
						'iso3'       => 'BEN',
						'country'    => 'Benin',
						'country_pt' => 'Benin',
						'prefix'     => '229',
						'language'   => 'fr-BJ',
					),
			'BL' =>
					array(
						'iso3'       => 'BLM',
						'country'    => 'Saint Barthelemy',
						'country_pt' => 'Guadeloupe',
						'prefix'     => '590',
						'language'   => 'fr',
					),
			'BM' =>
					array(
						'iso3'       => 'BMU',
						'country'    => 'Bermuda',
						'country_pt' => 'Bermuda',
						'prefix'     => '1441',
						'language'   => 'en-BM',
					),
			'BN' =>
					array(
						'iso3'       => 'BRN',
						'country'    => 'Brunei',
						'country_pt' => 'Brunei',
						'prefix'     => '673',
						'language'   => 'ms-BN',
					),
			'BO' =>
					array(
						'iso3'       => 'BOL',
						'country'    => 'Bolivia',
						'country_pt' => 'Bolívia',
						'prefix'     => '591',
						'language'   => 'es-BO',
					),
			'BQ' =>
					array(
						'iso3'       => 'BES',
						'country'    => 'Bonaire, Saint Eustatius and Saba ',
						'country_pt' => 'Bonaire, Saint Eustatius and Saba ',
						'prefix'     => '599',
						'language'   => 'nl',
					),
			'BR' =>
					array(
						'iso3'       => 'BRA',
						'country'    => 'Brazil',
						'country_pt' => 'Brasil',
						'prefix'     => '55',
						'language'   => 'pt-BR',
					),
			'BS' =>
					array(
						'iso3'       => 'BHS',
						'country'    => 'Bahamas',
						'country_pt' => 'Baamas',
						'prefix'     => '1242',
						'language'   => 'en-BS',
					),
			'BT' =>
					array(
						'iso3'       => 'BTN',
						'country'    => 'Bhutan',
						'country_pt' => 'Butão',
						'prefix'     => '975',
						'language'   => 'dz',
					),
			'BW' =>
					array(
						'iso3'       => 'BWA',
						'country'    => 'Botswana',
						'country_pt' => 'Botsuana',
						'prefix'     => '267',
						'language'   => 'en-BW',
					),
			'BY' =>
					array(
						'iso3'       => 'BLR',
						'country'    => 'Belarus',
						'country_pt' => 'Bielorrússia',
						'prefix'     => '375',
						'language'   => 'be',
					),
			'BZ' =>
					array(
						'iso3'       => 'BLZ',
						'country'    => 'Belize',
						'country_pt' => 'Belize',
						'prefix'     => '501',
						'language'   => 'en-BZ',
					),
			'CA' =>
					array(
						'iso3'       => 'CAN',
						'country'    => 'Canada',
						'country_pt' => 'Canadá',
						'prefix'     => '1',
						'language'   => 'en-CA',
					),
			'CC' =>
					array(
						'iso3'       => 'CCK',
						'country'    => 'Cocos Islands',
						'country_pt' => 'lhas Cocos',
						'prefix'     => '61',
						'language'   => 'ms-CC',
					),
			'CD' =>
					array(
						'iso3'       => 'COD',
						'country'    => 'Democratic Republic of the Congo',
						'country_pt' => 'República Democrática do Congo',
						'prefix'     => '243',
						'language'   => 'fr-CD',
					),
			'CF' =>
					array(
						'iso3'       => 'CAF',
						'country'    => 'Central African Republic',
						'country_pt' => 'República Centro-Africana',
						'prefix'     => '236',
						'language'   => 'fr-CF',
					),
			'CG' =>
					array(
						'iso3'       => 'COG',
						'country'    => 'Republic of the Congo',
						'country_pt' => 'Congo-Kinshasa',
						'prefix'     => '242',
						'language'   => 'fr-CG',
					),
			'CH' =>
					array(
						'iso3'       => 'CHE',
						'country'    => 'Switzerland',
						'country_pt' => 'Suíça',
						'prefix'     => '41',
						'language'   => 'de-CH',
					),
			'CI' =>
					array(
						'iso3'       => 'CIV',
						'country'    => 'Ivory Coast',
						'country_pt' => 'Costa do Marfim',
						'prefix'     => '225',
						'language'   => 'fr-CI',
					),
			'CK' =>
					array(
						'iso3'       => 'COK',
						'country'    => 'Cook Islands',
						'country_pt' => 'Ilhas Cook',
						'prefix'     => '682',
						'language'   => 'en-CK',
					),
			'CL' =>
					array(
						'iso3'       => 'CHL',
						'country'    => 'Chile',
						'country_pt' => 'Chile',
						'prefix'     => '56',
						'language'   => 'es-CL',
					),
			'CM' =>
					array(
						'iso3'       => 'CMR',
						'country'    => 'Cameroon',
						'country_pt' => 'Camarões',
						'prefix'     => '237',
						'language'   => 'en-CM',
					),
			'CN' =>
					array(
						'iso3'       => 'CHN',
						'country'    => 'China',
						'country_pt' => 'China',
						'prefix'     => '86',
						'language'   => 'zh-CN',
					),
			'CO' =>
					array(
						'iso3'       => 'COL',
						'country'    => 'Colombia',
						'country_pt' => 'Colômbia',
						'prefix'     => '57',
						'language'   => 'es-CO',
					),
			'CR' =>
					array(
						'iso3'       => 'CRI',
						'country'    => 'Costa Rica',
						'country_pt' => 'Costa Rica',
						'prefix'     => '506',
						'language'   => 'es-CR',
					),
			'CU' =>
					array(
						'iso3'       => 'CUB',
						'country'    => 'Cuba',
						'country_pt' => 'Cuba',
						'prefix'     => '53',
						'language'   => 'es-CU',
					),
			'CV' =>
					array(
						'iso3'       => 'CPV',
						'country'    => 'Cape Verde',
						'country_pt' => 'Cabo Verde',
						'prefix'     => '238',
						'language'   => 'pt-CV',
					),
			'CW' =>
					array(
						'iso3'       => 'CUW',
						'country'    => 'Curacao',
						'country_pt' => 'Curacao',
						'prefix'     => '599',
						'language'   => 'nl',
					),
			'CX' =>
					array(
						'iso3'       => 'CXR',
						'country'    => 'Christmas Island',
						'country_pt' => 'Ilha do Natal',
						'prefix'     => '61',
						'language'   => 'en',
					),
			'CY' =>
					array(
						'iso3'       => 'CYP',
						'country'    => 'Cyprus',
						'country_pt' => 'Chipre',
						'prefix'     => '357',
						'language'   => 'el-CY',
					),
			'CZ' =>
					array(
						'iso3'       => 'CZE',
						'country'    => 'Czechia',
						'country_pt' => 'República Checa',
						'prefix'     => '420',
						'language'   => 'cs',
					),
			'DE' =>
					array(
						'iso3'       => 'DEU',
						'country'    => 'Germany',
						'country_pt' => 'Alemanha',
						'prefix'     => '49',
						'language'   => 'de',
					),
			'DJ' =>
					array(
						'iso3'       => 'DJI',
						'country'    => 'Djibouti',
						'country_pt' => 'Jibuti',
						'prefix'     => '253',
						'language'   => 'fr-DJ',
					),
			'DK' =>
					array(
						'iso3'       => 'DNK',
						'country'    => 'Denmark',
						'country_pt' => 'Dinamarca',
						'prefix'     => '45',
						'language'   => 'da-DK',
					),
			'DM' =>
					array(
						'iso3'       => 'DMA',
						'country'    => 'Dominica',
						'country_pt' => 'Dominica',
						'prefix'     => '1767',
						'language'   => 'en-DM',
					),
			'DO' =>
					array(
						'iso3'       => 'DOM',
						'country'    => 'Dominican Republic',
						'country_pt' => 'Dominican Republic',
						'prefix'     => '18091829',
						'language'   => 'es-DO',
					),
			'DZ' =>
					array(
						'iso3'       => 'DZA',
						'country'    => 'Algeria',
						'country_pt' => 'Algéria',
						'prefix'     => '213',
						'language'   => 'ar-DZ',
					),
			'EC' =>
					array(
						'iso3'       => 'ECU',
						'country'    => 'Ecuador',
						'country_pt' => 'Equador',
						'prefix'     => '593',
						'language'   => 'es-EC',
					),
			'EE' =>
					array(
						'iso3'       => 'EST',
						'country'    => 'Estonia',
						'country_pt' => 'Estónia',
						'prefix'     => '372',
						'language'   => 'et',
					),
			'EG' =>
					array(
						'iso3'       => 'EGY',
						'country'    => 'Egypt',
						'country_pt' => 'Egipto',
						'prefix'     => '20',
						'language'   => 'ar-EG',
					),
			'EH' =>
					array(
						'iso3'       => 'ESH',
						'country'    => 'Western Sahara',
						'country_pt' => 'Western Sahara',
						'prefix'     => '212',
						'language'   => 'ar',
					),
			'ER' =>
					array(
						'iso3'       => 'ERI',
						'country'    => 'Eritrea',
						'country_pt' => 'Eritreia',
						'prefix'     => '291',
						'language'   => 'aa-ER',
					),
			'ES' =>
					array(
						'iso3'       => 'ESP',
						'country'    => 'Spain',
						'country_pt' => 'Espanha',
						'prefix'     => '34',
						'language'   => 'es-ES',
					),
			'ET' =>
					array(
						'iso3'       => 'ETH',
						'country'    => 'Ethiopia',
						'country_pt' => 'Etiópia',
						'prefix'     => '251',
						'language'   => 'am',
					),
			'FI' =>
					array(
						'iso3'       => 'FIN',
						'country'    => 'Finland',
						'country_pt' => 'Finlândia',
						'prefix'     => '358',
						'language'   => 'fi-FI',
					),
			'FJ' =>
					array(
						'iso3'       => 'FJI',
						'country'    => 'Fiji',
						'country_pt' => 'Fiji',
						'prefix'     => '679',
						'language'   => 'en-FJ',
					),
			'FK' =>
					array(
						'iso3'       => 'FLK',
						'country'    => 'Falkland Islands',
						'country_pt' => 'Ilhas Falkland',
						'prefix'     => '500',
						'language'   => 'en-FK',
					),
			'FM' =>
					array(
						'iso3'       => 'FSM',
						'country'    => 'Micronesia',
						'country_pt' => 'Micronésia',
						'prefix'     => '691',
						'language'   => 'en-FM',
					),
			'FO' =>
					array(
						'iso3'       => 'FRO',
						'country'    => 'Faroe Islands',
						'country_pt' => 'Ilhas Faroe',
						'prefix'     => '298',
						'language'   => 'fo',
					),
			'FR' =>
					array(
						'iso3'       => 'FRA',
						'country'    => 'France',
						'country_pt' => 'França',
						'prefix'     => '33',
						'language'   => 'fr-FR',
					),
			'GA' =>
					array(
						'iso3'       => 'GAB',
						'country'    => 'Gabon',
						'country_pt' => 'Gabão',
						'prefix'     => '241',
						'language'   => 'fr-GA',
					),
			'GB' =>
					array(
						'iso3'       => 'GBR',
						'country'    => 'United Kingdom',
						'country_pt' => 'Ilha de Man',
						'prefix'     => '44',
						'language'   => 'en-GB',
					),
			'GD' =>
					array(
						'iso3'       => 'GRD',
						'country'    => 'Grenada',
						'country_pt' => 'Granada',
						'prefix'     => '1473',
						'language'   => 'en-GD',
					),
			'GE' =>
					array(
						'iso3'       => 'GEO',
						'country'    => 'Georgia',
						'country_pt' => 'Geórgia',
						'prefix'     => '995',
						'language'   => 'ka',
					),
			'GF' =>
					array(
						'iso3'       => 'GUF',
						'country'    => 'French Guiana',
						'country_pt' => 'French Guiana',
						'prefix'     => '594',
						'language'   => 'fr-GF',
					),
			'GG' =>
					array(
						'iso3'       => 'GGY',
						'country'    => 'Guernsey',
						'country_pt' => 'Guernsey',
						'prefix'     => '441481',
						'language'   => 'en',
					),
			'GH' =>
					array(
						'iso3'       => 'GHA',
						'country'    => 'Ghana',
						'country_pt' => 'Gana',
						'prefix'     => '233',
						'language'   => 'en-GH',
					),
			'GI' =>
					array(
						'iso3'       => 'GIB',
						'country'    => 'Gibraltar',
						'country_pt' => 'Gibraltar',
						'prefix'     => '350',
						'language'   => 'en-GI',
					),
			'GL' =>
					array(
						'iso3'       => 'GRL',
						'country'    => 'Greenland',
						'country_pt' => 'Gronelândia',
						'prefix'     => '299',
						'language'   => 'kl',
					),
			'GM' =>
					array(
						'iso3'       => 'GMB',
						'country'    => 'Gambia',
						'country_pt' => 'Gâmbia',
						'prefix'     => '220',
						'language'   => 'en-GM',
					),
			'GN' =>
					array(
						'iso3'       => 'GIN',
						'country'    => 'Guinea',
						'country_pt' => 'Guiné',
						'prefix'     => '224',
						'language'   => 'fr-GN',
					),
			'GP' =>
					array(
						'iso3'       => 'GLP',
						'country'    => 'Guadeloupe',
						'country_pt' => 'Guadeloupe',
						'prefix'     => '590',
						'language'   => 'fr-GP',
					),
			'GQ' =>
					array(
						'iso3'       => 'GNQ',
						'country'    => 'Equatorial Guinea',
						'country_pt' => 'Guiné Equatorial',
						'prefix'     => '240',
						'language'   => 'es-GQ',
					),
			'GR' =>
					array(
						'iso3'       => 'GRC',
						'country'    => 'Greece',
						'country_pt' => 'Grécia',
						'prefix'     => '30',
						'language'   => 'el-GR',
					),
			'GT' =>
					array(
						'iso3'       => 'GTM',
						'country'    => 'Guatemala',
						'country_pt' => 'Guatemala',
						'prefix'     => '502',
						'language'   => 'es-GT',
					),
			'GU' =>
					array(
						'iso3'       => 'GUM',
						'country'    => 'Guam',
						'country_pt' => 'Guam',
						'prefix'     => '1671',
						'language'   => 'en-GU',
					),
			'GW' =>
					array(
						'iso3'       => 'GNB',
						'country'    => 'Guinea-Bissau',
						'country_pt' => 'Guiné-Bissau',
						'prefix'     => '245',
						'language'   => 'pt-GW',
					),
			'GY' =>
					array(
						'iso3'       => 'GUY',
						'country'    => 'Guyana',
						'country_pt' => 'Guiana',
						'prefix'     => '592',
						'language'   => 'en-GY',
					),
			'HK' =>
					array(
						'iso3'       => 'HKG',
						'country'    => 'Hong Kong',
						'country_pt' => 'Hong Kong',
						'prefix'     => '852',
						'language'   => 'zh-HK',
					),
			'HN' =>
					array(
						'iso3'       => 'HND',
						'country'    => 'Honduras',
						'country_pt' => 'Honduras',
						'prefix'     => '504',
						'language'   => 'es-HN',
					),
			'HR' =>
					array(
						'iso3'       => 'HRV',
						'country'    => 'Croatia',
						'country_pt' => 'Croácia',
						'prefix'     => '385',
						'language'   => 'hr-HR',
					),
			'HT' =>
					array(
						'iso3'       => 'HTI',
						'country'    => 'Haiti',
						'country_pt' => 'Haiti',
						'prefix'     => '509',
						'language'   => 'ht',
					),
			'HU' =>
					array(
						'iso3'       => 'HUN',
						'country'    => 'Hungary',
						'country_pt' => 'Hungria',
						'prefix'     => '36',
						'language'   => 'hu-HU',
					),
			'ID' =>
					array(
						'iso3'       => 'IDN',
						'country'    => 'Indonesia',
						'country_pt' => 'Indonésia',
						'prefix'     => '62',
						'language'   => 'id',
					),
			'IE' =>
					array(
						'iso3'       => 'IRL',
						'country'    => 'Ireland',
						'country_pt' => 'Irlanda',
						'prefix'     => '353',
						'language'   => 'en-IE',
					),
			'IL' =>
					array(
						'iso3'       => 'ISR',
						'country'    => 'Israel',
						'country_pt' => 'Israel',
						'prefix'     => '972',
						'language'   => 'he',
					),
			'IM' =>
					array(
						'iso3'       => 'IMN',
						'country'    => 'Isle of Man',
						'country_pt' => 'Ilha de Man',
						'prefix'     => '441624',
						'language'   => 'en',
					),
			'IN' =>
					array(
						'iso3'       => 'IND',
						'country'    => 'India',
						'country_pt' => 'Índia',
						'prefix'     => '91',
						'language'   => 'en-IN',
					),
			'IO' =>
					array(
						'iso3'       => 'IOT',
						'country'    => 'British Indian Ocean Territory',
						'country_pt' => 'British Indian Ocean Territory',
						'prefix'     => '246',
						'language'   => 'en-IO',
					),
			'IQ' =>
					array(
						'iso3'       => 'IRQ',
						'country'    => 'Iraq',
						'country_pt' => 'Iraque',
						'prefix'     => '964',
						'language'   => 'ar-IQ',
					),
			'IR' =>
					array(
						'iso3'       => 'IRN',
						'country'    => 'Iran',
						'country_pt' => 'Irão',
						'prefix'     => '98',
						'language'   => 'fa-IR',
					),
			'IS' =>
					array(
						'iso3'       => 'ISL',
						'country'    => 'Iceland',
						'country_pt' => 'Islândia',
						'prefix'     => '354',
						'language'   => 'is',
					),
			'IT' =>
					array(
						'iso3'       => 'ITA',
						'country'    => 'Italy',
						'country_pt' => 'Itália',
						'prefix'     => '39',
						'language'   => 'it-IT',
					),
			'JE' =>
					array(
						'iso3'       => 'JEY',
						'country'    => 'Jersey',
						'country_pt' => 'Jersey',
						'prefix'     => '441534',
						'language'   => 'en',
					),
			'JM' =>
					array(
						'iso3'       => 'JAM',
						'country'    => 'Jamaica',
						'country_pt' => 'Jamaica',
						'prefix'     => '1876',
						'language'   => 'en-JM',
					),
			'JO' =>
					array(
						'iso3'       => 'JOR',
						'country'    => 'Jordan',
						'country_pt' => 'Jordânia',
						'prefix'     => '962',
						'language'   => 'ar-JO',
					),
			'JP' =>
					array(
						'iso3'       => 'JPN',
						'country'    => 'Japan',
						'country_pt' => 'Japão',
						'prefix'     => '81',
						'language'   => 'ja',
					),
			'KE' =>
					array(
						'iso3'       => 'KEN',
						'country'    => 'Kenya',
						'country_pt' => 'Quénia',
						'prefix'     => '254',
						'language'   => 'en-KE',
					),
			'KG' =>
					array(
						'iso3'       => 'KGZ',
						'country'    => 'Kyrgyzstan',
						'country_pt' => 'Kyrgyzstan',
						'prefix'     => '996',
						'language'   => 'ky',
					),
			'KH' =>
					array(
						'iso3'       => 'KHM',
						'country'    => 'Cambodia',
						'country_pt' => 'Camboja',
						'prefix'     => '855',
						'language'   => 'km',
					),
			'KI' =>
					array(
						'iso3'       => 'KIR',
						'country'    => 'Kiribati',
						'country_pt' => 'Quiribati',
						'prefix'     => '686',
						'language'   => 'en-KI',
					),
			'KM' =>
					array(
						'iso3'       => 'COM',
						'country'    => 'Comoros',
						'country_pt' => 'Comores',
						'prefix'     => '269',
						'language'   => 'ar',
					),
			'KN' =>
					array(
						'iso3'       => 'KNA',
						'country'    => 'Saint Kitts and Nevis',
						'country_pt' => 'Saint Kitts e Nevis',
						'prefix'     => '1869',
						'language'   => 'en-KN',
					),
			'KP' =>
					array(
						'iso3'       => 'PRK',
						'country'    => 'North Korea',
						'country_pt' => 'Coreia do Norte',
						'prefix'     => '850',
						'language'   => 'ko-KP',
					),
			'KR' =>
					array(
						'iso3'       => 'KOR',
						'country'    => 'South Korea',
						'country_pt' => 'Coreia do Sul',
						'prefix'     => '82',
						'language'   => 'ko-KR',
					),
			'KW' =>
					array(
						'iso3'       => 'KWT',
						'country'    => 'Kuwait',
						'country_pt' => 'Koweit',
						'prefix'     => '965',
						'language'   => 'ar-KW',
					),
			'KY' =>
					array(
						'iso3'       => 'CYM',
						'country'    => 'Cayman Islands',
						'country_pt' => 'Ilhas Cayman',
						'prefix'     => '1345',
						'language'   => 'en-KY',
					),
			'KZ' =>
					array(
						'iso3'       => 'KAZ',
						'country'    => 'Kazakhstan',
						'country_pt' => 'Cazaquistão',
						'prefix'     => '7',
						'language'   => 'kk',
					),
			'LA' =>
					array(
						'iso3'       => 'LAO',
						'country'    => 'Laos',
						'country_pt' => 'Laos',
						'prefix'     => '856',
						'language'   => 'lo',
					),
			'LB' =>
					array(
						'iso3'       => 'LBN',
						'country'    => 'Lebanon',
						'country_pt' => 'Líbano',
						'prefix'     => '961',
						'language'   => 'ar-LB',
					),
			'LC' =>
					array(
						'iso3'       => 'LCA',
						'country'    => 'Saint Lucia',
						'country_pt' => 'Santa Lúcia',
						'prefix'     => '1758',
						'language'   => 'en-LC',
					),
			'LI' =>
					array(
						'iso3'       => 'LIE',
						'country'    => 'Liechtenstein',
						'country_pt' => 'Liechtenstein',
						'prefix'     => '423',
						'language'   => 'de-LI',
					),
			'LK' =>
					array(
						'iso3'       => 'LKA',
						'country'    => 'Sri Lanka',
						'country_pt' => 'Sri Lanka',
						'prefix'     => '94',
						'language'   => 'si',
					),
			'LR' =>
					array(
						'iso3'       => 'LBR',
						'country'    => 'Liberia',
						'country_pt' => 'Libéria',
						'prefix'     => '231',
						'language'   => 'en-LR',
					),
			'LS' =>
					array(
						'iso3'       => 'LSO',
						'country'    => 'Lesotho',
						'country_pt' => 'Lesoto',
						'prefix'     => '266',
						'language'   => 'en-LS',
					),
			'LT' =>
					array(
						'iso3'       => 'LTU',
						'country'    => 'Lithuania',
						'country_pt' => 'Lituânia',
						'prefix'     => '370',
						'language'   => 'lt',
					),
			'LU' =>
					array(
						'iso3'       => 'LUX',
						'country'    => 'Luxembourg',
						'country_pt' => 'Luxemburgo',
						'prefix'     => '352',
						'language'   => 'lb',
					),
			'LV' =>
					array(
						'iso3'       => 'LVA',
						'country'    => 'Latvia',
						'country_pt' => 'Letónia',
						'prefix'     => '371',
						'language'   => 'lv',
					),
			'LY' =>
					array(
						'iso3'       => 'LBY',
						'country'    => 'Libya',
						'country_pt' => 'Líbia',
						'prefix'     => '218',
						'language'   => 'ar-LY',
					),
			'MA' =>
					array(
						'iso3'       => 'MAR',
						'country'    => 'Morocco',
						'country_pt' => 'Marrocos',
						'prefix'     => '212',
						'language'   => 'ar-MA',
					),
			'MC' =>
					array(
						'iso3'       => 'MCO',
						'country'    => 'Monaco',
						'country_pt' => 'Mónaco',
						'prefix'     => '377',
						'language'   => 'fr-MC',
					),
			'MD' =>
					array(
						'iso3'       => 'MDA',
						'country'    => 'Moldova',
						'country_pt' => 'Moldávia',
						'prefix'     => '373',
						'language'   => 'ro',
					),
			'ME' =>
					array(
						'iso3'       => 'MNE',
						'country'    => 'Montenegro',
						'country_pt' => 'Montenegro',
						'prefix'     => '382',
						'language'   => 'sr',
					),
			'MF' =>
					array(
						'iso3'       => 'MAF',
						'country'    => 'Saint Martin',
						'country_pt' => 'Saint Martin',
						'prefix'     => '590',
						'language'   => 'fr',
					),
			'MG' =>
					array(
						'iso3'       => 'MDG',
						'country'    => 'Madagascar',
						'country_pt' => 'Madagáscar',
						'prefix'     => '261',
						'language'   => 'fr-MG',
					),
			'MH' =>
					array(
						'iso3'       => 'MHL',
						'country'    => 'Marshall Islands',
						'country_pt' => 'Ilhas Marshall',
						'prefix'     => '692',
						'language'   => 'mh',
					),
			'MK' =>
					array(
						'iso3'       => 'MKD',
						'country'    => 'Macedonia',
						'country_pt' => 'Macedónia',
						'prefix'     => '389',
						'language'   => 'mk',
					),
			'ML' =>
					array(
						'iso3'       => 'MLI',
						'country'    => 'Mali',
						'country_pt' => 'Mali',
						'prefix'     => '223',
						'language'   => 'fr-ML',
					),
			'MM' =>
					array(
						'iso3'       => 'MMR',
						'country'    => 'Myanmar',
						'country_pt' => 'Mianmar',
						'prefix'     => '95',
						'language'   => 'my',
					),
			'MN' =>
					array(
						'iso3'       => 'MNG',
						'country'    => 'Mongolia',
						'country_pt' => 'Mongólia',
						'prefix'     => '976',
						'language'   => 'mn',
					),
			'MO' =>
					array(
						'iso3'       => 'MAC',
						'country'    => 'Macao',
						'country_pt' => 'Macau',
						'prefix'     => '853',
						'language'   => 'zh',
					),
			'MP' =>
					array(
						'iso3'       => 'MNP',
						'country'    => 'Northern Mariana Islands',
						'country_pt' => 'Ilhas Marianas do Norte',
						'prefix'     => '1670',
						'language'   => 'fil',
					),
			'MQ' =>
					array(
						'iso3'       => 'MTQ',
						'country'    => 'Martinique',
						'country_pt' => 'Martinique',
						'prefix'     => '596',
						'language'   => 'fr-MQ',
					),
			'MR' =>
					array(
						'iso3'       => 'MRT',
						'country'    => 'Mauritania',
						'country_pt' => 'Mauritânia',
						'prefix'     => '222',
						'language'   => 'ar-MR',
					),
			'MS' =>
					array(
						'iso3'       => 'MSR',
						'country'    => 'Montserrat',
						'country_pt' => 'Montserrat',
						'prefix'     => '1664',
						'language'   => 'en-MS',
					),
			'MT' =>
					array(
						'iso3'       => 'MLT',
						'country'    => 'Malta',
						'country_pt' => 'Malta',
						'prefix'     => '356',
						'language'   => 'mt',
					),
			'MU' =>
					array(
						'iso3'       => 'MUS',
						'country'    => 'Mauritius',
						'country_pt' => 'Maurícia',
						'prefix'     => '230',
						'language'   => 'en-MU',
					),
			'MV' =>
					array(
						'iso3'       => 'MDV',
						'country'    => 'Maldives',
						'country_pt' => 'Maldivas',
						'prefix'     => '960',
						'language'   => 'dv',
					),
			'MW' =>
					array(
						'iso3'       => 'MWI',
						'country'    => 'Malawi',
						'country_pt' => 'Malawi',
						'prefix'     => '265',
						'language'   => 'ny',
					),
			'MX' =>
					array(
						'iso3'       => 'MEX',
						'country'    => 'Mexico',
						'country_pt' => 'México',
						'prefix'     => '52',
						'language'   => 'es-MX',
					),
			'MY' =>
					array(
						'iso3'       => 'MYS',
						'country'    => 'Malaysia',
						'country_pt' => 'Malásia',
						'prefix'     => '60',
						'language'   => 'ms-MY',
					),
			'MZ' =>
					array(
						'iso3'       => 'MOZ',
						'country'    => 'Mozambique',
						'country_pt' => 'Moçambique',
						'prefix'     => '258',
						'language'   => 'pt-MZ',
					),
			'NA' =>
					array(
						'iso3'       => 'NAM',
						'country'    => 'Namibia',
						'country_pt' => 'Namíbia',
						'prefix'     => '264',
						'language'   => 'en-NA',
					),
			'NC' =>
					array(
						'iso3'       => 'NCL',
						'country'    => 'New Caledonia',
						'country_pt' => 'Nova Caledonia',
						'prefix'     => '687',
						'language'   => 'fr-NC',
					),
			'NE' =>
					array(
						'iso3'       => 'NER',
						'country'    => 'Niger',
						'country_pt' => 'Níger',
						'prefix'     => '227',
						'language'   => 'fr-NE',
					),
			'NF' =>
					array(
						'iso3'       => 'NFK',
						'country'    => 'Norfolk Island',
						'country_pt' => 'Norfolk Island',
						'prefix'     => '672',
						'language'   => 'en-NF',
					),
			'NG' =>
					array(
						'iso3'       => 'NGA',
						'country'    => 'Nigeria',
						'country_pt' => 'Nigéria',
						'prefix'     => '234',
						'language'   => 'en-NG',
					),
			'NI' =>
					array(
						'iso3'       => 'NIC',
						'country'    => 'Nicaragua',
						'country_pt' => 'Nicarágua',
						'prefix'     => '505',
						'language'   => 'es-NI',
					),
			'NL' =>
					array(
						'iso3'       => 'NLD',
						'country'    => 'Netherlands',
						'country_pt' => 'Países Baixos',
						'prefix'     => '31',
						'language'   => 'nl-NL',
					),
			'NO' =>
					array(
						'iso3'       => 'NOR',
						'country'    => 'Norway',
						'country_pt' => 'Noruega',
						'prefix'     => '47',
						'language'   => 'no',
					),
			'NP' =>
					array(
						'iso3'       => 'NPL',
						'country'    => 'Nepal',
						'country_pt' => 'Nepal',
						'prefix'     => '977',
						'language'   => 'ne',
					),
			'NR' =>
					array(
						'iso3'       => 'NRU',
						'country'    => 'Nauru',
						'country_pt' => 'Nauru',
						'prefix'     => '674',
						'language'   => 'na',
					),
			'NU' =>
					array(
						'iso3'       => 'NIU',
						'country'    => 'Niue',
						'country_pt' => 'Niue',
						'prefix'     => '683',
						'language'   => 'niu',
					),
			'NZ' =>
					array(
						'iso3'       => 'NZL',
						'country'    => 'New Zealand',
						'country_pt' => 'Nova Zelândia',
						'prefix'     => '64',
						'language'   => 'en-NZ',
					),
			'OM' =>
					array(
						'iso3'       => 'OMN',
						'country'    => 'Oman',
						'country_pt' => 'Omã',
						'prefix'     => '968',
						'language'   => 'ar-OM',
					),
			'PA' =>
					array(
						'iso3'       => 'PAN',
						'country'    => 'Panama',
						'country_pt' => 'Panamá',
						'prefix'     => '507',
						'language'   => 'es-PA',
					),
			'PE' =>
					array(
						'iso3'       => 'PER',
						'country'    => 'Peru',
						'country_pt' => 'Peru',
						'prefix'     => '51',
						'language'   => 'es-PE',
					),
			'PF' =>
					array(
						'iso3'       => 'PYF',
						'country'    => 'French Polynesia',
						'country_pt' => 'Polinésia Francêsa',
						'prefix'     => '689',
						'language'   => 'fr-PF',
					),
			'PG' =>
					array(
						'iso3'       => 'PNG',
						'country'    => 'Papua New Guinea',
						'country_pt' => 'Papua Nova Guiné',
						'prefix'     => '675',
						'language'   => 'en-PG',
					),
			'PH' =>
					array(
						'iso3'       => 'PHL',
						'country'    => 'Philippines',
						'country_pt' => 'Filipinas',
						'prefix'     => '63',
						'language'   => 'tl',
					),
			'PK' =>
					array(
						'iso3'       => 'PAK',
						'country'    => 'Pakistan',
						'country_pt' => 'Paquistão',
						'prefix'     => '92',
						'language'   => 'ur-PK',
					),
			'PL' =>
					array(
						'iso3'       => 'POL',
						'country'    => 'Poland',
						'country_pt' => 'Polónia',
						'prefix'     => '48',
						'language'   => 'pl',
					),
			'PM' =>
					array(
						'iso3'       => 'SPM',
						'country'    => 'Saint Pierre and Miquelon',
						'country_pt' => 'Saint Pierre and Miquelon',
						'prefix'     => '508',
						'language'   => 'fr-PM',
					),
			'PN' =>
					array(
						'iso3'       => 'PCN',
						'country'    => 'Pitcairn',
						'country_pt' => 'Pitcairn',
						'prefix'     => '870',
						'language'   => 'en-PN',
					),
			'PR' =>
					array(
						'iso3'       => 'PRI',
						'country'    => 'Puerto Rico',
						'country_pt' => 'Puerto Rico',
						'prefix'     => '17871939',
						'language'   => 'en-PR',
					),
			'PS' =>
					array(
						'iso3'       => 'PSE',
						'country'    => 'Palestinian Territory',
						'country_pt' => 'Palestinian Territory',
						'prefix'     => '970',
						'language'   => 'ar-PS',
					),
			'PT' =>
					array(
						'iso3'       => 'PRT',
						'country'    => 'Portugal',
						'country_pt' => 'Portugal',
						'prefix'     => '351',
						'language'   => 'pt-PT',
					),
			'PW' =>
					array(
						'iso3'       => 'PLW',
						'country'    => 'Palau',
						'country_pt' => 'Palau',
						'prefix'     => '680',
						'language'   => 'pau',
					),
			'PY' =>
					array(
						'iso3'       => 'PRY',
						'country'    => 'Paraguay',
						'country_pt' => 'Paraguai',
						'prefix'     => '595',
						'language'   => 'es-PY',
					),
			'QA' =>
					array(
						'iso3'       => 'QAT',
						'country'    => 'Qatar',
						'country_pt' => 'Qatar',
						'prefix'     => '974',
						'language'   => 'ar-QA',
					),
			'RE' =>
					array(
						'iso3'       => 'REU',
						'country'    => 'Reunion',
						'country_pt' => 'Reunion',
						'prefix'     => '262',
						'language'   => 'fr-RE',
					),
			'RO' =>
					array(
						'iso3'       => 'ROU',
						'country'    => 'Romania',
						'country_pt' => 'Roménia',
						'prefix'     => '40',
						'language'   => 'ro',
					),
			'RS' =>
					array(
						'iso3'       => 'SRB',
						'country'    => 'Serbia',
						'country_pt' => 'Sérvia',
						'prefix'     => '381',
						'language'   => 'sr',
					),
			'RU' =>
					array(
						'iso3'       => 'RUS',
						'country'    => 'Russia',
						'country_pt' => 'Cazaquistão',
						'prefix'     => '7',
						'language'   => 'ru',
					),
			'RW' =>
					array(
						'iso3'       => 'RWA',
						'country'    => 'Rwanda',
						'country_pt' => 'Ruanda',
						'prefix'     => '250',
						'language'   => 'rw',
					),
			'SA' =>
					array(
						'iso3'       => 'SAU',
						'country'    => 'Saudi Arabia',
						'country_pt' => 'Arábia Saudita',
						'prefix'     => '966',
						'language'   => 'ar-SA',
					),
			'SB' =>
					array(
						'iso3'       => 'SLB',
						'country'    => 'Solomon Islands',
						'country_pt' => 'Ilhas Salomão',
						'prefix'     => '677',
						'language'   => 'en-SB',
					),
			'SC' =>
					array(
						'iso3'       => 'SYC',
						'country'    => 'Seychelles',
						'country_pt' => 'Seicheles',
						'prefix'     => '248',
						'language'   => 'en-SC',
					),
			'SD' =>
					array(
						'iso3'       => 'SDN',
						'country'    => 'Sudan',
						'country_pt' => 'Sudão',
						'prefix'     => '249',
						'language'   => 'ar-SD',
					),
			'SS' =>
					array(
						'iso3'       => 'SSD',
						'country'    => 'South Sudan',
						'country_pt' => 'South Sudan',
						'prefix'     => '211',
						'language'   => 'en',
					),
			'SE' =>
					array(
						'iso3'       => 'SWE',
						'country'    => 'Sweden',
						'country_pt' => 'Suécia',
						'prefix'     => '46',
						'language'   => 'sv-SE',
					),
			'SG' =>
					array(
						'iso3'       => 'SGP',
						'country'    => 'Singapore',
						'country_pt' => 'Singapura',
						'prefix'     => '65',
						'language'   => 'cmn',
					),
			'SH' =>
					array(
						'iso3'       => 'SHN',
						'country'    => 'Saint Helena',
						'country_pt' => 'Saint Helena, Tristan da Cunha',
						'prefix'     => '290',
						'language'   => 'en-SH',
					),
			'SI' =>
					array(
						'iso3'       => 'SVN',
						'country'    => 'Slovenia',
						'country_pt' => 'Eslovénia',
						'prefix'     => '386',
						'language'   => 'sl',
					),
			'SJ' =>
					array(
						'iso3'       => 'SJM',
						'country'    => 'Svalbard and Jan Mayen',
						'country_pt' => 'Svalbard and Jan Mayen',
						'prefix'     => '47',
						'language'   => 'no',
					),
			'SK' =>
					array(
						'iso3'       => 'SVK',
						'country'    => 'Slovakia',
						'country_pt' => 'Eslováquia',
						'prefix'     => '421',
						'language'   => 'sk',
					),
			'SL' =>
					array(
						'iso3'       => 'SLE',
						'country'    => 'Sierra Leone',
						'country_pt' => 'Serra Leoa',
						'prefix'     => '232',
						'language'   => 'en-SL',
					),
			'SM' =>
					array(
						'iso3'       => 'SMR',
						'country'    => 'San Marino',
						'country_pt' => 'São Marino',
						'prefix'     => '378',
						'language'   => 'it-SM',
					),
			'SN' =>
					array(
						'iso3'       => 'SEN',
						'country'    => 'Senegal',
						'country_pt' => 'Senegal',
						'prefix'     => '221',
						'language'   => 'fr-SN',
					),
			'SO' =>
					array(
						'iso3'       => 'SOM',
						'country'    => 'Somalia',
						'country_pt' => 'Somália',
						'prefix'     => '252',
						'language'   => 'so-SO',
					),
			'SR' =>
					array(
						'iso3'       => 'SUR',
						'country'    => 'Suriname',
						'country_pt' => 'Suriname',
						'prefix'     => '597',
						'language'   => 'nl-SR',
					),
			'ST' =>
					array(
						'iso3'       => 'STP',
						'country'    => 'Sao Tome and Principe',
						'country_pt' => 'São Tomé e Príncipe',
						'prefix'     => '239',
						'language'   => 'pt-ST',
					),
			'SV' =>
					array(
						'iso3'       => 'SLV',
						'country'    => 'El Salvador',
						'country_pt' => 'El Salvador',
						'prefix'     => '503',
						'language'   => 'es-SV',
					),
			'SX' =>
					array(
						'iso3'       => 'SXM',
						'country'    => 'Sint Maarten',
						'country_pt' => 'Sint Maarten',
						'prefix'     => '599',
						'language'   => 'nl',
					),
			'SY' =>
					array(
						'iso3'       => 'SYR',
						'country'    => 'Syria',
						'country_pt' => 'Síria',
						'prefix'     => '963',
						'language'   => 'ar-SY',
					),
			'SZ' =>
					array(
						'iso3'       => 'SWZ',
						'country'    => 'Swaziland',
						'country_pt' => 'Suazilândia',
						'prefix'     => '268',
						'language'   => 'en-SZ',
					),
			'TC' =>
					array(
						'iso3'       => 'TCA',
						'country'    => 'Turks and Caicos Islands',
						'country_pt' => 'Turks and Caicos Islands',
						'prefix'     => '1649',
						'language'   => 'en-TC',
					),
			'TD' =>
					array(
						'iso3'       => 'TCD',
						'country'    => 'Chad',
						'country_pt' => 'Chade',
						'prefix'     => '235',
						'language'   => 'fr-TD',
					),
			'TG' =>
					array(
						'iso3'       => 'TGO',
						'country'    => 'Togo',
						'country_pt' => 'Togo',
						'prefix'     => '228',
						'language'   => 'fr-TG',
					),
			'TH' =>
					array(
						'iso3'       => 'THA',
						'country'    => 'Thailand',
						'country_pt' => 'Tailândia',
						'prefix'     => '66',
						'language'   => 'th',
					),
			'TJ' =>
					array(
						'iso3'       => 'TJK',
						'country'    => 'Tajikistan',
						'country_pt' => 'Tajiquistão',
						'prefix'     => '992',
						'language'   => 'tg',
					),
			'TK' =>
					array(
						'iso3'       => 'TKL',
						'country'    => 'Tokelau',
						'country_pt' => 'Tokelau',
						'prefix'     => '690',
						'language'   => 'tkl',
					),
			'TL' =>
					array(
						'iso3'       => 'TLS',
						'country'    => 'Timor Leste',
						'country_pt' => 'Timor-Leste',
						'prefix'     => '670',
						'language'   => 'tet',
					),
			'TM' =>
					array(
						'iso3'       => 'TKM',
						'country'    => 'Turkmenistan',
						'country_pt' => 'Turquemenistão',
						'prefix'     => '993',
						'language'   => 'tk',
					),
			'TN' =>
					array(
						'iso3'       => 'TUN',
						'country'    => 'Tunisia',
						'country_pt' => 'Tunísia',
						'prefix'     => '216',
						'language'   => 'ar-TN',
					),
			'TO' =>
					array(
						'iso3'       => 'TON',
						'country'    => 'Tonga',
						'country_pt' => 'Tonga',
						'prefix'     => '676',
						'language'   => 'to',
					),
			'TR' =>
					array(
						'iso3'       => 'TUR',
						'country'    => 'Turkey',
						'country_pt' => 'Turquia',
						'prefix'     => '90',
						'language'   => 'tr-TR',
					),
			'TT' =>
					array(
						'iso3'       => 'TTO',
						'country'    => 'Trinidad and Tobago',
						'country_pt' => 'Trindade e Tobago',
						'prefix'     => '1868',
						'language'   => 'en-TT',
					),
			'TV' =>
					array(
						'iso3'       => 'TUV',
						'country'    => 'Tuvalu',
						'country_pt' => 'Tuvalu',
						'prefix'     => '688',
						'language'   => 'tvl',
					),
			'TW' =>
					array(
						'iso3'       => 'TWN',
						'country'    => 'Taiwan',
						'country_pt' => 'Taiwan',
						'prefix'     => '886',
						'language'   => 'zh-TW',
					),
			'TZ' =>
					array(
						'iso3'       => 'TZA',
						'country'    => 'Tanzania',
						'country_pt' => 'Tanzânia',
						'prefix'     => '255',
						'language'   => 'sw-TZ',
					),
			'UA' =>
					array(
						'iso3'       => 'UKR',
						'country'    => 'Ukraine',
						'country_pt' => 'Ucrânia',
						'prefix'     => '380',
						'language'   => 'uk',
					),
			'UG' =>
					array(
						'iso3'       => 'UGA',
						'country'    => 'Uganda',
						'country_pt' => 'Uganda',
						'prefix'     => '256',
						'language'   => 'en-UG',
					),
			'UM' =>
					array(
						'iso3'       => 'UMI',
						'country'    => 'United States Minor Outlying Islands',
						'country_pt' => 'United States Minor Outlying Islands',
						'prefix'     => '1',
						'language'   => 'en-UM',
					),
			'US' =>
					array(
						'iso3'       => 'USA',
						'country'    => 'United States',
						'country_pt' => 'Estados Unidos',
						'prefix'     => '1',
						'language'   => 'en-US',
					),
			'UY' =>
					array(
						'iso3'       => 'URY',
						'country'    => 'Uruguay',
						'country_pt' => 'Uruguai',
						'prefix'     => '598',
						'language'   => 'es-UY',
					),
			'UZ' =>
					array(
						'iso3'       => 'UZB',
						'country'    => 'Uzbekistan',
						'country_pt' => 'Usbequistão',
						'prefix'     => '998',
						'language'   => 'uz',
					),
			'VA' =>
					array(
						'iso3'       => 'VAT',
						'country'    => 'Vatican',
						'country_pt' => 'Itália',
						'prefix'     => '379',
						'language'   => 'la',
					),
			'VC' =>
					array(
						'iso3'       => 'VCT',
						'country'    => 'Saint Vincent and the Grenadines',
						'country_pt' => 'São Vicente e Granadinas',
						'prefix'     => '1784',
						'language'   => 'en-VC',
					),
			'VE' =>
					array(
						'iso3'       => 'VEN',
						'country'    => 'Venezuela',
						'country_pt' => 'Venezuela',
						'prefix'     => '58',
						'language'   => 'es-VE',
					),
			'VG' =>
					array(
						'iso3'       => 'VGB',
						'country'    => 'British Virgin Islands',
						'country_pt' => 'Ilhas Virgens Britânicas',
						'prefix'     => '1284',
						'language'   => 'en-VG',
					),
			'VI' =>
					array(
						'iso3'       => 'VIR',
						'country'    => 'U.S. Virgin Islands',
						'country_pt' => 'Ilhas Virgem Americas',
						'prefix'     => '1340',
						'language'   => 'en-VI',
					),
			'VN' =>
					array(
						'iso3'       => 'VNM',
						'country'    => 'Vietnam',
						'country_pt' => 'Vietname',
						'prefix'     => '84',
						'language'   => 'vi',
					),
			'VU' =>
					array(
						'iso3'       => 'VUT',
						'country'    => 'Vanuatu',
						'country_pt' => 'Vanuatu',
						'prefix'     => '678',
						'language'   => 'bi',
					),
			'WF' =>
					array(
						'iso3'       => 'WLF',
						'country'    => 'Wallis and Futuna',
						'country_pt' => 'Wallis and Futuna',
						'prefix'     => '681',
						'language'   => 'wls',
					),
			'WS' =>
					array(
						'iso3'       => 'WSM',
						'country'    => 'Samoa',
						'country_pt' => 'Samoa',
						'prefix'     => '685',
						'language'   => 'sm',
					),
			'YE' =>
					array(
						'iso3'       => 'YEM',
						'country'    => 'Yemen',
						'country_pt' => 'Iémen',
						'prefix'     => '967',
						'language'   => 'ar-YE',
					),
			'YT' =>
					array(
						'iso3'       => 'MYT',
						'country'    => 'Mayotte',
						'country_pt' => 'Mayotte',
						'prefix'     => '262',
						'language'   => 'fr-YT',
					),
			'ZA' =>
					array(
						'iso3'       => 'ZAF',
						'country'    => 'South Africa',
						'country_pt' => 'África do Sul',
						'prefix'     => '27',
						'language'   => 'zu',
					),
			'ZM' =>
					array(
						'iso3'       => 'ZMB',
						'country'    => 'Zambia',
						'country_pt' => 'Zâmbia',
						'prefix'     => '260',
						'language'   => 'en-ZM',
					),
			'ZW' =>
					array(
						'iso3'       => 'ZWE',
						'country'    => 'Zimbabwe',
						'country_pt' => 'Zimbabwe',
						'prefix'     => '263',
						'language'   => 'en-ZW',
					),
			'CS' =>
					array(
						'iso3'       => 'SCG',
						'country'    => 'Serbia and Montenegro',
						'country_pt' => 'Serbia and Montenegro',
						'prefix'     => '381',
						'language'   => 'cu',
					),
			'AN' =>
					array(
						'iso3'       => 'ANT',
						'country'    => 'Netherlands Antilles',
						'country_pt' => 'Bonaire, Saint Eustatius e Saba',
						'prefix'     => '599',
						'language'   => 'nl-AN',
					),
		)
	)
);

define('COUNTRY_CODES', EFWP_COUNTRY_CODES);//retro-compatibility

if(!defined('ALTERNATE_WP_CRON') ){
    define('ALTERNATE_WP_CRON', true);
}

add_action(
	'in_admin_header',
	function () {

		if ( strpos( get_current_screen()->id, 'smart-marketing-for-wp' ) == false ) {
			return false;
		}

		remove_all_actions( 'admin_notices' );
		remove_all_actions( 'all_admin_notices' );

	},
	1000
);

// INITIALIZE PLUGIN
function run_egoi_for_wp() {

	$plugin = new Egoi_For_Wp();
	$plugin->run();

}
run_egoi_for_wp();

// bloco shortcode gutenberg
require plugin_dir_path( __FILE__ ) . '/blocks/shortcode.php';
