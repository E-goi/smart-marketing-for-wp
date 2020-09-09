<?php

if ( ! defined( 'ABSPATH' ) ) {
    die();
}
$dir = plugin_dir_path(__FILE__) . 'capture/';

include_once $dir . '/functions.php';
require_once plugin_dir_path(__FILE__) . 'egoi-for-wp-common.php';

$Egoi4WP = get_option('Egoi4WpBuilderObject');
$lists = $Egoi4WP->getLists();

function webpushValidator($cod) {
    if (preg_match("/^[A-Za-z0-9_-]*$/", $cod)) {
        $url = 'https://egoiapp2.com/wp/files/' . filter_var($cod, FILTER_SANITIZE_STRING) ;
        if(function_exists('wp_remote_request')) {

            $res = wp_remote_request($url,
                array(
                    'method' => 'GET',
                    'timeout' => 30,
                )
            );

            if ( $res['response']['code'] != 200) {
                return false;
            }

            return true;
        }else{
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            curl_exec($ch);

            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code != 200) {
                return false;
            }
            return true;
        }
    } else {
        return false;
    }
}

if (!empty($_POST) && $_POST['form_id'] == 'create-webpush-form' ) {
    check_admin_referer($_POST['form_id']);
}

$redir = false;
if (!empty($_GET['sub']) && $_GET['sub'] === 'create-wp' && !empty($_POST['create_wp_form'])) {
    $apikey = $this->get_apikey();
    $api = new EgoiApiV3($apikey);

    $data = array(
        'site' => get_site_url(),
        'list_id' => $_POST['create_wp_form']['list'],
        'name' => $_POST['create_wp_form']['label']
    );

    $_POST['egoi_webpush']['code'] = $api->createWebPushSite($data);

    if (json_decode($_POST['egoi_webpush']['code'])) {
        if (strpos($_POST['egoi_webpush']['code'], ' ') !== false) {
            echo get_notification(__('Error!', 'egoi-for-wp'), __('Web Push name already exists!', 'egoi-for-wp'), 'error');
        } else {
            echo get_notification(__('Error!', 'egoi-for-wp'), __('Error creating Web Push site!', 'egoi-for-wp'), 'error');
        }
        unset($_POST['action']);
    } else {
        unset($_GET['sub']);
        $redir = true;
    }
}

$error = $ok = 0;
if(isset($_POST['action'])){
    if (isset($_POST['egoi_webpush']['code'])) {  // Save web push code
        $_POST['egoi_webpush']['code'] = filter_var($_POST['egoi_webpush']['code'], FILTER_SANITIZE_STRING);
        if (webpushValidator($_POST['egoi_webpush']['code'])) { // valid web push
            if (!$options = get_option('egoi_webpush_code')) {
                $_POST['egoi_webpush']['track'] = 1;
                add_option('egoi_webpush_code', $_POST['egoi_webpush']);
            } else {
                $_POST['egoi_webpush']['track'] = $options['track'];
                update_option('egoi_webpush_code', $_POST['egoi_webpush']);
            }
            $ok = 1;
        } else { // invalid web push
            $error = 1;
        }

    } else if (isset($_POST['egoi_webpush']['track'])) {  // switch on/off web push

        $options = get_option('egoi_webpush_code');
        $options['track'] = $_POST['egoi_webpush']['track'];
        update_option('egoi_webpush_code', $options);

    }

    echo get_notification(__('Success!', 'egoi-for-wp'), __('Ecommerce Option Updated!', 'egoi-for-wp'));

}
$options = get_option('egoi_webpush_code');

$locale = get_locale();

$page = array(
    'home' => !isset($_GET['sub']),
    'create-wp' => $_GET['sub'] == 'create-wp',
);

if (strpos($locale, 'pt') !== false) {
    $link_price = 'https://www.e-goi.pt/precos';
    $link_learn = 'https://www.e-goi.pt/notificacoes-web-push';
    $link_help = 'https://helpdesk.e-goi.com/765004-Criar-web-push';
} else if (strpos($locale, 'es') !== false) {
    $link_price = 'https://www.e-goi.es/precios';
    $link_learn = 'https://www.e-goi.es/notificaciones-web-push';
    $link_help = 'https://helpdesk.e-goi.com/092775-Crear-mensaje-web-push';
} else {
    $link_price = 'https://www.e-goi.com/pricing';
    $link_learn = 'https://www.e-goi.com/web-push-notifications/';
    $link_help = 'https://helpdesk.e-goi.com/135733-Creating-a-web-push-message';
}

if ($redir) {
    echo '<script>window.location.search = "?page=egoi-4-wp-webpush"</script>';
    exit;
}
?>

<div class="smsnf">
    <div class="smsnf-modal-bg"></div>
    <!-- Header -->
    <header>
        <div class="wrapper-loader-egoi">
            <h1>Smart Marketing > <b><?php _e( 'Web Push', 'egoi-for-wp' ); ?></b></h1>
            <?=getLoader('egoi-loader',false)?>
        </div>
        <nav>
            <ul>
                <li><a class="home <?= $page['home'] ?'-select':'' ?>" href="?page=egoi-4-wp-webpush"><?php _e('Configuration', 'egoi-for-wp'); ?></a></li>
                <li><a class="home <?= $page['create-wp'] ?'-select':'' ?>" href="?page=egoi-4-wp-webpush&sub=create-wp"><?php _e('Create Web Push', 'egoi-for-wp'); ?></a></li>
            </ul>
        </nav>
    </header>
    <!-- / Header -->
    <!-- Content -->
    <main style="grid-template-columns: 4fr 3fr !important;">

        <?php

        if(isset($_GET['sub']) && $_GET['sub'] === 'create-wp'){
            require_once plugin_dir_path(__FILE__) . 'webpush/create-wp.php';
        }else{
            require_once plugin_dir_path(__FILE__) . 'webpush/home.php';
        }

        ?>

    </main>
</div>

<?php $js_dir = plugins_url().'/smart-marketing-for-wp/admin/js/egoi-for-wp-webpush.js'; ?>
<script src="<?=$js_dir?>"></script>