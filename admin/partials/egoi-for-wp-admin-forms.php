<?php 

if ( ! defined( 'ABSPATH' ) ) {
    die();
}

	$Egoi4WpBuilderObject = get_option('Egoi4WpBuilderObject');
	$form_id = $_GET['form'];

	// forms
	if(isset($_POST['action']) && ($_POST['action'])) {
		
		$post = $_POST;
		$post['egoi_form_sync']['form_content'] = htmlentities($_POST['egoi_form_sync']['form_content']);
		$egoiform = $post['egoiform'];

		update_option($egoiform, $post);

		if(isset($post['widget'])){
			$opt_upd = 'Widgets Settings';
		}else{
			$opt_upd = 'Form Settings';
		}

		echo '<div class="e-goi-notice updated notice is-dismissible"><p>';
			_e($opt_upd . ' Updated!', 'egoi-for-wp');
		echo '</p></div>';

		$Egoi4WpBuilderObject = get_option('Egoi4WpBuilderObject');
	}

	if( (isset($_GET['del']) && ($_GET['del_form'])) || (isset($_GET['del_simple_form']) && ($_GET['del_simple_form']) ) ){
		delete_option('egoi_form_sync_'.$form_id);
		echo '<div class="e-goi-notice updated notice is-dismissible"><p>';
			_e('Form deleted successfully!', 'egoi-for-wp');
		echo '</p></div>';
	}

	add_thickbox();
?>

	<!-- Header -->
	<h1 class="logo">Smart Marketing - <?php _e('Forms', 'egoi-for-wp');?></h1>
		<p class="breadcrumbs">
			<span class="prefix"><?php echo __('You are here: ', 'egoi-for-wp'); ?></span>
			<strong>Smart Marketing &rsaquo;<?php
			if(isset($_GET['form']) && ($_GET['type']) && ($_GET['form'] <= 5)){ ?>
				<a href="<?php echo admin_url('admin.php?page=egoi-4-wp-form'); ?>"><?php _e('Forms List', 'egoi-for-wp');?></a> &rsaquo;
				<span class="current-crumb"><?php _e('Form '.$form_id, 'egoi-for-wp');?></strong></span><?php
			}else{ ?>
				<span class="current-crumb"><?php _e('Forms List', 'egoi-for-wp');?></strong></span><?php
			} ?>
		</p>

	<h2 class="nav-tab-wrapper" id="egoi-tabs">
		<a class="nav-tab nav-tab-forms nav-tab-active" id="nav-tab-forms" 
		onclick="tabs('show_forms');"><?php _e('Advanced Forms', 'egoi-for-wp'); ?></a>

		<a class="nav-tab nav-tab-simple-forms" id="nav-tab-simple-forms" 
		onclick="tabs('show_simple_forms');"><?php _e('Simple Forms', 'egoi-for-wp'); ?></a>

		<a class="nav-tab nav-tab-main-bar" id="nav-tab-main-bar" 
		onclick="tabs('show_bar');"><?php _e('Subscriber Bar', 'egoi-for-wp'); ?></a>

		<a class="nav-tab nav-tab-widget" id="nav-tab-widget" 
		onclick="tabs('show_widget');"><?php _e('Widget Options', 'egoi-for-wp'); ?></a>
	</h2>
	

	<!-- wrap Forms -->
	<div class="wrap egoi4wp-settings" id="tab-forms">
		<div class="row">
		<?php
		if(isset($_GET['form']) && ($_GET['type']) && ($_GET['form'] <= 5)){
				
			/* Include shortcodes */
			include 'egoi-for-wp-admin-shortcodes.php';
			$FORM_OPTION = get_optionsform($form_id);

			$opt = get_option($FORM_OPTION); 
			?>	

			<div class="sidebar">
				<?php include ('egoi-for-wp-admin-sidebar.php'); ?>
			</div>
			
			<!-- Main Content -->
			<div id="egoi4wp-form" class="main-content col col-4 e-goi-forms-fields">
				
				<form id="e-goi-form-options" method="get" action="#">
					<input type="hidden" name="page" value="egoi-4-wp-form">
					<input type="hidden" name="form" value="<?php echo $form_id;?>">
					<p class="label_span"><?php _e('Select the Form Type you want', 'egoi-for-wp');?></p>
					
					<select class="e-goi-option-select-admin-forms" name="type" id="form_choice">
						<option value="" disabled selected>
							<?php _e('Selected the form', 'egoi-for-wp');?>	
						</option>
						<option value="popup" <?php selected($_GET['type'], 'popup');?>>
							<?php _e('E-goi Popup', 'egoi-for-wp');?>
						</option>
						<option value="html" <?php selected($_GET['type'], 'html');?>>
							<?php _e('E-goi Advanced HTML', 'egoi-for-wp');?>
						</option>
						<option value="iframe" <?php selected($_GET['type'], 'iframe');?>><?php _e('E-goi Iframe', 'egoi-for-wp');?>
						</option>
					</select>
					<input type="hidden" id="type_frm_saved" value="<?php echo $_GET['type'];?>">

					<span id="load_frm_change" class="dashicons dashicons-update" style="display: none;"></span>
				</form>

				<!-- FORM E-GOI -->
				<div id="egoi4wp-form-hide" class="nav-tab-forms-options-mt">
					<a class="nav-tab-forms-options nav-tab-active" id="nav-tab-forms-options" onclick="tabs('show_options');">
						<?php _e('Opções | ', 'egoi-for-wp');?>
					</a>
					<a class="nav-tab-forms-options--appearance" id="nav-tab-forms-appearance" onclick="tabs('show_appearance');">
						<?php _e('Customizing the form', 'egoi-for-wp');?>
					</a>

					<form method="post" action="#" id="form-egoi">	
						
						<span class="cd-popup-trigger-change"></span>

						<!-- PopUp ALERT Change Form -->
						<div class="cd-popup cd-popup-change-form" id="change-form" role="alert">
							<div class="cd-popup-container">
								<p><b><?php echo __('Attention! If you change your form you will lose the settings.', 'egoi-for-wp');?></b></p>
								<ul class="cd-buttons">
									<li>
										<a id="change_form_req" href="#">Confirmar</a>
									</li>
									<li>
										<a class="cd-popup-close-btn" id="close_frm_change" href="#0">Cancelar</a>
									</li>
								</ul>
							</div> <!-- cd-popup-container -->
						</div> <!-- PopUp ALERT Change Form -->

						<div id="tab-forms-options">
							<?php settings_fields($FORM_OPTION);?>

							<div class="e-goi-form-title">
								<p style="font-size:18px; line-height:16px;"><?php _e('Form title', 'egoi-for-wp'); ?></p>
								<!-- Title - Options -->
								<div class="e-goi-form-title--left">
									<span><?php _e( 'Show Title', 'egoi-for-wp' ); ?></span>
									<input type="radio" name="egoi_form_sync[show_title]" value="1" <?php checked($opt['egoi_form_sync']['show_title'], 1); ?> />
										<?php _e( 'Yes', 'egoi-for-wp' ); ?>
									<input type="radio" name="egoi_form_sync[show_title]" value="0" <?php checked($opt['egoi_form_sync']['show_title'], 0); ?> />
									<?php _e( 'No', 'egoi-for-wp' ); ?>
								</div>
							</div> <!-- .e-goi-form-title -->

							<input type="hidden" name="egoi_form_sync[egoi]" value="<?php echo $_GET['type'];?>">
							<div id="titlediv" class="small-margin">
								<div id="titlewrap">
									<label class="screen-reader-text" for="title"><?php _e('Form Title', 'egoi-for-wp'); ?></label>
									<input type="hidden" name="egoi_form_sync[form_id]" value="<?php echo $form_id;?>">

									<input class="e-goi-form-title--input" type="text" name="egoi_form_sync[form_name]" size="30" value="<?php echo $opt['egoi_form_sync']['form_name'];?>" id="title" spellcheck="true" autocomplete="off" placeholder="<?php echo __( "Write here the title of your form", 'egoi-for-wp' ); ?>" required pattern="\S.*\S">

									<input id="shortcode" type="hidden" name="egoiform" value="<?php echo 'egoi_form_sync_'.$form_id;?>">
								</div>
							</div>
							
							<!-- Content -->				
							<div>
								<a href="#TB_inline?width=700&height=450&inlineId=egoi_form_inter&modal=true" 
									id="form_egoint" class="thickbox button-secondary" style="display:none;">
								</a>

								<div>
									<?php
									
									if (($_GET['type'] == 'popup') || ($_GET['type'] == 'html')){
										$content = stripslashes($opt['egoi_form_sync']['form_content']);

										if ($opt['egoi_form_sync']['egoi'] == $_GET['type']) {?>
											<!-- Textarea -->
											<textarea class="e-goi-form-title--text-area" name="egoi_form_sync[form_content]">
												<?php echo $content;?>	
											</textarea>
										<?php

										} else { ?>
											
											<span id="session_form">1</span>

											<!-- Header Textarea -->
											<div class="e-goi-header-textarea">
												<!-- Titulo -->
												<?php if($_GET['type'] == 'html'){ ?>
												
													<p><?php _e('Advanced HTML code', 'egoi-for-wp'); ?></p>
												
												<?php } else{ ?>

													<p><?php _e('Código da janela Pop-up', 'egoi-for-wp'); ?></p>
												
												<?php } ?>

												<!-- link -->
												<div>
													<a target="_blank" href="<?php _e( 'https://helpdesk.e-goi.com/838402-Integrating-an-E-goi-form-with-an-external-system-via-HTML', 'egoi-for-wp' ); ?>"><?php _e( 'Copiar código HTML no E-goi', 'egoi-for-wp' ); ?>
													<span class="dashicons dashicons-external"></span></a>
												</div>
												<!-- link Video -->
												<div class="e-goi-help-link-video">
													<a class="" target="_blank" href="<?php _e( 'https://helpdesk.e-goi.com/603920-Adding-a-sign-up-form-to-my-website', 'egoi-for-wp' ); ?>">
														<span class="dashicons dashicons-controls-play"></span>
														<?php _e( 'Veja aqui como fazer', 'egoi-for-wp' ); ?>
													</a>
												</div>
											</div>

											<!-- textarea for Advanced HTML -->
											<?php if($_GET['type'] == 'html'){ ?>
												<textarea class="e-goi-header-textarea--html-adv" placeholder="<?php _e( 'Paste here the Advanced HTML code of your E-goi form', 'egoi-for-wp' ); ?>" name="egoi_form_sync[form_content]"></textarea>
											<?php } else{ ?>

											<!-- textarea for Pop-up -->
												<textarea class="e-goi-header-textarea--html-popup" placeholder="<?php _e( 'Paste here the Pop-Up window code of your E-goi form', 'egoi-for-wp' ); ?>" name="egoi_form_sync[form_content]"></textarea>
											<?php } ?>

											<?php
										}

									} else if($_GET['type'] == 'iframe') { ?>
												
										<span id="session_form">1</span>
											
										<div class="e-goi-iframe-select-list e-goi-fcenter">
											<!-- AREA Iframe - List to subscribe -->
											<span class="e-goi-iframe-select-list--title"><?php _e( 'List to Subscribe', 'egoi-for-wp' ); ?></span>
												
											<span class="e-goi-lists_not_found" style="display: none;">
												<?php printf(__('Lists not found, <a href="%s">are you connected to egoi</a>?', 'egoi-for-wp'), admin_url('admin.php?page=egoi-4-wp-account')); ?>
											</span>

											<!-- Tooltip - help -->
											<div class="e-goi-tooltip">
												 <span class="dashicons dashicons-editor-help"></span>
											  	 <span class="e-goi-tooltiptext">
											  	 	<span><?php _e( 'The selected mailing list will receive the contacts subscribed into the form.', 'egoi-for-wp' ); ?></span>
											 	</span>
											</div>
											<span style="margin-left:140px;">
												
												<span id="e-goi-lists_ct_forms" hidden><?php echo $opt['egoi_form_sync']['list'];?></span> 

												<span class="loading_lists dashicons dashicons-update" style="display: none;"></span>
												<select name="egoi_form_sync[list]" id="e-goi-list-frm" style="display: none;">
													<option value="" selected disabled>
														<?php _e( 'Select List', 'egoi-for-wp' ); ?>
													</option>
												</select>
												<span id="load_forms" class="dashicons dashicons-update" style="display: none;"></span>
											</span>
										</div><!-- .e-goi-iframe-select-list -->
											
										<div class="e-goi-iframe-select-form" id="egoi_form_wp" style="margin-bottom:15px;">
											<span style="font-size: 16px;">
												<?php _e( 'E-goi Form to Subscribe', 'egoi-for-wp' ); ?></span>
											<div class="e-goi-tooltip">
												<span class="dashicons dashicons-editor-help"></span>
											  	<span class="e-goi-tooltiptext e-goi-tooltiptext--subscribe"><?php _e( 'Need a iframe form? Simply select a form (which already exists in E-goi) and copy the shortcode to display this form on your website or blog', 'egoi-for-wp' ); ?></span>
											</div>
											<span style="margin-left:26px;">
												<span id="e-goi-forms" hidden><?php echo $opt['egoi_form_sync']['form_content'];?></span> 
												<select name="egoi_form_sync[form_content]" id="formid_egoi" style="display: none;">
													<?php
													if ($listID) { ?>
														<option value=""><?php _e('Select your form', 'egoi-for-wp');?></option><?php
														foreach ($forms as $value) {
															if($value->title){ ?>
																<option value="<?php echo $value->id.' - '.$value->url;?>" <?php selected($value->id.' - '.$value->url, $opt['egoi_form_sync']['form_content']);?>>
																	<?php echo $value->title;?>
																</option><?php
															}
														}
													} ?>
												</select>
											</span>
										</div>

										<span class="e-goi-iframe-select-form" id="empty_forms" style="display: none;">
											<span class="no-forms">
												<span class="dashicons dashicons-warning"></span>
												<span style="font-size:20px; "><?php _e('There are no forms on E-goi. Click here to learn how to create.', 'egoi-for-wp'); ?></span>
											</span>
										</span>

										<?php

											if($opt['egoi_form_sync']['form_content']){
												$url = explode(' - ', $opt['egoi_form_sync']['form_content']); ?>
												<div id="egoi_form_inter" style="display:none;">
													<a id="TB_closeWindowButton">X</a>
										    		<iframe src="http://<?php echo $url[1];?>" width="700" height="600" style="border: 0 none;" onload="window.parent.parent.scrollTo(0,0);"></iframe>
										    	</div>
										    <?php
										    
									    }else{ ?>
											<div id="egoi_form_inter" style="display:none;"></div><?php 
										} 
									} ?>

									</div>
							</div>

							<div style="margin-bottom:20px;">
								<!-- Shortcode Title-->
								<p class="e-goi-form-shortcode--title"><?php _e('Shortcode', 'egoi-for-wp');?></p>
								<!-- Shortcode print -->
								<div class="e-goi-form-shortcode">
									<a class="e-goi-form-shortcode--input e-goi-tooltip" data-title-before="<?php _e('Copy', 'egoi-for-wp');?>" data-title-after="Copied" id="e-goi_shortcode" data-clipboard-text="<?php echo '[egoi_form_sync_'.$form_id.']';?>"><?php echo '[egoi_form_sync_'.$form_id.']';?></a>
									<span class="egoi4wp-form-usage e-goi-help-shortcode-text">
										<?php _e('Use this shortcode to display this form inside of your site or blog', 'egoi-for-wp');?>
									</span>
								</div>
							</div>

							<table class="form-table" style="table-layout: fixed;">
								<tr valign="top">
									<th scope="row" class="row--custom-active"><?php _e( 'Enable Form', 'egoi-for-wp' ); ?></th>
									<td class="nowrap nowrap--custom">
										<label>
											<input type="radio" name="egoi_form_sync[enabled]" value="1" <?php checked($opt['egoi_form_sync']['enabled'], 1); ?> />
											<?php _e( 'Yes', 'egoi-for-wp' ); ?>
										</label> &nbsp;
										<label>
											<input type="radio" name="egoi_form_sync[enabled]" value="0" <?php checked($opt['egoi_form_sync']['enabled'], false); ?> />
											<?php _e( 'No', 'egoi-for-wp' ); ?>
										</label>
										<p class="help">
											<?php _e( 'Select "yes" to enable this form.', 'egoi-for-wp' ); ?>
										</p>
									</td>
								</tr>
							</table>
						</div>

						<div class="tab" id="tab-forms-appearance">
							<?php include ('custom/egoi-for-wp-form-appearance.php'); ?>
						</div>

						<div style="display: -webkit-inline-box; margin-bottom: 30px;">
							<button style="margin-top: 12px;" type="submit" class="button button-primary"><?php _e('Save Changes', 'egoi-for-wp');?></button>
						</div>
					</form>
				</div>
			</div>
			
			<?php
		}else{ ?>

			<a href="#TB_inline?width=0&height=450&inlineId=egoi-for-wp-form-choice&modal=true" id="form_type" class="thickbox button-secondary" style="display:none;"></a>
			
			<!-- List -->			
			<div class="main-content col col-4" style="margin:0 0 20px;">
				<div style="font-size:14px; margin:10px 0;">
					<?php echo __('Max number of forms:', 'egoi-for-wp');?> <span id="rcv_e-goi_forms"></span>/5
				</div>

				<table border='0' class="widefat striped">
				<thead>
					<tr>
						<th><?php _e('Shortcode', 'egoi-for-wp');?></th>
						<th><?php _e('Form ID', 'egoi-for-wp');?></th>
						<th><?php _e('Title', 'egoi-for-wp');?></th>
						<th><?php _e('State', 'egoi-for-wp');?></th>
						<th><?php _e('', 'egoi-for-wp');?></th>
						<th><?php _e('', 'egoi-for-wp');?></th>
					</tr>
				</thead><?php

				$form_exists = '';
				for ($j=1; $j<=5; $j++){

					$form = get_option('egoi_form_sync_'.$j);
					if($form['egoi_form_sync']['form_id']){
						$form_name = $form['egoi_form_sync']['form_name'];?>

						<!-- PopUp ALERT Delete Form -->
							<div class="cd-popup cd-popup-del-form" data-id-form="<?=$j?>" data-type-form="form" role="alert">
								<div class="cd-popup-container">
									<p><b><?php echo __('Are you sure you want to delete this form?</b> This action will remove only the form in your plugin (will be kept in E-goi).', 'egoi-for-wp');?> </p>
									<ul class="cd-buttons">
										<li>
											<a href="<?php echo $_SERVER['REQUEST_URI'];?>&form=<?php echo $j;?>&del=1&del_form=027c8q921">Confirmar</a>
										</li>
										<li>
											<a class="cd-popup-close-btn" href="#0">Cancelar</a>
										</li>
									</ul>
								</div> <!-- cd-popup-container -->
							</div> <!-- PopUp ALERT Delete Form -->

						<tr>
							<!-- Shortcode -->
							<td><span style="padding:6px 12px; background-color: #ffffff; border: 1px solid #ccc;"><?php echo "[egoi_form_sync_$j]";?></span></td>
							<!-- ID -->
							<td><?php echo $j;?></td>
							<!-- Title -->
							<td><?php echo $form_name;?></td>
							<!-- State -->
							<td>
							<?php ($form['egoi_form_sync']['enabled']) ? _e('<span class="e-goi-form-active-label">Active</span>', 'egoi-for-wp') : _e('<span class="e-goi-form-inactive-label">Inactive</span>', 'egoi-for-wp');?>
							</td>
							<td>
								<a class="cd-popup-trigger-del" data-id-form="<?=$j?>" data-type-form="form" href="#"><?php _e('Delete', 'egoi-for-wp');?></a>
							</td>
							<!-- Option -->
							<td style="text-align:right;">
								<a title="<?php _e('Edit', 'egoi-for-wp');?>" href="<?php echo $_SERVER['REQUEST_URI'];?>&form=<?php echo $j;?>&type=<?php echo $form['egoi_form_sync']['egoi'];?>"><span class="dashicons dashicons-edit"></span></a> 
							</td>
						</tr>
						<?php
						$form_exists .= $form['egoi_form_sync']['form_id'].' - ';
					}
				}	

				$count_op = count(array_filter(explode(' - ', $form_exists)));
				echo "<span id='ct_e-goi_forms' style='display:none;'>".$count_op."</span>";
				if($count_op == 0){
					echo "<td colspan='3'>";
						_e('Subscriber Forms are empty', 'egoi-for-wp');
					echo "</td>";
				} ?>
				</table>

				<p><?php

				if($count_op >= 5){ ?>
					<a id="disabled" class='button-primary'><?php _e('Create form +', 'egoi-for-wp');?></a><?php
				}else{ ?>
					<a href="<?php echo $_SERVER['REQUEST_URI'];?>&form=<?php echo ($count_op+1);?>&type=form" class='button-primary'><?php _e('Create form +', 'egoi-for-wp');?></a><?php
				} ?>
				</p>
			</div>

            <!-- Banner -->
            <div class="sidebar" style="width: 220px;">
                <?php include ('egoi-for-wp-admin-banner.php'); ?>
            </div>

			<?php
				
		} ?>
		</div>
	</div>

	<!-- wrap Simple Forms -->
	<div class="wrap tab" id="tab-simple-forms">
		<?php include ('egoi-for-wp-admin-simple-forms.php'); ?>
	</div>

	<!-- wrap Subscriber Bar -->
	<div class="wrap tab" id="tab-main-bar">
		<?php include ('egoi-for-wp-admin-bar.php'); ?>
	</div>

	<!-- wrap Widget Options -->
	<div class="wrap tab" id="tab-widget">
		<?php include ('egoi-for-wp-admin-widget.php'); ?>
	</div>

	<?php 
		if ( (isset($_GET['type']) && $_GET['type'] == 'simple_form') ||
		(isset($_GET['simple_form'])) ) { 
		?>
		<script type="text/javascript">
		
			jQuery('#tab-simple-forms').show();
			jQuery('#tab-forms').hide();
			jQuery('#tab-main-bar').hide();
			jQuery('#tab-widget').hide();

			var option = "nav-tab ";

			jQuery('#nav-tab-forms').attr('class', option + 'nav-tab-forms');
			jQuery('#nav-tab-main-bar').attr('class', option + 'nav-tab-main-bar');
			jQuery('#nav-tab-widget').attr('class', option + 'nav-tab-widget');
			jQuery('#nav-tab-simple-forms').addClass('nav-tab-active');

		</script>
	<?php } ?>