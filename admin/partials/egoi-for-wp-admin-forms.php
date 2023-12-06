<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

$dir = plugin_dir_path( __FILE__ ) . 'capture/';

require_once $dir . '/functions.php';
require_once plugin_dir_path( __FILE__ ) . 'egoi-for-wp-common.php';
require_once plugin_dir_path( __FILE__ ) . '../../includes/class-egoi-for-wp-popup.php';


$img = 'https://img.icons8.com/ios/50/000000/picture.png';

function printHome(){
 ?>
    <svg class="smsnfCapture__header__menu__item__homeIcon" version="1.1" id="Camada_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 30.3 27.1" style="enable-background:new 0 0 30.3 27.1;" xml:space="preserve"><style type="text/css">.st0{clip-path:url(#SVGID_4_);}.st1{clip-path:url(#SVGID_6_);}</style><g><g><path id="SVGID_1_" d="M1.2,16.1h2.4v9.8c0,0.6,0.5,1,1,1h7.2c0.6,0,1-0.5,1-1v-7.2h4.4v7.2c0,0.6,0.5,1,1,1H25c0.6,0,1-0.5,1-1v-9.8h3c0.4,0,0.8-0.3,1-0.6c0.2-0.4,0.1-0.8-0.2-1.1L16.2,0.6c-0.4-0.4-1.1-0.4-1.5,0L0.5,14.3c-0.3,0.3-0.4,0.8-0.2,1.2C0.4,15.8,0.8,16.1,1.2,16.1z M15.5,2.7L26.7,14h-1.5c-0.6,0-1,0.5-1,1v9.8h-4.7v-7.2c0-0.6-0.5-1-1-1H12c-0.6,0-1,0.5-1,1v7.2H5.9V15c0-0.6-0.5-1-1-1H4L15.5,2.7z"/></g><g><defs><path id="SVGID_2_" d="M1.2,16.1h2.4v9.8c0,0.6,0.5,1,1,1h7.2c0.6,0,1-0.5,1-1v-7.2h4.4v7.2c0,0.6,0.5,1,1,1H25c0.6,0,1-0.5,1-1v-9.8h3c0.4,0,0.8-0.3,1-0.6c0.2-0.4,0.1-0.8-0.2-1.1L16.2,0.6c-0.4-0.4-1.1-0.4-1.5,0L0.5,14.3c-0.3,0.3-0.4,0.8-0.2,1.2C0.4,15.8,0.8,16.1,1.2,16.1z M15.5,2.7L26.7,14h-1.5c-0.6,0-1,0.5-1,1v9.8h-4.7v-7.2c0-0.6-0.5-1-1-1H12c-0.6,0-1,0.5-1,1v7.2H5.9V15c0-0.6-0.5-1-1-1H4L15.5,2.7z"/></defs><clipPath id="SVGID_4_"><use xlink:href="#SVGID_2_"  style="overflow:visible;"/></clipPath><g class="st0"><g><rect id="SVGID_3_" x="-188.8" y="-134.9" width="1920" height="1080"/></g><g><defs><rect id="SVGID_5_" x="-188.8" y="-134.9" width="1920" height="1080"/></defs><clipPath id="SVGID_6_"><use xlink:href="#SVGID_5_"  style="overflow:visible;"/></clipPath><rect x="-4.8" y="-4.9" class="st1" width="40" height="36.8"/></g></g></g></g></svg>
<?php
}

// apaga formulário avançado
if ( isset( $_GET['del_adv_form'] ) ) {
	delete_option( 'egoi_form_sync_' . sanitize_key($_GET['del_adv_form']) );
	echo get_notification( __( 'Advanced Form', 'egoi-for-wp' ), __( 'Form was successfully deleted!', 'egoi-for-wp' ) );
}

// apaga formulário simples
if ( isset( $_GET['del_simple_form'] ) ) {
	if ( ! EgoiPopUp::checkFormSafeDelete( sanitize_key($_GET['del_simple_form']) ) ) {
		echo get_notification( __( 'Error', 'egoi-for-wp' ), __( 'You have Popups using the form you are trying to delete.', 'egoi-for-wp' ) );
	} else {
		delete_simple_form( $_GET['del_simple_form'] );
		echo get_notification( __( 'Simple Form', 'egoi-for-wp' ), __( 'Form was successfully deleted!', 'egoi-for-wp' ) );
	}
}

// apaga popups
if ( isset( $_GET['del_popup'] ) ) {
	EgoiPopUp::deletePopup( sanitize_key($_GET['del_popup']) );
	echo get_notification( __( 'Popups', 'egoi-for-wp' ), __( 'Popup was successfully deleted!', 'egoi-for-wp' ) );
}

$sub_var = isset($_GET['sub'])?sanitize_key( $_GET['sub'] ):'';
$page    = array(
	'home'             => ! isset( $_GET['sub'] ),
	'adv forms'        => $sub_var == 'adv-forms',
	'simple form'      => $sub_var == 'simple-forms',
	'subscription bar' => $sub_var == 'subscription-bar',
	'widget options'   => $sub_var == 'widget-options',
	'popup'            => $sub_var == 'popup',
);

$next_adv_form_id = get_next_adv_form_id();
?>


<!-- Wrap -->
<div class="smsnf">
	<div class="smsnf-modal-bg"></div>
	<!-- Header -->
	<header>
		<h1>Smart Marketing > <b><?php _e( 'Capture Contacts', 'egoi-for-wp' ); ?></b></h1>
		<nav>
			<ul>
				<li><a class="home <?php echo $page['home'] ? '-select' : ''; ?>" href="?page=egoi-4-wp-form"><?php printHome(); ?></a></li>
				<li><a class="<?php echo $page['simple form'] ? '-select' : ''; ?>" href="?page=egoi-4-wp-form&sub=simple-forms"><?php _e( 'Simple Forms', 'egoi-for-wp' ); ?></a></li>
				<?php // se $next_adv_form_id == null significa que já existem 5 adv forms logo o utilizador não pode criar mais formulários ?>
				<li><a class="<?php echo $page['adv forms'] ? '-select' : ''; ?> <?php echo $next_adv_form_id == null ? '-disabled' : ''; ?>"
					   href="<?php echo $next_adv_form_id == null ? '#' : '?page=egoi-4-wp-form&sub=adv-forms&form=' . $next_adv_form_id; ?>"><?php _e( 'Advanced Forms', 'egoi-for-wp' ); ?></a></li>
				<li><a class="<?php echo $page['subscription bar'] ? '-select' : ''; ?>" href="?page=egoi-4-wp-form&sub=subscription-bar"><?php _e( 'Subscriber Bar', 'egoi-for-wp' ); ?></a></li>
				<li><a class="<?php echo $page['widget options'] ? '-select' : ''; ?>" href="?page=egoi-4-wp-form&sub=widget-options"><?php _e( 'Widget Options', 'egoi-for-wp' ); ?></a></li>
				<li><a class="<?php echo $page['popup'] ? '-select' : ''; ?>" href="?page=egoi-4-wp-form&sub=popup"><?php _e( 'Popup', 'egoi-for-wp' ); ?></a></li>
			</ul>
		</nav>
	</header>
	<!-- / Header -->
	<!-- Content -->
	<main <?php echo ! empty( $page['popup'] ) ? 'style="grid-template-columns: 4fr 3fr !important;"' : ''; ?> >
		<!-- Content -->
		<section class="smsnf-content" style="<?php echo $page['adv forms'] ? 'padding-bottom: 0;' : ''; ?>">
			<?php
			if ( $page['home'] ) {
				$file = $dir . 'home.php';
			} elseif ( $page['simple form'] ) {
				$file = $dir . 'simple-forms.php';
			} elseif ( $page['adv forms'] ) {
				$file = $dir . 'advanced-forms.php';
			} elseif ( $page['subscription bar'] ) {
				$file = $dir . 'subscription-bar.php';
			} elseif ( $page['widget options'] ) {
				$file = $dir . 'widget-options.php';
			} elseif ( $page['popup'] ) {
				$file = $dir . 'popup.php';
			}

			require $file;
			?>
			<?php if ( $page['simple form'] || $page['subscription bar'] || $page['widget options'] ) : ?>
				<div class="modal" id="create-new-tag">
					<a href="#close" class="modal-overlay" aria-label="Close"></a>
					<div class="modal-container">
						<div class="modal-header">
							<h2><?php _e( 'Create New Tag', 'egoi-for-wp' ); ?></h2>
							<a href="#close" class="btn btn-clear float-right" aria-label="Close"></a>
						</div>
						<div class="modal-body">
							<div class="content">

								<div class="smsnf-input-group">
									<label for="tag_name"><?php _e( 'Name', 'egoi-for-wp' ); ?><div id="loading_add_tag" style="display: none;" class="loading"></div></label>
									<input id="new_tag_name" type="text" name="name" />
								</div>
								<div class="smsnf-input-group">
									<input id="new_tag_submit" type="submit" value="Criar TAG">
								</div>

							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</section>
		<!-- / Content -->
		<!-- Pub -->

		<?php if ( $page['popup'] ) { ?>
			<section class="smsnf-content" style="height: calc(100vh - 200px); position: fixed; width: calc( 3/7 * 100vw - 100px); right: 20px;">
				<?php include $dir . 'popup-preview.php'; ?>
			</section>
		<?php } else { ?>
			<section class="smsnf-pub">
				<div>
					<?php include 'egoi-for-wp-admin-banner.php'; ?>
				</div>
			</section>
		<?php } ?>
		<!-- / Pub -->
	</main>
	<!-- / Content -->
</div>
<!-- / Wrap -->
