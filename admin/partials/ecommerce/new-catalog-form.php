<form action="#" method="post" id="form-create-catalog">
    <?php wp_nonce_field( 'form-create-catalog' ); ?>
    <input name="form_id" type="hidden" value="form-create-catalog" />
    <input id="default-store-country" type="hidden" value="<?php
    $store_raw_country = get_option( 'woocommerce_default_country' );
    $split_country = explode( ":", $store_raw_country );
    echo $split_country[0];?>" />
    <input id="default-store-currency" type="hidden" value="<?=get_option('woocommerce_currency');?>" />
    <span style="display: block;margin-bottom: 20px;">* <?=__('Create a catalog base on language and currency and start the product\'s synchronization with E-goi.', 'egoi-for-wp');?></span>
    <div class="smsnf-grid">
        <div>
            <div class="smsnf-input-group">
                <label for="catalog_name"><?php _e('Name', 'egoi-for-wp'); ?></label>
                <input  id="catalog_name" type="text"
                        name="catalog_name" size="30" spellcheck="true" autocomplete="off"
                        placeholder="<?= __( "Write here the name of your catalog", 'egoi-for-wp' ); ?>" />
            </div>

            <div class="smsnf-input-group">
                <label for="catalog_language"><?php _e( 'Language', 'egoi-for-wp' ); ?></label>
                <p class="subtitle"><?php _e( 'Select the language your products are setup.' ,'egoi-for-wp' ); ?></p>
                <div class="smsnf-wrapper">
                    <select id="catalog_language" name="catalog_language" class="form-select" >
                        <option value="off" selected><?php _e( 'Select a language..', 'egoi-for-wp' ); ?></option>
                    </select>
                </div>
            </div>

            <div class="smsnf-input-group">
                <label for="catalog_currency"><?php _e( 'Currency', 'egoi-for-wp' ); ?></label>
                <p class="subtitle"><?php _e( 'Select the list to which visitors should be subscribed.' ,'egoi-for-wp' ); ?></p>
                <div class="smsnf-wrapper">
                    <select id="catalog_currency" name="catalog_currency" class="form-select" >
                        <option value="off" selected><?php _e( 'Select a currency..', 'egoi-for-wp' ); ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="smsnf-input-group">
        <input type="submit" id="create_catalog_button" value="<?php _e('Create Catalog', 'egoi-for-wp');?>" />
    </div>

</form>