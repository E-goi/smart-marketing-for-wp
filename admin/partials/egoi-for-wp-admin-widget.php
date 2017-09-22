
<script type="text/javascript">
jQuery(document).ready(function($) {
	'use strict';

	$('#nav-tab-settings').click(function() {
		$('#tab-settings').show();
		$('#tab-appearance').hide();
		$(this).addClass('nav-tab-active');
		$('#nav-tab-appearance').removeClass('nav-tab-active');
	});

	$('#nav-tab-appearance').click(function() {
		$('#tab-appearance').show();
		$('#tab-settings').hide();
		$(this).addClass('nav-tab-active');
		$('#nav-tab-settings').removeClass('nav-tab-active');
	});
});
</script>
<style type="text/css">
	.nav-tab-wrapper{
		border-bottom: 1px solid #ccc;
		padding-top: 9px;
	}
</style>
	
<div class="wrap egoi4wp-settings">

	<div class="row">
		<div id="egoi4wp-admin" class="main-content col col-4">

			<form method="post" action=""><?php
			settings_fields($FORM_OPTION);?>
			
			<input type="hidden" name="egoiform" value="egoi_widget">
			<table class="form-table" style="table-layout: fixed;">
				<tr valign="top">
					<th scope="row"><?php _e( 'Enable Widget', 'egoi-for-wp' ); ?></th>
					<td class="nowrap">
						<label>
							<input type="radio" name="egoi_widget[enabled]" value="1" <?php checked($egoiwidget['enabled'], 1); ?> />
							<?php _e( 'Yes', 'egoi-for-wp' ); ?>
						</label> &nbsp;
						<label>
							<input type="radio" name="egoi_widget[enabled]" value="0" <?php checked($egoiwidget['enabled'], 0); ?> />
							<?php _e( 'No', 'egoi-for-wp' ); ?>
						</label>
						<p class="help">
							<?php _e( 'Select "yes" to enable forms widget.', 'egoi-for-wp' ); ?>
						</p>
					</td>
				</tr>
			</table>
			
			<h2 class="nav-tab-wrapper" id="egoi-tabs-widget">
				<a class="nav-tab nav-tab-widget-settings nav-tab-active" id="nav-tab-widget-settings" style="cursor: pointer;"><?php _e( 'Settings', 'egoi-for-wp' ); ?></a>
				<a class="nav-tab nav-tab-widget-appearance" id="nav-tab-widget-appearance" style="cursor: pointer;"><?php _e( 'Appearance', 'egoi-for-wp' ); ?></a>
			</h2>

			<div id="tab-widget-settings">
				<table class="form-table" style="table-layout: fixed;">
					<tr valign="top">
						<th scope="row"><label for="egoi_form_sync_subscribed"><?php _e( 'Successfully subscribed', 'egoi-for-wp' ); ?></label></th>
						<td>
							<input type="text" style="width:450px;" id="egoi_form_sync_subscribed" name="egoi_widget[msg_subscribed]" value="<?php echo esc_attr($egoiwidget['msg_subscribed']);?>" />
							<p class="help"><?php _e( 'The text that shows when an email address is successfully subscribed to the selected list.', 'egoi-for-wp' ); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="egoi_form_sync_invalid_email"><?php _e( 'Invalid email address', 'egoi-for-wp' ); ?></label></th>
						<td>
							<input type="text" style="width:450px;" id="egoi_form_sync_invalid_email" name="egoi_widget[msg_invalid]" value="<?php echo esc_attr($egoiwidget['msg_invalid']);?>" />
							<p class="help"><?php _e( 'The text that shows when an invalid email address is given.', 'egoi-for-wp' ); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="egoi_form_sync_email_empty"><?php _e( 'Empty email address', 'egoi-for-wp' ); ?></label></th>
						<td>
							<input type="text" style="width:450px;" id="egoi_form_sync_email_empty" name="egoi_widget[msg_empty]" value="<?php echo esc_attr($egoiwidget['msg_empty']);?>" />
							<p class="help"><?php _e( 'The text that shows when the email is empty.', 'egoi-for-wp' ); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="egoi_form_sync_already_subscribed"><?php _e( 'Already subscribed', 'egoi-for-wp' ); ?></label></th>
						<td>
							<input type="text" style="width:450px;" id="egoi_form_sync_already_subscribed" name="egoi_widget[msg_exists_subscribed]" value="<?php echo esc_attr($egoiwidget['msg_exists_subscribed']);?>" />
							<p class="help"><?php _e( 'The text that shows when the given email is already subscribed to the selected list.', 'egoi-for-wp' ); ?></p>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Hide form after successful sign-up', 'egoi-for-wp' ); ?></th>
						<td class="nowrap">
							<label>
								<input type="radio" name="egoi_widget[hide_form]" value="1" <?php checked($egoiwidget['hide_form'], 1);?> />
								<?php _e( 'Yes', 'egoi-for-wp' ); ?>
							</label> &nbsp;
							<label>
								<input type="radio" name="egoi_widget[hide_form]" value="0" <?php checked($egoiwidget['hide_form'], 0);?> />
								<?php _e( 'No', 'egoi-for-wp' ); ?>
							</label>
							<p class="help">
								<?php _e( 'Select "yes" to hide the form after successful sign-up.', 'egoi-for-wp' ); ?>
							</p>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><label for="egoi_form_sync_redirect"><?php _e( 'Redirect to URL after a successful sign-up', 'egoi-for-wp' ); ?></label></th>
						<td>
							<input type="text" style="width:450px;" name="egoi_widget[redirect]" id="egoi_form_sync_redirect" placeholder="<?php printf(__('Example: %s', 'egoi-for-wp'), esc_attr(site_url('/thank-you/')));?>" value="<?php echo esc_attr($egoiwidget['redirect']); ?>" />
							<p class="help"><?php _e( 'Leave empty for no redirect. Otherwise, use complete (absolute) URLs.', 'egoi-for-wp' ); ?></p>
						</td>
					</tr>
				</table>
			</div>

			<div class="tab" id="tab-widget-appearance">
				<table class="form-table">

					<tr valign="top">
						<th scope="row">
							<label>
								<?php _e( 'Input Width', 'egoi-for-wp' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="egoi_widget[input_width]" value="<?php echo esc_attr($egoiwidget['input_width']); ?>">
							<p class="help"><?php _e( 'Change the input width in px, otherwise leave empty if you want to 100%', 'egoi-for-wp' ); ?></p>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<label>
								<?php _e( 'Button Width', 'egoi-for-wp' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="egoi_widget[btn_width]" value="<?php echo esc_attr($egoiwidget['btn_width']); ?>">
							<p class="help"><?php _e( 'Change the subscriber button width in px', 'egoi-for-wp' ); ?></p>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<label>
								<?php _e( 'Border Color', 'egoi-for-wp' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="egoi_widget[bcolor]" value="<?php echo esc_attr($egoiwidget['bcolor']); ?>" class="color">
							<p class="help"><?php _e( 'Change the color of the Widget border', 'egoi-for-wp' ); ?></p>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<label>
								<?php _e( 'Background Color on Success', 'egoi-for-wp' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="egoi_widget[bcolor_success]" value="<?php echo esc_attr($egoiwidget['bcolor_success']); ?>" class="color">
							<p class="help"><?php _e( 'Change the color of the Widget Success message', 'egoi-for-wp' ); ?></p>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<label>
								<?php _e( 'Background Color on Error', 'egoi-for-wp' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="egoi_widget[bcolor_error]" value="<?php echo esc_attr($egoiwidget['bcolor_error']); ?>" class="color">
							<p class="help"><?php _e( 'Change the color of the Widget Error message', 'egoi-for-wp' ); ?></p>
						</td>
					</tr>
				</table>
			</div>

			<table class="form-table" style="table-layout: fixed;">
				<tr valign="top">
					<td colspan="2">
						<div style="display: -webkit-inline-box;">
							<button style="margin-top: 12px;" type="submit" class="button button-primary"><?php _e('Save Changes', 'egoi-for-wp');?></button>
						</div>
					</td>
				</tr>
			</table>
			</form>

		</div>
	</div>
