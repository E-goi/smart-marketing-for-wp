<?php
error_reporting(~E_NOTICE);

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
	
	$egoi = new Egoi_for_WP;
	
	if(!empty($_POST)){

		// on change Apikey
		if(isset($_POST['apikey_frm']) && ($_POST['apikey_frm'])){

			update_option('egoi_api_key', $_POST['egoi_api_key']);

			update_option('egoi_client', $egoi->getClient($_POST['egoi_api_key']['api_key']));

			echo '<div class="e-goi-notice updated notice is-dismissible"><p>';
				_e('API Key updated!', 'egoi-for-wp');
			echo '</p></div>';
		}

		// on create a list
		if(isset($_POST['egoi_wp_createlist']) && (!empty($_POST['egoi_wp_title']))) {
		
			$name = $_POST['egoi_wp_title'];
			$lang = $_POST['egoi_wp_lang'];

			$egoi = new Egoi_for_WP;
			$new_list = $egoi->createList($name, $lang);
			
			if(is_string($new_list)){
				echo '<div class="e-goi-notice error notice is-dismissible"><p>';
					_e('No more lists allowed in your account!', 'egoi-for-wp');
				echo '</p></div>';
			}else{
				echo '<div class="e-goi-notice updated notice is-dismissible"><p>';
					_e('New list created successfully!', 'egoi-for-wp');
				echo '</p></div>';
			}

		}else{

			if(isset($_POST['egoi_wp_createlist']) && (empty($_POST['egoi_wp_title']))) {
				echo '<div class="e-goi-notice updated notice is-dismissible"><p>';
					_e('Empty data!', 'egoi-for-wp');
				echo '</p></div>';
			}
		}
	}

	update_option('Egoi4WpBuilderObject', $egoi);

	$opt = get_option('egoi_data');

	$lists = $egoi->getLists();
?>

<script type="text/javascript">
	function hide_show_apikey(){
		var apikey = document.getElementById('apikey');
		var span = document.getElementById('api_key_span');
		var ok = document.getElementById('save_apikey');
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

    jQuery( document ).ready(function() {

        var iframe_src = jQuery("#iframe").attr('src');
        var width = jQuery(".iframe-container").width();

        if (width < 450) {
            if (iframe_src.indexOf("type=h") >= 0) {
                var pos = iframe_src.indexOf("type=h");
                var new_iframe_src = iframe_src.substring(0, pos) + 'type=v' + iframe_src.substring(pos+6);

                jQuery("#iframe").attr('src',new_iframe_src);
                jQuery(".iframe-container").css( 'padding-top', 530 );
            }
        }

    });

    jQuery( window ).resize( function () {
        var width = jQuery(".iframe-container").width();

        var iframe_src = jQuery("#iframe").attr('src');
        if (width < 450) {
            if (iframe_src.indexOf("type=h") >= 0) {
                var pos = iframe_src.indexOf("type=h");
                var new_iframe_src = iframe_src.substring(0, pos) + 'type=v' + iframe_src.substring(pos+6);

                jQuery("#iframe").attr('src',new_iframe_src);
                jQuery(".iframe-container").css( 'padding-top', 530 );
            }
        } else {
            if (iframe_src.indexOf("type=v") >= 0) {
                var pos = iframe_src.indexOf("type=v");
                var new_iframe_src = iframe_src.substring(0, pos) + 'type=h' + iframe_src.substring(pos+6);

                jQuery("#iframe").attr('src',new_iframe_src);
                jQuery(".iframe-container").css( 'padding-top', 270 );
            }
        }

    });
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
<style type="text/css">
    .iframe-container {
        overflow: hidden;
        padding-top: 270px;
        position: relative;
    }

    .iframe-container iframe {
        border: 0;
        height: 100%;
        left: 0;
        position: absolute;
        top: 0;
        width: 100%;
    }

</style>
<body>
<div class="iframe-container">
    <iframe id="iframe" src="https://eg.e-goi.com/pluginbanners/wp-iframe.php?type=h&lang=<?php echo get_locale(); ?>" ></iframe>
</div>
<div class='wrap-content' id="wrap--acoount">
	<div class="main-content">
		<div id="icon-wp-info" class="icon32"></div>
			<div class="wrap-content--API">
	      		<?php 
		      	// If API Key exists in BD
		      	$apikey = get_option('egoi_api_key');
		      	if(isset($apikey['api_key']) && ($apikey['api_key'])) {

		      		$api_key = $apikey['api_key'];

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
			      		</div>

		      			<?php update_option('egoi_api_key', '');
		      			wp_die();

		      		}else{

		      			update_option('egoi_client', $api_client); ?>
						
						<div>
							<form name='egoi_apikey_form' method='post'>
								
								<?php
								settings_fields(Egoi_For_Wp_Admin::API_OPTION);
								settings_errors(); ?>

								<input type="hidden" name="apikey_frm" value="1">
								<div class="e-goi-account-apikey">
									<!-- Title -->
									<div class="e-goi-account-apikey--title" for="egoi_wp_apikey">
										<?php echo __('E-goi API Key');?>
									</div>

									<span id="confirm_text" style="display: none;"><?php _e('You really want to change your API Key? You will lose all data!', 'egoi-for-wp');?>
									</span>

									<!-- API key and btn -->
									<div class="e-goi-account-apikey--grp">

										<span class="e-goi-account-apikey--grp--form" size="55" maxlength="40" id="api_key_span">
											<?php echo substr($api_key, 0, 30).'**********';?>
										</span>

										<input type="text" class="e-goi-account-apikey--grp--form__input" style="display:none;" autofocus size="55" maxlength="40" id="apikey" name="egoi_api_key[api_key]" value="<?php echo $api_key;?>">
										<input type="hidden" id="old_apikey" value="<?php echo $api_key;?>">

										<span id="save_apikey" class="button-primary button-primary--custom" style="display:none;">
											<?php echo __('Save', 'egoi-for-wp');?>
										</span>

										<a type="button" id="edit" class="button button--custom" onclick="hide_show_apikey();"> 
											<?php echo __('Edit API Key', 'egoi-for-wp');?>
										</a>
										
									</div>
								</div>
								<hr>
								<div class="e-goi-account-apikey--link--account-settings">
									<a href="https://login.egoiapp.com/#/login/?action=login&from=%2F%3Faction%3Ddados_cliente&menu=sec" target="_blank" id="egoi_edit_info">
										<?php echo __('Click here if you want to change your E-goi account info', 'egoi-for-wp');?>
									</a>
									</span>
								</div>
							</form>	
						</div><?php 
					
					}

				}else{ ?>

					<!-- if apikey not exists in DB -->
					<div class="e-goi-account-apikey">
						
						<!-- Title-->
						<div class="e-goi-account-apikey--title">
							<?php echo __('Enter the API key of your E-goi account', 'egoi-for-wp');?>
						</div>

						<!-- Form-->
						<form name='egoi_apikey_form' method='post' action='<?php echo admin_url('options.php'); ?>' autocomplete="off">
							<?php settings_fields(Egoi_For_Wp_Admin::API_OPTION); settings_errors(); ?>

							<input type='text' size='55' placeholder="<?php echo __('Paste here your E-goi\'s API key', 'egoi-for-wp'); ?>" maxlength="40" class="e-goi-account-apikey--grp--form__input" name='egoi_api_key[api_key]' id="egoi_api_key_input" /> 
						
							<button type="submit" class='button-primary button-primary--custom' id="egoi_4_wp_login" disabled="disabled">
								<span id="load" class="dashicons dashicons-update" style="display: none;"></span>
								<span id="api-save-text"><?php echo __('Save', 'egoi-for-wp');?></span>
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
								  	 <span class="e-goi-tooltiptext e-goi-tooltiptext--custom" style="padding: 5px 8px;!important;"><?php _e( 'Can\'t find your API Key? We help you » <a href="https://helpdesk.e-goi.com/511369-Whats-E-gois-API-and-where-do-I-find-my-API-key" target="_blank">here!</a>', 'egoi-for-wp' ); ?>
								 	</span>
								</span>
								<span><?php echo __('To get your API key simply click the "Apps" menu in your account <span style="text-decoration:underline;"><a target="_blank" href="https://login.egoiapp.com/#/login/?menu=sec">E­-goi</span></a> and copy it.', 'egoi-for-wp');?>
							</p>
						</form>
					</div>
					
					<div class="e-goi-separator"></div>
					<div class="e-goi-account-apikey-dont-have-account">
						<p><?php echo __("Don't have an E-goi account?", "egoi-for-wp");?></p>
						<a href="https://login.egoiapp.com/#/signup" target="_blank"><?php echo __("Click here to create your account</a> (It's free and takes less than 1 minute)</p>", "egoi-for-wp"); ?>
					</div><?php

				} ?>
				</div><!-- .wrap-content-API -->

				
				<!-- LISTS -->
				<?php 
				
				// Display lists ERROR
				if (isset($lists->ERROR) && ($lists->ERROR)) {

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

					// Display valid lists
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
					<label>
						<input type="radio" name="egoi_data[remove]" <?php echo (!$opt || $opt == 0) ?: 'checked'; ?> value="1">
						<?php echo __('Yes', 'egoi-for-wp');?>
					</label> &nbsp;
					<label>
						<input type="radio" name="egoi_data[remove]" <?php echo ($opt == 1) ?: 'checked'; ?> value="0">
						<?php echo __('No', 'egoi-for-wp');?>
					</label>
					<a class='button-secondary' id="egoi_remove_data">
						<?php echo __('Confirm', 'egoi-for-wp');?>
					</a> &nbsp;
					<span id="remove_valid" style="display:none;color: green;"><?php echo __('Option saved', 'egoi-for-wp');?></span>
				</div>

			<p class="help"><?php echo __("If you stop using the plugin (as a matter of fact you'll love :) be sure to remove your data before you uninstall", "egoi-for-wp");?>
			</p>
		
	</div>
</div><!-- .wrap -->
