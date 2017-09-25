<table>
	<tr>
		<th scope="row">
			<?php _e( 'Enable Track&Engage', 'egoi-for-wp' ); ?>
		</th>

		<td class="nowrap">
			<label>
				<input type="radio" name="egoi_sync[track]" 
				<?php checked( $this->options_list['track'], 1 ); ?> value="1">
				<?php _e( 'Yes', 'egoi-for-wp' ); ?></label> &nbsp;
			<label>
				<input type="radio" name="egoi_sync[track]" 
				<?php checked( $this->options_list['track'], 0 ); ?> value="0"><?php _e( 'No', 'egoi-for-wp' ); ?>
			</label>
			<p class="help">
				<?php 
					_e('<b>First activate Track&Engage in E-goi - How to do in <a target="_blank" href="https://helpdesk.e-goi.com/416945-Using-Track--Engage-to-track-subscribers-across-my-site">here</a></b> <p>Then Select "yes" if you want the plugin to track your WP Users when using plugin Woocommerce', 'egoi-for-wp'); ?>
			</p>
		</td>
	</tr>
</table>