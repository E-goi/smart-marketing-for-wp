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
define( 'COUNTRY_CODES' , serialize(array(
    'AFG'=>array('name'=>'Afeganistão','code'=>'93'),
    'ZAF'=>array('name'=>'África do Sul','code'=>'27'),
    'ALB'=>array('name'=>'Albânia','code'=>'355'),
    'DEU'=>array('name'=>'Alemanha','code'=>'49'),
    'DZA'=>array('name'=>'Algéria','code'=>'213'),
    'AND'=>array('name'=>'Andorra','code'=>'376'),
    'AGO'=>array('name'=>'Angola','code'=>'244'),
    'AIA'=>array('name'=>'Anguilla','code'=>'12684'),
    'ATG'=>array('name'=>'Antígua e Barbuda','code'=>'1268'),
    'SAU'=>array('name'=>'Arábia Saudita','code'=>'966'),
    'ARG'=>array('name'=>'Argentina','code'=>'54'),
    'ARM'=>array('name'=>'Arménia','code'=>'374'),
    'ABW'=>array('name'=>'Aruba','code'=>'297'),
    'ASC'=>array('name'=>'Ascension','code'=>'247'),
    'AUS'=>array('name'=>'Austrália','code'=>'61'),
    'AUT'=>array('name'=>'Áustria','code'=>'43'),
    'AZE'=>array('name'=>'Azerbeijão','code'=>'994'),
    'BHS'=>array('name'=>'Baamas','code'=>'1242'),
    'BHR'=>array('name'=>'Bahrein','code'=>'973'),
    'BGD'=>array('name'=>'Bangladesh','code'=>'880'),
    'BRB'=>array('name'=>'Barbados','code'=>'1246'),
    'BEL'=>array('name'=>'Bélgica','code'=>'32'),
    'BLZ'=>array('name'=>'Belize','code'=>'501'),
    'BEN'=>array('name'=>'Benin','code'=>'229'),
    'BMU'=>array('name'=>'Bermuda','code'=>'1441'),
    'BLR'=>array('name'=>'Bielorrússia','code'=>'375'),
    'BOL'=>array('name'=>'Bolívia','code'=>'591'),
    'SXM'=>array('name'=>'Bonaire, Saint Eustatius e Saba','code'=>'599'),
    'BIH'=>array('name'=>'Bósnia-Herzegovina','code'=>'387'),
    'BWA'=>array('name'=>'Botsuana','code'=>'267'),
    'BRA'=>array('name'=>'Brasil','code'=>'55'),
    'BRN'=>array('name'=>'Brunei','code'=>'673'),
    'BGR'=>array('name'=>'Bulgária','code'=>'359'),
    'BFA'=>array('name'=>'Burkina-Faso','code'=>'226'),
    'BDI'=>array('name'=>'Burundi','code'=>'257'),
    'BTN'=>array('name'=>'Butão','code'=>'975'),
    'CPV'=>array('name'=>'Cabo Verde','code'=>'238'),
    'CMR'=>array('name'=>'Camarões','code'=>'237'),
    'KHM'=>array('name'=>'Camboja','code'=>'855'),
    'CAN'=>array('name'=>'Canadá','code'=>'1'),
    'KAZ'=>array('name'=>'Cazaquistão','code'=>'7'),
    'TCD'=>array('name'=>'Chade','code'=>'235'),
    'CHL'=>array('name'=>'Chile','code'=>'56'),
    'CHN'=>array('name'=>'China','code'=>'86'),
    'CYP'=>array('name'=>'Chipre','code'=>'357'),
    'VAT'=>array('name'=>'Cidade do Vaticano','code'=>'379'),
    'COL'=>array('name'=>'Colômbia','code'=>'57'),
    'COM'=>array('name'=>'Comores','code'=>'269'),
    'COG'=>array('name'=>'Congo-Brazzaville','code'=>'243'),
    'COD'=>array('name'=>'Congo-Kinshasa','code'=>'242'),
    'PRK'=>array('name'=>'Coreia do Norte','code'=>'850'),
    'KOR'=>array('name'=>'Coreia do Sul','code'=>'82'),
    'CIV'=>array('name'=>'Costa do Marfim','code'=>'225'),
    'CRI'=>array('name'=>'Costa Rica','code'=>'506'),
    'HRV'=>array('name'=>'Croácia','code'=>'385'),
    'CUB'=>array('name'=>'Cuba','code'=>'53'),
    'CUW'=>array('name'=>'Curação','code'=>'599'),
    'DGA'=>array('name'=>'Diego Garcia','code'=>'246'),
    'DNK'=>array('name'=>'Dinamarca','code'=>'45'),
    'DMA'=>array('name'=>'Dominica','code'=>'1767'),
    'EGY'=>array('name'=>'Egipto','code'=>'20'),
    'SLV'=>array('name'=>'El Salvador','code'=>'503'),
    'ARE'=>array('name'=>'Emirados Árabes Unidos','code'=>'971'),
    'ECU'=>array('name'=>'Equador','code'=>'593'),
    'ERI'=>array('name'=>'Eritreia','code'=>'291'),
    'SVK'=>array('name'=>'Eslováquia','code'=>'421'),
    'SVN'=>array('name'=>'Eslovénia','code'=>'386'),
    'ESP'=>array('name'=>'Espanha','code'=>'34'),
    'EST'=>array('name'=>'Estónia','code'=>'372'),
    'ETH'=>array('name'=>'Etiópia','code'=>'251'),
    'USA'=>array('name'=>'EUA','code'=>'1'),
    'FJI'=>array('name'=>'Fiji','code'=>'679'),
    'PHL'=>array('name'=>'Filipinas','code'=>'63'),
    'FIN'=>array('name'=>'Finlândia','code'=>'358'),
    'FRA'=>array('name'=>'França','code'=>'33'),
    'GAB'=>array('name'=>'Gabão','code'=>'241'),
    'GMB'=>array('name'=>'Gâmbia','code'=>'220'),
    'GHA'=>array('name'=>'Gana','code'=>'233'),
    'GEO'=>array('name'=>'Geórgia','code'=>'995'),
    'GIB'=>array('name'=>'Gibraltar','code'=>'350'),
    'GRD'=>array('name'=>'Granada','code'=>'1473'),
    'GRC'=>array('name'=>'Grécia','code'=>'30'),
    'GRL'=>array('name'=>'Gronelândia','code'=>'299'),
    'GLP'=>array('name'=>'Guadeloupe','code'=>'590'),
    'GUM'=>array('name'=>'Guam','code'=>'1671'),
    'GTM'=>array('name'=>'Guatemala','code'=>'502'),
    'GUY'=>array('name'=>'Guiana','code'=>'592'),
    'GUF'=>array('name'=>'Guiana Francêsa','code'=>'594'),
    'GIN'=>array('name'=>'Guiné','code'=>'224'),
    'GNQ'=>array('name'=>'Guiné Equatorial','code'=>'240'),
    'GNB'=>array('name'=>'Guiné-Bissau','code'=>'245'),
    'HTI'=>array('name'=>'Haiti','code'=>'509'),
    'HND'=>array('name'=>'Honduras','code'=>'504'),
    'HKG'=>array('name'=>'Hong Kong','code'=>'852'),
    'HUN'=>array('name'=>'Hungria','code'=>'36'),
    'YEM'=>array('name'=>'Iémen','code'=>'967'),
    'IMN'=>array('name'=>'Ilha de Man','code'=>'44'),
    'CYM'=>array('name'=>'Ilhas Cayman','code'=>'1345'),
    'COK'=>array('name'=>'Ilhas Cook','code'=>'682'),
    'FLK'=>array('name'=>'Ilhas Falkland','code'=>'500'),
    'FRO'=>array('name'=>'Ilhas Faroe','code'=>'298'),
    'MNP'=>array('name'=>'Ilhas Marianas do Norte','code'=>'1670'),
    'MHL'=>array('name'=>'Ilhas Marshall','code'=>'692'),
    'SLB'=>array('name'=>'Ilhas Salomão','code'=>'677'),
    'VIR'=>array('name'=>'Ilhas Virgem Americas','code'=>'1340'),
    'VGB'=>array('name'=>'Ilhas Virgens Britânicas','code'=>'1284'),
    'IND'=>array('name'=>'Índia','code'=>'91'),
    'IDN'=>array('name'=>'Indonésia','code'=>'62'),
    'IRN'=>array('name'=>'Irão','code'=>'98'),
    'IRQ'=>array('name'=>'Iraque','code'=>'964'),
    'IRL'=>array('name'=>'Irlanda','code'=>'353'),
    'ISL'=>array('name'=>'Islândia','code'=>'354'),
    'ISR'=>array('name'=>'Israel','code'=>'972'),
    'ITA'=>array('name'=>'Itália','code'=>'39'),
    'JAM'=>array('name'=>'Jamaica','code'=>'1876'),
    'JPN'=>array('name'=>'Japão','code'=>'81'),
    'DJI'=>array('name'=>'Jibuti','code'=>'253'),
    'JOR'=>array('name'=>'Jordânia','code'=>'962'),
    'KWT'=>array('name'=>'Koweit','code'=>'965'),
    'LAO'=>array('name'=>'Laos','code'=>'856'),
    'LSO'=>array('name'=>'Lesoto','code'=>'266'),
    'LVA'=>array('name'=>'Letónia','code'=>'371'),
    'LBN'=>array('name'=>'Líbano','code'=>'961'),
    'LBR'=>array('name'=>'Libéria','code'=>'231'),
    'LBY'=>array('name'=>'Líbia','code'=>'218'),
    'LIE'=>array('name'=>'Liechtenstein','code'=>'423'),
    'LTU'=>array('name'=>'Lituânia','code'=>'370'),
    'LUX'=>array('name'=>'Luxemburgo','code'=>'352'),
    'MAC'=>array('name'=>'Macau','code'=>'853'),
    'MKD'=>array('name'=>'Macedónia','code'=>'389'),
    'MDG'=>array('name'=>'Madagáscar','code'=>'261'),
    'MYS'=>array('name'=>'Malásia','code'=>'60'),
    'MWI'=>array('name'=>'Malawi','code'=>'265'),
    'MDV'=>array('name'=>'Maldivas','code'=>'960'),
    'MLI'=>array('name'=>'Mali','code'=>'223'),
    'MLT'=>array('name'=>'Malta','code'=>'356'),
    'MAR'=>array('name'=>'Marrocos','code'=>'212'),
    'MTQ'=>array('name'=>'Martinica','code'=>'596'),
    'MUS'=>array('name'=>'Maurícia','code'=>'230'),
    'MRT'=>array('name'=>'Mauritânia','code'=>'222'),
    'MYT'=>array('name'=>'Mayotte','code'=>'262'),
    'MEX'=>array('name'=>'México','code'=>'52'),
    'MMR'=>array('name'=>'Mianmar','code'=>'95'),
    'FSM'=>array('name'=>'Micronésia','code'=>'691'),
    'MOZ'=>array('name'=>'Moçambique','code'=>'258'),
    'MDA'=>array('name'=>'Moldávia','code'=>'373'),
    'MCO'=>array('name'=>'Mónaco','code'=>'377'),
    'MNG'=>array('name'=>'Mongólia','code'=>'976'),
    'MNE'=>array('name'=>'Montenegro','code'=>'382'),
    'MSR'=>array('name'=>'Montserrat','code'=>'1664'),
    'NAM'=>array('name'=>'Namíbia','code'=>'264'),
    'NRU'=>array('name'=>'Nauru','code'=>'674'),
    'NPL'=>array('name'=>'Nepal','code'=>'977'),
    'NIC'=>array('name'=>'Nicarágua','code'=>'505'),
    'NER'=>array('name'=>'Níger','code'=>'227'),
    'NGA'=>array('name'=>'Nigéria','code'=>'234'),
    'NIU'=>array('name'=>'Niue','code'=>'683'),
    'NOR'=>array('name'=>'Noruega','code'=>'47'),
    'NCL'=>array('name'=>'Nova Caledonia','code'=>'687'),
    'NZL'=>array('name'=>'Nova Zelândia','code'=>'64'),
    'OMN'=>array('name'=>'Omã','code'=>'968'),
    'NLD'=>array('name'=>'Países Baixos','code'=>'31'),
    'PLW'=>array('name'=>'Palau','code'=>'680'),
    'PSE'=>array('name'=>'Palestina','code'=>'970'),
    'PAN'=>array('name'=>'Panamá','code'=>'507'),
    'PNG'=>array('name'=>'Papua Nova Guiné','code'=>'675'),
    'PAK'=>array('name'=>'Paquistão','code'=>'92'),
    'PRY'=>array('name'=>'Paraguai','code'=>'595'),
    'PER'=>array('name'=>'Peru','code'=>'51'),
    'PYF'=>array('name'=>'Polinésia Francêsa','code'=>'689'),
    'POL'=>array('name'=>'Polónia','code'=>'48'),
    'PRT'=>array('name'=>'Portugal','code'=>'351'),
    'PRI'=>array('name'=>'Puerto Rico','code'=>'1'),
    'QAT'=>array('name'=>'Qatar','code'=>'974'),
    'KEN'=>array('name'=>'Quénia','code'=>'254'),
    'KGZ'=>array('name'=>'Quirguistão','code'=>'7'),
    'KIR'=>array('name'=>'Quiribati','code'=>'686'),
    'GBR'=>array('name'=>'Reino Unido','code'=>'44'),
    'CAF'=>array('name'=>'República Centro-Africana','code'=>'236'),
    'CZE'=>array('name'=>'República Checa','code'=>'420'),
    'DOM'=>array('name'=>'República Dominicana','code'=>'1'),
    'REU'=>array('name'=>'Reunion','code'=>'262'),
    'ROU'=>array('name'=>'Roménia','code'=>'40'),
    'RWA'=>array('name'=>'Ruanda','code'=>'250'),
    'RUS'=>array('name'=>'Rússia','code'=>'7'),
    'SHN'=>array('name'=>'Saint Helena, Tristan da Cunha','code'=>'290'),
    'KNA'=>array('name'=>'Saint Kitts e Nevis','code'=>'1869'),
    'SPM'=>array('name'=>'Saint Pierre and Miquelon','code'=>'508'),
    'WSM'=>array('name'=>'Samoa','code'=>'685'),
    'ASM'=>array('name'=>'Samoa Americana','code'=>'1684'),
    'LCA'=>array('name'=>'Santa Lúcia','code'=>'1758'),
    'SMR'=>array('name'=>'São Marino','code'=>'378'),
    'STP'=>array('name'=>'São Tomé e Príncipe','code'=>'239'),
    'VCT'=>array('name'=>'São Vicente e Granadinas','code'=>'1784'),
    'SYC'=>array('name'=>'Seicheles','code'=>'248'),
    'SEN'=>array('name'=>'Senegal','code'=>'221'),
    'SLE'=>array('name'=>'Serra Leoa','code'=>'232'),
    'SRB'=>array('name'=>'Sérvia','code'=>'381'),
    'SGP'=>array('name'=>'Singapura','code'=>'65'),
    'BES'=>array('name'=>'Sint Maarten Holandês','code'=>'599'),
    'SYR'=>array('name'=>'Síria','code'=>'963'),
    'SOM'=>array('name'=>'Somália','code'=>'252'),
    'LKA'=>array('name'=>'Sri Lanka','code'=>'94'),
    'SWZ'=>array('name'=>'Suazilândia','code'=>'268'),
    'SDN'=>array('name'=>'Sudão','code'=>'249'),
    'SWE'=>array('name'=>'Suécia','code'=>'46'),
    'CHE'=>array('name'=>'Suíça','code'=>'41'),
    'SUR'=>array('name'=>'Suriname','code'=>'597'),
    'THA'=>array('name'=>'Tailândia','code'=>'66'),
    'TWN'=>array('name'=>'Taiwan','code'=>'886'),
    'TJK'=>array('name'=>'Tajiquistão','code'=>'992'),
    'TZA'=>array('name'=>'Tanzânia','code'=>'255'),
    'TLS'=>array('name'=>'Timor-Leste','code'=>'670'),
    'TGO'=>array('name'=>'Togo','code'=>'228'),
    'TON'=>array('name'=>'Tonga','code'=>'676'),
    'TTO'=>array('name'=>'Trindade e Tobago','code'=>'1868'),
    'TUN'=>array('name'=>'Tunísia','code'=>'216'),
    'TCA'=>array('name'=>'Turks and Caicos Islands','code'=>'1649'),
    'TKM'=>array('name'=>'Turquemenistão','code'=>'993'),
    'TUR'=>array('name'=>'Turquia','code'=>'90'),
    'TUV'=>array('name'=>'Tuvalu','code'=>'688'),
    'UKR'=>array('name'=>'Ucrânia','code'=>'380'),
    'UGA'=>array('name'=>'Uganda','code'=>'256'),
    'URY'=>array('name'=>'Uruguai','code'=>'598'),
    'UZB'=>array('name'=>'Usbequistão','code'=>'998'),
    'VUT'=>array('name'=>'Vanuatu','code'=>'678'),
    'VEN'=>array('name'=>'Venezuela','code'=>'58'),
    'VNM'=>array('name'=>'Vietname','code'=>'84'),
    'WLF'=>array('name'=>'Wallis and Futuna','code'=>'681'),
    'ZMB'=>array('name'=>'Zâmbia','code'=>'260'),
    'ZWE'=>array('name'=>'Zimbabwe','code'=>'263')
)));


// INITIALIZE PLUGIN
function run_egoi_for_wp() {

    $plugin = new Egoi_For_Wp();
    $plugin->run();

}
run_egoi_for_wp();
