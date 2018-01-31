<?php

if ( ! defined( 'ABSPATH' ) ) {
    die();
}

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
?>

<h1 class="logo">Smart Marketing - <?php _e( 'Ecommerce', 'egoi-for-wp' ); ?></h1>
	<p class="breadcrumbs">
		<span class="prefix"><?php echo __( 'You are here: ', 'egoi-for-wp' ); ?></span>
		<strong>Smart Marketing</a> &rsaquo;
		<span class="current-crumb"><?php _e( 'Ecommerce', 'egoi-for-wp' ); ?></strong></span>
	</p>
<hr/>

<?php

if(!$options['list']) { ?>
	<div class="postbox" style="margin-top:20px; max-width:80%; padding:5px 20px 5px; border-left:2px solid red;">
		<div style="padding:10px 0;">
			<span style="color: orangered; margin-top:5px;" class="dashicons dashicons-warning"></span>
			<span style="display: inline-block; line-height: 22px; font-size: 16px; margin-left: 12px; margin-top: 3px;">
			<?php 
			_e('Select your mailing list in the option "Synchronize users with this list" to activate Track & Engage.<br>You will find this option in ', 'egoi-for-wp'); 
			?>
			<a href="<?php echo $this->protocol . $_SERVER['SERVER_NAME'] . $this->port;?>/wp-admin/admin.php?page=egoi-4-wp-subscribers">
				<?php _e('Sync Contacts', 'egoi-for-wp'); ?></a>
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
			<span style="display: inline-block; max-witdh:100px;"><?php _e('Track & Engage is an E-goi analytics feature, connected to your Wordpress Website or WooCommerce Store, perfect for remarketing actions like returning users or abandoned cart.<p><span style="font-size:16px;">Activate this option here, and confirm if Track & Engage is also active in E-goi Platform (Web -> Track & Engage).</span></p><p><span style="font-size:16px;">To know more about the feature Track & Engage, check <a target="_blank" href="https://helpdesk.e-goi.com/416945-Using-Track--Engage-to-track-subscribers-across-my-site">here</a>.</span></p>', 'egoi-for-wp'); ?>
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
			
			<div style="font-size:16px; margin-top:10px;line-height:28px; margin-bottom:10px;"></div>

			<button style="margin-top: 20px; margin-bottom: 20px;" class="button-primary"><?php _e( 'Save', 'egoi-for-wp' ); ?></button>
		</form><?php

	} ?>
	
</div>