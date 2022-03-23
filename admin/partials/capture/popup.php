<?php

require_once plugin_dir_path( __FILE__ ) . '../../../includes/class-egoi-for-wp-popup.php';
require_once plugin_dir_path( __FILE__ ) . 'functions.php';
if ( ! empty( $_POST ) ) {
	$id = EgoiPopUp::savePostPopup( $_POST );
	if ( $id !== false ) {
		echo get_notification( __( 'Popups', 'egoi-for-wp' ), __( 'Your popup was saved successfully', 'egoi-for-wp' ) );
		if ( $_POST['popup_id'] == 'new' ) {
			wp_redirect( '?page=egoi-4-wp-form&highlight=' . $id );
			exit;
		}
	} else {
		echo get_notification( __( 'Popups', 'egoi-for-wp' ), __( 'Your popup information is not correct', 'egoi-for-wp' ), 'error' );
	}
}

$popup_id = empty( $_GET['popup_id'] ) ? 'new' : sanitize_key( trim( $_GET['popup_id'] ) );

$popup      = new EgoiPopUp( $popup_id );
$popup_data = $popup->getPopupSavedData();

$content   = stripslashes( $popup_data['content'] );
$editor_id = 'content';

?>


<form id="smsnf-popup-form" method="post" action="#">

	<input type="hidden" id="popup_id" name="popup_id" value="<?php echo esc_attr($popup_id); ?>">


	<ul class="tab">
		<li class="tab-item active">
			<a href="#" tab-target="smsnf-configuration"><?php _e( 'Settings', 'egoi-for-wp' ); ?></a>
		</li>
		<li class="tab-item">
			<a href="#" tab-target="smsnf-appearance"><?php _e( 'Appearance', 'egoi-for-wp' ); ?></a>
		</li>
		<li class="tab-item">
			<a href="#" tab-target="smsnf-layout"><?php _e( 'Layout', 'egoi-for-wp' ); ?></a>
		</li>
	</ul>

	<div id="smsnf-configuration" class="smsnf-tab-content active">
		<div>

			<div class="smsnf-input-group" style="display: flex;flex-direction: column;">
				<label for="title"><?php _e( 'Form title', 'egoi-for-wp' ); ?></label>
				<input  id="title" type="text"
						name="title" size="30" spellcheck="true" autocomplete="off" pattern="\S.*\S"
						value="<?php echo htmlentities( stripslashes( $popup_data['title'] ) ); ?>"
						placeholder="<?php _e( 'Write here the title of your form', 'egoi-for-wp' ); ?>" />
			</div>

			<!-- SIMPLE FORM -->
			<div class="smsnf-input-group">
				<label for="form_id"><?php _e( 'Form', 'egoi-for-wp' ); ?></label>
				<select name="form_id" class="form-select " id="form_id">

					<option value="new" selected disabled hidden><?php _e( 'Select a form...', 'egoi-for-wp' ); ?></option>
					<option value="disabled" ><?php _e( 'No Form', 'egoi-for-wp' ); ?></option>

					<?php
					foreach ( get_simple_forms() as $form ) {
						echo '<option value="' . esc_attr($form->ID) . '" ' . selected( $form->ID, $popup_data['form_id'] ) . '>' . esc_textarea($form->post_title) . '</option>';
					}
					?>
				</select>
			</div>
			<!-- / SIMPLE FORM -->


			<div class="smsnf-input-group" style="margin-block-end: 0px;margin-bottom: 12px;">
				<label for="form_border_color"><?php _e( 'Customize', 'egoi-for-wp' ); ?></label>
			</div>
			<?php wp_editor( $content, $editor_id ); ?>
			<div class="smsnf-input-group" style="margin-top: 24px;">
				<label for="page_trigger"><?php _e( 'Target Page', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php _e( 'Configure rules for target page <b>URL</b>', 'egoi-for-wp' ); ?></p>
				<select name="page_trigger_rule" class="form-select " id="page_trigger_rule">
					<option value="contains" <?php selected( $popup_data['page_trigger_rule'], 'contains' ); ?>><?php _e( 'Include', 'egoi-for-wp' ); ?></option>
					<option value="not_contains" <?php selected( $popup_data['page_trigger_rule'], 'not_contains' ); ?> ><?php _e( 'Exclude', 'egoi-for-wp' ); ?></option>
				</select>
				<div class="page_trigger_select" >
					<select class="js-example-basic-multiple" name="page_trigger[]" id="page_trigger" multiple="multiple" style="max-width: 400px;">
						<?php foreach ( get_pages() as $available_posts ) { ?>
							<option id="page_<?php echo esc_attr($available_posts->ID); ?>" value="<?php echo esc_attr($available_posts->ID); ?>"
								<?php
								if ( in_array( $available_posts->ID, $popup_data['page_trigger'] ) ) {
									echo 'selected';}
								?>
								>
								<?php echo esc_textarea($available_posts->post_title); ?>
							</option>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="smsnf-input-group">
				<label for="trigger"><?php _e( 'Popup Trigger', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php _e( 'This will dictate the trigger rule', 'egoi-for-wp' ); ?></p>
				<select name="trigger" class="form-select " id="trigger">
					<option value="delay" <?php selected( $popup_data['trigger'], 'delay' ); ?>><?php _e( 'Delay', 'egoi-for-wp' ); ?></option>
					<option value="on_leave"  <?php selected( $popup_data['trigger'], 'on_leave' ); ?>><?php _e( 'On Leave', 'egoi-for-wp' ); ?></option>
				</select>
				<input name="trigger_option" id="trigger_option" value="<?php echo esc_attr($popup_data['trigger_option']); ?>" placeholder="<?php _e( 'Time in seconds here', 'egoi-for-wp' ); ?>" style="display: none;max-width: 400px;">
			</div>

			<div class="smsnf-input-group">
				<label for="show_until"><?php _e( 'Popup Trigger Stop', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php _e( 'Choose when your popup will stop showing', 'egoi-for-wp' ); ?></p>
				<select name="show_until" class="form-select " id="show_until">
					<option value="one_time" <?php selected( $popup_data['show_until'], 'one_time' ); ?> ><?php _e( 'One Time', 'egoi-for-wp' ); ?></option>
					<option value="until_submition" <?php selected( $popup_data['show_until'], 'until_submition' ); ?> ><?php _e( 'Until Submission', 'egoi-for-wp' ); ?></option>
				</select>
			</div>

			<div class="smsnf-input-group">
				<label for="show_logged"><?php _e( 'Logged in Users', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php _e( 'Do you want this popup to show in already identified users?', 'egoi-for-wp' ); ?></p>
				<select name="show_logged" class="form-select " id="show_logged">
					<option value="yes" <?php selected( $popup_data['show_logged'], 'yes' ); ?> ><?php _e( 'Yes', 'egoi-for-wp' ); ?></option>
					<option value="no" <?php selected( $popup_data['show_logged'], 'no' ); ?> ><?php _e( 'No', 'egoi-for-wp' ); ?></option>
				</select>
			</div>

			<div class="smsnf-input-group">
				<label for="show_device"><?php _e( 'Devices', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php _e( 'Choose the devices you want the popup to trigger', 'egoi-for-wp' ); ?></p>
				<select name="show_device" class="form-select " id="show_logged">
					<option value="all" <?php selected( $popup_data['show_device'], 'all' ); ?> ><?php _e( 'All', 'egoi-for-wp' ); ?></option>
					<option value="desktop" <?php selected( $popup_data['show_device'], 'desktop' ); ?> ><?php _e( 'Desktop', 'egoi-for-wp' ); ?></option>
					<option value="mobile" <?php selected( $popup_data['show_device'], 'mobile' ); ?> ><?php _e( 'Mobile', 'egoi-for-wp' ); ?></option>
				</select>
			</div>

		</div>
	</div>

	<div id="smsnf-appearance" class="smsnf-tab-content">


		<div class="smsnf-input-group">
			<label for="form-position"><?php _e( 'Display Position', 'egoi-for-wp' ); ?></label>
			<p class="subtitle"><?php _e( 'This will dictate the popup position', 'egoi-for-wp' ); ?></p>
			<div class="smsnf-adv-forms">
				<div id="form-position" class="smsnf-adv-forms-types" style="grid-template-columns: 1fr 1fr;">
					<label>
						<input type="radio" name="type" value="center" <?php checked( $popup_data['type'], 'center' ); ?> />
						<div>
							<p><?php _e( 'Center', 'egoi-for-wp' ); ?></p>
							<div>
								<img src="<?php echo plugin_dir_url( __DIR__ ) . '../img/icon_popup.png'; ?>" />
							</div>
						</div>
					</label>
					<label>
						<input type="radio" name="type" value="rightside" <?php checked( $popup_data['type'], 'rightside' ); ?> />
						<div>
							<p><?php _e( 'Right Side', 'egoi-for-wp' ); ?></p>
							<div>
								<img src="<?php echo plugin_dir_url( __DIR__ ) . '../img/icon_small_popup.svg'; ?>" />
							</div>
						</div>
					</label>
				</div>
			</div>
		</div>

		<!-- BORDER RADIUS -->
		<div class="smsnf-input-group">
			<label for="bar-position"><?php _e( 'Border Radius', 'egoi-for-wp' ); ?>: <span id="border_range_label"><?php echo empty( $popup_data['border_radius'] ) ? '0' : esc_textarea( $popup_data['border_radius'] ); ?>px</span></label>
			<input style="max-width: 400px;border: 0 !important;" type="range" min="0" max="20" value="<?php echo esc_attr( $popup_data['border_radius'] ); ?>" class="slider" name="border_radius" id="border_radius" >
		</div>
		<!-- / BORDER RADIUS -->


		<div class="smsnf-input-group">
			<label for="background_color"><?php _e( 'Background Color', 'egoi-for-wp' ); ?></label>
			<div class="colorpicker-wrapper" style="max-width: 400px;">
				<div style="background-color:<?php echo esc_attr( $popup_data['background_color'] ); ?>" class="view" ></div>
				<input id="background_color" type="text" name="background_color" value="<?php echo esc_attr( $popup_data['background_color'] ); ?>"  autocomplete="off" />
				<p><?php _e( 'Select Color', 'egoi-for-wp' ); ?></p>
			</div>
		</div>

		<div class="smsnf-input-group">
			<label for="font_color"><?php _e( 'Font Color', 'egoi-for-wp' ); ?></label>
			<div class="colorpicker-wrapper" style="max-width: 400px;">
				<div style="background-color:<?php echo esc_attr( $popup_data['font_color'] ); ?>" class="view" ></div>
				<input id="font_color" type="text" name="font_color" value="<?php echo esc_attr( $popup_data['font_color'] ); ?>"  autocomplete="off" />
				<p><?php _e( 'Select Color', 'egoi-for-wp' ); ?></p>
			</div>
		</div>

		<div class="smsnf-input-group">
			<label for="form_border_color"><?php _e( 'Custom Css', 'egoi-for-wp' ); ?></label>
			<?php
			// do_action( 'wp_enqueue_code_editor', array('type' => 'text/css') );
			wp_enqueue_code_editor(
				array(
					'type'       => 'text/css',
					'codemirror' => array(
						'autoRefresh' => true,
					),
				)
			);

			?>
			<fieldset>
				<textarea id="custom_css" rows="5" name="custom_css" class="widefat textarea"><?php echo wp_unslash( $popup_data['custom_css'] ); ?></textarea>
			</fieldset>
		</div>

	</div>

	<div id="smsnf-layout" class="smsnf-tab-content">

		<div class="smsnf-input-group">
			<label for="popup-layout"><?php _e( 'Popup Layout', 'egoi-for-wp' ); ?></label>
			<p class="subtitle"><?php _e( 'You can choose to divide the popup with an image', 'egoi-for-wp' ); ?></p>
			<div class="smsnf-adv-forms">
				<div id="popup-layout" class="smsnf-adv-forms-types" style="grid-template-columns: 1fr 1fr 1fr;">
					<label>
						<input type="radio" name="popup_layout" value="simple" <?php checked( $popup_data['popup_layout'], 'simple' ); ?> />
						<div class="egoi-checkbox-big-pannel">
							<p><?php _e( 'Simple', 'egoi-for-wp' ); ?></p>
							<div>
								<img src="<?php echo plugin_dir_url( __DIR__ ) . '../img/icon_popup.png'; ?>" />
							</div>
						</div>
					</label>
					<label>
						<input type="radio" name="popup_layout" value="left_image" <?php checked( $popup_data['popup_layout'], 'left_image' ); ?> />
						<div class="egoi-checkbox-big-pannel">
							<p><?php _e( 'Left Image', 'egoi-for-wp' ); ?></p>
							<div>
								<img src="<?php echo plugin_dir_url( __DIR__ ) . '../img/icon_left_image_popup.svg'; ?>" />
							</div>
						</div>
					</label>
					<label>
						<input type="radio" name="popup_layout" value="right_image" <?php checked( $popup_data['popup_layout'], 'right_image' ); ?> />
						<div class="egoi-checkbox-big-pannel">
							<p><?php _e( 'Right Image', 'egoi-for-wp' ); ?></p>
							<div>
								<img src="<?php echo plugin_dir_url( __DIR__ ) . '../img/icon_right_image_popup.svg'; ?>" />
							</div>
						</div>
					</label>
				</div>
			</div>
		</div>

		<div class="smsnf-input-group" class="select-image">
			<label for="side_image"><?php _e( 'Side Image', 'egoi-for-wp' ); ?></label>
			<p class="subtitle"><?php _e( 'Pick an image from your gallery', 'egoi-for-wp' ); ?></p>
			<div>
				<div class='image-preview-wrapper egoi-image-selector-preview <?php echo empty( $popup_data['side_image'] ) ? '' : 'egoi-image-selector-preview--selected'; ?>' style="background-image: url(<?php echo wp_get_attachment_url( $popup_data['side_image'] ); ?>);">
					<?php if ( empty( $popup_data['side_image'] ) ) { ?>
						<i class="far fa-image" aria-hidden="true"></i>
						<span><?php _e( 'Upload Image', 'egoi-for-wp' ); ?></span>
					<?php } else { ?>
						<span class="dashicons dashicons-no popup_remove_side_image"></span>
					<?php } ?>
				</div>
			</div>

			<input type='hidden' name='side_image' id='side_image' value='<?php echo esc_attr( $popup_data['side_image'] ); ?>'>
		</div>


		<div class="smsnf-input-group">
			<label for="form_orientation"><?php _e( 'Form Orientation', 'egoi-for-wp' ); ?></label>
			<p class="subtitle"><?php _e( 'Disable this if you want to use customized setting', 'egoi-for-wp' ); ?></p>
			<select name="form_orientation" class="form-select " id="form_orientation">
				<option value="off" <?php selected( $popup_data['form_orientation'], 'off' ); ?> ><?php _e( 'Disabled', 'egoi-for-wp' ); ?></option>
				<option value="vertical" <?php selected( $popup_data['form_orientation'], 'vertical' ); ?> ><?php _e( 'Vertical', 'egoi-for-wp' ); ?></option>
                <option value="horizontal" <?php selected( $popup_data['form_orientation'], 'horizontal' ); ?> ><?php _e( 'Horizontal', 'egoi-for-wp' ); ?></option>
			</select>
		</div>

		<div class="smsnf-input-group">
			<label for="max_width"><?php _e( 'Popup Max Width', 'egoi-for-wp' ); ?></label>
			<p class="subtitle"><?php _e( 'Configure rules for target page', 'egoi-for-wp' ); ?></p>
			<input style="max-width: 400px;" id="max_width" type="text"
				   value="<?php echo esc_attr( $popup_data['max_width'] ); ?>"
				   name="max_width" autocomplete="off"
				   placeholder="<?php _e( 'write in px, vh or %', 'egoi-for-wp' ); ?>" />
		</div>

	</div>
	<div class="egoi-undertable-button-wrapper" style="bottom: 0;position: absolute;right: 30px;">
		<div class="smsnf-input-group">
			<input type="submit" id="sava_changes_popup" value="<?php echo $popup_id == 'new' ? __( 'Create', 'egoi-for-wp' ) : __( 'Save Changes', 'egoi-for-wp' ); ?>" />
		</div>
	</div>
</form>

<style>
	.select2-selection--multiple{
		width: 400px !important;
		max-width: 400px !important;
		margin-top: 12px;
	}
	select, input{
		max-width: 400px !important;
	}
</style>
