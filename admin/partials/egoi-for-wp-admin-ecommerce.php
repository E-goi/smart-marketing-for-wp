<div class="postbox" style="margin-top:20px; padding: 5px 20px 30px;">
			<div>
				<h1><?php _e( 'Track&Engage', 'egoi-for-wp' ); ?></h1>
			</div>
			<div>
				<span style="padding:15px 0; font-size:16px;display: inline-block;"><?php _e('First activate Track&Engage in E-goi - How to do in <a target="_blank" href="https://helpdesk.e-goi.com/416945-Using-Track--Engage-to-track-subscribers-across-my-site">here</a> 
					<p>
					<span style="font-size:16px;">Then Select "yes" if you want the plugin to track your WP Users when using plugin Woocommerce', 'egoi-for-wp'); ?>
				</span>
			</div>
			<span style="display: inline-block; font-size:16px;">
				<b><?php _e( 'Enable Track&Engage', 'egoi-for-wp' ); ?></b>
			</span>
			<span style="margin-left:20px; font-size:18px;">
				
			<input type="radio" name="egoi_sync[track]" 
				<?php checked( $this->options_list['track'], 1 ); ?> value="1">
					<?php _e( 'Yes', 'egoi-for-wp' ); ?></label> &nbsp;
					<input type="radio" name="egoi_sync[track]" 
					<?php checked( $this->options_list['track'], 0 ); ?> value="0"><?php _e( 'No', 'egoi-for-wp' ); ?>
			</span>
		</div>