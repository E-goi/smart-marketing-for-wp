<div class="tab-pane fade" id="v-pills-cs" role="tabpanel" aria-labelledby="v-pills-cs-tab">
	<p>Configure Connected Sites</p>
	<form id="form-cs" method="post" action="#">

		<div class="smsnf-grid">
			<div>

				<div class="smsnf-input-group">
					<label for="domain">· <?php _e( 'Domain', 'egoi-for-wp' ); ?></label>
					<p class="subtitle"><?php _e( 'Domain that will be connected', 'egoi-for-wp' ); ?></p>
					<input id="domain" name="domain" type="text" placeholder="<?php _e( 'Write website domain', 'egoi-for-wp' ); ?>" value="<?php echo parse_url( get_site_url() )['host']; ?>" required autocomplete="off" />
				</div>

				<br/>

				<div class="smsnf-input-group">
					<label for="egoi_sync[track]">· <?php _e( 'Activate Connected Sites', 'egoi-for-wp' ); ?></label>
					<p class="subtitle"><?php _e( 'Will inject Connected Sites script in your website', 'egoi-for-wp' ); ?></p>
					<div class="form-group switch-yes-no">
						<label class="form-switch">
							<input id="egoi_sync[track]" type="checkbox" name="egoi_sync[track]" value="1" checked>
							<i class="form-icon"></i><div class="yes"><?php _e( 'Yes', 'egoi-for-wp' ); ?> <span style="font-size: small;font-weight: 100;color: black;opacity: 50%">(<?php _e( 'Recommended', 'egoi-for-wp' ); ?>)</span></div><div class="no"><?php _e( 'No', 'egoi-for-wp' ); ?></div>
						</label>
					</div>
				</div>

			</div>
		</div>

	</form>
</div>
