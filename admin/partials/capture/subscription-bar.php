<?php
// check if have a new tag in BD

if( isset($this->bar_post['tag-egoi'])){
	$tag = $this->bar_post['tag-egoi'];
} else {
	$tag = 0;
}

?>

<ul class="tab">
	<li class="tab-item active">
		<a href="#" tab-target="smsnf-configuration"><?php _e( 'Settings', 'egoi-for-wp' ); ?></a>
	</li>
	<li class="tab-item">
		<a href="#" tab-target="smsnf-appearance"><?php _e( 'Appearance', 'egoi-for-wp' ); ?></a>
	</li>
	<li class="tab-item">
		<a href="#" tab-target="smsnf-messages"><?php _e( 'Messages', 'egoi-for-wp' ); ?></a>
	</li>
</ul>

<form id="smsnf-subscriber-bar" method="post" name="bar_options" action="<?php echo admin_url( 'options.php' ); ?>">
<?php
settings_fields( Egoi_For_Wp_Admin::BAR_OPTION_NAME );
if(get_settings_errors()){
	echo get_notification( __( 'Saved Configurations', 'egoi-for-wp' ), __( 'Subscription bar configurations saved with success.', 'egoi-for-wp' ) );
}
?>
	<div id="smsnf-configuration" class="smsnf-tab-content smsnf-grid active">
		<div>
			<div class="smsnf-input-group">
				<label for="enabled_bar"><?php echo _e( 'Enable Bar?', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php echo _e( 'A valid way to completely disable the bar.', 'egoi-for-wp' ); ?></p>
				<div class="form-group switch-yes-no">
					<label class="form-switch">
						<input id="enabled_bar" name="egoi_bar_sync[enabled]" value="1" <?php checked( $this->bar_post['enabled'], 1 ); ?> type="checkbox">
						<i class="form-icon"></i><div class="yes"><?php echo _e( 'Yes' ); ?></div><div class="no"><?php echo _e( 'No' ); ?></div>
					</label>
				</div>
			</div>
			<div class="smsnf-input-group">
				<label for="bar_open"><?php echo _e( 'Open Bar by default?', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php _e( 'Show or Hide by default the bar.', 'egoi-for-wp' ); ?></p>
				<div class="form-group switch-yes-no">
					<label class="form-switch">
						<input id="bar_open" name="egoi_bar_sync[open]" value="1" <?php checked( $this->bar_post['open'], 1 ); ?> type="checkbox">
						<i class="form-icon"></i><div class="yes"><?php echo _e( 'Yes' ); ?></div><div class="no"><?php echo _e( 'No' ); ?></div>
					</label>
				</div>
			</div>
			<!-- Double Opt-In -->
			<div class="smsnf-input-group">
				<label for="bar_double_optin"><?php echo _e( 'Enable Double Opt-In?', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php echo _e( 'If you activate the double opt-in, a confirmation e-mail will be send to the subscribers.', 'egoi-for-wp' ); ?></p>
				<div class="form-group switch-yes-no">
					<label class="form-switch">
						<?php $double_optin_enable = $this->bar_post['double_optin'] == 1 || $this->bar_post['list'] == 0; ?>
						<input id="bar_double_optin" name="egoi_bar_sync[double_optin]" value="1" <?php checked( $double_optin_enable, 1 ); ?> type="checkbox">
						<i class="form-icon"></i><div class="yes"><?php echo _e( 'Yes' ); ?></div><div class="no"><?php echo _e( 'No' ); ?></div>
					</label>
				</div>
			</div>
			<!-- / Double Opt-In -->
			<!-- LISTAS -->
			<?php get_list_html( $this->bar_post['list'], 'egoi_bar_sync[list]' ); ?>
			<!-- / LISTAS -->
			<!-- TAGS -->
			<?php get_tag_html( $tag, 'egoi_bar_sync[tag-egoi]' ); ?>
			<!-- / TAGS -->
			<!-- BAR TEXT -->
			<div class="smsnf-input-group">
				<label for="text-bar"><?php _e( 'Bar Text', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php _e( 'The text to appear before the email field.', 'egoi-for-wp' ); ?></p>
				<input id="text-bar" type="text" name="egoi_bar_sync[text_bar]" value="<?php echo ! empty( $this->bar_post['text_bar'] ) ? esc_attr( $this->bar_post['text_bar'] ) : ''; ?>" autocomplete="off" />
			</div>
			<!-- / BAR TEXT -->
			<!-- BTN TEXT -->
			<div class="smsnf-input-group">
				<label for="text-btn"><?php echo _e( 'Button Text', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php _e( 'The text on the submit button.', 'egoi-for-wp' ); ?></p>
				<input id="text-btn" type="text" name="egoi_bar_sync[text_button]" value="<?php echo ! empty( $this->bar_post['text_button'] ) ? esc_attr( $this->bar_post['text_button'] ) : ''; ?>" autocomplete="off" />
			</div>
			<!-- / BTN TEXT -->
			<!-- PLACEHOLDER EMAIL -->
			<div class="smsnf-input-group">
				<label for="email-placeholde"><?php echo _e( 'Email Placeholder Text', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php _e( 'The initial placeholder text to appear in the email field.', 'egoi-for-wp' ); ?></p>
				<input id="email-placeholde" type="text" name="egoi_bar_sync[text_email_placeholder]" value="<?php echo ! empty( $this->bar_post['text_email_placeholder'] ) ? esc_attr( $this->bar_post['text_email_placeholder'] ) : ''; ?>" autocomplete="off" />
			</div>
			<!-- / PLACEHOLDER EMAIL -->
		</div>
	</div>

	<div id="smsnf-appearance" class="smsnf-tab-content smsnf-grid">
		<div>
			<!-- BAR POSITION -->
			<div class="smsnf-input-group">
				<label for="bar-position"><?php echo _e( 'Bar Position', 'egoi-for-wp' ); ?></label>
				<select name="egoi_bar_sync[position]" class="form-select " id="bar-position">
					<option value="top" <?php selected( $this->bar_post['position'], 'top' ); ?>><?php _e( 'Top', 'egoi-for-wp' ); ?></option>
					<option value="bottom" <?php selected( $this->bar_post['position'], 'bottom' ); ?>><?php _e( 'Bottom', 'egoi-for-wp' ); ?></option>
				</select>
			</div>
			<!-- / BAR POSITION -->
			<!-- FIXED BAR -->
			<div class="smsnf-input-group">
				<label for="bar_open"><?php _e( 'Bar Fixed?', 'egoi-for-wp' ); ?></label>
				<div class="form-group switch-yes-no">
					<label class="form-switch">
						<input id="bar_open" name="egoi_bar_sync[sticky]" value="1" <?php checked( $this->bar_post['sticky'], 1 ); ?> type="checkbox">
						<i class="form-icon"></i><div class="yes"><?php _e( 'Yes' ); ?></div><div class="no"><?php _e( 'No' ); ?></div>
					</label>
				</div>
			</div>
			<!-- / FIXED BAR -->
			<!-- BACKGROUND COLOR -->
			<div class="smsnf-input-group">
				<div class="egoi-transparent-option">
					<label for="bar-background-color"><?php _e( 'Background Color', 'egoi-for-wp' ); ?></label>
					<span class="e-goi-tooltip">
						<div class="form-group switch-yes-no">
						<label class="form-switch">
							<input id="bar_open" name="egoi_bar_sync[color_bar_transparent]" value="1" <?php checked( $this->bar_post['color_bar_transparent'], 1 ); ?> type="checkbox">
							<i class="form-icon"></i><div class="yes"><?php _e( 'Yes' ); ?></div><div class="no"><?php _e( 'No' ); ?></div>
						</label>
					</div>
									<span class="e-goi-tooltiptext e-goi-tooltiptext--active">
									<?php echo __( 'Set this to "no" for no background color.', 'egoi-for-wp' ); ?>
								  </span>
							 </span>
				</div>

				<div class="colorpicker-wrapper">
					<div style="background-color:<?php echo esc_attr( $this->bar_post['color_bar'] ); ?>" class="view" ></div>
					<input id="bar-background-color" type="text" name="egoi_bar_sync[color_bar]" value="<?php echo ! empty( $this->bar_post['color_bar'] ) ? esc_attr( $this->bar_post['color_bar'] ) : '#ffffff'; ?>"  autocomplete="off" />
					<p><?php _e( 'Select Color', 'egoi-for-wp' ); ?></p>
				</div>
			</div>
			<!-- / BACKGROUND COLOR -->
			<!-- TEXT COLOR -->
			<div class="smsnf-input-group">
				<label for="bar-text-color"><?php _e( 'Text Color', 'egoi-for-wp' ); ?></label>
				<div class="colorpicker-wrapper">
					<div style="background-color:<?php echo esc_attr( $this->bar_post['bar_text_color'] ); ?>" class="view" ></div>
					<input id="bar-text-color" type="text" name="egoi_bar_sync[bar_text_color]" value="<?php echo ! empty( $this->bar_post['bar_text_color'] ) ? esc_attr( $this->bar_post['bar_text_color'] ) : ''; ?>"  autocomplete="off" />
					<p><?php _e( 'Select Color', 'egoi-for-wp' ); ?></p>
				</div>
			</div>
			<!-- / TEXT COLOR -->
			<!-- BORDER SIZE -->
			<div class="smsnf-input-group">
				<label for="bar-border-size"><?php _e( 'Border Size', 'egoi-for-wp' ); ?></label>
				<input  id="bar-border-size" type="text" name="egoi_bar_sync[border_px]" value="<?php echo ! empty( $this->bar_post['border_px'] ) ? esc_attr( $this->bar_post['border_px'] ) : ''; ?>" autocomplete="off" />
			</div>
			<!-- / BORDER SIZE -->
			<!-- BORDER COLOR -->
			<div class="smsnf-input-group">
				<label for="bar-text-color"><?php _e( 'Border Color', 'egoi-for-wp' ); ?></label>
				<div class="colorpicker-wrapper">
					<div style="background-color:<?php echo esc_attr( $this->bar_post['border_color'] ); ?>" class="view" ></div>
					<input id="bar-text-color" type="text" name="egoi_bar_sync[border_color]" value="<?php echo ! empty( $this->bar_post['border_color'] ) ? esc_attr( $this->bar_post['border_color'] ) : ''; ?>"  autocomplete="off" />
					<p><?php _e( 'Select Color', 'egoi-for-wp' ); ?></p>
				</div>
			</div>
			<!-- / BORDER COLOR -->
			<!-- BTN COLOR -->
			<div class="smsnf-input-group">
				<label for="bar-text-color"><?php _e( 'Button Color', 'egoi-for-wp' ); ?></label>
				<div class="colorpicker-wrapper">
					<div style="background-color:<?php echo esc_attr( $this->bar_post['color_button'] ); ?>" class="view" ></div>
					<input id="bar-text-color" type="text" name="egoi_bar_sync[color_button]" value="<?php echo ! empty( $this->bar_post['color_button'] ) ? esc_attr( $this->bar_post['color_button'] ) : ''; ?>"  autocomplete="off" />
					<p><?php _e( 'Select Color', 'egoi-for-wp' ); ?></p>
				</div>
			</div>
			<!-- / BTN COLOR -->
			<!-- BTN TEXT COLOR -->
			<div class="smsnf-input-group">
				<label for="bar-text-color"><?php _e( 'Button Text Color', 'egoi-for-wp' ); ?></label>
				<div class="colorpicker-wrapper">
					<div style="background-color:<?php echo esc_attr( $this->bar_post['color_button_text'] ); ?>" class="view" ></div>
					<input id="bar-text-color" type="text" name="egoi_bar_sync[color_button_text]" value="<?php echo ! empty( $this->bar_post['color_button_text'] ) ? esc_attr( $this->bar_post['color_button_text'] ) : ''; ?>"  autocomplete="off" />
					<p><?php _e( 'Select Color', 'egoi-for-wp' ); ?></p>
				</div>
			</div>
			<!-- / BTN TEXT COLOR -->
			<!-- SUBMIT - SUCCESS -->
			<div class="smsnf-input-group">
				<label for="bar-text-color"><?php _e( 'Background Color on Success', 'egoi-for-wp' ); ?></label>
				<div class="colorpicker-wrapper">
					<div style="background-color:<?php echo esc_attr( $this->bar_post['success_bgcolor'] ); ?>" class="view" ></div>
					<input id="bar-text-color" type="text" name="egoi_bar_sync[success_bgcolor]" value="<?php echo ! empty( $this->bar_post['success_bgcolor'] ) ? esc_attr( $this->bar_post['success_bgcolor'] ) : ''; ?>"  autocomplete="off" />
					<p><?php _e( 'Select Color', 'egoi-for-wp' ); ?></p>
				</div>
			</div>
			<!-- / SUBMIT - SUCCESS -->
			<!-- SUBMIT - ERROR -->
			<div class="smsnf-input-group">
				<label for="bar-text-color"><?php _e( 'Background Color on Error', 'egoi-for-wp' ); ?></label>
				<div class="colorpicker-wrapper">
					<div style="background-color:<?php echo esc_attr( $this->bar_post['error_bgcolor'] ); ?>" class="view" ></div>
					<input id="bar-text-color" type="text" name="egoi_bar_sync[error_bgcolor]" value="<?php echo ! empty( $this->bar_post['error_bgcolor'] ) ? esc_attr( $this->bar_post['error_bgcolor'] ) : ''; ?>"  autocomplete="off" />
					<p><?php _e( 'Select Color', 'egoi-for-wp' ); ?></p>
				</div>
			</div>
			<!-- / SUBMIT - ERROR -->
		</div>
	</div>

	<div id="smsnf-messages" class="smsnf-tab-content">
		<!-- SUCCESS -->
		<div class="smsnf-input-group">
			<label for="subscribed-msg"><?php _e( 'Success', 'egoi-for-wp' ); ?></label>
			<input id="subscribed-msg" type="text" name="egoi_bar_sync[text_subscribed]" value="<?php echo ! empty( $this->bar_post['text_subscribed'] ) ? esc_attr( $this->bar_post['text_subscribed'] ) : __( 'Success Subscribed', 'egoi-for-wp' ); ?>" autocomplete="off" />
		</div>
		<!-- / SUCCESS -->
		<!-- INVALID EMAIL -->
		<div class="smsnf-input-group">
			<label for="invalid-email-msg"><?php _e( 'Invalid email address', 'egoi-for-wp' ); ?></label>
			<input id="invalid-email-msg" type="text" name="egoi_bar_sync[text_invalid_email]" value="<?php echo ! empty( $this->bar_post['text_invalid_email'] ) ? esc_attr( $this->bar_post['text_invalid_email'] ) : __( 'Invalid e-mail', 'egoi-for-wp' ); ?>" autocomplete="off" />
		</div>
		<!-- / INVALID EMAIL -->
		<!-- Already subscribed -->
		<div class="smsnf-input-group">
			<label for="already-subscribed-msg"><?php _e( 'Already subscribed', 'egoi-for-wp' ); ?></label>
			<input id="already-subscribed-msg" type="text" name="egoi_bar_sync[text_already_subscribed]" value="<?php echo ! empty( $this->bar_post['text_already_subscribed'] ) ? esc_attr( $this->bar_post['text_already_subscribed'] ) : __( 'Subscriber already exists', 'egoi-for-wp' ); ?>" autocomplete="off" />
		</div>
		<!-- / Already subscribed -->

		<!-- Waiting for confirmation -->
		<div class="smsnf-input-group">
			<label for="wating_for_confirmation-msg"><?php _e( 'Already subscribed and waiting for confirmation e-mail', 'egoi-for-wp' ); ?></label>
			<input id="wating_for_confirmation-msg" type="text" name="egoi_bar_sync[text_waiting_for_confirmation]" value="<?php echo ! empty( $this->bar_post['text_waiting_for_confirmation'] ) ? esc_attr( $this->bar_post['text_waiting_for_confirmation'] ) : __( 'Already subscribed and waiting for confirmation e-mail', 'egoi-for-wp' ); ?>" autocomplete="off" />
		</div>
		<!-- / Waiting for confirmation -->


		<!-- Other errors -->
		<div class="smsnf-input-group">
			<label for="bar-other-errors-msg"><?php _e( 'Other errors', 'egoi-for-wp' ); ?></label>
			<input id="bar-other-errors-msg" type="text" name="egoi_bar_sync[text_error]" placeholder="<?php _e( 'Eg. List Missing from E-goi', 'egoi-for-wp' ); ?>" value="<?php echo ! empty( $this->bar_post['text_error'] ) ? esc_attr( $this->bar_post['text_error'] ) : ''; ?>" autocomplete="off" />
		</div>
		<!-- / Other errors -->
		<!-- Redirect URL -->
		<div class="smsnf-input-group">
			<label for="bar-redirect-url"><?php _e( 'Redirect to URL after successful sign-up', 'egoi-for-wp' ); ?></label>
			<input id="bar-redirect-url" type="text" name="egoi_bar_sync[redirect]" value="<?php echo esc_url( $this->bar_post['redirect'] ); ?>" placeholder="<?php echo ! empty( $this->bar_post['redirect'] ) ? esc_url( $this->bar_post['redirect'] ) : ''; ?>" autocomplete="off" />
			<p class="subtitle"><?php _e( 'Leave empty for no redirect. Otherwise, use complete (absolute) URLs, including <code>http://</code>.', 'egoi-for-wp' ); ?></p>
		</div>
		<!-- / Redirect URL -->
	</div>
	<div class="smsnf-input-group">
		<input type="submit" value="<?php _e( 'Save', 'egoi-for-wp' ); ?>">
	</div>
</form>

<style>
	.e-goi-tooltip .e-goi-tooltiptext{
		margin-left: 60px !important;
	}
</style>
