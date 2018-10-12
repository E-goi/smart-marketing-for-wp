<?php
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
?>
<div class="wrap egoi4wp-settings" id="tab-forms">
    <div class="row">

        <?php
        if (isset($_GET['type']) && $_GET['type'] == 'simple_form' ) {

            if (isset($_POST['id_simple_form'])) {

                function saveSimpleForm() {
                    global $wpdb;

                    $table = $wpdb->prefix.'posts';
                    $user = wp_get_current_user();
                    $date = date('Y-m-d H:i:s');
                    $post_name =  preg_replace('~[^0-9a-z]+~i', '-', preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities(str_replace(" ", "-", strtolower($_POST['title'])), ENT_QUOTES, 'UTF-8'))) ;


                    //to save simple form options: listId and language
                    $table2 = $wpdb->prefix.'options';

                    $data->list = $_POST['list'];
                    $data->lang = $_POST['lang'];
                    $data->double_optin = $_POST['double_optin'];

                    //to add tag if not exist
                    if(isset($_POST['tag-egoi']) && $_POST['tag-egoi']!=''){
                        $data->tag = $_POST['tag-egoi'];
                    }
                    else{
                        $tag = new Egoi_For_Wp();
                        $new = $tag->addTag($_POST['tag']);
                        $data->tag = $new->ID;
                    }

                    $info = json_encode($data);

                    if ($_POST['id_simple_form'] == 0) {
                        $post = array (
                            'post_author' => $user->ID,
                            'post_date' => $date,
                            'post_date_gmt' => $date,
                            'post_content' => $_POST['html_code'],
                            'post_title' => $_POST['title'],
                            'comment_status' => 'closed',
                            'ping_status' => 'closed',
                            'post_name' => $post_name,
                            'post_modified' => $date,
                            'post_modified_gmt' => $date,
                            'guid' => $_SERVER['HTTP_REFERER'],
                            'post_type' => 'egoi-simple-form'
                        );

                        $query =  $wpdb->insert($table, $post);
                        $id_simple_form = $wpdb->insert_id;

                        //insert simple form options
                        $options = array(
                            'option_name' => 'egoi_simple_form_'.$id_simple_form,
                            'option_value' => $info,
                            'autoload' => 'yes'
                        );

                        $query2 =  $wpdb->insert($table2, $options);

                    }
                    else {

                        $post = array (
                            'post_author' => $user->ID,
                            'post_content' => $_POST['html_code'],
                            'post_title' => $_POST['title'],
                            'post_name' => $post_name,
                            'post_modified' => $date,
                            'post_modified_gmt' => $date,
                            'guid' => $_SERVER['HTTP_REFERER']
                        );

                        $where = array('ID' => $_POST['id_simple_form']);
                        $query = $wpdb->update($table, $post, $where);
                        $id_simple_form = $_POST['id_simple_form'];

                        //update simple form options
                        $options = array(
                            'option_value' => $info
                        );

                        $where2 = array('option_name' => 'egoi_simple_form_'.$id_simple_form);

                        $query2 = $wpdb->update($table2, $options, $where2);
                    }

                    $shortcode = '[egoi-simple-form id="'.$id_simple_form.'"]';

                    return array(
                        'shortcode' => $shortcode,
                        'id_simple_form' => $id_simple_form,
                        'title_simple_form' => $_POST['title'],
                        'html_code_simple_form' => $_POST['html_code'],
                        'list' => $_POST['list'],
                        'lang' => $_POST['lang'],
                        'tag' => isset($_POST['tag-egoi']) ? $_POST['tag-egoi'] : $new->ID,
                        'double_optin' => $_POST['double_optin']
                    ) ;

                }

                $shortcode = saveSimpleForm();
                $id_simple_form = $shortcode['id_simple_form'];

                echo "<div id='shortcode_div' style='width:100%;background:#00aeda;text-align:center;color:#fff'  onclick='select_all(this)'>".$shortcode['shortcode']."</div>";

            } else if (isset($_GET['edit_simple_form'])) {

                function selectSimpleForm($id) {
                    global $wpdb;
                    $table = $wpdb->prefix."posts";
                    $shortcode['shortcode'] = '[egoi-simple-form id="'.$id.'"]';
                    $shortcode['title_simple_form'] = $wpdb->get_var( "SELECT post_title FROM ".$table." WHERE ID = '".$id."' " );
                    $shortcode['html_code_simple_form'] = $wpdb->get_var( "SELECT post_content FROM ".$table." WHERE ID = '".$id."' " );


                    //get simple form options
                    $data = get_option('egoi_simple_form_'.$id);

                    $info = json_decode($data);

                    $shortcode['list'] = $info->list;
                    $shortcode['lang'] = $info->lang;
                    $shortcode['tag'] = $info->tag;
                    $shortcode['double_optin'] = $info->double_optin;

                    return $shortcode;
                }

                $shortcode = selectSimpleForm($_GET['simple_form']);

                $id_simple_form = $_GET['simple_form'];

                echo "<div id='shortcode_div' style='width:100%;background:#00aeda;text-align:center;color:#fff'  onclick='select_all(this)'>".$shortcode['shortcode']."</div>";

            } else {
                $id_simple_form = 0;
            }
            ?>

            <div class="nav-tab-forms-options-mt">
                <form id="egoi_simple_form" method="post" action="#">
                    <input name="id_simple_form" type="hidden" value="<?=$id_simple_form?>" />
                    <input name="action" type="hidden" value="1" />
                    <div id="simple-form-submit-error"></div>

                    <div id="sf-submit-error" style="display: none;">
                        <div class="error notice">
                            <p><?php _e('Please, choose the list.', 'egoi-for-wp'); ?></p>
                        </div>
                    </div>

                    <div>
                        <table class="form-table" style="table-layout: fixed;">


                            <tr valign="top">
                                <th scope="row">
                                    <label>
                                        <?php _e( 'Enable Double Opt-In?', 'egoi-for-wp' ); ?>
                                    </label>
                                </th>
                                <td>
                                    <label>
                                        <input type="radio" name="double_optin" value="1" <?php echo !isset($shortcode['double_optin']) || $shortcode['double_optin'] == 1 ? 'checked' : null; ?> /> <?php _e( 'Yes' ); ?>
                                    </label>
                                    <label>
                                        <input type="radio" name="double_optin" value="0" <?php checked($shortcode['double_optin'], 0); ?> /> <?php _e( 'No' ); ?>
                                    </label>
                                    <p class="help"><?php _e( 'If you activate the double opt-in, a confirmation e-mail will be send to the subscribers.', 'egoi-for-wp' ); ?></p>
                                </td>
                            </tr>


                            <tr valign="top">
                                <th scope="row"><label><?php _e( 'Egoi List', 'egoi-for-wp' ); ?></label></th>
                                <td>
                                <span class="e-goi-lists_not_found" style="display: none;">
                                    <?php printf(__('No lists found, <a href="%s">are you connected to Egoi</a>?', 'egoi-for-wp'), admin_url('admin.php?page=egoi-for-wp'));?>
                                </span>

                                    <span id="e-goi-lists_ct_simple_form" style="display: none;"><?php echo $shortcode['list'];?></span>

                                    <span class="loading_lists-simple-form dashicons dashicons-update" style="display: none;"></span>
                                    <select name="list" class="lists" id="e-goi-list-simple-form" style="display: none;">
                                        <option disabled <?php selected($shortcode['list'], ''); ?>><?php _e( 'Select a list..', 'egoi-for-wp' ); ?></option>
                                    </select>
                                    <p class="help"><?php _e( 'Select the list to which visitors should be subscribed.' ,'egoi-for-wp' ); ?></p>
                                </td>
                            </tr>

                            <!-- Languages -->
                            <tr valign="top">
                                <th scope="row"><label id="egoi-lang-sf" style="display: none;"><?php _e( 'E-goi List Language', 'egoi-for-wp' ); ?></label></th>
                                <td>
                                    <span id="lang_simple_form" style="display: none;"><?php echo $shortcode['lang'];?></span>

                                    <span class="loading_lang-simple-form dashicons dashicons-update" style="display: none;"></span>
                                    <select name="lang" id="e-goi-lang-simple-form" style="display: none;">
                                    </select>
                                </td>
                            </tr>
                            <!-- END config list and language -->


                            <tr valign="top">
                                <th scope="row"><label for="egoi_tag_simple-form"><?php _e( 'Select a tag', 'egoi-for-wp' ); ?></label></th>
                                <td>
                                    <div class="nav-tab-wrapper-tags" id="egoi-tabs-simple-form-tags">
                                        <a class="nav-tab-simple-form-egoi-tags nav-tab-active" id="nav-tab-simple-form-egoi-tags" style="cursor: pointer;"><?php _e( 'Select E-goi tags', 'egoi-for-wp' ); ?></a>
                                        <span> | </span>
                                        <a class="nav-tab-simple-form-new-tags" id="nav-tab-simple-form-new-tags" style="cursor: pointer;"><?php _e( 'Add new tag', 'egoi-for-wp' ); ?></a>
                                    </div>
                                    <br>

                                    <!-- TABS -->
                                    <div id="tab-simple-form-egoi-tags">
                                    <span class="egoi-tags_not_found" style="display: none;">
                                        <?php printf(__('No tags found, <a href="%s">are you connected to Egoi</a>?', 'egoi-for-wp'), admin_url('admin.php?page=egoi-for-wp'));?>
                                    </span>

                                        <span id="e-goi-tags_ct_simple-form" style="display: none;"><?php echo $shortcode['tag'];?></span>

                                        <span class="loading_tags-simple-form dashicons dashicons-update" style="display: none;"></span>
                                        <select name="tag-egoi" class="tags" id="e-goi-tags-simple-form" style="display: none;">
                                            <option disabled <?php selected($shortcode['tag'], ''); ?>><?php _e( 'Select a tag..', 'egoi-for-wp' ); ?></option>
                                        </select>

                                        <p class="help"><?php _e( 'Select the tag to which visitors should be associated', 'egoi-for-wp' ); ?></p>
                                    </div>

                                    <div id="tab-simple-form-new-tags" style="display: none;">
                                        <input type="text" style="width:450px;" id="egoi_tag" name="tag" placeholder="<?php _e( 'Choose a name for your new tag', 'egoi-for-wp' ); ?>" value="" />
                                        <p class="help"><?php _e( 'Create a new tag to which visitors should be associated', 'egoi-for-wp' ); ?></p>
                                    </div>

                                </td>
                            </tr>

                        </table>
                    </div>

                    <!-- TAGS -->
                    <div>
                    </div>

                    <div class="e-goi-form-title">
                        <p style="font-size:18px; line-height:16px;"><?php _e('Form title', 'egoi-for-wp'); ?></p>
                    </div> <!-- .e-goi-form-title -->

                    <div id="titlediv" class="small-margin">
                        <div id="titlewrap">
                            <label class="screen-reader-text" for="title"><?php _e('Form Title', 'egoi-for-wp'); ?></label>

                            <input class="e-goi-form-title--input" type="text" name="title" size="30" id="title" spellcheck="true" autocomplete="off"
                                   placeholder="<?php echo __( "Write here the title of your form", 'egoi-for-wp' ); ?>" required pattern="\S.*\S"
                                   value="<?= htmlentities(stripslashes($shortcode['title_simple_form'])) ?>" />
                        </div>
                    </div>

                    <!-- Header Textarea -->
                    <div class="e-goi-header-textarea">
                        <!-- Titulo -->
                        <p><?php _e('HTML code', 'egoi-for-wp'); ?></p>

                    </div>
                    <div>
                        <p>
                            <button type="button" class="simple-form-button button button-default" id="egoi_name_button"><?php _e('Name', 'egoi-for-wp');?></button>
                            <button type="button" class="simple-form-button button button-default" id="egoi_email_button"><?php _e('Email', 'egoi-for-wp');?></button>
                            <button type="button" class="simple-form-button button button-default" id="egoi_mobile_button"><?php _e('Mobile', 'egoi-for-wp');?></button>
                            <button type="button" class="simple-form-button button button-default" id="egoi_submit_button"><?php _e('Submit Button', 'egoi-for-wp');?></button>
                        </p>
                        <p style="color:#d8b14e;"><?php _e('You can\'t change the IDs of inputs and buttons', 'egoi-for-wp'); ?></p>
                    </div>

                    <!-- textarea for Advanced HTML -->
                    <textarea id="html_code" class="e-goi-header-textarea--html-adv" placeholder="<?php _e( 'HTML code of your form', 'egoi-for-wp' ); ?>" name="html_code"><?= stripslashes($shortcode['html_code_simple_form']); ?></textarea>

                    <div style="display: -webkit-inline-box; margin-bottom: 30px;">
                        <button style="margin-top: 12px;" type="submit" class="button button-primary"><?php _e('Save Changes', 'egoi-for-wp');?></button>
                    </div>
                </form>
            </div>

            <script type="text/javascript">

                jQuery(document).ready(function() {

                    var html_code = jQuery("#html_code").val();

                    if (html_code.length > 0) {

                        var tags = ["name", "email", "mobile", "submit"];
                        for (var i = 0; i < tags.length; i++) {
                            if (html_code.indexOf('[e_' + tags[i] + ']') >= 0 && html_code.indexOf('[/e_' + tags[i] + ']') >= 0) {
                                jQuery('#egoi_' + tags[i] + '_button').addClass("active");
                            }
                        }
                    }

                    if(jQuery("#e-goi-lists_ct_simple_form").text() != ""){
                        jQuery('#egoi-lang-sf').show();
                        getListLangSF(jQuery("#e-goi-lists_ct_simple_form").text());
                    }

                    if(jQuery("#e-goi-tags_ct_simple_form").text() != ""){
                        getTagsSF();
                    }

                    'use strict';

                    new Clipboard('#e-goi_shortcode');

                    var session_form = jQuery('#session_form');

                    // initialize class to parse URLs
                    var urlObj = new URL(window.location.href);

                    // Async fetch
                    var page = urlObj.searchParams.get("page");
                    if(typeof page != 'undefined'){
                        if(page == 'egoi-4-wp-form'){

                            var data_lists = {
                                action: 'egoi_get_lists'
                            };

                            var select_lists_simple_form = jQuery('#e-goi-list-simple-form');
                            var current_lists = [];

                            jQuery(".loading_lists-simple-form").addClass('spin').show();

                            var lists_count_simple_form = jQuery('#e-goi-lists_ct_simple_form');

                            jQuery.post(url_egoi_script.ajaxurl, data_lists, function(response) {
                                jQuery(".loading_lists-simple-form").removeClass('spin').hide();
                                current_lists = JSON.parse(response);



                                if(current_lists.ERROR){
                                    jQuery('.e-goi-lists_not_found').show();

                                    select_lists_simple_form.hide();

                                }else{
                                    select_lists_simple_form.show();

                                    jQuery('.e-goi-lists_not_found').hide();

                                    jQuery.each(current_lists, function(key, val) {

                                        if(typeof val.listnum != 'undefined') {
                                            select_lists_simple_form.append(jQuery('<option />').val(val.listnum).text(val.title));

                                            if(lists_count_simple_form.text() === val.listnum){
                                                select_lists_simple_form.val(val.listnum);
                                            }
                                        }
                                    });

                                }
                            });
                        }
                    }


                    jQuery('#nav-tab-simple-form-egoi-tags').click(function() {
                        jQuery('#tab-simple-form-new-tags').hide();
                        jQuery('#tab-simple-form-egoi-tags').show();
                        jQuery('#egoi_tag').val('');
                        jQuery(this).addClass('nav-tab-active');
                        jQuery('#nav-tab-simple-form-new-tags').removeClass('nav-tab-active');
                    });

                    jQuery('#nav-tab-simple-form-new-tags').click(function() {
                        jQuery('#tab-simple-form-new-tags').show();
                        jQuery('#tab-simple-form-egoi-tags').hide();
                        jQuery('#e-goi-tags-simple-form').val('');
                        jQuery(this).addClass('nav-tab-active');
                        jQuery('#nav-tab-simple-form-egoi-tags').removeClass('nav-tab-active');
                    });

                    getTagsSF();

                });

                jQuery("#e-goi-list-simple-form").change(function(){
                    jQuery('#e-goi-lang-simple-form').hide();
                    jQuery('#e-goi-lang-simple-form').empty();
                    jQuery('#egoi-lang-sf').show();
                    jQuery(".loading_lang-simple-form").addClass('spin').show();

                    var listID = jQuery("#e-goi-list-simple-form").val();

                    getListLangSF(listID);
                });

                function getListLangSF(listID){

                    var data_lists = {
                        action: 'egoi_get_lists'
                    };

                    jQuery.post(url_egoi_script.ajaxurl, data_lists, function(response) {

                        content = JSON.parse(response);

                        jQuery('#e-goi-lang-simple-form').show();

                        var idiomas = [];
                        jQuery.each(content, function(key, val) {

                            if(val.listnum == listID){
                                var idioma = val.idioma;
                                var idiomas_extra = val.idiomas_extra;

                                var idiomas = [];

                                idiomas.push(idioma);
                                if (idiomas_extra != "") {
                                    jQuery.each(idiomas_extra, function(key, val){
                                        idiomas.push(val);
                                    });
                                }

                                jQuery.each(idiomas, function(key, val){
                                    if(jQuery('#lang_simple_form').text() != "" && jQuery('#lang_simple_form').text() == val){
                                        jQuery("#e-goi-lang-simple-form").append('<option selected value="' + val + '">' + val + '</option>');
                                    }
                                    else{
                                        jQuery("#e-goi-lang-simple-form").append('<option value="' + val + '">' + val + '</option>');
                                    }
                                });

                                jQuery(".loading_lang-simple-form").removeClass('spin').hide();
                            }

                        });

                    });
                }

                function toggleLabel(html_code, label, tag) {
                    var button = jQuery('#egoi_' + tag + '_button');
                    button.toggleClass("active");

                    var begin_tag = "[e_" + tag + "]";
                    var first_char = html_code.indexOf(begin_tag);

                    if (first_char < 0 ) {
                        html_code += label;
                    } else {
                        var end_tag = "[/e_" + tag + "]";
                        var last_char = html_code.indexOf(end_tag) + end_tag.length +1;

                        html_code = html_code.replace(html_code.substring(first_char, last_char), '');

                    }
                    return html_code;
                }

                jQuery(".simple-form-button").on("click", function () {
                    var html_code = jQuery("#html_code").val();

                    switch (this.id) {
                        case 'egoi_name_button':
                            var label = '[e_name]\n<p>\n  <label for="egoi_name"><?php _e('Name', 'egoi-for-wp');?>: </label>\n  <input type="text" name="egoi_name" id="egoi_name" />\n</p>\n[/e_name]\n';
                            html_code = toggleLabel(html_code, label, 'name');
                            break;
                        case 'egoi_email_button':
                            var label = '[e_email]\n<p>\n  <label for="egoi_email"><?php _e('Email', 'egoi-for-wp');?>: </label>\n  <input type="email" name="egoi_email" id="egoi_email" />\n</p>\n[/e_email]\n';
                            html_code = toggleLabel(html_code, label, 'email');
                            break;
                        case 'egoi_mobile_button':
                            var label = '[e_mobile]\n<p>\n  <label for="egoi_mobile"><?php _e('Mobile', 'egoi-for-wp');?>: </label>\n  <select name="egoi_country_code" id="egoi_country_code"></select><input type="text" name="egoi_mobile" id="egoi_mobile" />\n</p>\n[/e_mobile]\n';
                            html_code = toggleLabel(html_code, label, 'mobile');
                            break;
                        case 'egoi_submit_button':
                            var label = '[e_submit]\n<p>\n  <button type="submit" id="egoi_submit_button"><?php _e('Submit Button', 'egoi-for-wp');?></button>\n</p>\n[/e_submit]\n';
                            html_code = toggleLabel(html_code, label, 'submit');
                    }

                    jQuery("#html_code").val(html_code);
                });

                jQuery("#egoi_simple_form").on("submit", function () {
                    var html_code = jQuery("#html_code").val();
                    var simple_form_error = false;
                    jQuery('#sf-submit-error').hide();

                    if(jQuery("#e-goi-list-simple-form").val() == null && jQuery("#e-goi-lang-simple-form").val() == null){
                        console.log('aqui');
                        jQuery('#sf-submit-error').show();
                        return false;
                    }

                    if ( html_code.indexOf('[e_submit]') < 0 || html_code.indexOf('[/e_submit]') < 0 ) {
                        simple_form_error = 'Submit button is required';
                    }
                    else if ( (html_code.indexOf('[e_name]') < 0 || html_code.indexOf('[/e_name]') < 0)
                        && (html_code.indexOf('[e_email]') < 0 || html_code.indexOf('[/e_email]') < 0)
                        && (html_code.indexOf('[e_mobile]') < 0 || html_code.indexOf('[/e_mobile]') < 0) ) {
                        simple_form_error = 'At least one input is required';
                    }

                    if (simple_form_error) {
                        jQuery('#simple-form-submit-error').empty().append('<div class="error notice"><p>' + simple_form_error + '</p></div>');
                        return false;
                    }


                });

                function select_all(el) {
                    if (typeof window.getSelection != "undefined" && typeof document.createRange != "undefined") {
                        var range = document.createRange();
                        range.selectNodeContents(el);
                        var sel = window.getSelection();
                        sel.removeAllRanges();
                        sel.addRange(range);
                    } else if (typeof document.selection != "undefined" && typeof document.body.createTextRange != "undefined") {
                        var textRange = document.body.createTextRange();
                        textRange.moveToElementText(el);
                        textRange.select();
                    }
                }


                function getTagsSF(){
                    var data = {
                        action: 'egoi_get_tags'
                    }

                    var select_tags = jQuery('#e-goi-tags-simple-form');

                    var tags = [];

                    jQuery(".loading_tags-simple-form").addClass('spin').show();
                    var lists_count_tags = jQuery('#e-goi-tags_ct_simple-form');


                    jQuery.post(url_egoi_script.ajaxurl, data, function(response) {
                        tags = JSON.parse(response);
                        jQuery(".loading_tags-simple-form").removeClass('spin').hide();


                        if(tags.ERROR){
                            jQuery('.egoi-tags_not_found').show();
                            select_tags.hide();

                        }else{

                            select_tags.show();

                            jQuery('.e-goi-tags_not_found').hide();

                            jQuery.each(tags['TAG_LIST'], function(key, val) {

                                if(typeof val.ID != 'undefined') {
                                    var field_text = jQuery('<option />').html(val.NAME).text();
                                    select_tags.append(jQuery('<option />').val(val.ID).text(field_text));

                                    if(lists_count_tags.text() === val.ID){
                                        select_tags.val(val.ID);

                                    }
                                }
                            });
                        }
                    });
                }

            </script>

        <?php } else { ?>

            <?php
            if (isset($_GET['del_simple_form'])) {
                function deleteSimpleForm($id) {
                    global $wpdb;
                    $table = $wpdb->prefix."posts";
                    $where = array('ID' => $id);

                    //delete simple form options
                    $table2 = $wpdb->prefix."options";
                    $where2 = array('option_name' => 'egoi_simple_form_'.$id);
                    $test = $wpdb->delete($table2, $where2);

                    return $wpdb->delete($table, $where);
                }
                deleteSimpleForm($_GET['simple_form']);
            }
            ?>

            <!-- List -->
            <div class="main-content col col-4" style="margin:0 0 20px;">
                <div style="font-size:14px; margin:10px 0;">
                    <?php _e('Simple Forms', 'egoi-for-wp');?>
                </div>

                <table border='0' class="widefat striped">
                    <thead>
                    <tr>
                        <th><?php _e('Shortcode', 'egoi-for-wp');?></th>
                        <th><?php _e('Form ID', 'egoi-for-wp');?></th>
                        <th><?php _e('Title', 'egoi-for-wp');?></th>
                        <th><?php _e('', 'egoi-for-wp');?></th>
                        <th><?php _e('', 'egoi-for-wp');?></th>
                    </tr>
                    </thead>

                    <?php

                    function getSimpleForms() {
                        global $wpdb;

                        $rows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."posts WHERE post_type = 'egoi-simple-form'");
                        return $rows;
                    }

                    $simple_forms = getSimpleForms();

                    foreach ($simple_forms as $simple_form) {
                        $id_simple_form = $simple_form->ID;
                        $title_simple_form = $simple_form->post_title;
                        $shortcode = '[egoi-simple-form id="'.$id_simple_form.'"]';


                        ?>

                        <!-- PopUp ALERT Delete Form -->
                        <div class="cd-popup cd-popup-del-form" data-id-form="<?=$id_simple_form?>" data-type-form="simple-form" role="alert">
                            <div class="cd-popup-container">
                                <p><b><?php echo __('Are you sure you want to delete this form?</b> This action will remove only the form in your plugin (will be kept in E-goi).', 'egoi-for-wp');?> </p>
                                <ul class="cd-buttons">
                                    <li>
                                        <a href="<?php echo $_SERVER['REQUEST_URI'];?>&simple_form=<?php echo $id_simple_form;?>&del_simple_form=1"><?php _e('Confirm', 'egoi-for-wp'); ?></a>
                                    </li>
                                    <li>
                                        <a class="cd-popup-close-btn" href="#0"><?php _e('Cancel', 'egoi-for-wp'); ?></a>
                                    </li>
                                </ul>
                            </div> <!-- cd-popup-container -->
                        </div> <!-- PopUp ALERT Delete Form -->

                        <tr>
                            <!-- Shortcode -->
                            <td><span style="padding:6px 12px; background-color: #ffffff; border: 1px solid #ccc;white-space: nowrap;"><?php echo $shortcode;?></span></td>
                            <!-- ID -->
                            <td><?php echo $id_simple_form;?></td>
                            <!-- Title -->
                            <td><?php echo $title_simple_form;?></td>
                            <td>
                                <a class="cd-popup-trigger-del" data-id-form="<?=$id_simple_form?>" data-type-form="simple-form" href="#"><?php _e('Delete', 'egoi-for-wp');?></a>
                            </td>
                            <!-- Option -->
                            <td style="text-align:right;">
                                <a title="<?php _e('Edit', 'egoi-for-wp');?>" href="<?php echo $_SERVER['REQUEST_URI'];?>&type=simple_form&simple_form=<?php echo $id_simple_form;?>&edit_simple_form=1"><span class="dashicons dashicons-edit"></span></a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>

                </table>
                <p>
                    <a href="<?php echo $_SERVER['REQUEST_URI'];?>&type=simple_form" class='button-primary'><?php _e('Create form +', 'egoi-for-wp');?></a>
                </p>
            </div>

            <?php

        } ?>
    </div>
</div>


<!-- Banner -->
<div class="sidebar" style="width: 220px;">
    <?php include ('egoi-for-wp-admin-banner.php'); ?>
</div>