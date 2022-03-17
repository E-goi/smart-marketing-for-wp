<?php
// cria html de uma notificação
function get_notification( $title = '', $content = '', $type = 'success', $lazy = false ) {
	$class   = $type == 'error' ? ' smsnf-notification-error' : '';
	$display = $lazy ? 'lazy="true"' : '';
	return '
        <div class="smsnf-notification' . $class . '" ' . $display . '>
            <div class="close-btn">&#10005;</div>
            <h2>' . esc_html( $title ) . '</h2>
            <p>' . esc_html( $content ) . '</p>
        </div>
	';
}

// obtem o id do próximo formulário avançado
function get_next_adv_form_id() {
	for ( $i = 1; $i <= 5; $i++ ) {
		$form = get_option( 'egoi_form_sync_' . $i );

		if ( ! $form['egoi_form_sync']['form_id'] ) {
			return $i;
		}
	}
	return null;
}

// obtem os formulários avançados
function get_adv_forms() {
	$forms = array();

	for ( $i = 1; $i <= 5; $i++ ) {
		$form = get_option( 'egoi_form_sync_' . $i );

		if ( ! $form['egoi_form_sync']['form_id'] ) {
			continue;
		}

		$forms[] = array(
			'id'        => $i,
			'shortcode' => "[egoi_form_sync_$i]",
			'title'     => $form['egoi_form_sync']['form_name'],
			'state'     => $form['egoi_form_sync']['enabled'],
			'type'      => $form['egoi_form_sync']['egoi'],
		);
	}

	return $forms;
}

// obtem os formulários simples
function get_simple_forms() {
	global $wpdb;

	$rows = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . "posts WHERE post_type = 'egoi-simple-form'" );
	return $rows;
}

// apaga formulário simples
function delete_simple_form( $id ) {
	global $wpdb;

	$table = $wpdb->prefix . 'posts';
	$where = array( 'ID' => $id );

	// delete simple form options
	$table2 = $wpdb->prefix . 'options';
	$where2 = array( 'option_name' => 'egoi_simple_form_' . $id );
	$test   = $wpdb->delete( $table2, $where2 );

	return $wpdb->delete( $table, $where );
}
?>

<?php function get_list_html( $selected_list, $name ) { ?>
	<div class="smsnf-input-group">
		<label for="list_to_subscribe"><?php _e( 'Egoi List', 'egoi-for-wp' ); ?></label>
		<p class="subtitle"><?php _e( 'Select the list to which visitors should be subscribed.', 'egoi-for-wp' ); ?></p>
		<div class="smsnf-wrapper">
			<select id="list_to_subscribe" name="<?php echo ! empty( $name ) ? esc_attr( $name ) : ''; ?>" class="form-select" <?php echo 'data-egoi-list="' . esc_attr( $selected_list ) . '"'; ?> disabled>
				<option value="" selected disabled hidden><?php _e( 'Select a list..', 'egoi-for-wp' ); ?></option>
			</select>
			<div class="loading"></div>
		</div>
	</div>
	<?php
}

function get_lang_html( $selected_lang, $name, $hide ) {
	?>
	<div id="form_lang_wrapper" class="smsnf-input-group" <?php echo ! empty( $hide ) ? 'style="display: none"' : ''; ?>>
		<label for="form_lang"><?php _e( 'E-goi List Language', 'egoi-for-wp' ); ?></label>
		<div class="smsnf-wrapper">
			<select name="<?php echo ! empty( $name ) ? esc_attr( $name ) : ''; ?>" id="form_lang" class="form-select" data-egoi-lang="<?php echo ! empty( $selected_lang ) ? esc_attr( $selected_lang ) : ''; ?>"  disabled required></select>
			<div class="loading"></div>
		</div>
	</div>
	<?php
}

function get_tag_html( $selected_tag, $name, $hide = false ) {
	?>
	<div id="form_tag_wrapper" class="smsnf-input-group" style="<?php echo $hide ? 'display: none;' : ''; ?>" >
		<label for="form_tag"><?php _e( 'Select a tag', 'egoi-for-wp' ); ?></label><a data-modal="create-new-tag"><?php _e( 'Create new tag +', 'egoi-for-wp' ); ?></a>
		<div class="smsnf-wrapper">
			<select name="<?php echo ! empty( $name ) ? esc_attr( $name ) : ''; ?>" id="form_tag" class="form-select" data-egoi-tag="<?php echo ! empty( $selected_tag ) ? esc_attr( $selected_tag ) : ''; ?>"  disabled>
				<option value="" selected disabled hidden><?php _e( 'Select a tag..', 'egoi-for-wp' ); ?></option>
			</select>
			<div class="loading"></div>
		</div>
	</div>
	<?php
}


function get_small_mapping_html( $name, $app, $hide = false ) {
	?>
	<div id="<?php echo ! empty( $name ) ? esc_attr( $name ) : ''; ?>" style="<?php echo $hide ? 'display: none;' : ''; ?>" class="smsnf-input-group">
		<label style="display: flex"><?php echo __( 'Fields Mapping', 'egoi-for-wp' ); ?><div id="egoi_add_map_loader" class="loader-egoi-self" role="status" style="display: none;"><i class="loading"><?php echo __( 'Loading', 'egoi-for-wp' ); ?></i></div></label>
		<div class="egoi-small-mapper">
			<div class="smsnf-input-group">
				<label for="app_field_map"><?php echo esc_html( __( "$app Fields", 'egoi-for-wp' ) ); ?></label>
				<select id="app_field_map" class="form-select">
					<option value="0" selected hidden><?php _e( 'Select a field..', 'egoi-for-wp' ); ?></option>
				</select>
			</div>
			<div></div>
			<div class="smsnf-input-group">
				<label for="egoi_field_map"><?php echo __( 'E-goi\'s Fields', 'egoi-for-wp' ); ?></label>
				<div class="smsnf-wrapper">
					<select id="egoi_field_map" class="form-select">
						<option value="0" selected hidden><?php _e( 'Select a field..', 'egoi-for-wp' ); ?></option>
					</select>
				</div>
			</div>
			<div class="smsnf-input-group">
				<label for="egoi_field_map">&ensp;</label>
				<input id="egoi_add_map" type="button" value="<?php _e( 'Add', 'egoi-for-wp' ); ?>" disabled />
			</div>
		</div>
		<div class="egoi-small-mapper egoi-small-mapped-fields" id="egoi-small-mapped-fields">
			<div class="app"></div>
			<div class="splitter"></div>
			<div class="egoi"></div>
			<div class="close"></div>
		</div>
	</div>
	<?php
}

function get_form_html( $selected_form, $name, $hide ) {
	?>
	<div id="form_list_group" class="smsnf-input-group" <?php echo ! empty( $hide ) ? 'style="display: none"' : ''; ?>>
		<label for="form_list"><?php _e( 'E-goi Form to Subscribe', 'egoi-for-wp' ); ?></span></label>
		<p class="subtitle"><?php _e( 'Need a iframe form? Simply select a form (which already exists in E-goi) and copy the shortcode to display this form on your website or blog', 'egoi-for-wp' ); ?></p>
		<div class="form-group">
			<div class="smsnf-wrapper">
				<select id="form_list" class="form-select" name="<?php echo ! empty( $name ) ? esc_attr( $name ) : ''; ?>" <?php echo 'data-egoi-form="' . esc_attr( $selected_form ) . '"'; ?> disabled>
					<option value="" selected disabled hidden><?php _e( 'Select your form', 'egoi-for-wp' ); ?></option>
				</select>
				<div class="loading"></div>
			</div>
			<p id="empty-forms" class="error-msg"><?php _e( 'There are no forms on E-goi.', 'egoi-for-wp' ); ?></p>
		</div>
	</div>
<?php } ?>
