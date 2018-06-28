<?php

if ( ! defined( 'ABSPATH' ) ) {
    die();
}

if(isset($_POST['action'])){
    $edit = isset($_GET['edit']) ? true : false;
    $result = $this->createFeed($_POST, $edit);

    if ($result) {
        ?>
        <div class="e-goi-notice updated notice is-dismissible">
            <p><?php _e('RSS Feed saved!', 'egoi-for-wp'); ?></p>
        </div>
        <?php
    }
}

if (isset($_GET['del'])) {
    delete_option($_GET['del']);
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

<?php if (!isset($_GET['add']) && !isset($_GET['edit']) && !isset($_GET['view'])) { ?>

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
                    <th></th><th></th>
                </tr>
            </thead>
            <tbody>
            <spam id="copy_text" style="display: none;"><?php _e('Copy URL', 'egoi-for-wp'); ?></spam>
            <spam id="copied_text" style="display: none;"><?php _e('Copied!', 'egoi-for-wp'); ?></spam>
            <?php
                global $wpdb;
                $table = $wpdb->prefix."options";
                $options = $wpdb->get_results( " SELECT * FROM ".$table." WHERE option_name LIKE 'egoi_rssfeed_%' ORDER BY option_id DESC ");
                foreach ($options as $option) {
                    $feed = get_option($option->option_name);
            ?>

                <!-- PopUp ALERT Delete Form -->
                <div class="cd-popup cd-popup-del-form" data-id-form="<?=$option->option_name?>" data-type-form="rss-feed" role="alert">
                    <div class="cd-popup-container">
                        <p><b><?php echo __('Are you sure you want to delete this RSS Feed?', 'egoi-for-wp');?> </b></p>
                        <ul class="cd-buttons">
                            <li>
                                <a href="<?php echo $this->prepareUrl('&del='.$option->option_name);?>"><?php _e('Confirm', 'egoi-for-wp'); ?></a>
                            </li>
                            <li>
                                <a class="cd-popup-close-btn" href="#0"><?php _e('Cancel', 'egoi-for-wp'); ?></a>
                            </li>
                        </ul>
                    </div> <!-- cd-popup-container -->
                </div> <!-- PopUp ALERT Delete Form -->

                <tr>
                    <td style="vertical-align: middle;"><?=$feed['name']?></td>
                    <td style="vertical-align: middle;"><?php _e(ucfirst($feed['type']), 'egoi-for-wp'); ?></td>
                    <td style="vertical-align: middle;"><input type="text" id="url_<?=$option->option_name?>" class="copy-input" value="<?php echo get_site_url().'/?feed='.$option->option_name; ?>" readonly
                        style="width: 100%;border: none;background-image:none;background-color:transparent;-webkit-box-shadow: none;-moz-box-shadow: none;box-shadow: none;"></td>
                    <td style="vertical-align: middle;" width="100">
                        <button class="copy_url button button--custom" style="width: 90px;" data-rss-feed="url_<?=$option->option_name?>"><?php _e('Copy URL', 'egoi-for-wp');?></button>
                    </td>
                    <td style="vertical-align: middle;" align="right" width="70" nowrap>
                        <a class="cd-popup-trigger-del" data-id-form="<?=$option->option_name?>" data-type-form="rss-feed" href="" title="<?php _e('Delete', 'egoi-for-wp'); ?>"><i style="padding-right: 3px;" class="far fa-trash-alt"></i></a>
                        <a title="<?php _e('Edit', 'egoi-for-wp'); ?>" href="<?php echo $this->prepareUrl('&edit='.$option->option_name);?>"><i style="padding-right: 2px;" class="far fa-edit"></i></a>
                        <a title="<?php _e('Preview', 'egoi-for-wp'); ?>" href="<?php echo $this->prepareUrl('&view='.$option->option_name);?>"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <p>
            <a href="<?php echo $this->prepareUrl('&add=1');?>" class='button-primary'><?php _e('Create RSS Feed +', 'egoi-for-wp');?></a>
        </p>
    </div>

<?php } else if (isset($_GET['add']) || isset($_GET['edit'])) { ?>

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

        if (isset($_GET['edit'])) {
            $feed = get_option($_GET['edit']);
        }

        if (!isset($_GET['edit'])) {
            $code = wp_generate_password(16, false);
        } else {
            $code = substr($_GET['edit'], -16);
        }
    ?>
    <div class="wrap">

        <div class="main-content">

            <!-- Main Content -->
            <div class="col-4" style="max-width: 900px;">
                <form id="egoi_simple_form" method="post" action="<?php echo $this->prepareUrl('&edit=egoi_rssfeed_'.$code); ?>">
                    <?php
                    settings_fields( Egoi_For_Wp_Admin::OPTION_NAME );
                    settings_errors();
                    ?>
                    <input name="code" type="hidden" value="<?=$code?>">
                    <div>
                        <p>
                            <a href="<?php echo $this->prepareUrl();?>" class='button button--custom'>
                                <i class="fas fa-arrow-left"></i>
                                <?php _e('Back', 'egoi-for-wp');?>
                            </a>
                        </p>

                        <table class="form-table" style="table-layout: fixed;">
                            <?php if (isset($_GET['edit'])) { ?>
                                 <tr valign="top">
                                    <th scope="row">
                                        <label><?php _e( 'URL', 'egoi-for-wp' ); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" style="width:90%; background: white; color: #32373c;" id="input_<?=$code?>" name="input_url"
                                               value="<?php echo get_site_url().'/?feed=egoi_rssfeed_'.$code; ?>" readonly />
                                        <button type="button" class="copy_url button button--custom" style="padding: 0 5px; height: 25px !important; line-height: 0 !important;" data-rss-feed="input_<?=$code?>"><i class="far fa-copy"></i></button>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr valign="top">
                                <th scope="row">
                                    <label><?php _e( 'Name', 'egoi-for-wp' ); ?></label>
                                </th>
                                <td>
                                    <input type="text" style="width:90%;" id="name" name="name"
                                           placeholder="<?php _e( 'Choose a name for your new RSS Feed', 'egoi-for-wp' ); ?>"
                                           value="<?php echo isset($feed) ? $feed['name'] : null; ?>" required />
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label><?php _e( 'Maximum of characters', 'egoi-for-wp' ); ?></label>
                                </th>
                                <td>
                                    <input type="text" style="width:90%;" id="max_characters" name="max_characters" pattern="[0-9]*"
                                           placeholder="<?php _e( 'Set the maximum characters for RSS Feed text', 'egoi-for-wp' ); ?>"
                                           value="<?php echo isset($feed) ? $feed['max_characters'] : null; ?>" required />
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><label><?php _e( 'Image size', 'egoi-for-wp' ); ?></label></th>
                                <td>
                                    <select name="image_size" id="image_size" style="width:90%;" required>
                                        <option value="" <?php selected($feed['image_size'], null); ?> ><?php _e( 'Select size..', 'egoi-for-wp' ); ?></option>
                                        <option value="full" <?php selected($feed['image_size'], 'full'); ?> ><?php _e('Full', 'egoi-for-wp'); ?></option>
                                        <option value="large" <?php selected($feed['image_size'], 'large'); ?> ><?php _e('Large', 'egoi-for-wp'); ?></option>
                                        <option value="medium" <?php selected($feed['image_size'], 'medium'); ?> ><?php _e('Medium', 'egoi-for-wp'); ?></option>
                                        <option value="medium_large" <?php selected($feed['image_size'], 'medium_large'); ?> ><?php _e('Medium Large', 'egoi-for-wp'); ?></option>
                                        <option value="thumbnail" <?php selected($feed['image_size'], 'thumbnail'); ?> ><?php _e('Thumbnail', 'egoi-for-wp'); ?></option>
                                    </select>
                                    <p class="help"><?php _e( 'Select a default size for RSS feed images.' ,'egoi-for-wp' ); ?></p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label><?php _e( 'Type', 'egoi-for-wp' ); ?></label>
                                </th>
                                <td>
                                    <label>
                                        <input type="radio" name="type" value="posts" <?php checked( $feed['type'], 'posts' ); ?> required /> <?php _e( 'Posts', 'egoi-for-wp' ); ?>
                                    </label>
                                    <label>
                                        <input type="radio" name="type" value="products" <?php checked( $feed['type'], 'products' ); ?> /> <?php _e( 'Products', 'egoi-for-wp' ); ?>
                                    </label>
                                    <p class="help"><?php _e( 'Choose between Posts or Products to fill out the RSS Feed', 'egoi-for-wp' ); ?>.</p>
                                </td>
                            </tr>

                            <tr valign="top" >
                                <th scope="row" class="cats_tags_titles">
                                    <label><?php _e( 'Categories', 'egoi-for-wp' ); ?></label>
                                </th>
                                <td class="post_cats_tags">
                                    <select class="js-example-basic-multiple" name="post_categories_include[]" multiple="multiple" style="width:90%;">
                                    <?php foreach ($post_categories as $category) { ?>
                                        <option id="posts_cats_include_<?=$category->term_id?>" value="<?=$category->term_id?>"
                                            <?php if (in_array($category->term_id, $feed['categories'])) echo 'selected';
                                            else if (in_array($category->term_id, $feed['categories_exclude'])) echo 'disabled'; ?> >
                                            <?=$category->name?>
                                        </option>
                                    <?php } ?>
                                    </select>
                                    <p class="help"><?php _e( 'Please select at least one Category or All will be displayed', 'egoi-for-wp' ); ?>.</p>
                                </td>
                                <td class="product_cats_tags">
                                    <select class="js-example-basic-multiple" name="product_categories_include[]" multiple="multiple" style="width:90%;">
                                        <?php foreach ($product_categories as $category) { ?>
                                            <option id="products_cats_include_<?=$category->term_id?>" value="<?=$category->term_id?>"
                                                <?php if (in_array($category->term_id, $feed['categories'])) echo 'selected';
                                                else if (in_array($category->term_id, $feed['categories_exclude'])) echo 'disabled'; ?> >
                                                <?=$category->name?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <p class="help"><?php _e( 'Please select at least one Category or All will be displayed', 'egoi-for-wp' ); ?>.</p>
                                </td>
                            </tr>
                            <tr valign="top" >
                                <th scope="row" class="cats_tags_titles">
                                    <label><?php _e( 'Categories to exclude', 'egoi-for-wp' ); ?></label>
                                </th>
                                <td class="post_cats_tags">
                                    <select class="js-example-basic-multiple" name="post_categories_exclude[]" multiple="multiple" style="width:90%;">
                                        <?php foreach ($post_categories as $category) { ?>
                                            <option id="posts_cats_exclude_<?=$category->term_id?>" value="<?=$category->term_id?>"
                                                <?php if (in_array($category->term_id, $feed['categories_exclude'])) echo 'selected';
                                                else if (in_array($category->term_id, $feed['categories'])) echo 'disabled'; ?> >
                                                <?=$category->name?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <p><br></p>
                                </td>
                                <td class="product_cats_tags">
                                    <select class="js-example-basic-multiple" name="product_categories_exclude[]" multiple="multiple" style="width:90%;">
                                        <?php foreach ($product_categories as $category) { ?>
                                            <option id="products_cats_exclude_<?=$category->term_id?>" value="<?=$category->term_id?>"
                                                <?php if (in_array($category->term_id, $feed['categories_exclude'])) echo 'selected';
                                                else if (in_array($category->term_id, $feed['categories'])) echo 'disabled'; ?> >
                                                <?=$category->name?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <p><br></p>
                                </td>
                            </tr>
                            <tr valign="top" >
                                <th scope="row" class="cats_tags_titles">
                                    <label><?php _e( 'Tags', 'egoi-for-wp' ); ?></label>
                                </th>
                                <td class="post_cats_tags">
                                    <select class="js-example-basic-multiple" name="post_tags_include[]" multiple="multiple" style="width:90%;">
                                        <?php foreach ($post_tags as $tag) { ?>
                                            <option id="posts_tags_include_<?=$tag->term_id?>" value="<?=$tag->term_id?>"
                                                <?php if (in_array($tag->term_id, $feed['tags'])) echo 'selected';
                                                else if (in_array($tag->term_id, $feed['tags_exclude'])) echo 'disabled'; ?> >
                                                <?=$tag->name?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <p class="help"><?php _e( 'Please select at least one Tag or All will be displayed', 'egoi-for-wp' ); ?>.</p>
                                </td>
                                <td class="product_cats_tags">
                                    <select class="js-example-basic-multiple" name="product_tags_include[]" multiple="multiple" style="width:90%;">
                                        <?php foreach ($product_tags as $tag) { ?>
                                            <option id="products_tags_include_<?=$tag->term_id?>" value="<?=$tag->term_id?>"
                                                <?php if (in_array($tag->term_id, $feed['tags'])) echo 'selected';
                                                else if (in_array($tag->term_id, $feed['tags_exclude'])) echo 'disabled'; ?> >
                                                <?=$tag->name?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <p class="help"><?php _e( 'Please select at least one Tag or All will be displayed', 'egoi-for-wp' ); ?>.</p>
                                </td>
                            </tr>
                            <tr valign="top" >
                                <th scope="row" class="cats_tags_titles">
                                    <label><?php _e( 'Tags to exclude', 'egoi-for-wp' ); ?></label>
                                </th>
                                <td class="post_cats_tags">
                                    <select class="js-example-basic-multiple" name="post_tags_exclude[]" multiple="multiple" style="width:90%;">
                                        <?php foreach ($post_tags as $tag) { ?>
                                            <option id="posts_tags_exclude_<?=$tag->term_id?>" value="<?=$tag->term_id?>"
                                                <?php if (in_array($tag->term_id, $feed['tags_exclude'])) echo 'selected';
                                                else if (in_array($tag->term_id, $feed['tags'])) echo 'disabled'; ?> >
                                                <?=$tag->name?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td class="product_cats_tags">
                                    <select class="js-example-basic-multiple" name="product_tags_exclude[]" multiple="multiple" style="width:90%;" >
                                        <?php foreach ($product_tags as $tag) { ?>
                                            <option id="products_tags_exclude_<?=$tag->term_id?>" value="<?=$tag->term_id?>"
                                                <?php if (in_array($tag->term_id, $feed['tags_exclude'])) echo 'selected';
                                                else if (in_array($tag->term_id, $feed['tags'])) echo 'disabled'; ?> >
                                                <?=$tag->name?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php submit_button(); ?>
                </form>
            </div>


            <!-- Sidebar -->
            <div class="sidebar">
                <?php include ('egoi-for-wp-admin-sidebar.php'); ?>
            </div>

        </div>
    </div>

<?php } else if ($_GET['view']) { ?>
    <a href="<?php echo $this->prepareUrl();?>" class='button button--custom'>
        <i class="fas fa-arrow-left"></i>
        <?php _e('Back', 'egoi-for-wp');?>
    </a>
    <?php
        $feed = get_option($_GET['view']);
        $args = $this->get_egoi_rss_feed_args($feed);

        $query = new WP_Query( $args );
    ?>

    <div class="wrap-content wrap-content--list">
        <div style="width: 600px; margin: auto;">
            <h3><?php echo $feed['name']; ?></h3>
            <?php if (!$query->have_posts()) { ?> <p> <?php _e('No Posts', 'egoi-for-wp'); ?> </p>
            <?php } else {
                    while ( $query->have_posts() ) {
                        $query->the_post();

                        $content = get_the_content_feed('rss2');

                        $all_content = implode(' ', get_extended(  get_post_field( 'post_content', get_the_ID() ) )) ;

                        $description = $this->egoi_rss_feed_description(get_the_excerpt(), $feed['max_characters']);
                        ?>
                        <p>
                            <a href="<?php the_permalink_rss() ?>" target="_blank">
                                <?php the_title_rss() ?>
                            </a><br>
                            <?php echo mysql2date('j M Y H:i', get_post_time('Y-m-d H:i:s', true), false); ?><br>
                            <?php the_author() ?>
                        </p>
                        <?php if ( has_post_thumbnail() ) {
                                echo '<p  align="center">'.get_the_post_thumbnail(get_the_ID(), $feed['image_size']).'</p>';
                            } else if ($gallery = get_post_gallery_images( get_the_ID() )) {
                            foreach( $gallery as $image_url ) {
                                ?><p  align="center"><img width="600" src="<?php echo $image_url; ?>" /></p><?php
                                break;
                            }
                        } else  {
                            preg_match('~<img.*?src=["\']+(.*?)["\']+~', $all_content, $img);
                            if (isset($img[1])) {
                                ?><p  align="center"><img width="600" src="<?php echo $img[1]; ?>"/></p><?php
                            }
                        }?>
                        <p><?php echo $description; ?> </p>
                <?php }
            }?>
        </div>
    </div>

<?php } ?>

<?php $js_dir = plugins_url().'/smart-marketing-for-wp/admin/js/egoi-for-wp-rssfeed.js'; ?>
<script src="<?=$js_dir?>"></script>