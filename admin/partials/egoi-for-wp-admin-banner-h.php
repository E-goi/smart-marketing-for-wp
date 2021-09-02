<?php
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

?>


<?php if(rand(1, 2) % 2 == 0){ ?>
    <embed>
        <div class="pub-head">
            <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__).'../css' ?>/egoi-for-wp-pub.css">
        </div>
        <div class="pub-body">
            <div class="pub-wrap-rate pub-clearfix" style="width: 100%; max-width: 800px; padding: 20px">
                <div class="pub-left" style="width: 60%;">
                    <img alt="E-goi" src="<?php echo plugin_dir_url(__FILE__).'../img/pub' ?>/e-goi.png">
                    <h2><?php _e('WooCommerce SMS', 'egoi-for-wp'); ?></h2>
                    <p><?php _e('Send SMS notifications to your buyers and admins for each change to the order status in your WooCommerce store. Increase your conversions and better communicate with your customers.', 'egoi-for-wp'); ?></p>

                    <div style="margin: 40px 0 10px 0;">
                        <a class="button-custom-egoi" href=" https://pt.wordpress.org/plugins/sms-orders-alertnotifications-for-woocommerce/" target="blank">DOWNLOAD »</a>
                    </div>
                </div>
                <div class="pub-right">
                    <img alt="Rating" src="<?php echo plugin_dir_url(__FILE__).'../img' ?>/addon-sms-notification.png" width="250px">
                </div>
            </div>
        </div>
    </embed>
    <?php }else{ ?>


    <div class="pub-head">
        <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__).'../css' ?>/egoi-for-wp-pub.css">
    </div>
    <div class="pub-body">
        <div class="pub-wrap-rate pub-clearfix" style="width: 100%; max-width: 800px; padding: 20px">
            <div class="pub-left" style="width: 60%;">
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
                <img alt="Rating" src="<?php echo plugin_dir_url(__FILE__).'../img/pub' ?>/rate.png" width="250px">
            </div>
        </div>
    </div>
    </embed>

<?php } ?>


