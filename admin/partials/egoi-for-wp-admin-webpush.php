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

$error = $ok = 0;
if(isset($_POST['action'])){
    if (isset($_POST['egoi_webpush']['code'])) {  // Save web push code
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
}
$options = get_option('egoi_webpush_code');

$locale = get_locale();

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
?>

<!-- head -->
<h1 class="logo">Smart Marketing - <?php _e( 'Web Push', 'egoi-for-wp' ); ?></h1>
<p class="breadcrumbs">
    <span class="prefix"><?php echo __( 'You are here: ', 'egoi-for-wp' ); ?></span>
    <strong>Smart Marketing</a> &rsaquo;
        <span class="current-crumb"><?php _e( 'Web Push', 'egoi-for-wp' ); ?></strong></span>
</p>
<hr/>

<table width="100%">
    <tr>
        <td valign="top">
            <!-- Web push code form AND tutorial how to get web push code -->
            <div class='wrap-content' id="wrap--acoount">
                <div class="main-content">
                    <div class="wrap-content--webpush">
                        <div>
                            <form id='form_webpush_code' method='post' action="">
                                <?php
                                settings_fields( Egoi_For_Wp_Admin::OPTION_NAME );
                                settings_errors();
                                ?>

                                <div class="e-goi-account-apikey">
                                    <!-- Title -->
                                    <div class="e-goi-account-apikey--title" for="egoi_wp_apikey">
                                        <?php echo _e('Insert here the Web Push code', 'egoi-for-wp');?>
                                    </div>

                                    <!-- API key and btn -->
                                    <div class="e-goi-account-apikey--grp" style="padding-bottom: 4px;">

                                        <?php if (!isset($options['code']) || (isset($_POST['egoi_webpush']['code']) && $error == 1) ) { // if not have a valid web push ?>

                                            <input type="text" class="e-goi-account-apikey--grp--form__input" name="egoi_webpush[code]" size="55"
                                               autocomplete="off" placeholder="<?php echo __( "Paste here the Web Push code", 'egoi-for-wp' ); ?>"
                                               required pattern="[a-zA-Z0-9]+" value="<?php echo $_POST['egoi_webpush']['code'];?>" spellcheck="false" autofocus
                                                <?php echo $error ? 'style="border-color: #ed000e;"' : null; ?>
                                            />

                                            <span id="save_webpush" class="button-primary button-primary--custom" >
                                                <?php echo __('Save', 'egoi-for-wp');?>
                                            </span>

                                        <?php } else { ?>

                                            <span class="e-goi-account-apikey--grp--form" id="webpush_span" style="width: 457px; <?php echo $ok ? "border-color: green;" : null; ?>" >
                                                <?php echo $options['code']; ?>
                                            </span>

                                            <a type="button" id="edit_webpush" class="button button--custom">
                                                <?php echo __('Edit', 'egoi-for-wp');?>
                                            </a>

                                            <input type="text" class="e-goi-account-apikey--grp--form__input" name="egoi_webpush[code]" size="55" id="egoi_webpush_cod"
                                                   autocomplete="off" placeholder="<?php echo __( "Paste here the Web Push code", 'egoi-for-wp' ); ?>"
                                                   required pattern="[a-zA-Z0-9]+" value="<?php echo $options['code'];?>" spellcheck="false" autofocus
                                                 style="display: none;"
                                            />
                                            <span id="save_webpush" class="button-primary button-primary--custom" style="display: none;">
                                                <?php echo __('Save', 'egoi-for-wp');?>
                                            </span>

                                        <?php } ?>

                                    </div>

                                    <?php if($ok) { ?>
                                        <div>
                                            <span style="color: green;"><?php echo __('Code is valid', 'egoi-for-wp');?></span>
                                        </div>
                                    <?php } else if($error) { ?>
                                        <div>
                                            <span style="color: #ed000e;"><?php echo __('Code is invalid', 'egoi-for-wp');?></span>
                                        </div>
                                    <?php } else { ?>
                                        <div>
                                            <span style="padding: 10px;"> </span>
                                        </div>
                                    <?php } ?>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- How to get web push code -->
            <div class='wrap-content' id="wrap--acoount" style="margin-top: 0px;">
                <div class="main-content">
                    <div class="wrap-content--webpush" style="background-color: #f9f9f9;">
                        <div class="e-goi-account-apikey--link--account-settings help-list-style" style="margin-left: 10px;">
                            <p style="font-size: 17px;"><?php _e('How to get the Web Push Code?', 'egoi-for-wp');?></p>
                            <ol style="font-size: 13px;">
                                <li>
                                    <?php _e('Do', 'egoi-for-wp');?>
                                    <b><?php _e('LOGIN', 'egoi-for-wp');?></b>
                                    <?php _e('in to your account and click on the top menu item', 'egoi-for-wp');?>
                                    <b><?php _e('APPS > PUSH APPS', 'egoi-for-wp');?></b>
                                </li>
                                <li>
                                    <?php _e('Click', 'egoi-for-wp');?>
                                    <b><?php _e('ADD APP > WEB PUSH', 'egoi-for-wp');?></b>
                                    <?php _e('button and fill in the initial settings', 'egoi-for-wp');?>
                                </li>
                                <li>
                                    <?php _e('At the bottom of the page click the tab labeled "Code" and', 'egoi-for-wp');?>
                                    <b><?php _e('COPY', 'egoi-for-wp');?></b>
                                    <?php _e('the code that is in the variable:', 'egoi-for-wp');?>
                                    <b>_egoiwp.code</b>
                                </li>
                            </ol>

                            <div style="margin-top: 30px;"><?php _e('Example image', 'egoi-for-wp'); ?></div>
                            <?php $img = plugins_url().'/smart-marketing-for-wp/admin/img/webpushcode.png'; ?>
                            <img src="<?=$img?>" style="max-width: 480px; display: inline-block; padding: 10px; background-color: white; border: 1px solid #e5e5e5; margin: 4px 0 20px 0;">

                        </div>
                    </div>
                </div>
            </div>
            <!-- Web push switch -->
            <?php if (isset($options['code'])) { ?>
                <div style="padding: 5px 5px 5px;">
                    <form id='form_webpush_switch' method='post' action="">
                        <?php
                        settings_fields( Egoi_For_Wp_Admin::OPTION_NAME );
                        settings_errors();
                        ?>
                        <table class="form-table">
                            <tr>
                                <th scope="row" style="min-width: 200px;"><?php _e( 'Activate Web Push', 'egoi-for-wp' ); ?></th>
                                <td class="nowrap">
                                    <label><input id="yes" type="radio" name="egoi_webpush[track]" <?php checked( $options['track'], 1 ); ?> value="1" required><?php _e( 'Yes', 'egoi-for-wp' ); ?></label> &nbsp;
                                    <label><input id="no" type="radio" name="egoi_webpush[track]" <?php checked( $options['track'], 0 ); ?> value="0"><?php _e( 'No', 'egoi-for-wp' ); ?></label>
                                </td>
                            </tr>
                        </table>
                        <?php submit_button(); ?>
                    </form>
                </div>
            <?php } ?>
        </td>
        <td valign="top">
            <!-- How to activate web push code in E-goi -->
            <div class='wrap-content' id="wrap--acoount" style="margin-bottom: 10px;">
                <div class="main-content">
                    <div class="wrap-content--webpush" style="padding: 5px 15px;">
                        <div>
                            <div style="background-color: #04afdb; color: white; margin: 15px 10px;  padding: 1px 20px; border-radius: 3px;">
                                <p style="font-weight:  bold; font-size: 14px;"><?php _e('Want to use E-goi\'s Web Push without limits?', 'egoi-for-wp'); ?>
                                <br><?php _e('Join an Unlimited Shipping Cloth.', 'egoi-for-wp'); ?>
                                    <a href="<?=$link_price?>" target="_blank" style="float: right; margin-top: -20px; text-decoration: none; background-color: white; color: #04afdb; padding: 10px 20px; border-radius: 20px;"><?php _e('Join Plane','egoi-for-wp'); ?></a></p>
                            </div>
                            <div style="margin-left: 10px;">
                                <p>
                                    <?php _e('Integrate', 'egoi-for-wp'); ?>
                                    <b><?php _e('Web Push Notification with your site', 'egoi-for-wp'); ?></b>
                                    <?php _e('to alert your Customers or Followers, with Instant Messaging, directly to your Browser, even if they are browsing another site.', 'egoi-for-wp'); ?>
                                    <a href="<?=$link_learn?>" target="_blank"><?php _e('Learn more', 'egoi-for-wp'); ?></a>
                                </p>

                                <?php $img = plugins_url().'/smart-marketing-for-wp/admin/img/webpushpage.jpg'; ?>
                                <div style="padding: 0 12px 20px 0;">
                                    <img src="<?=$img?>" style="width: 100%; height: auto;">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <span>
            <?php _e('Do not know how to create a Web Push in E-goi? Learn how to do it', 'egoi-for-wp'); ?>
                <a href="<?=$link_help?>" target="_blank"><?php _e('here', 'egoi-for-wp'); ?></a>
            </span>
        </td>
    </tr>
</table>




<?php $js_dir = plugins_url().'/smart-marketing-for-wp/admin/js/egoi-for-wp-webpush.js'; ?>
<script src="<?=$js_dir?>"></script>