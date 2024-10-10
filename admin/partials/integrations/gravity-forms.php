<?php
$contact_forms = Egoi_For_Wp::getGravityFormsInfoAll();
$mapped        = Egoi_For_Wp::getGravityFormsInfo();
?>
<form method="post" action="" id="egoi_form_mappable">
	<?php settings_fields( 4 ); ?>
	<input type="hidden" name="egoiform" value="egoi_int">
	<input type="hidden" id="egoi_map_to_save" name="egoi_map_to_save" value="">
	<div class="smsnf-grid">
		<div>
			<?php if ( ! class_exists( 'GFAPI' ) ) { ?>
			<div class="container" style="display: flex;justify-content: center;align-items: center;flex-direction: column;min-height: 100%;">
				<div>
					<h2><?php _e( 'Plugin missing', 'egoi-for-wp' ); ?></h2>
					<h4><?php _e( 'Make sure you have Gravity Forms installed before trying to configure the integration.', 'egoi-for-wp' ); ?></h4>
				</div>
			<div>
			<?php } else { ?>

				<div class="smsnf-input-group">
					<label for="egoi_int"><?php _e( 'Enable Gravity Forms Integration', 'egoi-for-wp' ); ?></label>
					<p class="subtitle"><?php _e( 'Select "yes" to enable Gravity Form Integration.', 'egoi-for-wp' ); ?></p>
					<div class="smsnf-wrapper" style="display: flex;align-items: flex-end;margin-top: 12px;">
						<label><input type="radio"  name="egoi_int[enable_gf]" <?php if(isset($egoint['enable_gf'])) {checked( $egoint['enable_gf'], 1 ); } ?> value="1"><?php _e( 'Yes', 'egoi-for-wp' ); ?></label> &nbsp;
						<label><input type="radio" name="egoi_int[enable_gf]" <?php if(isset($egoint['enable_gf'])) {checked( $egoint['enable_gf'], 0 ); } ?> value="0"><?php _e( 'No', 'egoi-for-wp' ); ?></label>
					</div>
				</div>

				<?php if ( isset($egoint['enable_gf'])  ) { ?>

					<div class="smsnf-input-group">
						<label for="egoi_map_trigger"><?php _e( 'Contact Form Name', 'egoi-for-wp' ); ?></label>
						<p class="subtitle"><?php _e( 'Select the form you want to sync.', 'egoi-for-wp' ); ?></p>
						<div class="smsnf-wrapper">
							<?php if ( empty( $contact_forms ) ) { ?>
								<span><?php _e( 'Cannot locate any forms from Gravity Forms', 'egoi-for-wp' ); ?></span>
							<?php } else { ?>
								<select id="egoi_map_trigger" name="gravity_form" class="form-select" >
									<option value="0"><?php echo __( 'Choose form to configure', 'egoi-for-wp' ); ?></option>
									<?php
									foreach ( $contact_forms as $key => $form ) {
										?>
										<option value="<?php echo esc_attr( $form['id'] ); ?>">
										<?php echo ( key_exists( $form['id'], $mapped ) ? '* ' : '' ) . esc_html( $form['title'] ); ?>
										</option>
									<?php } ?>
								</select>
							<?php } ?>
						</div>
					</div>


					<?php get_small_mapping_html( 'egoi_mapper', 'Gravity Form', true ); ?>

					<?php get_tag_html( '', 'gf_tag', true ); ?>


					<div class="smsnf-input-group">
						<label for="edit_gf"><?php _e( 'Update Subscriber', 'egoi-for-wp' ); ?></label>
						<p class="subtitle"><?php _e( 'Select "yes" to edit the subscriber if already exists in E-goi List.', 'egoi-for-wp' ); ?></p>
						<div class="smsnf-wrapper" style="display: flex;align-items: flex-end;margin-top: 12px;">
							<label><input type="radio"  name="egoi_int[edit_gf]" <?php  if(isset($egoint['edit_gf'])) {checked( $egoint['edit_gf'], 1 ); } ?> value="1"><?php _e( 'Yes', 'egoi-for-wp' ); ?></label> &nbsp;
							<label><input type="radio" name="egoi_int[edit_gf]" <?php if(isset($egoint['edit_gf'])) {checked( $egoint['edit_gf'], 0 ); } ?> value="0"><?php _e( 'No', 'egoi-for-wp' ); ?></label>
						</div>
					</div>


				<?php } ?>

			<?php } ?>

		</div>
	</div>

	<?php if ( class_exists( 'GFAPI' ) ) { ?>
		<div class="egoi-undertable-button-wrapper" style="bottom: 0;position: absolute;right: 30px;">
			<div class="smsnf-input-group">
				<input type="submit" value="<?php _e( 'Save', 'egoi-for-wp' ); ?>" />
			</div>
		</div>
	<?php } ?>

	<div class="modal" id="create-new-tag">
		<a href="#close" class="modal-overlay" aria-label="Close"></a>
		<div class="modal-container">
			<div class="modal-header">
				<h2><?php _e( 'Create New Tag', 'egoi-for-wp' ); ?></h2>
				<a href="#close" class="btn btn-clear float-right" aria-label="Close"></a>
			</div>
			<div class="modal-body">
				<div class="content">

					<div class="smsnf-input-group">
						<label for="tag_name" style="display: flex;justify-content: center;"><?php _e( 'Name', 'egoi-for-wp' ); ?><div id="loading_add_tag" style="display: none;margin-left: 20px;" class="loading"></div></label>
						<input id="new_tag_name" type="text" name="name" />
					</div>
					<div class="smsnf-input-group">
						<input id="new_tag_submit" type="submit" value="Criar TAG">
					</div>

				</div>
			</div>
		</div>
	</div>

</form>
