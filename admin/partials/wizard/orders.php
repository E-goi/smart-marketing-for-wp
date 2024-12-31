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
                <div class="form-group switch-yes-no" style="margin-top: 10px;">
                    <label class="form-switch">
                        <input id="orders_back" type="radio" name="egoi_sync[backend_order_sync]" value="true"
                               checked>
                        <i class="form-icon"></i>
                        <div class="yes">
                            <?php _e('Yes', 'egoi-for-wp'); ?>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Option to sync by script -->
            <div class="smsnf-input-group">
                <label for="order_sync_script">· <?php _e('Synchronize via Script', 'egoi-for-wp'); ?></label>
                <p class="subtitle"><?php _e('Uses a script to process cart synchronization.', 'egoi-for-wp'); ?></p>
                <div class="form-group switch-yes-no">
                    <label class="form-switch">
                        <input id="orders_script" type="radio" name="egoi_sync[backend_order_sync]" value="false">
                        <i class="form-icon"></i>
                        <div class="yes"><?php _e('Yes', 'egoi-for-wp'); ?></div>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
