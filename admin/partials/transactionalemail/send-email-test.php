<?php

require_once plugin_dir_path( __FILE__ ) . '../../../includes/transactionalemail/transactional-email-helper.php';

// Initialize values
$transactionalEmailOptions                 = get_option( 'egoi_transactional_email' );
$options_list['check_transactional_email'] = $transactionalEmailOptions['check_transactional_email'];
$options_list['from']                      = $transactionalEmailOptions['from'];

// Initialize helper
$helper = new TransactionalEmailHelper();

if ( isset( $_POST['action'] ) ) {
	$post = $_POST;

	$helper->send_test_email( $post );
}
?>
<div id="smsnf-configuration" class="smsnf-tab-content smsnf-grid active">
	<div>
	
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
					_e( $options_list['from'], 'egoi-for-wp' );
					?>
					
					<?php

			} else {
				echo '<span style="background:#900;color:#fff;padding:5px;">' . __( 'E-goi Transactional Email OFF', 'egoi-for-wp' ) . '</span><p>';
				_e( 'The plugin is currently not sending WordPress emails.', 'egoi-for-wp' );
			}
			?>
			</div>  

			<form method="post" action="#">
			<?php
			settings_fields( 'egoi_transactional_email_test_email' );
			settings_errors();
			?>
				<div class="smsnf-input-group">
						<label for="to"><?php _e( 'Send To', 'egoi-for-wp' ); ?></label>
						<input id="to" name="to" type="text" placeholder="<?php _e( 'Set the email address of the recipient', 'egoi-for-wp' ); ?>" value="" required />
					</div>

				<div class="smsnf-input-group">
					<label for="subject"><?php _e( 'Subject', 'egoi-for-wp' ); ?></label>
					<input id="subject" name="subject" type="text" placeholder="<?php _e( 'Set the subject of email', 'egoi-for-wp' ); ?>" value="" required />
				</div>

				<div class="smsnf-input-group">
					<label for="message"><?php _e( 'Message', 'egoi-for-wp' ); ?></label>
					<textarea id="message" rows=10 name="message" value="" required></textarea>
				</div>

				<div class="smsnf-input-group">
				<!-- Button-->
					<input type='submit' class='button-primary' name='egoi_select_transactional_email_test' id='egoi_select_transactional_email_test' value='<?php echo _e( 'Send Email', 'egoi-for-wp' ); ?>' />
				</div>
			</form>
	</div>
</div>
