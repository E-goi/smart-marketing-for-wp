<?php

require_once plugin_dir_path( __FILE__ ) . '../../../includes/transactionalemail/transactional-email-helper.php';

// Initialize values
$transactionalEmailOptions                 = get_option( 'egoi_transactional_email' );
$options_list['check_transactional_email'] = $transactionalEmailOptions['check_transactional_email'];
$options_list['from']                      = $transactionalEmailOptions['from'];

// Initialize helper
$helper = new TransactionalEmailHelper();

// Get all email sneders
$senders = $helper->get_email_senders();

if ( isset( $_POST['action'] ) ) {

	$post         = $_POST;
	$options_list = array_merge( $this->options_list, $post['egoi_transactional_email'] );

	// Update options
	$helper->update_egoi_transactional_email_option( $post['egoi_transactional_email'], $senders );
}

?>
<div id="smsnf-configuration" class="smsnf-tab-content smsnf-grid active">
	<div>

		<!--FORM -->
		<form method="post" action="#">
		<?php

			settings_fields( 'egoi_transactional_email' );
			settings_errors();
		?>

			<!-- E-goi Transactional Email on/off-->
			<div style="background:#fff;border: 1px solid #ccc;text-align: center;" class="smsnf-input-group">
			<?php
			if ( $options_list['check_transactional_email'] ) {
				echo '<span style="background:#066;color:#fff;padding:5px;">' . __( 'E-goi Transactional Email ON', 'egoi-for-wp' ) . '</span><p>';
				_e( 'The plugin is currently sending all WordPress emails.', 'egoi-for-wp' );
				?>
					</br>
					<?php
					_e( 'Email sender: ', 'egoi-for-wp' );
					echo esc_textarea( $options_list['from'] );
					?>
					<?php

			} else {
				echo '<span style="background:#900;color:#fff;padding:5px;">' . __( 'E-goi Transactional Email OFF', 'egoi-for-wp' ) . '</span><p>';
				_e( 'The plugin is currently not sending WordPress emails.', 'egoi-for-wp' );
			}
			?>
			</div>  

			<!-- E-goi Transactional Email on/off-->
			<div class="smsnf-input-group">
				<label><?php _e( 'About Transactional Email', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php _e( 'E-goi Transactional Email is a feature that enables you to select E-goi email sending logic to send all your WordPress emails. Our main goal is to make email deliverability reliable by sending your WordPress emails with E-goi.', 'egoi-for-wp' ); ?></p>
			</div>  

			<!-- Enable E-goi-->
			<div class="smsnf-input-group">
				<label><?php _e( 'Enable E-goi Transactional Email', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php _e( 'Select "yes" if you want the plugin to send all WordPress emails.', 'egoi-for-wp' ); ?></p>
				<div for="egoi_transactional_email" class="smsnf-wrapper" style="display: flex;align-items: flex-end;margin-top: 12px;">
					<label><input type="radio" name="egoi_transactional_email[check_transactional_email]" <?php checked( $options_list['check_transactional_email'], 1 ); ?> value=1><?php _e( 'Yes', 'egoi-for-wp' ); ?></label> &nbsp;
					<label><input type="radio" name="egoi_transactional_email[check_transactional_email]" <?php checked( $options_list['check_transactional_email'], 0 ); ?> value=0><?php _e( 'No', 'egoi-for-wp' ); ?></label>
				</div>
			</div>
			<div class="smsnf-input-group">

				<!-- LIST GET EMAIL SENDERS-->
				<div id="egoi_email_senders" class="smsnf-input-group">
				<label><?php _e( 'Email Sender', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php _e( 'If you enable E-goi Transactional Email select the email sender.', 'egoi-for-wp' ); ?></p>
					<?php
					if ( empty( $senders ) ) {
						printf( __( 'No email senders found, <a href="%s">are you connected to E-goi</a> and/or have created email senders?', 'egoi-for-wp' ), admin_url( 'admin.php?page=egoi-for-wp' ) );
					} else {
						?>
							<select id="from" name="egoi_transactional_email[from]" required class="form-select">
							<?php
							foreach ( $senders as $sender ) {

								if ( $sender->email ) {
									?>
										<option value="<?php echo esc_textarea( $sender->email ); ?>" <?php selected( $options_list['from'], $sender->email ); ?>>
												<?php echo esc_textarea( $sender->email ); ?>
										</option>
										<?php
								}
							}
							?>
							</select>
					<?php } ?>
				</div>
				<!-- Button-->
				<input type='submit' class='button-primary' name='egoi_select_transactional_email' id='egoi_select_transactional_email' value='<?php echo _e( 'Save Changes', 'egoi-for-wp' ); ?>' />
			</div>
		</form>
	</div>
</div>
