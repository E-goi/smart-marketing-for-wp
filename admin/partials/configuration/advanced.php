<?php
if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	foreach ( array( 'lazy_sync', 'backend_order', 'backend_order_state' ) as $field ) {
		if ( ! isset( $_POST[ $field ] ) ) {
			$this->options_list[ $field ] = false;
			continue;
		}
		$this->options_list[ $field ] = sanitize_key( $_POST[ $field ] );
	}
	update_option( 'egoi_sync', $this->options_list );
	echo get_notification( __( 'Saved Configuration', 'egoi-for-wp' ), __( 'Advanced configurations saved with success.', 'egoi-for-wp' ) );
}

	require_once plugin_dir_path( __FILE__ ) . '../../../includes/class-egoi-for-wp-lazy.php';
	$converter     = new \EgoiLazyConverter();
	$countRequests = $converter->countRequestsWaiting();
?>

<div>
	<form method="post" action="#">
		<?php if ( class_exists( 'WooCommerce' ) ) { ?>
			<h5> <?php _e( 'Ecommerce', 'egoi-for-wp' ); ?> </h5>
			<div class="egoi-sub-form-ident">
				<div class="smsnf-input-group">
					<label for="backend_order">· <?php _e( 'Convert orders via backend', 'egoi-for-wp' ); ?></label>
					<p class="subtitle"><?php _e( 'Will convert your Tracking events via api in the backend', 'egoi-for-wp' ); ?></p>
					<div class="form-group switch-yes-no">
						<label class="form-switch">
							<input id="backend_order" type="checkbox" name="backend_order" value="1" <?php checked( $this->options_list['backend_order'] ); ?>>
							<i class="form-icon"></i><div class="yes"><?php _e( 'Yes', 'egoi-for-wp' ); ?></div><div class="no"><?php _e( 'No', 'egoi-for-wp' ); ?></div>
						</label>
					</div>
				</div>

				<div class="smsnf-input-group">
					<label for="backend_order_state">· <?php _e( 'Order status to be converted', 'egoi-for-wp' ); ?></label>
					<p class="subtitle"><?php _e( 'Select order status.', 'egoi-for-wp' ); ?></p>
					<div class="smsnf-wrapper">
						<select id="backend_order_state" name="backend_order_state" class="form-select" >
							<option disabled value="" selected>
								<?php
								_e( 'Select a conversion state', 'egoi-for-wp' );
								?>
								</option>
								<?php
								foreach ( wc_get_order_statuses() as $key => $status ) {
									?>
									<option value="<?php echo $key; ?>" <?php selected( $this->options_list['backend_order_state'], $key ); ?> > <?php echo $status; ?> </option>
									<?php
								}
								?>
						</select>

					</div>
				</div>

			</div>
			<hr>
		<?php } ?>
		<h5 style="display: flex;flex-direction: row;align-items: center;"> <?php _e( 'Lazy Sync', 'egoi-for-wp' ); ?> <p class="subtitle" style="padding-left: 10px;">(<?php _e( 'Optional', 'egoi-for-wp' ); ?>)</p> </h5>
		<div class="egoi-sub-form-ident">
			<div class="smsnf-input-group">
				<label for="lazy_sync">· <?php _e( 'Lazy conversion', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php echo __( 'Will convert event and signups in cron job (next: at ', 'egoi-for-wp' ) . date( 'm/d/Y H:i:s', wp_next_scheduled( 'egoi_cron_hook' ) ) . sprintf( __( ' with (%d) events).', 'egoi-for-wp' ), $countRequests ); ?></p>
				<div class="form-group switch-yes-no">
					<label class="form-switch">
						<input id="lazy_sync" type="checkbox" name="lazy_sync" value="1" <?php checked( $this->options_list['lazy_sync'] ); ?> >
						<i class="form-icon"></i><div class="yes"><?php _e( 'Yes', 'egoi-for-wp' ); ?></div><div class="no"><?php _e( 'No', 'egoi-for-wp' ); ?></div>
					</label>
				</div>
			</div>

			<div class="smsnf-dashboard-subs-stats" style="flex-direction: column;background-color: #f9f9f9;">
				<h3 style="margin-top: 10px;">
					<?php _e( 'Instructions', 'egoi-for-wp' ); ?>
				</h3>
				<div>
					<p class="subtitle"><?php _e( 'This plugin will create a new cron job schedule every minute, if you activate lazy sync, every API request will be done through this cron.', 'egoi-for-wp' ); ?></p>
					<p class="subtitle"><?php _e( 'To take the maximum from this feature we advise to setup a crontab execution.', 'egoi-for-wp' ); ?> <a href="https://www.a2hosting.com/kb/installable-applications/optimization-and-configuration/wordpress2/configuring-a-cron-job-for-wordpress" target="_blank" ><?php _e( 'Tutorial Here', 'egoi-for-wp' ); ?></a></p>
					<p class="subtitle" style="margin-top: 1em;"><?php _e( '1st - Add the following line in your wp-config.php file:', 'egoi-for-wp' ); ?> </p>
					<div style="display: flex">
						<code class="egoi-code-block-padding" >
							define('DISABLE_WP_CRON', true);
						</code>
						<button class="egoi-copy-code">
							<?php _e( 'Copy', 'egoi-for-wp' ); ?>
						</button>
					</div>
					<p class="subtitle"> <?php _e( '2nd - Enable backend cron execution request (crontab method)', 'egoi-for-wp' ); ?> </p>
					<div style="display: flex">
						<code class="egoi-code-block-padding">
							*/1 * * * *  wget -q -O - <?php echo get_site_url(); ?>/wp-cron.php?doing_wp_cron >/dev/null 2>&1
						</code>
						<button class="egoi-copy-code">
							<?php _e( 'Copy', 'egoi-for-wp' ); ?>
						</button>
					</div>

				</div>
			</div>
		</div>
		<div style="display: flex;flex-direction: row;justify-content: left;align-content: center;margin-top: 1em;margin-bottom: 1em;margin-left: 1em;">
			<?php

			if ( wp_next_scheduled( 'egoi_cron_hook' ) > time() + 60 ) {
				?>
				<span class="dashicons dashicons-warning"></span>
				<span><?php _e( 'Warning: looks something is missing in your cron configuration, scheduled time for cron is higher than current time', 'egoi-for-wp' ); ?></span>
				<?php
			} else {
				?>
				<span><?php _e( 'Status', 'egoi-for-wp' ); ?>:</span>
				<span class="dashicons dashicons-yes-alt"></span>
				<?php
			}

			?>
		</div>

		<div class="egoi-undertable-button-wrapper" style="bottom: -14px;position: absolute;right: 20px;">
			<div class="smsnf-input-group">
				<!-- <span>ignore</span> -->
				<input type="submit" value="<?php _e( 'Save', 'egoi-for-wp' ); ?>" />
			</div>
		</div>
	</form>
</div>
