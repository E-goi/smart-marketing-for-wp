<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

add_thickbox();

if ( isset( $_GET['type'] ) ) {

	if ( ( $_GET['type'] == 'html' ) || ( $_GET['type'] == 'popup' ) ) { ?>

		<div class="egoi-box" style="position:fixed;padding-right: 10px;">
			<h4 style="background-color: #fff; padding: 10px 10px;" class="egoi-title"><?php echo __( 'How to integrate the form on the page or article?', 'egoi-for-wp' ); ?></h4>
			<p> 1 - <?php echo __( 'Enter the E-goi account and open the forms tab.', 'egoi-for-wp' ); ?></p>
			<p> 2 - <?php echo __( 'Choose the desired form.', 'egoi-for-wp' ); ?></p>
			<p> 3 - <?php echo __( 'Select the stock button and choose "Publish".', 'egoi-for-wp' ); ?>
			<p> 4 - <?php echo __( 'Add another publication.', 'egoi-for-wp' ); ?>
			<?php
			if ( $_GET['type'] == 'html' ) {
				?>
				<p> 5 - 
				<?php
				echo __( 'Get the <b>Advanced HTML code</b>', 'egoi-for-wp' );
			} elseif ( $_GET['type'] == 'popup' ) {
				?>
				<p> 6 - 
				<?php
				echo __( 'Get the <b>Popup code</b>', 'egoi-for-wp' );
			}
			?>
			<p> 7 - <?php echo __( 'Transcribe code to the blank text box on the plug-in', 'egoi-for-wp' ); ?>
			<p> 8 - <?php echo __( 'Save changes to the plug-in', 'egoi-for-wp' ); ?></p>
		</div>
		<?php
	}
} elseif ( isset( $_GET['page'] ) && $_GET['page'] == 'egoi-4-wp-rssfeed' ) {
	?>

	<div class="egoi-box" style="position:fixed;padding-right: 10px;">
		<h4 class="egoi-title"><?php echo _e( 'Need help?', 'egoi-for-wp' ); ?></h4>
		<p><?php echo _e( 'We have several resources available to help you:', 'egoi-for-wp' ); ?></p>
		<ul class="ul-square">
			<li><a target="_blank" href="<?php echo _e( 'https://helpdesk.e-goi.com/337098-Adding-an-RSS-feed-to-my-email', 'egoi-for-wp' ); ?>">
					<?php echo _e( 'How to insert RSS content into your emails?', 'egoi-for-wp' ); ?>
				</a></li>
			<li><a target="_blank" href="<?php echo _e( 'https://www.e-goi.com/features/integrations-and-plugins/plugin-wordpress-smart-marketing-e-goi/', 'egoi-for-wp' ); ?>">
				<?php echo __( 'General information', 'egoi-for-wp' ); ?></a></li>
			</a></li>
		</ul>
		<p><?php echo _e( 'Did not find the answer to your question? you can use the <a href="https://wordpress.org/support/plugin/smart-marketing-for-wp">support forum at WordPress.org </a> to post your question.', 'egoi-for-wp' ); ?></p>
	</div>
	<?php

} else {
	?>

	<div class="egoi-box" style="position:fixed;padding-right: 10px;">
	<h4 class="egoi-title"><?php echo __( 'Looking for help?', 'egoi-for-wp' ); ?></h4>
	<p><?php echo __( 'We have some resources available to help you in the right direction.', 'egoi-for-wp' ); ?></p>
	<ul class="ul-square">
		<li><a target="_blank" href="<?php echo __( 'https://www.e-goi.com/en/o/smart-marketing-wordpress/', 'egoi-for-wp' ); ?>"><?php echo __( 'General info', 'egoi-for-wp' ); ?></a></li>
		<li><a target="_blank" href="<?php echo __( 'https://helpdesk.e-goi.com/242267-Integrating-E-goi-with-Wordpress', 'egoi-for-wp' ); ?>">
				<?php echo __( 'Knowledge Base', 'egoi-for-wp' ); ?></a></li>
		</a></li>
	</ul>
	<p><?php echo __( 'If your answer can not be found in the resources listed above, please use the <a href="https://wordpress.org/support/plugin/smart-marketing-for-wp/">support forums on WordPress.org</a>.', 'egoi-for-wp' ); ?></p>
	</div><?php } ?>
