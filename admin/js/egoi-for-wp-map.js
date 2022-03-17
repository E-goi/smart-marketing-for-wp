jQuery( document ).ready(
	function($) {

		$( '#wp_fields' ).change(
			function() {
				if (($( this ).val() != '') && ($( '#egoi' ).val() != '')) {
					$( '#save_map_fields' ).prop( 'disabled', false );
				} else {
					$( '#save_map_fields' ).prop( 'disabled', true );
				}
			}
		);

		$( '#egoi' ).change(
			function() {
				if (($( this ).val() != '') && ($( '#wp_fields' ).val() != '')) {
					$( '#save_map_fields' ).prop( 'disabled', false );
				} else {
					$( '#save_map_fields' ).prop( 'disabled', true );
				}
			}
		);

		$( '#save_map_fields' ).click(
			function() {

				var $wp        = $( '#wp_fields' );
				var $wp_name   = $( '#wp_fields option:selected' );
				var $egoi      = $( '#egoi' );
				var $egoi_name = $( '#egoi option:selected' );

				if (($wp.val() != '') && ($egoi.val() != '')) {

					$( '#load_map' ).show();

					$.ajax(
						{
							type: 'POST',
							data:({
								security: egoi_config_ajax_object_core.ajax_nonce,
								action: 'egoi_map_fields_egoi',
								wp: $wp.val(),
								wp_name: $wp_name.text(),
								egoi: $egoi.val(),
								egoi_name: $egoi_name.text(),
								token_egoi_api: 1
							}),
							url: egoi_config_ajax_object_core.ajax_url,
							success:function(data, status) {
								if (data == 'ERROR') {
									$( '#error_map' ).show();
									$( '#success_map' ).hide();
									$( '#delete_map' ).hide();
								} else {
									$( data ).appendTo( '#all_fields_mapped' );
									$( '#error_map' ).hide();
									$( '#success_map' ).show();
									$( '#delete_map' ).hide();

								}

								$wp.val( '' );
								$egoi.val( '' );
								$( '#save_map_fields' ).prop( 'disabled', true );

								$( '#load_map' ).hide();
								$( '#delete_map' ).hide();
							},
							error:function(status){
								if (status) {
									$( "#error_map" ).show();
									$( '#load_map' ).hide();
								}
								$( '#success_map' ).hide();
								$( '#delete_map' ).hide();
							}
						}
					);
				}

			}
		);

		$( '.egoi_fields' ).on(
			"click",
			function(){

				var id = $( this ).data( 'target' );
				var tr = 'egoi_fields_' + id;
				$( '#load_map' ).show();

				$.ajax(
					{
						type: 'POST',
						data:({
							security: egoi_config_ajax_object_core.ajax_nonce,
							action: 'egoi_map_fields_egoi',
							id_egoi: id
						}),
						url: egoi_config_ajax_object_core.ajax_url,
						success:function(data, status) {
							$( '#' + tr ).remove();
							$( '#load_map' ).hide();
							$( '#delete_map' ).show();
							$( '#success_map' ).hide();
						},
						error:function(status){
							if (status) {
								$( "#error_map" ).show();
								$( '#load_map' ).hide();
							}
							$( '#delete_map' ).hide();
							$( '#success_map' ).hide();
						}
					}
				);

			}
		);

		$( '#map-rebump' ).on(
			'click',
			function(e){
				$( '#makeMapping' ).modal( 'show' );
			}
		)

	}
);
