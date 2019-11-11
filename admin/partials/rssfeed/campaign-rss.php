<style>
    .egoi_create_campaign_table{
        display: none;
    }
    .egoi_create_campaign_webpush_table{
        display: none;
    }
</style>
    <?php
        global $wpdb;
        $table = $wpdb->prefix."options";
        $options = $wpdb->get_results( " SELECT * FROM ".$table." WHERE option_name LIKE 'egoi_rssfeed_%' ORDER BY option_id DESC ");
    ?>

<ul class="tab">
    <li class="tab-item active">
        <a href="#" tab-target="smsnf-email"><?= _e( 'E-mail', 'egoi-for-wp' ); ?></a>
    </li>
    <li class="tab-item">
        <a href="#" tab-target="smsnf-webpush"><?= _e( 'Webpush', 'egoi-for-wp' ); ?></a>
    </li>
</ul>

<div id="smsnf-email" class="smsnf-tab-content active">
    <div class="smsnf-grid">
        <div>
            <div class="smsnf-input-group">
                <label for="egoi_add_campaign"><? _e( 'RSS Feed', 'egoi-for-wp' ); ?></label>
                <p class="subtitle"><?php _e( 'Select the feed which will be used in your campaign.' ,'egoi-for-wp' ); ?></p>
                <div class="smsnf-wrapper">
                    <select id="egoi_add_campaign" class="form-select">
                        <option value="0"><?php _e('Select an feed...', 'egoi-for-wp'); ?></option>
                        <?php
                        foreach ($options as $option){
                            $feed = get_option($option->option_name); ?>
                            <option value="<?=$option->option_name; ?>"><?=$feed['name']?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="smsnf-input-group egoi_create_campaign_table">
                <label for="egoi_list"><? _e( 'List', 'egoi-for-wp' ); ?></label>
                <p class="subtitle"><?php _e( 'Select the list which will be linked your campaign.' ,'egoi-for-wp' ); ?></p>
                <div class="smsnf-wrapper">
                    <select id="egoi_list" class="form-select">
                        <option value="0"><?php _e('Select an list...', 'egoi-for-wp'); ?></option>
                    </select>
                    <?=getLoaderNew('egoi_list_loading',true); ?>
                </div>
            </div>

            <div class="smsnf-input-group egoi_create_campaign_table">
                <label for="egoi_senders"><? _e( 'Sender', 'egoi-for-wp' ); ?></label>
                <p class="subtitle"><?php _e( 'Select the sender which will be linked your campaign.' ,'egoi-for-wp' ); ?></p>
                <div class="smsnf-wrapper">
                    <select id="egoi_senders" class="form-select">
                        <option value="0"><?php _e('Select a sender...', 'egoi-for-wp'); ?></option>
                    </select>
                    <?=getLoaderNew('egoi_senders_loading',true); ?>
                </div>
            </div>

            <div class="smsnf-input-group egoi_create_campaign_table">
                <label for="campaign_subject"><?= _e( 'Subject', 'egoi-for-wp' ); ?></label>
                <p class="subtitle"><? _e( 'The text to appear as a Title', 'egoi-for-wp' ); ?></p>
                <input id="campaign_subject" name="campaign_subject" type="text" placeholder="<?= __('Choose a subject for your campaign', 'egoi-for-wp');?>" value="" required autocomplete="off" />
            </div>

            <div class="smsnf-input-group egoi_create_campaign_table">
                <label for="campaign_snippet"><?= _e( 'Snippet', 'egoi-for-wp' ); ?></label>
                <p class="subtitle"><? _e( 'This is the small email\'s resume', 'egoi-for-wp' ); ?></p>
                <input id="campaign_snippet" name="campaign_snippet" type="text" placeholder="<?= __('Choose a snippet for your campaign', 'egoi-for-wp');?>" value="" required autocomplete="off" />
            </div>

            <div class="smsnf-input-group egoi_create_campaign_table">
                <label for="campaign_title"><?= _e( 'Title', 'egoi-for-wp' ); ?></label>
                <p class="subtitle"><? _e( 'This is the campaign title inside', 'egoi-for-wp' ); ?></p>
                <input id="campaign_title" name="campaign_title" type="text" placeholder="<?= __('Choose a title for your newsletter', 'egoi-for-wp');?>" value="" required autocomplete="off" />
            </div>

            <div class="smsnf-input-group egoi_create_campaign_table">
                <label for="items_per_email"><? _e( 'Items per Email', 'egoi-for-wp' ); ?></label>
                <div class="smsnf-wrapper">
                    <div class="input-group number-spinner">
                        <span class="input-group-btn" style="margin-top: 12px;">
                            <button style="height: 100%;" class="btn btn-default" data-dir="dwn"><span class="fas fa-minus"></span></button>
                        </span>
                        <input id="items_per_email" type="text" class="form-control text-center" value="5">
                        <span class="input-group-btn" style="margin-top: 12px;">
                            <button style="height: 100%;" class="btn btn-default" data-dir="up"><span class="fas fa-plus"></span></button>
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="egoi-undertable-button-wrapper" style="bottom: 0;position: absolute;right: 30px;">
        <div class="smsnf-input-group" style="display: flex;flex-direction: row;">
            <input id="egoi_create_campaign" type="submit" value="<? _e('Create RSS Campaign +', 'egoi-for-wp');?>" />
            <?=getLoader('egoi_create_campaign_loading', false) ?>
        </div>
        <div class="smsnf-input-group" style="display: flex;flex-direction: row;">
            <input hidden value="" id="campaign_hash_deploy" />
            <input hidden value="" id="campaign_list_id_deploy" />

            <i class="loading" id="egoi_send_campaign_loading" style="display: none;">x</i>

            <span id="egoi_edit_campaign" class='egoi-span-button' style="display: none;"><?php _e('Edit Campaign in E-goi', 'egoi-for-wp');?></span>

            <span id="egoi_send_campaign" class='egoi-span-button' style="display: none;"><?php _e('Send Campaign >', 'egoi-for-wp');?></span>
            <div id="success_email" class="alert alert-success" role="alert" style="display: none;">
                <i class="form-icon icon icon-check icon-valid text-success" style="margin-right: 30px;"></i>
                <a><?= __('Campaign sent successfully!', 'egoi-for-wp');?></a>

            </div>
        </div>
    </div>

</div>

<div id="smsnf-webpush" class="smsnf-tab-content">
    <?php
    $webpush_code_flag = get_option('egoi_webpush_code');
    if(empty($webpush_code_flag) || empty($webpush_code_flag['code'])){//display warning, webpush needs to be on
        ?>
        <div style="display: flex;justify-content: center;width: 100%;">
            <div style="background-color: #04afdb; color: white; margin: 15px 10px;  padding: 1px 20px; border-radius: 3px;">
                <table width="100%">
                    <tbody><tr>
                        <td>
                            <p style="font-weight:  bold; font-size: 14px;">
                                <?= __('Do you want to make WebPush campaigns?', 'egoi-for-wp'); ?>
                                <br>
                                <?= __('Make sure you have it on!', 'egoi-for-wp'); ?>
                            </p>
                        </td>
                        <td style="min-width: 130px;" align="right">
                            <a href="<?=get_site_url();?>/wp-admin/admin.php?page=egoi-4-wp-webpush" style="font-weight:  bold; font-size: 14px; text-decoration: none; background-color: white; color: #04afdb; padding: 10px 20px; border-radius: 20px;"><?=__('Activate','egoi-for-wp');?></a>
                        </td>
                    </tr>
                    </tbody></table>
            </div>
        </div>
    <?php } else { ?>

    <div class="smsnf-grid">
        <div>
            <div class="smsnf-input-group">
                <label for="egoi_add_campaign_webpush"><? _e( 'RSS Feed', 'egoi-for-wp' ); ?></label>
                <p class="subtitle"><?php _e( 'Select the feed which will be used in your campaign.' ,'egoi-for-wp' ); ?></p>
                <div class="smsnf-wrapper">
                    <select id="egoi_add_campaign_webpush" class="form-select">
                        <option value="0"><?php _e('Select an feed...', 'egoi-for-wp'); ?></option>
                        <?php
                        foreach ($options as $option){
                            $feed = get_option($option->option_name); ?>
                            <option value="<?=$option->option_name; ?>"><?=$feed['name']?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="smsnf-input-group egoi_create_campaign_webpush_table">
                <label for="campaign_title_webpush"><?= _e( 'Title', 'egoi-for-wp' ); ?></label>
                <p class="subtitle"><? _e( 'This is the campaign title inside', 'egoi-for-wp' ); ?></p>
                <input id="campaign_title_webpush" name="campaign_title_webpush" type="text" placeholder="<?= __('Choose a title for your newsletter', 'egoi-for-wp');?>" value="" required autocomplete="off" />
            </div>

        </div>
    </div>

    <div class="egoi-undertable-button-wrapper" style="bottom: 0;position: absolute;right: 30px;">
        <div class="smsnf-input-group" style="display: flex;flex-direction: row;">
            <?=getLoader('egoi_create_campaign_webpush_loading', false) ?>
            <input id="egoi_create_campaign_webpush" type="submit" value="<? _e('Create RSS Campaign +', 'egoi-for-wp');?>" />
        </div>
        <div class="smsnf-input-group" style="display: flex;flex-direction: row;">
            <input hidden value="" id="campaign_hash_deploy_webpush" />
            <input hidden value="" id="campaign_list_id_deploy_webpush" />

            <i class="loading" id="egoi_send_campaign_webpush_loading" style="display: none;">x</i>

            <span id="egoi_edit_campaign_webpush" class='egoi-span-button' style="display: none;"><?php _e('Edit Campaign in E-goi', 'egoi-for-wp');?></span>

            <span id="egoi_send_campaign_webpush" class='egoi-span-button' style="display: none;"><?php _e('Send Campaign >', 'egoi-for-wp');?></span>
            <div id="success_webpush" class="alert alert-success" role="alert" style="display: none;">
                <i class="form-icon icon icon-check icon-valid text-success" style="margin-right: 30px;"></i>
                <a><?= __('Campaign sent successfully!', 'egoi-for-wp');?></a>

            </div>
        </div>
    </div>

    <?php } ?>
</div>
