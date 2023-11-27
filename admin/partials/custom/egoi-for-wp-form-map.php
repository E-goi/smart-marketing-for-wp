<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( empty( $wp_fields ) ) {
	$wp_fields = array();}

$wp_fields_billing = array_filter(
	$wp_fields,
	function ( $field ) {
		return strpos( $field, 'billing' ) !== false;
	},
	ARRAY_FILTER_USE_KEY
);

$wp_fields_shipping = array_filter(
	$wp_fields,
	function ( $field ) {
		return strpos( $field, 'shipping' ) !== false;
	},
	ARRAY_FILTER_USE_KEY
);
?>
<div class="row" style="background: #364656;padding: 20px;">
	<div class="egoi-map-title"><?php _e( 'Map Custom Fields', 'egoi-for-wp' ); ?></div>
	<div id="error_map" class="updated error notice" style="display:none;margin: 0px !important;"><?php _e( 'The selected fields are already mapped!', 'egoi-for-wp' ); ?></div><div id="error_map" class="updated error notice" style="display:none;margin: 0px !important;"><?php _e( 'The selected fields are already mapped!', 'egoi-for-wp' ); ?></div>
	<div style="float:left;width: 40%;margin-top: 40px;">
		<div class="egoi-label-fields"><?php _e( 'WordPress Fields', 'egoi-for-wp' ); ?>
			<table class="table">
				<tr>
					<td>
						<select name="wp_fields" id="wp_fields" class="form-control" style="width: 100%;">
							<option value=""><?php _e( 'Select WordPress Field', 'egoi-for-wp' ); ?></option>
							<optgroup label="Name">
								<option value="first_name"><?php _e( 'First Name', 'egoi-for-wp' ); ?></option>
								<option value="last_name"><?php _e( 'Last Name', 'egoi-for-wp' ); ?></option>
								<option value="user_login"><?php _e( 'Nickname', 'egoi-for-wp' ); ?></option>
							</optgroup>
							<optgroup label="Contact">
								<option value="user_url"><?php _e( 'Website', 'egoi-for-wp' ); ?></option>
							</optgroup>
							<optgroup label="About">
								<option value="description"><?php _e( 'Biographical Info', 'egoi-for-wp' ); ?></option>
							</optgroup>
							<?php
							if ( class_exists( 'WooCommerce' ) ) {
								if ( ! empty( $wp_fields_billing ) ) {
									echo '<optgroup label="Woocommerce Billing">';
									foreach ( $wp_fields_billing as $key => $value ) {
										echo '<option value="' . $key . '">';
										_e( $value, 'egoi-for-wp' );
										echo '</option>';
									}
									echo '</optgroup>';
								}
								if ( ! empty( $wp_fields_shipping ) ) {
									echo '<optgroup label="Woocommerce Shipping">';
									foreach ( $wp_fields_shipping as $key => $value ) {
										echo '<option value="' . $key . '">';
										_e( $value, 'egoi-for-wp' );
										echo '</option>';
									}
									echo '</optgroup>';
								}
							}
							?>
						</select>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div style="float:right;width: 60%;margin-top: 40px;padding-left: 30px;">
		<div class="egoi-label-fields"><?php _e( 'E-goi Fields', 'egoi-for-wp' ); ?>
			<table class="table">
				<tr>
					<td>
						<select name="egoi" id="egoi" style="width: 180px;">
							<option value=""><?php _e( 'Select E-goi Field', 'egoi-for-wp' ); ?></option>
																			<?php
																			foreach ( $egoi_fields as $key => $field ) {
																				?>
								<option value="<?php echo $key; ?>"><?php echo $field; ?></option>
																				<?php
																			}
																			?>
						</select>
					</td>
					<td>
						&nbsp; <button class="button button-primary" type="button" id="save_map_fields" style="background: #00aeda !important;" disabled><?php _e( 'Save', 'egoi-for-wp' ); ?></button>
						&nbsp; <div id="load_map" style="display:none;"></div>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>

<div style="width:100%;padding: 20px;display: flex;flex-direction: column;">
	<span class="egoi-label-fields" style="font-size: 16px;margin: 20px 0px 10px;"><?php _e( 'Mapped fields', 'egoi-for-wp' ); ?></span>
	<table class='table' style='width:100%;' id="all_fields_mapped">
		<tbody>
		<?php
		foreach ( $mapped_fields as $key => $row ) {
			$wc = explode( '_', $row->wp );
			?>
			<tr id="egoi_fields_<?php echo $row->id; ?>">
										   <?php
											if ( ( $wc[0] == 'billing' ) || ( $wc[0] == 'shipping' ) ) {
												?>
					<td style='border-bottom: 1px solid #ccc;font-size: 16px;'><?php echo $row->wp_name; ?> (WooCommerce)</td>
																						  <?php
											} else {
												?>
					<td style='border-bottom: 1px solid #ccc;font-size: 16px;'><?php echo $row->wp_name; ?></td>
																						  <?php
											}
											?>
				<td style='border-bottom: 1px solid #ccc;font-size: 16px;'><?php echo $row->egoi_name; ?></td>
				<td class="egoi-content-center" style="border-bottom: 1px solid #ccc;font-size: 16px;">
					<button type='button' id='field_<?php echo $row->id; ?>' class='egoi_fields button button-secondary' data-target='<?php echo $row->id; ?>'>
						<span class="dashicons dashicons-trash"></span>
					</button>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
</div>

<button type="button" style="margin-bottom: 10px;" class="button smsnf-btn egoi-btn-close " id="TB_closeWindowButton"><?php _e( 'Close', 'egoi-for-wp' ); ?></button>
