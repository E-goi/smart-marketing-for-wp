<?php
defined( 'ABSPATH' ) or exit;

add_thickbox();

if(isset($_GET['type'])){
	
	if(($_GET['type'] == 'html') || ($_GET['type'] == 'popup')){ ?>

		<div class="egoi-box" style="position:fixed;">
			<h4 class="egoi-title"><?php echo __( 'How to integrate the form on the page or article?', 'egoi-for-wp' );?></h4>
			<p> - <?php echo __( 'Enter the E-goi account and open the forms tab.', 'egoi-for-wp' );?></p>
			<p> - <?php echo __( 'Choose the desired form.', 'egoi-for-wp' );?></p>
			<p> - <?php echo __( 'Select the stock button and choose "Publish".', 'egoi-for-wp' );?>
			<p> - <?php echo __( 'Add another publication.', 'egoi-for-wp' );?>
			<?php 
			if($_GET['type'] == 'html'){ ?>
				<p> - <?php echo __( 'Get the <b>Advanced HTML code</b>', 'egoi-for-wp' );
			}else if($_GET['type'] == 'popup'){ ?>
				<p> - <?php echo __( 'Get the <b>Popup code</b>', 'egoi-for-wp' );
			} ?>
			<p> - <?php echo __( 'Transcribe code to the blank text box on the plug-in', 'egoi-for-wp' );?>
			<p> - <?php echo __( 'Save changes to the plug-in', 'egoi-for-wp' );?></p>
		</div><?php
	}

}else{?>

	<div class="egoi-box" style="position:fixed;">
		<h4 class="egoi-title"><?php echo __( 'Looking for help?', 'egoi-for-wp' ); ?></h4>
		<p><?php echo __( 'We have some resources available to help you in the right direction.', 'egoi-for-wp' ); ?></p>
		<ul class="ul-square">
			<li><a href="<?php echo __( 'https://www.e-goi.com/en/o/smart-marketing-wordpress/', 'egoi-for-wp' ); ?>"><?php echo __( 'General info', 'egoi-for-wp' ); ?></a></li>
			<li><a href="<?php echo __( 'https://helpdesk.e-goi.com/242267-Integrating-E-goi-with-Wordpress', 'egoi-for-wp' ); ?>">
				<?php echo __( 'Knowledge Base', 'egoi-for-wp' ); ?></a></li>
			</a></li>
		</ul>
		<p><?php echo __( 'If your answer can not be found in the resources listed above, please use the <a href="https://wordpress.org/support/plugin/smart-marketing-for-wp/">support forums on WordPress.org</a>.', 'egoi-for-wp' ); ?></p>
	</div><?php

}
