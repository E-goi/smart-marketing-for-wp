<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

// check if have a new tag in BD
if ( $this->bar_post['tag'] != '' ) {
	$info = $this->getTag( $this->bar_post['tag'] );
	$tag  = $info['ID'];
} else {
	$tag = $this->bar_post['tag-egoi'];
}
?>
<div class="main-content col eg-col-4">
<div class="wrap" id="egoi4wp-admin" style="width:100%;">

	<div id="egoi-tabs-bar">
		<!--	<a class="nav-tab-preview nav-tab-active" id="nav-tab-preview" onclick="tabs('preview_bar');"><?php // _e( 'Preview bar', 'egoi-for-wp' ); ?></a>
		<span> | </span> -->
		<a class="nav-tab-settings nav-tab-active" id="nav-tab-settings" ><?php _e( 'Settings', 'egoi-for-wp' ); ?></a>
		<span> | </span>
		<a class="nav-tab-appearance" id="nav-tab-appearance" ><?php _e( 'Appearance', 'egoi-for-wp' ); ?></a>
		<span> | </span>
		<a class="nav-tab-messages" id="nav-tab-messages" ><?php _e( 'Messages', 'egoi-for-wp' ); ?></a>
	</div>

	<form method="post" name="bar_options" action="<?php echo admin_url( 'options.php' ); ?>">
															  <?php
																settings_fields( Egoi_For_Wp_Admin::BAR_OPTION_NAME );
																settings_errors();
																?>

		<div id="bar-submit-error" style="display: none;">
			<div class="error notice">
				<p><?php _e( 'Please, choose the list.', 'egoi-for-wp' ); ?></p>
			</div>
		</div>

		<!-- Bar Settings -->
		<div id="tab-settings">

			<table class="form-table">

				<tr valign="top">
					<th scope="row">
						<label>
							<?php _e( 'Enable Bar?', 'egoi-for-wp' ); ?>
						</label>
					</th>
					<td>
						<label>
							<input type="radio" name="egoi_bar_sync[enabled]" value="1" <?php checked( $this->bar_post['enabled'], 1 ); ?> /> <?php _e( 'Yes' ); ?>
						</label>
						<label>
							<input type="radio" name="egoi_bar_sync[enabled]" value="0" <?php checked( $this->bar_post['enabled'], 0 ); ?> /> <?php _e( 'No' ); ?>
						</label>
						<p class="help"><?php _e( 'A valid way to completely disable the bar.', 'egoi-for-wp' ); ?></p>
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
							<input type="radio" name="egoi_bar_sync[double_optin]" value="1" <?php echo $this->bar_post['double_optin'] == 1 || $this->bar_post['list'] == 0 ? 'checked' : null; ?> /> <?php _e( 'Yes' ); ?>
						</label>
						<label>
							<input type="radio" name="egoi_bar_sync[double_optin]" value="0" <?php echo $this->bar_post['double_optin'] == 0 && $this->bar_post['list'] != 0 ? 'checked' : null; ?> /> <?php _e( 'No' ); ?>
						</label>
						<p class="help"><?php _e( 'If you activate the double opt-in, a confirmation e-mail will be send to the subscribers.', 'egoi-for-wp' ); ?></p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<label>
							<?php _e( 'Open Bar by default?', 'egoi-for-wp' ); ?>
						</label>
					</th>
					<td>
						<label>
							<input type="radio" name="egoi_bar_sync[open]" value="1" <?php checked( $this->bar_post['open'], 1 ); ?> /> <?php _e( 'Yes' ); ?>
						</label>
						<label>
							<input type="radio" name="egoi_bar_sync[open]" value="0" <?php checked( $this->bar_post['open'], 0 ); ?> /> <?php _e( 'No' ); ?>
						</label>
						<p class="help"><?php _e( 'Show or Hide by default the bar.', 'egoi-for-wp' ); ?></p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label><?php _e( 'Egoi List', 'egoi-for-wp' ); ?></label></th>
					<td>
							<span class="e-goi-lists_not_found" style="display: none;">
								<?php printf( __( 'No lists found, <a href="%s">are you connected to Egoi</a>?', 'egoi-for-wp' ), admin_url( 'admin.php?page=egoi-for-wp' ) ); ?>
							</span>

						<span id="e-goi-lists_ct_bar" style="display: none;"><?php echo $this->bar_post['list']; ?></span>

						<span class="loading loading_lists" style="margin-left: 10px; display: none;"></span>
						<select name="egoi_bar_sync[list]" class="lists" id="e-goi-list-bar" style="display: none;" required>
							<option disabled <?php selected( $this->bar_post['list'], '' ); ?>><?php _e( 'Select a list..', 'egoi-for-wp' ); ?></option>
						</select>
						<p class="help"><?php _e( 'Select the list to which visitors should be subscribed.', 'egoi-for-wp' ); ?></p>
					</td>
				</tr>

				<!-- Languages -->
				<tr valign="top">
					<th scope="row"><label id="egoi-lang" style="display: none;"><?php _e( 'E-goi List Language', 'egoi-for-wp' ); ?></label></th>
					<td>
						<span id="lang_bar" style="display: none;"><?php echo $this->bar_post['lang']; ?></span>

						<span class="loading loading_lang-bar" style="margin-left: 10px; display: none;" required></span>
						<select name="egoi_bar_sync[lang]"  id="e-goi-lang-bar" style="display: none;">
							<option disabled <?php selected( $this->bar_post['lang'], '' ); ?>><?php _e( 'Select a lang..', 'egoi-for-wp' ); ?></option>
						</select>
					</td>
				</tr>


				<!-- TAGS -->
				<tr valign="top">
					<th scope="row"><label for="egoi_tag_bar"><?php _e( 'Select a tag', 'egoi-for-wp' ); ?></label></th>
					<td>
						<div class="nav-tab-wrapper-tags" id="egoi-tabs-bar-tags">
							<a class="nav-tab-bar-egoi-tags nav-tab-active" id="nav-tab-bar-egoi-tags" style="cursor: pointer;"><?php _e( 'Select E-goi tags', 'egoi-for-wp' ); ?></a>
							<span> | </span>
							<a class="nav-tab-bar-new-tags" id="nav-tab-bar-new-tags" style="cursor: pointer;"><?php _e( 'Add new tag', 'egoi-for-wp' ); ?></a>
						</div>
						<br>

						<!-- TABS -->
						<div id="tab-bar-egoi-tags">
								<span class="egoi-tags_not_found" style="display: none;">
									<?php printf( __( 'No tags found, <a href="%s">are you connected to Egoi</a>?', 'egoi-for-wp' ), admin_url( 'admin.php?page=egoi-for-wp' ) ); ?>
								</span>

							<span id="e-goi-tags_ct_bar" style="display: none;"><?php echo $tag; ?></span>

							<span class="loading loading_tags-bar" style="margin-left: 10px; display: none;"></span>
							<select name="egoi_bar_sync[tag-egoi]" class="tags" id="e-goi-tags-bar" style="display: none;">
								<option disabled <?php selected( $tag, '' ); ?>><?php _e( 'Select a tag..', 'egoi-for-wp' ); ?></option>
							</select>

							<p class="help"><?php _e( 'Select the tag to which visitors should be associated', 'egoi-for-wp' ); ?></p>
						</div>

						<div id="tab-bar-new-tags" style="display: none;">
							<input type="text" style="width:450px;" id="egoi_tag" name="egoi_bar_sync[tag]" placeholder="<?php _e( 'Choose a name for your new tag', 'egoi-for-wp' ); ?>" value=""/>
							<p class="help"><?php _e( 'Create a new tag to which visitors should be associated', 'egoi-for-wp' ); ?></p>
						</div>

					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<label>
							<?php _e( 'Bar Text', 'egoi-for-wp' ); ?>
						</label>
					</th>
					<td>
						<input type="text" name="egoi_bar_sync[text_bar]" value="<?php echo $this->bar_post['text_bar']; ?>" class="regular-text" />
						<p class="help"><?php _e( 'The text to appear before the email field.', 'egoi-for-wp' ); ?></p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<label>
							<?php _e( 'Button Text', 'egoi-for-wp' ); ?>
						</label>
					</th>
					<td>
						<input type="text" name="egoi_bar_sync[text_button]" value="<?php echo $this->bar_post['text_button']; ?>" class="regular-text" />
						<p class="help"><?php _e( 'The text on the submit button.', 'egoi-for-wp' ); ?></p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<label>
							<?php _e( 'Email Placeholder Text', 'egoi-for-wp' ); ?>
						</label>
					</th>
					<td>
						<input type="text" name="egoi_bar_sync[text_email_placeholder]" value="<?php echo $this->bar_post['text_email_placeholder']; ?>" class="regular-text" />
						<p class="help"><?php _e( 'The initial placeholder text to appear in the email field.', 'egoi-for-wp' ); ?></p>
					</td>
				</tr>
			</table>
		</div>


		<!-- Appearance Tab -->
		<div class="eg-tab" id="tab-appearance">

			<div class="row">
				<div class="col col-2">
					<table class="form-table">

						<tr valign="top">
							<th scope="row">
								<label>
									<?php _e( 'Bar Position', 'egoi-for-wp' ); ?>
								</label>
							</th>
							<td>
								<select name="egoi_bar_sync[position]" id="select-bar-position">
									<option value="top" <?php selected( $this->bar_post['position'], 'top' ); ?>><?php _e( 'Top', 'egoi-for-wp' ); ?></option>
									<option value="bottom" <?php selected( $this->bar_post['position'], 'bottom' ); ?>><?php _e( 'Bottom', 'egoi-for-wp' ); ?></option>
								</select>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row" style="width: 100px;">
								<label>
									<?php _e( 'Bar Fixed?', 'egoi-for-wp' ); ?>
								</label>
							</th>
							<td>
								<label>
									<input type="radio" name="egoi_bar_sync[sticky]" value="1" <?php checked( $this->bar_post['sticky'], 1 ); ?> /> <?php _e( 'Yes' ); ?>
								</label>
								<label>
									<input type="radio" name="egoi_bar_sync[sticky]" value="0" <?php checked( $this->bar_post['sticky'], 0 ); ?> /> <?php _e( 'No' ); ?>
								</label>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<label>
									<?php _e( 'Background Color', 'egoi-for-wp' ); ?>
								</label>
							</th>
							<td>
								<input type="text" name="egoi_bar_sync[color_bar]" value="<?php echo esc_attr( $this->bar_post['color_bar'] ); ?>" class="color">
							</td>
						</tr>

						<tr valign="top">
							<th scope="row" style="width: 100px;">
								<label>
									<?php _e( 'Text Color', 'egoi-for-wp' ); ?>
								</label>
							</th>
							<td>
								<input type="text" name="egoi_bar_sync[bar_text_color]" value="<?php echo esc_attr( $this->bar_post['bar_text_color'] ); ?>" class="color">
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<label>
									<?php _e( 'Border Size', 'egoi-for-wp' ); ?>
								</label>
							</th>
							<td>
								<input type="text" style="width: 50px;" name="egoi_bar_sync[border_px]" value="<?php echo esc_attr( $this->bar_post['border_px'] ); ?>" class="regular-text">
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<label>
									<?php _e( 'Border Color', 'egoi-for-wp' ); ?>
								</label>
							</th>
							<td>
								<input type="text" name="egoi_bar_sync[border_color]" value="<?php echo esc_attr( $this->bar_post['border_color'] ); ?>" class="color">
							</td>
						</tr>

					</table>
				</div>
				<div class="col col-2">
					<table class="form-table">

						<tr valign="top">
							<th scope="row">
								<label>
									<?php _e( 'Button Color', 'egoi-for-wp' ); ?>
								</label>
							</th>
							<td>
								<input type="text" name="egoi_bar_sync[color_button]" value="<?php echo esc_attr( $this->bar_post['color_button'] ); ?>" class="color">
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<label>
									<?php _e( 'Button Text Color', 'egoi-for-wp' ); ?>
								</label>
							</th>
							<td>
								<input type="text" name="egoi_bar_sync[color_button_text]" value="<?php echo esc_attr( $this->bar_post['color_button_text'] ); ?>" class="color">
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<label>
									<?php _e( 'Background Color on Success', 'egoi-for-wp' ); ?>
								</label>
							</th>
							<td>
								<input type="text" name="egoi_bar_sync[success_bgcolor]" value="<?php echo esc_attr( $this->bar_post['success_bgcolor'] ); ?>" class="color">
								<p class="help"><?php _e( 'Change the color of the Success message', 'egoi-for-wp' ); ?></p>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<label>
									<?php _e( 'Background Color on Error', 'egoi-for-wp' ); ?>
								</label>
							</th>
							<td>
								<input type="text" name="egoi_bar_sync[error_bgcolor]" value="<?php echo esc_attr( $this->bar_post['error_bgcolor'] ); ?>" class="color">
								<p class="help"><?php _e( 'Change the color of the Error message', 'egoi-for-wp' ); ?></p>
							</td>
						</tr>

					</table>
				</div>
			</div>
			<br style="clear: both;" />
		</div>

		<!-- Form Messages -->
		<div class="eg-tab" id="tab-messages">

			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label><?php _e( 'Success', 'egoi-for-wp' ); ?></label></th>
					<td><input type="text" class="widefat" name="egoi_bar_sync[text_subscribed]" value="<?php echo esc_attr( $this->bar_post['text_subscribed'] ) ?: __( 'Success Subscribed', 'egoi-for-wp' ); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label><?php _e( 'Invalid email address', 'egoi-for-wp' ); ?></label></th>
					<td><input type="text" class="widefat" name="egoi_bar_sync[text_invalid_email]" value="<?php echo esc_attr( $this->bar_post['text_invalid_email'] ) ?: __( 'Invalid e-mail', 'egoi-for-wp' ); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label><?php _e( 'Already subscribed', 'egoi-for-wp' ); ?></label></th>
					<td><input type="text" class="widefat" name="egoi_bar_sync[text_already_subscribed]" value="<?php echo esc_attr( $this->bar_post['text_already_subscribed'] ) ?: __( 'Subscriber already exists', 'egoi-for-wp' ); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label><?php _e( 'Already subscribed and waiting for confirmation e-mail', 'egoi-for-wp' ); ?></label></th>
					<td><input type="text" class="widefat" name="egoi_bar_sync[text_waiting_for_confirmation]" value="<?php echo esc_attr( $this->bar_post['text_waiting_for_confirmation'] ) ?: __( 'Already subscribed and waiting for confirmation e-mail', 'egoi-for-wp' ); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label><?php _e( 'Other errors', 'egoi-for-wp' ); ?></label></th>
					<td><input type="text" class="widefat" name="egoi_bar_sync[text_error]" placeholder="<?php _e( 'Eg. List Missing from E-goi', 'egoi-for-wp' ); ?>"  value="<?php echo esc_attr( $this->bar_post['text_error'] ); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label>
							<?php _e( 'Redirect to URL after successful sign-up', 'egoi-for-wp' ); ?>
						</label>
					</th>
					<td>
						<input type="text" name="egoi_bar_sync[redirect]" placeholder="<?php echo esc_url( $this->bar_post['redirect'] ); ?>" value="<?php echo esc_url( $this->bar_post['redirect'] ); ?>" class="widefat" />
						<p class="help"><?php _e( 'Leave empty for no redirect. Otherwise, use complete (absolute) URLs, including <code>http://</code>.', 'egoi-for-wp' ); ?></p>
					</td>
				</tr>

			</table>
		</div>
		<button style="margin-top: 12px; margin-bottom: 30px;" id="egoi-bar-btn" type="submit" class="button button-primary"><?php _e( 'Save', 'egoi-for-wp' ); ?></button>

		<?php // submit_button(); ?>
	</form>



</div>
</div>

	<!-- Banner -->
	<div class="sidebar" style="width: 220px;">
		<?php require 'egoi-for-wp-admin-banner.php'; ?>
	</div>



<script type="text/javascript">

	jQuery(document).ready(function($) {

		$('#nav-tab-bar-egoi-tags').click(function() {
			$('#tab-bar-new-tags').hide();
			$('#tab-bar-egoi-tags').show();
			$('#egoi_tag').val('');
			$(this).addClass('nav-tab-active');
			$('#nav-tab-bar-new-tags').removeClass('nav-tab-active');
		});

		$('#nav-tab-bar-new-tags').click(function() {
			$('#tab-bar-new-tags').show();
			$('#tab-bar-egoi-tags').hide();
			$('#e-goi-tags-bar').val('');
			$(this).addClass('nav-tab-active');
			$('#nav-tab-bar-egoi-tags').removeClass('nav-tab-active');
		});

		if(jQuery("#e-goi-lists_ct_bar").text() != ""){
			jQuery('#egoi-lang').show();
			getListLang(jQuery("#e-goi-lists_ct_bar").text());
		}

		getTagsBar();
	});

	jQuery("#e-goi-list-bar").change(function(){
		jQuery('#e-goi-lang-bar').hide();
		jQuery('#egoi-lang').show();
		jQuery('#e-goi-lang-bar').empty();
		jQuery(".loading_lang-bar").addClass('spin').show();

		var listID = jQuery("#e-goi-list-bar").val();

		getListLang(listID);
	});


	function getListLang(listID){

		var data_lists = {
			action: 'egoi_get_lists'
		};

		jQuery.post(url_egoi_script.ajaxurl, data_lists, function(response) {

			content = JSON.parse(response);

			jQuery('#e-goi-lang-bar').show();

			var idiomas = [];
			jQuery.each(content, function(key, val) {

				if(val.listnum == listID){
					var idioma = val.idioma;
					var idiomas_extra = val.idiomas_extra;

					var idiomas = [];

					idiomas.push(idioma);
					if (idiomas_extra != "") {
						jQuery.each(idiomas_extra, function(key, val){
							idiomas.push(val);
						});
					}

					jQuery.each(idiomas, function(key, val){
						var field_text = jQuery('<option />').html(val.title).text();
						if(jQuery('#lang_bar').text() != "" && jQuery('#lang_bar').text() == val){
							jQuery("#e-goi-lang-bar").append('<option selected value="' + val + '">' + val + '</option>');
						}
						else{
							jQuery("#e-goi-lang-bar").append('<option value="' + val + '">' + val + '</option>');
						}
					});

					jQuery(".loading_lang-bar").removeClass('spin').hide();
				}

			});

		});
	}

	function getTagsBar(){
		var data = {
			action: 'egoi_get_tags'
		}

		var select_tags = jQuery('#e-goi-tags-bar');

		var tags = [];

		jQuery(".loading_tags-bar").addClass('spin').show();
		var lists_count_tags_bar = jQuery('#e-goi-tags_ct_bar');

		jQuery.post(url_egoi_script.ajaxurl, data, function(response) {
			tags = JSON.parse(response);

			if(tags.ERROR){
				jQuery('.egoi-tags_not_found').show();
				select_tags.hide();
				jQuery(".loading_tags").removeClass('spin').hide();

			}else{

				select_tags.show();

				jQuery('.e-goi-tags_not_found').hide();

				jQuery.each(tags['TAG_LIST'], function(key, val) {

					if(typeof val.ID != 'undefined') {
						var field_text = jQuery('<option />').html(val.NAME).text();
						select_tags.append(jQuery('<option />').val(val.ID).text(field_text));

						if(lists_count_tags_bar.text() === val.ID){
							select_tags.val(val.ID);
						}
					}
				});
				jQuery(".loading_tags-bar").removeClass('spin').hide();
			}

		});
	}

	jQuery("#egoi-bar-btn").on("click", function () {
		jQuery('#bar-submit-error').hide();

		if(jQuery("#e-goi-list-bar").val() == null && jQuery("#e-goi-lang-bar").val() == null){

			jQuery('#bar-submit-error').show();
			return false;
		}

		var new_tag = jQuery("#egoi_tag").val();

		if(new_tag != ''){
			var data = {
				action: 'egoi_add_tag',
				name: new_tag
			};

			jQuery.post(url_egoi_script.ajaxurl, data, function(response){
				tag = JSON.parse(response);
			});
		}
	});
</script>
