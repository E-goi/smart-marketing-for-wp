<?php

if ( ! defined( 'ABSPATH' ) ) {
	die();
}
$dir = plugin_dir_path( __FILE__ ) . 'capture/';

require_once $dir . '/functions.php';
require_once plugin_dir_path( __FILE__ ) . 'egoi-for-wp-common.php';
$page = array(
	'home' => ! isset( $_GET['subpage'] ),
);

echo get_notification( __( 'Success!', 'egoi-for-wp' ), '', 'success', true );

$options = $this->options_list;
?>

<div class="smsnf">
	<div class="smsnf-modal-bg"></div>
	<!-- Header -->
	<header>
		<div class="wrapper-loader-egoi">
			<h1>Smart Marketing > <b><?php _e( 'Connected Sites', 'egoi-for-wp' ); ?></b></h1>
			<?php echo getLoader( 'egoi-loader', false ); ?>
		</div>
		<nav>
			<ul>
				<li><a class="home <?php echo $page['home'] ? '-select' : ''; ?>" href="?page=egoi-4-wp-trackengage"><?php _e( 'Configuration', 'egoi-for-wp' ); ?></a></li>
			</ul>
		</nav>
	</header>
	<!-- / Header -->
	<!-- Content -->
	<main>
		<!-- Content -->
		<section class="smsnf-content">

			<?php

			if ( ! $options['list'] ) {
				?>
				<div class="postbox" style="margin-bottom:20px; padding:5px 20px 5px; border-left:2px solid red;">
					<div style="padding:10px 0;">
						<span style="color: orangered; margin-top:5px;" class="dashicons dashicons-warning"></span>
						<span style="display: inline-block; line-height: 22px; font-size: 13px; margin-left: 12px; margin-top: 3px;">
						<?php
						_e( 'Select your mailing list in the option "Synchronize users with this list" to activate Connected Sites.<br>You will find this option in ', 'egoi-for-wp' );
						?>
						<a href="admin.php?page=egoi-4-wp-subscribers">
							<?php _e( 'Sync Contacts', 'egoi-for-wp' ); ?></a>
						</span>
					</div>
				</div>
				<?php
			}
			?>


			<div style="padding: 5px 20px 5px;">

				<div>
					<h1><?php _e( 'Connected Sites', 'egoi-for-wp' ); ?></h1>
				</div>

				<div>
					<span style="padding:15px 0;font-size: 13px;display: inline-block;">
						<span style="display: inline-block; text-align: justify;     font-size: 13px;">
							<?php _e( 'Connected Sites is an E-goi content manager feature, connected to your WordPress Website or WooCommerce Store, perfect for remarketing actions like returning users, abandoned cart, forms and much more!', 'egoi-for-wp' ); ?>
							<p>
								<span style="font-size: 13px;"><?php _e( 'Activate this option here, and confirm if Connected Sites is also active in E-goi Platform (Account Settings -> Connected Sites).', 'egoi-for-wp' ); ?></span>
							</p>
							<p>
								<span style="font-size: 13px;"> <?php _e( 'To know more about the feature Connected Sites, check <a target="_blank" href="https://helpdesk.e-goi.com/262312-Connected-Sites-What-is-it-and-how-do-I-use-it">here</a>.', 'egoi-for-wp' ); ?></span>
							</p>
						</span>
					</span>
				</div>

				<?php
				if ( $options['list'] ) {
					?>

					<form method="post" action="#">
					<?php

						settings_fields( Egoi_For_Wp_Admin::OPTION_NAME );
						settings_errors();
					?>

						<div class="smsnf-input-group">
							<label for="track"><?php _e( 'Activate Connected Sites', 'egoi-for-wp' ); ?></label>
							<div class="form-group switch-yes-no">
								<label class="form-switch">
									<input id="track" type="checkbox" name="track" <?php checked( ! empty( $options['domain'] ) ); ?>>
									<i class="form-icon"></i><div class="yes"><?php _e( 'Yes', 'egoi-for-wp' ); ?> <span style="font-size: small;font-weight: 100;color: black;opacity: 50%">(<?php _e( 'Recommended', 'egoi-for-wp' ); ?>)</span></div><div class="no"><?php _e( 'No', 'egoi-for-wp' ); ?></div>
								</label>
							</div>
						</div>

						<div class="smsnf-input-group" id="domain_group" style="<?php echo empty( $options['domain'] ) ? 'display:none' : ''; ?>">
							<label for="domain"><?php _e( 'Domain', 'egoi-for-wp' ); ?></label>
							<p class="subtitle"><?php _e( 'Domain that will be connected', 'egoi-for-wp' ); ?></p>
							<input <?php disabled( ! empty( $options['domain'] ) ); ?> id="domain" style="max-width: 25rem;" name="domain" type="text" placeholder="<?php _e( 'Write website domain', 'egoi-for-wp' ); ?>" value="<?php echo ! empty( $options['domain'] ) ? $options['domain'] : parse_url( get_site_url() )['host']; ?>" required autocomplete="off" />
						</div>

						<div class="egoi-undertable-button-wrapper" style="bottom: 0;position: absolute;right: 30px;">
							<div class="smsnf-input-group">
								<input id="save_connected_sites" type="submit" value="<?php _e( 'Save', 'egoi-for-wp' ); ?>" disabled/>
							</div>
						</div>

					</form>
					<?php

					if ( ! empty( $options['domain'] ) && ! empty( $options['list'] ) ) {
						$api        = new EgoiApiV3( $this->getApikey() );
						$domainData = $api->getConnectedSite( $options['domain'] );

						?>
						<hr class="smsnf-input-group">
						<h5><?php _e( 'Features', 'egoi-for-wp' ); ?></h5>
						<div class="egoi-sub-form-ident">
							<?php
							foreach ( $domainData['features'] as $name => $feat ) {
								if( !empty($feat['enabled']) && empty($feat['items'])){
                                    ?>
                                    <h6><?php echo ucwords( str_replace( '_', ' ', esc_html( $name ) ) ); ?> <span class="egoi-resolved-indicator"></span></h6>
                                    <div class="egoi-sub-form-ident"><p style="padding-bottom: 1em;"><?php _e( 'This feature is active.', 'egoi-for-wp' ); ?></p></div>
                                    <?php
                                    continue;
                                }elseif ( empty( $feat['items'] ) ) {
									?>
										<h6><?php echo ucwords( str_replace( '_', ' ', esc_html( $name ) ) ); ?></h6>
										<div class="egoi-sub-form-ident"><p style="padding-bottom: 1em;opacity: 0.5;"><?php _e( 'Nothing here yet.', 'egoi-for-wp' ); ?></p></div>
										<?php
										continue;
								}
								?>
									<h6><?php echo ucwords( str_replace( '_', ' ', esc_html( $name ) ) ); ?> <span class="egoi-resolved-indicator"></span></h6>
									<div class="egoi-sub-form-ident">
										<table border="0" class="smsnf-table" style="width: fit-content;min-width: 50%;">
											<thead>
											<tr>
											<?php
											foreach ( $feat['items'][0] as $fields => $x ) {
												?>
														<th><?php echo $fields; ?></th>
													<?php
											}
											?>
											</tr>
											</thead>
											<tbody>
											<?php
											foreach ( $feat['items'] as $item ) {
												?>
													<tr>
													<?php
													foreach ( $item as $value ) {
														?>
															<td><?php echo $value; ?></td>
															<?php
													}
													?>
													</tr>
													<?php
											}
											?>
											</tbody>
										</table>
									</div>
									<?php
							}
							?>
						</div>

						<?php
					}
				}
				?>

			</div>

		</section>

		<section class="smsnf-pub">
			<div>
				<?php require 'egoi-for-wp-admin-banner.php'; ?>
			</div>
		</section>
			<!-- / Content -->
	</main>
</div>
