<section class="smsnf-content">
	<div id="wrap--acoount">
		<div class="main-content">
			<?php
				$options = get_option( 'egoi_webpush_code' );
			if ( ! empty( $this->options_list['domain'] ) && ( ! empty( $options ) && ( ! empty( $options['track'] ) || ! empty( $options['code'] ) ) ) ) {
				?>
					<div style="background:#fff;border: 1px solid #ccc;text-align: center;" class="smsnf-input-group">
						<span style="background:#900;color:#fff;padding:5px;"><?php _e( 'Connected Sites Conflict', 'egoi-for-wp' ); ?></span>
						<p style="margin: 1em;text-align: justify;"><?php _e( 'You have connected sites enable in your website, it can create a conflict if you use this feature joint with connected sites Webpush, if you already have Webpush active in your Connected Site make sure to turn this feature off.', 'egoi-for-wp' ); ?></p>
					</div>
				<?php
			}
			?>
			<div>
				<form id='form_webpush_code' method='post' action="">
					<?php
					settings_fields( Egoi_For_Wp_Admin::OPTION_NAME );
					settings_errors();
					?>

					<div class="e-goi-account-apikey">
						<!-- Title -->
						<div class="e-goi-account-apikey--title" for="egoi_wp_apikey">
							<?php echo _e( 'Insert the Web Push code here', 'egoi-for-wp' ); ?>
						</div>

						<div style="margin: -10px 0px 15px 0px;">
									<span style="color: #444444;">
										<i class="fas fa-exclamation-circle"></i>
										<?php echo __( 'Enter only highlighted code with black color', 'egoi-for-wp' ); ?>
									</span>
						</div>

						<!-- API key and btn -->
						<div class="e-goi-account-apikey--grp" style="padding-bottom: 4px;">

							<?php if ( ! isset( $options['code'] ) || ( isset( $_POST['egoi_webpush']['code'] ) && $error == 1 ) ) { // if not have a valid web push ?>
								<div class="smsnf-input-group" style="display: flex;align-items: center;min-width: 100%;">
									<input type="text" class="e-goi-account-apikey--grp--form__input" name="egoi_webpush[code]" size="55"
										   autocomplete="off" placeholder="<?php echo __( 'Paste the Web Push code here', 'egoi-for-wp' ); ?>"
										   required pattern="[a-zA-Z0-9]+" value="<?php echo esc_attr($_POST['egoi_webpush']['code']); ?>" spellcheck="false" autofocus
										   maxlength="32" <?php echo !empty($error) ? 'style="border-color: #ed000e;"' : null; ?>
									/>


									<span id="save_webpush" class="button-primary button-primary--custom" style="margin-top: 10px;" >
												<?php echo __( 'Save', 'egoi-for-wp' ); ?>
											</span>
								</div>


							<?php } else { ?>

								<span class="e-goi-account-apikey--grp--form" id="webpush_span" style="width: 457px; <?php echo !empty($ok) ? 'border-color: green;' : ''; ?>" >
											<?php echo esc_html($options['code']); ?>
										</span>

								<a type="button" id="edit_webpush" class="button button--custom">
									<?php echo __( 'Edit', 'egoi-for-wp' ); ?>
								</a>

								<div class="smsnf-input-group" style="display: flex;align-items: center;min-width: 100%;">
									<input type="text" class="e-goi-account-apikey--grp--form__input" name="egoi_webpush[code]" size="55" id="egoi_webpush_cod"
										   autocomplete="off" placeholder="<?php echo __( 'Paste here the Web Push code', 'egoi-for-wp' ); ?>"
										   required pattern="[a-zA-Z0-9]+" value="<?php echo esc_attr($options['code']); ?>" spellcheck="false" autofocus
										   maxlength="32" style="display: none;"
									/>
									<span id="save_webpush" class="button-primary button-primary--custom" style="display: none;margin-top: 10px;">
												<?php echo __( 'Save', 'egoi-for-wp' ); ?>
											</span>
								</div>



							<?php } ?>

						</div>

						<?php if ( $ok ) { ?>
							<div>
								<span style="color: green;"><?php echo __( 'Code is valid', 'egoi-for-wp' ); ?></span>
							</div>
						<?php } elseif ( $error ) { ?>
							<div>
								<span style="color: #ed000e;"><?php echo __( 'Code is invalid', 'egoi-for-wp' ); ?></span>
							</div>
						<?php } else { ?>
							<div>
								<span style="padding: 10px;"> </span>
							</div>
						<?php } ?>

					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- How to get web push code -->
	<div id="wrap--acoount" style="margin-top: 0px;">
		<div class="main-content">
			<div style="background-color: #f9f9f9;">
				<div class="e-goi-account-apikey--link--account-settings help-list-style" style="margin-left: 10px;">
					<p style="font-size: 17px;"><?php _e( 'How to get the Web Push Code?', 'egoi-for-wp' ); ?></p>
					<ol style="font-size: 13px;">
						<li>
							<?php _e( ' ', 'egoi-for-wp' ); ?>
							<b><?php _e( 'LOGIN', 'egoi-for-wp' ); ?></b>
							<?php _e( 'to your account and click on the top menu item', 'egoi-for-wp' ); ?>
							<b><?php _e( 'APPS > PUSH APPS', 'egoi-for-wp' ); ?></b>
						</li>
						<li>
							<?php _e( 'Click', 'egoi-for-wp' ); ?>
							<b><?php _e( 'ADD APP > WEB PUSH', 'egoi-for-wp' ); ?></b>
							<?php _e( 'button and fill in the require settings', 'egoi-for-wp' ); ?>
						</li>
						<li>
							<?php _e( 'At the bottom of the page click the tab labeled "Code" and', 'egoi-for-wp' ); ?>
							<b><?php _e( 'COPY', 'egoi-for-wp' ); ?></b>
							<?php _e( 'the code that is between double quotes, after', 'egoi-for-wp' ); ?>
							<b>_egoiwp.code</b> <?php _e( 'variable', 'egoi-for-wp' ); ?>
						</li>
					</ol>

					<div style="margin-top: 30px;"><?php _e( 'Example image', 'egoi-for-wp' ); ?></div>
					<img src="<?php echo esc_url(plugins_url('../../../admin/img/webpushcode.png',__FILE__)); ?>" style="max-width: 480px; display: inline-block; padding: 10px; background-color: white; border: 1px solid #e5e5e5; margin: 4px 0 20px 0;">

				</div>
			</div>
		</div>
	</div>
	<!-- Web push switch -->
	<?php if ( isset( $options['code'] ) ) { ?>
		<div style="padding: 5px 5px 5px;">
			<form id='form_webpush_switch' method='post' action="">
				<?php
				settings_fields( Egoi_For_Wp_Admin::OPTION_NAME );
				settings_errors();
				?>
				<table class="form-table">
					<tr>
						<th scope="row" style="min-width: 200px;"><?php _e( 'Activate Web Push', 'egoi-for-wp' ); ?></th>
						<td class="nowrap">
							<label><input id="yes" type="radio" name="egoi_webpush[track]" <?php checked( $options['track'], 1 ); ?> value="1" required><?php _e( 'Yes', 'egoi-for-wp' ); ?></label> &nbsp;
							<label><input id="no" type="radio" name="egoi_webpush[track]" <?php checked( $options['track'], 0 ); ?> value="0"><?php _e( 'No', 'egoi-for-wp' ); ?></label>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
	<?php } ?>

</section>

<section class="smsnf-content">
	<div id="wrap--acoount" style="margin-bottom: 10px;">
		<div class="main-content">
			<div class="wrap-content--webpush">
				<div>
					<div style="background-color: #04afdb; color: white; margin: 15px 10px;  padding: 1px 20px; border-radius: 3px;">
						<table width="100%">
							<tr>
								<td>
									<p style="font-weight:  bold; font-size: 14px;color: white;margin: 15px 10px;"><?php _e( 'Want to use E-goi\'s Web Push without limits?', 'egoi-for-wp' ); ?>
										<br><?php _e( 'Join an Unlimited Sending plan.', 'egoi-for-wp' ); ?>
									</p>
								</td>
								<td style="min-width: 153px;" align="right">
									<a href="<?php echo esc_url($link_price); ?>" target="_blank" style="font-weight:  bold; font-size: 14px; text-decoration: none; background-color: white; color: #04afdb; padding: 10px 20px; border-radius: 20px;"><?php _e( 'Join Now', 'egoi-for-wp' ); ?></a>
								</td>
							</tr>
						</table>
					</div>
					<div style="margin-left: 10px;">
						<p>
							<?php _e( 'Integrate', 'egoi-for-wp' ); ?>
							<b><?php _e( 'Web Push Notification', 'egoi-for-wp' ); ?></b>
							<?php _e( 'with your site to alert your customers or followers, with Instant Messaging, directly to your browser, even if they are browsing another site.', 'egoi-for-wp' ); ?>
							<a href="<?php echo esc_url($link_learn); ?>" target="_blank"><?php _e( 'Learn more', 'egoi-for-wp' ); ?></a>
						</p>

						<div style="padding: 0 12px 20px 0;">
							<img src="<?php echo esc_url(plugins_url('../../../admin/img/webpushpage.jpg',__FILE__)); ?>" style="width: 100%; height: auto;">
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	<span>
			<?php _e( 'Don\'t know how to create a Web Push in E-goi? Learn how to do it', 'egoi-for-wp' ); ?>
		<a href="<?php echo esc_url($link_help); ?>" target="_blank"><?php _e( 'here', 'egoi-for-wp' ); ?></a>
			</span>
</section>
