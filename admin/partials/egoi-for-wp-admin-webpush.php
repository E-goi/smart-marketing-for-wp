<?php

if ( ! defined( 'ABSPATH' ) ) {
    die();
}

if(isset($_POST['action'])){

    if (!get_option('egoi_webpush')) {
        add_option('egoi_webpush', $_POST['egoi_webpush']);
    } else {
        update_option('egoi_webpush', $_POST['egoi_webpush']);
    }

    echo '<div class="e-goi-notice updated notice is-dismissible"><p>';
    _e('Web Push Updated!', 'egoi-for-wp');
    echo '</p></div>';

    $options = get_option('egoi_webpush');

}else{
    $options = get_option('egoi_webpush');
}
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

<div class="error notice is-dismissible">
    <p>
        <?php echo _e('You need a paid plan!', 'egoi-for-wp'); ?>
        <a href="<?php echo $link_money; ?>" target="_blank"><?php echo _e('Link Here', 'egoi-for-wp'); ?></a>
    </p>
</div>

<div class="error notice is-dismissible">
    <p>
        <?php echo _e('You need a Web Push Code!', 'egoi-for-wp'); ?>
        <a href="<?php echo $link_tutorial; ?>" target="_blank"><?php echo _e('Link Here', 'egoi-for-wp'); ?></a>
    </p>
</div>

<div style="margin-top:20px; max-width:80%; padding: 5px 20px 5px;">

    <form method="post" action="#">
        <?php
            settings_fields( Egoi_For_Wp_Admin::OPTION_NAME );
            settings_errors();
        ?>

        <div style="font-size:16px; margin-top:10px;line-height:28px; margin-bottom:10px;"></div>

        <span style="display: inline-block; font-size:16px;">
            <b><?php _e( 'Activate Web Push', 'egoi-for-wp' ); ?></b>
        </span>

        <span style="margin-left:20px; font-size:18px;">
            <input id="yes" type="radio" name="egoi_webpush[track]" <?php checked( $options['track'], 1 ); ?> value="1">
            <label for="yes"><?php _e( 'Yes', 'egoi-for-wp' ); ?></label> &nbsp;

            <input id="no" type="radio" name="egoi_webpush[track]" <?php checked( $options['track'], 0 ); ?> value="0">
            <label for="no"><?php _e( 'No', 'egoi-for-wp' ); ?></label>
        </span>

        <div style="font-size:16px; margin-top:10px;line-height:28px; margin-bottom:10px;"></div>

        <span style="display: inline-block; font-size:16px;">
            <b><?php _e( 'Web Push Code', 'egoi-for-wp' ); ?></b>
        </span>

        <span style="margin-left:20px; font-size:18px;">
            <input class="e-goi-form-title--input" type="text" name="egoi_webpush[cod]" size="40" id="egoi_webpush_cod"
               autocomplete="off" placeholder="<?php echo __( "Write here the code of your Web Push", 'egoi-for-wp' ); ?>"
               required value="<?php echo $options['cod'];?> "/>
        </span>
        <br>
        <button style="margin-top: 20px; margin-bottom: 20px;" class="button-primary"><?php _e( 'Save', 'egoi-for-wp' ); ?></button>
    </form>

</div>