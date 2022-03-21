<?php
// cria/atualiza widget
if ( isset( $_POST['action'] ) && isset( $_POST['egoi_widget'] )  ) {

	update_option( sanitize_key($_POST['egoiform']), [
            'egoi_widget' => [
                'enabled' => sanitize_key($_POST['egoi_widget']['enabled']),
                'double_optin' => sanitize_key($_POST['egoi_widget']['double_optin']),
                'list' =>  sanitize_key($_POST['egoi_widget']['list']),
                'lang' => sanitize_text_field($_POST['egoi_widget']['lang']),
                'tag-egoi' => sanitize_key($_POST['egoi_widget']['tag-egoi']),
                'msg_subscribed' => sanitize_text_field($_POST['egoi_widget']['msg_subscribed']),
                'msg_invalid' => sanitize_text_field($_POST['egoi_widget']['msg_invalid']),
                'msg_empty' => sanitize_text_field($_POST['egoi_widget']['msg_empty']),
                'msg_exists_subscribed' => sanitize_text_field($_POST['egoi_widget']['msg_exists_subscribed']),
                'redirect' => esc_url($_POST['egoi_widget']['redirect']),
                'input_width' => sanitize_text_field($_POST['egoi_widget']['input_width']),
                'btn_width' => sanitize_text_field($_POST['egoi_widget']['btn_width']),
                'bcolor' => sanitize_text_field($_POST['egoi_widget']['bcolor']),
                'bcolor_success' => sanitize_text_field($_POST['egoi_widget']['bcolor_success']),
                'bcolor_error' => sanitize_text_field($_POST['egoi_widget']['bcolor_error']),
            ]
    ] );

	echo get_notification( 'Widget', __( 'Widget saved successfully!', 'egoi-for-wp' ) );
}

$opt_widget = get_option( 'egoi_widget' );
$egoiwidget = $opt_widget['egoi_widget'];
if ( empty( $egoiwidget ) ) {
	$egoiwidget = array();
}

if ( $egoiwidget['tag'] != '' ) {
	$info = $this->egoiWpApi->getTag( $egoiwidget['tag'] );
	$tag  = $info['ID'];
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

require plugin_dir_path( __DIR__ ) . 'egoi-for-wp-admin-shortcodes.php';
$FORM_OPTION = get_optionsform( $form_id );
?>

<form id="smsnf-widget-options" action="" method="post">
	<?php settings_fields( $FORM_OPTION ); ?>

	<input type="hidden" name="widget" value="1">
	<input type="hidden" name="egoiform" value="egoi_widget">
	
	<!-- enable widget -->
	<div class="smsnf-input-group">
		<label for="enable-widget">Enable Widgets</label>
		<div class="form-group switch-yes-no">
			<label class="form-switch">
				<input id="enable-widget" name="egoi_widget[enabled]" value="1" <?php checked( $egoiwidget['enabled'], 1 ); ?> type="checkbox">
				<i class="form-icon"></i><div class="yes"><?php _e( 'Yes', 'egoi-for-wp' ); ?></div><div class="no"><?php _e( 'No', 'egoi-for-wp' ); ?></div>
			</label>
		</div>
	</div>
	<!-- / enable widget -->
	<!-- Double Opt-In -->
	<div class="smsnf-input-group">
		<label for="widget_double_optin"><?php _e( 'Enable Double Opt-In?', 'egoi-for-wp' ); ?></label>
		<p class="subtitle"><?php _e( 'If you activate the double opt-in, a confirmation e-mail will be send to the subscribers.', 'egoi-for-wp' ); ?></p>
		<div class="form-group switch-yes-no">
			<label class="form-switch">
				<?php $double_optin_enable = $egoiwidget['double_optin'] == 1 || $egoiwidget['list'] == 0; ?>
				<input id="widget_double_optin" name="egoi_widget[double_optin]" value="1" <?php checked( $double_optin_enable, 1 ); ?> type="checkbox">
				<i class="form-icon"></i><div class="yes"><?php _e( 'Yes', 'egoi-for-wp' ); ?></div><div class="no"><?php _e( 'No', 'egoi-for-wp' ); ?></div>
			</label>
		</div>
	</div>
	<!-- / Double Opt-In -->
	<!-- TAB -->
	<ul class="tab">
		<li class="tab-item active">
			<a href="#" tab-target="smsnf-configuration"><?php _e( 'Settings', 'egoi-for-wp' ); ?></a>
		</li>
		<li class="tab-item">
			<a href="#" tab-target="smsnf-appearance"><?php _e( 'Appearance', 'egoi-for-wp' ); ?></a>
		</li>
	</ul>
	<!-- / TAB -->
	<!-- Configuration -->
	<div id="smsnf-configuration" class="smsnf-tab-content smsnf-grid active">
		<div>
			<!-- LIST -->
			<?php get_list_html( $egoiwidget['list'], 'egoi_widget[list]' ); ?>
			<!-- / LIST -->
			<!-- lang -->
			<?php get_lang_html( $egoiwidget['lang'], 'egoi_widget[lang]', empty( $egoiwidget['list'] ) ); ?>
			<!-- / lang -->
			<!-- tag -->
			<?php get_tag_html( $tag, 'egoi_widget[tag-egoi]' ); ?>
			<!-- / tag -->
			<!-- success msg -->
			<div class="smsnf-input-group ">
				<label for="widget-success-msg"><?php _e( 'Successfully subscribed', 'egoi-for-wp' ); ?></label>
				<input id="widget-success-msg" type="text" name="egoi_widget[msg_subscribed]" value="<?php echo esc_attr( $egoiwidget['msg_subscribed'] ); ?>" placeholder="<?php _e( 'Your request has been successfully submitted. Thank you.', 'egoi-for-wp' ); ?>" autocomplete="off" />
			</div>
			<!-- / success msg -->
			<!-- error msg -->
			<div class="smsnf-input-group ">
				<label for="widget-error-msg"><?php _e( 'Invalid email address', 'egoi-for-wp' ); ?></label>
				<input id="widget-error-msg" type="text" name="egoi_widget[msg_invalid]" value="<?php echo esc_attr( $egoiwidget['msg_invalid'] ); ?>" placeholder="<?php _e( 'Check, please, if you wrote your e-mail address correctly.', 'egoi-for-wp' ); ?>" autocomplete="off" />
			</div>
			<!-- / error msg -->
			<!-- empty email msg -->
			<div class="smsnf-input-group ">
				<label for="widget-empty-email-msg"><?php _e( 'Empty email address', 'egoi-for-wp' ); ?></label>
				<input id="widget-empty-email-msg" type="text" name="egoi_widget[msg_empty]" value="<?php echo esc_attr( $egoiwidget['msg_empty'] ); ?>" placeholder="<?php _e( 'Your e-mail field is empty!', 'egoi-for-wp' ); ?>" autocomplete="off" />
			</div>
			<!-- / empty email msg -->
			<!-- already subscribed msg -->
			<div class="smsnf-input-group ">
				<label for="widget-already-subscribed-msg"><?php _e( 'Already subscribed', 'egoi-for-wp' ); ?></label>
				<input id="widget-already-subscribed-msg" type="text" name="egoi_widget[msg_exists_subscribed]" value="<?php echo esc_attr( $egoiwidget['msg_exists_subscribed'] ); ?>" placeholder="<?php _e( 'The email address already exists in your list of contacts.', 'egoi-for-wp' ); ?>" autocomplete="off" />
				<p class="subtitle"><?php _e( 'The text that shows when the given email is already subscribed to the selected list.', 'egoi-for-wp' ); ?></p>
			</div>
			<!-- / already subscribed msg -->
			<!--  -->
			<div class="smsnf-input-group">
				<label for="widget-hide-form"><?php _e( 'Hide form after successful sign-up', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php _e( 'Select "yes" to hide the form after successful sign-up.', 'egoi-for-wp' ); ?></p>
				<div class="form-group switch-yes-no">
					<label class="form-switch">
						<input id="widget-hide-form" name="egoi_widget[hide_form]" value="1" <?php checked( $egoiwidget['hide_form'], 1 ); ?> type="checkbox">
						<i class="form-icon"></i><div class="yes"><?php _e( 'Yes', 'egoi-for-wp' ); ?></div><div class="no"><?php _e( 'No', 'egoi-for-wp' ); ?></div>
					</label>
				</div>
			</div>
			<!-- /  -->
			<!--  -->
			<div class="smsnf-input-group ">
				<label for="widget-redirect"><?php _e( 'Redirect to URL after a successful sign-up', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php _e( 'Leave empty for no redirect. Otherwise, use complete (absolute) URLs.', 'egoi-for-wp' ); ?></p>
				<input id="widget-redirect" type="text" name="egoi_widget[redirect]" value="<?php echo esc_attr( $egoiwidget['redirect'] ); ?>" placeholder="<?php echo printf( __( 'Example: %s', 'egoi-for-wp' ), esc_attr( site_url( '/thank-you/' ) ) ); ?>" autocomplete="off" />
			</div>
			<!-- /  -->
		</div>
	</div>
	<!-- / Configuration -->
	<!-- appearance -->
	<div id="smsnf-appearance" class="smsnf-tab-content smsnf-grid">
		<div>
			<!-- Input Width -->
			<div class="smsnf-input-group ">
				<label for="widget-input-width"><?php _e( 'Input Width', 'egoi-for-wp' ); ?></label>
				<input id="widget-input-width" type="text" name="egoi_widget[input_width]" value="<?php echo esc_attr( $egoiwidget['input_width'] ); ?>">
				<p class="subtitle"><?php _e( 'Change the input width in px, otherwise leave empty if you want to 100%', 'egoi-for-wp' ); ?></p>
			</div>
			<!-- / Input Width -->
			<!-- Button Width -->
			<div class="smsnf-input-group ">
				<label for="widget-button-width"><?php _e( 'Button Width', 'egoi-for-wp' ); ?></label>
				<input id="widget-button-width" type="text" name="egoi_widget[btn_width]" value="<?php echo esc_attr( $egoiwidget['btn_width'] ); ?>">
				<p class="subtitle"><?php _e( 'Change the subscriber button width in px', 'egoi-for-wp' ); ?></p>
			</div>
			<!-- / Button Width -->
			<!-- Border Color -->
			<div class="smsnf-input-group">
				<label for="bar-text-color"><?php _e( 'Border Color', 'egoi-for-wp' ); ?></label>
				<div class="colorpicker-wrapper">
					<div style="background-color:<?php echo esc_attr( $egoiwidget['bcolor'] ); ?>" class="view" ></div>
					<input id="bar-text-color" type="text" name="egoi_widget[bcolor]" value="<?php echo esc_attr( $egoiwidget['bcolor'] ); ?>"  autocomplete="off" />
					<p><?php _e( 'Select Color', 'egoi-for-wp' ); ?></p>
				</div>
			</div>
			<!-- / Border Color -->
			<!-- Background Color on Success -->
			<div class="smsnf-input-group">
				<label for="bar-text-color"><?php _e( 'Background Color on Success', 'egoi-for-wp' ); ?></label>
				<div class="colorpicker-wrapper">
					<div style="background-color:<?php echo esc_attr( $egoiwidget['bcolor_success'] ); ?>" class="view" ></div>
					<input id="bar-text-color" type="text" name="egoi_widget[bcolor_success]" value="<?php echo esc_attr( $egoiwidget['bcolor_success'] ); ?>"  autocomplete="off" />
					<p><?php _e( 'Select Color', 'egoi-for-wp' ); ?></p>
				</div>
				<p class="subtitle"><?php _e( 'Change the color of the Widget Success message', 'egoi-for-wp' ); ?></p>
			</div>
			<!-- / Background Color on Success -->
			<!-- Background Color on Error -->
			<div class="smsnf-input-group">
				<label for="bar-text-color"><?php _e( 'Background Color on Error', 'egoi-for-wp' ); ?></label>
				<div class="colorpicker-wrapper">
					<div style="background-color:<?php echo esc_attr( $egoiwidget['bcolor_error'] ); ?>" class="view" ></div>
					<input id="bar-text-color" type="text" name="egoi_widget[bcolor_error]" value="<?php echo esc_attr( $egoiwidget['bcolor_error'] ); ?>"  autocomplete="off" />
					<p><?php _e( 'Select Color', 'egoi-for-wp' ); ?></p>
				</div>
				<p class="subtitle"><?php _e( 'Change the color of the Widget Error message', 'egoi-for-wp' ); ?></p>
			</div>
			<!-- / Background Color on Error -->
		</div>
	</div>
	<!-- / appearance -->
	<!-- SUBMIT -->
	<div class="smsnf-input-group">
		<input type="submit" value="<?php _e( 'Save', 'egoi-for-wp' ); ?>">
	</div>
	<!-- / SUBMIT -->
</form>
