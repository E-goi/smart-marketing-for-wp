jQuery( document ).ready(
	function($) {
		'use strict';

		var $context = $( document.getElementById( 'egoi4wp-admin' ) );
		$context.find( '.color' ).wpColorPicker();

		var codeCopy = $( ".egoi-copy-code" );

		codeCopy.on(
			'click',
			(e) => {
            e.preventDefault()
				copyStringToClipboard( $( e.target ).parent().children( "code" ).text() )
			}
		)

		function copyStringToClipboard (str) {
			// Create new element
			var el = document.createElement( 'textarea' );
			// Set value (string to be copied)
			el.value = str;
			// Set non-editable to avoid focus and move outside of view
			el.setAttribute( 'readonly', '' );
			el.style = {position: 'absolute', left: '-9999px'};
			document.body.appendChild( el );
			// Select text inside element
			el.select();
			// Copy text to clipboard
			document.execCommand( 'copy' );
			// Remove temporary element
			document.body.removeChild( el );
		}


		$( ".e-goi-account-apikey--grp--form__input" ).on(
			'input',
			function() {
				var btn_submit = $( "#save_apikey" );
                if(btn_submit.length != 0){
                    btn_submit[0].classList.add( 'disabled' );
                    btn_submit.unbind( 'click' );
                }
                $( ".icon-error" ).hide();

				var key = $( this ).val();
				if (key.length == 40) {

					$( ".icon-load" ).show();
					$( ".icon-valid" ).hide();
					$( ".icon-error" ).hide();

					$.ajax(
						{
							url: egoi_config_ajax_object.ajax_url,
							type: 'POST',
							data:({
								security:   egoi_config_ajax_object.ajax_nonce,
								action:     'egoi_change_api_key',
								egoi_key: key
							}),
						success:function(data, status) {
							if (status == '404' || (data.data && data.data.ERROR) ) {
								$( ".icon-error" ).show();
								$( ".icon-valid" ).hide();
								$( ".icon-load" ).hide();
							} else {
								$( ".icon-valid" ).show();
								$( ".icon-error" ).hide();
								$( ".icon-load" ).hide();
                                if(btn_submit.length != 0){
                                    btn_submit[0].classList.remove( 'disabled' );
                                    btn_submit.bind( 'click', 			function(e){
                                        e.preventDefault();
                        
                                        if ($( '#apikey' ).val() != $( '#old_apikey' ).val()) {
                                            var confirmation = confirm( $( '#confirm_text' ).text() );
                                            if (confirmation) {
                        
                                                var data = {
                                                    security:   egoi_config_ajax_object_core.ajax_nonce,
                                                    action: 'efwp_apikey_changes'
                                                };
                        
                                                $.post(
                                                    egoi_config_ajax_object_core.ajax_url,
                                                    data,
                                                    function(response) {
                                                        response = JSON.parse( response );
                                                        if (response.result == 'ok' && $( '#apikey' ).val() != '') {
                                                            $( 'form[name="egoi_apikey_form"]' ).submit();
                                                        }else{
                                                            window.location.reload()
                                                        }
                                                    }
                                                );
                        
                                            } else {
                                                return false;
                                            }
                                        } else {
                                            $( 'form[name="egoi_apikey_form"]' ).submit();
                                        }
                                    } );
                                }
							}
						},
							error:function(status){
								if (status) {
									$( ".icon-valid" ).hide();
									$( ".icon-error" ).show();
									$( ".icon-load" ).hide();
									$( "#api-save-text" ).show();
								}
							}
						}
					);

				} else {
					$( "#save_apikey" ).prop( 'disabled', true );
					$( "#valid" ).hide();
				}

			}
		);

		$( "#egoi_api_key_input" ).on(
			'input',
			function() {
				var btn_submit = $( "#egoi_4_wp_login" );
				btn_submit.prop( 'disabled', true );

				var key = $( this ).val();
				if (key.length == 40) {

					$( ".icon-load" ).show();
					$( ".icon-valid" ).hide();
					$( ".icon-error" ).hide();

					$.ajax(
						{
							url: egoi_config_ajax_object.ajax_url,
							type: 'POST',
							data:({
								security:   egoi_config_ajax_object.ajax_nonce,
								action:     'egoi_change_api_key',
								egoi_key: key
							}),
						success:function(data, status) {
							if (status == '404' || (data.data && data.data.ERROR) ) {
								$( ".icon-error" ).show();
								$( ".icon-valid" ).hide();
								$( ".icon-load" ).hide();
							} else {
								$( ".icon-valid" ).show();
								$( ".icon-error" ).hide();
								$( ".icon-load" ).hide();
								btn_submit.prop( 'disabled', false );
							}
						},
							error:function(status){
								if (status) {
									$( ".icon-valid" ).hide();
									$( ".icon-error" ).show();
									$( ".icon-load" ).hide();
									$( "#api-save-text" ).show();
								}
							}
						}
					);

				} else {
					$( "#egoi_4_wp_login" ).prop( 'disabled', true );
					$( "#valid" ).hide();
				}

			}
		);

		$( '#egoi_4_wp_login' ).on(
			'click',
			function(e){
				e.preventDefault();
				$( this ).prop( 'disabled', true );
				$( 'form[name="egoi_apikey_form"]' ).submit();
			}
		);

		$('form[name="egoi_apikey_form"]').submit( (e) => {
			e.preventDefault();

			var data = {
				security:   egoi_config_ajax_object_core.ajax_nonce,
				action: 'efwp_apikey_save',
				apikey: jQuery("#apikey").val()?jQuery("#apikey").val():jQuery("#egoi_api_key_input").val()
			};

			jQuery.post(egoi_config_ajax_object_core.ajax_url, data, function(response) {
				if(!response.success){
					//show error here
					alert(response.data)
					return;
				}

				if(response.data.redirect){
					window.location = response.data.redirect
				}else{
					window.location.reload()
				}

				//window.location.reload();
			});

		})

		$( '#save_apikey' ).on(
			'click',
			function(e){
				e.preventDefault();

				if ($( '#apikey' ).val() != $( '#old_apikey' ).val()) {
					var confirmation = confirm( $( '#confirm_text' ).text() );
					if (confirmation) {

						var data = {
							security:   egoi_config_ajax_object_core.ajax_nonce,
							action: 'efwp_apikey_changes'
						};

						$.post(
							egoi_config_ajax_object_core.ajax_url,
							data,
							function(response) {
								response = JSON.parse( response );
								if (response.result == 'ok' && $( '#apikey' ).val() != '') {
									$( 'form[name="egoi_apikey_form"]' ).submit();
								}else{
									window.location.reload()
								}
							}
						);

					} else {
						return false;
					}
				} else {
					$( 'form[name="egoi_apikey_form"]' ).submit();
				}
			}
		)

		// remove data from WP
		$( '#egoi_remove_data' ).on(
			'click',
			function() {

				var rmdata = $( 'input[name="egoi_data[remove]"]:checked' ).val();
				$( '#load_data' ).show();

				$.ajax(
					{
						type: 'POST',
						data:({
							security:   egoi_config_ajax_object_core.ajax_nonce,
							action: 'efwp_remove_data',
							rmdata: rmdata
						}),
						url: egoi_config_ajax_object_core.ajax_url,
						success:function(data, status) {
							$( "#remove_valid" ).show();
							$( "#load_data" ).hide();
							$( ".icon-error" ).hide();
						},
						error:function(status){
							if (status) {
								$( "#remove_valid" ).hide();
								$( ".icon-error" ).show();
							}
						}
					}
				);
			}
		);

		// Dropdown toggle
		$( '.button-primary--custom-add' ).on(
			'click',
			function(){
				$( '#e-goi-create-list' ).show();
				$( this ).hide();
			}
		);

		$( '.cancel-toggle' ).on(
			'click',
			function(){
				$( '#e-goi-create-list' ).hide();
				$( '.button-primary--custom-add' ).show();
			}
		);

		// Hidden options
		$( '.egoi_json_trigger' ).change(
			function() {
				if ( $( '#egoi_track_social' ).is( ':checked' ) ) {
					  $( '#egoi_track_json' ).show();
				} else {
					$( '#egoi_track_json' ).hide();
				}
			}
		);
	}
);
