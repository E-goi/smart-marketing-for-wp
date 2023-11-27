<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}
?>
<div class="wrap-content wrap-content--list">

		<div class="e-goi-account-list__title">
			<?php echo __( 'Information from your E-goi mailing lists', 'egoi-for-wp' ); ?>
		</div>
		<table border='0' class="widefat striped">
			<thead>
				<tr>
					<th><?php echo _e( 'List ID', 'egoi-for-wp' ); ?></th>
					<th><?php echo _e( 'Public Title', 'egoi-for-wp' ); ?></th>
					<th><?php echo _e( 'Internal Title', 'egoi-for-wp' ); ?></th>
					<th><?php echo _e( 'Settings', 'egoi-for-wp' ); ?></th>
				</tr>
			</thead>
			<?php
			foreach ( $lists as $list ) {

				if ( isset( $list['list_id'] ) ) {
					?>
					<tr>
						<td>
							<?php echo ! empty( $list['list_id'] ) ? esc_html( $list['list_id']) : 0; ?>
						</td>
						<td>
							<?php echo ! empty( $list['public_name'] ) ? esc_html( $list['public_name'] ) : ''; ?>
						</td>
						<td>
							<?php echo ! empty( $list['internal_name'] ) ? esc_html( $list['internal_name'] ) : ''; ?>
						</td>
						<td>
							<a href="https://login.egoiapp.com/#/login/?action=login&menu=sec&from=%2F%3Faction%3Dlista_definicoes_principal%26list%3D<?php echo esc_attr( $list['list_id'] ); ?>%26menu%3Dsec" class='smsnf-btn' style="min-width:125px;" target="_blank" />
							<?php _e( 'Change in E-goi', 'egoi-for-wp' ); ?>
						</a>
						</td>
					</tr>
					<?php
				}
			}
			?>
		</table>

		<div class="e-goi-account--toogle">
			<a type="button" class="button-primary button-primary--custom-add eg-dropdown-toggle">
				<?php echo _e( 'Create List +', 'egoi-for-wp' ); ?>
			</a>
				
			<div style="position:relative">

				<form name='egoi_wp_createlist_form' method='post' action='<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>'>
					
					<div id="e-goi-create-list" style="display: none;">
						<div class="e-goi-account-lists--create-name e-goi-fcenter">
							<span>
								<label for="egoi_wp_title"><?php echo _e( 'Name', 'egoi-for-wp' ); ?></label>
							</span>
							<span>
								<input type='text' size='60' name='egoi_wp_title' autofocus required="required" />
							</span>
						</div>

						<input type='submit' class='button-primary' name='egoi_wp_createlist' id='egoi_wp_createlist' value='<?php echo _e( 'Save', 'egoi-for-wp' ); ?>' />
						<a style="margin-left:10px;" class='link cancel-toggle'><?php echo _e( 'Cancelar', 'egoi-for-wp' ); ?></a>
					</div>
					
				</form>
			</div>
		</div>
	</div><!-- .wrap -->
