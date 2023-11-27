<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

require_once plugin_dir_path( __FILE__ ) . 'egoi-for-wp-common.php';
require_once plugin_dir_path( __FILE__ ) . 'capture/functions.php';

$page = array(
	'home'     => ! isset( $_GET['subpage'] ),
	'advanced' => isset( $_GET['subpage'] ) && $_GET['subpage'] == 'advanced',
);

add_thickbox();

$lists = $this->egoiWpApiV3->getLists();

$mapped_fields = $this->egoiWpApi->getMappedFields();

$extra       = $this->egoiWpApiV3->getExtraFields( $this->options_list['list'], 'obj' );

$egoi_fields = array(
	'first_name' => 'First name',
	'last_name'  => 'Last name',
	'cellphone'  => 'Mobile',
	'telephone'  => 'Telephone',
	'birth_date' => 'Birth Date',
);

$positions = array(
	'woocommerce_checkout_before_customer_details' => __( 'Before Customer Details', 'egoi-for-wp' ),
	'woocommerce_before_checkout_billing_form'     => __( 'Before Billing Form', 'egoi-for-wp' ),
	'woocommerce_after_checkout_billing_form'      => __( 'After Billing Form', 'egoi-for-wp' ),
	'woocommerce_before_checkout_shipping_form'    => __( 'Before Shipping Form', 'egoi-for-wp' ),
	'woocommerce_after_checkout_shipping_form'     => __( 'After Shipping Form', 'egoi-for-wp' ),
	'woocommerce_before_order_notes'               => __( 'Before Order Details', 'egoi-for-wp' ),
	'woocommerce_after_order_notes'                => __( 'After Order Details', 'egoi-for-wp' ),
	'woocommerce_checkout_after_customer_details'  => __( 'After Customer Details', 'egoi-for-wp' ),
);

if ( $this->options_list['list'] ) {
	if ( isset($extra) ) {
		foreach ( $extra as $key => $extra_field ) {
			$egoi_fields[ $extra_field['field_id'] ] = $extra_field['name'];
		}
	}
}

if ( class_exists( 'Woocommerce' ) ) {
	$wc = new WC_Admin_Profile();
	foreach ( $wc->get_customer_meta_fields() as $key => $value ) {
		foreach ( $value['fields'] as $key_value => $label ) {
			$wp_fields[ $key_value ] = $label['label'];
		}
	}
}

$count_users = count_users();
?>

<script type="text/javascript">
jQuery(document).ready(function($) {
	
	var listID = '<?php echo $this->options_list['list']; ?>';
	var date_validation = $('#date_validation').val();
	// run on start
	runSS(listID);

	function runSS(listID){
		
		var role = '<?php echo $this->options_list['role']; ?>';
		var data = {
			security: egoi_config_ajax_object_core.ajax_nonce,
			action: 'egoi_count_subs',
			list: listID,
			role: role
		};

		jQuery.post(egoi_config_ajax_object_core.ajax_url, data, (response) => {
			$('#egoi_sinc_users_wp').hide();
			$('#valid_sync').html('<?php _e( 'Subscribed in E-goi (Active)', 'egoi-for-wp' ); ?>: <span class=""><b>'+response.data.egoi+'</b></span><p><?php _e( 'WordPress Users', 'egoi-for-wp' ); ?>: <span class=""><b>'+response.data.wp+'</b></span><p>');
		})

	}

	$('#map').click(function() {
		$('#TB_window').css('width', '820px');
		$('#TB_ajaxContent').prop('width', '800px');
	});

	$('#update_users').click(function() {
		$('#e-goi_import_valid').hide();
		$('#load').show();
		var data = {
			action: 'efwp_add_users',
			listID: listID,
			submit: 1
		};

		jQuery.post(ajaxurl, data, function(response) {
			$('#load').hide();
			$('#e-goi_import_valid').show();
			setTimeout(function () {
				runSS(listID);
			}, 5000);
		});
	});

	$('#wpfooter').hide();

});
</script>

<div class="smsnf">
	<div class="smsnf-modal-bg"></div>
	<!-- Header -->
	<header>
		<div class="wrapper-loader-egoi">
			<h1>Smart Marketing > <b><?php _e( 'Sync Contacts', 'egoi-for-wp' ); ?></b></h1>
			<?php echo getLoader( 'egoi-loader', false ); ?>
		</div>
		<nav>
			<ul>
				<li><a class="home <?php echo $page['home'] ? '-select' : ''; ?>" href="?page=egoi-4-wp-subscribers"><?php _e( 'Configuration', 'egoi-for-wp' ); ?></a></li>
				<li><a class="<?php echo $page['advanced'] ? '-select' : ''; ?>" href="?page=egoi-4-wp-subscribers&subpage=advanced"><?php _e( 'Advanced', 'egoi-for-wp' ); ?></a></li>
			</ul>
		</nav>
	</header>

	<!-- / Header -->
	<!-- Content -->
	<main style="grid-template-columns: 3fr 1fr !important;">
		<!-- Content -->
		<section class="smsnf-content">
			<?php
			if ( isset( $_GET['subpage'] ) && $_GET['subpage'] == 'advanced' ) {
				require_once plugin_dir_path( __FILE__ ) . 'configuration/advanced.php';
			} else {
				require_once plugin_dir_path( __FILE__ ) . 'configuration/subscribers.php';
			}
			?>
		</section>

		<section class="smsnf-pub">
			<div>
				<?php require 'egoi-for-wp-admin-sidebar.php'; ?>
			</div>
		</section>

	</main>
</div>

<!-- Mapeamento dos campos -->
<div id="egoi-for-wp-form-map" style="display:none;width:700px;">
	<?php require dirname( __FILE__ ) . '/custom/egoi-for-wp-form-map.php'; ?>
</div>
