<?php

if ( ! defined( 'ABSPATH' ) ) {
    die();
}

if(isset($_POST['action'])){
    $code = wp_generate_password(16, false);

    $type = filter_var($_POST['type'], FILTER_SANITIZE_STRING);
    $categories = $_POST[substr($type,0,-1)."_categories"];
    $categories_exclude = $_POST[substr($type,0,-1)."_categories_exclude"];
    $tags = $_POST[substr($type,0,-1)."_tags"];
    $tags_exclude = $_POST[substr($type,0,-1)."_tags_exclude"];

    $rssfeed = array(
        'code' => $code,
        'name' => filter_var($_POST['name'], FILTER_SANITIZE_STRING),
        'max_characters' => filter_var($_POST['max_characters'], FILTER_SANITIZE_NUMBER_INT),
        'type' => filter_var($_POST['type'], FILTER_SANITIZE_STRING),
        'categories' => $categories,
        'categories_exclude' => $categories_exclude,
        'tags' => $tags,
        'tags_exclude' => $tags_exclude
    );
    add_option('egoi_rssfeed_'.$code, $rssfeed);

    ?>
    <div class="e-goi-notice updated notice is-dismissible">
        <p><?php _e('RSS Feed saved!', 'egoi-for-wp'); ?></p>
    </div>
    <?php
}

if (isset($_GET['del'])) {
    delete_option($_GET['del']);
}

function prepare_url($complement = '') {
    if (strpos($_SERVER['REQUEST_URI'], '&del=')) {
        $url = substr($_SERVER['REQUEST_URI'], 0, -34);
    } else if (strpos($_SERVER['REQUEST_URI'], '&add=')) {
        $url = substr($_SERVER['REQUEST_URI'], 0, -6);
    } else {
        $url = $_SERVER['REQUEST_URI'];
    }
    return $url.$complement;
}

?>
<!-- head -->
<h1 class="logo">Smart Marketing - <?php _e( 'RSS Feed', 'egoi-for-wp' ); ?></h1>
<p class="breadcrumbs">
    <span class="prefix"><?php echo __( 'You are here: ', 'egoi-for-wp' ); ?></span>
    <strong>Smart Marketing</a> &rsaquo;
        <span class="current-crumb"><?php _e( 'RSS Feed', 'egoi-for-wp' ); ?></strong></span>
</p>
<hr/>

<?php if (!isset($_GET['add'])) { ?>

    <div class="wrap-content wrap-content--list">

        <div class="e-goi-account-list__title">
            <?php echo __('RSS Feed', 'egoi-for-wp'); ?>
        </div>
        <table border='0' class="widefat striped">
            <thead>
                <tr>
                    <th><?php _e('Name', 'egoi-for-wp'); ?></th>
                    <th><?php _e('Type', 'egoi-for-wp'); ?></th>
                    <th><?php _e('URL', 'egoi-for-wp'); ?> </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php
                global $wpdb;
                $table = $wpdb->prefix."options";
                $options = $wpdb->get_results( " SELECT * FROM ".$table." WHERE option_name LIKE 'egoi_rssfeed_%' ");
                foreach ($options as $option) {
                    $feed = get_option($option->option_name);
            ?>

                    <!-- PopUp ALERT Delete Form -->
                    <div class="cd-popup cd-popup-del-form" data-id-form="<?=$option->option_name?>" data-type-form="rss-feed" role="alert">
                        <div class="cd-popup-container">
                            <p><b><?php echo __('Are you sure you want to delete this RSS Feed?', 'egoi-for-wp');?> </b></p>
                            <ul class="cd-buttons">
                                <li>
                                    <a href="<?php echo prepare_url('&del='.$option->option_name);?>"><?php _e('Confirm', 'egoi-for-wp'); ?></a>
                                </li>
                                <li>
                                    <a class="cd-popup-close-btn" href="#0"><?php _e('Cancel', 'egoi-for-wp'); ?></a>
                                </li>
                            </ul>
                        </div> <!-- cd-popup-container -->
                    </div> <!-- PopUp ALERT Delete Form -->

                <tr>
                    <td><?=$feed['name']?></td>
                    <td><?=$feed['type']?></td>
                    <td></td>
                    <td>
                        <a class="cd-popup-trigger-del" data-id-form="<?=$option->option_name?>" data-type-form="rss-feed" href="#"><?php _e('Delete', 'egoi-for-wp');?></a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <p>
            <a href="<?php echo prepare_url('&add=1');?>" class='button-primary'><?php _e('Create RSS Feed +', 'egoi-for-wp');?></a>
        </p>
    </div>

<?php } else if (isset($_GET['add'])) { ?>

    <?php
        $args['hide_empty'] = false;

        $args['taxonomy'] = 'category';
        $post_categories = get_terms($args);

        $args['taxonomy'] = 'post_tag';
        $post_tags = get_terms($args);

        $args['taxonomy'] = 'product_cat';
        $product_categories = get_terms($args);

        $args['taxonomy'] = 'product_tag';
        $product_tags = get_terms($args);
    ?>

    <div class="wrap egoi4wp-settings" id="tab-forms">
        <div class="row">
            <div class="nav-tab-forms-options-mt">
                <form id="egoi_simple_form" method="post" action="#">
                    <?php
                    settings_fields( Egoi_For_Wp_Admin::OPTION_NAME );
                    settings_errors();
                    ?>
                    <div>
                        <p>
                            <a href="<?php echo prepare_url();?>" class='button button--custom'>
                                <i class="fas fa-arrow-left"></i>
                                <?php _e('Back', 'egoi-for-wp');?>
                            </a>
                        </p>

                        <table class="form-table" style="table-layout: fixed;">
                            <tr valign="top">
                                <th scope="row">
                                    <label><?php _e( 'Name', 'egoi-for-wp' ); ?></label>
                                </th>
                                <td>
                                    <input type="text" style="width:450px;" id="name" name="name" placeholder="<?php _e( 'Choose a name for your new RSS Feed', 'egoi-for-wp' ); ?>" value="" required />
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label><?php _e( 'Maximum of characters', 'egoi-for-wp' ); ?></label>
                                </th>
                                <td>
                                    <input type="text" style="width:450px;" id="max_characters" name="max_characters" placeholder="<?php _e( 'Define a maximum of characters for your RSS Feed', 'egoi-for-wp' ); ?>" value="" required />
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label><?php _e( 'Type', 'egoi-for-wp' ); ?></label>
                                </th>
                                <td>
                                    <label>
                                        <input type="radio" name="type" value="posts" required /> <?php _e( 'Posts' ); ?>
                                    </label>
                                    <label>
                                        <input type="radio" name="type" value="products" /> <?php _e( 'Products' ); ?>
                                    </label>
                                    <p class="help"><?php _e( 'You can chose between Posts and Products to fill your RSS Feed', 'egoi-for-wp' ); ?></p>
                                </td>
                            </tr>

                            <tr valign="top" >
                                <th scope="row">
                                    <label><?php _e( 'Categories', 'egoi-for-wp' ); ?></label>
                                </th>
                                <td class="post_cats_tags">
                                    <?php foreach ($post_categories as $category) { ?>
                                        <input type="checkbox" name="post_categories[]" value="<?=$category->term_id?>" /><?=$category->name?>
                                    <?php } ?>
                                </td>
                                <td class="product_cats_tags">
                                    <?php foreach ($product_categories as $category) { ?>
                                        <input type="checkbox" name="product_categories[]" value="<?=$category->term_id?>" /><?=$category->name?>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr valign="top" >
                                <th scope="row">
                                    <label><?php _e( 'Categories to exclude', 'egoi-for-wp' ); ?></label>
                                </th>
                                <td class="post_cats_tags">
                                    <?php foreach ($post_categories as $category) { ?>
                                        <input type="checkbox" name="post_categories_exclude[]" value="<?=$category->term_id?>" /><?=$category->name?>
                                    <?php } ?>
                                </td>
                                <td class="product_cats_tags">
                                    <?php foreach ($product_categories as $category) { ?>
                                        <input type="checkbox" name="product_categories_exclude[]" value="<?=$category->term_id?>" /><?=$category->name?>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr valign="top" >
                                <th scope="row">
                                    <label><?php _e( 'Tags', 'egoi-for-wp' ); ?></label>
                                </th>
                                <td class="post_cats_tags">
                                    <?php foreach ($post_tags as $tag) { ?>
                                        <input type="checkbox" name="post_tags[]" value="<?=$tag->term_id?>" /><?=$tag->name?>
                                    <?php } ?>
                                </td>
                                <td class="product_cats_tags">
                                    <?php foreach ($product_tags as $tag) { ?>
                                        <input type="checkbox" name="product_tags[]" value="<?=$tag->term_id?>" /><?=$tag->name?>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr valign="top" >
                                <th scope="row">
                                    <label><?php _e( 'Tags to exclude', 'egoi-for-wp' ); ?></label>
                                </th>
                                <td class="post_cats_tags">
                                    <?php foreach ($post_tags as $tag) { ?>
                                        <input type="checkbox" name="post_tags_exclude[]" value="<?=$tag->term_id?>" /><?=$tag->name?>
                                    <?php } ?>
                                </td>
                                <td class="product_cats_tags">
                                    <?php foreach ($product_tags as $tag) { ?>
                                        <input type="checkbox" name="product_tags_exclude[]" value="<?=$tag->term_id?>" /><?=$tag->name?>
                                    <?php } ?>
                                </td>
                            </tr>


                        </table>
                    </div>
                    <?php submit_button(); ?>
                </form>
            </div>
        </div>
    </div>

<?php } ?>

<?php $js_dir = plugins_url().'/smart-marketing-for-wp/admin/js/egoi-for-wp-rssfeed.js'; ?>
<script src="<?=$js_dir?>"></script>