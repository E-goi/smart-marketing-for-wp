<?php 
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
?>

<table class="form-table egoi4wp-form-messages">

	<tr valign="top">
		<th scope="row"><label for="egoi_form_sync_subscribed"><?php _e( 'Successfully subscribed', 'egoi-for-wp' ); ?></label></th>
		<td>
			<input type="text" class="widefat" id="egoi_form_sync_subscribed" name="egoi_form_sync[msg_subscribed]" value="<?php echo esc_attr($opt['egoi_form_sync']['msg_subscribed']); ?>" />
			<p class="help"><?php _e( 'The text that shows when an email address is successfully subscribed to the selected list(s).', 'egoi-for-wp' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="egoi_form_sync_invalid_email"><?php _e( 'Invalid email address', 'egoi-for-wp' ); ?></label></th>
		<td>
			<input type="text" class="widefat" id="egoi_form_sync_invalid_email" name="egoi_form_sync[msg_invalid]" value="<?php echo esc_attr($opt['egoi_form_sync']['msg_invalid']); ?>" />
			<p class="help"><?php _e( 'The text that shows when an invalid email address is given.', 'egoi-for-wp' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="egoi_form_sync_already_subscribed"><?php _e( 'Already subscribed', 'egoi-for-wp' ); ?></label></th>
		<td>
			<input type="text" class="widefat" id="egoi_form_sync_already_subscribed" name="egoi_form_sync[msg_exists_subscribed]" value="<?php echo esc_attr($opt['egoi_form_sync']['msg_exists_subscribed']); ?>" />
			<p class="help"><?php _e( 'The text that shows when the given email is already subscribed to the selected list.', 'egoi-for-wp' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="egoi_form_sync_error"><?php _e( 'General error' ,'egoi-for-wp' ); ?></label></th>
		<td>
			<input type="text" class="widefat" id="egoi_form_sync_error" name="egoi_form_sync[msg_error]" value="<?php echo esc_attr($opt['egoi_form_sync']['msg_error']); ?>" />
			<p class="help"><?php _e( 'The text that shows when a general error occured.', 'egoi-for-wp' ); ?></p>
		</td>
	</tr>

</table>