<?php

if ( ! defined( 'ABSPATH' ) ) {
    die();
}

require_once plugin_dir_path(__FILE__) . 'egoi-for-wp-common.php';

if (!empty($_POST['form_id'])) {
    switch ($_POST['form_id']){
        case 'form-create-catalog':
            $result = $this->ecommerceFormProcess($_POST);
            break;
        default:
            breaK;
    }
}


if(isset($_POST['action'])){
		
	//$post = $_POST;
	//update_option('egoi_sync', array_merge($this->options_list, $post['egoi_sync']));

	//echo '<div class="e-goi-notice updated notice is-dismissible"><p>';
	//	_e('Ecommerce Option Updated!', 'egoi-for-wp');
	//echo '</p></div>';

	//$options = get_option('egoi_sync');
	
}else{
	//$options = $this->options_list;
}
?>

<h1 class="logo">Smart Marketing - <?php _e( 'Ecommerce', 'egoi-for-wp' ); ?></h1>
	<p class="breadcrumbs">
		<span class="prefix"><?php echo __( 'You are here: ', 'egoi-for-wp' ); ?></span>
		<strong>Smart Marketing</a> &rsaquo;
		<span class="current-crumb"><?php _e( 'Ecommerce', 'egoi-for-wp' ); ?></strong></span>
	</p>
<hr/>


<div id="egoi-success" style="<?=empty($result['success'])?'display: none;':'';?>">
    <div class="postbox egoi-dialog-box" style="border-left: 2px solid green !important;">
        <div style="padding:10px 0;">
            <span style="color: green; margin-top:5px;" class="dashicons dashicons-yes-alt"></span>
            <span id="egoi-success-message" style="display: inline-block; line-height: 22px; font-size: 16px; margin-left: 12px; margin-top: 3px;"><?=!empty($result['success'])?$result['success']:'';?></span>
        </div>
        <div class="egoi-simple-close-x"><span>X</span></div>
    </div>
</div>

<div id="egoi-alert" style="<?=empty($result['error'])?'display: none;':'';?>">
    <div class="postbox egoi-dialog-box">
        <div style="padding:10px 0;">
            <span style="color: orangered; margin-top:5px;" class="dashicons dashicons-warning"></span>
            <span id="egoi-alert-message" style="display: inline-block; line-height: 22px; font-size: 16px; margin-left: 12px; margin-top: 3px;"><?=!empty($result['error'])?$result['error']:'';?></span>
        </div>
        <div class="egoi-simple-close-x"><span>X</span></div>
    </div>
</div>



<div class="postbox" style="margin-top:20px; max-width:80%; padding: 5px 20px 5px;">
	
	<div class="wrapper-loader-egoi">
        <?php if(!empty($_GET['subpage'])){ ?>
            <h1><a class="egoi-back-button" style="text-decoration: none;display: flex;align-items: center;" href="admin.php?page=egoi-4-wp-ecommerce">&nbsp;<span class="dashicons dashicons-arrow-left-alt"></span>&nbsp;</a></h1>
        <?php } ?>
		<h1><?php _e( 'E-Commerce', 'egoi-for-wp' ); ?></h1>
        <?=getLoader('egoi-loader',false)?>
	</div>

	<?php if ( !class_exists( 'WooCommerce' ) ) {
        require_once plugin_dir_path(__FILE__) . 'ecommerce/no-woocommerce.php';
     }else{
	    if(isset($_GET['subpage']) && $_GET['subpage'] == 'new_catalog'){
            require_once plugin_dir_path(__FILE__) . 'ecommerce/new-catalog-form.php';
        }else{
            require_once plugin_dir_path(__FILE__) . 'ecommerce/catalogs.php';
        }
    } ?>

</div>