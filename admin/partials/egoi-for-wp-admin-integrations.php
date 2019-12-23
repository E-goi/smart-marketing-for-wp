<?php
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
$dir = plugin_dir_path(__FILE__) . 'capture/';
include_once $dir . '/functions.php';
require_once plugin_dir_path(__FILE__) . 'egoi-for-wp-common.php';
$page = array(
    'home' => !isset($_GET['sub']),
    'contact-form-7' => $_GET['sub'] == 'contact-form-7',
    'post-comment' => $_GET['sub'] == 'post-comment',
    'gravity-forms' => $_GET['sub'] == 'gravity-forms'
);
$Egoi4WpBuilderObject = get_option('Egoi4WpBuilderObject');

if(isset($_POST['action'])){
	$egoiform = $_POST['egoiform'];
    $prev_data = get_option($egoiform);
	$post = $_POST;
	if(!empty($post['egoi_map_to_save'])){
        $obj = json_decode(str_replace('\"','"',$post['egoi_map_to_save']),true);
        $map = [];
        foreach ($obj as $field){
            $map[(string)$field[0]] = $field[1];
        }
	    Egoi_For_Wp::setGravityFormsInfo($post['gravity_form'], $map);
        if(!empty($post['gf_tag'])){
            Egoi_For_Wp::setGravityFormsTag($post['gravity_form'],$post['gf_tag']);
            unset($post['gf_tag']);
        }
        unset($post['egoi_map_to_save']);
    }
	if(empty($prev_data))
	    update_option($egoiform, $post);
	else
        update_option($egoiform, array_replace_recursive($prev_data,$post));

    echo get_notification(__('Success', 'egoi-for-wp'), __('Integrations Settings Updated!', 'egoi-for-wp'));
}

$lists = $Egoi4WpBuilderObject->getLists();

$opt = get_option('egoi_int');
$egoint = $opt['egoi_int'];

if(!$egoint['enable_pc']){
	$egoint['enable_pc'] = 0;
}

if(!$egoint['enable_cf']){
	$egoint['enable_cf'] = 0;
}

if(!$egoint['enable_gf']){
    $egoint['enable_gf'] = 0;
}

?>
<style type="text/css">
.form-table th{
    padding: 20px 10px 20px 10px !important;
}
</style>

<div class="smsnf">
    <div class="smsnf-modal-bg"></div>
    <!-- Header -->
    <header>
        <div class="wrapper-loader-egoi">
            <h1>Smart Marketing > <b><?php _e( 'Integrations', 'egoi-for-wp' ); ?></b></h1>
            <?=getLoader('egoi-loader',false)?>
        </div>
        <nav>
            <ul>
                <li><a class="home <?= $page['home'] ?'-select':'' ?>" href="?page=egoi-4-wp-integrations"><?= $home ?></a></li>
                <li><a class="<?= $page['contact-form-7'] ?'-select':'' ?>" href="?page=egoi-4-wp-integrations&sub=contact-form-7"><?php _e('Contact Form 7', 'egoi-for-wp'); ?></a></li>
                <li><a class="<?= $page['post-comment'] ?'-select':'' ?>" href="?page=egoi-4-wp-integrations&sub=post-comment"><?php _e('Post Comment', 'egoi-for-wp'); ?></a></li>
                <li><a class="<?= $page['gravity-forms'] ?'-select':'' ?>" href="?page=egoi-4-wp-integrations&sub=gravity-forms"><?php _e('Gravity Forms', 'egoi-for-wp'); ?></a></li>

            </ul>
        </nav>
    </header>
    <!-- / Header -->
    <!-- Content -->
    <main style="grid-template-columns: 3fr 1fr !important;">
        <!-- Content -->
        <section class="smsnf-content">

            <?php

            if(isset($_GET['sub']) && $_GET['sub'] == 'contact-form-7'){
                require_once plugin_dir_path(__FILE__) . 'integrations/contact-form-7.php';
            }else if(isset($_GET['sub']) && $_GET['sub'] == 'post-comment'){
                require_once plugin_dir_path(__FILE__) . 'integrations/post-comment.php';
            }else if(isset($_GET['sub']) && $_GET['sub'] == 'gravity-forms'){
                require_once plugin_dir_path(__FILE__) . 'integrations/gravity-forms.php';
            }else{
                require_once plugin_dir_path(__FILE__) . 'integrations/home.php';
            }

            ?>
        </section>

        <section class="smsnf-pub">
            <div>
                <?php include ('egoi-for-wp-admin-sidebar.php'); ?>
            </div>
        </section>
        <!-- / Content -->
    </main>
</div>
