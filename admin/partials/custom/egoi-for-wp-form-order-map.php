<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die();
}
?>


<div class="row" style="background: #364656;">
	<div class="egoi-map-title" style="padding: 20px 32px 20px 32px;">
		<?php _e( 'Map order states', 'egoi-for-wp' ); ?>
	</div>
	<div class="egoi-map-description">
		<?php _e( 'Here you can map the order statuses from Wordpress with those accepted by E-goi. These will be the statuses that appear on the E-goi contact.', 'egoi-for-wp' ); ?>
	</div>
	<div id="error_map" style="display:none;padding: 20px 32px 0px 32px;">
		<div class="updated error notice egoi-notice-error" style="margin: 0px !important;">
			<?php _e( 'An unexpected error occurred. Please try again', 'egoi-for-wp' ); ?>
		</div>
	</div>
	<div id="success_map" style="display:none;padding: 20px 32px 0px 32px;">
		<div class="updated success notice egoi-notice-success" style="margin: 0px !important;">
			<?php _e( 'Order statuses mapped successfully', 'egoi-for-wp' ); ?>
		</div>
	</div>
	<div style="float:left;width: 100%;margin-top: 20px;background-color: #ffffff;padding: 20px 32px 0px 32px;">
		<div class="egoi-label-fields">
			<table class="table">
				<?php foreach ( $wpOrderStatus as $index => $field ) : ?>
					<tr>
						<td>
							<select name="wp_order_fields[]" id="wp_order_fields_<?= $index ?>" class="form-control" style="width: 100%;" disabled="true">
								<option value="<?php echo $field ?>"><?php echo $field ?></option>
							</select>
						</td>
						<td style="text-align: center; vertical-align: middle;">
							<i class="fa fa-arrow-right"></i>
						</td>
						<td>
							<select name="egoi_order_fields[]" id="egoi_order_fields_<?= $index ?>" class="form-control" style="width: 100%;">
								<?php foreach ( $egoiOrderStatus as $egoiField ) : ?>
									<?php 
									if (array_key_exists($field, $wpEgoiOrderMap) && $wpEgoiOrderMap[$field] == $egoiField) {
										$selected = 'selected="selected"';
									} else {
										$selected = '';
									}
									?>
									<option value="<?php echo $egoiField ?>" <?php echo $selected ?>><?php echo $egoiField ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
</div>

<div class="row egoi-order-map-actions">
	<button type="button" style="margin-bottom: 10px;" class="button smsnf-btn egoi-btn-close" id="TB_closeWindowButton"><?php _e( 'Close', 'egoi-for-wp' ); ?></button>
	<button type="button" style="margin-bottom: 10px; margin-left: 10px; background: #00aeda !important; color: #fff !important;" class="button smsnf-btn" id="save_order_map_fields"><?php _e( 'Save', 'egoi-for-wp' ); ?></button>
</div>