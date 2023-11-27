<?php
require_once plugin_dir_path( __FILE__ ) . '../../../includes/class-egoi-for-wp-popup.php';
$simple_forms = get_simple_forms();
$popups = EgoiPopUp::getSavedPopUps();
?>

<h3><?php _e( 'Your Simple Forms list', 'egoi-for-wp' ); ?></h3>

<?php if ( count( $simple_forms ) == 0 ) : ?>
	<p><?php _e( 'No simple forms', 'egoi-for-wp' ); ?></p>
<?php else : ?>
	<table class="smsnf-table">
		<thead>
			<tr>
				<th>Shortcode</th>
				<th>ID</th>
				<th><?php _e( 'Title', 'egoi-for-wp' ); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $simple_forms as $simple_form ) : ?>
				<?php
				$id              = $simple_form->ID;
					$shortcode   = sprintf( '[egoi-simple-form id="%d"]', $id );
					$enable      = $simple_form->post_status == 'publish';
					$title       = $simple_form->post_title;
					$edit_link   = sprintf( '?page=egoi-4-wp-form&sub=simple-forms&edit_simple_form=1&form=%d', $id );
					$delete_link = sprintf( '?page=egoi-4-wp-form&del_simple_form=%d', $id );
				?>
				<tr>
					<td style="width:1%">
						<div class="shortcode">
							<div class="shortcode"
								data-clipboard-text="<?php echo esc_html( $shortcode ); ?>"><?php echo esc_html( $shortcode ); ?></div>
							<div class="eg_tooltip tooltip-right shortcode -copy"
									data-tooltip="<?php _e( 'Copy', 'egoi-for-wp' ); ?>"
									data-before="<?php _e( 'Copy', 'egoi-for-wp' ); ?>"
									data-after="<?php _e( 'Copied', 'egoi-for-wp' ); ?>"
									data-clipboard-text="<?php echo esc_html( $shortcode ); ?>"
								>
								<?php _e( 'Copy', 'egoi-for-wp' ); ?>
							</div>
						</div>
					</td>
					<td style="width:1%"><?php echo esc_html( $id ); ?></td>
					<td><?php echo esc_html( $title ); ?></td>
					<!--<td class="<?php echo $enable ? '-enable' : '-disable'; ?>"><?php echo $enable ? 'Ativo' : 'Inativo'; ?></td>-->
					<td style="width:1%">
						<a class="smsnf-btn" href="<?php echo esc_url($edit_link); ?>"><?php _e( 'Edit', 'egoi-for-wp' ); ?></a>
						<a class="smsnf-btn delete-adv-form" href="<?php echo esc_attr( $delete_link ); ?>"><?php _e( 'Delete', 'egoi-for-wp' ); ?></a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>

<h3><?php _e( 'Your Popup List', 'egoi-for-wp' ); ?></h3>

<?php if ( count( $popups ) == 0 ) : ?>
	<p><?php _e( 'No Popup yet', 'egoi-for-wp' ); ?></p>
<?php else : ?>
	<table class="smsnf-table">
		<thead>
		<tr>
			<th style="width:1%">ID</th>
			<th style="width:1%"><?php _e( 'Type', 'egoi-for-wp' ); ?></th>
			<th style="width:98%"><?php _e( 'Title', 'egoi-for-wp' ); ?></th>
			<th style="width:1%"></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ( $popups as $form ) : ?>
			<?php
			$popup_data = ( new EgoiPopUp( sanitize_key($form) ) )->getPopupSavedData();

			$edit_link   = sprintf( '?page=egoi-4-wp-form&sub=popup&popup_id=%d', sanitize_key($form) );
			$delete_link = sprintf( '?page=egoi-4-wp-form&del_popup=%d', sanitize_key($form) );
			?>
			<tr <?php if(isset($_GET['highlight'])) {echo $form == $_GET['highlight'] ? 'class="pulse-highlight"' : ''; }?>>
				<td><?php echo esc_textarea($form); ?></td>
				<td><?php _e( 'Popup', 'egoi-for-wp' ); ?></td>
				<td><?php echo esc_textarea($popup_data['title']); ?></td>
				<td>
					<a class="smsnf-btn" href="<?php echo esc_attr( $edit_link ); ?>"><?php _e( 'Edit', 'egoi-for-wp' ); ?></a>
					<a class="smsnf-btn delete-adv-form" href="<?php echo esc_attr( $delete_link ); ?>"><?php _e( 'Delete', 'egoi-for-wp' ); ?></a>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>

<script>

	jQuery(document).ready(function($) {
		$(".pulse-highlight").each(function( index ) {
			$(this).css('filter', 'none')
		});

	});
</script>
