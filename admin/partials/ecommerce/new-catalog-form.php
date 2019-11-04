<form action="#" method="post" id="form-create-catalog">
    <?php wp_nonce_field( 'form-create-catalog' ); ?>
    <input name="form_id" type="hidden" value="form-create-catalog" />
    <input id="default-store-country" type="hidden" value="<?php
    $store_raw_country = get_option( 'woocommerce_default_country' );
    $split_country = explode( ":", $store_raw_country );
    echo $split_country[0];?>" />
    <input id="default-store-currency" type="hidden" value="<?=get_option('woocommerce_currency');?>" />
    <table class="form form-table">
        <tbody>
        <tr>
            <th>
                <span><?=__('Name','egoi-for-wp');?></span>
            </th>
            <th>
                <input name="catalog_name" id="catalog_name" class="form-control" type="text" style="width: 100%;">
            </th>
        </tr>
        <tr>
            <th>
                <span><?=__('Language','egoi-for-wp');?></span>
            </th>
            <th>
                <select class="e-goi-option-select-admin-forms" style="width: 100%;" name="catalog_language" id="catalog_language" required>
                    <option value="off" selected>
                        <?php __('Disable', 'egoi-for-wp');?>
                    </option>
                </select>
            </th>
        </tr>
        <tr>
            <th>
                <span><?=__('Currency','egoi-for-wp');?></span>
            </th>
            <th>
                <select class="e-goi-option-select-admin-forms" style="width: 100%;" name="catalog_currency" id="catalog_currency" required>
                    <option value="off" selected>
                        <?php __('Disable', 'egoi-for-wp');?>
                    </option>
                </select>
            </th>
        </tr>
        </tbody>
    </table>
    <?php
    $other_attributes = array( 'id' => 'create_catalog_button' );
    submit_button( __( 'Create Catalog', 'egoi-fow-wp' ), 'primary', 'submit', true, $other_attributes );
    ?>
</form>