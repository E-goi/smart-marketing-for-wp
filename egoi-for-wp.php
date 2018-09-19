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
 * Version:           2.3.4
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

define('SELF_VERSION', '2.3.4');

if (!session_id()){
    session_start();
}

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

// HOOK E-GOI SIMPLE FORM SHORTCODE
function process_egoi_simple_form($atts){
    $public_area = new Egoi_For_Wp_Public();
    return $public_area->subscribe_egoi_simple_form($atts);
}
add_shortcode( 'egoi-simple-form', 'process_egoi_simple_form' );

// HOOK E-GOI SIMPLE FORM ADD SUBSCRIBER
add_action( 'wp_ajax_my_action', 'process_simple_form_add' );
add_action( 'wp_ajax_nopriv_my_action', 'process_simple_form_add' );
function process_simple_form_add(){
    $admin = new Egoi_For_Wp_Admin('smart-marketing-for-wp', SELF_VERSION);
    return $admin->subscribe_egoi_simple_form_add();
}

// HOOK E-GOI VISUAL COMPOSER SHORTCODE
function process_egoi_vc_shortcode($atts){
    $public_area = new Egoi_For_Wp_Public();
    return $public_area->egoi_vc_shortcode_output($atts);
}
add_shortcode( 'egoi_vc_shortcode', 'process_egoi_vc_shortcode' );

// HOOK E-GOI PAGE BUILDER WIDGET 
function add_egoi_pb_widget_folders( $folders ){
    $folders[] = plugin_dir_path(__FILE__) . 'widgets/';
    return $folders;
}
add_action('siteorigin_widgets_widget_folders', 'add_egoi_pb_widget_folders');


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

// HOOK GET TAGS
add_action('wp_ajax_egoi_get_tags', 'egoi_get_tags');
function egoi_get_tags(){
    $admin = new Egoi_For_Wp_Admin('smart-marketing-for-wp', SELF_VERSION);
    return $admin->get_tags();
}

// HOOK ADD TAG
add_action('wp_ajax_egoi_add_tag', 'egoi_add_tag');
function egoi_add_tag(){
    $admin = new Egoi_For_Wp_Admin('smart-marketing-for-wp', SELF_VERSION);
    return $admin->add_tag($_POST['name']);
}

// HOOK WEB PUSH
function egoi_add_webpush() {

    if(!strpos($_SERVER['HTTP_REFERER'], 'wp-admin')){ //don't show web push on admin pages
        $public_area = new Egoi_For_Wp_Public();
        $webpush = $public_area->add_webpush();

        if ($webpush) {
            echo $webpush;
        }
    }
}
add_action('wp_footer', 'egoi_add_webpush');


/**
 * Hooks for RSS Feeds
 * Registers our custom feed
 */
add_action('wp_feed_options', 'force_feed', 10, 1);
function force_feed($feed) {
    $feed->force_feed(true);
}

function register_egoi_rss_feeds() {
    $public_area = new Egoi_For_Wp_Public();
    $public_area->add_egoi_rss_feeds();
}
add_action( 'init', 'register_egoi_rss_feeds' );


function egoi_rss_feeds(){
    $admin = new Egoi_For_Wp_Admin('smart-marketing-for-wp', SELF_VERSION);
    $admin->egoi_rss_feeds_content();
}

function hook_font_awesome() {
    ?>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <?php
}
add_action('admin_head', 'hook_font_awesome');

// COUNTRY MOBILE CODES
define( 'COUNTRY_CODES' , serialize(array (
    'AD' => array ('name' => 'Andorra', 'code' => '376'),
    'AE' => array ('name' => 'Emirados Árabes Unidos', 'code' => '971'),
    'AF' => array ('name' => 'Afeganistão', 'code' => '93'),
    'AG' => array ('name' => 'Antígua e Barbuda', 'code' => '1268'),
    'AL' => array ('name' => 'Albânia', 'code' => '355'),
    'AM' => array ('name' => 'Arménia', 'code' => '374'),
    'AN' => array ('name' => 'Bonaire, Saint Eustatius e Saba', 'code' => '599'),
    'AO' => array ('name' => 'Angola', 'code' => '244'),
    'AR' => array ('name' => 'Argentina', 'code' => '54'),
    'AS' => array ('name' => 'Samoa Americana', 'code' => '1684'),
    'AT' => array ('name' => 'Áustria', 'code' => '43'),
    'AU' => array ('name' => 'Austrália', 'code' => '61'),
    'AW' => array ('name' => 'Aruba', 'code' => '297'),
    'AZ' => array ('name' => 'Azerbeijão', 'code' => '994'),
    'BA' => array ('name' => 'Bósnia-Herzegovina', 'code' => '387'),
    'BB' => array ('name' => 'Barbados', 'code' => '1246'),
    'BD' => array ('name' => 'Bangladesh', 'code' => '880'),
    'BE' => array ('name' => 'Bélgica', 'code' => '32'),
    'BF' => array ('name' => 'Burkina-Faso', 'code' => '226'),
    'BG' => array ('name' => 'Bulgária', 'code' => '359'),
    'BH' => array ('name' => 'Bahrein', 'code' => '973'),
    'BI' => array ('name' => 'Burundi', 'code' => '257'),
    'BJ' => array ('name' => 'Benin', 'code' => '229'),
    'BL' => array ('name' => 'Guadeloupe', 'code' => '590'),
    'BM' => array ('name' => 'Bermuda', 'code' => '1441'),
    'BN' => array ('name' => 'Brunei', 'code' => '673'),
    'BO' => array ('name' => 'Bolívia', 'code' => '591'),
    'BR' => array ('name' => 'Brasil', 'code' => '55'),
    'BS' => array ('name' => 'Baamas', 'code' => '1242'),
    'BT' => array ('name' => 'Butão', 'code' => '975'),
    'BW' => array ('name' => 'Botsuana', 'code' => '267'),
    'BY' => array ('name' => 'Bielorrússia', 'code' => '375'),
    'BZ' => array ('name' => 'Belize', 'code' => '501'),
    'CA' => array ('name' => 'Canadá', 'code' => '1'),
    'CC' => array ('name' => 'Austrália', 'code' => '61'),
    'CD' => array ('name' => 'Congo-Brazzaville', 'code' => '243'),
    'CF' => array ('name' => 'República Centro-Africana', 'code' => '236'),
    'CG' => array ('name' => 'Congo-Kinshasa', 'code' => '242'),
    'CH' => array ('name' => 'Suíça', 'code' => '41'),
    'CI' => array ('name' => 'Costa do Marfim', 'code' => '225'),
    'CK' => array ('name' => 'Ilhas Cook', 'code' => '682'),
    'CL' => array ('name' => 'Chile', 'code' => '56'),
    'CM' => array ('name' => 'Camarões', 'code' => '237'),
    'CN' => array ('name' => 'China', 'code' => '86'),
    'CO' => array ('name' => 'Colômbia', 'code' => '57'),
    'CR' => array ('name' => 'Costa Rica', 'code' => '506'),
    'CU' => array ('name' => 'Cuba', 'code' => '53'),
    'CV' => array ('name' => 'Cabo Verde', 'code' => '238'),
    'CX' => array ('name' => 'Austrália', 'code' => '61'),
    'CY' => array ('name' => 'Chipre', 'code' => '357'),
    'CZ' => array ('name' => 'República Checa', 'code' => '420'),
    'DE' => array ('name' => 'Alemanha', 'code' => '49'),
    'DJ' => array ('name' => 'Jibuti', 'code' => '253'),
    'DK' => array ('name' => 'Dinamarca', 'code' => '45'),
    'DM' => array ('name' => 'Dominica', 'code' => '1767'),
    'DZ' => array ('name' => 'Algéria', 'code' => '213'),
    'EC' => array ('name' => 'Equador', 'code' => '593'),
    'EE' => array ('name' => 'Estónia', 'code' => '372'),
    'EG' => array ('name' => 'Egipto', 'code' => '20'),
    'ER' => array ('name' => 'Eritreia', 'code' => '291'),
    'ES' => array ('name' => 'Espanha', 'code' => '34'),
    'ET' => array ('name' => 'Etiópia', 'code' => '251'),
    'FI' => array ('name' => 'Finlândia', 'code' => '358'),
    'FJ' => array ('name' => 'Fiji', 'code' => '679'),
    'FK' => array ('name' => 'Ilhas Falkland', 'code' => '500'),
    'FM' => array ('name' => 'Micronésia', 'code' => '691'),
    'FO' => array ('name' => 'Ilhas Faroe', 'code' => '298'),
    'FR' => array ('name' => 'França', 'code' => '33'),
    'GA' => array ('name' => 'Gabão', 'code' => '241'),
    'GB' => array ('name' => 'Ilha de Man', 'code' => '44'),
    'GD' => array ('name' => 'Granada', 'code' => '1473'),
    'GE' => array ('name' => 'Geórgia', 'code' => '995'),
    'GH' => array ('name' => 'Gana', 'code' => '233'),
    'GI' => array ('name' => 'Gibraltar', 'code' => '350'),
    'GL' => array ('name' => 'Gronelândia', 'code' => '299'),
    'GM' => array ('name' => 'Gâmbia', 'code' => '220'),
    'GN' => array ('name' => 'Guiné', 'code' => '224'),
    'GQ' => array ('name' => 'Guiné Equatorial', 'code' => '240'),
    'GR' => array ('name' => 'Grécia', 'code' => '30'),
    'GT' => array ('name' => 'Guatemala', 'code' => '502'),
    'GU' => array ('name' => 'Guam', 'code' => '1671'),
    'GW' => array ('name' => 'Guiné-Bissau', 'code' => '245'),
    'GY' => array ('name' => 'Guiana', 'code' => '592'),
    'HK' => array ('name' => 'Hong Kong', 'code' => '852'),
    'HN' => array ('name' => 'Honduras', 'code' => '504'),
    'HR' => array ('name' => 'Croácia', 'code' => '385'),
    'HT' => array ('name' => 'Haiti', 'code' => '509'),
    'HU' => array ('name' => 'Hungria', 'code' => '36'),
    'ID' => array ('name' => 'Indonésia', 'code' => '62'),
    'IE' => array ('name' => 'Irlanda', 'code' => '353'),
    'IL' => array ('name' => 'Israel', 'code' => '972'),
    'IM' => array ('name' => 'Ilha de Man', 'code' => '44'),
    'IN' => array ('name' => 'Índia', 'code' => '91'),
    'IQ' => array ('name' => 'Iraque', 'code' => '964'),
    'IR' => array ('name' => 'Irão', 'code' => '98'),
    'IS' => array ('name' => 'Islândia', 'code' => '354'),
    'IT' => array ('name' => 'Itália', 'code' => '39'),
    'JM' => array ('name' => 'Jamaica', 'code' => '1876'),
    'JO' => array ('name' => 'Jordânia', 'code' => '962'),
    'JP' => array ('name' => 'Japão', 'code' => '81'),
    'KE' => array ('name' => 'Quénia', 'code' => '254'),
    'KH' => array ('name' => 'Camboja', 'code' => '855'),
    'KI' => array ('name' => 'Quiribati', 'code' => '686'),
    'KM' => array ('name' => 'Comores', 'code' => '269'),
    'KN' => array ('name' => 'Saint Kitts e Nevis', 'code' => '1869'),
    'KP' => array ('name' => 'Coreia do Norte', 'code' => '850'),
    'KR' => array ('name' => 'Coreia do Sul', 'code' => '82'),
    'KW' => array ('name' => 'Koweit', 'code' => '965'),
    'KY' => array ('name' => 'Ilhas Cayman', 'code' => '1345'),
    'KZ' => array ('name' => 'Cazaquistão', 'code' => '7'),
    'LA' => array ('name' => 'Laos', 'code' => '856'),
    'LB' => array ('name' => 'Líbano', 'code' => '961'),
    'LC' => array ('name' => 'Santa Lúcia', 'code' => '1758'),
    'LI' => array ('name' => 'Liechtenstein', 'code' => '423'),
    'LK' => array ('name' => 'Sri Lanka', 'code' => '94'),
    'LR' => array ('name' => 'Libéria', 'code' => '231'),
    'LS' => array ('name' => 'Lesoto', 'code' => '266'),
    'LT' => array ('name' => 'Lituânia', 'code' => '370'),
    'LU' => array ('name' => 'Luxemburgo', 'code' => '352'),
    'LV' => array ('name' => 'Letónia', 'code' => '371'),
    'LY' => array ('name' => 'Líbia', 'code' => '218'),
    'MA' => array ('name' => 'Marrocos', 'code' => '212'),
    'MC' => array ('name' => 'Mónaco', 'code' => '377'),
    'MD' => array ('name' => 'Moldávia', 'code' => '373'),
    'ME' => array ('name' => 'Montenegro', 'code' => '382'),
    'MG' => array ('name' => 'Madagáscar', 'code' => '261'),
    'MH' => array ('name' => 'Ilhas Marshall', 'code' => '692'),
    'MK' => array ('name' => 'Macedónia', 'code' => '389'),
    'ML' => array ('name' => 'Mali', 'code' => '223'),
    'MM' => array ('name' => 'Mianmar', 'code' => '95'),
    'MN' => array ('name' => 'Mongólia', 'code' => '976'),
    'MO' => array ('name' => 'Macau', 'code' => '853'),
    'MP' => array ('name' => 'Ilhas Marianas do Norte', 'code' => '1670'),
    'MR' => array ('name' => 'Mauritânia', 'code' => '222'),
    'MS' => array ('name' => 'Montserrat', 'code' => '1664'),
    'MT' => array ('name' => 'Malta', 'code' => '356'),
    'MU' => array ('name' => 'Maurícia', 'code' => '230'),
    'MV' => array ('name' => 'Maldivas', 'code' => '960'),
    'MW' => array ('name' => 'Malawi', 'code' => '265'),
    'MX' => array ('name' => 'México', 'code' => '52'),
    'MY' => array ('name' => 'Malásia', 'code' => '60'),
    'MZ' => array ('name' => 'Moçambique', 'code' => '258'),
    'NA' => array ('name' => 'Namíbia', 'code' => '264'),
    'NC' => array ('name' => 'Nova Caledonia', 'code' => '687'),
    'NE' => array ('name' => 'Níger', 'code' => '227'),
    'NG' => array ('name' => 'Nigéria', 'code' => '234'),
    'NI' => array ('name' => 'Nicarágua', 'code' => '505'),
    'NL' => array ('name' => 'Países Baixos', 'code' => '31'),
    'NO' => array ('name' => 'Noruega', 'code' => '47'),
    'NP' => array ('name' => 'Nepal', 'code' => '977'),
    'NR' => array ('name' => 'Nauru', 'code' => '674'),
    'NU' => array ('name' => 'Niue', 'code' => '683'),
    'NZ' => array ('name' => 'Nova Zelândia', 'code' => '64'),
    'OM' => array ('name' => 'Omã', 'code' => '968'),
    'PA' => array ('name' => 'Panamá', 'code' => '507'),
    'PE' => array ('name' => 'Peru', 'code' => '51'),
    'PF' => array ('name' => 'Polinésia Francêsa', 'code' => '689'),
    'PG' => array ('name' => 'Papua Nova Guiné', 'code' => '675'),
    'PH' => array ('name' => 'Filipinas', 'code' => '63'),
    'PK' => array ('name' => 'Paquistão', 'code' => '92'),
    'PL' => array ('name' => 'Polónia', 'code' => '48'),
    'PM' => array ('name' => 'Saint Pierre and Miquelon', 'code' => '508'),
    'PR' => array ('name' => 'Canadá', 'code' => '1'),
    'PT' => array ('name' => 'Portugal', 'code' => '351'),
    'PW' => array ('name' => 'Palau', 'code' => '680'),
    'PY' => array ('name' => 'Paraguai', 'code' => '595'),
    'QA' => array ('name' => 'Qatar', 'code' => '974'),
    'RO' => array ('name' => 'Roménia', 'code' => '40'),
    'RS' => array ('name' => 'Sérvia', 'code' => '381'),
    'RU' => array ('name' => 'Cazaquistão', 'code' => '7'),
    'RW' => array ('name' => 'Ruanda', 'code' => '250'),
    'SA' => array ('name' => 'Arábia Saudita', 'code' => '966'),
    'SB' => array ('name' => 'Ilhas Salomão', 'code' => '677'),
    'SC' => array ('name' => 'Seicheles', 'code' => '248'),
    'SD' => array ('name' => 'Sudão', 'code' => '249'),
    'SE' => array ('name' => 'Suécia', 'code' => '46'),
    'SG' => array ('name' => 'Singapura', 'code' => '65'),
    'SH' => array ('name' => 'Saint Helena, Tristan da Cunha', 'code' => '290'),
    'SI' => array ('name' => 'Eslovénia', 'code' => '386'),
    'SK' => array ('name' => 'Eslováquia', 'code' => '421'),
    'SL' => array ('name' => 'Serra Leoa', 'code' => '232'),
    'SM' => array ('name' => 'São Marino', 'code' => '378'),
    'SN' => array ('name' => 'Senegal', 'code' => '221'),
    'SO' => array ('name' => 'Somália', 'code' => '252'),
    'SR' => array ('name' => 'Suriname', 'code' => '597'),
    'ST' => array ('name' => 'São Tomé e Príncipe', 'code' => '239'),
    'SV' => array ('name' => 'El Salvador', 'code' => '503'),
    'SY' => array ('name' => 'Síria', 'code' => '963'),
    'SZ' => array ('name' => 'Suazilândia', 'code' => '268'),
    'TC' => array ('name' => 'Turks and Caicos Islands', 'code' => '1649'),
    'TD' => array ('name' => 'Chade', 'code' => '235'),
    'TG' => array ('name' => 'Togo', 'code' => '228'),
    'TH' => array ('name' => 'Tailândia', 'code' => '66'),
    'TJ' => array ('name' => 'Tajiquistão', 'code' => '992'),
    'TL' => array ('name' => 'Timor-Leste', 'code' => '670'),
    'TM' => array ('name' => 'Turquemenistão', 'code' => '993'),
    'TN' => array ('name' => 'Tunísia', 'code' => '216'),
    'TO' => array ('name' => 'Tonga', 'code' => '676'),
    'TR' => array ('name' => 'Turquia', 'code' => '90'),
    'TT' => array ('name' => 'Trindade e Tobago', 'code' => '1868'),
    'TV' => array ('name' => 'Tuvalu', 'code' => '688'),
    'TW' => array ('name' => 'Taiwan', 'code' => '886'),
    'TZ' => array ('name' => 'Tanzânia', 'code' => '255'),
    'UA' => array ('name' => 'Ucrânia', 'code' => '380'),
    'UG' => array ('name' => 'Uganda', 'code' => '256'),
    'US' => array ('name' => 'Canadá', 'code' => '1'),
    'UY' => array ('name' => 'Uruguai', 'code' => '598'),
    'UZ' => array ('name' => 'Usbequistão', 'code' => '998'),
    'VA' => array ('name' => 'Itália', 'code' => '39'),
    'VC' => array ('name' => 'São Vicente e Granadinas', 'code' => '1784'),
    'VE' => array ('name' => 'Venezuela', 'code' => '58'),
    'VG' => array ('name' => 'Ilhas Virgens Britânicas', 'code' => '1284'),
    'VI' => array ('name' => 'Ilhas Virgem Americas', 'code' => '1340'),
    'VN' => array ('name' => 'Vietname', 'code' => '84'),
    'VU' => array ('name' => 'Vanuatu', 'code' => '678'),
    'WF' => array ('name' => 'Wallis and Futuna', 'code' => '681'),
    'WS' => array ('name' => 'Samoa', 'code' => '685'),
    'XK' => array ('name' => 'Sérvia', 'code' => '381'),
    'YE' => array ('name' => 'Iémen', 'code' => '967'),
    'YT' => array ('name' => 'Mayotte', 'code' => '262'),
    'ZA' => array ('name' => 'África do Sul', 'code' => '27'),
    'ZM' => array ('name' => 'Zâmbia', 'code' => '260'),
    'ZW' => array ('name' => 'Zimbabwe', 'code' => '263')
)));


// INITIALIZE PLUGIN
function run_egoi_for_wp() {

    $plugin = new Egoi_For_Wp();
    $plugin->run();

}
run_egoi_for_wp();
