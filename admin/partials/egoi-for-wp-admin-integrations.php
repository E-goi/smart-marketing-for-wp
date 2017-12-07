<?php
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

$Egoi4WpBuilderObject = get_option('Egoi4WpBuilderObject');

if(isset($_POST['action'])){
	$egoiform = $_POST['egoiform'];
	$post = $_POST;
	
	update_option($egoiform, $post);
	
	echo '<div class="updated notice is-dismissible"><p>';
		_e('Integrations Settings Updated!', 'egoi-for-wp');
	echo '</p></div>';
}

$lists = $Egoi4WpBuilderObject->getLists();

$opt = get_option('egoi_int');
$egoint = $opt['egoi_int'];

if(!$egoint['enable_pc']){
	$egoint['enable_pc'] = 0;
}

if(!$egoint['enable_cf']){
	$egoint['enable_cf'] = 0;
}

$contact_forms = $Egoi4WpBuilderObject->getContactFormInfo();

?>
<style type="text/css">
.form-table th{
    padding: 20px 10px 20px 10px !important;
}
</style>

<h1 class="logo">Smart Marketing - <?php _e('Integrations', 'egoi-for-wp');?></h1>
	<p class="breadcrumbs">
		<span class="prefix"><?php echo __('You are here: ', 'egoi-for-wp'); ?></span>
		<strong>Smart Marketing</a> &rsaquo;
		<span class="current-crumb"><?php _e('Integrations Settings', 'egoi-for-wp');?></strong></span>
	</p>
	
	<div class="sidebar">
		<?php include ('egoi-for-wp-admin-sidebar.php'); ?>
	</div>
	<div id="egoi4wp-widget">
		<form method="post" action=""><?php
			settings_fields($FORM_OPTION);?>
			
			<input type="hidden" name="egoiform" value="egoi_int">
			<table class="form-table" style="table-layout: fixed;width: 60%;"><?php
				if(class_exists('WPCF7')){ ?>

					<tr valign="top" style="border:1px solid #ccc;background:#fff;">
						<th scope="row"><?php _e( 'Enable Contact Form 7 Integration', 'egoi-for-wp' ); ?></th>
						<td class="nowrap">
							<label>
								<input type="radio" name="egoi_int[enable_cf]" value="1" <?php checked($egoint['enable_cf'], 1); ?> />
								<?php _e( 'Yes', 'egoi-for-wp' ); ?>
							</label> &nbsp;
							<label>
								<input type="radio" name="egoi_int[enable_cf]" value="0" <?php checked($egoint['enable_cf'], 0); ?> />
								<?php _e( 'No', 'egoi-for-wp' ); ?>
							</label>
							<p class="help">
								<?php _e( 'Select "yes" to enable Contact From 7 Integration.', 'egoi-for-wp' ); ?>
							</p>
						</td>
					</tr><?php
					if($egoint['enable_cf']){ ?>
						
						<tr valign="top">
							<th scope="row"><?php _e( 'Contact Form Name', 'egoi-for-wp' ); ?></th>
							<?php
							if(empty($contact_forms)) { ?>
								<td><b><?php _e('Cannot locate any forms from Contact Form 7', 'egoi-for-wp');?></b></td><?php
							}else{ ?>
								<td>
									<select style="width: 220px;" name="contact_form[]" id="egoi4wp-forms" multiple="multiple"><?php
										foreach($contact_forms as $key => $form) { ?>
											<option value="<?php echo esc_attr($form->ID);?>" <?php 
											if(is_array($opt['contact_form'])){
												selected(in_array($form->ID, $opt['contact_form']));
											}
											echo '>'.esc_html($form->post_title);?></option><?php
										} ?>
									</select>
									<p class="help"><?php _e( 'Select the contact form that you want to be listened (To select multiple forms hold the CTRL (CMD in OS-X) button on click).' ,'egoi-for-wp' ); ?></p>
								</td><?php 
							} ?>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'List to Subscribe', 'egoi-for-wp' ); ?></th>
							<?php
							if(empty($lists)) { ?>
								<td><?php printf(__('Lists not found, <a href="%s">are you connected to egoi</a>?', 'egoi-for-wp'), admin_url('admin.php?page=egoi-4-wp-account'));?></td><?php
							}else{ ?>
								<td>
									<select name="egoi_int[list_cf]" id="egoi4wp-lists"><?php
										$index = 1;
										foreach($lists as $list) {
											if($list->title!=''){ ?>
												<option value="<?php echo esc_attr($list->listnum);?>" <?php selected($list->listnum, $egoint['list_cf']);?>><?php echo esc_html($list->title);?></option><?php
											}
											$index++;
										} ?>
									</select>
									<p class="help"><?php _e( 'Select the list to which who submit in Contact Form 7 should be subscribed.' ,'egoi-for-wp' ); ?></p>
								</td><?php 
							} ?>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e( 'Update Subscriber', 'egoi-for-wp' ); ?></th>
							<td class="nowrap">
								<label>
									<input type="radio" name="egoi_int[edit]" value="1" <?php checked($egoint['edit'], 1); ?> />
									<?php _e( 'Yes', 'egoi-for-wp' ); ?>
								</label> &nbsp;
								<label>
									<input type="radio" name="egoi_int[edit]" value="0" <?php checked($egoint['edit'], 0); ?> />
									<?php _e( 'No', 'egoi-for-wp' ); ?>
								</label>
								<p class="help">
									<?php _e( 'Select "yes" to edit the subscriber if already exists in E-goi List.', 'egoi-for-wp' ); ?>
								</p>
							</td>
						</tr><?php
					}

				}else{ ?>

					<tr valign="top" style="border:1px solid #ccc;background:#fff;">
						<th scope="row"><?php _e( 'Enable Contact Form 7 Integration', 'egoi-for-wp' ); ?></th>
						<th scope="nowrap"><?php _e( 'This integration is not possible because the CF7 plugin is not installed.', 'egoi-for-wp' ); ?></th>
					</tr><?php

				} ?>
				<tr>
					<td colspan="2"><hr style="width:65%;float:left;" /></td>
				</tr>

				<tr valign="top" style="border:1px solid #ccc;background:#fff;">
					<th scope="row"><?php _e( 'Enable Post Comment Integration', 'egoi-for-wp' ); ?></th>
					<td class="nowrap">
						<label>
							<input type="radio" name="egoi_int[enable_pc]" value="1" <?php checked($egoint['enable_pc'], 1); ?> />
							<?php _e( 'Yes', 'egoi-for-wp' ); ?>
						</label> &nbsp;
						<label>
							<input type="radio" name="egoi_int[enable_pc]" value="0" <?php checked($egoint['enable_pc'], 0); ?> />
							<?php _e( 'No', 'egoi-for-wp' ); ?>
						</label>
						<p class="help">
							<?php _e( 'Select "yes" to enable Post Comment Integration.', 'egoi-for-wp' ); ?>
						</p>
					</td>
				</tr><?php
				if($egoint['enable_pc']){ ?>
					<tr valign="top">
						<th scope="row"><?php _e( 'List to Subscribe', 'egoi-for-wp' ); ?></th>
						<?php
						if(empty($lists)) { ?>
							<td colspan="2"><?php printf(__('Lists not found, <a href="%s">are you connected to egoi</a>?', 'egoi-for-wp'), admin_url('admin.php?page=egoi-4-wp-account'));?></td><?php
						}else{ ?>
							<td>
								<select name="egoi_int[list_cp]" id="egoi4wp-lists"><?php
									$index = 1;
									foreach($lists as $list) {
										if($list->title!=''){ ?>
											<option value="<?php echo esc_attr($list->listnum);?>" <?php selected($list->listnum, $egoint['list_cp']);?>><?php echo esc_html($list->title);?></option><?php
										}
										$index++;
									} ?>
								</select>
								<p class="help"><?php _e( 'Select the list to which who submit in Post Comment should be subscribed.' ,'egoi-for-wp' ); ?></p>
							</td><?php 
						} ?>
					</tr><?php
				} ?>
				
				<tr>
					<td colspan="2"><hr style="width:65%;float:left;" /></td>
				</tr>

				<tr valign="top">
					<td colspan="2">
						<div style="display: -webkit-inline-box;">
							<button style="margin-top: 12px;" type="submit" class="button button-primary"><?php _e('Save Changes', 'egoi-for-wp');?></button>
						</div>
					</td>
				</tr>
			</table>
		</form>
	</div>
