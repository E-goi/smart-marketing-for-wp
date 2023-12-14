<?php if ( ! empty( $table ) ) { ?>
	<div class="container" style="overflow: auto;">
		<table border="0" class="smsnf-table" style="margin-bottom: 54px;">
			<thead>
			<tr>
				<th><?php _e( 'ID', 'egoi-for-wp' ); ?></th>
				<th><?php _e( 'Name', 'egoi-for-wp' ); ?></th>
				<th><?php _e( 'Language', 'egoi-for-wp' ); ?></th>
				<th><?php _e( 'Currency', 'egoi-for-wp' ); ?></th>
				<th><?php _e( 'Status', 'egoi-for-wp' ); ?></th>
				<th><?php _e( 'Variations', 'egoi-for-wp' ); ?></th>
				<th><?php _e( 'Operations', 'egoi-for-wp' ); ?></th>
				<th><?php _e( 'Created At', 'egoi-for-wp' ); ?></th>
			</tr>
			</thead>
			<tbody>
				<?php
				foreach ( $table as $catalog ) {
					echo EgoiProductsBo::genTableCatalog( $catalog ); }
				?>
			</tbody>
		</table>
	<?php } else { ?>
	<div class="container" style="display: flex;justify-content: center;align-items: center;flex-direction: column;min-height: 100%;">
		<div style="text-align: center;">
			<h2><?php _e( 'There are no Catalogs!', 'egoi-for-wp' ); ?></h2>
			<h4><?php _e( 'Create your first catalog', 'egoi-for-wp' ); ?></h4>
		</div>
	<?php } ?>
	<div class="egoi-undertable-button-wrapper" style="bottom: -14px;position: absolute;right: 13px;">
		<div class="smsnf-input-group">
			<input type="submit" id="new_catalog_page" value="<?php _e( 'New Catalog', 'egoi-for-wp' ); ?>" />
		</div>
	</div>
</div>


<?php
	getProductImportModal();
?>


<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="egoi-modal-header modal-header">
				<button id="close_modal_catalog" type="button" class="close no-border-button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h2 class="modal-title" id="modalLabel"><?php _e( 'Delete', 'egoi-for-wp' ); ?></h2>
			</div>
			<div class="modal-body nav">
				<input type="hidden" id="selected-delete-catalog">
				<span><?php _e( 'All imported products will be removed from E-goi if you delete this catalog!', 'egoi-for-wp' ); ?></span>
				<div style="display: flex;"><h4><?php _e( 'Are you sure?', 'egoi-for-wp' ); ?></h4></div>
			</div>
			<div class="modal-footer flex-centered sun-margin">
				<button id="cancel_modal_catalog" type="button" class="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><?php _e( 'Cancel', 'egoi-for-wp' ); ?></span>
				</button>
				<button id="verified-delete-catalog" type="button" class="button egoi-remove-button" data-dismiss="modal">
					<?php echo getLoaderNew( 'delete_catalog_loader', false ); ?>
					<span id="verified-delete-catalog-span" aria-hidden="true"><?php _e( 'Yes', 'egoi-for-wp' ); ?></span>
				</button>
			</div>
		</div>
	</div>
</div>


