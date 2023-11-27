<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

// widgets
$opt_widget = get_option( 'egoi_widget' );
$egoiwidget = $opt_widget['egoi_widget'];

if ( $egoiwidget['tag'] != '' ) {
	if( ! is_numeric($egoiwidget['tag']) ){
		$info = $this->egoiWpApiV3->getTag( $egoiwidget['tag'] );
		$tag = $info['tag_id'];
	} else {
		$tag  = $egoiwidget['tag'];
	}
} else {
	$tag = $egoiwidget['tag-egoi'];
}


$egoiwidget = array_map(
	function( $str ) {
		return str_replace( "\'", "'", $str );
	},
	$egoiwidget
);

if ( ! $egoiwidget['enabled'] ) {
	$egoiwidget['enabled'] = 0;
}

?>
<style type="text/css">
	.nav-tab-wrapper{
		border-bottom: 1px solid #ccc;
		padding-top: 9px;
	}

	.nav-tab-wrapper-tags{
		border-bottom: 1px solid #ccc;
		padding-top: 9px;
	}
</style>

<div class="wrap egoi4wp-settings">

	<div class="row">
		<div id="egoi4wp-admin" class="main-content col eg-col-4">

			<form method="post" action="">
				<?php settings_fields( $FORM_OPTION ); ?>

				<div id="widget-submit-error" style="display: none;">
					<div class="error notice">
						<p><?php _e( 'Please, choose the list.', 'egoi-for-wp' ); ?></p>
					</div>
				</div>

				<input type="hidden" name="widget" value="1">
				<input type="hidden" name="egoiform" value="egoi_widget">
				<table class="form-table" style="table-layout: fixed;">

					<tr valign="top">
						<th>
						<?php
						echo _e(
							'Enable Widgets 
 							<span class="e-goi-tooltip">
 								 <span class="dashicons dashicons-editor-help"></span>
 							  	 <span class="e-goi-tooltiptext e-goi-tooltiptext--custom-widget">
 							  	 	Need to have the form of this widget disabled? Just tick this option. (don\'t forget that for widget to be visible you must drag him to a sidebar or click it in Appearance > Widgets)
 							 	</span>
 							</span>',
							'egoi-for-wp'
						);
						?>
						</th>
						<td class="nowrap">
							<label>
								<input type="radio" name="egoi_widget[enabled]" value="1" <?php checked( $egoiwidget['enabled'], 1 ); ?> />
								<?php _e( 'Yes', 'egoi-for-wp' ); ?>
							</label> &nbsp;
							<label>
								<input type="radio" name="egoi_widget[enabled]" value="0" <?php checked( $egoiwidget['enabled'], 0 ); ?> />
								<?php _e( 'No', 'egoi-for-wp' ); ?>
							</label>
							<!-- <p class="help">
							<?php // _e( 'Select "yes" to enable forms widget.', 'egoi-for-wp' ); ?>
						</p> -->
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<label>
								<?php _e( 'Enable Double Opt-In?', 'egoi-for-wp' ); ?>
							</label>
						</th>
						<td>
							<label>
								<input type="radio" name="egoi_widget[double_optin]" value="1" <?php echo $egoiwidget['double_optin'] == 1 || $egoiwidget['list'] == 0 ? 'checked' : null; ?> /> <?php _e( 'Yes' ); ?>
							</label>
							<label>
								<input type="radio" name="egoi_widget[double_optin]" value="0" <?php echo $egoiwidget['double_optin'] == 0 && $egoiwidget['list'] != 0 ? 'checked' : null; ?> /> <?php _e( 'No' ); ?>
							</label>
							<p class="help"><?php _e( 'If you activate the double opt-in, a confirmation e-mail will be send to the subscribers.', 'egoi-for-wp' ); ?></p>
						</td>
					</tr>
				</table>

				<div class="nav-tab-wrapper" id="egoi-tabs-widget">
					<a class="nav-tab-widget-settings nav-tab-active" id="nav-tab-widget-settings" style="cursor: pointer;"><?php _e( 'Settings', 'egoi-for-wp' ); ?></a>
					<span> | </span>
					<a class="nav-tab-widget-appearance" id="nav-tab-widget-appearance" style="cursor: pointer;"><?php _e( 'Appearance', 'egoi-for-wp' ); ?></a>
				</div>

				<div id="tab-widget-settings">
					<table class="form-table" style="table-layout: fixed;">

						<!-- Config list -->
						<tr valign="top">
							<th scope="row"><label><?php _e( 'Egoi List', 'egoi-for-wp' ); ?></label></th>
							<td>
							<span class="e-goi-lists_not_found" style="display: none;">
								<?php printf( __( 'No lists found, <a href="%s">are you connected to Egoi</a>?', 'egoi-for-wp' ), admin_url( 'admin.php?page=egoi-for-wp' ) ); ?>
							</span>

								<span id="e-goi-lists_ct_widget" style="display: none;"><?php echo esc_textarea($egoiwidget['list']); ?></span>

								<span class="loading loading_lists-widget" style="margin-left: 10px; display: none;"></span>
								<select name="egoi_widget[list]" class="lists" id="e-goi-list-widget" style="display: none;" required>
									<option disabled <?php selected( $egoiwidget['list'], '' ); ?>><?php _e( 'Select a list..', 'egoi-for-wp' ); ?></option>
								</select>
								<p class="help"><?php _e( 'Select the list to which visitors should be subscribed.', 'egoi-for-wp' ); ?></p>
							</td>
						</tr>

						<!-- END config list-->


						<!-- TAGS -->
						<tr valign="top">
							<th scope="row"><label for="egoi_tag_widget"><?php _e( 'Select a tag', 'egoi-for-wp' ); ?></label></th>
							<td>
								<div class="nav-tab-wrapper-tags" id="egoi-tabs-widget-tags">
									<a class="nav-tab-widget-egoi-tags nav-tab-active" id="nav-tab-widget-egoi-tags" style="cursor: pointer;"><?php _e( 'Select E-goi tags', 'egoi-for-wp' ); ?></a>
									<span> | </span>
									<a class="nav-tab-widget-new-tags" id="nav-tab-widget-new-tags" style="cursor: pointer;"><?php _e( 'Add new tag', 'egoi-for-wp' ); ?></a>
								</div>
								<br>

								<!-- TABS -->
								<div id="tab-widget-egoi-tags">
								<span class="egoi-tags_not_found" style="display: none;">
									<?php printf( __( 'No tags found, <a href="%s">are you connected to Egoi</a>?', 'egoi-for-wp' ), admin_url( 'admin.php?page=egoi-for-wp' ) ); ?>
								</span>

									<span id="e-goi-tags_ct_widget" style="display: none;"><?php echo esc_textarea($tag); ?></span>

									<span class="loading loading_tags-widget" style="margin-left: 10px; display: none;"></span>
									<select name="egoi_widget[tag-egoi]" class="tags" id="e-goi-tags-widget" style="display: none;">
										<option disabled <?php selected( $tag, '' ); ?>><?php _e( 'Select a tag..', 'egoi-for-wp' ); ?></option>
									</select>

									<p class="help"><?php _e( 'Select the tag to which visitors should be associated', 'egoi-for-wp' ); ?></p>
								</div>

								<div id="tab-widget-new-tags" style="display: none;">
									<input type="text" style="width:450px;" id="egoi_tag_widget" name="egoi_widget[tag]" placeholder="<?php _e( 'Choose a name for your new tag', 'egoi-for-wp' ); ?>" value="" />
									<p class="help"><?php _e( 'Create a new tag to which visitors should be associated', 'egoi-for-wp' ); ?></p>
								</div>

							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="egoi_form_sync_subscribed"><?php _e( 'Successfully subscribed', 'egoi-for-wp' ); ?></label></th>
							<td>
								<input type="text" style="width:450px;" id="egoi_form_sync_subscribed" placeholder="<?php _e( 'Your request has been successfully submitted. Thank you.', 'egoi-for-wp' ); ?>" name="egoi_widget[msg_subscribed]" value="<?php echo esc_attr( $egoiwidget['msg_subscribed'] ); ?>" />
								<p class="help"><?php _e( 'The text that shows when an email address is successfully subscribed to the selected list.', 'egoi-for-wp' ); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="egoi_form_sync_invalid_email"><?php _e( 'Invalid email address', 'egoi-for-wp' ); ?></label></th>
							<td>
								<input type="text" style="width:450px;" id="egoi_form_sync_invalid_email" placeholder="<?php _e( 'Check, please, if you wrote your e-mail address correctly.', 'egoi-for-wp' ); ?>" name="egoi_widget[msg_invalid]" value="<?php echo esc_attr( $egoiwidget['msg_invalid'] ); ?>" />
								<p class="help"><?php _e( 'The text that shows when an invalid email address is given.', 'egoi-for-wp' ); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="egoi_form_sync_email_empty"><?php _e( 'Empty email address', 'egoi-for-wp' ); ?></label></th>
							<td>
								<input type="text" style="width:450px;" placeholder="<?php _e( 'Your e-mail field is empty!', 'egoi-for-wp' ); ?>" id="egoi_form_sync_email_empty" name="egoi_widget[msg_empty]" value="<?php echo esc_attr( $egoiwidget['msg_empty'] ); ?>" />
								<p class="help"><?php _e( 'The text that shows when the email is empty.', 'egoi-for-wp' ); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="egoi_form_sync_already_subscribed"><?php _e( 'Already subscribed', 'egoi-for-wp' ); ?></label></th>
							<td>
								<input type="text" style="width:450px;" id="egoi_form_sync_already_subscribed" placeholder="<?php _e( 'The email address already exists in your list of contacts.', 'egoi-for-wp' ); ?>" name="egoi_widget[msg_exists_subscribed]" value="<?php echo esc_attr( $egoiwidget['msg_exists_subscribed'] ); ?>" />
								<p class="help"><?php _e( 'The text that shows when the given email is already subscribed to the selected list.', 'egoi-for-wp' ); ?></p>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e( 'Hide form after successful sign-up', 'egoi-for-wp' ); ?></th>
							<td class="nowrap">
								<label>
									<input type="radio" name="egoi_widget[hide_form]" value="1" <?php checked( $egoiwidget['hide_form'], 1 ); ?> />
									<?php _e( 'Yes', 'egoi-for-wp' ); ?>
								</label> &nbsp;
								<label>
									<input type="radio" name="egoi_widget[hide_form]" value="0" <?php checked( $egoiwidget['hide_form'], 0 ); ?> />
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
								<input type="text" style="width:450px;" name="egoi_widget[redirect]" id="egoi_form_sync_redirect" placeholder="<?php printf( __( 'Example: %s', 'egoi-for-wp' ), esc_attr( site_url( '/thank-you/' ) ) ); ?>" value="<?php echo esc_attr( $egoiwidget['redirect'] ); ?>" />
								<p class="help"><?php _e( 'Leave empty for no redirect. Otherwise, use complete (absolute) URLs.', 'egoi-for-wp' ); ?></p>
							</td>
						</tr>
					</table>
				</div>

				<div class="eg-tab" id="tab-widget-appearance">
					<table class="form-table">

						<tr valign="top">
							<th scope="row">
								<label>
									<?php _e( 'Input Width', 'egoi-for-wp' ); ?>
								</label>
							</th>
							<td>
								<input type="text" name="egoi_widget[input_width]" value="<?php echo esc_attr( $egoiwidget['input_width'] ); ?>">
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
								<input type="text" name="egoi_widget[btn_width]" value="<?php echo esc_attr( $egoiwidget['btn_width'] ); ?>">
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
								<input type="text" name="egoi_widget[bcolor]" value="<?php echo esc_attr( $egoiwidget['bcolor'] ); ?>" class="color">
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
								<input type="text" name="egoi_widget[bcolor_success]" value="<?php echo esc_attr( $egoiwidget['bcolor_success'] ); ?>" class="color">
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
								<input type="text" name="egoi_widget[bcolor_error]" value="<?php echo esc_attr( $egoiwidget['bcolor_error'] ); ?>" class="color">
								<p class="help"><?php _e( 'Change the color of the Widget Error message', 'egoi-for-wp' ); ?></p>
							</td>
						</tr>
					</table>
				</div>

				<button style="margin-top: 12px; margin-bottom: 30px;" id="egoi-widget-btn" type="submit" class="button button-primary"><?php _e( 'Save', 'egoi-for-wp' ); ?></button>

			</form>

		</div>
	</div>


	<script type="text/javascript">

		jQuery(document).ready(function($) {
			if(jQuery("#e-goi-lists_ct_widget").text() != ""){
				getListWidget(jQuery("#e-goi-lists_ct_widget").text());
			}
		});

		jQuery("#e-goi-list-widget").change(function(){

			var listID = jQuery("#e-goi-list-widget").val();

			getListWidget(listID);
		});

		function getListWidget(listID){
			var data_lists = {
				action: 'egoi_get_lists'
			};

			jQuery.post(url_egoi_script.ajaxurl, data_lists, function(response) {
				content = JSON.parse(response);
			});
		}

		jQuery("#egoi-widget-btn").on("click", function () {

			jQuery('#widget-submit-error').hide();

			if(jQuery("#e-goi-list-widget").val() == null ){
				jQuery('#widget-submit-error').show();
				return false;
			}

			var new_tag = jQuery("#egoi_tag_widget").val();

			if(new_tag != ''){
				var data = {
					action: 'egoi_add_tag',
					name: new_tag
				};

				jQuery.post(url_egoi_script.ajaxurl, data, function(response){
					tag = JSON.parse(response);
				});

				return false;
			}
		});

	</script>



<!-- Banner -->
<div class="sidebar" style="width: 200px;">
	<?php require 'egoi-for-wp-admin-banner.php'; ?>
</div>
