jQuery( document ).ready(
	function($) {

		'use strict';

		var hidden = document.cookie;
		var body   = document.body;
		var bar    = document.getElementById( 'egoi-bar' );

		bar.style.display = 'none';

		function setCookie(hide_bar, cvalue, exdays) {
			var d = new Date();
			d.setTime( d.getTime() + (exdays * 24 * 60 * 60 * 1000) );
			var expires     = "expires=" + d.toUTCString();
			document.cookie = hide_bar + "=" + cvalue + "; " + expires;
		}

		function getCookie(hide_bar) {
			var name = hide_bar + "=";
			var ca   = document.cookie.split( ';' );
			for (var i = 0; i < ca.length; i++) {
				var c = ca[i];
				while (c.charAt( 0 ) == ' ') {
					c = c.substring( 1 );
				}
				if (c.indexOf( name ) == 0) {
					return c.substring( name.length, c.length );
				}
			}
			return "";
		}

		var subs_bar     = getCookie( "hide_bar" );
		var egoi_session = $( '#e-goi-bar-session' ).text();

		if ((subs_bar == '0') || (egoi_session)) {

			$( bar ).show();

			$( 'body' ).css( {'padding-top': '45px'} );

			$( '#tab_egoi_footer' ).addClass( 'egoi-bottom-close-action' );
			$( '#tab_egoi_footer' ).removeClass( 'egoi-bottom-open-action' );

			$( '#tab_egoi_footer_fixed' ).removeClass( 'egoi-bottom-open-action' );
			$( '#tab_egoi_footer_fixed' ).addClass( 'egoi-bottom-close-action' );

			$( '#tab_egoi' ).addClass( 'egoi-close-action' );
			$( '#tab_egoi' ).removeClass( 'egoi-open-action' );

			if ($( '#tab_egoi_footer_fixed' ).hasClass( 'egoi-bottom-close-action' )) {
				$( 'body' ).css( {'padding-top': '0px'} );
			}

			if ($( '#tab_egoi_footer' ).hasClass( 'egoi-bottom-close-action' )) {
				$( 'body' ).css( {'padding-top': '0px'} );
			}

		} else {
			$( '#tab_egoi' ).addClass( 'egoi-open-action' );
			$( '#tab_egoi' ).removeClass( 'egoi-close-action' );
			$( 'body' ).css( {'padding-top': '0px'} );
		}

		// header
		$( '#smart-marketing-egoi' ).on(
			'click',
			'#tab_egoi',
			function() {
				if ($( '#egoi-bar' ).is( ":visible" )) {

					$( this ).removeClass( 'egoi-close-action' );
					$( this ).addClass( 'egoi-open-action' );

					$( '#egoi-bar' ).fadeOut( 400 ).hide();
					$( 'body' ).animate( {'padding-top': '0px'}, 100 );

					setCookie( "hide_bar", "1", 20 );

				} else {

					$( this ).removeClass( 'egoi-open-action' );
					$( this ).addClass( 'egoi-close-action' );
					$( 'body' ).animate( {'padding-top': '45px'} );

					$( '#egoi-bar' ).slideDown( 400 ).show();
					setCookie( "hide_bar", "0", 20 );
				}

				return true;
			}
		);

		// footer
		$( '#smart-marketing-egoi' ).on(
			'click',
			'#tab_egoi_footer',
			function() {
				if ($( '#egoi-bar' ).is( ":visible" )) {

					$( this ).removeClass( 'egoi-bottom-close-action' );
					$( this ).addClass( 'egoi-bottom-open-action' );

					$( '#egoi-bar' ).fadeOut( 400 ).hide();
					setCookie( "hide_bar", "1", 20 );
				} else {

					$( this ).removeClass( 'egoi-bottom-open-action' );
					$( this ).addClass( 'egoi-bottom-close-action' );

					$( '#egoi-bar' ).slideDown( 400 ).show();
					$( "html, body" ).animate( { scrollTop: $( document ).height() }, 1000 );
					setCookie( "hide_bar", "0", 20 );
				}

				return true;
			}
		);

		// when fixed
		$( '#smart-marketing-egoi' ).on(
			'click',
			'#tab_egoi_footer_fixed',
			function() {
				if ($( '#egoi-bar' ).is( ":visible" )) {
					$( this ).addClass( 'egoi-bottom-open-action' );
					$( this ).removeClass( 'egoi-bottom-close-action' );

					$( '#egoi-bar' ).hide();
					setCookie( "hide_bar", "1", 20 );
				} else {
					$( this ).addClass( 'egoi-bottom-close-action' );
					$( this ).removeClass( 'egoi-bottom-open-action' );

					$( '#egoi-bar' ).show();
					setCookie( "hide_bar", "0", 20 );
				}

				return true;
			}
		);

		// BAR GENERATION
		$( '#smart-marketing-egoi' ).on(
			'click',
			'#tab_egoi_submit_close',
			function(){

				$( '#egoi-bar' ).fadeOut( 400 ).hide();
				if ($( '#tab_egoi_submit_close' ).hasClass( 'top' )) {
					$( 'body' ).animate( {'padding-top': '0px'}, 100 );
				}

				setCookie( "hide_bar", "1", 20 );

				var data = {
					action: 'efwp_generate_subscription_bar',
					regenerate: 1
				};

				$.post(
					url_egoi_script.ajaxurl,
					data,
					function(response) {
						$( '#smart-marketing-egoi' ).html( response );
					}
				);
			}
		);

		// BAR SUBSCRIPTION
		$( '#smart-marketing-egoi' ).on(
			'click',
			'input.egoi_sub_btn',
			function() {

				var btn = $( this );
				var cl  = new CanvasLoader( "process_data_egoi" );
				cl.setColor( '#ababab' );
				cl.setShape( 'spiral' );
				cl.setDiameter( 28 );
				cl.setDensity( 77 );
				cl.setRange( 1 );
				cl.setSpeed( 5 );
				cl.show();

				$( '#process_data_egoi' ).show();
				btn.hide();

				var data = {
					action: 'efwp_process_subscription',
					email: $( 'input.egoi-email' ).val(),
					egoi_action_sub: 1
				};

				$.post(
					url_egoi_script.ajaxurl,
					data,
					function(response) {
						$( '#process_data_egoi' ).hide();
						$( '#smart-marketing-egoi' ).html( response );
					}
				);
			}
		);

		/*$('.egoi4wp-form-fields').on('click', 'input[data-egoi_form_submit="1"]', function() {

			var cl = new CanvasLoader("egoi_form_loader");
			cl.setColor('#ababab');
			cl.setShape('spiral');
			cl.setDiameter(28);
			cl.setDensity(77);
			cl.setRange(1);
			cl.setSpeed(5);
			cl.show();

			$('#egoi_form_loader').show();
			var form = $('.egoi4wp-form-fields').find('form').find('input');
			var url = 'https://88.e-goi.com//w/1ie1neFWze4OIz2ANxbe8f2705a4';
			var data = {};
			var id = '';
			var val = '';
			$.each(form, function(index, element){
				id = element.id;
				val = element.value;
				if(id){
					data[id] = val;
				}
			});
			data['action_url'] = url;
			data['action'] = 'process_egoi_form';

			$.post(url_egoi_script.ajaxurl, data, function(response) {
				$('#egoi_form_loader').hide();
				$('.egoi4wp-form-fields').append('<div class="external_content_egoi">'+response+'</div>');
			 });
		});*/

	}
);
