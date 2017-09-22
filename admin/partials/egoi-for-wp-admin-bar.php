<div class="wrap" id="egoi4wp-admin" style="width:65%;">

	<div id="egoi-tabs-bar">
		<a class="nav-tab-preview nav-tab-active" style="cursor: pointer;" id="nav-tab-preview" onclick="preview_bar();"><?php _e( 'Preview bar', 'egoi-for-wp' ); ?></a>
		<span> | </span>
		<a class="nav-tab-settings" id="nav-tab-settings" style="cursor: pointer;" onclick="tabs(this.id, 'nav-tab-appearance', 'nav-tab-messages', 'tab-settings', 'tab-appearance', 'tab-messages');"><?php _e( 'Settings', 'egoi-for-wp' ); ?></a>
		<span> | </span>
		<a class="nav-tab-appearance" id="nav-tab-appearance" style="cursor: pointer;" onclick="tabs(this.id, 'nav-tab-settings', 'nav-tab-messages', 'tab-appearance', 'tab-settings','tab-messages');"><?php _e( 'Appearance', 'egoi-for-wp' ); ?></a>
		<span> | </span>
		<a class="nav-tab-messages" id="nav-tab-messages" style="cursor: pointer;" onclick="tabs(this.id, 'nav-tab-settings', 'nav-tab-appearance','tab-messages', 'tab-settings', 'tab-appearance');"><?php _e( 'Messages', 'egoi-for-wp' ); ?></a>
	</div>

	<form method="post" name="bar_options" action="<?php echo admin_url('options.php'); ?>"><?php 

		settings_fields( Egoi_For_Wp_Admin::BAR_OPTION_NAME );
		settings_errors();
		?>

		<div id="egoi-bar-preview">

			<div class="e-goi-preview-text"><?php _e( 'Preview of Subscriber Bar', 'egoi-for-wp' ); ?></div>
			<div class="egoi-bar" style="border:<?php echo $this->bar_post['border_px'].' solid '.$this->bar_post['border_color'].';background:'.$this->bar_post['color_bar'].';'?>">
			
				<label class="egoi-label" style="color:<?php echo $this->bar_post['bar_text_color']; ?>;"><?php echo $this->bar_post['text_bar']; ?></label>
					<input type="email" name="email" placeholder="<?php echo $this->bar_post['text_email_placeholder']; ?>" class="egoi-email"  />
					<input class="button" class="egoi-button" style="text-align:-webkit-center;padding:10px;height:31px;background:<?php echo $this->bar_post['color_button']; ?>;color:<?php echo $this->bar_post['color_button_text']; ?>;" value="<?php echo $this->bar_post['text_button']; ?>" />
			</div>
			<!-- / MailChimp Top Bar -->
		</div>

		<!-- Bar Settings -->
			<div class="tab" id="tab-settings">

				<table class="form-table">

					<tr valign="top">
						<th scope="row">
							<label>
								<?php _e( 'Enable Bar?', 'egoi-for-wp' ); ?>
							</label>
						</th>
						<td>
							<label>
								<input type="radio" name="egoi_bar_sync[enabled]" value="1" <?php checked($this->bar_post['enabled'], 1); ?> /> <?php _e( 'Yes' ); ?>
							</label>
							<label>
								<input type="radio" name="egoi_bar_sync[enabled]" value="0" <?php checked($this->bar_post['enabled'], 0); ?> /> <?php _e( 'No' ); ?>
							</label>
							<p class="help"><?php _e( 'A valid way to completely disable the bar.', 'egoi-for-wp' ); ?></p>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><label><?php _e( 'Egoi List', 'egoi-for-wp' ); ?></label></th>
						<td><?php
						if(empty($lists)) {

							printf( __( 'No lists found, <a href="%s">are you connected to Egoi</a>?', 'egoi-for-wp' ), admin_url( 'admin.php?page=egoi-for-wp' ) );

						}else{ ?>

							<select name="egoi_bar_sync[list]" class="egoi4wp-list-input" id="select-egoi-list">
								<option disabled <?php selected($this->bar_post['list'], ''); ?>><?php _e( 'Select a list..', 'egoi-for-wp' ); ?></option><?php 
								
								foreach($lists as $list) {
								 	if($list->title!=''){?>
										<option value="<?php echo esc_attr( $list->listnum ); ?>" <?php selected($this->bar_post['list'], $list->listnum); ?>><?php echo esc_html( $list->title ); ?></option><?php
									}
								} ?>
							</select>
							<p class="help"><?php _e( 'Select the list to which visitors should be subscribed.' ,'egoi-for-wp' ); ?></p><?php
						}?>
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
		<div class="tab" id="tab-appearance">

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
									<input type="radio" name="egoi_bar_sync[sticky]" value="1" <?php checked( $this->bar_post['sticky'], 1); ?> /> <?php _e( 'Yes' ); ?>
								</label>
								<label>
									<input type="radio" name="egoi_bar_sync[sticky]" value="0" <?php checked( $this->bar_post['sticky'], 0); ?> /> <?php _e( 'No' ); ?>
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
		<div class="tab" id="tab-messages">

			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label><?php _e( 'Success', 'egoi-for-wp' ); ?></label></th>
					<td><input type="text" class="widefat" name="egoi_bar_sync[text_subscribed]" placeholder="<?php echo esc_attr( $this->bar_post['text_subscribed'] ); ?>"  value="<?php echo esc_attr( $this->bar_post['text_subscribed'] ); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label><?php _e( 'Invalid email address', 'egoi-for-wp' ); ?></label></th>
					<td><input type="text" class="widefat" name="egoi_bar_sync[text_invalid_email]" placeholder="<?php echo esc_attr( $this->bar_post['text_invalid_email'] ); ?>"  value="<?php echo esc_attr( $this->bar_post['text_invalid_email'] ); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label><?php _e( 'Already subscribed', 'egoi-for-wp' ); ?></label></th>
					<td><input type="text" class="widefat" name="egoi_bar_sync[text_already_subscribed]" placeholder="<?php echo esc_attr( $this->bar_post['text_already_subscribed'] ); ?>"  value="<?php echo esc_attr( $this->bar_post['text_already_subscribed'] ); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label><?php _e( 'Other errors' ,'egoi-for-wp' ); ?></label></th>
					<td><input type="text" class="widefat" name="egoi_bar_sync[text_error]" placeholder="<?php _e( 'Eg. List Missing from E-goi', 'egoi-for-wp' );?>"  value="<?php echo esc_attr( $this->bar_post['text_error'] ); ?>" /></td>
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


		<?php submit_button(); ?>
	</form>

</div>