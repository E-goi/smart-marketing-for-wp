<?php if ( ! defined( 'ABSPATH' ) ) {
	die();
}

?>

<button id="smsnf-help-btn" class="smsnf-help-btn"><?php _e( 'Help?', 'egoi-for-wp' ); ?></button>

<div class="smsnf-adv-forms" style='background: url("<?php echo plugins_url( '../../img/cs_banner.svg', __FILE__ ); ?>") no-repeat center center;background-size: contain;height: 100%;cursor: pointer' onclick="window.open('https://bo.egoiapp.com/#/accounts/info/account/connectedsites', '_blank');">

</div>

<section id="smsnf-help" class="help">
	<div class="close-btn">
		<svg version="1.1" id="Camada_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 19.1 20.2" style="enable-background:new 0 0 19.1 20.2;" xml:space="preserve">
			<g>
				<path d="M10.9,10.1l7.9-8.3c0.4-0.4,0.4-1,0-1.4c-0.4-0.4-1-0.4-1.4,0L9.6,8.6L1.8,0.4C1.4,0,0.8,0,0.4,0.4s-0.4,1,0,1.4l7.8,8.3
					l-7.6,8c-0.4,0.4-0.4,1,0,1.4c0.2,0.2,0.4,0.3,0.7,0.3c0.3,0,0.5-0.1,0.7-0.3l7.5-7.9l7.8,8.2c0.2,0.2,0.5,0.3,0.7,0.3
					c0.2,0,0.5-0.1,0.7-0.3c0.4-0.4,0.4-1,0-1.4L10.9,10.1z"/>
			</g>
		</svg>
	</div>
	<p><?php _e( 'How to integrate the form in a post or page', 'egoi-for-wp' ); ?></p>
	<hr />
	<ol>
		<li><?php _e( 'Go in your E-goi\'s account in the tab/menu Forms.', 'egoi-for-wp' ); ?></li>
		<li><?php _e( 'Choose the desired form.', 'egoi-for-wp' ); ?></li>
		<li><?php _e( 'Select the Save button and choose "Publish".', 'egoi-for-wp' ); ?></li>
		<li><?php _e( 'Select the Connected Site you want to activate the form.', 'egoi-for-wp' ); ?></li>
		<li><?php _e( 'Paste the generated short code in the page you want the form.', 'egoi-for-wp' ); ?></li>
		<li><?php _e( 'Save all changes.', 'egoi-for-wp' ); ?></li>
	</ol>
</section>

<div id="smsnf-confirm-modal" class="modal modal-sm" id="modal-id" style="width: 100% !important; max-width: 100vw;">
	<a href="#close" class="modal-overlay" aria-label="Close"></a>
	<div class="modal-container">
		<div class="modal-header">
			<h2 class="modal-title"><?php _e( 'Change Form type.', 'egoi-for-wp' ); ?></h2>
			<a href="#close" class="btn btn-clear float-right" aria-label="Close"></a>
		</div>
	<div class="modal-body">
		<div class="content">
			<?php _e( 'Attention! If you change your form you will lose the settings.', 'egoi-for-wp' ); ?>
		</div>
	</div>
		<div class="modal-footer">
			<button id="confirm-btn" class="smsnf-btn primary"><?php _e( 'Confirm', 'egoi-for-wp' ); ?></button>
			<a href="#close" class="smsnf-btn"><?php _e( 'Cancel', 'egoi-for-wp' ); ?></a>
		</div>
	</div>
</div>
