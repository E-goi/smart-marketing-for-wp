<section class="smsnf-content">
	<div>
		<form method="post" id="create-webpush-form" action="<?php admin_url( 'admin.php?page=egoi-4-wp-webpush&sub=create-wp' ); ?>">
			<input value="create-webpush-form" name="form_id" hidden>
			<?php
			wp_nonce_field( 'create-webpush-form' );
			?>

			<div class="smsnf-grid">
				<div>
					<div class="smsnf-input-group">
						<label for="max_width"><?php _e( 'Web Push Title', 'egoi-for-wp' ); ?></label>
						<p class="subtitle"><?php _e( 'When visitors subscribe, the URL will include...', 'egoi-for-wp' ); ?></p>
						<input style="max-width: 400px;" type="text" required
							   name="create_wp_form[label]" autocomplete="off"
							   placeholder="<?php _e( 'Example: ' . get_bloginfo() ); ?>" />
					</div>

					<div class="smsnf-input-group">
						<label for="list"><?php _e( 'Web Push List', 'egoi-for-wp' ); ?></label>
						<p class="subtitle"><?php _e( 'Visitors opting in to your notifications will be kept in...', 'egoi-for-wp' ); ?></p>
						<div class="smsnf-wrapper">

							<?php
							if ( empty( $lists ) ) {
								printf( __( 'No lists found, <a href="%s">are you connected to E-goi</a> and/or have created lists?', 'egoi-for-wp' ), admin_url( 'admin.php?page=egoi-for-wp' ) );
							} else {
								?>
								<select name="create_wp_form[list]" required class="form-select" >
								<?php
									$array_list = '';
								foreach ( $lists as $list ) {

									if ( $list->title ) {
										?>
										<option value="<?php echo esc_textarea( $list->listnum ); ?>" <?php selected( $this->options_list['list'], $list->listnum ); ?>>
											<?php echo esc_textarea( $list->title ); ?>
											</option>
											<?php
											$array_list .= $list->listnum . ' - ';
									}
								}
								?>
								</select>
							<?php } ?>

						</div>
					</div>
				</div>
			</div>
			<div class="smsnf-input-group">
				<input type="submit" value="<?php _e( 'Save Changes', 'egoi-for-wp' ); ?>" />
			</div>
		</form>
	</div>
</section>
