jQuery( document ).ready(
	function($) {

		var nav_tab_settings   = $( "#nav-tab-settings" );
		var nav_tab_appearance = $( "#nav-tab-appearance" );
		var nav_tab_messages   = $( "#nav-tab-messages" );

		var tab_settings   = $( "#tab-settings" );
		var tab_appearance = $( "#tab-appearance" );
		var tab_messages   = $( "#tab-messages" );

		$( ".nav-tab-addon" ).on(
			"click",
			function () {
				activeConfigTab( this );

				var tab  = $( ".nav-tab-active" ).attr( "id" );
				var wrap = "#" + tab.substring( 4 );

				showConfigWrap( wrap );
			}
		);

		nav_tab_settings.on(
			"click",
			function () {
				$( this ).attr( "class", "nav-tab-active" );
				nav_tab_appearance.attr( "class", "" );
				nav_tab_messages.attr( "class", "" );

				tab_settings.show();
				tab_appearance.hide();
				tab_messages.hide();
			}
		);

		nav_tab_appearance.on(
			"click",
			function () {
				$( this ).attr( "class", "nav-tab-active" );
				nav_tab_settings.attr( "class", "" );
				nav_tab_messages.attr( "class", "" );

				tab_appearance.show();
				tab_settings.hide();
				tab_messages.hide();
			}
		);

		nav_tab_messages.on(
			"click",
			function () {
				$( this ).attr( "class", "nav-tab-active" );
				nav_tab_appearance.attr( "class", "" );
				nav_tab_settings.attr( "class", "" );

				tab_messages.show();
				tab_appearance.hide();
				tab_settings.hide();
			}
		);

		function activeConfigTab(tag) {
			$( ".nav-tab-addon" ).each(
				function () {
					$( this ).attr( "class", "nav-tab nav-tab-addon" );
				}
			);
			$( tag ).attr( "class", "nav-tab nav-tab-addon nav-tab-active" );
		}

		function showConfigWrap(wrap) {
			$( ".wrap-addon" ).each(
				function () {
					$( this ).hide();
				}
			);
			$( wrap ).show();
		}

	}
);
