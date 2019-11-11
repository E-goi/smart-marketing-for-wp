<?
// cria html de uma notificação
function get_notification($title = '', $content = '') {
    return  '
        <div class="smsnf-notification">
            <div class="close-btn">&#10005;</div>
            <h2>' . $title . '</h2>
            <p>' . $content . '</p>
        </div>
	';
}

// obtem o id do próximo formulário avançado
function get_next_adv_form_id() {
	for ($i = 1; $i <= 5; $i++) {
		$form = get_option('egoi_form_sync_'. $i);

		if (!$form['egoi_form_sync']['form_id']) {
			return $i;
		}
	}
	return null;
}

// obtem os formulários avançados
function get_adv_forms() {
	$forms = array();

	for ($i = 1; $i <= 5; $i++) {
		$form = get_option('egoi_form_sync_'. $i);

		if (!$form['egoi_form_sync']['form_id']) {
			continue;
		}

		$forms[] = array(
			'id' => $i,
			'shortcode' => "[egoi_form_sync_$i]",
			'title' => $form['egoi_form_sync']['form_name'],
			'state' => $form['egoi_form_sync']['enabled'],
			'type' => $form['egoi_form_sync']['egoi'],
		);
	}

	return $forms;
}

// obtem os formulários simples
function get_simple_forms() {
    global $wpdb;

    $rows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."posts WHERE post_type = 'egoi-simple-form'");
    return $rows;
}

// apaga formulário simples
function delete_simple_form($id) {
	global $wpdb;

	$table = $wpdb->prefix."posts";
	$where = array('ID' => $id);

	//delete simple form options
	$table2 = $wpdb->prefix."options";
	$where2 = array('option_name' => 'egoi_simple_form_'.$id);
	$test = $wpdb->delete($table2, $where2);

	return $wpdb->delete($table, $where);
}
?>

<? function get_list_html($selected_list, $name) { ?>
	<div class="smsnf-input-group">
		<label for="list_to_subscribe"><? _e( 'Egoi List', 'egoi-for-wp' ); ?></label>
		<p class="subtitle"><?php _e( 'Select the list to which visitors should be subscribed.' ,'egoi-for-wp' ); ?></p>
		<div class="smsnf-wrapper">
			<select id="list_to_subscribe" name="<?= $name ?>" class="form-select" <?= 'data-egoi-list="'. $selected_list .'"' ?> disabled>
                <option value="" selected disabled hidden><? _e( 'Select a list..', 'egoi-for-wp' ); ?></option>
            </select>
			<div class="loading"></div>
		</div>
	</div>
<? }

function get_lang_html($selected_lang, $name, $hide) { ?>
	<div id="form_lang_wrapper" class="smsnf-input-group" <?= $hide ? 'style="display: none"' : '' ?>>
		<label for="form_lang"><? _e( 'E-goi List Language', 'egoi-for-wp' ); ?></label>
		<div class="smsnf-wrapper">
			<select name="egoi_widget[lang]" id="form_lang" class="form-select" data-egoi-lang="<?=$selected_lang ?>"  disabled required></select>
			<div class="loading"></div>
		</div>
	</div>
<? } 

function get_tag_html($selected_tag, $name) { return;?>
	<div class="smsnf-input-group">
		<label for="form_tag"><? _e( 'Select a tag', 'egoi-for-wp' ); ?></label><a data-modal="create-new-tag">Criar nova tag +</a>
		<div class="smsnf-wrapper">
			<select name="<?= $name ?>" id="form_tag" class="form-select" data-egoi-tag="<?= $selected_tag ?>"  disabled required>
				<option selected disabled hidden>Selecionar Tag</option>
			</select>
			<div class="loading"></div>
		</div>
	</div>
<? }

function get_form_html($selected_form, $name, $hide) { ?>
	<div id="form_list_group" class="smsnf-input-group" <?= $hide ? 'style="display: none"' : '' ?>>
		<label for="form_list"><? _e( 'E-goi Form to Subscribe', 'egoi-for-wp' ); ?></span></label>
		<p class="subtitle"><? _e( 'Need a iframe form? Simply select a form (which already exists in E-goi) and copy the shortcode to display this form on your website or blog', 'egoi-for-wp' ); ?></p>
		<div class="form-group">
			<div class="smsnf-wrapper">
				<select id="form_list" class="form-select" name="<?= $name ?>" <?= 'data-egoi-form="'. $selected_form .'"' ?> disabled>
					<option value="" selected disabled hidden><? _e('Select your form', 'egoi-for-wp');?></option>
				</select>
				<div class="loading"></div>
			</div>
			<p id="empty-forms" class="error-msg">There are no forms on E-goi.</p>
		</div>
	</div>
<? } ?>