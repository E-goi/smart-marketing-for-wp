jQuery.fn.rotate = function(degrees) {
	jQuery( this ).animate(
		{ deg: degrees },
		{
			duration: 500,
			step: function(now) {
				jQuery( this ).css( { transform: 'rotate(' + now + 'deg)' } );
			}
		}
	);
	return jQuery( this );
};

jQuery(document).ready(function ($) {

    $('.cd-popup-trigger-del').on('click', function (e) {
        e.preventDefault();
    
        // Get the data attributes
        var idForm = $(this).data('id-form');
        var typeForm = $(this).data('type-form');
    

        var data = {
            security: egoi_config_ajax_object_rss.ajax_nonce,
            action: 'egoi_remove_rss',
            rssId: idForm
        };

        $.post(
            egoi_config_ajax_object_rss.ajax_url,
            data,
            function(response) {
                location.reload();
            }
        );
    });
    
});

jQuery( document ).ready(
	function() {

		jQuery( '.js-example-basic-multiple' ).select2();

		jQuery( ".cats_tags_titles" ).hide();
		jQuery( ".post_cats_tags" ).hide();
		jQuery( ".product_cats_tags" ).hide();

		var type = jQuery( 'input[type=radio][name=type]:checked' ).val();
		if (type == 'posts') {
			jQuery( ".post_cats_tags" ).show();
			jQuery( ".cats_tags_titles" ).show();
		} else if (type == 'products') {
			jQuery( ".product_cats_tags" ).show();
			jQuery( ".cats_tags_titles" ).show();
		}

		jQuery( 'input[type=radio][name=type]' ).on('change',
			function(e) {
				jQuery( ".cats_tags_titles" ).show();
				if (this.value == 'posts') {
					jQuery( ".post_cats_tags" ).show();
					jQuery( ".product_cats_tags" ).hide();
				} else if (this.value == 'products') {
					jQuery( ".post_cats_tags" ).hide();
					jQuery( ".product_cats_tags" ).show();
				}
			}
		);

		jQuery( "#egoi_toggle_create_campaign" ).on(
			"click",
			function () {
				var table = jQuery( '#egoi_create_campaign' );
				var arrow = jQuery( '#egoi_campaign_arrow' );
				var icon  = jQuery( '#egoi_campaign_on_off' );
				if (table.is( ':visible' )) {
					table.hide( 500 );
					arrow.rotate( 0 );
					icon.removeClass( 'fa-toggle-on' );
					icon.addClass( 'fa-toggle-off' );
				} else {
					table.show( 500 );
					arrow.rotate( 180 );
					icon.removeClass( 'fa-toggle-off' );
					icon.addClass( 'fa-toggle-on' );
				}

			}
		);

		jQuery( "#egoi_add_campaign" ).change(
			function() {
				var form = jQuery( ".egoi_create_campaign_table" );
				if (this.value == 0) {
					form.hide( 200 );
				} else {
					getLists();
					getSenders();
					form.show( 200 );
				}

			}
		);

		jQuery( "#egoi_add_campaign_webpush" ).change(
			function() {
				var form = jQuery( ".egoi_create_campaign_webpush_table" );
				if (this.value == 0) {
					form.hide( 200 );
				} else {
					form.show( 200 );
				}

			}
		);

		jQuery( ".number-spinner button" ).on(
			"click",
			function () {

				var btn  = jQuery( this ),
				oldValue = btn.closest( '.number-spinner' ).find( 'input' ).val().trim();

				if (btn.attr( 'data-dir' ) == 'up') {
					if (parseInt( oldValue ) >= 10) {
						return;
					}
					oldValue = parseInt( oldValue ) + 1;
				} else {
					if (oldValue > 1) {
						oldValue = parseInt( oldValue ) - 1;
					} else {
						oldValue = 1;
					}
				}
				btn.closest( '.number-spinner' ).find( 'input' ).val( oldValue );
			}
		);

		jQuery( "#egoi_create_campaign_webpush" ).on(
			"click",
			function () {
				var feed = jQuery( "#egoi_add_campaign_webpush" ),
				title    = jQuery( "#campaign_title_webpush" ),
				button   = jQuery( "#egoi_create_campaign_webpush" ),
				loading  = jQuery( "#egoi_create_campaign_webpush_loading" );

				if ( ! valid( [title] )) {
					return;
				}

				feed.attr( 'disabled', true );
				title.attr( 'disabled', true );
				button.attr( 'disabled',true );
				loading.show();

				var campaign = {
					security:   egoi_config_ajax_object.ajax_nonce,
					action:     'egoi_rss_campaign_webpush',
					feed:       feed.val(),
					title:      title.val()
				};

				jQuery.post(
					egoi_config_ajax_object.ajax_url,
					campaign,
					function(response) {
						loading.hide();
						response = JSON.parse( response );
						if (typeof response.ERROR != 'undefined') {
							clearWebpushForm();
							alert( response.ERROR );
							return false;
						}
						if (typeof response.campaign_hash == 'undefined') {
							clearWebpushForm();
							console.log( response );
							alert( 'error' );
							return false;
						}
						button.hide( 200 );
						jQuery( "#campaign_hash_deploy_webpush" ).val( response.campaign_hash );
						jQuery( "#campaign_list_id_deploy_webpush" ).val( response.list_id );

						var edit = jQuery( "#egoi_edit_campaign_webpush" );
						var send = jQuery( "#egoi_send_campaign_webpush" );
						edit.show( 200 );
						send.show( 200 );
					}
				);

			}
		);

		jQuery( "#egoi_create_campaign" ).on(
			"click",
			function () {

				var feed = jQuery( "#egoi_add_campaign" ),
				subject  = jQuery( "#campaign_subject" ),
				snippet  = jQuery( "#campaign_snippet" ),
				items    = jQuery( ".number-spinner button" ).closest( '.number-spinner' ).find( 'input' ).val().trim(),
				list     = jQuery( "#egoi_list" ),
				sender   = jQuery( "#egoi_senders" ),
				title    = jQuery( "#campaign_title" ),
				button   = jQuery( "#egoi_create_campaign" ),
				loading  = jQuery( "#egoi_create_campaign_loading" );

				if ( ! valid( [subject,snippet,list,sender,title] )) {
					return;
				}

				onOffForm( false );

				var campaign = {
					security:   egoi_config_ajax_object.ajax_nonce,
					action:     'egoi_rss_campaign',
					list:       list.val(),
					sender:     sender.val(),
					feed:       feed.val(),
					subject:    subject.val(),
					snippet:    snippet.val(),
					items:      items,
					title:      title.val()
				};

				jQuery.post(
					egoi_config_ajax_object.ajax_url,
					campaign,
					function(response) {
						loading.hide();
						if (typeof response.data.campaign_hash == 'undefined') {
							console.log( 'error' );
							return false;
						}
						button.hide();
						jQuery( "#campaign_hash_deploy" ).val( response.data.campaign_hash );
						jQuery( "#campaign_list_id_deploy" ).val( list.val() );

						var edit = jQuery( "#egoi_edit_campaign" );
						var send = jQuery( "#egoi_send_campaign" );
						edit.show( 200 );
						send.show( 200 );

					}
				);
			}
		);

		jQuery( "#egoi_edit_campaign" ).on(
			"click",
			function () {
				var win = window.open( 'https://login.egoiapp.com/login?from=%2F%3Faction%3Dui#/messages/email/rss/wizard/' + jQuery( "#campaign_list_id_deploy" ).val() + '/' + jQuery( "#campaign_hash_deploy" ).val() + '/edit', '_blank' );
				if (win) {
					var edit = jQuery( "#egoi_edit_campaign" );
					var send = jQuery( "#egoi_send_campaign" );
					edit.hide( 200 );
					send.hide( 200 );
					onOffForm( true );
					clearForm();
					win.focus();
				} else {
					alert( 'Please allow popups for this website' );
				}
			}
		);

		jQuery( "#egoi_edit_campaign_webpush" ).on(
			"click",
			function () {
				var win = window.open( 'https://login.egoiapp.com/login?from=%2F%3Faction%3Dui#/messages/webpush/rss/wizard/' + jQuery( "#campaign_list_id_deploy_webpush" ).val() + '/' + jQuery( "#campaign_hash_deploy_webpush" ).val() + '/edit', '_blank' );
				if (win) {
					var edit = jQuery( "#egoi_edit_campaign_webpush" );
					var send = jQuery( "#egoi_send_campaign_webpush" );
					edit.hide( 200 );
					send.hide( 200 );
					clearWebpushForm();
					win.focus();
				} else {
					alert( 'Please allow popups for this website' );
				}
			}
		);

		jQuery( "#egoi_send_campaign" ).on(
			"click",
			function () {
				jQuery( "#egoi_send_campaign_loading" ).show();

				var campaign = {
					security:       egoi_config_ajax_object.ajax_nonce,
					action:         'egoi_deploy_rss',
					campaing_hash:  jQuery( "#campaign_hash_deploy" ).val()
				}

				jQuery.post(
					egoi_config_ajax_object.ajax_url,
					campaign,
					function(response) {
                        console.log(response)
						if (typeof response.error != 'undefined') {
							onOffForm( true );
							clearForm();
							alert( response.error );
							return false;
						}
						jQuery( "#egoi_send_campaign_loading" ).hide();
						var edit = jQuery( "#egoi_edit_campaign" );
						var send = jQuery( "#egoi_send_campaign" );
						edit.hide( 200 );
						send.hide( 200 );
						jQuery( "#success_email" ).show( 300 );
						setTimeout(
							function () {
								jQuery( "#success_email" ).hide( 300 );
								onOffForm( true );
								clearForm();
							},
							2000
						);
					}
				);
			}
		);

		jQuery( "#egoi_send_campaign_webpush" ).on(
			"click",
			function () {
				jQuery( "#egoi_send_campaign_webpush_loading" ).show();

				var campaign = {
					security:       egoi_config_ajax_object.ajax_nonce,
					action:         'egoi_deploy_rss_webpush',
					campaing_hash:  jQuery( "#campaign_hash_deploy_webpush" ).val()
				}

				jQuery.post(
					egoi_config_ajax_object.ajax_url,
					campaign,
					function(response) {
						jQuery( "#egoi_send_campaign_webpush_loading" ).hide();
						var edit = jQuery( "#egoi_edit_campaign_webpush" );
						var send = jQuery( "#egoi_send_campaign_webpush" );
						edit.hide( 200 );
						send.hide( 200 );
						if (typeof response.error != 'undefined') {
							clearWebpushForm();
							alert( response.error );
							return false;
						}
						jQuery( "#success_webpush" ).show( 300 );
						setTimeout(
							function () {
								jQuery( "#success_webpush" ).hide( 300 );
								clearWebpushForm();
							},
							2000
						);
					}
				);
			}
		);

		jQuery( ".nav-tab-addon" ).on(
			"click",
			function () {
				activeConfigTab( this );

				var tab  = jQuery( ".nav-tab-active" ).attr( "id" );
				var wrap = "#" + tab.substring( 4 );

				showConfigWrap( wrap );
			}
		);

		function clearWebpushForm(){
			var feed = jQuery( "#egoi_add_campaign_webpush" ),
			title    = jQuery( "#campaign_title_webpush" ),
			button   = jQuery( "#egoi_create_campaign_webpush" );

			title.val( '' );
			feed.val( 0 );
			feed.trigger( 'change' );
			feed.attr( 'disabled', false );
			title.attr( 'disabled', false );
			button.attr( 'disabled',false );
			button.show();

		}

		function activeConfigTab(tag) {
			jQuery( ".nav-tab-addon" ).each(
				function () {
					jQuery( this ).attr( "class", "nav-tab nav-tab-addon" );
				}
			);
			jQuery( tag ).attr( "class", "nav-tab nav-tab-addon nav-tab-active" );
		}

		function showConfigWrap(wrap) {
			jQuery( ".wrap-addon" ).each(
				function () {
					jQuery( this ).hide();
				}
			);
			jQuery( wrap ).show();
		}

		function clearForm(){
			var feed   = jQuery( "#egoi_add_campaign" ),
			subject    = jQuery( "#campaign_subject" ),
			snippet    = jQuery( "#campaign_snippet" ),
			itemsInput = jQuery( ".number-spinner button" ).closest( '.number-spinner' ).find( 'input' ),
			title      = jQuery( "#campaign_title" );

			feed.trigger( 'change' );
			subject.val( '' );
			snippet.val( '' );
			itemsInput.val( 5 );
			title.val( '' );
		}

		function onOffForm(on = true){

			var feed    = jQuery( "#egoi_add_campaign" ),
			subject     = jQuery( "#campaign_subject" ),
			snippet     = jQuery( "#campaign_snippet" ),
			itemsButton = jQuery( ".number-spinner button" ).closest( '.number-spinner' ).find( 'span' ).find( 'button' ),
			itemsInput  = jQuery( ".number-spinner button" ).closest( '.number-spinner' ).find( 'input' ),
			list        = jQuery( "#egoi_list" ),
			sender      = jQuery( "#egoi_senders" ),
			title       = jQuery( "#campaign_title" ),
			button      = jQuery( "#egoi_create_campaign" ),
			loading     = jQuery( "#egoi_create_campaign_loading" );

			if ( ! on) {
				loading.show();
			} else {
				button.show();
				loading.hide();
			}

			button.attr( 'disabled', ! on );
			feed.attr( 'disabled', ! on );
			subject.attr( 'disabled', ! on );
			snippet.attr( 'disabled', ! on );
			itemsButton.attr( 'disabled', ! on );
			itemsInput.attr( 'disabled', ! on );
			list.attr( 'disabled', ! on );
			sender.attr( 'disabled', ! on );
			title.attr( 'disabled', ! on );

		}

		function valid(obj){
			var flag = true;
			obj.forEach(
				function(element) {
					if (element.val() == '' || element.val() == 0) {
						flag = false;
						toggleError( element );
					}
				}
			);

			return flag;
		};

		function toggleError(elm){
			elm.addClass( 'invalidjQuery' );
			setTimeout(
				function () {
					elm.removeClass( 'invalidjQuery' );
				},
				2000
			);
		};

		function getSenders(){
			var sender_place = jQuery( "#egoi_senders" );

			jQuery( "#egoi_senders option[value!='0']" ).remove();

			jQuery( "#egoi_senders_loading" ).show();
			var obj = {
				security:   egoi_config_ajax_object.ajax_nonce,
				action:     'egoi_get_email_senders',
			};

			jQuery.post(
				egoi_config_ajax_object.ajax_url,
				obj,
				function(response) {
					jQuery( "#egoi_senders_loading" ).hide();

					if (typeof response.error != "undefined") {
						sender_place.append( jQuery( "<option />" ).val( 0 ).text( response.error ) );
						return;
					}

					jQuery.each(
						response.data,
						function () {
							sender_place.append( jQuery( "<option />" ).val( this.sender_id ).text( this.email ) );
						}
					);
				}
			);
		}

		function getLists(){
			var list_place = jQuery( "#egoi_list" );
			jQuery( "#egoi_list option[value!='0']" ).remove();

			jQuery( "#egoi_list_loading" ).show();
			var obj = {
				security:   egoi_config_ajax_object.ajax_nonce,
				action:     'egoi_get_lists',
			};

			jQuery.post(
				egoi_config_ajax_object.ajax_url,
				obj,
				function(response) {					
					response = JSON.parse( response );
					jQuery.each(
						response,
						function (index, value) {
							if (typeof value['list_id'] == 'undefined') {
								return true;
							}
							list_place.append( jQuery( "<option />" ).val( value['list_id']).text( decodeHTML( value['public_name'] ) ) );
						}
					);
					jQuery( "#egoi_list_loading" ).hide();
					if (typeof response.default != 'undefined') {
						list_place.val( response.default );
					}
				}
			);
		}

		var decodeHTML = function (html) {
			var txt       = document.createElement( 'textarea' );
			txt.innerHTML = html;
			return txt.value;
		};
	}
);

jQuery( '.js-example-basic-multiple' ).on(
	'select2:select',
	function (e) {
		var option = e.params.data.element.id;

		if (option.indexOf( 'include' ) >= 0) {
			var option_change = option.replace( 'include', 'exclude' );
		} else {
			var option_change = option.replace( 'exclude', 'include' );
		}
		jQuery( '#' + option_change ).prop( 'disabled', true );

		jQuery( ".js-example-basic-multiple" ).select2( "destroy" );
		jQuery( ".js-example-basic-multiple" ).select2();

	}
);

jQuery( '.js-example-basic-multiple' ).on(
	'select2:unselect',
	function (e) {
		var option = e.params.data.element.id;

		if (option.indexOf( 'include' ) >= 0) {
			var option_change = option.replace( 'include', 'exclude' );
		} else {
			var option_change = option.replace( 'exclude', 'include' );
		}
		jQuery( '#' + option_change ).prop( 'disabled', false );

		setTimeout(
			function () {
				jQuery( ".js-example-basic-multiple" ).select2( "destroy" );
				jQuery( ".js-example-basic-multiple" ).select2();
			}
		);

	}
);

jQuery( ".copy_url" ).click(
	function () {
		var feed = jQuery( this ).attr( 'data-rss-feed' );
		var url  = document.getElementById( feed );
		url.select();
		document.execCommand( "copy" );

		if (feed.indexOf( "url" ) >= 0) {
			var copy_text   = jQuery( "#copy_text" ).text();
			var copied_text = jQuery( "#copied_text" ).text();
			jQuery( ".copy_url" ).each(
				function () {
					jQuery( this ).html( copy_text ).attr( 'style', 'width: 90px;' );
				}
			);
			jQuery( this ).html( copied_text ).css( 'color', '#1BDB49' );
		} else if (feed.indexOf( "input" ) >= 0) {
			jQuery( this ).html( "<i class=\"fas fa-check\"></i>" ).css( 'color', '#1BDB49' );
		}
	}
);
