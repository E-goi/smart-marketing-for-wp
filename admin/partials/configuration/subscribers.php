<div>
	<form method="post" action="<?php echo admin_url( 'options.php' ); ?>">
		<?php

		settings_fields( Egoi_For_Wp_Admin::OPTION_NAME );
		if(get_settings_errors()){
			echo get_notification( __( 'Saved Configurations', 'egoi-for-wp' ), __( 'Subscribers configurations saved with success.', 'egoi-for-wp' ) );
		}

		if ( $this->options_list['list'] !== '' ) {
			?>

			<div style="background:#fff;border: 1px solid #ccc;text-align: center;" class="smsnf-input-group">
				<?php

				if ( $this->options_list['enabled'] ) {
					echo '<span style="background:#066;color:#fff;padding:5px;">' . __( 'Syncronization ON', 'egoi-for-wp' ) . '</span><p>';
					_e( 'The plugin is listening to changes in your users and will automatically keep your WP users with the selected E-goi list.', 'egoi-for-wp' );
					?>
					<?php
				} else {
					echo '<span style="background:#900;color:#fff;padding:5px;">' . __( 'Syncronization OFF', 'egoi-for-wp' ) . '</span><p>';
					_e( 'The plugin is currently not listening to any changes in your users.', 'egoi-for-wp' );
				}
				?>

				<table class="form-table" style="background:#fff;">
					<tr valign="top">
						<td scope="row" style="text-align: center;" id="valid_sync">
							<span id="load_sync"></span>
							<p id="egoi_sinc_users_wp"><div class="egoi_sinc_users"><?php _e( 'Loading Subscribers Information...', 'egoi-for-wp' ); ?></div></p>
						</td>
					</tr>
				</table>
			</div>
			<?php

		}
		?>
		<input name="egoi_sync[lazy_sync]" value="<?php echo $this->options_list['lazy_sync']; ?>" hidden>
		<input name="egoi_sync[backend_order]" value="<?php echo $this->options_list['backend_order']; ?>" hidden>
		<input name="egoi_sync[backend_order_state]" value="<?php echo $this->options_list['backend_order_state']; ?>" hidden>
		<div class="smsnf-grid">
			<div>

				<div class="smsnf-input-group">
					<label for="egoi_sync"><?php _e( 'Enable Auto-Sync', 'egoi-for-wp' ); ?></label>
					<p class="subtitle"><?php _e( 'Select "yes" if you want the plugin to "listen" to all changes in your WordPress user base and auto-sync them with the selected Egoi list', 'egoi-for-wp' ); ?></p>
					<div class="smsnf-wrapper" style="display: flex;align-items: flex-end;margin-top: 12px;">
						<label><input type="radio"  name="egoi_sync[enabled]" <?php checked( $this->options_list['enabled'], 1 ); ?> value="1"><?php _e( 'Yes', 'egoi-for-wp' ); ?></label> &nbsp;
						<label><input type="radio" name="egoi_sync[enabled]" <?php checked( $this->options_list['enabled'], 0 ); ?> value="0"><?php _e( 'No', 'egoi-for-wp' ); ?></label>
					</div>
				</div>

				<div class="smsnf-input-group">
					<label for="egoi_sync"><?php _e( '"Subscribe to Newsletter" default', 'egoi-for-wp' ); ?></label>
					<p class="subtitle"><?php _e( 'By activating this option, the Newsletter checkbox will be active by default, which could lead to legal issues under the GDPR', 'egoi-for-wp' ); ?></p>
					<div class="smsnf-wrapper" style="display: flex;align-items: flex-end;margin-top: 12px;">
						<label><input type="radio"  name="egoi_sync[egoi_newsletter_active]" <?php checked( $this->options_list['egoi_newsletter_active'], 1 ); ?> value="1"><?php _e( 'On', 'egoi-for-wp' ); ?></label> &nbsp;
						<label><input type="radio" name="egoi_sync[egoi_newsletter_active]" <?php checked( $this->options_list['egoi_newsletter_active'], 0 ); ?> value="0"><?php _e( 'Off', 'egoi-for-wp' ); ?></label>
					</div>
				</div>

				<div class="smsnf-input-group">
					<label for="sub_button_position"><?php _e( '"Subscribe to Newsletter" position', 'egoi-for-wp' ); ?></label>
					<p class="subtitle"><?php _e( 'Select the position it will be displayed in your checkout form.', 'egoi-for-wp' ); ?></p>
					<div class="smsnf-wrapper">
						<select id="sub_button_position" name="egoi_sync[sub_button_position]" class="form-select" >
							<?php foreach ( $positions as $key => $value ) { ?>
								<option value="<?php echo $key; ?>" <?php selected( $this->options_list['sub_button_position'], $key ); ?>> <?php echo $value; ?> </option>
								<?php
							}
							?>
						</select>
					</div>
				</div>

				<div class="smsnf-input-group">
					<label for="list"><?php _e( 'Sync users with this list', 'egoi-for-wp' ); ?></label>
					<p class="subtitle"><?php _e( 'Select the E-goi\'s list for your subscribers.', 'egoi-for-wp' ); ?></p>
					<div class="smsnf-wrapper">

						<?php

						if ( empty( $lists ) ) {

							printf( __( 'No lists found, <a href="%s">are you connected to E-goi</a> and/or have created lists?', 'egoi-for-wp' ), admin_url( 'admin.php?page=egoi-for-wp' ) );

						} else {
							?>

							<select id="list" name="egoi_sync[list]" required class="form-select" >
								<?php
								$array_list = '';
								foreach ( $lists as $list ) {

									if ( $list['public_name'] ) {
										?>
										<option value="<?php echo esc_textarea( $list['list_id'] ); ?>" <?php selected( $this->options_list['list'], $list['list_id'] ); ?>>
											<?php echo esc_textarea($list['public_name'] ); ?>
										</option>
										<?php
										$array_list .= $list['list_id'] . ' - ';
									}
								}
								?>
							</select>
							<p class="subtitle"><?php _e( 'Select the list to synchronize your WordPress user base with.', 'egoi-for-wp' ); ?></p>
							<?php
						}
						?>
					</div>
				</div>

				<div class="smsnf-input-group">
					<label for="role"><?php _e( 'Sync users with this role', 'egoi-for-wp' ); ?></label>
					<p class="subtitle"><?php _e( 'Select the role to synchronize your Subscribers with.', 'egoi-for-wp' ); ?></p>
					<div class="smsnf-wrapper">
						<select id="role" name="egoi_sync[role]" class="form-select" >
							<option value="" <?php selected( $this->options_list['role'], '' ); ?>><?php _e( 'All roles', 'egoi-for-wp' ); ?></option>
							<?php
							$roles = get_editable_roles();
							foreach ( $roles as $key_role => $role ) {
								?>
								<option value="<?php echo $key_role; ?>" <?php selected( $this->options_list['role'], $key_role ); ?>> <?php echo $role['name']; ?> </option>
								<?php
							}
							?>
						</select>
					</div>
				</div>

				<div class="smsnf-input-group">
					<label for="role"><?php _e( 'Sync existing WP Users', 'egoi-for-wp' ); ?></label>
					<div class="smsnf-wrapper">
						<?php

						if ( $count_users['total_users'] > '100000' ) {
							?>

							<button type="button" class="smsnf-btn smsnf-btn-mt10" disabled><?php echo _e( 'Manual Sync', 'egoi-for-wp' ); ?></button>
							<p class="subtitle">
								<?php
								_e( 'You have too much WP Users to be assigned in bulk!', 'egoi-for-wp' );
								?>
							</p>
							<?php

						} else {
							?>
							<div class="smsnf-btn-mt10" style="display: flex;align-items: center;">
								<button type="button" class="smsnf-btn" id="update_users"><?php echo _e( 'Manual Sync', 'egoi-for-wp' ); ?></button>
								<?php echo getLoader( 'load', false ); ?>
								<span id="e-goi_import_valid" class="dashicons dashicons-yes" style="display: none;"></span>

							</div>

							<div id="e-goi_import_error" style="display:none;">
								<span class="dashicons dashicons-no-alt"></span>
							</div>
							<p class="subtitle">
								<?php
								_e( 'When manual sync is loading you should not do anything in this page but you can navigate to other pages in another window/tab', 'egoi-for-wp' );
								?>
							</p>
							<?php
						}
						?>
					</div>
				</div>


				<?php

				if ( $this->options_list['enabled'] ) {
					?>
					<div class="smsnf-input-group">
						<label for="catalog_language"><?php _e( 'Sync custom fields', 'egoi-for-wp' ); ?></label>
						<div class="smsnf-wrapper">
							<a href="/?TB_inline?width=700&height=750&inlineId=egoi-for-wp-form-map&modal=true" id="map" class="thickbox smsnf-btn smsnf-btn-mt10">
								<?php _e( 'Map Custom fields', 'egoi-for-wp' ); ?>
							</a>
						</div>
					</div>

					<?php
				}
				?>
			</div>
		</div>
		<div class="smsnf-input-group">
			<input type="submit" id="save_sync_button" value="<?php _e( 'Save Changes', 'egoi-for-wp' ); ?>" />
		</div>
	</form>
</div>
