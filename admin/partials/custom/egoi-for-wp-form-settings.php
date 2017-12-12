<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
?>
<div class="medium-margin"></div>

	<table class="form-table" style="table-layout: fixed;">

		<tr valign="top">
			<th scope="row"><?php _e( 'Update existing subscribers', 'egoi-for-wp' ); ?></th>
			<td class="nowrap">
				<label>
					<input type="radio" name="egoi_form_sync[update]" value="1" <?php checked($opt['egoi_form_sync']['update'], 1); ?> />
					<?php _e( 'Yes', 'egoi-for-wp' ); ?>
				</label> &nbsp;
				<label>
					<input type="radio" name="egoi_form_sync[update]" value="0" <?php checked($opt['egoi_form_sync']['update'], 0); ?> />
					<?php _e( 'No', 'egoi-for-wp' ); ?>
				</label>
				<p class="help"><?php _e( 'Select "yes" if you want to update existing subscribers with the data that is sent.', 'egoi-for-wp' ); ?></p>
			</td>
		</tr>
	</table>


	<table class="form-table" style="table-layout: fixed;">

		<tr valign="top">
			<th scope="row"><?php _e( 'Hide form after a successful sign-up', 'egoi-for-wp' ); ?></th>
			<td class="nowrap">
				<label>
					<input type="radio" name="egoi_form_sync[hide_form]" value="1" <?php checked($opt['egoi_form_sync']['hide_form'], 1); ?> />
					<?php _e( 'Yes', 'egoi-for-wp' ); ?>
				</label> &nbsp;
				<label>
					<input type="radio" name="egoi_form_sync[hide_form]" value="0" <?php checked($opt['egoi_form_sync']['hide_form'], 0); ?> />
					<?php _e( 'No', 'egoi-for-wp' ); ?>
				</label>
				<p class="help">
					<?php _e( 'Select "yes" to hide the form after successful sign-up.', 'egoi-for-wp' ); ?>
				</p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="egoi_form_sync_redirect"><?php _e( 'Redirect to URL after successful sign-up', 'egoi-for-wp' ); ?></label></th>
			<td>
				<input type="text" class="widefat" name="egoi_form_sync[redirect]" id="egoi_form_sync_redirect" placeholder="<?php printf(__('Example: %s', 'egoi-for-wp'), esc_attr(site_url('/thank-you/')));?>" value="<?php echo esc_attr($opt['egoi_form_sync']['redirect']); ?>" />
				<p class="help"><?php _e( 'Leave empty for no redirect. Otherwise, use complete (absolute) URLs.', 'egoi-for-wp' ); ?></p>
			</td>
		</tr>
	</table>