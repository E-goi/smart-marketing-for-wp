<?php

if ( ! defined( 'ABSPATH' ) ) {
    die();
}
$dir = plugin_dir_path(__FILE__) . 'capture/';

include_once $dir . '/functions.php';
require_once plugin_dir_path(__FILE__) . 'egoi-for-wp-common.php';
$page = array(
    'home' => !isset($_GET['subpage']),
);
if(isset($_POST['action'])){

    $post = $_POST;
    $post['egoi_sync']['social_track_json'] = $post['egoi_sync']['social_track'] ? $post['egoi_sync']['social_track_json'] : 0;
    $options = array_merge($this->options_list, $post['egoi_sync']);
    if(!empty($post['egoi_sync']['social_track'])){
        $apikey = $this->get_apikey();
        if(!empty($apikey)){
            $api = new EgoiApiV3($apikey);

            $social_track_id = $api->updateSocialTrack('create');
            
            if(!empty($social_track_id)){
                $options = array_merge($options, ['social_track_id' => $social_track_id]);
            }else{
                $options['social_track'] = $options['social_track_json'] = 0;                
            }
        }
    }

    update_option('egoi_sync', $options);
    
    if($post['egoi_sync']['social_track'] && !$options['social_track']){    
        echo get_notification(__('Error', 'egoi-for-wp'), __('Something went wrong while retrieving remarketing configuration, please try again later.', 'egoi-for-wp'));
    }else{    
        echo get_notification(__('Success!', 'egoi-for-wp'), __('Ecommerce Option Updated!', 'egoi-for-wp'));
    }

	$options = get_option('egoi_sync');
}else{
	$options = $this->options_list;
}
?>

<div class="smsnf">
    <div class="smsnf-modal-bg"></div>
    <!-- Header -->
    <header>
        <div class="wrapper-loader-egoi">
            <h1>Smart Marketing > <b><?php _e( 'Track&Engage', 'egoi-for-wp' ); ?></b></h1>
            <?=getLoader('egoi-loader',false)?>
        </div>
        <nav>
            <ul>
                <li><a class="home <?= $page['home'] ?'-select':'' ?>" href="?page=egoi-4-wp-trackengage"><?php _e('Configuration', 'egoi-for-wp'); ?></a></li>
            </ul>
        </nav>
    </header>
    <!-- / Header -->
    <!-- Content -->
    <main>
        <!-- Content -->
        <section class="smsnf-content">

            <?php

            if(!$options['list']) { ?>
                <div class="postbox" style="margin-bottom:20px; padding:5px 20px 5px; border-left:2px solid red;">
                    <div style="padding:10px 0;">
                        <span style="color: orangered; margin-top:5px;" class="dashicons dashicons-warning"></span>
                        <span style="display: inline-block; line-height: 22px; font-size: 13px; margin-left: 12px; margin-top: 3px;">
                        <?php
                        _e('Select your mailing list in the option "Synchronize users with this list" to activate Track & Engage.<br>You will find this option in ', 'egoi-for-wp');
                        ?>
                        <a href="<?php echo $this->protocol . $_SERVER['SERVER_NAME'] . $this->port;?>/wp-admin/admin.php?page=egoi-4-wp-subscribers">
                            <?php _e('Sync Contacts', 'egoi-for-wp'); ?></a>
                        </span>
                    </div>
                </div><?php
            } ?>


            <div style="padding: 5px 20px 5px;">

                <div>
                    <h1><?php _e( 'Track&Engage', 'egoi-for-wp' ); ?></h1>
                </div>

                <div>
                    <span style="padding:15px 0;font-size: 13px;display: inline-block;">
                        <span style="display: inline-block; text-align: justify;     font-size: 13px;"><?php _e('Track & Engage is an E-goi analytics feature, connected to your Wordpress Website or WooCommerce Store, perfect for remarketing actions like returning users or abandoned cart.<p><span style="font-size: 13px;">Activate this option here, and confirm if Track & Engage is also active in E-goi Platform (Web -> Track & Engage).</span></p><p><span style="font-size: 13px;">To know more about the feature Track & Engage, check <a target="_blank" href="https://helpdesk.e-goi.com/416945-Using-Track--Engage-to-track-subscribers-across-my-site">here</a>.</span></p>', 'egoi-for-wp'); ?>
                        </span>
                    </span>
                </div>

                <?php
                if($options['list']) { ?>

                    <form method="post" action="#"><?php

                        settings_fields( Egoi_For_Wp_Admin::OPTION_NAME );
                        settings_errors(); ?>

                        <div class="smsnf-input-group">
                            <label for="egoi_sync[track]"><?php _e( 'Activate Track&Engage', 'egoi-for-wp' ); ?></label>
                            <div class="smsnf-wrapper" style="display: flex;align-items: flex-end;margin-top: 12px;">
                                <label><input type="radio"  name="egoi_sync[track]" <?php checked( $options['track'], 1 ); ?> value="1"><?php _e( 'Yes', 'egoi-for-wp' ); ?></label> &nbsp;
                                <label><input type="radio" name="egoi_sync[track]" <?php checked( $options['track'], 0 ); ?> value="0"><?php _e( 'No', 'egoi-for-wp' ); ?></label>
                            </div>
                        </div>

                        <div class="smsnf-input-group">
                            <label for="egoi_sync[social_track]"><?php _e( 'Activate Remarketing', 'egoi-for-wp' ); ?></label>
                            <p class="subtitle"><?php _e( 'Enable this if you would like to target your website audience using E-goi Ads. <a target="_blank" href="https://helpdesk.e-goi.com/185705-Creating-E-goi-remarketing-Ads">Learn more</a>', 'egoi-for-wp' ); ?></p>
                            <div class="smsnf-wrapper" style="display: flex;align-items: flex-end;margin-top: 12px;">
                                <label><input type="radio" id="egoi_track_social" class="egoi_json_trigger" name="egoi_sync[social_track]" <?php checked( $options['social_track'], 1 ); ?> value="1"><?php _e( 'Yes', 'egoi-for-wp' ); ?></label> &nbsp;
                                <label><input type="radio" class="egoi_json_trigger" name="egoi_sync[social_track]" <?php checked( $options['social_track'], 0 ); ?> value="0"><?php _e( 'No', 'egoi-for-wp' ); ?></label>
                            </div>
                        </div>

                        <div id="egoi_track_json" class="smsnf-input-group" <?php if(!$options['social_track']) echo "style='display: none'" ?>>
                            <label for="egoi_sync[social_track_json]"><?php _e( 'Activate Product Microdata', 'egoi-for-wp' ); ?></label>
                            <p class="subtitle"><?php _e( 'Enable this to improve your products SEO and create dynamic product ads. (Insert the SKU, GTIN and Brand for correct synchronization in Products > Edit Product > Inventory)', 'egoi-for-wp' ); ?></p>
                            <div class="smsnf-wrapper" style="display: flex;align-items: flex-end;margin-top: 12px;">
                                <label><input type="radio" name="egoi_sync[social_track_json]" <?php checked( $options['social_track_json'], 1 ); ?> value="1"><?php _e( 'Yes', 'egoi-for-wp' ); ?></label> &nbsp;
                                <label><input type="radio" name="egoi_sync[social_track_json]" <?php checked( $options['social_track_json'], 0 ); ?> value="0"><?php _e( 'No', 'egoi-for-wp' ); ?></label>
                            </div>
                        </div>

                        <div class="egoi-undertable-button-wrapper" style="bottom: 0;position: absolute;right: 30px;">
                            <div class="smsnf-input-group">
                                <input type="submit" value="<?php _e('Save', 'egoi-for-wp');?>" />
                            </div>
                        </div>

                    </form><?php

                } ?>

            </div>

        </section>

        <section class="smsnf-pub">
            <div>
                <?php include ('egoi-for-wp-admin-banner.php'); ?>
            </div>
        </section>
            <!-- / Content -->
    </main>
</div>