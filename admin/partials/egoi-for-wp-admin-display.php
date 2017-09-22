<?php
$egoi = new Egoi_For_Wp;
update_option('Egoi4WpBuilderObject',$egoi);

$opt = get_option('egoi_data');

$apikey = get_option('egoi_api_key');
$api_key = $apikey['api_key'];

	
	if(isset($_POST['egoi_wp_createlist']) && (!empty($_POST['egoi_wp_title']))) {
	
		$name = $_POST['egoi_wp_title'];
		$lang = $_POST['egoi_wp_lang'];
		$new_list = $egoi->createList($name,$lang);
		
		if(is_string($new_list)){
			echo '<div class="e-goi-notice error notice is-dismissible"><p>';
				_e('No more lists allowed in your account!', 'egoi-for-wp');
			echo '</p></div>';
		}else{
			echo '<div class="e-goi-notice updated notice is-dismissible"><p>';
				_e('New list created successfully!', 'egoi-for-wp');
			echo '</p></div>';
		}

		update_option('Egoi4WpBuilderObject', $egoi);

	}else{

		if( isset($_POST['egoi_wp_createlist']) && (empty($_POST['egoi_wp_title'])) ) {
			echo '<div class="e-goi-notice updated notice is-dismissible"><p>';
				_e('Empty data!', 'egoi-for-wp');
			echo '</p></div>';
		}
	}


	$lists = $egoi->getLists($start, $limit);

	$list_name = '';
	foreach($result as $key_value => $list) {
		$title = $list->title;
		$list_name .= $title.' - ';
	}

	$total_lists = count(array_filter(explode(' - ', $list_name)));			
	update_option('Egoi4WpBuilderObject', $egoi);

?>
<script type="text/javascript">
	function hide_show_apikey(){
		var apikey = document.getElementById('apikey');
		var span = document.getElementById('api_key_span');
		var ok = document.getElementById('ok');
		var edit = document.getElementById('edit');
		if(apikey.style.display == 'none'){
			span.style.display = 'none';
			edit.style.display = 'none';
			apikey.style.display = 'inline-block';
			ok.style.display = 'inline-block';
		}else{
			apikey.style.display = 'none';
			ok.style.display = 'none';
			span.style.display = 'inline-block';
			edit.style.display = 'block';
		}
	}

</script>
<style type="text/css">
.form-table th{
	padding: 15px 10px 10px 0 !important;
}
</style>

<!-- Breadcrumbs -->
<h1 class="logo">Smart Marketing - <?php echo __('Account', 'egoi-for-wp');?></h1>
	<p class="breadcrumbs">
		<span class="prefix"><?php echo __('You are here: ', 'egoi-for-wp'); ?></span>
		<strong>Smart Marketing &rsaquo;
		<span class="current-crumb"><?php echo __('Account', 'egoi-for-wp');?></strong></span>
	</p>
<hr/>

<div class='wrap' id="wrap--acoount">
	<div class="main-content">
		<div id="icon-wp-info" class="icon32"></div>

		<div class="postbox">
      		<?php 
	      	// If exists in db
	      	if($api_key){

	      		$api_client = $egoi->getClient();
	      		if($api_client->response == 'INVALID'){ ?>

					<div class="e-goi-account-apikey">
						<!-- Titulo Error-->
						<div class="e-goi-account-apikey__connect-failed">
							<span class="dashicons dashicons-warning dashicons-warning--apikeyinvalid"></span>
							<h1 class="e-goi-account-apikey__title--connect-failed" for="egoi_wp_apikey">
								<?php echo __('Connection Refused!', 'egoi-for-wp');?>
							</h1>
						</div>

		      			<div class="apikey-error">
		      				<?php echo __('API key is invalid OR is empty! Please insert you valid apikey <a href="admin.php?page=egoi-4-wp-account">here</a>', 'egoi-for-wp'); ?>
		      			</div>

	      			<?php update_option('egoi_api_key', '');
	      			exit();

	      		}else{?>
					
					<style>
					#wpfooter{
						position: relative !important;
					}
					</style>
					
					 <!-- API Key do E-goi -->
					<div>
						<form name='egoi_apikey_form' method='post' action='<?php echo admin_url('options.php');?>'>
							<?php
								settings_fields( Egoi_For_Wp_Admin::API_OPTION );
								settings_errors();
								?>
							<div class="e-goi-account-apikey">
								<!-- Titulo -->
								<div class="e-goi-account-apikey__title" for="egoi_wp_apikey">
									<?php echo __('API Key do E-goi');?>
								</div>

								<!-- Api key and btn -->
								<div class="e-goi-account-apikey__actions">

									<span class="e-goi-account-apikey__actions__form" size="55" maxlength="40" id="api_key_span"><?php echo substr($api_key, 1, 30).'**********';?></span>

									<input type="text" class="e-goi-account-apikey__actions__form--input" style="display:none;" autofocus size="55" maxlength="40" id="apikey" name="egoi_api_key[api_key]" 
										value="<?php echo $api_key;?>">

									<span id="ok" class="button-primary e-goi-account-btn" style="display:none;" onclick="document.egoi_apikey_form.submit();">
										<?php echo __('Save', 'egoi-for-wp');?>
									</span>

									<a type="button" id="edit" class="button e-goi-account-btn" onclick="hide_show_apikey();"> 
										<?php echo __('Edit API Key', 'egoi-for-wp');?>
									</a>
									
								</div>
							</div>

							<div class="e-goi-separator"></div>

							<div class="e-goi-account-apikey__actions__btn-change-account">
								<a href="//bo.e-goi.com/?from=<?php echo urlencode('/?action=dados_cliente&menu=sec');?>" target="_blank" class='link' id="egoi_edit_info">
									<?php echo __('Change Account Info in E-goi?', 'egoi-for-wp');?>
								</a>
								<?php echo __('You will be redirected to E-goi', 'egoi-for-wp');?>
								</span>
							</div>
						</form>	
					</div> <!-- .API Key -->

					<?php
				}


			}else{

			// if apikey not exists ?>
			<div class="e-goi-account-apikey">
				<!-- Titulo -->
				<h1 class="e-goi-account-apikey__title">
					<?php echo __('Enter the API key of your E-goi account', 'egoi-for-wp');?>
				</h1>
			
				<form name='egoi_apikey_form' method='post' action='<?php echo admin_url('options.php'); ?>' autocomplete="off">
					<?php settings_fields(Egoi_For_Wp_Admin::API_OPTION); settings_errors(); ?>

						<!-- <label for="egoi_wp_apikey"><?php echo __('Your API key', 'egoi-for-wp');?></label> -->
						<input type='text' size='55' placeholder="<?php echo __('Paste here your E-goi API Key', 'egoi-for-wp'); ?>" maxlength="40" class="e-goi-account-apikey__actions__form--input" name='egoi_api_key[api_key]' id="egoi_api_key_input" /> 
					
						<button type="submit" class='button-primary e-goi-account-btn' id="egoi_4_wp_login" disabled="disabled"><span id="load" class="dashicons dashicons-update" style="display: none;"></span>
							<span id="api-save-text"><?php echo __('Save and Connect', 'egoi-for-wp');?>
							</span>
						</button>

						<div id="valid" style="display:none;">
							<span class="dashicons dashicons-yes"></span>
						</div>
						<div id="error" style="display:none;">
							<span class="dashicons dashicons-no-alt"></span>
						</div>
					
						<p class="e-goi-help-text">
							<?php echo __('<span class="dashicons dashicons-editor-help"></span>To retrieve your E­goi API Key you must login to your <a target="_blank" href="https://login.egoiapp.com/#/login"><span style="text-decoration:underline;">E­-goi</span></a> account and click the menu “Apps”', 'egoi-for-wp');?>
						</p>
				</form>
			</div>
			<div class="e-goi-separator"></div>

			<div class="e-goi-account-apikey-dont-have-account">
				<p><?php echo __("Don't have an E-goi account?", "egoi-for-wp");?></p>
				<a href="http://bo.e-goi.com/?action=registo" target="_blank"><?php echo __('Click here to create your account!</a> (takes less than 1 minute)</p>', 'egoi-for-wp');
				} ?>
			</div>

		</div><!-- .postbox -->
				<!-- List -->
			<?php if($lists->response != 'NO_USERNAME_AND_PASSWORD_AND_APIKEY'){
					  include 'egoi-for-wp-admin-lists.php';
				} ?>

			<!-- .List -->
	</div><!-- main-content -->

	<div class="e-goi-separator2"></div>
		<div class="e-goi-delete-account">
			<p><strong><?php echo __('Remove Data', 'egoi-for-wp');?></strong></p>
				<div class="e-goi-delete-account--actions">
					<label><input type="radio" name="egoi_data[remove]" <?php checked( $opt, 1 ); ?> value="1">		<?php echo __('Yes', 'egoi-for-wp');?></label> &nbsp;
					<label><input type="radio" name="egoi_data[remove]" <?php checked( $opt, 0 ); ?> value="0">		<?php echo __('No', 'egoi-for-wp');?></label>
					<a class='button-secondary' id="egoi_remove_data">
						<?php echo __('Save option', 'egoi-for-wp');?>
					</a> &nbsp;
					<span id="remove_valid" style="display:none;color: green;"><?php echo __('Option saved', 'egoi-for-wp');?></span>
				</div>

			<p class="help"><?php echo __('Select "Yes" if you want REMOVE the plugin and delete all saved data', 'egoi-for-wp');?>
			</p>
			
		</div>
	</div><!-- .wrap -->
