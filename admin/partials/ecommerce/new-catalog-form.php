<form action="#" method="post" id="form-create-catalog">
	<?php wp_nonce_field( 'form-create-catalog' ); ?>
	<input name="form_id" type="hidden" value="form-create-catalog" />
	<input id="default-store-country" type="hidden" value="
	<?php
	$store_raw_country = get_option( 'woocommerce_default_country' );
	$split_country     = explode( ':', $store_raw_country );
	echo $split_country[0];
	?>
	" />
	<input id="default-store-currency" type="hidden" value="<?php echo get_option( 'woocommerce_currency' ); ?>" />
	<span style="display: block;margin-bottom: 20px;">* <?php _e( 'Create a catalog base on language and currency and start the product\'s synchronization with E-goi.', 'egoi-for-wp' ); ?></span>
	<div class="smsnf-grid">
		<div>
			<div class="smsnf-input-group">
				<label for="catalog_name"><?php _e( 'Name', 'egoi-for-wp' ); ?></label>
				<input  id="catalog_name" type="text"
						name="catalog_name" size="30" spellcheck="true" autocomplete="off"
						placeholder="<?php _e( 'Write here the name of your catalog', 'egoi-for-wp' ); ?>" />
			</div>

			<div class="smsnf-input-group">
				<label for="catalog_language"><?php _e( 'Language', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php _e( 'Select the form you want to sync contacts on.', 'egoi-for-wp' ); ?></p>
				<div class="smsnf-wrapper">
					<select id="catalog_language" name="catalog_language" class="form-select" >
						<option value="off" selected><?php _e( 'Select a language..', 'egoi-for-wp' ); ?></option>
					</select>
				</div>
			</div>

			<div class="smsnf-input-group">
				<label for="catalog_currency"><?php _e( 'Currency', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php _e( 'Select the currency to be assign to the products', 'egoi-for-wp' ); ?></p>
				<div class="smsnf-wrapper">
					<select id="catalog_currency" name="catalog_currency" class="form-select" >
						<option value="off" selected><?php _e( 'Select a currency..', 'egoi-for-wp' ); ?></option>
					</select>
				</div>
			</div>

			<div class="smsnf-input-group">
				<label for="catalog_tax"><?php _e( 'Tax', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php _e( 'Select the tax rate to be assigned to the products. ( tax country code | tax rate % )', 'egoi-for-wp' ); ?></p>
				<div class="smsnf-wrapper">
					<select id="catalog_tax" name="catalog_tax" class="form-select" >
						<option value="off" selected><?php _e( 'Select the tax rate..', 'egoi-for-wp' ); ?></option>
					</select>
				</div>
			</div>

			<hr class="smsnf-input-group">

			<div class="smsnf-input-group">
				<label for="variations"><?php _e( 'Synchronize variations', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php _e( 'Select "yes" to sync all product variations individually.', 'egoi-for-wp' ); ?></p>
				<div class="form-group switch-yes-no">
					<label class="form-switch">
						<input id="variations" type="checkbox" name="variations" value="1">
						<i class="form-icon"></i><div class="yes"><?php _e( 'Yes', 'egoi-for-wp' ); ?></div><div class="no"><?php _e( 'No', 'egoi-for-wp' ); ?> <span style="font-size: small;font-weight: 100;color: black;opacity: 50%">(<?php _e( 'Recommended', 'egoi-for-wp' ); ?>)</span></div>
					</label>
				</div>
			</div>

		</div>
	</div>
	<div class="egoi-undertable-button-wrapper" style="bottom: 0;position: absolute;right: 30px;">
		<div class="smsnf-input-group">
			<input type="submit" id="create_catalog_button" value="<?php _e( 'Create Catalog', 'egoi-for-wp' ); ?>" />
		</div>
	</div>

</form>
