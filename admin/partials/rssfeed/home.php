<?php
if ( ! isset( $_GET['add'] ) && ! isset( $_GET['edit'] ) && ! isset( $_GET['view'] ) ) { ?>



		<div class="e-goi-account-list__title">
			<?php echo __( 'RSS Feed', 'egoi-for-wp' ); ?>
		</div>
		<table border='0' class="widefat striped">
			<thead>
			<tr>
				<th><?php _e( 'Name', 'egoi-for-wp' ); ?></th>
				<th><?php _e( 'Type', 'egoi-for-wp' ); ?></th>
				<th><?php _e( 'URL', 'egoi-for-wp' ); ?> </th>
				<th></th><th></th>
			</tr>
			</thead>
			<tbody>
			<spam id="copy_text" style="display: none;"><?php _e( 'Copy URL', 'egoi-for-wp' ); ?></spam>
			<spam id="copied_text" style="display: none;"><?php _e( 'Copied!', 'egoi-for-wp' ); ?></spam>
			<?php
			global $wpdb;
			$table   = $wpdb->prefix . 'options';
			$options = $wpdb->get_results( ' SELECT * FROM ' . $table . " WHERE option_name LIKE 'egoi_rssfeed_%' ORDER BY option_id DESC " );
			foreach ( $options as $option ) {
				$feed = get_option( $option->option_name );
				?>

				<!-- PopUp ALERT Delete Form -->
				<div class="cd-popup cd-popup-del-form" data-id-form="<?php echo $option->option_name; ?>" data-type-form="rss-feed" role="alert">
					<div class="cd-popup-container">
						<p><b><?php echo __( 'Are you sure you want to delete this RSS Feed?', 'egoi-for-wp' ); ?> </b></p>
						<ul class="cd-buttons">
							<li>
								<a href="<?php echo esc_url($this->prepareUrl( '&del=' . $option->option_name )); ?>"><?php _e( 'Confirm', 'egoi-for-wp' ); ?></a>
							</li>
							<li>
								<a class="cd-popup-close-btn" href="#0"><?php _e( 'Cancel', 'egoi-for-wp' ); ?></a>
							</li>
						</ul>
					</div> <!-- cd-popup-container -->
				</div> <!-- PopUp ALERT Delete Form -->

				<tr>
					<td style="vertical-align: middle;"><?php echo $feed['name']; ?></td>
					<td style="vertical-align: middle;"><?php _e( ucfirst( $feed['type'] ), 'egoi-for-wp' ); ?></td>
					<td style="vertical-align: middle;"><input type="text" id="url_<?php echo $option->option_name; ?>" class="copy-input" value="<?php echo get_home_url() . '/?feed=' . $option->option_name; ?>" readonly
															   style="width: 100%;border: none;background-image:none;background-color:transparent;-webkit-box-shadow: none;-moz-box-shadow: none;box-shadow: none;"></td>
					<td style="vertical-align: middle;" width="130">
						<button type="button" class="copy_url smsnf-btn" data-rss-feed="url_<?php echo $option->option_name; ?>" onclick="copyToClipboard('url_<?php echo $option->option_name; ?>')"><?php _e( 'Copy URL', 'egoi-for-wp' ); ?></button>
					</td>
					<td style="vertical-align: middle;" align="right" width="70" nowrap>
						<nobr>
							<a class="cd-popup-trigger-del" data-id-form="<?php echo esc_attr($option->option_name); ?>" data-type-form="rss-feed" href="" title="<?php _e( 'Delete', 'egoi-for-wp' ); ?>"><i style="padding-right: 3px;" class="far fa-trash-alt"></i></a>
							<a title="<?php _e( 'Edit', 'egoi-for-wp' ); ?>" href="<?php echo esc_url($this->prepareUrl( '&sub=rss-feed&edit=' . $option->option_name )); ?>"><i style="padding-right: 2px;" class="far fa-edit"></i></a>
							<a title="<?php _e( 'Preview', 'egoi-for-wp' ); ?>" href="<?php echo esc_url($this->prepareUrl( '&sub=rss-feed&view=' . $option->option_name )); ?>"><i class="fas fa-eye"></i></a>
						</nobr>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<br>
		<div class="egoi-undertable-button-wrapper">
			<div class="smsnf-input-group" style="margin-block-end: 0 !important;">
				<input type="submit" onclick="window.location='<?php echo esc_url($this->prepareUrl( '&add=1&sub=rss-feed' )); ?>';" value="<?php _e( 'Create RSS Feed +', 'egoi-for-wp' ); ?>">
			</div>
		</div>



<?php } ?>

<script>
    function copyToClipboard(elementId) {
        var copyText = document.getElementById(elementId);
        copyText.select();
        document.execCommand("copy");
    }
</script>