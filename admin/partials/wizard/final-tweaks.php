<div class="tab-pane fade" id="v-pills-tweaks" role="tabpanel" aria-labelledby="v-pills-tweaks-tab">
	<p>Back end conversion, cron conversion (performance)</p>
	<form id="form-tweaks" method="post" action="#">
		<?php if ( class_exists( 'WooCommerce' ) ) { ?>
		<div class="smsnf-input-group">
			<label for="egoi_sync[backend_order]">· <?php _e( 'Convert orders via backend', 'egoi-for-wp' ); ?></label>
			<p class="subtitle"><?php _e( 'Will convert your Tracking events via api in the backend', 'egoi-for-wp' ); ?></p>
			<div class="form-group switch-yes-no">
				<label class="form-switch">
					<input id="egoi_sync[backend_order]" type="checkbox" name="egoi_sync[backend_order]" value="1" checked>
					<i class="form-icon"></i><div class="yes"><?php _e( 'Yes', 'egoi-for-wp' ); ?></div><div class="no"><?php _e( 'No', 'egoi-for-wp' ); ?></div>
				</label>
			</div>
		</div>
		<?php } ?>


		<div class="smsnf-input-group">
			<label for="egoi_sync[lazy_sync]">· <?php _e( 'Lazy conversion', 'egoi-for-wp' ); ?></label>
			<p class="subtitle"><?php echo __( 'Will convert event and signups in cron job (next: ', 'egoi-for-wp' ) . date( 'm/d/Y H:i:s', wp_next_scheduled( 'egoi_cron_hook' ) ); ?>)</p>
			<div class="form-group switch-yes-no">
				<label class="form-switch">
					<input id="egoi_sync[lazy_sync]" type="checkbox" name="egoi_sync[lazy_sync]" value="1">
					<i class="form-icon"></i><div class="yes"><?php _e( 'Yes', 'egoi-for-wp' ); ?></div><div class="no"><?php _e( 'No', 'egoi-for-wp' ); ?></div>
				</label>
			</div>
		</div>
	</form>

</div>
