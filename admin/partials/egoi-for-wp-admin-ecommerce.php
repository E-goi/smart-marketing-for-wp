<?php

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

require_once plugin_dir_path( __FILE__ ) . 'egoi-for-wp-common.php';

if ( ! empty( $_POST['form_id'] ) ) {
	switch ( $_POST['form_id'] ) {
		case 'form-create-catalog':
			$result = $this->ecommerceFormProcess( $_POST );
			break;
		default:
			break;
	}
}


if ( isset( $_POST['action'] ) ) {

	// $post = $_POST;
	// update_option('egoi_sync', array_merge($this->options_list, $post['egoi_sync']));

	// echo '<div class="e-goi-notice updated notice is-dismissible"><p>';
	// _e('Ecommerce Option Updated!', 'egoi-for-wp');
	// echo '</p></div>';

	// $options = get_option('egoi_sync');

} else {
	// $options = $this->options_list;
}


$page = array(
	'home'        => ! isset( $_GET['subpage'] ),
	'new_catalog' => isset( $_GET['subpage'] ) && $_GET['subpage'] == 'new_catalog',
);

?>


<!-- Wrap -->
<div class="smsnf">
	<div class="smsnf-modal-bg"></div>
	<!-- Header -->
	<header>
		<div class="wrapper-loader-egoi">
			<h1>Smart Marketing > <b><?php _e( 'E-commerce', 'egoi-for-wp' ); ?></b></h1>
			<?php echo getLoader( 'egoi-loader', false ); ?>
		</div>
		<nav>
			<ul>
				<li><a class="home <?php echo $page['home'] ? '-select' : ''; ?>" href="?page=egoi-4-wp-ecommerce"><?php _e( 'Catalogs', 'egoi-for-wp' ); ?></a></li>
				<li><a class="<?php echo $page['new_catalog'] ? '-select' : ''; ?>" href="?page=egoi-4-wp-ecommerce&subpage=new_catalog"><?php _e( 'Create Catalog', 'egoi-for-wp' ); ?></a></li>
			</ul>
		</nav>
	</header>
	<!-- / Header -->
	<!-- Content -->
	<main>
		<!-- Content -->
		<section class="smsnf-content">

			<!-- Messages -->

			<div id="egoi-success" style="<?php echo empty( $result['success'] ) ? 'display: none;' : ''; ?>">
				<div class="postbox egoi-dialog-box" style="border-left: 2px solid green !important;">
					<div style="padding:10px 0;">
						<span style="color: green; margin-top:5px;" class="dashicons dashicons-yes-alt"></span>
						<span id="egoi-success-message" style="display: inline-block; line-height: 22px; font-size: 16px; margin-left: 12px; margin-top: 3px;"><?php echo ! empty( $result['success'] ) ? $result['success'] : ''; ?></span>
					</div>
					<div class="egoi-simple-close-x"><span>X</span></div>
				</div>
			</div>

			<div id="egoi-alert" style="<?php echo empty( $result['error'] ) ? 'display: none;' : ''; ?>">
				<div class="postbox egoi-dialog-box">
					<div style="padding:10px 0;">
						<span style="color: orangered; margin-top:5px;" class="dashicons dashicons-warning"></span>
						<span id="egoi-alert-message" style="display: inline-block; line-height: 22px; font-size: 16px; margin-left: 12px; margin-top: 3px;"><?php echo ! empty( $result['error'] ) ? $result['error'] : ''; ?></span>
					</div>
					<div class="egoi-simple-close-x"><span>X</span></div>
				</div>
			</div>

			<!-- / Messages -->

			<?php
			if ( ! class_exists( 'WooCommerce' ) ) {
				require_once plugin_dir_path( __FILE__ ) . 'ecommerce/no-woocommerce.php';
			} else {
				if ( isset( $_GET['subpage'] ) && $_GET['subpage'] == 'new_catalog' ) {
					require_once plugin_dir_path( __FILE__ ) . 'ecommerce/new-catalog-form.php';
				} else {
					require_once plugin_dir_path( __FILE__ ) . 'ecommerce/catalogs.php';
				}
			}
			?>
		</section>

		<section class="smsnf-pub">
			<div>
				<?php require 'egoi-for-wp-admin-banner.php'; ?>
			</div>
		</section>
		<!-- / Content -->
	</main>
	<!-- / Content -->
	<?php if ( empty( $_GET['subpage'] ) ) { ?>
		<main style="grid-template-columns:1fr !important;">
			<!-- Content -->
			<section class="smsnf-content">
				<div class="container" style="margin: 0;">
					<h4 style="margin: 0;"><?php _e( 'DISCLAIMER:', 'egoi-for-wp' ); ?></h4>
					<p style="font-size: 11px;">
						<?php _e( 'Any changes on categories or .csv importations will need to be manually imported by clicking the "import" button in the Catalog\'s page.', 'egoi-for-wp' ); ?>
					</p>
				</div>
			</section>
			<!-- / Content -->
		</main>
	<?php } ?>
</div>
<!-- / Wrap -->
