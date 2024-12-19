<div class="tab-pane fade" id="v-pills-orders" role="tabpanel" aria-labelledby="v-pills-orders-tab">
    <p><?php _e('Choose how orders are synchronized.', 'egoi-for-wp'); ?></p>
    <div class="smsnf-grid">
        <div>
            <!-- Option to sync by backend -->
            <div class="smsnf-input-group">
                <label for="order_sync_backend">· <?php _e('Synchronize via Backend (Recommended)', 'egoi-for-wp'); ?></label>
                <p class="subtitle">
                    <?php _e('Uses the backend to process order synchronization.', 'egoi-for-wp'); ?>
                    <?php _e('Includes all order statuses.', 'egoi-for-wp'); ?>
                </p>
                <div class="alert-warning"
                     style="background-color: #fffbe5; border: 1px solid #ffe58a; padding: 10px; border-radius: 4px; margin-top: 10px;">
                    <strong><?php _e('Attention:', 'egoi-for-wp'); ?></strong>
                    <?php _e('The configuration must be completed on the plugin advanced settings page', 'egoi-for-wp'); ?>
                </div>
                <div class="form-group switch-yes-no" style="margin-top: 10px;">
                    <label class="form-switch">
                        <input id="orders" type="radio" name="egoi_sync[order_sync_method]" value="backend" checked>
                        <i class="form-icon"></i>
                        <div class="yes">
                            <?php _e('Yes', 'egoi-for-wp'); ?>
                            <span style="font-size: small;font-weight: 100;color: black;opacity: 50%">
                    (<?php _e('Recommended', 'egoi-for-wp'); ?>)
                </span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Option to sync by script -->
            <div class="smsnf-input-group">
                <label for="order">· <?php _e('Synchronize via Script', 'egoi-for-wp'); ?></label>
                <p class="subtitle"><?php _e('Uses a script to process order synchronization.', 'egoi-for-wp'); ?></p>
                <div class="form-group switch-yes-no">
                    <label class="form-switch">
                        <input id="orders" type="radio" name="egoi_sync[order_sync_method]" value="script">
                        <i class="form-icon"></i>
                        <div class="yes"><?php _e('Yes', 'egoi-for-wp'); ?></div>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>




