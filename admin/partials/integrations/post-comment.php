<form method="post" action="">
	<?php
    require plugin_dir_path( __DIR__ ) . 'egoi-for-wp-admin-shortcodes.php';
    $FORM_OPTION = get_optionsform( 2 );
    settings_fields( $FORM_OPTION );

    ?>
	<input type="hidden" name="egoiform" value="egoi_int">
	<div class="smsnf-grid">
		<div>

			<div class="smsnf-input-group">
				<label for="egoi_int"><?php _e( 'Enable Post Comment Integration', 'egoi-for-wp' ); ?></label>
				<p class="subtitle"><?php _e( 'Select "yes" to enable Post Comment Integration.', 'egoi-for-wp' ); ?></p>
				<div class="smsnf-wrapper" style="display: flex;align-items: flex-end;margin-top: 12px;">
					<label><input type="radio"  name="egoi_int[enable_pc]" <?php if(isset($egoint['enable_pc'])) {checked( $egoint['enable_pc'], 1 ); } ?> value="1"><?php _e( 'Yes', 'egoi-for-wp' ); ?></label> &nbsp;
					<label><input type="radio" name="egoi_int[enable_pc]" <?php if(isset($egoint['enable_pc'])) {checked( $egoint['enable_pc'], 0 ); } ?> value="0"><?php _e( 'No', 'egoi-for-wp' ); ?></label>
				</div>
			</div>

			<?php if ( isset($egoint['enable_pc']) ) { ?>

				<div class="smsnf-input-group">
					<label for="list_cf"><?php _e( 'List to Subscribe', 'egoi-for-wp' ); ?></label>
					<?php if ( empty( $lists ) ) { ?>
						<td><?php printf( __( 'Lists not found, <a href="%s">are you connected to egoi</a>?', 'egoi-for-wp' ), admin_url( 'admin.php?page=egoi-4-wp-account' ) ); ?></td>
																											<?php
					} else {
						?>
						<p class="subtitle"><?php _e( 'Select the list to which who submit in Post Comment should be subscribed.', 'egoi-for-wp' ); ?></p>
						<div class="smsnf-wrapper">
							<select name="egoi_int[list_cp]" id="egoi4wp-lists" class="form-select">
							<?php
								$index = 1;
							foreach ( $lists as $list ) {
								if ( $list['public_name'] != '' ) {
									?>
										<option value="<?php echo esc_attr( $list['list_id'] ); ?>" <?php if(isset($egoint['list_cp'])){selected( $list['list_id'], $egoint['list_cp'] ); }?>><?php echo esc_html( $list['public_name'] ); ?></option>
																  <?php
								}
								$index++;
							}
							?>
							</select>
						</div>
					<?php } ?>
				</div>

			<?php } ?>

		</div>
	</div>

	<div class="egoi-undertable-button-wrapper" style="bottom: 0;position: absolute;right: 30px;">
		<div class="smsnf-input-group">
			<input type="submit" value="<?php _e( 'Save', 'egoi-for-wp' ); ?>" />
		</div>
	</div>

</form>
