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
                } else {
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
                }

                $shortcode = '[egoi-simple-form id="'.$id_simple_form.'"]';

                return array(
                                'shortcode' => $shortcode, 
                                'id_simple_form' => $id_simple_form, 
                                'title_simple_form' => $_POST['title'], 
                                'html_code_simple_form' => $_POST['html_code']
                            ) ;

            }

            $shortcode = saveSimpleForm();
            $id_simple_form = $shortcode['id_simple_form'];

            echo "<div id='shortcode_div' style='width:100%;background:#00aeda;text-align:center;color:#fff'  onclick='select_all(this)'>".$shortcode['shortcode']."</div>";
       
        } else if (isset($_GET['edit_simple_form'])) {

            function selectSimpleForm($id) {
                global $wpdb;
                $table = $wpdb->prefix."posts";
                $shortcode['title_simple_form'] = $wpdb->get_var( "SELECT post_title FROM ".$table." WHERE ID = '".$id."' " ); 
                $shortcode['html_code_simple_form'] = $wpdb->get_var( "SELECT post_content FROM ".$table." WHERE ID = '".$id."' " ); 
                return $shortcode;
            }
            $shortcode = selectSimpleForm($_GET['simple_form']);
            $id_simple_form = $_GET['simple_form'];

        } else {
            $id_simple_form = 0;
        }
        ?>      
    
        <div class="nav-tab-forms-options-mt">
            <form id="egoi_simple_form" method="post" action="#">
                <input name="id_simple_form" type="hidden" value="<?=$id_simple_form?>">
                <input name="action" type="hidden" value="1">
                <div id="simple-form-submit-error"></div>
                <div class="e-goi-form-title">
                    <p style="font-size:18px; line-height:16px;"><?php _e('Form title', 'egoi-for-wp'); ?></p>
                </div> <!-- .e-goi-form-title -->

                <div id="titlediv" class="small-margin">
                    <div id="titlewrap">
                        <label class="screen-reader-text" for="title"><?php _e('Form Title', 'egoi-for-wp'); ?></label>

                        <input class="e-goi-form-title--input" type="text" name="title" size="30" id="title" spellcheck="true" autocomplete="off" 
                        placeholder="<?php echo __( "Write here the title of your form", 'egoi-for-wp' ); ?>" required pattern="\S.*\S" 
                        value="<?=$shortcode['title_simple_form']?>">
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
                    <p style="color:#d8b14e;"><?php _e('You cant change the inputs IDs', 'egoi-for-wp'); ?></p>
                </div>

                <!-- textarea for Advanced HTML -->
                <textarea id="html_code" class="e-goi-header-textarea--html-adv" placeholder="<?php _e( 'HTML code of your form', 'egoi-for-wp' ); ?>" name="html_code"><?php echo str_replace('\"', '"', $shortcode['html_code_simple_form']); ?></textarea>

                <div style="display: -webkit-inline-box; margin-bottom: 30px;">
                    <button style="margin-top: 12px;" type="submit" class="button button-primary"><?php _e('Save Changes', 'egoi-for-wp');?></button>
                </div>
            </form>
        </div>
        
        <script type="text/javascript">

            function toggleLabel(html_code, label, tag) {
                var begin_tag = "[Egoi-" + tag + "]";
                var first_char = html_code.indexOf(begin_tag);

                if (first_char < 0 ) {
                    html_code += label;
                } else {
                    var end_tag = "[/Egoi-" + tag + "]";
                    var last_char = html_code.indexOf(end_tag) + end_tag.length +1;

                    html_code = html_code.replace(html_code.substring(first_char, last_char), '');
                    
                }
                return html_code;
            }

            jQuery(".simple-form-button").on("click", function () {
                var html_code = jQuery("#html_code").val();

                switch (this.id) {
                    case 'egoi_name_button':
                        var label = '[Egoi-Name]\n<p>\n  <label for="egoi_name"><?php _e('Name', 'egoi-for-wp');?>: </label>\n  <input type="text" name="egoi_name" id="egoi_name">\n</p>\n[/Egoi-Name]\n';
                        html_code = toggleLabel(html_code, label, 'Name');
                        break;
                    case 'egoi_email_button':
                        var label = '[Egoi-Email]\n<p>\n   <label for="egoi_email"><?php _e('Email', 'egoi-for-wp');?>: </label>\n  <input type="email" name="egoi_email" id="egoi_email">\n</p>\n[/Egoi-Email]\n';
                        html_code = toggleLabel(html_code, label, 'Email');
                        break;
                    case 'egoi_mobile_button':
                        var label = '[Egoi-Mobile]\n<p>\n   <label for="egoi_mobile"><?php _e('Mobile', 'egoi-for-wp');?>: </label>\n  <input type="text" name="egoi_mobile" id="egoi_mobile">\n</p>\n[/Egoi-Mobile]\n';
                        html_code = toggleLabel(html_code, label, 'Mobile');
                        break;
                    case 'egoi_submit_button':
                        var label = '[Egoi-Submit]\n<p>\n   <button type="submit"><?php _e('Submit Button', 'egoi-for-wp');?></button>\n</p>\n[/Egoi-Submit]\n';
                        html_code = toggleLabel(html_code, label, 'Submit');
                }

                jQuery("#html_code").val(html_code);
            });

            jQuery("#egoi_simple_form").on("submit", function () {
                var html_code = jQuery("#html_code").val();
                var simple_form_error = false;
                if ( html_code.indexOf('[Egoi-Submit]') < 0 || html_code.indexOf('[/Egoi-Submit]') < 0 ) {
                    simple_form_error = 'Submit button is required';       
                } else if ( (html_code.indexOf('[Egoi-Name]') < 0 || html_code.indexOf('[/Egoi-Name]') < 0) 
                        && (html_code.indexOf('[Egoi-Email]') < 0 || html_code.indexOf('[/Egoi-Email]') < 0)
                        && (html_code.indexOf('[Egoi-Mobile]') < 0 || html_code.indexOf('[/Egoi-Mobile]') < 0) ) {
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

        </script>

    <?php } else { ?>

    <?php
        if (isset($_GET['del_simple_form'])) {
            function deleteSimpleForm($id) {
                global $wpdb;
                $table = $wpdb->prefix."posts";
                $where = array('ID' => $id);
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
                                <a href="<?php echo $_SERVER['REQUEST_URI'];?>&simple_form=<?php echo $id_simple_form;?>&del_simple_form=1">Confirmar</a>
                            </li>
                            <li>
                                <a class="cd-popup-close-btn" href="#0">Cancelar</a>
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
                        <a class="cd-popup-trigger-del" data-id-form="<?=$id_simple_form?>" data-type-form="simple-form" href="#"><?php _e('Delete', 'egoi-for-wp');?></span>
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