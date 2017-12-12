<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
?>
<div class="row" style="background: #0085ba;padding: 10px;">
	<div class="egoi-map-title"><?php _e('Map Custom Fields', 'egoi-for-wp');?></div>
	<div id="error_map" class="updated error notice" style="display:none;"><?php _e('The selected fields are already mapped!', 'egoi-for-wp');?></div><div id="error_map" class="updated error notice" style="display:none;"><?php _e('The selected fields are already mapped!', 'egoi-for-wp');?></div>
	<div style="float:left;width: 40%;margin-top: 20px;">
		<div class="egoi-label-fields"><?php _e('Wordpress Fields', 'egoi-for-wp');?></div>
			<table class="table">
				<tr>
					<td>
						<select name="wp_fields" id="wp_fields" class="form-control">
							<option value=""><?php _e('Select Wordpress Field', 'egoi-for-wp');?></option>
							<optgroup label="Name">
								<option value="first_name"><?php _e('First Name', 'egoi-for-wp');?></option>
								<option value="last_name"><?php _e('Last Name', 'egoi-for-wp');?></option>
								<option value="user_login"><?php _e('Nickname', 'egoi-for-wp');?></option>
							</optgroup>
							<optgroup label="Contact">
								<option value="user_url"><?php _e('Website', 'egoi-for-wp');?></option>
							</optgroup>
							<optgroup label="About">
								<option value="description"><?php _e('Biographical Info', 'egoi-for-wp');?></option>
							</optgroup><?php
							if (class_exists('WooCommerce')) {
								echo '<optgroup label="Woocommerce">';
								foreach ($wp_fields as $key => $value) {
									echo '<option value="'.$key.'">';
									_e($value, 'egoi-for-wp');
									echo '</option>';
								}
								echo '</optgroup>';
							} ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>

	</div>

	<div style="float:right;width: 60%;margin-top: 20px;">
		<div class="egoi-label-fields"><?php _e('E-goi Fields', 'egoi-for-wp');?></div>
			<table class="table">
				<tr>
					<td>
						<select name="egoi" id="egoi" style="width: 180px;">
							<option value=""><?php _e('Select E-goi Field', 'egoi-for-wp');?></option><?php
							foreach($egoi_fields as $key => $field){ ?>
								<option value="<?php echo $key;?>"><?php echo $field;?></option><?php
							} ?>
						</select>
					</td>
					<td>
						&nbsp; <button class="button button-primary" type="button" id="save_map_fields" disabled><?php _e('Save', 'egoi-for-wp');?></button>
						&nbsp; <div id="load_map" style="display:none;"></div>
					</td>
				</tr>
			</table>
	</div>
</div>

<div style="width:100%;margin-top:10%;">
	<table class='table' style='width:100%;' id="all_fields_mapped">
		<tr>
			<th style="font-size: 16px;"><?php _e('Mapped fields', 'egoi-for-wp');?></th> 
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr><?php
		foreach ($mapped_fields as $key => $row) {
			$wc = explode('_', $row->wp); ?>
			<tr id="egoi_fields_<?php echo $row->id;?>"><?php
				if(($wc[0] == 'billing') || ($wc[0] == 'shipping')){?>
					<td style='border-bottom: 1px solid #ccc;font-size: 16px;'><?php echo $row->wp_name;?> (WooCommerce)</td><?php
				}else{ ?>
					<td style='border-bottom: 1px solid #ccc;font-size: 16px;'><?php echo $row->wp_name;?></td><?php
				} ?>
				<td style='border-bottom: 1px solid #ccc;font-size: 16px;'><?php echo $row->egoi_name;?></td>
				<td><button type='button' id='field_<?php echo $row->id;?>' class='egoi_fields button button-secondary' data-target='<?php echo $row->id;?>'>
				<span class="dashicons dashicons-trash"></span></button></td>
			</tr><?php
		}?>
	</table>
</div>

<button type="button" class="button egoi-btn-close" id="TB_closeWindowButton"><?php _e( 'Close', 'egoi-for-wp' ); ?></button>