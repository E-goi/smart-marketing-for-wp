<?php
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

add_thickbox();

?>

<div class="egoi-box" style="background-color: transparent; padding: 0;width:250px;">
    <?php 
        require_once dirname(__DIR__).'/partials/egoi-for-wp-admin-alert.php';
        if(!empty($alert))
            echo $alert;
    ?>

<?php if(rand(1, 2) % 2 == 0){ ?>
    <embed>
        <div class="pub-body">
            <div class="pub-wrap-rate pub-clearfix" style="width: 250px; padding: 10px">
                <div class="pub-left" style="width: 100%;">
                    <img alt="E-goi" src="<?php echo plugin_dir_url(__FILE__).'../img/pub' ?>/e-goi.png">
                    <h2><?php _e('Want a discount?', 'egoi-for-wp'); ?></h2>
                    <p><?php _e('Write a review of our plugin and send a screenshot of your comment to the email:', 'egoi-for-wp'); ?>
                        <a href="mailto:wordpress@e-goi.com">wordpress@e-goi.com</a>
                    </p>
                    <div style="margin: 40px 0 10px 0;">
                        <a class="button-custom-egoi" href="https://wordpress.org/support/plugin/smart-marketing-for-wp/reviews/?filter=5" target="blank">START HERE »</a>
                    </div>
                </div>
                <div class="pub-right">
                    <img alt="Rating" src="<?php echo plugin_dir_url(__FILE__).'../img/pub' ?>/rate.png" width="200px">
                </div>
            </div>
        </div>
    </embed>
    <?php }else{ ?>

    <embed>
        <div class="pub-body">
            <div class="pub-wrap-rate pub-clearfix" style="width: 250px; padding: 10px">
                <div class="pub-left" style="width: 100%;">
                    <img alt="E-goi" src="<?php echo plugin_dir_url(__FILE__).'../img/pub' ?>/e-goi.png">
                    <h2><?php _e('WooCommerce SMS', 'egoi-for-wp'); ?></h2>
                    <p><?php _e('Send SMS notifications to your buyers and admins for each change to the order status in your WooCommerce store. Increase your conversions and better communicate with your customers.', 'egoi-for-wp'); ?></p>
                    <div style="margin: 40px 0 10px 0;">
                        <a class="button-custom-egoi" href=" https://pt.wordpress.org/plugins/sms-orders-alertnotifications-for-woocommerce/" target="blank">DOWNLOAD »</a>
                    </div>
                </div>
                <div class="pub-right">
                    <img alt="Rating" src="<?php echo plugin_dir_url(__FILE__).'../img' ?>/addon-sms-notification.png" width="200px">
                </div>
            </div>
        </div>
    </embed>

    <?php } ?>
    <!-- <iframe id="iframe" src="https://eg.e-goi.com/pluginbanners/wp-iframe.php?type=v&lang=<?php echo get_locale(); ?>" height="480px" style="max-width: 100%;" ></iframe> -->
</div>


