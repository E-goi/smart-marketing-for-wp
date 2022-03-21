<?php

function getLoader( $id, $on = true, $spacer = false, $width = false ) {
	$class  = $on == false ? ' style="display: none;" ' : ' ';
	$spacer = $spacer == true ? '&nbsp;&nbsp;' : '';
	$width  = $width ? "width:$width" . 'px;' : '';
	return '<div id="' . $id . '" class="loader-egoi-self" role="status"' . $class . '>' . $spacer . '<i class="loading" style="' . $width . '">' . __( 'Loading...', 'egoi-for-wp' ) . '</i></div>';
}

function getLoaderNew( $id, $on = true ) {
	$style = empty( $on ) ? 'display: none;' : '';
	return '<div class="loading" id="' . $id . '" style="' . $style . '"></div>';
}

function getProductImportModal() {
	?>
		<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="egoi-modal-header modal-header">
						<button type="button" class="close no-border-button" data-dismiss="modal" aria-label="Close">
							&times;
						</button>
						<h2 class="modal-title" id="modalLabel"><?php _e( 'Import Catalog: ', 'egoi-for-wp' ); ?> <span id="display-selected"></span></h2>
					</div>
					<div class="modal-body nav">
						<input type="hidden" id="selected-import-catalog">
						<span><?php _e( 'This will import all your store\'s products!', 'egoi-for-wp' ); ?></span>
						<span><?php _e( 'After this all new products will be synchronized to the selected catalog.', 'egoi-for-wp' ); ?></span>
						<div style="display: flex;"><h4><?php _e( 'Products to import: ', 'egoi-for-wp' ); ?> <span id="display-number-products"></span></h4><?php echo getLoader( 'egoi-loader-products', false ); ?></div>
						<div id="loading-import" style="display: none;">
							<span><?php _e( 'Products left', 'egoi-for-wp' ); ?>: <span id="egoi-left-products"></span></span>
							<div class="progress">
								<div id="progressbar-import" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button id="start-import-catalog" type="button" class="button-primary" disabled><?php _e( 'Start', 'egoi-for-wp' ); ?></button>
					</div>
				</div>
			</div>
		</div>
	<?php
}
function getHomeSvg(){
    ?>
    <svg class="smsnfCapture__header__menu__item__homeIcon" version="1.1" id="Camada_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 30.3 27.1" style="enable-background:new 0 0 30.3 27.1;" xml:space="preserve"><style type="text/css">.st0{clip-path:url(#SVGID_4_);}.st1{clip-path:url(#SVGID_6_);}</style><g><g><path id="SVGID_1_" d="M1.2,16.1h2.4v9.8c0,0.6,0.5,1,1,1h7.2c0.6,0,1-0.5,1-1v-7.2h4.4v7.2c0,0.6,0.5,1,1,1H25c0.6,0,1-0.5,1-1v-9.8h3c0.4,0,0.8-0.3,1-0.6c0.2-0.4,0.1-0.8-0.2-1.1L16.2,0.6c-0.4-0.4-1.1-0.4-1.5,0L0.5,14.3c-0.3,0.3-0.4,0.8-0.2,1.2C0.4,15.8,0.8,16.1,1.2,16.1z M15.5,2.7L26.7,14h-1.5c-0.6,0-1,0.5-1,1v9.8h-4.7v-7.2c0-0.6-0.5-1-1-1H12c-0.6,0-1,0.5-1,1v7.2H5.9V15c0-0.6-0.5-1-1-1H4L15.5,2.7z"/></g><g><defs><path id="SVGID_2_" d="M1.2,16.1h2.4v9.8c0,0.6,0.5,1,1,1h7.2c0.6,0,1-0.5,1-1v-7.2h4.4v7.2c0,0.6,0.5,1,1,1H25c0.6,0,1-0.5,1-1v-9.8h3c0.4,0,0.8-0.3,1-0.6c0.2-0.4,0.1-0.8-0.2-1.1L16.2,0.6c-0.4-0.4-1.1-0.4-1.5,0L0.5,14.3c-0.3,0.3-0.4,0.8-0.2,1.2C0.4,15.8,0.8,16.1,1.2,16.1z M15.5,2.7L26.7,14h-1.5c-0.6,0-1,0.5-1,1v9.8h-4.7v-7.2c0-0.6-0.5-1-1-1H12c-0.6,0-1,0.5-1,1v7.2H5.9V15c0-0.6-0.5-1-1-1H4L15.5,2.7z"/></defs><clipPath id="SVGID_4_"><use xlink:href="#SVGID_2_"  style="overflow:visible;"/></clipPath><g class="st0"><g><rect id="SVGID_3_" x="-188.8" y="-134.9" width="1920" height="1080"/></g><g><defs><rect id="SVGID_5_" x="-188.8" y="-134.9" width="1920" height="1080"/></defs><clipPath id="SVGID_6_"><use xlink:href="#SVGID_5_"  style="overflow:visible;"/></clipPath><rect x="-4.8" y="-4.9" class="st1" width="40" height="36.8"/></g></g></g></g></svg>
    <?php
}

?>
<style>
	#wpcontent {
		padding: 0;
		background-color: #f4f8fa;
		height: auto;
		min-height: calc( 100vh - 32px);
	}
	#wpwrap{
		background-color: #f4f8fa;
		position: initial;
	}
	#wpfooter{
		position: inherit;
	}
	#wpbody-content > .update-nag, #wpbody-content > .notice, #wpbody-content > .notice-warning{
		display: none;
	}
</style>
