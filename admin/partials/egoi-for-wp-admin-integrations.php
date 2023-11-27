<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}
$dir = plugin_dir_path( __FILE__ ) . 'capture/';
require_once $dir . '/functions.php';
require_once plugin_dir_path( __FILE__ ) . 'egoi-for-wp-common.php';
$sub_var = sanitize_key( isset( $_GET['sub'] )?$_GET['sub']:'' );
$page    = array(
	'home'           => ! isset( $_GET['sub'] ),
	'contact-form-7' => $sub_var == 'contact-form-7',
	'post-comment'   => $sub_var == 'post-comment',
	'gravity-forms'  => $sub_var == 'gravity-forms',
);

if ( isset( $_POST['action'] ) ) {
	$egoiform  = sanitize_text_field( $_POST['egoiform'] );
	$prev_data = get_option( $egoiform );
	$post      = $_POST;
	if ( ! empty( $post['egoi_map_to_save'] ) ) {
		$obj = json_decode( str_replace( '\"', '"', $post['egoi_map_to_save'] ), true );
		$map = array();
		foreach ( $obj as $field ) {
			$map[ (string) $field[0] ] = $field[1];
		}
		Egoi_For_Wp::setGravityFormsInfo( $post['gravity_form'], $map );
		if ( ! empty( $post['gf_tag'] ) ) {
			Egoi_For_Wp::setGravityFormsTag( $post['gravity_form'], $post['gf_tag'] );
			unset( $post['gf_tag'] );
		}
		unset( $post['egoi_map_to_save'] );
	}
	if ( empty( $prev_data ) ) {
		update_option( $egoiform, $post );
	} else {
		update_option( $egoiform, array_replace_recursive( $prev_data, $post ) );
	}

	echo get_notification( __( 'Success', 'egoi-for-wp' ), __( 'Integrations Settings Updated!', 'egoi-for-wp' ) );
}

$lists = $this->egoiWpApiV3->getLists();

$opt    = get_option( 'egoi_int' );
$egoint = $opt['egoi_int'];

if ( !isset($egoint['enable_pc']) || ! $egoint['enable_pc'] ) {
	$egoint['enable_pc'] = 0;
}

if ( !isset($egoint['enable_cf']) || ! $egoint['enable_cf'] ) {
	$egoint['enable_cf'] = 0;
}

if ( !isset($egoint['enable_gf']) || ! $egoint['enable_gf'] ) {
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
			<?php echo getLoader( 'egoi-loader', false ); ?>
		</div>
		<nav>
			<ul>
				<li><a class="home <?php echo $page['home'] ? '-select' : ''; ?>" href="?page=egoi-4-wp-integrations"><?php getHomeSvg(); ?></a></li>
				<li><a class="<?php echo $page['contact-form-7'] ? '-select' : ''; ?>" href="?page=egoi-4-wp-integrations&sub=contact-form-7"><?php _e( 'Contact Form 7', 'egoi-for-wp' ); ?></a></li>
				<li><a class="<?php echo $page['post-comment'] ? '-select' : ''; ?>" href="?page=egoi-4-wp-integrations&sub=post-comment"><?php _e( 'Post Comment', 'egoi-for-wp' ); ?></a></li>
				<li><a class="<?php echo $page['gravity-forms'] ? '-select' : ''; ?>" href="?page=egoi-4-wp-integrations&sub=gravity-forms"><?php _e( 'Gravity Forms', 'egoi-for-wp' ); ?></a></li>

			</ul>
		</nav>
	</header>
	<!-- / Header -->
	<!-- Content -->
	<main style="grid-template-columns: 3fr 1fr !important;">
		<!-- Content -->
		<section class="smsnf-content">

			<?php
			$sub_var = sanitize_key( isset($_GET['sub'])?$_GET['sub']:'' );
			if ( $sub_var == 'contact-form-7' ) {
				require_once plugin_dir_path( __FILE__ ) . 'integrations/contact-form-7.php';
			} elseif ( $sub_var == 'post-comment' ) {
				require_once plugin_dir_path( __FILE__ ) . 'integrations/post-comment.php';
			} elseif ( $sub_var == 'gravity-forms' ) {
				require_once plugin_dir_path( __FILE__ ) . 'integrations/gravity-forms.php';
			} else {
				require_once plugin_dir_path( __FILE__ ) . 'integrations/home.php';
			}

			?>
		</section>

		<section class="smsnf-pub">
			<div>
				<?php require 'egoi-for-wp-admin-sidebar.php'; ?>
			</div>
		</section>
		<!-- / Content -->
	</main>
</div>
