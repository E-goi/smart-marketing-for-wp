<?php

if ( ! defined( 'ABSPATH' ) ) {
    die();
}

function webpushValidator($cod) {

    $ch = curl_init('https://egoiapp2.com/wp/files/'.$cod);
    curl_setopt($ch,  CURLOPT_RETURNTRANSFER, TRUE);

    curl_exec($ch);

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if($http_code != 200) {
        return false;
    }
    return true;
}

if(isset($_POST['action'])){
    if (
        ($_POST['egoi_webpush']['track'] == 1 || $_POST['egoi_webpush']['track'] == 0) &&
        (trim($_POST['egoi_webpush']['cod']) != '')
    ) {
        if (webpushValidator($_POST['egoi_webpush']['cod'])) {
            if (!get_option('egoi_webpush')) {
                add_option('egoi_webpush', $_POST['egoi_webpush']);
            } else {
                update_option('egoi_webpush', $_POST['egoi_webpush']);
            }

            echo '<div class="e-goi-notice updated notice is-dismissible"><p>';
            _e('Web Push Updated!', 'egoi-for-wp');
            echo '</p></div>';
        } else {
            echo '<div class="error notice is-dismissible"><p>';
            _e('Invalid Web Push Code!', 'egoi-for-wp');
            echo '</p></div>';
        }

    } else {
        echo '<div class="error notice is-dismissible"><p>';
        _e('ERROR!', 'egoi-for-wp');
        echo '</p></div>';
    }
}
$options = get_option('egoi_webpush');
?>

<h1 class="logo">Smart Marketing - <?php _e( 'Web Push', 'egoi-for-wp' ); ?></h1>
<p class="breadcrumbs">
    <span class="prefix"><?php echo __( 'You are here: ', 'egoi-for-wp' ); ?></span>
    <strong>Smart Marketing</a> &rsaquo;
        <span class="current-crumb"><?php _e( 'Web Push', 'egoi-for-wp' ); ?></strong></span>
</p>
<hr/>

<?php
    $locale = get_locale();

    if (strpos($locale, 'pt') !== false) {
        $link_money = 'https://www.e-goi.pt/precos/';
        $link_tutorial = 'https://helpdesk.e-goi.com/765004-Criar-web-push';
    } else if (strpos($locale, 'es') !== false) {
        $link_money = 'https://www.e-goi.pt/precos/';
        $link_tutorial = 'https://helpdesk.e-goi.com/092775-Crear-mensaje-web-push';
    } else {
        $link_money = 'https://www.e-goi.pt/precos/';
        $link_tutorial = 'https://helpdesk.e-goi.com/135733-Creating-a-web-push-message';
    }
?>

<form method="post" action="#">

    <div class='wrap-content' id="wrap--acoount">
        <div class="main-content">
            <div class="wrap-content--API">
                <div>
                    <?php
                    settings_fields( Egoi_For_Wp_Admin::OPTION_NAME );
                    settings_errors();
                    ?>

                    <div class="e-goi-account-apikey">
                        <!-- Title -->
                        <div class="e-goi-account-apikey--title" for="egoi_wp_apikey">
                            <?php echo _e('Web Push Code', 'egoi-for-wp');?>
                        </div>

                        <div class="e-goi-account-apikey--grp">
                            <input type="text" class="e-goi-account-apikey--grp--form__input" autofocus name="egoi_webpush[cod]" size="40" id="egoi_webpush_cod"
                                   autocomplete="off" placeholder="<?php echo __( "Write here the code of your Web Push", 'egoi-for-wp' ); ?>"
                                   required pattern="[a-zA-Z0-9\s]+" value="<?php echo $options['cod'];?> "/>
                        </div>

                        <div style="color: #a6a4a2;">
                            <i class="fas fa-question-circle"></i>
                            <?php _e('To get your web push code, just click on the menu "Push Apps > Web Push > Create/Edit > Code" of your account' ,'egoi-for-wp'); ?>
                            <a href="https://login.egoiapp.com/#/login" target="_blank">E-goi</a>
                            <?php _e('and copy what is in:', 'egoi-for-wp'); ?>
                            _egoiwp.code= "XxXxXxXxXxXxXxXxXxXxXxXxXx"
                        </div>
                    </div>
                    <hr>
                    <div class="e-goi-account-apikey--link--account-settings" style="margin-left: 10px;">
                        <?php _e('In order to use E-goi web push you will need to join a paid plan.','egoi-for-wp'); ?>
                        <a href="<?=$link_money?>" target="_blank">
                            <?php echo __(' Learn more here!', 'egoi-for-wp');?>
                        </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="max-width:80%; padding: 5px 5px 5px;">

            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e( 'Activate Web Push', 'egoi-for-wp' ); ?></th>
                    <td class="nowrap">
                        <label><input id="yes" type="radio" name="egoi_webpush[track]" <?php checked( $options['track'], 1 ); ?> value="1" required><?php _e( 'Yes', 'egoi-for-wp' ); ?></label> &nbsp;
                        <label><input id="no" type="radio" name="egoi_webpush[track]" <?php checked( $options['track'], 0 ); ?> value="0"><?php _e( 'No', 'egoi-for-wp' ); ?></label>
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>

    </div>

</form>