<?php if ( ! defined( 'ABSPATH' ) ) die();

// cria/atualiza o formulário ("action" do form)
if (isset($_POST['action']) && ($_POST['action'])) {
    $post = $_POST;
    $post['egoi_form_sync']['form_content'] = htmlentities($_POST['egoi_form_sync']['form_content']);
    $egoiform = $post['egoiform'];

    update_option($egoiform, $post);
    
    echo get_notification(__('Saved Form', 'egoi-for-wp'), __('Form saved with success.', 'egoi-for-wp'));
}

$form_id = $_GET['form'];
$form_type = $_GET['type'];

include plugin_dir_path( __DIR__ ) . 'egoi-for-wp-admin-shortcodes.php';
$FORM_OPTION = get_optionsform($form_id);

$opt = get_option($FORM_OPTION);

$is_iframe = $_GET['type'] == 'iframe';
?>

<button id="smsnf-help-btn" class="smsnf-help-btn"><?php _e('Help?', 'egoi-for-wp') ?></button>

<div class="smsnf-adv-forms">
    <h3><?php _e('Select form type', 'egoi-for-wp') ?></h3>
    <form id="adv-forms-select-type" method="get" action="">
        <input type="hidden" name="page" value="egoi-4-wp-form">
        <input type="hidden" name="sub" value="adv-forms">
        <input type="hidden" name="form" value="<?= $form_id?>">

        <div id="smsnf-adv-forms-types" class="smsnf-adv-forms-types">
            <label>
                <input type="radio" name="type" value="popup" <?php checked($form_type, 'popup')?> />
                <div class="">
                    <p>Pop-up</p>
                    <div>
                        <img src="<?= plugin_dir_url( __DIR__ ) . '../img/icon_popup.png' ?>" />
                    </div>
                </div>
            </label>
            <label>
                <input type="radio" name="type" value="iframe" <?php checked($form_type, 'iframe');?> />
                <div>
                    <p>iframe</p>
                    <div>
                        <img src="<?= plugin_dir_url( __DIR__ ) . '../img/icon_iframe.png' ?>" />
                    </div>
                </div>
            </label>
            <label>
                <input type="radio" name="type" value="html" <?php checked($form_type, 'html');?> />
                <div>
                    <p>Advanced HTML</p>
                    <div>
                        <img src="<?= plugin_dir_url( __DIR__ ) . '../img/icon_html.png' ?>" />
                    </div>
                </div>
            </label>
        </div>
    </form>

    <?php if (isset($form_type) && in_array($form_type, array('popup', 'iframe', 'html'))) : ?>
        <ul class="tab">
            <li class="tab-item active">
                <a href="#" tab-target="smsnf-adv-forms-options"><?php _e('Options', 'egoi-for-wp');?></a>
            </li>
            <li class="tab-item">
                <a href="#" tab-target="smsnf-adv-forms-custom"><?php _e('Customizing the form', 'egoi-for-wp');?></a>
            </li>
        </ul>

        <form id="smsnf-adv-forms-form" method="post" action="">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="egoi_form_sync[form_id]" value="<?php echo $form_id;?>">
            <input type="hidden" name="egoiform" value="<?php echo 'egoi_form_sync_'.$form_id;?>">
            <input type="hidden" name="egoi_form_sync[egoi]" value="<?php echo $_GET['type'];?>">
            <div id="smsnf-adv-forms-options" class="smsnf-tab-content smsnf-grid active">
                <div>
                    <!-- TÍTULO E MOSTRAR TÍTULO -->
                    <div class="smsnf-input-group">
                        <label for="form_name"><?php _e('Form title', 'egoi-for-wp'); ?></label>
                        <div class="form-group switch-right switch-yes-no">
                            <label class="form-switch small">
                                <input type="checkbox" name="egoi_form_sync[show_title]" value="1" <?php checked($opt['egoi_form_sync']['show_title']) ?>>
                                <i class="form-icon"></i> <?php _e( 'Show Title', 'egoi-for-wp' ); ?>
                            </label>
                        </div>
                        <input id="form_name" type="text" name="egoi_form_sync[form_name]" value="<?= $opt['egoi_form_sync']['form_name'];?>" placeholder="<?=__( "Write here the title of your form", 'egoi-for-wp' )?>" autocomplete="off" />
                    </div>
                    <!-- / TÍTULO E MOSTRAR TÍTULO -->
                    <?php if (!$is_iframe) : ?>
                        <!-- CÓDIGO HTML -->
                        <div class="smsnf-input-group">
                            <?php
if($_GET['type'] == 'popup') {
                                    $placeholder = __( 'Paste here the Pop-Up window code of your E-goi form', 'egoi-for-wp' );
                                    $label = __('Código da janela Pop-up', 'egoi-for-wp');
                                } else {
                                    $placeholder = __( 'Paste here the Advanced HTML code of your E-goi form', 'egoi-for-wp' );
                                    $label = __('Advanced HTML code', 'egoi-for-wp');
                                }
                                $content = stripslashes($opt['egoi_form_sync']['form_content']);
                            ?>
                            <label for="form_code"><?= $label ?></label>
                            <textarea id="form_code" rows="11" placeholder="<?= $placeholder ?>" name="egoi_form_sync[form_content]"><?=$content?></textarea>
                        </div>
                        <!-- / CÓDIGO HTML -->
                    <?php endif; ?>
                    <?php if ($is_iframe) :
                        $egoi_list_id = $opt['egoi_form_sync']['list'];
                        $egoi_form_id = $opt['egoi_form_sync']['form_content'];
                        ?>
                        <!-- LISTA DE SUBSCRITORES -->
                        <?php get_list_html($egoi_list_id, 'egoi_form_sync[list]'); ?>
                        <!-- / LISTA DE SUBSCRITORES -->
                        <!-- FORMULÁRIO -->
                        <?php get_form_html($egoi_form_id, 'egoi_form_sync[form_content]', empty($egoi_list_id)) ?>
                        <!-- / FORMULÁRIO -->
                    <?php endif; ?>
                </div>
                <div>
                    <!-- ATIVAR FORM -->
                    <div class="smsnf-input-group">
                        <label for="form_ative"><?php _e( 'Enable Form', 'egoi-for-wp' ); ?></label>
                        <p class="subtitle"><?php _e( 'Select "yes" to enable this form.', 'egoi-for-wp' ); ?></p>
                        <div class="form-group switch-yes-no">
                            <label class="form-switch">
                                <input id="form_ative" type="checkbox" name="egoi_form_sync[enabled]" value="1" <?php checked($opt['egoi_form_sync']['enabled']) ?>>
                                <i class="form-icon"></i><div class="yes"><?php _e( 'Yes', 'egoi-for-wp' ); ?></div><div class="no"><?php _e( 'No', 'egoi-for-wp' ); ?></div>
                            </label>
                        </div>
                    </div>
                    <!-- / ATIVAR FORM -->

                    <?php if (!empty($opt['egoi_form_sync']['form_id'])) : ?>
                    <!-- SHORTCODE -->
                    <div class="smsnf-input-group">
                        <label for="smsnf-af-shortcode">Shortcode</label>
                        <div
                            class="tooltip shortcode -copy"
                            type="text"
                            data-clipboard-text="<?= "[egoi_form_sync_$form_id]" ?>"
                            data-before="<?php _e('Click to copy', 'egoi-for-wp');?>"
                            data-after="<?php _e('Copied', 'egoi-for-wp');?>"
                            data-tooltip="<?php _e('Click to copy', 'egoi-for-wp');?>"
                            ><?= "[egoi_form_sync_$form_id]" ?></div>
                        <p class="subtitle"><?php _e('Use this shortcode to display this form inside of your site or blog', 'egoi-for-wp');?></p>
                    </div>
                    <?php endif; ?>
                    <!-- / SHORTCODE -->
                </div>
            </div>
            <div id="smsnf-adv-forms-custom" class="smsnf-tab-content smsnf-grid">
                <div clss="inputs">
                    <?php
$border = $opt['egoi_form_sync']['border'] ? $opt['egoi_form_sync']['border'] : 0;
                        $color = $opt['egoi_form_sync']['border_color'] ? $opt['egoi_form_sync']['border_color'] : '#000000';
                        $width = isset($opt['egoi_form_sync']['width']) ? str_replace('px', '', $opt['egoi_form_sync']['width']) : '300';
                        $height = isset($opt['egoi_form_sync']['height']) ? str_replace('px', '', $opt['egoi_form_sync']['height']) : '250';
                    ?>
                    <!-- CONTORNO -->
                    <div class="smsnf-input-group">
                        <label for="form_border">Contorno do Formulário <small>(px)</small></label>
                        <input  id="form_border" type="number" name="egoi_form_sync[border]" value="<?= $border ?>" min="0" autocomplete="off" />
                    </div>
                    <!-- / CONTORNO -->
                    <!-- COR CONTORNO -->
                    <!--<div class="smsnf-input-group color-picker">
                        <label for="form_border_color">Cor do contorno</label>
                        <input  id="form_border_color" class="color" type="text" name="egoi_form_sync[border_color]" value="<?= $color ?>" autocomplete="off" />
                    </div>-->
                    <div class="smsnf-input-group">
                        <label for="form_border_color"><?=__('Border Color','egoi-for-wp');?></label>
                        <div class="colorpicker-wrapper">
                            <div style="background-color:<?= $color ?>" class="view" ></div>
                            <input id="form_border_color" type="text" name="egoi_form_sync[border_color]" value="<?= $color ?>"  autocomplete="off" />
                            <p><?= _e( 'Select Color', 'egoi-for-wp' ) ?></p>
                        </div>
                    </div>
                    <!-- / COR CONTORNO -->
                    <!-- LARGURA -->
                    <div class="smsnf-input-group">
                        <label for="form_width"><?=__('Form Width','egoi-for-wp');?> <small>(px)</small></label>
                        <input  id="form_width" type="number" name="egoi_form_sync[width]" value="<?= $width ?>" min="200" autocomplete="off" />
                    </div>
                    <!-- / LARGURA -->
                    <!-- ALTURA -->
                    <div class="smsnf-input-group">
                        <label for="form_height"><?=__('Form Height','egoi-for-wp');?> <small>(px)</small></label>
                        <input  id="form_height" type="number" name="egoi_form_sync[height]" value="<?= $height ?>" min="100" autocomplete="off" />
                    </div>
                    <!-- / ALTURA -->
                </div>
                <div id="preview" class="preview">
                    <table>
                        <tr>
                            <td class="width">
                                <div class="arrows arrows-h">
                                    <div class="left"></div>
                                    <div class="center"></div>
                                    <div class="right"></div>
                                </div>
                                <span></span>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><div id="form-preview"></div></td>
                            <td class="height">
                                <div class="arrows arrows-v">
                                    <div class="top"></div>
                                    <div class="center"></div>
                                    <div class="bottom"></div>
                                </div>
                                <span></span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="smsnf-input-group">
                <input type="submit" value="GUARDAR ALTERAÇÕES" />
            </div>
        </form>
    <?php endif; ?>
</div>

<section id="smsnf-help" class="help">
    <div class="close-btn">
        <svg version="1.1" id="Camada_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 19.1 20.2" style="enable-background:new 0 0 19.1 20.2;" xml:space="preserve">
            <g>
                <path d="M10.9,10.1l7.9-8.3c0.4-0.4,0.4-1,0-1.4c-0.4-0.4-1-0.4-1.4,0L9.6,8.6L1.8,0.4C1.4,0,0.8,0,0.4,0.4s-0.4,1,0,1.4l7.8,8.3
                    l-7.6,8c-0.4,0.4-0.4,1,0,1.4c0.2,0.2,0.4,0.3,0.7,0.3c0.3,0,0.5-0.1,0.7-0.3l7.5-7.9l7.8,8.2c0.2,0.2,0.5,0.3,0.7,0.3
                    c0.2,0,0.5-0.1,0.7-0.3c0.4-0.4,0.4-1,0-1.4L10.9,10.1z"/>
            </g>
        </svg>
    </div>
    <p><?=__('How to integrate the form in a post or page','egoi-for-wp');?></p>
    <hr />
    <ol>
        <li><?=__('Go in your E-goi\'s account in the tab/menu Forms.','egoi-for-wp');?></li>
        <li><?=__('Choose the desired form.','egoi-for-wp');?></li>
        <li><?=__('Select the Save button and choose "Publish".','egoi-for-wp');?></li>
        <li><?=__('Add another Post.','egoi-for-wp');?></li>
        <li><?=__('Get the advanced HTML code.','egoi-for-wp');?></li>
        <li><?=__('Paste the code in the plugin advanced form.','egoi-for-wp');?></li>
        <li><?=__('Save all changes.','egoi-for-wp');?></li>
    </ol>
</section>

<div id="smsnf-confirm-modal" class="modal modal-sm" id="modal-id" style="width: 100% !important; ">
    <a href="#close" class="modal-overlay" aria-label="Close"></a>
    <div class="modal-container">
        <div class="modal-header">
            <a href="#close" class="btn btn-clear float-right" aria-label="Close"></a>
            <h2 class="modal-title"><?=__('Change Form type.','egoi-for-wp');?></h2>
        </div>
    <div class="modal-body">
        <div class="content">
            <?=__('Attention! If you change your form you will lose the settings.','egoi-for-wp');?>
        </div>
    </div>
        <div class="modal-footer">
            <button id="confirm-btn" class="smsnf-btn primary"><?=__('Confirm','egoi-for-wp');?></button>
            <a href="#close" class="smsnf-btn"><?=__('Cancel','egoi-for-wp');?></a>
        </div>
    </div>
</div>