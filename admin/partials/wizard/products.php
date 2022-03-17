<div class="tab-pane fade" id="v-pills-products" role="tabpanel" aria-labelledby="v-pills-products-tab">
	<p>Import Products</p>

	<?php
	if ( ! class_exists( 'WooCommerce' ) ) {
		require_once plugin_dir_path( __FILE__ ) . '../ecommerce/no-woocommerce.php';
		?>
		</div>
		<?php
		return;
	}
	$ProductBO = new EgoiProductsBo();
	$table     = $ProductBO->getCatalogsTable();

	if ( ! empty( $table ) ) {
		?>

	<input type="hidden" id="force_catalog_glob" idgoi="">
	<input type="hidden" id="catalog_glob_status" value="">

	<div class="smsnf-input-group">
		<label for="catalog">· <?php _e( 'Sync products with this catalog', 'egoi-for-wp' ); ?></label>
		<p class="subtitle"><?php _e( 'Select the E-goi\'s catalog for your store.', 'egoi-for-wp' ); ?></p>
		<div class="smsnf-wrapper">
			<select id="catalog" name="egoi_sync[catalog]" required class="form-select" >
				<option disabled value="" selected>
					<?php
						_e( 'Select a catalog', 'egoi-for-wp' );
					foreach ( $table as $catalog ) {
						?>
						<option value="<?php echo $catalog['catalog_id']; ?>"> <?php echo $catalog['title']; ?> [ <?php echo $catalog['language'] . ' - ' . $catalog['currency']; ?> ]</option>
						<?php
					}
					?>
				</option>
			</select>

		</div>
	</div>

	<b><?php _e( 'Or', 'egoi-for-wp' ); ?></b>
		<?php
	} else {
		?>

		<h3><?php _e( 'Looks like you dont have any catalogs yet, it\'s time for your first one!', 'egoi-for-wp' ); ?></h3>

		<?php
	}

	?>

	<div class="smsnf-input-group" style="margin-top: 1em;margin-bottom: -4px;">
		<input type="submit" id="create_catalog" value="<?php echo _e( 'Create Catalog', 'egoi-for-wp' ); ?>">
	</div>


</div>


<?php
getProductImportModal();
?>

<div class="modal fade" id="createCatalogModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" style="opacity: 1;">
	<div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 868px;">
		<div class="modal-content">
			<div class="egoi-modal-header modal-header">
				<button type="button" class="close no-border-button" data-dismiss="modal" aria-label="Close">
					&times;
				</button>
				<h2 class="modal-title" id="modalLabel"><?php _e( 'Create Catalog', 'egoi-for-wp' ); ?></h2>
			</div>
			<div class="modal-body nav">
				<input hidden id="preventCatalogSubmit">
				<?php
					require_once plugin_dir_path( __FILE__ ) . '../ecommerce/new-catalog-form.php';
				?>
			</div>
		</div>
	</div>
</div>
