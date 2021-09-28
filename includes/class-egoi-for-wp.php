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
	protected $version = EFWP_SELF_VERSION;

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
		'egoi_has_list',
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
	 * Rest Client
	 *
	 * @var string
	 */
	protected $restUrlv3 = 'https://api.egoiapp.com';

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
	const PAGE_SLUG      = 'egoi4wp-form-preview';
	const TAG_NEWSLETTER = 'wp_newsletter';
	const GUEST_BUY      = 'wp_guest_client';
	const ORDER_TNG_FLAG = 'egoi_order_tng_';
	const COUNTRY_DIAL   = array(
		'AF' => '93',
		'AL' => '355',
		'DZ' => '213',
		'AS' => '1-684',
		'AD' => '376',
		'AO' => '244',
		'AI' => '1-264',
		'AQ' => '672',
		'AG' => '1-268',
		'AR' => '54',
		'AM' => '374',
		'AW' => '297',
		'AU' => '61',
		'AT' => '43',
		'AZ' => '994',
		'BS' => '1-242',
		'BH' => '973',
		'BD' => '880',
		'BB' => '1-246',
		'BY' => '375',
		'BE' => '32',
		'BZ' => '501',
		'BJ' => '229',
		'BM' => '1-441',
		'BT' => '975',
		'BO' => '591',
		'BA' => '387',
		'BW' => '267',
		'BV' => '47',
		'BR' => '55',
		'IO' => '246',
		'VG' => '1-284',
		'BN' => '673',
		'BG' => '359',
		'BF' => '226',
		'BI' => '257',
		'KH' => '855',
		'CM' => '237',
		'CA' => '1',
		'CV' => '238',
		'BQ' => '599',
		'KY' => '1-345',
		'CF' => '236',
		'TD' => '235',
		'CL' => '56',
		'CN' => '86',
		'CX' => '61',
		'CC' => '61',
		'CO' => '57',
		'KM' => '269',
		'CG' => '242',
		'CD' => '243',
		'CK' => '682',
		'CR' => '506',
		'HR' => '385',
		'CU' => '53',
		'CW' => '599',
		'CY' => '357',
		'CZ' => '420',
		'CI' => '225',
		'DK' => '45',
		'DJ' => '253',
		'DM' => '1-767',
		'DO' => '1-809,1-829,1-849',
		'EC' => '593',
		'EG' => '20',
		'SV' => '503',
		'GQ' => '240',
		'ER' => '291',
		'EE' => '372',
		'ET' => '251',
		'FK' => '500',
		'FO' => '298',
		'FJ' => '679',
		'FI' => '358',
		'FR' => '33',
		'GF' => '594',
		'PF' => '689',
		'TF' => '262',
		'GA' => '241',
		'GM' => '220',
		'GE' => '995',
		'DE' => '49',
		'GH' => '233',
		'GI' => '350',
		'GR' => '30',
		'GL' => '299',
		'GD' => '1-473',
		'GP' => '590',
		'GU' => '1-671',
		'GT' => '502',
		'GG' => '44',
		'GN' => '224',
		'GW' => '245',
		'GY' => '592',
		'HT' => '509',
		'HM' => '672',
		'HN' => '504',
		'HK' => '852',
		'HU' => '36',
		'IS' => '354',
		'IN' => '91',
		'ID' => '62',
		'IR' => '98',
		'IQ' => '964',
		'IE' => '353',
		'IM' => '44',
		'IL' => '972',
		'IT' => '39',
		'JM' => '1-876',
		'JP' => '81',
		'JE' => '44',
		'JO' => '962',
		'KZ' => '7',
		'KE' => '254',
		'KI' => '686',
		'KW' => '965',
		'KG' => '996',
		'LA' => '856',
		'LV' => '371',
		'LB' => '961',
		'LS' => '266',
		'LR' => '231',
		'LY' => '218',
		'LI' => '423',
		'LT' => '370',
		'LU' => '352',
		'MO' => '853',
		'MK' => '389',
		'MG' => '261',
		'MW' => '265',
		'MY' => '60',
		'MV' => '960',
		'ML' => '223',
		'MT' => '356',
		'MH' => '692',
		'MQ' => '596',
		'MR' => '222',
		'MU' => '230',
		'YT' => '262',
		'MX' => '52',
		'FM' => '691',
		'MD' => '373',
		'MC' => '377',
		'MN' => '976',
		'ME' => '382',
		'MS' => '1-664',
		'MA' => '212',
		'MZ' => '258',
		'MM' => '95',
		'NA' => '264',
		'NR' => '674',
		'NP' => '977',
		'NL' => '31',
		'NC' => '687',
		'NZ' => '64',
		'NI' => '505',
		'NE' => '227',
		'NG' => '234',
		'NU' => '683',
		'NF' => '672',
		'KP' => '850',
		'MP' => '1-670',
		'NO' => '47',
		'OM' => '968',
		'PK' => '92',
		'PW' => '680',
		'PS' => '970',
		'PA' => '507',
		'PG' => '675',
		'PY' => '595',
		'PE' => '51',
		'PH' => '63',
		'PN' => '870',
		'PL' => '48',
		'PT' => '351',
		'PR' => '1',
		'QA' => '974',
		'RO' => '40',
		'RU' => '7',
		'RW' => '250',
		'RE' => '262',
		'WS' => '685',
		'SM' => '378',
		'SA' => '966',
		'SN' => '221',
		'RS' => '381 p',
		'SC' => '248',
		'SL' => '232',
		'SG' => '65',
		'SX' => '1-721',
		'SK' => '421',
		'SI' => '386',
		'SB' => '677',
		'SO' => '252',
		'ZA' => '27',
		'GS' => '500',
		'KR' => '82',
		'SS' => '211',
		'ES' => '34',
		'LK' => '94',
		'BL' => '590',
		'SH' => '290 n',
		'KN' => '1-869',
		'LC' => '1-758',
		'MF' => '590',
		'PM' => '508',
		'VC' => '1-784',
		'SD' => '249',
		'SR' => '597',
		'SJ' => '47',
		'SZ' => '268',
		'SE' => '46',
		'CH' => '41',
		'SY' => '963',
		'ST' => '239',
		'TW' => '886',
		'TJ' => '992',
		'TZ' => '255',
		'TH' => '66',
		'TL' => '670',
		'TG' => '228',
		'TK' => '690',
		'TO' => '676',
		'TT' => '1-868',
		'TN' => '216',
		'TR' => '90',
		'TM' => '993',
		'TC' => '1-649',
		'TV' => '688',
		'VI' => '1-340',
		'GB' => '44',
		'US' => '1',
		'UG' => '256',
		'UA' => '380',
		'AE' => '971',
		'UY' => '598',
		'UZ' => '998',
		'VU' => '678',
		'VA' => '39-06',
		'VE' => '58',
		'VN' => '84',
		'WF' => '681',
		'EH' => '212',
		'YE' => '967',
		'ZM' => '260',
		'ZW' => '263',
		'AX' => '358',
	);

	/**
	 * Constructor
	 *
	 * @param bool $debug
	 * @since 1.0.0
	 */
	public function __construct( $debug = false ) {

		$this->plugin_name = 'egoi-for-wp';
		$this->debug       = $debug;
		$this->host        = isset( $_SERVER['SERVER_NAME'] ) ? esc_url_raw( $_SERVER['SERVER_NAME'] ) : esc_url_raw( $_SERVER['HTTP_HOST'] );

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_listen_hooks();

		// Contact Form 7
		$this->getContactFormInfo();

		$this->getClientAPI();
		$this->syncronizeEgoi( $_POST );

		$this->setClient();

		$this->setTransactionEmailOption();

	}

	/**
	 * Set Transactiona Email option
	 */
	public function setTransactionEmailOption() {

		$transactionalEmailOptions = array(
			'from'                      => '', // Obter default from
			'fromId'                    => 0,
			'fromname'                  => '', // Obter default fromName
			'check_transactional_email' => 0,
			'mailer'                    => 'default',
		);

		add_option( 'egoi_transactional_email', $transactionalEmailOptions );

	}

	/**
	 * Set E-goi Client ID
	 *
	 * @since    1.1.2
	 */
	protected function setClient() {

		if ( ! is_admin() ) {
			if ( ! get_option( 'egoi_client' ) ) {
				add_option( 'egoi_client', $this->getClient() );
			}
		}
	}

	/**
	 * Remove all data from this plugin
	 *
	 * @param bool $rmOnlyMappedFields
	 * @param bool $returnContent
	 * @return bool
	 * @since    1.1.0
	 * @access   public
	 */
	public static function removeData( $rmOnlyMappedFields = false, $returnContent = false ) {

		try {
			global $wpdb;

			$egoi_options = self::$options;

			$all_options = wp_load_alloptions();

			// to get all options that are egoi simple forms
			foreach ( $all_options as $key => $value ) {
				if ( strpos( $key, 'egoi_simple_' ) !== false ) {
					$egoi_options[] = $key; // add to egoi options

					// to delete simple form on posts table in BD
					$post_id = str_replace( 'egoi_simple_form_', '', $key );
					wp_delete_post( $post_id );
				}
			}

			foreach ( $egoi_options as $opt ) {
				delete_option( $opt );
			}

			$wpdb->hide_errors();
			$table = $wpdb->prefix . 'egoi_map_fields';

			if ( $rmOnlyMappedFields ) {
				$sql = "TRUNCATE TABLE $table";
				$wpdb->query( $sql );
			} else {
				$sql = "DROP TABLE $table";
				$wpdb->query( $sql );
			}

			if ( $returnContent ) {
				echo wp_json_encode( array( 'result' => 'ok' ) );
				exit;
			}

			return true;

		} catch ( Exception $e ) {
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

		$plugin_admin = new Egoi_For_Wp_Admin( $this->get_plugin_name(), $this->get_version(), $this->debug );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		$this->define_apikey();

		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'del_action_link' );

		$this->loader->add_action( 'wp_ajax_smsnf_hide_notification', $plugin_admin, 'smsnf_hide_notification' );

		// Dashboard
		$this->loader->add_action( 'wp_ajax_smsnf_show_blog_posts', $plugin_admin, 'smsnf_show_blog_posts' );
		$this->loader->add_action( 'wp_ajax_smsnf_kill_alert', $plugin_admin, 'smsnf_kill_alert' );
		$this->loader->add_action( 'wp_ajax_smsnf_show_account_info_ajax', $plugin_admin, 'smsnf_show_account_info_ajax' );
		$this->loader->add_action( 'wp_ajax_smsnf_show_last_campaigns_reports', $plugin_admin, 'smsnf_show_last_campaigns_reports' );

		// Rss
		$this->loader->add_action( 'wp_ajax_egoi_deploy_rss', $plugin_admin, 'egoi_deploy_rss' );
		$this->loader->add_action( 'wp_ajax_egoi_deploy_rss_webpush', $plugin_admin, 'egoi_deploy_rss_webpush' );
		$this->loader->add_action( 'wp_ajax_egoi_rss_campaign_webpush', $plugin_admin, 'egoi_rss_campaign_webpush' );
		$this->loader->add_action( 'wp_ajax_egoi_rss_campaign', $plugin_admin, 'egoi_rss_campaign' );
		$this->loader->add_action( 'wp_ajax_egoi_get_email_senders', $plugin_admin, 'egoi_get_email_senders' );

		// E-commerce
		$this->loader->add_action( 'wp_ajax_egoi_sync_catalog', $plugin_admin, 'egoi_sync_catalog' );
		$this->loader->add_action( 'wp_ajax_egoi_force_import_catalog', $plugin_admin, 'egoi_force_import_catalog' );
		$this->loader->add_action( 'wp_ajax_egoi_delete_catalog', $plugin_admin, 'egoi_delete_catalog' );
		$this->loader->add_action( 'wp_ajax_egoi_catalog_utilities', $plugin_admin, 'egoi_catalog_utilities' );
		$this->loader->add_action( 'wp_ajax_egoi_count_products', $plugin_admin, 'egoi_count_products' );
		$this->loader->add_action( 'transition_post_status', $plugin_admin, 'egoi_product_check_delete', 10, 3 );
		$this->loader->add_action( 'woocommerce_update_product', $plugin_admin, 'egoi_product_creation', 10 );

		$this->loader->add_action( 'woocommerce_product_import_before_import', $plugin_admin, 'egoi_import_bypass', 10, 1 );

		// Newsletter
		$this->loader->add_action( 'show_user_profile', $plugin_admin, 'egoi_add_newsletter_signup_admin', 10 );
		// user information
		$this->loader->add_filter( 'manage_users_columns', $plugin_admin, 'egoi_add_extra_user_info', 10 );
		$this->loader->add_filter( 'manage_users_custom_column', $plugin_admin, 'egoi_add_extra_user_info_row', 1, 3 );

		$this->loader->add_action( 'gform_after_submission', $plugin_admin, 'egoi_gform_add_subscriber', 10, 2 );

		// Mapping Ajax
		$this->loader->add_action( 'wp_ajax_egoi_get_mapping_n_fields', $plugin_admin, 'egoi_get_mapping_n_fields' );

		// handle transactional email
			$activate_mailer = get_option( 'egoi_transactional_email' );
		if ( $activate_mailer['check_transactional_email'] ) {
			$this->loader->add_filter( 'wp_mail_from', $plugin_admin, 'egoi_mail_from' );
			$this->loader->add_filter( 'wp_mail_from_name', $plugin_admin, 'egoi_mail_from_name' );
			$this->loader->add_action( 'phpmailer_init', $plugin_admin, 'egoi_phpmailer_init' );

			// Redifine PHPMailer
			$this->loader->add_action( 'plugins_loaded', $plugin_admin, 'replace_phpmailer' );
		}
		// Campaign widget metaboxes
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'email_campaign_widget_meta_box_admin' );

		// Campaign widget on save and on status transition
		$this->loader->add_action( 'save_post', $plugin_admin, 'on_save_post_admin', 10, 3 );
		$this->loader->add_action( 'publish_post', $plugin_admin, 'send_campaign_admin', 10, 1 );
		$this->loader->add_action( 'transition_post_status', $plugin_admin, 'on_transition_post_status_admin', 10, 3 );
	}

	/**
	 * Register API Key on runtime
	 *
	 * @since 1.0.13
	 */
	public function define_apikey() {

		$apikey = get_option( 'egoi_api_key' );
		if ( isset( $apikey['api_key'] ) && ( $apikey['api_key'] ) ) {
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

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// handle bar
		$bar_post = get_option( Egoi_For_Wp_Admin::BAR_OPTION_NAME );
		if ( isset( $bar_post['enabled'] ) && ( $bar_post['enabled'] ) ) {
			if ( $bar_post['position'] == 'top' ) {
				$this->loader->add_filter( 'wp_head', $plugin_public, 'get_bar' );
			} else {
				$this->loader->add_filter( 'wp_footer', $plugin_public, 'get_bar' );
			}
			$this->loader->add_action( 'admin_post_bar_handler', $plugin_public, 'bar_handler' );
		}

		// handle form
		if ( isset( $_GET['form_id'] ) ) {
			$this->loader->add_filter( 'the_content', $plugin_public, 'get_html' );
		}

		add_shortcode( 'egoi_form_sync_1', array( $plugin_public, 'shortcode_default' ) );
		add_shortcode( 'egoi_form_sync_2', array( $plugin_public, 'shortcode_second' ) );
		add_shortcode( 'egoi_form_sync_3', array( $plugin_public, 'shortcode_last1' ) );
		add_shortcode( 'egoi_form_sync_4', array( $plugin_public, 'shortcode_last2' ) );
		add_shortcode( 'egoi_form_sync_5', array( $plugin_public, 'shortcode_last3' ) );

		$this->loader->add_action( 'admin_post_form_handler', $plugin_public, 'form_handler' );

		$this->loader->add_action( 'wp_ajax_smsnf_save_advanced_form_subscriber', $plugin_public, 'smsnf_save_advanced_form_subscriber' );
		$this->loader->add_action( 'wp_ajax_egoi_simple_form_submit', $plugin_public, 'efwp_process_simple_form_add' );
		$this->loader->add_action( 'wp_ajax_nopriv_egoi_simple_form_submit', $plugin_public, 'efwp_process_simple_form_add' );

		// Newsletter
		$this->loader->add_action( 'woocommerce_register_form', $plugin_public, 'egoi_add_newsletter_signup', 10 );
		$this->loader->add_action( 'woocommerce_edit_account_form', $plugin_public, 'egoi_add_newsletter_signup', 10 );

		$option = Egoi_For_Wp_Admin::get_option();
		if ( ! empty( $option['sub_button_position'] ) ) {
			$this->loader->add_action( $option['sub_button_position'], $plugin_public, 'egoi_add_newsletter_signup_hide', 10 );
		} else {
			$this->loader->add_action( 'woocommerce_after_order_notes', $plugin_public, 'egoi_add_newsletter_signup_hide', 10 );
		}

		// save Newsletter
		$this->loader->add_action( 'woocommerce_created_customer', $plugin_public, 'egoi_save_account_fields', 10 );
		$this->loader->add_action( 'personal_options_update', $plugin_public, 'egoi_save_account_fields', 10 );
		$this->loader->add_action( 'woocommerce_save_account_details', $plugin_public, 'egoi_save_account_fields', 10 );
		$this->loader->add_action( 'woocommerce_new_order', $plugin_public, 'egoi_save_account_fields_order', 10, 1 );

		// Tracking&Engage
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'hookEcommerce', 99999 );
		$this->loader->add_action( 'woocommerce_new_order', $plugin_public, 'hookEcommerceSetOrder', 99999, 1 );
		$this->loader->add_action( 'woocommerce_thankyou', $plugin_public, 'hookEcommerceGetOrder', 99999, 1 );

		if ( empty( $_POST ) ) {
			$this->loader->add_action( 'wp_head', $plugin_public, 'loadPopups', 99999, 1 );
		}

	}

	/**
	 * Register Profile Hooks
	 *
	 * @since   1.0.5
	 */
	public function define_listen_hooks() {

		$this->loader->add_action( 'user_register', $this, 'get_listener', 999 );
		$this->loader->add_action( 'profile_update', $this, 'get_user_update', 999 );

		$this->loader->add_action( 'delete_user', $this, 'get_user_del' );

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
	 */
	private function init_form() {

		$form_id    = (int) $_GET['form_id'];
		$is_preview = isset( $_GET['preview'] );

		$instance = new self( $form_id, $is_preview );
	}

	/**
	 * @param bool $apikey
	 * @return mixed
	 */
	public function getClient( $apikey = false ) {

		$url = $this->restUrl . 'getClientData&' . http_build_query(
			array(
				'functionOptions' => array(
					'apikey'     => $apikey ?: $this->_valid['api_key'],
					'plugin_key' => $this->plugin,
				),
			),
			'',
			'&'
		);

		$result_client = json_decode( $this->_getContent( $url ) );
		if ( $result_client->Egoi_Api->getClientData->status == 'success' ) {
			return $result_client->Egoi_Api->getClientData;
		}
	}

	/**
	 * @param bool $start
	 * @param bool $limit
	 * @param bool $list_id
	 * @return mixed
	 */
	public function getLists( $start = false, $limit = false, $list_id = false ) {
		$params = array(
			'apikey'     => $this->_valid['api_key'],
			'plugin_key' => $this->plugin,
		);
		if ( $list_id ) {
			$params['listID'] = $list_id;
		}
		$url = $this->restUrl . 'getLists&' . http_build_query(
			array(
				'functionOptions' => $params,
			),
			'',
			'&'
		);

		$result_client = json_decode( $this->_getContent( $url ) );
		return $result_client->Egoi_Api->getLists;
	}

	/**
	 * @param $name
	 * @param $lang
	 * @return mixed
	 */
	public function createList( $name, $lang ) {

		$url = $this->restUrl . 'createList&' . http_build_query(
			array(
				'functionOptions' => array(
					'apikey'       => $this->_valid['api_key'],
					'plugin_key'   => $this->plugin,
					'nome'         => $name,
					'idioma_lista' => $lang,
				),
			),
			'',
			'&'
		);

		$result_client = json_decode( $this->_getContent( $url ) );
		$created_list  = $result_client->Egoi_Api->createList;

		if ( $created_list->ERROR ) {
			return $created_list->ERROR;
		}

		return $created_list;
	}

	/**
	 * Check if a tag exists, if not creates, returns id
	 *
	 * @param string $tag
	 * @return int $tag_id
	 * @throws Exception
	 */
	public function createTagVerified( $tag ) {
		$url = $this->restUrl . 'getTags&' . http_build_query(
			array(
				'functionOptions' => array(
					'apikey'     => $this->_valid['api_key'],
					'plugin_key' => $this->plugin,
				),
			),
			'',
			'&'
		);

		$result_tags = json_decode( $this->_getContent( $url ), true );
		if ( empty( $result_tags['Egoi_Api']['getTags']['TAG_LIST'] ) || ! is_array( $result_tags['Egoi_Api']['getTags']['TAG_LIST'] ) ) {
			return 0;
		}

		foreach ( $result_tags['Egoi_Api']['getTags']['TAG_LIST'] as $tag_resp ) {
			if ( strcasecmp( $tag_resp['NAME'], $tag ) == 0 ) {
				return $tag_resp['ID'];
			}
		}

		$tag = $this->addTag( $tag );
		if ( empty( $tag->ID ) ) {
			return 0;
		}

		return $tag->ID;
	}

	/**
	 * @param $listID
	 * @param $array
	 * @param bool   $tag
	 * @param int    $operation
	 * @return mixed
	 * @throws Exception
	 */
	public function addSubscriberArray( $listID, $array, $tag = false, $operation = 1 ) {

		if ( is_string( $tag ) ) {
			$tag = $this->createTagVerified( $tag );
		}

		$url = $this->restUrl . 'addSubscriberBulk&' . http_build_query(
			array(
				'functionOptions' => array(
					'apikey'       => $this->_valid['api_key'],
					'plugin_key'   => $this->plugin,
					'compareField' => 'email',
					'listID'       => $listID,
					'operation'    => $operation,
					'tags'         => is_array( $tag ) ? $tag : array( $tag ),
					'status'       => 1,
					'subscribers'  => array( $array ),
				),
			),
			'',
			'&'
		);

		$result_client = json_decode( $this->_getContent( $url ) );
		if ( $result_client->Egoi_Api->addSubscriber->status == 'success' ) {
			return $result_client->Egoi_Api->addSubscriber;
		}
	}

	/**
	 * @param $listID
	 * @param string $name
	 * @param $email
	 * @param string $lang
	 * @param bool   $status
	 * @param string $mobile
	 * @param bool   $tag
	 * @param string $phone
	 * @return mixed
	 * @throws Exception
	 */
	public function addSubscriber( $listID, $name = '', $email, $lang = '', $status = false, $mobile = '', $tag = false, $phone = '' ) {

		$full_name = explode( ' ', $name );
		$fname     = $full_name[0];
		$lname     = $full_name[1];

		if ( $status === false ) {
			$status = 1;
		}
		if ( $tag ) {
			if ( is_string( $tag ) ) {
				$tag = $this->createTagVerified( $tag );
			}
			$url = $this->restUrl . 'addSubscriber&' . http_build_query(
				array(
					'functionOptions' => array(
						'apikey'     => $this->_valid['api_key'],
						'plugin_key' => $this->plugin,
						'listID'     => $listID,
						'lang'       => $lang,
						'first_name' => $fname,
						'last_name'  => $lname,
						'email'      => $email,
						'cellphone'  => $mobile,
						'telephone'  => $phone,
						'status'     => $status,
						'tags'       => is_array( $tag ) ? $tag : array( $tag ),
					),
				),
				'',
				'&'
			);
		} else {
			$url = $this->restUrl . 'addSubscriber&' . http_build_query(
				array(
					'functionOptions' => array(
						'apikey'     => $this->_valid['api_key'],
						'plugin_key' => $this->plugin,
						'listID'     => $listID,
						'lang'       => $lang,
						'first_name' => $fname,
						'last_name'  => $lname,
						'email'      => $email,
						'cellphone'  => $mobile,
						'telephone'  => $phone,
						'status'     => $status,
					),
				),
				'',
				'&'
			);
		}

		$result_client = json_decode( $this->_getContent( $url ) );
		if ( $result_client->Egoi_Api->addSubscriber->status == 'success' ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/includes/TrackingEngageSDK.php';
			TrackingEngageSDK::setUidSession( $result_client->Egoi_Api->addSubscriber->UID );
			return $result_client->Egoi_Api->addSubscriber;
		}
	}

	/**
	 * @param $listID
	 * @param $tag
	 * @param $subscriber
	 * @return mixed
	 */
	public function addSubscriberSoap( $listID, $tag, $subscriber ) {
		try {
			$api    = new SoapClient( $this->url );
			$params = array_merge(
				array(
					'apikey'       => $this->_valid['api_key'],
					'plugin_key'   => $this->plugin,
					'listID'       => $listID,
					'compareField' => 'email',
					'operation'    => '2',
					'tags'         => is_array( $tag ) ? $tag : array( $tag ),
				),
				$subscriber
			);
			$result = $api->addSubscriber( $params );
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/includes/TrackingEngageSDK.php';
			TrackingEngageSDK::setUidSession( $result );
		} catch ( Exception $e ) {
			// continue
		}

		return $result;
	}

	/**
	 * @param $listID
	 * @param $tag
	 * @param array  $subscribers
	 * @return mixed
	 */
	public function addSubscriberBulk( $listID, $tag, $subscribers = array() ) {
		try {
			if ( count( $subscribers ) == 1 ) {
				return $this->addSubscriberSoap( $listID, $tag, array_shift( array_values( $subscribers ) ) ); }
			$api    = new SoapClient( $this->url );
			$params = array(
				'apikey'       => $this->_valid['api_key'],
				'plugin_key'   => $this->plugin,
				'listID'       => $listID,
				'subscribers'  => $subscribers,
				'compareField' => 'email',
				'operation'    => '2',
				'tags'         => is_array( $tag ) ? $tag : array( $tag ),
			);
			$result = $api->addSubscriberBulk( $params );
		} catch ( Exception $e ) {
			// continue
		}

		return $result;
	}

	/**
	 * @param $listID
	 * @param $email
	 * @param array  $tags
	 * @param string $name
	 * @param string $lname
	 * @param int    $role
	 * @param array  $extra_fields
	 * @param int    $option
	 * @param array  $ref_fields
	 * @param int    $status
	 * @return mixed
	 */
	public function addSubscriberTags( $listID, $email, $tags = array(), $name = '', $lname = '', $role = 0, $extra_fields = array(), $option = 0, $ref_fields = array(), $status = 1
	) {
		$full_name = explode( ' ', $name );
		$fname     = $full_name[0];
		if ( ! $lname ) {
			$lname = $full_name[1];
		}

		$tel  = $ref_fields['tel'];
		$cell = $ref_fields['cell'];
		$bd   = $ref_fields['bd'];
		$fax  = $ref_fields['fax'];
		$lang = $ref_fields['lang'];

		$params = array(
			'apikey'     => $this->_valid['api_key'],
			'plugin_key' => $this->plugin,
			'listID'     => $listID,
			'email'      => $email,
			'first_name' => $fname,
			'last_name'  => $lname,
			'tags'       => $tags,
			'lang'       => $lang ?: null,
			'status'     => $status,
		);

		// telephone
		if ( $tel ) {
			$params['telephone'] = $tel;
		}
		// cellphone
		if ( $cell ) {
			$params['cellphone'] = $cell;
		}
		// birthdate
		if ( $bd ) {
			$params['birth_date'] = $bd;
		}
		// fax
		if ( $fax ) {
			$params['fax'] = $fax;
		}

		if ( $option ) {
			$all_extra_fields = get_object_vars( $this->getExtraFields( $listID ) );
			if ( $all_extra_fields ) {

				foreach ( $extra_fields as $key => $value ) {
					$filtered_key = str_replace( array( 'key_', 'extra_' ), '', $key );
					if ( array_key_exists( 'key_' . $filtered_key, $all_extra_fields ) ) {
						$params[ 'extra_' . $filtered_key ] = $value;
					}
				}
			}
		}

		$url           = $this->restUrl . 'addSubscriber&' . http_build_query( array( 'functionOptions' => $params ), '', '&' );
		$result_client = json_decode( $this->_getContent( $url ) );

		return $result_client->Egoi_Api->addSubscriber;
	}

	/**
	 * @param $list_id
	 * @param $subscriber
	 * @return mixed
	 */
	public function addSubscriberWpForm( $list_id, $subscriber ) {
		$params = array_merge(
			array(
				'apikey'     => $this->_valid['api_key'],
				'plugin_key' => $this->plugin,
				'listID'     => $list_id,
			),
			$subscriber
		);

		$url = $this->restUrl . 'addSubscriber&' . http_build_query(
			array(
				'functionOptions' => $params,
			),
			'',
			'&'
		);

		$result_client = json_decode( $this->_getContent( $url ) );
		if ( ! empty( $result_client->Egoi_Api->addSubscriber->UID ) ) {
			require_once plugin_dir_path( __FILE__ ) . '../public/includes/TrackingEngageSDK.php';
			TrackingEngageSDK::setUidSession( $result_client->Egoi_Api->addSubscriber->UID );
		}
		return $result_client->Egoi_Api->addSubscriber;
	}

	/**
	 * @param $listID
	 * @param $subscriber
	 * @param int        $role
	 * @param string     $fname
	 * @param string     $lname
	 * @param array      $fields
	 * @param int        $option
	 * @param array      $ref_fields
	 * @param array      $tags
	 * @return mixed
	 * @throws Exception
	 */
	public function editSubscriber( $listID, $subscriber, $role = 0, $fname = '', $lname = '', $fields = array(), $option = 0, $ref_fields = array(), $tags = array() ) {

		$apikey     = $this->_valid['api_key'];
		$plugin_key = $this->plugin;

		$telephone  = $ref_fields['tel'];
		$cellphone  = $ref_fields['cell'];
		$birth_date = $ref_fields['bd'];
		$fax        = $ref_fields['fax'];
		$lang       = $ref_fields['lang'];

		$params         = compact(
			'apikey',
			'plugin_key',
			'listID',
			'subscriber',
			'telephone',
			'cellphone',
			'birth_date',
			'fax',
			'lang',
			'tags'
		);
		$params['tags'] = $tags;
		// role
		if ( $role ) {
			$id               = $this->createTagVerified( $role );
			$params['tags'][] = $id;
		}
		// first name
		if ( $fname ) {
			$params['first_name'] = $fname;
		}
		// last name
		if ( $lname ) {
			$params['last_name'] = $lname;
		}

		if ( $option ) {
			foreach ( $fields as $key => $value ) {
				$params[ str_replace( 'key_', 'extra_', $key ) ] = $value;
			}
		}

		$url           = $this->restUrl . 'editSubscriber&' . http_build_query( array( 'functionOptions' => $params ), '', '&' );
		$result_client = json_decode( $this->_getContent( $url ) );

		return $result_client->Egoi_Api->editSubscriber;
	}

	/**
	 * @param $listID
	 * @param $email
	 * @return mixed
	 */
	public function delSubscriber( $listID, $email ) {

		$url = $this->restUrl . 'removeSubscriber&' . http_build_query(
			array(
				'functionOptions' => array(
					'apikey'       => $this->_valid['api_key'],
					'plugin_key'   => $this->plugin,
					'listID'       => $listID,
					'subscriber'   => $email,
					'removeMethod' => 'API',
				),
			),
			'',
			'&'
		);

		$result_client = json_decode( $this->_getContent( $url ) );
		return $result_client->Egoi_Api->removeSubscriber;
	}

	/**
	 * @param $listID
	 * @param int    $start
	 * @return mixed
	 */
	public function getAllSubscribers( $listID, $start = 0 ) {

		$url = $this->restUrl . 'subscriberData&' . http_build_query(
			array(
				'functionOptions' => array(
					'apikey'     => $this->_valid['api_key'],
					'plugin_key' => $this->plugin,
					'listID'     => $listID,
					'subscriber' => 'all_subscribers',
					'limit'      => 1000,
					'start'      => $start,
				),
			),
			'',
			'&'
		);

		$result_client = json_decode( $this->_getContent( $url ) );
		if ( $result_client->Egoi_Api->subscriberData->status == 'success' ) {
			return $result_client->Egoi_Api->subscriberData;
		}
	}

	/**
	 * @param $listID
	 * @param $email
	 * @return mixed
	 */
	public function getSubscriber( $listID, $email ) {

		$url = $this->restUrl . 'subscriberData&' . http_build_query(
			array(
				'functionOptions' => array(
					'apikey'     => $this->_valid['api_key'],
					'plugin_key' => $this->plugin,
					'listID'     => $listID,
					'subscriber' => $email,
				),
			),
			'',
			'&'
		);

		$result_client = json_decode( $this->_getContent( $url ) );
		if ( $result_client->Egoi_Api->subscriberData->status == 'success' ) {
			return $result_client->Egoi_Api->subscriberData;
		}
	}

	/**
	 * @param $listID
	 * @param $id
	 * @return mixed
	 */
	public function getSubscriberById( $listID, $id ) {

		$url = $this->restUrl . 'subscriberData&' . http_build_query(
			array(
				'functionOptions' => array(
					'apikey'     => $this->_valid['api_key'],
					'plugin_key' => $this->plugin,
					'listID'     => $listID,
					'subscriber' => $id,
				),
			),
			'',
			'&'
		);

		$result_client = json_decode( $this->_getContent( $url ) );
		if ( $result_client->Egoi_Api->subscriberData->status == 'success' ) {
			return $result_client->Egoi_Api->subscriberData->subscriber;
		}
	}

	/**
	 * @param bool $listID
	 * @param bool $option
	 * @return mixed
	 */
	public function getForms( $listID = false, $option = false ) {

		$url = $this->restUrl . 'getForms&' . http_build_query(
			array(
				'functionOptions' => array(
					'apikey'     => $this->_valid['api_key'],
					'plugin_key' => $this->plugin,
					'listID'     => $listID,
				),
			),
			'',
			'&'
		);

		$result_client = json_decode( $this->_getContent( $url ) );
		$forms         = $result_client->Egoi_Api->getForms;
		return $forms;
	}

	/**
	 * @param $listID
	 * @param null   $plugin_key
	 * @return string
	 */
	public function getExtraFields( $listID, $plugin_key = null ) {

		$url = $this->restUrl . 'getExtraFields&' . http_build_query(
			array(
				'functionOptions' => array(
					'apikey'     => $this->_valid['api_key'],
					'plugin_key' => empty( $plugin_key ) ? $this->plugin : $plugin_key,
					'listID'     => $listID,
				),
			),
			'',
			'&'
		);

		$result_client = json_decode( $this->_getContent( $url ) );
		$extra_fields  = $result_client->Egoi_Api->getExtraFields->extra_fields;
		if ( $extra_fields == '0' ) {
			return '';
		}
		return $extra_fields;
	}

	/**
	 * @param bool $name
	 * @param bool $field
	 * @return mixed
	 */
	public function getFieldMap( $name = false, $field = false ) {

		global $wpdb;

		$table = $wpdb->prefix . 'egoi_map_fields';
		if ( $field ) {
			$sql = "SELECT * FROM $table WHERE wp='$field'";
		} else {
			$sql = "SELECT * FROM $table WHERE egoi='$name'";
		}
		$rows = $wpdb->get_results( $sql );

		return $rows[0]->egoi;
	}

	/**
	 * @return mixed
	 */
	public function getMappedFields() {

		global $wpdb;

		$table = $wpdb->prefix . 'egoi_map_fields';
		$sql   = "SELECT * FROM $table order by id DESC";
		$rows  = $wpdb->get_results( $sql );

		return $rows;
	}

	/**
	 * @param $listID
	 * @return int
	 */
	private function getEgoiSubscribers( $listID ) {

		$count  = 0;
		$result = $this->getLists();
		foreach ( $result as $key => $value ) {
			if ( $value->listnum == $listID ) {
				$count = $value->subs_activos;
			}
		}

		return $count;
	}

	/**
	 * @param array $post
	 */
	public function syncronizeEgoi( $post = array() ) {

		if ( ! empty( $post ) ) {
			if ( isset( $post['action'] ) && ( $post['action'] == 'synchronize' ) ) {
				$all_subscribers = $this->getEgoiSubscribers( $post['list'] );

				$users       = count_users();
				$total_users = '';
				foreach ( $users['avail_roles'] as $role => $count ) {
					if ( $role == $post['role'] ) {
						$total_users .= $count;
					}
				}

				if ( $post['role'] == '' ) {
					global $wpdb;
					$table       = $wpdb->prefix . 'users';
					$sql         = "SELECT COUNT(*) AS COUNT FROM $table";
					$row         = $wpdb->get_row( $sql );
					$total_users = $row->COUNT;
				}

				$total_users = ( $total_users - 1 );
				$total[]     = $all_subscribers;
				$total[]     = $total_users;

				$this->addTrackEngage( $post['list'] );

				echo wp_json_encode( $total );
				exit;
			}
		}
	}

	/**
	 * @param $name
	 * @return mixed
	 */
	public function addTag( $name ) {

		$url = $this->restUrl . 'addTag&' . http_build_query(
			array(
				'functionOptions' => array(
					'apikey'     => $this->_valid['api_key'],
					'plugin_key' => $this->plugin,
					'name'       => sanitize_text_field( $name ),
				),
			),
			'',
			'&'
		);

		$result_client = json_decode( $this->_getContent( $url ) );

		return $result_client->Egoi_Api->addTag;
	}

	/**
	 * @param $name
	 * @return mixed
	 */
	public function getTag( $name ) {

		$url = $this->restUrl . 'getTags&' . http_build_query(
			array(
				'functionOptions' => array(
					'apikey'     => $this->_valid['api_key'],
					'plugin_key' => $this->plugin,
				),
			),
			'',
			'&'
		);

		$result_client = json_decode( $this->_getContent( $url ) );
		$result        = $result_client->Egoi_Api->getTags->TAG_LIST;

		foreach ( $result as $key => $value ) {
			if ( strcasecmp( $value->NAME, $name ) == 0 ) {
				$data['NAME'] = $value->NAME;
				$data['ID']   = $value->ID;
			}
		}

		if ( empty( $data ) ) {
			$rest             = $this->addTag( $name );
			$data['NEW_ID']   = $rest->ID;
			$data['NEW_NAME'] = $rest->NAME;
		}

		return $data;
	}

	/**
	 * @return mixed
	 */
	public function getTags() {

		$url = $this->restUrl . 'getTags&' . http_build_query(
			array(
				'functionOptions' => array(
					'apikey'     => $this->_valid['api_key'],
					'plugin_key' => $this->plugin,
				),
			),
			'',
			'&'
		);

		$result_client = json_decode( $this->_getContent( $url ) );
		$result        = $result_client->Egoi_Api->getTags;

		return $result;
	}

	/**
	 * @param $data
	 * @param $apikey
	 * @param bool   $option
	 * @return string
	 */
	private function callClient( $data, $apikey, $option = false ) {

		return $this->checkUser( $data, $apikey, $option );
	}

	/**
	 * @param $url
	 * @param array $headers
	 * @return string
	 */
	protected function _getContent( $url, $headers = array() ) {

		$res = wp_remote_request(
			$url,
			array(
				'method'  => 'GET',
				'timeout' => 30,
				'headers' => $headers,
			)
		);

		return is_wp_error( $res ) ? '{}' : $res['body'];
	}

	/**
	 * @param $url
	 * @param $rows
	 * @param bool $option
	 * @return string
	 */
	private function _postContent( $url, $rows, $option = false ) {

		$url            = str_replace( 'service', 'post', $url );
		$rows['option'] = $option;

		$res = wp_remote_request(
			$url,
			array(
				'method'  => 'POST',
				'timeout' => 30,
				'body'    => $rows,
				'headers' => array(),
			)
		);

		return is_wp_error( $res ) ? '{}' : $res['body'];
	}

	public function get_listener( $user_id ) {

		$listen = new Egoi_For_Wp_Listener( $this->get_plugin_name(), $this->get_version() );
		if ( $user_id ) {
			$listen->init( $user_id );
		}

	}

	/**
	 * @param $user_id
	 */
	public function get_user_update( $user_id ) {

		$listen = new Egoi_For_Wp_Listener( $this->get_plugin_name(), $this->get_version() );
		if ( $user_id ) {
			$listen->init( $user_id );
		}

	}

	/**
	 * @param $user_id
	 */
	public function get_user_del( $user_id ) {

		$listen = new Egoi_For_Wp_Listener( $this->get_plugin_name(), $this->get_version() );
		if ( $user_id ) {
			$listen->Listen_delete( $user_id );
		}

	}

	/**
	 * @param $option_name
	 * @return string
	 */
	protected function name_post( $option_name ) {

		if ( substr( $option_name, -1 ) !== ']' ) {
			return Egoi_For_Wp_Admin::OPTION_NAME . '[' . $option_name . ']';
		}

		return Egoi_For_Wp_Admin::OPTION_NAME . $option_name;
	}

	/**
	 * @param $option_name
	 * @return string
	 */
	protected function bar_post( $option_name ) {

		if ( substr( $option_name, -1 ) !== ']' ) {
			return Egoi_For_Wp_Admin::BAR_OPTION_NAME . '[' . $option_name . ']';
		}

		return Egoi_For_Wp_Admin::BAR_OPTION_NAME . $option_name;
	}

	/**
	 * @param $option_name
	 * @return string
	 */
	protected function form_post( $option_name ) {

		if ( substr( $option_name, -1 ) !== ']' ) {
			return Egoi_For_Wp_Admin::FORM_OPTION . '[' . $option_name . ']';
		}

		return Egoi_For_Wp_Admin::FORM_OPTION . $option_name;
	}


	/**
	 * @param $id
	 * @return mixed
	 */
	public function get_preview_page( $id ) {

		$page = get_page_by_path( self::PAGE_SLUG );

		if ( $page instanceof WP_Post && in_array( $page->post_status, array( 'draft', 'publish' ) ) ) {
			$page_id = $page->ID;

		} else {
			$page_id = wp_insert_post(
				array(
					'post_name'    => self::PAGE_SLUG,
					'post_type'    => 'page',
					'post_status'  => 'draft',
					'post_title'   => 'E-goi for WordPress: Form Preview',
					'post_content' => '[egoi_form_sync_' . $id . ']',
				)
			);
		}

		return $page_id;
	}

	/**
	 * @param $element
	 * @param $id
	 * @return mixed
	 */
	public function redirect_public( $element, $id ) {

		$content = $element;
		switch ( $id ) {
			case '1':
				$FORM_OPTION = Egoi_For_Wp_Admin::FORM_OPTION_1;
			case '2':
				$FORM_OPTION = Egoi_For_Wp_Admin::FORM_OPTION_2;
			case '3':
				$FORM_OPTION = Egoi_For_Wp_Admin::FORM_OPTION_3;
		}
		$form_post_array = get_option( $FORM_OPTION, array() );

		$base_url    = get_permalink( $this->get_preview_page( $id ) );
		$args        = array(
			'form_id' => $form_post_array['form_id'],
		);
		$preview_url = add_query_arg( $args, $base_url );

		return $preview_url;
	}

	/**
	 * @param $data_params
	 * @param $apikey
	 * @param bool        $option
	 * @return string
	 */
	private function checkUser( $data_params, $apikey, $option = false ) {

		try {

			require_once ABSPATH . '/wp-includes/pluggable.php';
			$email      = wp_get_current_user();
			$user_email = $email->data->user_email;

			$params = array(
				'apikey'   => $apikey,
				'email'    => $user_email,
				'smegoi_c' => $data_params->CLIENTE_ID,
				'smegoi_i' => '',
				'smegoi_v' => 'Wordpress_' . $this->version,
				'smegoi_h' => $this->host,
				'smegoi_e' => get_locale(),
			);

			require 'service/post_wsdl.php';
			if ( class_exists( 'SoapClient' ) ) {
				$response = new SoapClient( null, $options );
				$response->call( $params, $option );
			} else {
				$response = $this->_postContent( $options['location'], $params, $option );
			}
		} catch ( Exception $e ) {
			// continue
		}

		return '';
	}


	/**
	 * @param bool $form_id
	 * @return mixed
	 */
	public function getContactFormInfo( $form_id = false ) {

		global $wpdb;
		$table = $wpdb->prefix . 'posts';

		if ( isset( $form_id ) && ( $form_id ) ) {
			$sql = "SELECT post_title FROM $table Where ID='" . (int) $form_id . "'";
		} else {
			$sql = "SELECT ID, post_title FROM $table Where post_type='wpcf7_contact_form'";
		}

		$count = $wpdb->get_results( $sql );
		return $count;
	}

	public static function getGravityFormsInfo( $form_id = false ) {

		$mapping = self::getOptionGF();

		if ( isset( $form_id ) && $form_id !== false ) {
			return empty( $mapping[ $form_id ] ) ? array() : $mapping[ $form_id ];
		} else {
			return $mapping;
		}

	}

	/**
	 * @param $form_id
	 * @param $data
	 */
	public static function setGravityFormsInfo( $form_id, $data ) {
		$mapping             = self::getOptionGF();
		$mapping[ $form_id ] = $data;
		if ( empty( $data ) ) {
			unset( $mapping[ $form_id ] );
		}
		self::updateOptionGF( $mapping );
	}

	/**
	 * @param $id
	 * @return mixed|string
	 */
	public static function getGravityFormsTag( $id ) {
		$mapping = self::getOptionTag();

		return empty( $mapping['gf_int'][ $id ] ) ? '' : $mapping['gf_int'][ $id ];
	}

	/**
	 * @param $form_id
	 * @param $data
	 */
	public static function setGravityFormsTag( $form_id, $data ) {
		$mapping                       = self::getOptionTag();
		$mapping['gf_int'][ $form_id ] = $data;
		if ( empty( $data ) ) {
			unset( $mapping['gf_int'][ $form_id ] );
		}
		self::updateOptionTag( $mapping );
	}


	/*
	 * Returns all Gravity Froms available to sync
	 * */
	/**
	 * @param null $filterID
	 * @return array
	 */
	public static function getGravityFormsInfoAll( $filterID = null ) {
		if ( ! class_exists( 'GFAPI' ) ) {
			return array();}
		$forms = GFAPI::get_forms();

		$forms = array_filter(
			$forms,
			function( $form ) use ( $filterID ) {
				if ( isset( $filterID ) ) {
					return $form['is_trash'] !== '1' && $form['id'] == $filterID;
				} else {
					return $form['is_trash'] !== '1';
				}
			}
		);

		return empty( $forms ) ? array() : array_values( $forms );// reorder array
	}

	/*
	 * Receives a array and filters the field 'fields' to a simplified version
	 * */
	/**
	 * @param $data
	 * @return array
	 */
	public static function getSimplifiedFormFields( $data ) {

		if ( empty( $data['fields'] ) || ! is_array( $data['fields'] ) ) {
			return array();}

		$simple_map = array();
		foreach ( $data['fields'] as $field ) {
			$inputs = $field->get_entry_inputs();

			if ( is_array( $inputs ) ) {
				foreach ( $inputs as $input ) {
					$simple_map[ $input['id'] ] = $input['label'];
				}
			} else {
				$simple_map[ $field->id ] = $field->label;
			}
		}
		return $simple_map;
	}

	/**
	 *
	 */
	public function getClientAPI() {
		if ( isset( $_POST['egoi_key'] ) ) {
			$key           = sanitize_key( $_POST['egoi_key'] );
			$url           = $this->restUrl . 'getClientData&' . http_build_query( array( 'functionOptions' => array( 'apikey' => $key ) ), '', '&' );
			$result_client = json_decode( $this->_getContent( $url ) );

			if ( ! isset( $result_client->Egoi_Api->getClientData->CLIENTE_ID ) ) {
				header( 'HTTP/1.1 404 Not Found' );
				exit;
			} else {
				$this->callClient( $result_client->Egoi_Api->getClientData, $key, 1 );
				echo 'SUCCESS';
				exit;
			}
		}
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function getTagByID( $id ) {

		$url = $this->restUrl . 'getTags&' . http_build_query(
			array(
				'functionOptions' => array(
					'apikey'     => $this->_valid['api_key'],
					'plugin_key' => $this->plugin,
				),
			),
			'',
			'&'
		);

		$result_client = json_decode( $this->_getContent( $url ) );
		$result        = $result_client->Egoi_Api->getTags->TAG_LIST;

		foreach ( $result as $key => $value ) {
			if ( $value->ID == $id ) {
				$data['NAME'] = $value->NAME;
				$data['ID']   = $value->ID;
			}
		}

		return $data;
	}

	/**
	 * Get E-goi client campaigns
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function getCampaigns() {
		$url = $this->restUrl . 'getCampaigns&' . http_build_query(
			array(
				'functionOptions' => array(
					'apikey'     => $this->_valid['api_key'],
					'limit'      => 1000,
					'plugin_key' => $this->plugin,
				),
			),
			'',
			'&'
		);

		$result_client = json_decode( $this->_getContent( $url ) );
		$result        = $result_client->Egoi_Api->getCampaigns;

		foreach ( $result as $key => $value ) {
			if ( $value->INTERNAL_NAME == '' ) { // remove the double opt-in "campaigns"
				unset( $result->$key );
			}
		}

		return $result;
	}

	/**
	 * @param $campaign_hash
	 * @return mixed
	 */
	public function getReport( $campaign_hash ) {
		$url = $this->restUrl . 'getReport&' . http_build_query(
			array(
				'functionOptions' => array(
					'apikey'     => $this->_valid['api_key'],
					'campaign'   => $campaign_hash,
					'plugin_key' => $this->plugin,
				),
			),
			'',
			'&'
		);

		$result_client = json_decode( $this->_getContent( $url ) );
		return $result_client->Egoi_Api->getReport;
	}


	/**
	 * @param $form_id
	 * @param $form_type
	 * @param $subscriber_data
	 * @param null            $form_title
	 * @return mixed
	 */
	public function smsnf_save_form_subscriber( $form_id, $form_type, $subscriber_data, $form_title = null ) {

		$list = $this->getLists( false, false, $subscriber_data->LIST );

		global $wpdb;

		$table = $wpdb->prefix . 'egoi_form_subscribers';

		$subscriber = array(
			'form_id'          => $form_id,
			'form_type'        => $form_type,
			'subscriber_id'    => sanitize_text_field( $subscriber_data->UID ),
			'subscriber_name'  => sanitize_text_field( $subscriber_data->FIRST_NAME ),
			'subscriber_email' => sanitize_email( $subscriber_data->EMAIL ),
			'list_id'          => $subscriber_data->LIST,
			'list_title'       => empty( $list->key_0->title ) ? $subscriber_data->LIST : $list->key_0->title,
			'created_at'       => current_time( 'mysql' ),
		);

		if ( $form_title ) {
			$subscriber['form_title'] = $form_title;
		} elseif ( $form_type == 'simple-form' ) {
			$subscriber['form_title'] = get_post( $form_id )->post_title;
		} elseif ( $form_type == 'bar' ) {
			$subscriber['form_title'] = 'Subscriber Bar';
		} elseif ( $form_type == 'widget' ) {
			$subscriber['form_title'] = get_option( 'widget_egoi4widget' )[ $form_id ]['title'];
		}

		return $wpdb->insert( $table, $subscriber );
	}

	/**
	 * @param $list
	 */
	public function addTrackEngage( $list ) {
		require_once plugin_dir_path( __FILE__ ) . '../includes/class-egoi-for-wp-apiv3.php';

		$apikey = get_option( 'egoi_api_key' );
		if ( empty( $apikey['apikey'] ) ) {
			return;
		}
		try {
			$api = new EgoiApiV3( $apikey['apikey'] );

			$api->activateTrackingEngage(
				'POST',
				array(
					'domain' => get_site_url(),
					'list'   => $list,
				)
			);
		} catch ( Exception $e ) {
			return;
		}

	}

	/**
	 * @return mixed
	 */
	public static function egoi_subscriber_signup_fields() {
		return apply_filters(
			'egoi_account_fields',
			array(
				'egoi_newsletter_active' => array(
					'type'  => 'checkbox',
					'class' => array( 'input-checkbox', 'egoi-custom-checkbox' ),
					'label' => __( 'Subscribe to newsletter', 'egoi-for-wp' ),
				),
			)
		);
	}

	/**
	 * @param $phone
	 * @return string
	 */
	public static function smsnf_get_valid_phone( $phone, $country = null ) {
		// micro integration with woocommerce checkout fields brazil plugin
		if ( is_plugin_active( 'woocommerce-extra-checkout-fields-for-brazil/woocommerce-extra-checkout-fields-for-brazil.php' ) ) {
			preg_match( '#\((.*?)\)#', $phone, $match_phone );
			if ( isset( $match_phone[1] ) ) {
				return '55-' . preg_replace( '/[^0-9]/', '', $phone );
			} else {
				$match = explode( '-', $phone );
				if ( isset( $match ) && count( $match ) == 4 ) {
					return '55-' . preg_replace( '/[^0-9]/', '', $phone );
				}
			}
		} elseif ( ! empty( $country ) && ! empty( self::COUNTRY_DIAL[ strtoupper( $country ) ] ) ) {
			$phone       = preg_replace( '/\(|\)|\ /', '', $phone );
			$phone_array = explode( '-', $phone );
			if ( count( $phone_array ) == 2 ) {
				foreach ( self::COUNTRY_DIAL as  $code => $dial ) {
					if ( $dial == $phone_array[0] ) {
						return $phone;}
				}
			} else {
				return self::COUNTRY_DIAL[ strtoupper( $country ) ] . '-' . implode( '', $phone_array );
			}
		} else {
			$phone       = preg_replace( '/\(|\)|\ /', '', $phone );
			$phone_array = explode( '-', $phone );

			if ( count( $phone_array ) == 2 ) {
				return $phone;
			}

			$country = get_option( 'woocommerce_default_country' );
			$country = explode( ':', $country, 2 )[0];

			return self::COUNTRY_DIAL[ strtoupper( $country ) ] . '-' . implode( '', $phone_array );
		}
	}

	/**
	 * @return array|mixed
	 */
	public static function getOptionGF() {
		$mapping = get_option( 'egoi_mapping_gf' );
		if ( $mapping === false || ! is_string( $mapping ) ) {
			return array();
		} else {
			$mapping = json_decode( $mapping, true );
		}
		return $mapping;
	}

	/**
	 * @param $data
	 */
	public static function updateOptionGF( $data ) {
		update_option( 'egoi_mapping_gf', wp_json_encode( $data ) );
	}

	/**
	 * @return array|mixed
	 */
	public static function getOptionTag() {
		$mapping = get_option( 'egoi_tag_function' );
		if ( $mapping === false || ! is_string( $mapping ) ) {
			return array();
		} else {
			$mapping = json_decode( $mapping, true );
		}
		return $mapping;
	}

	/**
	 * @param $data
	 */
	public static function updateOptionTag( $data ) {
		update_option( 'egoi_tag_function', wp_json_encode( $data ) );
	}

	/**
	 * @return array
	 */
	public static function getAccountTags() {
		$Egoi4WpBuilderObject = get_option( 'Egoi4WpBuilderObject' );
		$tag_list             = json_decode( wp_json_encode( $Egoi4WpBuilderObject->getTags() ), true );

		if ( empty( $tag_list['TAG_LIST'] ) || ! is_array( $tag_list['TAG_LIST'] ) ) {
			return array();
		}

		$tag_list = $tag_list['TAG_LIST'];

		$parsed_list = array();
		foreach ( $tag_list as $tag ) {
			$parsed_list[ $tag['ID'] ] = $tag['NAME'];
		}

		return $parsed_list;
	}

	/**
	 * @param null $plugin_key
	 * @return array
	 */
	public static function getFullListFields( $plugin_key = null ) {

		$options = get_option( Egoi_For_Wp_Admin::OPTION_NAME );

		$Egoi4WpBuilderObject = get_option( 'Egoi4WpBuilderObject' );
		$extra                = (array) $Egoi4WpBuilderObject->getExtraFields( empty( $options['list'] ) ? $options->list : $options['list'], $plugin_key );

		$egoi_fields = array(
			'first_name' => __( 'First name', 'egoi-for-wp' ),
			'last_name'  => __( 'Last name', 'egoi-for-wp' ),
			'cellphone'  => __( 'Mobile', 'egoi-for-wp' ),
			'email'      => __( 'Email', 'egoi-for-wp' ),
			'telephone'  => __( 'Telephone', 'egoi-for-wp' ),
			'birth_date' => __( 'Birth Date', 'egoi-for-wp' ),
			'lang'       => __( 'Language', 'egoi-for-wp' ),
		);

		if ( ( ! empty( $options->list ) || ! empty( $options['list'] ) ) && $extra ) {

			foreach ( $extra as $key => $extra_field ) {
				$egoi_extra_field_key                 = str_replace( 'key_', 'extra_', $key );
				$egoi_fields[ $egoi_extra_field_key ] = $extra_field->NAME;
			}
		}

		return $egoi_fields;
	}
}
