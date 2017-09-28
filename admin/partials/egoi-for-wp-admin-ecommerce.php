<div class="postbox" style="margin-top:20px; padding: 5px 20px 30px;">

	<div>
		<h1><?php _e( 'Track&Engage', 'egoi-for-wp' ); ?></h1>
	</div>
	
	<div>
		<span style="padding:15px 0; font-size:16px;display: inline-block;">
			<span style="display: inline-block; max-witdh:100px;"><?php _e('Do you want to automatically tracks what your customers do on your site and engages them where it counts? <p><span style="font-size:16px;">Simply add our HTML code snippet to your site, select "Yes" and you\'re all set. Here\'s how to <a target="_blank" href="https://helpdesk.e-goi.com/416945-Using-Track--Engage-to-track-subscribers-across-my-site">do</a>', 'egoi-for-wp'); ?>
			</span>
		</span>
	</div>

	<span style="display: inline-block; font-size:16px;">
		<b><?php _e( 'Activate Track&Engage', 'egoi-for-wp' ); ?></b>
	</span>
	
	<span style="margin-left:20px; font-size:18px;">
		
		<input id="yes" type="radio" name="egoi_sync[track]" <?php checked( $this->options_list['track'], 1 ); ?> value="1">
		<label for="yes"><?php _e( 'Yes', 'egoi-for-wp' ); ?></label> &nbsp;
		
		<input id="no" type="radio" name="egoi_sync[track]" <?php checked( $this->options_list['track'], 0 ); ?> value="0">
		<label for="no"><?php _e( 'No', 'egoi-for-wp' ); ?></label>
	</span>

	<div style="font-size:16px; margin-top:30px;line-height:28px;"><?php _e( 'If you want the plugin track your WP Users don\'t forget to have your WooCommerce activated.', 'egoi-for-wp' ); ?></div>
</div>


