<?php
require_once(plugin_dir_path( __FILE__ ) . '../../../includes/class-egoi-for-wp-popup.php');
$simple_forms = get_simple_forms();
$adv_forms = get_adv_forms();
$popups = EgoiPopUp::getSavedPopUps();
?>

<h3><?php _e('Your Simple Forms list', 'egoi-for-wp') ?></h3>

<?php if (count($simple_forms) == 0) : ?>
    <p><?php _e('No simple forms', 'egoi-for-wp') ?></p>
<?php else : ?>
    <table class="smsnf-table">
        <thead>
            <tr>
                <th>Shortcode</th>
                <th>ID</th>
                <th><?php _e('Title', 'egoi-for-wp') ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($simple_forms as $simple_form) : ?>
                <?php
$id = $simple_form->ID;
                    $shortcode = sprintf('[egoi-simple-form id="%d"]', $id);
                    $enable = $simple_form->post_status == 'publish';
                    $title = $simple_form->post_title;
                    $edit_link = sprintf("?page=egoi-4-wp-form&sub=simple-forms&edit_simple_form=1&form=%d", $id);
                    $delete_link = sprintf("?page=egoi-4-wp-form&del_simple_form=%d", $id);
                ?>
                <tr>
                    <td style="width:1%">
                        <div class="shortcode">
                            <div class="shortcode"
                                data-clipboard-text="<?php echo  esc_html($shortcode) ?>"><?php echo  $shortcode ?></div>
                            <div class="eg_tooltip tooltip-right shortcode -copy"
                                    data-tooltip="<?php _e('Copy', 'egoi-for-wp') ?>"
                                    data-before="<?php _e('Copy', 'egoi-for-wp');?>"
                                    data-after="<?php _e('Copied', 'egoi-for-wp');?>"
                                    data-clipboard-text="<?php echo  esc_html($shortcode) ?>"
                                >
                                <?php _e('Copy', 'egoi-for-wp') ?>
                            </div>
                        </div>
                    </td>
                    <td style="width:1%"><?php echo  $id ?></td>
                    <td><?php echo  $title ?></td>
                    <!--<td class="<?php echo  $enable ? '-enable' : '-disable'?>"><?php echo  $enable ? 'Ativo' : 'Inativo' ?></td>-->
                    <td style="width:1%">
                        <a class="smsnf-btn" href="<?php echo  $edit_link ?>"><?php _e('Edit', 'egoi-for-wp');?></a>
                        <a class="smsnf-btn delete-adv-form" href="<?php echo  $delete_link ?>"><?php _e('Delete', 'egoi-for-wp');?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<h3><?php _e('Your Advanced Forms list', 'egoi-for-wp') ?></h3>

<?php if (count($adv_forms) == 0) : ?>
    <p><?php _e('No advanced forms', 'egoi-for-wp') ?></p>
<?php else : ?>
    <table class="smsnf-table">
        <thead>
            <tr>
                <th style="width:1%">Shortcode</th>
                <th style="width:1%">ID</th>
                <th style="width:1%"><?php _e('Type', 'egoi-for-wp') ?></th>
                <th><?php _e('Title', 'egoi-for-wp') ?></th>
                <th style="width:1%"><?php _e('State', 'egoi-for-wp') ?></th>
                <th style="width:1%"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($adv_forms as $form) : ?>
                <?php
$edit_link = sprintf("?page=egoi-4-wp-form&sub=adv-forms&form=%d&type=%s", $form['id'], $form['type']);
                    $delete_link = sprintf("?page=egoi-4-wp-form&del_adv_form=%d", $form['id']);
                ?>
                <tr>
                    <td>
                        <div class="shortcode">
                            <div class="shortcode"
                                data-clipboard-text="<?php echo  $form['shortcode'] ?>"><?php echo  $form['shortcode'] ?></div>
                            <div class="eg_tooltip tooltip-right shortcode -copy"
                                    data-tooltip="<?php _e('Copy', 'egoi-for-wp') ?>"
                                    data-before="<?php _e('Copy', 'egoi-for-wp');?>"
                                    data-after="<?php _e('Copied', 'egoi-for-wp');?>"
                                    data-clipboard-text="<?php echo  $form['shortcode'] ?>"
                                >
                                <?php _e('Copy', 'egoi-for-wp') ?>
                            </div>
                        </div>
                    </td>
                    <td><?php echo  $form['id'] ?></td>
                    <td><?php echo  $form['type'] ?></td>
                    <td><?php echo  $form['title'] ?></td>
                    <td class="<?php echo  $form['state'] ? '-enable' : '-disable'?>"><?php echo  $form['state'] ? 'Ativo' : 'Inativo' ?></td>
                    <td>
                        <a class="smsnf-btn" href="<?php echo  $edit_link ?>"><?php _e('Edit', 'egoi-for-wp');?></a>
                        <a class="smsnf-btn delete-adv-form" href="<?php echo  $delete_link ?>"><?php _e('Delete', 'egoi-for-wp');?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<h3><?php _e('Your Popup List', 'egoi-for-wp') ?></h3>

<?php if (count($popups) == 0) : ?>
    <p><?php _e('No Popup yet', 'egoi-for-wp') ?></p>
<?php else : ?>
    <table class="smsnf-table">
        <thead>
        <tr>
            <th style="width:1%">ID</th>
            <th style="width:1%"><?php _e('Type', 'egoi-for-wp') ?></th>
            <th style="width:98%"><?php _e('Title', 'egoi-for-wp') ?></th>
            <th style="width:1%"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($popups as $form) : ?>
            <?php
            $popup_data = (new EgoiPopUp($form))->getPopupSavedData();

            $edit_link = sprintf("?page=egoi-4-wp-form&sub=popup&popup_id=%d", $form);
            $delete_link = sprintf("?page=egoi-4-wp-form&del_popup=%d", $form);
            ?>
            <tr <?php echo $form == $_GET['highlight']?'class="pulse-highlight"':'' ?>>
                <td><?php echo  $form ?></td>
                <td><?php _e('Popup','egoi-for-wp'); ?></td>
                <td><?php echo  $popup_data['title']; ?></td>
                <td>
                    <a class="smsnf-btn" href="<?php echo  $edit_link ?>"><?php _e('Edit', 'egoi-for-wp');?></a>
                    <a class="smsnf-btn delete-adv-form" href="<?php echo  $delete_link ?>"><?php _e('Delete', 'egoi-for-wp');?></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<script>

    jQuery(document).ready(function($) {
        $(".pulse-highlight").each(function( index ) {
            $(this).css('filter', 'none')
        });

    });
</script>
