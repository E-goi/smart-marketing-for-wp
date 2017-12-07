<?php

if ( ! defined( 'ABSPATH' ) ) {
    die();
}

$width = $opt['egoi_form_sync']['width'];
$height = $opt['egoi_form_sync']['height'];

?>
<table class="form-table egoi4wp-form-content">

	<tr valign="top">
		<th scope="row">
			<label>
				<?php _e('Box Border', 'egoi-for-wp'); ?>
			</label>
		</th>
		<td>
			<input type="text" name="egoi_form_sync[border]" value="<?php echo $opt['egoi_form_sync']['border'];?>" maxlength="4" size="10">
			<p class="help"><?php _e( 'The box border width', 'egoi-for-wp' ); ?></p>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row">
			<label>
				<?php _e('Box Border Color', 'egoi-for-wp'); ?>
			</label>
		</th>
		<td>
			<input type="text" name="egoi_form_sync[border_color]" value="<?php echo $opt['egoi_form_sync']['border_color'];?>" class="color">
			<p class="help"><?php _e( 'The border color outside the form.', 'egoi-for-wp' ); ?></p>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row">
			<label>
				<?php _e('Box Width', 'egoi-for-wp'); ?>
			</label>
		</th>
		<td>
			<input type="text" name="egoi_form_sync[width]" value="<?php echo $width ? $width : '700px';?>" maxlength="5" size="10">
			<p class="help"><?php _e( 'The width of outside box form.', 'egoi-for-wp' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label>
				<?php _e('Box Height', 'egoi-for-wp'); ?>
			</label>
		</th>
		<td>
			<input type="text" name="egoi_form_sync[height]" value="<?php echo $height ? $height : '600px';?>" maxlength="5" size="10">
			<p class="help"><?php _e( 'The height of outside box form.', 'egoi-for-wp' ); ?></p>
		</td>
	</tr>
	
</table>
