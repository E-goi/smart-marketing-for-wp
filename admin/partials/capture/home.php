<?php
require_once(plugin_dir_path( __FILE__ ) . '../../../includes/class-egoi-for-wp-popup.php');
$simple_forms = get_simple_forms();
$adv_forms = get_adv_forms();
$popups = EgoiPopUp::getSavedPopUps();
?>

<h3><?= _e('Your Simple Forms list', 'egoi-for-wp') ?></h3>

<?php if (count($simple_forms) == 0) : ?>
    <p><?= _e('No simple forms', 'egoi-for-wp') ?></p>
<?php else : ?>
    <table class="smsnf-table">
        <thead>
            <tr>
                <th>Shortcode</th>
                <th>ID</th>
                <th><?= _e('Title', 'egoi-for-wp') ?></th>
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
                                data-clipboard-text="<?= esc_html($shortcode) ?>"><?= $shortcode ?></div>
                            <div class="tooltip tooltip-right shortcode -copy"
                                    data-tooltip="<?= _e('Copy', 'egoi-for-wp') ?>"
                                    data-before="<?php _e('Copy', 'egoi-for-wp');?>"
                                    data-after="<?php _e('Copied', 'egoi-for-wp');?>"
                                    data-clipboard-text="<?= esc_html($shortcode) ?>"
                                >
                                <?= _e('Copy', 'egoi-for-wp') ?>
                            </div>
                        </div>
                    </td>
                    <td style="width:1%"><?= $id ?></td>
                    <td><?= $title ?></td>
                    <!--<td class="<?= $enable ? '-enable' : '-disable'?>"><?= $enable ? 'Ativo' : 'Inativo' ?></td>-->
                    <td style="width:1%">
                        <a class="smsnf-btn" href="<?= $edit_link ?>"><?php _e('Edit', 'egoi-for-wp');?></a>
                        <a class="smsnf-btn delete-adv-form" href="<?= $delete_link ?>"><?php _e('Delete', 'egoi-for-wp');?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<h3><?= _e('Your Advanced Forms list', 'egoi-for-wp') ?></h3>

<?php if (count($adv_forms) == 0) : ?>
    <p><?= _e('No advanced forms', 'egoi-for-wp') ?></p>
<?php else : ?>
    <table class="smsnf-table">
        <thead>
            <tr>
                <th style="width:1%">Shortcode</th>
                <th style="width:1%">ID</th>
                <th style="width:1%"><?= _e('Type', 'egoi-for-wp') ?></th>
                <th><?= _e('Title', 'egoi-for-wp') ?></th>
                <th style="width:1%"><?= _e('State', 'egoi-for-wp') ?></th>
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
                                data-clipboard-text="<?= $form['shortcode'] ?>"><?= $form['shortcode'] ?></div>
                            <div class="tooltip tooltip-right shortcode -copy"
                                    data-tooltip="<?= _e('Copy', 'egoi-for-wp') ?>"
                                    data-before="<?php _e('Copy', 'egoi-for-wp');?>"
                                    data-after="<?php _e('Copied', 'egoi-for-wp');?>"
                                    data-clipboard-text="<?= $form['shortcode'] ?>"
                                >
                                <?= _e('Copy', 'egoi-for-wp') ?>
                            </div>
                        </div>
                    </td>
                    <td><?= $form['id'] ?></td>
                    <td><?= $form['type'] ?></td>
                    <td><?= $form['title'] ?></td>
                    <td class="<?= $form['state'] ? '-enable' : '-disable'?>"><?= $form['state'] ? 'Ativo' : 'Inativo' ?></td>
                    <td>
                        <a class="smsnf-btn" href="<?= $edit_link ?>"><?php _e('Edit', 'egoi-for-wp');?></a>
                        <a class="smsnf-btn delete-adv-form" href="<?= $delete_link ?>"><?php _e('Delete', 'egoi-for-wp');?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<h3><?= _e('Your Popup List', 'egoi-for-wp') ?></h3>

<?php if (count($popups) == 0) : ?>
    <p><?= _e('No Popup yet', 'egoi-for-wp') ?></p>
<?php else : ?>
    <table class="smsnf-table">
        <thead>
        <tr>
            <th style="width:1%">ID</th>
            <th style="width:1%"><?= _e('Type', 'egoi-for-wp') ?></th>
            <th style="width:98%"><?= _e('Title', 'egoi-for-wp') ?></th>
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
                <td><?= $form ?></td>
                <td><?= __('Popup','egoi-for-wp'); ?></td>
                <td><?= $popup_data['title']; ?></td>
                <td>
                    <a class="smsnf-btn" href="<?= $edit_link ?>"><?php _e('Edit', 'egoi-for-wp');?></a>
                    <a class="smsnf-btn delete-adv-form" href="<?= $delete_link ?>"><?php _e('Delete', 'egoi-for-wp');?></a>
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
