<?php

require_once(plugin_dir_path( __FILE__ ) . '../../../includes/class-egoi-for-wp-popup.php');
require_once(plugin_dir_path( __FILE__ ) . 'functions.php');
if(!empty($_POST)){
    EgoiPopUp::savePostPopup($_POST);
    echo get_notification(__('Popups', 'egoi-for-wp'), __('Your popup was saved successfully', 'egoi-for-wp'));
}

$popup_id = empty($_GET['popup_id'])?'new':trim($_GET['popup_id']);

$popup = new EgoiPopUp($popup_id);
$popup_data = $popup->getPopupSavedData();

$content   = stripslashes($popup_data['content']);
$editor_id = 'content';


if(empty(get_simple_forms())){
    ?>
        <h2><?php _e('One step before starting...','egoi-for-wp'); ?></h2>
        <p><?php _e('Your popup must be linked to a simple form and for that you need to create your first one before!','egoi-for-wp'); ?></p>
<?php
    return;
}

?>


<form id="smsnf-popup-form" method="post" action="#">

    <input type="hidden" id="popup_id" name="popup_id" value="<?php echo $popup_id; ?>">


    <ul class="tab">
        <li class="tab-item active">
            <a href="#" tab-target="smsnf-configuration"><?php _e( 'Settings', 'egoi-for-wp' ); ?></a>
        </li>
        <li class="tab-item">
            <a href="#" tab-target="smsnf-appearance"><?php _e( 'Appearance', 'egoi-for-wp' ); ?></a>
        </li>
        <li class="tab-item">
            <a href="#" tab-target="smsnf-layout"><?php _e( 'Layout', 'egoi-for-wp' ); ?></a>
        </li>
    </ul>

    <div id="smsnf-configuration" class="smsnf-tab-content active">
        <div>

            <!-- SIMPLE FORM -->
            <div class="smsnf-input-group">
                <label for="form_id"><?= _e( 'Form', 'egoi-for-wp' ); ?></label>
                <select name="form_id" class="form-select " id="form_id">

                    <option value="new" selected disabled hidden><?php _e( 'Select a form...', 'egoi-for-wp' ); ?></option>
                    <?php
                    foreach (get_simple_forms() as $form){
                        echo "<option value=\"".$form->ID."\" ".selected($form->ID, $popup_data['form_id']).">".$form->post_title."</option>";
                    }
                    ?>
                </select>
            </div>
            <!-- / SIMPLE FORM -->


            <div class="smsnf-input-group">
                <label for="form_border_color"><?=__('Customize','egoi-for-wp');?></label>
                <?php wp_editor( $content, $editor_id ); ?>
            </div>

            <div class="smsnf-input-group">
                <label for="page_trigger"><?php _e('Target Page', 'egoi-for-wp'); ?></label>
                <p class="subtitle"><?php _e( 'Configure rules for target page <b>URL</b>', 'egoi-for-wp' ); ?></p>
                <select name="page_trigger_rule" class="form-select " id="page_trigger_rule">
                    <option value="contains" <?php selected($popup_data['page_trigger_rule'], 'contains'); ?>><?php _e( 'Contains', 'egoi-for-wp' ); ?></option>
                    <option value="not_contains" <?php selected($popup_data['page_trigger_rule'], 'not_contains'); ?> ><?php _e( 'Not Contains', 'egoi-for-wp' ); ?></option>
                </select>
                <input style="max-width: 400px;" id="page_trigger" type="text" value="<?php echo $popup_data['page_trigger']; ?>"
                        name="page_trigger" autocomplete="off"
                        placeholder="<?= __( "Leave empty if triggers in every page", 'egoi-for-wp' ); ?>" />
            </div>

            <div class="smsnf-input-group">
                <label for="trigger"><?=__('Popup Trigger','egoi-for-wp');?></label>
                <p class="subtitle"><?php _e( 'This will dictate the trigger rule', 'egoi-for-wp' ); ?></p>
                <select name="trigger" class="form-select " id="trigger">
                    <option value="delay" <?php selected($popup_data['trigger'], 'delay'); ?>><?php _e( 'Delay', 'egoi-for-wp' ); ?></option>
                    <option value="on_leave"  <?php selected($popup_data['trigger'], 'on_leave'); ?>><?php _e( 'On Leave', 'egoi-for-wp' ); ?></option>
                </select>
                <input name="trigger_option" id="trigger_option" value="<?php echo $popup_data['trigger_option']; ?>" placeholder="<?php _e('Time in seconds here','egoi-for-wp'); ?>" style="display: none;max-width: 400px;">
            </div>

            <div class="smsnf-input-group">
                <label for="show_until"><?=__('Popup Trigger Stop','egoi-for-wp');?></label>
                <p class="subtitle"><?php _e( 'Choose when your popup will stop showing', 'egoi-for-wp' ); ?></p>
                <select name="show_until" class="form-select " id="show_until">
                    <option value="one_time" <?php selected($popup_data['show_until'], 'one_time'); ?> ><?php _e( 'One Time', 'egoi-for-wp' ); ?></option>
                    <option value="until_submition" <?php selected($popup_data['show_until'], 'until_submition'); ?> ><?php _e( 'Until Submission', 'egoi-for-wp' ); ?></option>
                </select>
            </div>

            <div class="smsnf-input-group">
                <label for="show_logged"><?=__('Logged in Users','egoi-for-wp');?></label>
                <p class="subtitle"><?php _e( 'Do you want this popup to show in already identified users?', 'egoi-for-wp' ); ?></p>
                <select name="show_logged" class="form-select " id="show_logged">
                    <option value="yes" <?php selected($popup_data['show_logged'], 'one_time'); ?> ><?php _e( 'Yes', 'egoi-for-wp' ); ?></option>
                    <option value="no" <?php selected($popup_data['show_logged'], 'until_submition'); ?> ><?php _e( 'No', 'egoi-for-wp' ); ?></option>
                </select>
            </div>

        </div>
    </div>

    <div id="smsnf-appearance" class="smsnf-tab-content">


        <div class="smsnf-input-group">
            <label for="form-position"><?=__('Display Position','egoi-for-wp');?></label>
            <p class="subtitle"><?php _e( 'This will dictate the popup position', 'egoi-for-wp' ); ?></p>
            <div class="smsnf-adv-forms">
                <div id="form-position" class="smsnf-adv-forms-types" style="grid-template-columns: 1fr 1fr;">
                    <label>
                        <input type="radio" name="type" value="center" <?php checked($popup_data['type'], 'center')?> />
                        <div class="">
                            <p><?php _e('Center','egoi-for-wp'); ?></p>
                            <div>
                                <img src="<?= plugin_dir_url( __DIR__ ) . '../img/icon_popup.png' ?>" />
                            </div>
                        </div>
                    </label>
                    <label>
                        <input type="radio" name="type" value="rightside" <?php checked($popup_data['type'], 'rightside');?> />
                        <div>
                            <p><?php _e('Right Side','egoi-for-wp'); ?></p>
                            <div>
                                <img src="<?= plugin_dir_url( __DIR__ ) . '../img/icon_small_popup.svg' ?>" />
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- BORDER RADIUS -->
        <div class="smsnf-input-group">
            <label for="bar-position"><?= _e( 'Border Radius', 'egoi-for-wp' ); ?></label>
            <input style="max-width: 400px;border: 0 !important;" type="range" min="0" max="100" value="<?php echo $popup_data['border_radius']; ?>" class="slider" name="border_radius" id="border_radius" >
        </div>
        <!-- / BORDER RADIUS -->


        <div class="smsnf-input-group">
            <label for="background_color"><?php _e( 'Background Color', 'egoi-for-wp' ); ?></label>
            <div class="colorpicker-wrapper" style="max-width: 400px;">
                <div style="background-color:<?= esc_attr( $popup_data['background_color'] ) ?>" class="view" ></div>
                <input id="background_color" type="text" name="background_color" value="<?= esc_attr( $popup_data['background_color'] ) ?>"  autocomplete="off" />
                <p><?= _e( 'Select Color', 'egoi-for-wp' ) ?></p>
            </div>
        </div>

        <div class="smsnf-input-group">
            <label for="font_color"><?php _e( 'Font Color', 'egoi-for-wp' ); ?></label>
            <div class="colorpicker-wrapper" style="max-width: 400px;">
                <div style="background-color:<?= esc_attr( $popup_data['font_color'] ) ?>" class="view" ></div>
                <input id="font_color" type="text" name="font_color" value="<?= esc_attr( $popup_data['font_color'] ) ?>"  autocomplete="off" />
                <p><?= _e( 'Select Color', 'egoi-for-wp' ) ?></p>
            </div>
        </div>

        <div class="smsnf-input-group">
            <label for="form_border_color"><?=__('Custom Css','egoi-for-wp');?></label>
            <?php
            do_action( 'wp_enqueue_code_editor', array('type' => 'text/css') );
            wp_enqueue_code_editor( array( 'type' => 'text/css' ) );

            ?>
            <fieldset>
                <textarea id="code_editor_page_css" rows="5" name="custom_css" class="widefat textarea"><?php echo wp_unslash( $popup_data['custom_css'] ); ?></textarea>
            </fieldset>
        </div>

    </div>

    <div id="smsnf-layout" class="smsnf-tab-content">

        <div class="smsnf-input-group">
            <label for="popup-layout"><?=__('Display Position','egoi-for-wp');?></label>
            <p class="subtitle"><?php _e( 'You can choose to divide the popup with an image', 'egoi-for-wp' ); ?></p>
            <div class="smsnf-adv-forms">
                <div id="popup-layout" class="smsnf-adv-forms-types" style="grid-template-columns: 1fr 1fr 1fr;">
                    <label>
                        <input type="radio" name="popup_layout" value="simple" <?php checked($popup_data['popup_layout'], 'simple')?> />
                        <div class="">
                            <p><?php _e('Simple','egoi-for-wp'); ?></p>
                            <div>
                                <img src="<?= plugin_dir_url( __DIR__ ) . '../img/icon_popup.png' ?>" />
                            </div>
                        </div>
                    </label>
                    <label>
                        <input type="radio" name="popup_layout" value="left_image" <?php checked($popup_data['popup_layout'], 'left_image')?> />
                        <div class="">
                            <p><?php _e('Left Image','egoi-for-wp'); ?></p>
                            <div>
                                <img src="<?= plugin_dir_url( __DIR__ ) . '../img/icon_left_image_popup.svg' ?>" />
                            </div>
                        </div>
                    </label>
                    <label>
                        <input type="radio" name="popup_layout" value="right_image" <?php checked($popup_data['popup_layout'], 'right_image');?> />
                        <div>
                            <p><?php _e('Right Image','egoi-for-wp'); ?></p>
                            <div>
                                <img src="<?= plugin_dir_url( __DIR__ ) . '../img/icon_right_image_popup.svg' ?>" />
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <div class="smsnf-input-group" class="select-image">
            <label for="side_image"><?=__('Side Image','egoi-for-wp');?></label>
            <p class="subtitle"><?php _e( 'Pick an image from your gallery', 'egoi-for-wp' ); ?></p>
            <div>
                <div class='image-preview-wrapper egoi-image-selector-preview' style="background-image: url(<?php echo wp_get_attachment_url( $popup_data['side_image'] ); ?>);">
                    <?php if(empty($popup_data['side_image'] )){ ?>
                        <i class="far fa-image" aria-hidden="true"></i>
                        <span><?php _e('Upload Image','egoi-for-wp'); ?></span>
                    <?php } ?>
                </div>
            </div>

            <input type='hidden' name='side_image' id='side_image' value='<?php echo $popup_data['side_image']; ?>'>
        </div>


        <div class="smsnf-input-group">
            <label for="form_orientation"><?=__('Form Orientation','egoi-for-wp');?></label>
            <p class="subtitle"><?php _e( 'Disable this if you want to use customized setting', 'egoi-for-wp' ); ?></p>
            <select name="form_orientation" class="form-select " id="form_orientation">
                <option value="off" <?php selected($popup_data['form_orientation'], 'off'); ?> ><?php _e( 'Disabled', 'egoi-for-wp' ); ?></option>
                <option value="vertical" <?php selected($popup_data['form_orientation'], 'vertical'); ?> ><?php _e( 'Vertical', 'egoi-for-wp' ); ?></option>
                <option value="horizontal" <?php selected($popup_data['form_orientation'], 'horizontal'); ?> ><?php _e( 'Horizontal', 'egoi-for-wp' ); ?></option>
            </select>
        </div>

        <div class="smsnf-input-group">
            <label for="max_width"><?php _e('Popup Max Width', 'egoi-for-wp'); ?></label>
            <p class="subtitle"><?php _e( 'Configure rules for target page', 'egoi-for-wp' ); ?></p>
            <input style="max-width: 400px;" id="max_width" type="text"
                   value="<?php echo $popup_data['max_width']; ?>"
                   name="max_width" autocomplete="off"
                   placeholder="<?= __( "write in px, vh or %", 'egoi-for-wp' ); ?>" />
        </div>

    </div>
    <div class="egoi-undertable-button-wrapper" style="bottom: 0;position: absolute;right: 30px;">
        <div class="smsnf-input-group">
            <input type="submit" id="sava_changes_popup" value="<?php echo $popup_id=='new'?__('Create', 'egoi-for-wp'):__('Save Changes', 'egoi-for-wp');?>" />
        </div>
    </div>
</form>

<style>
    .CodeMirror-line {
        margin-left: 45px !important;
    }
</style>

<script>
    jQuery(document).ready(function($) {
        const TRIGGER_WITH_OPTION = ['delay'];

        //form logic
        $('#trigger').change(function(){
            checkTriggerOption();
        });

        function checkTriggerOption() {
            if(TRIGGER_WITH_OPTION.includes($('#trigger').val())){
                $('#trigger_option').show();
            }else{
                $('#trigger_option').hide();
            }
        }

        checkTriggerOption();

        if( $('#code_editor_page_css').length ) {
            if(wp.codeEditor == 'undefined' || wp.codeEditor == null){return;}
            var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
            editorSettings.codemirror = _.extend(
                {},
                editorSettings.codemirror,
                {
                    mode: 'css',
                }
            );
            var editor = wp.codeEditor.initialize( $('#code_editor_page_css'), editorSettings );
        }


        /////image upload


        var file_frame;
        var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
        var set_to_post_id = 1; // Set this

        $('.egoi-image-selector-preview').live('click', function( event ){

            event.preventDefault();

            // If the media frame already exists, reopen it.
            if ( file_frame ) {
                // Set the post ID to what we want
                file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
                // Open frame
                file_frame.open();
                return;
            } else {
                // Set the wp.media post id so the uploader grabs the ID we want when initialised
                wp.media.model.settings.post.id = set_to_post_id;
            }

            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                title: jQuery( this ).data( 'uploader_title' ),
                button: {
                    text: jQuery( this ).data( 'uploader_button_text' ),
                },
                multiple: false  // Set to true to allow multiple files to be selected
            });

            // When an image is selected, run a callback.
            file_frame.on( 'select', function() {

                attachment = file_frame.state().get('selection').first().toJSON();

                // Do something with attachment.id and/or attachment.url here
                $( '.egoi-image-selector-preview' ).empty();
                $( '.egoi-image-selector-preview' ).css( 'background-image', `url(${attachment.url})` );
                $( '#side_image' ).val( attachment.id );

                wp.media.model.settings.post.id = wp_media_post_id;
            });

            // Finally, open the modal
            file_frame.open();

            // Restore the main ID when the add media button is pressed
            $('a.add_media').on('click', function() {
                wp.media.model.settings.post.id = wp_media_post_id;
            });
        });
    });
</script>