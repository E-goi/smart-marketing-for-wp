jQuery( document ).ready(
	function($) {

		'use strict';

		new ClipboardJS( '#e-goi_shortcode' );
		// initialize class to parse URLs
		var urlObj = new URL( window.location.href );

		// Async fetch
		var page = urlObj.searchParams.get( "page" );
		if (typeof page != 'undefined') {
			if (page == 'egoi-4-wp-form') {

				var data_lists       = {
					action: 'egoi_get_lists'
				};
				var select_lists_frm = $( '#e-goi-list-frm' );
				var select_form      = $( '#formid_egoi' );
				var select_lists_bar = $( '#e-goi-list-bar' );
				var current_lists    = [];

				$( ".loading_lists" ).addClass( 'spin' ).show();
				var lists_count_frm    = $( '#e-goi-lists_ct_forms' );
				var lists_count_bar    = $( '#e-goi-lists_ct_bar' );
				var form_to_subscriber = $( "#e-goi-forms" );
				var lang               = $( "#lang_bar" );

				$.post(
					url_egoi_script.ajaxurl,
					data_lists,
					function(response) {
						$( ".loading_lists" ).removeClass( 'spin' ).hide();
						current_lists = JSON.parse( response );
						if (!current_lists) {
							$( '.e-goi-lists_not_found' ).show();

							select_lists_frm.hide();
							select_lists_bar.hide();

							select_form.hide();
						} else {
							select_lists_frm.show();
							if (form_to_subscriber.text() != '') {
								select_form.show();
							}

							$( '.e-goi-lists_not_found' ).hide();

							$.each(
								current_lists,
								function(key, val) {

									if (typeof val['list_id'] != 'undefined') {
										select_lists_frm.append( $( '<option />' ).val( val['list_id'] ).text( val['public_name'] ) );
										select_lists_bar.append( $( '<option />' ).val( val['list_id'] ).text( val['public_name'] ) );

										if (lists_count_frm.text() === val['list_id']) {
											select_lists_frm.val( val['list_id'] );
											if (form_to_subscriber.text() != '') {
												select_form.append( $( '<option />' ).val( form_to_subscriber.text() ).text( form_to_subscriber.text() ) );
											}
										}

										if (lists_count_bar.text() === val['list_id']) {
											select_lists_bar.val( val['list_id'] );
										}
									}
								}
							);

							select_lists_bar.show();
						}
					}
				);
			}
		}
		// End of Async fetch

		$( '#rcv_e-goi_forms' ).text( $( '#ct_e-goi_forms' ).text() );

		$( '#egoi4wp-form-hide' ).hide();
		$( '#wp-form_content-editor-tools' ).append( '<b>Editor</b>' );

		var $context = $( document.getElementsByClassName( 'wrap' ) );
		$context.find( '.color' ).wpColorPicker();

		var $content    = $( document.getElementById( 'tab-content' ) );
		var $appearance = $( document.getElementById( 'tab-appearance' ) );

		$appearance.hide();

		$( '#nav-tab-content' ).click(
			function() {

				$content.show();
				$appearance.hide();

				$( '#nav-tab-content' ).addClass( 'nav-tab-active' );
				$( '#nav-tab-appearance' ).removeClass( 'nav-tab-active' );
			}
		);

		$( '#nav-tab-appearance' ).click(
			function() {

				$appearance.show();
				$content.hide();

				$( '#nav-tab-appearance' ).addClass( 'nav-tab-active' );
				$( '#nav-tab-content' ).removeClass( 'nav-tab-active' );
			}
		);

		$( '#close_egoi' ).click(
			function() {
				$( '#TB_closeWindowButton' ).trigger( "click" );
			}
		);

		// POPUP
		// open popup
		$( ".cd-popup-trigger-del" ).click(
			function() {
				var id   = $( this ).data( 'id-form' );
				var type = $( this ).data( 'type-form' );

				event.preventDefault();
				$( '.cd-popup-del-form' ).filter(
					function(){
						var popup = false;
						if ($( this ).data( 'id-form' ) === id && $( this ).data( 'type-form' ) === type) {
							popup = true;
						}
						return popup;
					}
				).addClass( 'is-visible' );

			}
		);

		$( '.cd-popup-trigger-change' ).on(
			'click',
			function(event){
				event.preventDefault();
				$( '.cd-popup-change-form' ).addClass( 'is-visible' );
			}
		);

		// close popup
		$( '.cd-popup' ).on(
			'click',
			function(event){
				if ( $( event.target ).is( '.cd-popup-close-btn' ) || $( event.target ).is( '.cd-popup' ) ) {
					event.preventDefault();
					$( this ).removeClass( 'is-visible' );
				}
			}
		);

		// cancel btn on change form
		$( '#close_frm_change' ).on(
			'click',
			function(){
				$( '#form_choice' ).val( $( '#type_frm_saved' ).val() );
			}
		);

		// alert popup on change form type
		$( '#change_form_req' ).click(
			function(){
				document.getElementById( "e-goi-form-options" ).submit();
			}
		);

		$( document ).on(
			'keyup',
			function(evt) {
				if (evt.keyCode == 27) {
					if ($( '.cd-popup' ).hasClass( 'is-visible' )) {
						$( '.cd-popup' ).removeClass( 'is-visible' );
						$( '#form_choice' ).val( $( '#type_frm_saved' ).val() );
					}
				}
			}
		);

		$( document ).on(
			'click',
			function(e) {
				var element = e.target;

				if (element.id && element.id == 'change-form') {
					$( '#form_choice' ).val( $( '#type_frm_saved' ).val() );
				}
			}
		);

		// OTHER THINGS
		$( '#get_type_form' ).click(
			function() {
				$( '#form_type' ).trigger( "click" );
			}
		);

		var c = urlObj.searchParams.get( "type" );
		if (c != 'form') {
			$( '#egoi4wp-form-hide' ).show();
		} else {
			$( '#egoi4wp-form-hide' ).hide();
		}

	}
);
