<?php

if ( ! defined( 'ABSPATH' ) ) {
    die();
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

<?php if (!isset($_GET['add_feed'])) { ?>

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
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <p>
            <a href="<?php echo $_SERVER['REQUEST_URI'];?>&add_feed=1" class='button-primary'><?php _e('Create RSS Feed +', 'egoi-for-wp');?></a>
        </p>
    </div>

<?php } else if (isset($_GET['add_feed'])) { ?>

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
                                        <input type="checkbox" name="post_categories" value="<?=$category->term_id?>" /><?=$category->name?>
                                    <?php } ?>
                                </td>
                                <td class="product_cats_tags">
                                    <?php foreach ($product_categories as $category) { ?>
                                        <input type="checkbox" name="product_categories" value="<?=$category->term_id?>" /><?=$category->name?>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr valign="top" >
                                <th scope="row">
                                    <label><?php _e( 'Categories to exclude', 'egoi-for-wp' ); ?></label>
                                </th>
                                <td class="post_cats_tags">
                                    <?php foreach ($post_categories as $category) { ?>
                                        <input type="checkbox" name="post_categories_exclude" value="<?=$category->term_id?>" /><?=$category->name?>
                                    <?php } ?>
                                </td>
                                <td class="product_cats_tags">
                                    <?php foreach ($product_categories as $category) { ?>
                                        <input type="checkbox" name="product_categories_exclude" value="<?=$category->term_id?>" /><?=$category->name?>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr valign="top" >
                                <th scope="row">
                                    <label><?php _e( 'Tags', 'egoi-for-wp' ); ?></label>
                                </th>
                                <td class="post_cats_tags">
                                    <?php foreach ($post_tags as $tag) { ?>
                                        <input type="checkbox" name="post_tags" value="<?=$tag->term_id?>" /><?=$tag->name?>
                                    <?php } ?>
                                </td>
                                <td class="product_cats_tags">
                                    <?php foreach ($product_tags as $tag) { ?>
                                        <input type="checkbox" name="product_tags" value="<?=$tag->term_id?>" /><?=$tag->name?>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr valign="top" >
                                <th scope="row">
                                    <label><?php _e( 'Tags to exclude', 'egoi-for-wp' ); ?></label>
                                </th>
                                <td class="post_cats_tags">
                                    <?php foreach ($post_tags as $tag) { ?>
                                        <input type="checkbox" name="post_tags_exclude" value="<?=$tag->term_id?>" /><?=$tag->name?>
                                    <?php } ?>
                                </td>
                                <td class="product_cats_tags">
                                    <?php foreach ($product_tags as $tag) { ?>
                                        <input type="checkbox" name="product_tags_exclude" value="<?=$tag->term_id?>" /><?=$tag->name?>
                                    <?php } ?>
                                </td>
                            </tr>


                        </table>
                    </div>
                    <div  style="display: -webkit-inline-box; margin-bottom: 30px;">
                        <button style="margin-top: 12px;" type="submit" class="button button-primary"><?php _e('Save Changes', 'egoi-for-wp');?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php } ?>

<?php $js_dir = plugins_url().'/smart-marketing-for-wp/admin/js/egoi-for-wp-rssfeed.js'; ?>
<script src="<?=$js_dir?>"></script>