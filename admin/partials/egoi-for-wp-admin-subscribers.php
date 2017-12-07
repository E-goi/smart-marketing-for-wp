<?php
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

add_thickbox();

$Egoi4WpBuilderObject = get_option('Egoi4WpBuilderObject'); 
		
$lists = $Egoi4WpBuilderObject->getLists();
$mapped_fields = $Egoi4WpBuilderObject->getMappedFields();

$extra = $Egoi4WpBuilderObject->getExtraFields($this->options_list['list']);
$egoi_fields = array(
	'first_name' => 'First name',
	'last_name' => 'Last name',
	'surname' => 'Surname',
	'cellphone' => 'Mobile',
	'telephone' => 'Telephone',
	'birth_date' => 'Birth Date'
);

if($this->options_list['list']){
	if($extra){
		foreach($extra as $key => $extra_field){
			$egoi_fields[$key] = $extra_field->NAME;
		}
	}
}

if(class_exists('Woocommerce')){
	$wc = new WC_Admin_Profile();
	foreach ($wc->get_customer_meta_fields() as $key => $value) {
		foreach($value['fields'] as $key_value => $label){
			$wp_fields[$key_value] = $label['label'];
		}
	}
}

$count_users = count_users();
?>

<script type="text/javascript">
jQuery(document).ready(function($) {
	
	var listID = '<?php echo $this->options_list['list'];?>';
	var date_validation = $('#date_validation').val();
	// run on start
	runSS(listID);

	function runSS(listID){
		
		var role = '<?php echo $this->options_list['role'];?>';
		var data = {
	        action: 'synchronize',
	        list: listID,
	        role: role
	    };

	    jQuery.ajax({
	    	type: 'POST',
	    	data: data,
	    	success: function(response){
	    		resp = JSON.parse(response);
	    		egoi = resp[0];
	    		wp = resp[1];
	    		$('#egoi_sinc_users_wp').hide();
	    		$('#valid_sync').html('<?php _e( 'Subscribed in E-goi (Active)', 'egoi-for-wp' ); ?>: <span class="help"><b>'+egoi+'</b></span><p><?php _e( 'WordPress Users', 'egoi-for-wp' ); ?>: <span class="help"><b>'+wp+'</b></span><p>');
	    	}
	    });
	}

	$('#map').click(function() {
		$('#TB_window').css('width', '820px');
		$('#TB_ajaxContent').prop('width', '800px');
	});

	$('#update_users').click(function() {
		$('#e-goi_import_valid').hide();
		$('#load').show();
		var data = {
	        action: 'add_users',
	        listID: listID,
	        submit: 1
	    };

	    jQuery.post(ajaxurl, data, function(response) {
	    	$('#load').hide();
	    	$('#e-goi_import_valid').show();
	    	setTimeout(function () {
	    		runSS(listID);
	    	}, 5000);
	    });
	});

	$('#wpfooter').hide();

});
</script>
<h1 class="logo">Smart Marketing - <?php _e( 'Sync Contacts', 'egoi-for-wp' ); ?></h1>
	<p class="breadcrumbs">
		<span class="prefix"><?php echo __( 'You are here: ', 'egoi-for-wp' ); ?></span>
		<strong>Smart Marketing</a> &rsaquo;
		<span class="current-crumb"><?php _e( 'Sync Contacts', 'egoi-for-wp' ); ?></strong></span>
	</p>
<hr/>
<div class="wrap">

	<div class="main-content">

		<!-- Main Content -->
		<div class="col-4">

			<form method="post" action="<?php echo admin_url('options.php'); ?>"><?php 
				
				settings_fields( Egoi_For_Wp_Admin::OPTION_NAME );
				settings_errors();

				if($this->options_list['list'] !== '') { ?>

					<div style="background:#fff;border: 1px solid #ccc;text-align: center;"><?php 

						if($this->options_list['enabled']) {
							echo '<span style="background:#066;color:#fff;padding:5px;">'.__('Syncronization ON', 'egoi-for-wp').'</span><p>';
							_e( 'The plugin is listening to changes in your users and will automatically keep your WP users with the selected E-goi list.', 'egoi-for-wp' ); ?><?php
						} else {
							echo '<span style="background:#900;color:#fff;padding:5px;">'.__('Syncronization OFF', 'egoi-for-wp').'</span><p>';
							_e( 'The plugin is currently not listening to any changes in your users.', 'egoi-for-wp' ); 
						} ?>
						
						<table class="form-table" style="background:#fff;">
							<tr valign="top">
								<td scope="row" id="valid_sync">
									<span id="load_sync"></span>
									<p id="egoi_sinc_users_wp"><div class="egoi_sinc_users"><?php _e('Loading Subscribers Information...', 'egoi-for-wp');?></div></p>
								</td>
							</tr>
						</table>
					</div>
					<hr /><?php

				} ?>
				

				<table class="form-table">
					<tr>
						<th scope="row"><?php _e( 'Enable Auto-Sync', 'egoi-for-wp' ); ?></th>
						<td class="nowrap">
							<label><input type="radio" name="egoi_sync[enabled]" <?php checked( $this->options_list['enabled'], 1 ); ?> value="1"><?php _e( 'Yes', 'egoi-for-wp' ); ?></label> &nbsp;
							<label><input type="radio" name="egoi_sync[enabled]" <?php checked( $this->options_list['enabled'], 0 ); ?> value="0"><?php _e( 'No', 'egoi-for-wp' ); ?></label>
							<p class="help"><?php _e('Select "yes" if you want the plugin to "listen" to all changes in your WordPress user base and auto-sync them with the selected Egoi list', 'egoi-for-wp');?></p>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Sync users with this list', 'egoi-for-wp' ); ?></th>
						<td><?php 

							if(empty($lists)) {
									
								printf( __( 'No lists found, <a href="%s">are you connected to E-goi</a> and/or have created lists?', 'egoi-for-wp' ), admin_url( 'admin.php?page=egoi-for-wp' ) );
							
							}else{ ?>

								<select name="egoi_sync[list]" required><?php
									$array_list = '';
									foreach($lists as $list) {

										if($list->title){ ?>
											<option value="<?php echo $list->listnum;?>" <?php selected($this->options_list['list'], $list->listnum); ?>>
												<?php echo $list->title;?>
											</option><?php
											$array_list .= $list->listnum.' - ';
										}
									} ?>
								</select>
								<p class="help"><?php _e( 'Select the list to synchronize your WordPress user base with.', 'egoi-for-wp' ); ?></p><?php
							} ?>

						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Sync users with this role', 'egoi-for-wp' ); ?></th>
						<td>
							<select name="egoi_sync[role]">
								<option value="" <?php selected( $this->options_list['role'], '' ); ?>><?php _e( 'All roles', 'egoi-for-wp' ); ?></option><?php
								$roles = get_editable_roles();
								foreach($roles as $key_role => $role) {?>
									<option value="<?php echo $key_role;?>" <?php selected($this->options_list['role'], $key_role);?>> <?php echo $role['name'];?> </option><?php
								}?>
							</select>
							<p class="help"><?php _e( 'Select the role to synchronize your Subscribers with.', 'egoi-for-wp' ); ?></p>

						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e( 'Sync existing WP Users', 'egoi-for-wp' ); ?></th>
						<td class="nowrap"><?php
						
						if($count_users['total_users'] > '100000'){ ?>
							
							<button type="button" class="button" disabled><?php echo _e('Manual Sync', 'egoi-for-wp');?></button>
							<p class="help"><?php
								_e('You have too much WP Users to be assigned in bulk!', 'egoi-for-wp');?>
							</p><?php

						}else{ ?>

							<button type="button" class="button" id="update_users"><?php echo _e('Manual Sync', 'egoi-for-wp');?></button>
							<div id="load" class="dashicons dashicons-update" style="color:#000;display:none;">
							</div>
							
							<span id="e-goi_import_valid" class="dashicons dashicons-yes" style="display: none;"></span>

							<div id="e-goi_import_error" style="display:none;">
								<span class="dashicons dashicons-no-alt"></span>
							</div>
							<p class="help"><?php 							
								_e('When manual sync is loading you should not do anything in this page but you can navigate to other pages in another window/tab', 'egoi-for-wp'); ?>
							</p><?php
						} ?>
						</td>
					</tr><?php

					if($this->options_list['enabled']) { ?>
						<tr>
							<th scope="row"><?php _e( 'Sync custom fields', 'egoi-for-wp' ); ?></th>
							<td class="nowrap">
								<a href="/?TB_inline?width=700&height=750&inlineId=egoi-for-wp-form-map&modal=true" id="map" class="thickbox button-secondary">
									<?php _e('Map Custom fields', 'egoi-for-wp');?>
								</a>
							</td>
						</tr>

						<?php
					} ?>
					
				</table>

				<?php submit_button();?>
			</form>

		<!-- / Main Content -->
		</div>

		
		<!-- Mapeamento dos campos -->
		<div id="egoi-for-wp-form-map" style="display:none;width:700px;">
			<?php include(dirname( __FILE__ ).'/custom/egoi-for-wp-form-map.php');?>
		</div>

		<!-- Sidebar -->
		<div class="sidebar">
			<?php include ('egoi-for-wp-admin-sidebar.php'); ?>
		</div>

	</div>

</div>