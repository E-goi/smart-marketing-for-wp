<div class="tab-pane fade show active" id="v-pills-subscribers" role="tabpanel" aria-labelledby="v-pills-subscribers-tab">
	<p>Setup list infos here, mapping and contact sync</p>
	<form id="form-subscribers" method="post" action="#">

		<div class="smsnf-grid">
			<div>
				<div class="smsnf-input-group">
					<label for="list">· <?php _e( 'Sync users with this list', 'egoi-for-wp' ); ?></label>
					<p class="subtitle"><?php _e( 'Select the E-goi\'s list for your subscribers.', 'egoi-for-wp' ); ?></p>
					<div class="smsnf-wrapper">
						<select id="list" name="egoi_sync[list]" required class="form-select" >
							<option disabled value="" selected>
								<?php _e( 'Select a list..', 'egoi-for-wp' ); ?>
							</option>
						</select>
					</div>
					<div id="loading-subs-import" style="display: none;">
						<div class="progress">
							<div id="progressbar-subs-import" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						<span id="subs-progress" style="font-size: x-small;"></span>
					</div>
				</div>

				<br/>

				<div class="smsnf-input-group">
					<label for="role">· <?php _e( 'Sync users with this role', 'egoi-for-wp' ); ?></label>
					<p class="subtitle"><?php _e( 'Select the role to synchronize your Subscribers with.', 'egoi-for-wp' ); ?></p>
					<div class="smsnf-wrapper">
						<select id="role" name="egoi_sync[role]" class="form-select" >
							<option value="" selected><?php _e( 'All roles', 'egoi-for-wp' ); ?></option>
							<?php
							$roles = get_editable_roles();
							foreach ( $roles as $key_role => $role ) {
								?>
								<option value="<?php echo $key_role; ?>"> <?php echo $role['name']; ?> </option>
								<?php
							}
							?>
						</select>
					</div>
				</div>

			</div>
		</div>


	</form>
</div>
