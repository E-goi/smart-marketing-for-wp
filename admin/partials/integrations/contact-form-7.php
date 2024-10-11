<?php
$contact_forms = $this->egoiWpApi->getContactFormInfo();
?>
<form method="post" action="">
	<?php settings_fields( 3 ); ?>
	<input type="hidden" name="egoiform" value="egoi_int">
	<div class="smsnf-grid">
		<div>
			<?php if ( ! class_exists( 'WPCF7' ) ) { ?>
			<div class="container" style="display: flex;justify-content: center;align-items: center;flex-direction: column;min-height: 100%;">
				<div>
					<h2><?php _e( 'Plugin missing', 'egoi-for-wp' ); ?></h2>
					<h4><?php _e( 'Make sure you have Contact Form 7 installed before trying to configure the integration.', 'egoi-for-wp' ); ?></h4>
				</div>
			<div>
			<?php } else { ?>

				<div class="smsnf-input-group">
					<label for="egoi_int"><?php _e( 'Enable Contact Form 7 Integration', 'egoi-for-wp' ); ?></label>
					<p class="subtitle"><?php _e( 'Select "yes" to enable Contact From 7 Integration.', 'egoi-for-wp' ); ?></p>
					<?php if ( preg_match( '#^pt_#', get_locale() ) === 1 ) { ?>
						<p class="subtitle"><?php _e( 'See how to configure ', 'egoi-for-wp' ); ?><a target="_blank" rel="noopener noreferrer" href="https://helpdesk.e-goi.com/683741-Integrar-o-E-goi-com-o-Contact-Form-7"><?php _e( 'here', 'egoi-for-wp' ); ?></a></p>
					<?php } elseif ( preg_match( '#^es_#', get_locale() ) === 1 ) { ?>
						<p class="subtitle"><?php _e( 'See how to configure ', 'egoi-for-wp' ); ?><a target="_blank" rel="noopener noreferrer" href="https://helpdesk.e-goi.com/880852-Integrar-E-goi-con-Contact-Form-7"><?php _e( 'here', 'egoi-for-wp' ); ?></a></p>
					<?php } else { ?>
						<p class="subtitle"><?php _e( 'See how to configure ', 'egoi-for-wp' ); ?><a target="_blank" rel="noopener noreferrer" href="https://helpdesk.e-goi.com/514759-Integrating-E-goi-with-Contact-Form-7"><?php _e( 'here', 'egoi-for-wp' ); ?></a></p>
					<?php } ?>
					<div class="smsnf-wrapper" style="display: flex;align-items: flex-end;margin-top: 12px;">
						<label><input type="radio"  name="egoi_int[enable_cf]" <?php if(isset($egoint['enable_cf'])) {checked( $egoint['enable_cf'], 1 ); }?> value="1"><?php _e( 'Yes', 'egoi-for-wp' ); ?></label> &nbsp;
						<label><input type="radio" name="egoi_int[enable_cf]" <?php if(isset($egoint['enable_cf'])) {checked( $egoint['enable_cf'], 0 ); } ?> value="0"><?php _e( 'No', 'egoi-for-wp' ); ?></label>
					</div>
				</div>

				<?php if ( isset($egoint['enable_cf']) ) { ?>

					<div class="smsnf-input-group">
						<label for="egoi4wp-forms"><?php _e( 'Contact Form Name', 'egoi-for-wp' ); ?></label>
						<p class="subtitle"><?php _e( 'Select the form you want to sync contacts on.', 'egoi-for-wp' ); ?></p>
						<div class="smsnf-wrapper">
							<?php if ( empty( $contact_forms ) ) { ?>
								<span><?php _e( 'Cannot locate any forms from Contact Form 7', 'egoi-for-wp' ); ?></span>
							<?php } else { ?>
								<select id="egoi4wp-forms" name="contact_form[]" class="form-select" multiple="multiple" >
									<?php
									foreach ( $contact_forms as $key => $form ) {
										?>
										<option value="<?php echo esc_attr( $form->ID ); ?>" 
																  <?php
																	if ( isset($opt['contact_form']) && is_array( $opt['contact_form'] ) ) {
																		selected( in_array( $form->ID, $opt['contact_form'] ) );
																	}
																	echo '>' . esc_html( $form->post_title );
																	?>
										</option>
										<?php
									}
									?>
								</select>
							<?php } ?>
						</div>
					</div>

					<div class="smsnf-input-group">
						<label for="list_cf"><?php _e( 'List to Subscribe', 'egoi-for-wp' ); ?></label>
						<?php if ( empty( $lists ) ) { ?>
							<td><?php printf( __( 'Lists not found, <a href="%s">are you connected to egoi</a>?', 'egoi-for-wp' ), admin_url( 'admin.php?page=egoi-4-wp-account' ) ); ?></td>
																												<?php
						} else {
							?>
							<p class="subtitle"><?php _e( 'Select the list to which who submit in Contact Form 7 should be subscribed', 'egoi-for-wp' ); ?></p>
							<div class="smsnf-wrapper">
								<select name="egoi_int[list_cf]" id="egoi4wp-lists" class="form-select">
								<?php
									$index = 1;
								foreach ( $lists as $list ) {
									if ( $list['public_name'] != '' ) {
										?>
											<option value="<?php echo esc_attr( $list['list_id'] ); ?>" <?php if(isset($egoint['list_cf'])){selected( $list['list_id'], $egoint['list_cf'] );} ?>><?php echo esc_html( $list['public_name'] ); ?></option>
																	  <?php
									}
									$index++;
								}
								?>
								</select>
							</div>
						<?php } ?>
					</div>

					<div class="smsnf-input-group">
						<label for="edit"><?php _e( 'Update Subscriber', 'egoi-for-wp' ); ?></label>
						<p class="subtitle"><?php _e( 'Select "yes" to edit the subscriber if already exists in E-goi List.', 'egoi-for-wp' ); ?></p>
						<div class="smsnf-wrapper" style="display: flex;align-items: flex-end;margin-top: 12px;">
							<label><input type="radio"  name="egoi_int[edit]" <?php if(isset($egoint['edit'])) {checked( $egoint['edit'], 1 );} ?> value="1"><?php _e( 'Yes', 'egoi-for-wp' ); ?></label> &nbsp;
							<label><input type="radio" name="egoi_int[edit]" <?php if(isset($egoint['edit'])) {checked( $egoint['edit'], 0 );} ?> value="0"><?php _e( 'No', 'egoi-for-wp' ); ?></label>
						</div>
					</div>


				<?php } ?>

			<?php } ?>

		</div>
	</div>

	<div class="egoi-undertable-button-wrapper" style="bottom: 0;position: absolute;right: 30px;">
		<div class="smsnf-input-group">
			<input type="submit" value="<?php _e( 'Save', 'egoi-for-wp' ); ?>" />
		</div>
	</div>

</form>
