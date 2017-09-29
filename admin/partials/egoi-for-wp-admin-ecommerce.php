<?php
defined( 'ABSPATH' ) or exit;

if(isset($_POST['action'])){
		
	$post = $_POST;
	update_option('egoi_sync', array_merge($this->options_list, $post['egoi_sync']));

	echo '<div class="e-goi-notice updated notice is-dismissible"><p>';
		_e('Ecommerce Option Updated!', 'egoi-for-wp');
	echo '</p></div>';

	$options = get_option('egoi_sync');
	
}else{
	$options = $this->options_list;
}

if(!$options['list']) { ?>
	<div class="postbox" style="margin-top:20px; max-width:80%; padding:5px 20px 5px; border-left:2px solid red;">
		<div style="padding:10px 0;">
			<span style="color: orangered; margin-top:5px;" class="dashicons dashicons-warning"></span>
			<span style="display: inline-block; line-height: 22px; font-size: 16px; margin-left: 12px; margin-top: 3px;"><?php _e( 'Select your mailing list in the option "Synchronize users with this list" to activate Track & Engage.<br>You will find this option in "<a href="http://wordpress.dev/wp-admin/admin.php?page=egoi-4-wp-subscribers">Sync contacts</a>" menu.', 'egoi-for-wp' ); ?>
			</span>
		</div>
	</div><?php
} ?>


<div class="postbox" style="margin-top:20px; max-width:80%; padding: 5px 20px 5px;">
	
	<div>
		<h1><?php _e( 'Track&Engage', 'egoi-for-wp' ); ?></h1>
	</div>

	<div>
		<span style="padding:15px 0; font-size:16px;display: inline-block;">
			<span style="display: inline-block; max-witdh:100px;"><?php _e('Do you want to automatically tracks what your customers do on your site and engages them where it counts? <p><span style="font-size:16px;">Simply add our HTML code snippet to your site, select "Yes" and you\'re all set. Here\'s how to <a target="_blank" href="https://helpdesk.e-goi.com/416945-Using-Track--Engage-to-track-subscribers-across-my-site">do</a>', 'egoi-for-wp'); ?>
			</span>
		</span>
	</div>

	<?php
	if($options['list']) { ?>

		<form method="post" action="#"><?php 
				
			settings_fields( Egoi_For_Wp_Admin::OPTION_NAME );
			settings_errors(); ?>

			<span style="display: inline-block; font-size:16px;">
				<b><?php _e( 'Activate Track&Engage', 'egoi-for-wp' ); ?></b>
			</span>
			
			<span style="margin-left:20px; font-size:18px;">

				<input id="yes" type="radio" name="egoi_sync[track]" <?php checked( $options['track'], 1 ); ?> value="1">
				<label for="yes"><?php _e( 'Yes', 'egoi-for-wp' ); ?></label> &nbsp;
				
				<input id="no" type="radio" name="egoi_sync[track]" <?php checked( $options['track'], 0 ); ?> value="0">
				<label for="no"><?php _e( 'No', 'egoi-for-wp' ); ?></label>
			</span>
			
			<div style="font-size:16px; margin-top:10px;line-height:28px; margin-bottom:10px;"><?php _e( 'If you want the plugin track your WP Users don\'t forget to have your WooCommerce activated.', 'egoi-for-wp' ); ?></div>

			<button style="margin-top: 20px; margin-bottom: 20px;" class="button-primary"><?php _e( 'Save', 'egoi-for-wp' ); ?></button>
		</form><?php

	} ?>
	
</div>