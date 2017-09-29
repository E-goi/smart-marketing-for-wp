<?php
$egoi = new Egoi_For_Wp;
update_option('Egoi4WpBuilderObject', $egoi);

$opt = get_option('egoi_data');

$apikey = get_option('egoi_api_key');
$api_key = $apikey['api_key'];

	// Create a list //
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
<!-- SCRIPTS for APi Key -->
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

<!-- STYLE on this page - Position the text footer fixed to the bottom --> 
<style>#wpfooter{position: relative !important;}</style>


<!-- HEADER and Breadcrumbs -->
<h1 class="logo">Smart Marketing - <?php echo __('Account', 'egoi-for-wp');?></h1>
	<p class="breadcrumbs">
		<span class="prefix"><?php echo __('You are here: ', 'egoi-for-wp'); ?></span>
		<strong>Smart Marketing &rsaquo;
		<span class="current-crumb"><?php echo __('Account', 'egoi-for-wp');?></strong></span>
	</p>
<hr/>

<!-- CONTENT -->
<div class='wrap-content' id="wrap--acoount">
	<div class="main-content">
		<div id="icon-wp-info" class="icon32"></div>
			<div class="wrap-content--API">
	      		<?php 
		      	// If exists in BD
		      	if($api_key){

		      		$api_client = $egoi->getClient();
		      		if($api_client->response == 'INVALID'){ ?>

						<div class="e-goi-account-apikey">
							<!-- Title Error -->
							<div class="e-goi-account-apikey__connect-failed">
								<span class="dashicons dashicons-warning dashicons-warning--apikeyinvalid"></span>
								<div class="" for="egoi_wp_apikey">
									<?php echo __('Connection Refused!', 'egoi-for-wp');?>
								</div>
							</div>

			      			<div class="apikey-error__text">
			      				<?php echo __('API key is invalid OR is empty! Please insert you valid apikey <a href="admin.php?page=egoi-4-wp-account">here</a>', 'egoi-for-wp'); ?>
			      			</div>

		      			<?php update_option('egoi_api_key', '');
		      			exit();

		      		}else{ ?>
						
						 <!-- API Key do E-goi -->
						<div>
							<form name='egoi_apikey_form' method='post' action='<?php echo admin_url('options.php');?>'>
								<?php
									settings_fields( Egoi_For_Wp_Admin::API_OPTION );
									settings_errors();
									?>
								<div class="e-goi-account-apikey">
									<!-- Title -->
									<div class="e-goi-account-apikey--title" for="egoi_wp_apikey">
										<?php echo __('API Key do E-goi');?>
									</div>

									<!-- API key and btn -->
									<div class="e-goi-account-apikey--grp">

										<span class="e-goi-account-apikey--grp--form" size="55" maxlength="40" id="api_key_span">
											<?php echo substr($api_key, 1, 30).'**********';?>
										</span>

										<input type="text" class="e-goi-account-apikey--grp--form__input" style="display:none;" autofocus size="55" maxlength="40" id="apikey" name="egoi_api_key[api_key]" 
											value="<?php echo substr($api_key, 1, 30).'**********';?>">

										<span id="ok" class="button-primary button-primary--custom" style="display:none;" onclick="document.egoi_apikey_form.submit();">
											<?php echo __('Save', 'egoi-for-wp');?>
										</span>

										<a type="button" id="edit" class="button button--custom" onclick="hide_show_apikey();"> 
											<?php echo __('Edit API Key', 'egoi-for-wp');?>
										</a>
										
									</div>
								</div>
								<hr>
								<div class="e-goi-account-apikey--link--account-settings">
									<a href="//bo.e-goi.com/?from=<?php echo urlencode('/?action=dados_cliente&menu=sec');?>" target="_blank" id="egoi_edit_info">
										<?php echo __('Click here if you want to change your E-goi account info', 'egoi-for-wp');?>
									</a>
									</span>
								</div>
							</form>	
						</div> <!-- .API Key -->

					<?php }

				}else{ ?>
					<!-- if apikey not exists on BD -->
					<div class="e-goi-account-apikey">
						<!-- Title-->
						<div class="e-goi-account-apikey--title">
							<?php echo __('Enter the API key of your E-goi account', 'egoi-for-wp');?>
						</div>
						<form name='egoi_apikey_form' method='post' action='<?php echo admin_url('options.php'); ?>' autocomplete="off">
							<?php settings_fields(Egoi_For_Wp_Admin::API_OPTION); settings_errors(); ?>

								<input type='text' size='55' placeholder="<?php echo __('Paste here your E-goi\'s API key', 'egoi-for-wp'); ?>" maxlength="40" class="e-goi-account-apikey--grp--form__input" name='egoi_api_key[api_key]' id="egoi_api_key_input" /> 
							
								<button type="submit" class='button-primary button-primary--custom' id="egoi_4_wp_login" disabled="disabled"><span id="load" class="dashicons dashicons-update" style="display: none;"></span>
									<span id="api-save-text"><?php echo __('Save', 'egoi-for-wp');?>
									</span>
								</button>

								<div id="valid" style="display:none;">
									<span class="dashicons dashicons-yes"></span>
								</div>
								<div id="error" style="display:none;">
									<span class="dashicons dashicons-no-alt"></span>
								</div>


							<!-- Tooltip - help -->					
								<p class="e-goi-help-text">
									<span class="e-goi-tooltip">
										 <span class="dashicons dashicons-editor-help"></span>
									  	 <span class="e-goi-tooltiptext e-goi-tooltiptext--custom" style="padding: 5px 8px;!important;"><?php _e( 'Can\'t find your API Key? We help you » <a href="https://helpdesk.e-goi.com/858130-O-que-%C3%A9-a-API-do-E-goi-e-onde-est%C3%A1-a-API-key" target="_blank">here!</a>', 'egoi-for-wp' ); ?>
									 	</span>
									 </span>
									<span><?php echo __('To get your API key simply click the "Apps" menu in your account <span style="text-decoration:underline;"><a target="_blank" href="https://login.egoiapp.com/#/login/?menu=sec">E­-goi</span></a> and copy it.', 'egoi-for-wp');?>
								</p>
						</form>
					</div>
					
					<div class="e-goi-separator"></div>
					<div class="e-goi-account-apikey-dont-have-account">
						<p><?php echo __("Don't have an E-goi account?", "egoi-for-wp");?></p>
						<a href="http://bo.e-goi.com/?action=registo" target="_blank"><?php echo __("Click here to create your account</a> (It's free and takes less than 1 minute)</p>", "egoi-for-wp"); ?>
					</div><?php

				} ?>
				</div><!-- .wrap-content-API -->

				
				<!-- LISTS -->
				<?php 
				
				// Display lists ERROR
				if ($lists->ERROR){

					update_option('egoi_has_list', 0); ?>

					<!-- Alert Error | Don't have lists -->
					<div class="e-goi-notice error notice is-dismissible">
						<p><?php _e('Dont have lists in your account!', 'egoi-for-wp'); ?></p>
					</div>

					<div class="postbox postbox--custom e-goi-fcenter">
						<a type="button" class="button-primary button-primary--custom-add dropdown-toggle"> 
							<?php echo _e('Create List +', 'egoi-for-wp');?>
						</a>
						
						<div class="e-goi-account-lists--create-list">
							<form name='egoi_wp_createlist_form' method='post' action='<?php echo $_SERVER['REQUEST_URI'];?>'>
								
								<div id="e-goi-create-list" style="display: none;">
									<div class="e-goi-account-lists--create-name e-goi-fcenter">
										<span>
											<label for="egoi_wp_title"><?php echo _e('Name', 'egoi-for-wp');?></label>
										</span>
										<span>
											<input type='text' size='60' name='egoi_wp_title' autofocus required="required" />
										</span>
									</div>

									<div class="e-goi-account-lists--create-lang e-goi-fcenter">
										<label for="egoi_wp_lang"><?php echo _e('Language', 'egoi-for-wp');?></label>
										<select name='egoi_wp_lang'>
											<option value='en'><?php echo _e('English', 'egoi-for-wp');?></option>
											<option value='pt'><?php echo _e('Portuguese', 'egoi-for-wp');?></option>
											<option value='br'><?php echo _e('Portuguese (Brasil)', 'egoi-for-wp');?></option>
											<option value='es'><?php echo _e('Spanish', 'egoi-for-wp');?></option>
										</select>
										<span class="e-goi-help-text-lang">
											<span style="display:inline-block; line-height:16px; margin-left:15px;"><i><?php echo _e("The emails you send for contacts of this list will then have E-goi's header and <br>footerautomatically translated into their language", "egoi-for-wp");?>
											</i></span>
										</span>
									</div>

									<input type='submit' class='button-primary' name='egoi_wp_createlist' id='egoi_wp_createlist' value='<?php echo _e('Save', 'egoi-for-wp');?>' />
									<a style="margin-left:10px;" class='link cancel-toggle'><?php echo _e('Cancelar', 'egoi-for-wp');?></a>
								</div>
							</form>
						</div>

					</div><!-- .Postbox-custom -->
				<?php
				
				}else{
				// Display lists VALID
					if($lists->response != 'NO_USERNAME_AND_PASSWORD_AND_APIKEY'){

						update_option('egoi_has_list', 1);
						include 'egoi-for-wp-admin-lists.php';
					}
				}?>

		</div><!-- main-content -->

		<div class="e-goi-mtb20">
			<hr>
		</div>
		<div class="e-goi-delete-account">
			<p><strong><?php echo __('Remove Data', 'egoi-for-wp');?></strong></p>
				<div class="e-goi-delete-account--actions">
					<label><input type="radio" name="egoi_data[remove]" <?php checked( $opt, 1 ); ?> value="1">		<?php echo __('Yes', 'egoi-for-wp');?></label> &nbsp;
					<label><input type="radio" name="egoi_data[remove]" <?php checked( $opt, 0 ); ?> value="0">		<?php echo __('No', 'egoi-for-wp');?></label>
					<a class='button-secondary' id="egoi_remove_data">
						<?php echo __('Confirm', 'egoi-for-wp');?>
					</a> &nbsp;
					<span id="remove_valid" style="display:none;color: green;"><?php echo __('Option saved', 'egoi-for-wp');?></span>
				</div>

			<p class="help"><?php echo __("If you stop using the plugin (as a matter of fact you'll love :) be sure to remove your data before you uninstall", "egoi-for-wp");?>
			</p>
		
	</div>
</div><!-- .wrap -->
