<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

require_once plugin_dir_path( __FILE__ ) . 'egoi-for-wp-common.php';

$page = array(
	'home' => ! isset( $_GET['subpage'] ),
);

add_thickbox();

?>

<div class="smsnf">
	<div class="smsnf-modal-bg"></div>
	<!-- Header -->
	<header>
		<div class="wrapper-loader-egoi">
			<h1>Smart Marketing > <b><?php _e( 'Setup', 'egoi-for-wp' ); ?></b></h1>
			<?php echo getLoader( 'egoi-loader', false ); ?>
		</div>
		<nav>
			<ul>
				<li><a class="home <?php echo $page['home'] ? '-select' : ''; ?>" href="?page=egoi-4-wp-setup-wizard"><?php _e( 'Configuration', 'egoi-for-wp' ); ?></a></li>
			</ul>
		</nav>
	</header>

	<!-- / Header -->
	<!-- Content -->
	<main>
		<!-- Content -->
		<section class="smsnf-content">
			<div class="d-flex align-items-start">
				<div class="nav flex-column nav-pills me-3 egoinav" id="v-pills-tab" role="tablist" aria-orientation="vertical">
					<button class="nav-link active" id="v-pills-subscribers-tab" data-bs-toggle="pill" data-bs-target="#v-pills-subscribers" type="button" role="tab" aria-controls="v-pills-subscribers" aria-selected="true"><span><?php _e( 'Subscribers', 'egoi-for-wp' ); ?></span></button>
					<button disabled class="nav-link" id="v-pills-cs-tab" data-bs-toggle="pill" data-bs-target="#v-pills-cs" type="button" role="tab" aria-controls="v-pills-cs" aria-selected="false"><span><?php _e( 'Connected Sites', 'egoi-for-wp' ); ?></span></button>
					<button disabled class="nav-link" id="v-pills-products-tab" data-bs-toggle="pill" data-bs-target="#v-pills-products" type="button" role="tab" aria-controls="v-pills-products" aria-selected="false"><span><?php _e( 'Products', 'egoi-for-wp' ); ?></span></button>
					<button disabled class="nav-link" id="v-pills-tweaks-tab" data-bs-toggle="pill" data-bs-target="#v-pills-tweaks" type="button" role="tab" aria-controls="v-pills-tweaks" aria-selected="false"><span><?php _e( 'Final Tweaks', 'egoi-for-wp' ); ?></span></button>
				</div>
				<div class="tab-content" id="v-pills-tabContent" style="display: flex;">
					<?php
					require_once plugin_dir_path( __FILE__ ) . 'wizard/subscribers.php';
					require_once plugin_dir_path( __FILE__ ) . 'wizard/connected-sites.php';
					require_once plugin_dir_path( __FILE__ ) . 'wizard/products.php';
					require_once plugin_dir_path( __FILE__ ) . 'wizard/final-tweaks.php';
					?>

				</div>
			</div>
			<div class="egoi-undertable-button-wrapper" style="bottom: -14px;position: absolute;right: 13px;">
				<div class="smsnf-input-group">
					<!-- <span>ignore</span> -->
					<input type="submit" id="next_step" value="<?php _e( 'Next', 'egoi-for-wp' ); ?>" />
				</div>
			</div>
		</section>

		<section class="smsnf-pub">
			<div>
				<?php require 'egoi-for-wp-admin-sidebar.php'; ?>
			</div>
		</section>

	</main>
</div>

<!-- Mapeamento dos campos -->
<!-- <div id="egoi-for-wp-form-map" style="display:none;width:700px;">
	<?php //require dirname( __FILE__ ) . '/custom/egoi-for-wp-form-map.php'; ?>
</div> -->
