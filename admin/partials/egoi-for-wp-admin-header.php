<!-- <div class="container">
	<div class="columns">
		<div class="column">
			<div class="smsnf-header">
				<span class="smsnf-header__logo"></span>
				<h1>Smart Marketing - <?php _e( 'Dashboard', 'egoi-for-wp' ); ?></h1>
			</div>
			<div class="smsnf-header__breadcrumbs">
			<span class="prefix">
				<?php echo __( 'You are here: ', 'egoi-for-wp' ); ?>
			</span>
				<strong>Smart Marketing</a> &rsaquo;
					<a href="#">
						<span class="current-crumb">
						<?php _e( 'Dashboard', 'egoi-for-wp' ); ?>
					</a>
				</strong>
				</span>
			</div>
			<hr/>
		</div>
	</div>
</div> -->


<h1 class="logo">Smart Marketing - <?php _e( 'Dashboard', 'egoi-for-wp' ); ?></h1>
	<p class="breadcrumbs">
		<span class="prefix"><?php echo __( 'You are here: ', 'egoi-for-wp' ); ?></span>
		<strong>Smart Marketing &rsaquo;
		<?php
		if ( isset( $_GET['form'] ) && ( $_GET['type'] ) && ( $_GET['form'] <= 5 ) ) {
			?>
			<a href="<?php echo admin_url( 'admin.php?page=egoi-4-wp-form' ); ?>"><?php _e( 'Dashboard', 'egoi-for-wp' ); ?></a> &rsaquo;
			<span class="current-crumb"><?php _e( 'Dashboard ' . $form_id, 'egoi-for-wp' ); ?></strong></span>
																		<?php
		} else {
			?>
			<span class="current-crumb"><?php _e( 'Dashboard', 'egoi-for-wp' ); ?></strong></span>
															  <?php
		}
		?>
	</p>
