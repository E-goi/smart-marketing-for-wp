<?php

if ( ! defined( 'ABSPATH' ) ) {
	die();
}
$dir = plugin_dir_path( __FILE__ ) . 'capture/';
require_once $dir . '/functions.php';
require_once plugin_dir_path( __FILE__ ) . 'egoi-for-wp-common.php';
$sub_var = sanitize_key( isset( $_GET['sub'] )?$_GET['sub']:'' );
$page    = array(
	'home'         => ! isset( $_GET['sub'] ),
	'campaign-rss' => $sub_var == 'campaign-rss',
	'rss-feed'     => $sub_var == 'rss-feed',
);
if ( isset( $_POST['action'] ) ) {
	$edit   = isset( $_GET['edit'] ) ? true : false;
	$result = $this->createFeed( $_POST, $edit );

	if ( $result ) {

		echo get_notification( __( 'Success', 'egoi-for-wp' ), __( 'RSS Feed saved!', 'egoi-for-wp' ) );

	}
}

if ( isset( $_GET['del'] ) ) {
	delete_option( $_GET['del'] );
}

?>


<div class="smsnf">
	<div class="smsnf-modal-bg"></div>
	<!-- Header -->
	<header>
		<div class="wrapper-loader-egoi">
			<h1>Smart Marketing > <b><?php _e( 'RSS Feed', 'egoi-for-wp' ); ?></b></h1>
			<?php echo getLoader( 'egoi-loader', false ); ?>
		</div>
		<nav>
			<ul>
				<li><a class="home <?php echo $page['home'] ? '-select' : ''; ?>" href="?page=egoi-4-wp-rssfeed"><?php getHomeSvg(); ?></a></li>
				<li><a class="<?php echo $page['rss-feed'] ? '-select' : ''; ?>" href="?page=egoi-4-wp-rssfeed&sub=rss-feed&add=1"><?php _e( 'Rss Feed', 'egoi-for-wp' ); ?></a></li>
				<li><a class="<?php echo $page['campaign-rss'] ? '-select' : ''; ?>" href="?page=egoi-4-wp-rssfeed&sub=campaign-rss"><?php _e( 'Campaign Rss', 'egoi-for-wp' ); ?></a></li>
			</ul>
		</nav>
	</header>
	<!-- / Header -->
	<!-- Content -->
	<main style="grid-template-columns: 1fr !important;">
		<!-- Content -->
		<section class="smsnf-content">

			<?php
			$sub_var = sanitize_key( isset( $_GET['sub'] )?$_GET['sub']:'' );
			if ( isset( $_GET['sub'] ) && $sub_var == 'rss-feed' ) {
				require_once plugin_dir_path( __FILE__ ) . 'rssfeed/feed-rss.php';
			} elseif ( isset( $_GET['sub'] ) && $sub_var == 'campaign-rss' ) {
				require_once plugin_dir_path( __FILE__ ) . 'rssfeed/campaign-rss.php';
			} else {
				require_once plugin_dir_path( __FILE__ ) . 'rssfeed/home.php';
			}

			?>
		</section>
		<!-- / Content -->
	</main>
</div>

