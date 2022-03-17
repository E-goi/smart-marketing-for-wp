(function( $ ) {

	var egoi_select = $( '#egoi_field_map' );
	var app_select  = $( '#app_field_map' );
	var add_button  = $( '#egoi_add_map' );

	var map_trigger = $( '#egoi_map_trigger' );
	var map_block   = $( '#egoi_mapper' );
	var loader      = $( '#egoi_add_map_loader' );

	var connections_block = $( '#egoi-small-mapped-fields' );
	var form              = $( '#egoi_form_mappable' );
	var egoi_mapp_to_save = $( '#egoi_map_to_save' );
	var tag               = $( "#form_tag_wrapper" );

	var scopeAjaxSync;
	var sending = false;

	const anim  = 300;
	const split = {name:'', id:'split', class:'dashicons dashicons-randomize', style:'height: auto;width: auto;padding-left: 0;'};

	form.submit(
		function (e) {
			if (sending) {
				return;}
			if ( typeof egoi_mapp_to_save.val() == 'undefined' ) {
				e.preventDefault(); }
			// map segmentation
			var map = [];
			connections_block.children().each(
				function(i,o){
					if (i % 2 !== 0) { // 0 && 2 (even)
						return;
					}
					$( o ).children().each(
						function(j,k){
							if (typeof map[j] == 'undefined') {
								map[j] = [];
							}
							map[j].push( $( k ).attr( 'egoidata' ) );
						}
					);
				}
			);
			egoi_mapp_to_save.val( JSON.stringify( map ) );
			sending = true;
			form.submit();
		}
	);

	map_trigger.change(
		function(){
			if (map_trigger.val() != 0) {
				  showMapping( map_trigger.val() );
				  $( '#form_tag' ).val( null );
			}
		}
	);

	$( document ).on(
		'click',
		'.close-button-trigger',
		function() {
			var arr = [];
			var a   = $( this ).attr( 'egoidata' );// never touch
			$( connections_block.children()[3] ).children().each(
				function(i,o){
					if ($( o ).attr( 'egoidata' ) == a) {
						connections_block.children().each(
							function(j,k){
								$( k ).children().each(
									function(l,p){
										if (l == i) {
											arr.push( p );
											$( p ).remove();
											return;
										}
									}
								)
							}
						)
					}
				}
			);

			var appElm  = $( arr[0] );
			var egoiElm = $( arr[2] );

			app_select.append( '<option value="' + appElm.attr( 'egoidata' ) + '" >' + appElm.text() + '</option>' );
			egoi_select.append( '<option value="' + egoiElm.attr( 'egoidata' ) + '" >' + egoiElm.text() + '</option>' );
		}
	);

	app_select.change(
		function(){
			validateButton();
		}
	);

	egoi_select.change(
		function(){
			validateButton();
		}
	);

	add_button.on(
		'click',
		function(){
			addToMappedFields();
			var egoi = egoi_select.val();
			var app  = app_select.val();
			removeIdFromSelect( egoi,egoi_select );
			removeIdFromSelect( app,app_select );

			validateButton();
		}
	);

	function validateButton(){
		if (app_select.val() == 0 || egoi_select.val() == 0) {
			add_button.prop( 'disabled', true );
		} else {
			add_button.prop( 'disabled', false );
		}
	}

	function showMapping(id){
		map_block.show( anim );
		tag.show( anim );
		loader.show();
		cleanMappedFields();
		fetchMappableFields( id );
	}

	function fetchMappableFields(id){
		var data = {
			security:   egoi_config_ajax_object.ajax_nonce,
			action:     'egoi_get_mapping_n_fields',
			app:        'gf',
			id:         id
		}

		if (typeof scopeAjaxSync != "undefined") {
			scopeAjaxSync.abort();
		}

		scopeAjaxSync = $.post(
			egoi_config_ajax_object.ajax_url,
			data,
			function(response) {
				loader.hide();
				response = jsonParserLit( response );
				if (typeof response.success != 'undefined' && response.success === false) {
					alert( response.data );
					return;
				}

				populateMappableFields( response.data );
			}
		);
	}

	function jsonParserLit(data){
		if (typeof data == "string") {
			return JSON.parse( data );
		} else {
			return  data;
		}
	}

	function populateMappableFields(data){
		if (data.mapped.length !== 0) {
			populateMappedFields( data );
		}

		app_select.children().each(
			function(i,o){
				var item = $( o );
				if (item.val() != '0') {
					item.remove();
				}
			}
		);
		egoi_select.children().each(
			function(i,o){
				var item = $( o );
				if (item.val() != '0') {
					item.remove();
				}
			}
		);

		for (const [key, value] of Object.entries( data.fields )) {
			if (typeof data.mapped[key] !== 'undefined') {
				continue;
			}
			app_select.append( '<option value="' + key + '">' + value + '</option>' )
		}
		for (const [key, value] of Object.entries( data.egoi_fields )) {
			if (Object.values( data.mapped ).includes( key )) {
				continue;
			}
			egoi_select.append( '<option value="' + key + '">' + value + '</option>' )
		}

		if (typeof data.tag != 'undefined' && data.tag != '') {
			$( '#form_tag' ).attr( 'data-egoi-tag', data.tag );
		} else {
			$( '#form_tag' ).attr( 'data-egoi-tag', '' );
		}
		$( document ).trigger( 'data-attribute-changed' );
	}

	function removeIdFromSelect(id, select){
		select.children().each(
			function(i,o){
				var item = $( o );
				if (item.val() == id) {
					item.remove();
				}
			}
		);
	}

	function populateMappedFields(data){
		for (const[app_key, egoi_key] of Object.entries( data.mapped )) {
			var add = [];
			add.push( {name:data.fields[app_key] , id:app_key} );
			add.push( split );
			add.push( {name:data.egoi_fields[egoi_key] , id:egoi_key} );
			add.push( getCloseButton( egoi_key ) );

			addObjMappedField( add );
		}

	}

	function cleanMappedFields(){
		connections_block.children().each(
			function(i,o){
				$( o ).children().each(
					function(j,k){
						$( k ).remove();
					}
				)
			}
		);
	}

	function addToMappedFields(){
		var add = [];

		app_select.children().each(
			function(i,o){
				var item = $( o );
				if (item.val() == app_select.val()) {
					add.push( {name: item.html(), id:item.val()} );
					return;
				}
			}
		);

		add.push( split );

		egoi_select.children().each(
			function(i,o){
				var item = $( o );
				if (item.val() == egoi_select.val()) {
					add.push( {name: item.html(), id:item.val()} );
					add.push( getCloseButton( item.val() ) );

					return;
				}
			}
		);

		addObjMappedField( add );

	}

	function getCloseButton(id){
		return {name:'', id:id, class:'dashicons dashicons-no-alt close-button-trigger', style:'height: auto;width: auto;padding-left: 0;'}
	}

	function addObjMappedField(add){
		connections_block.children().each(
			function(i,o){
				var item = $( o );
				item.append( '<span style="' + add[i].style + '" class="' + add[i].class + '" egoidata="' + add[i].id + '">' + add[i].name + '</span>' )
			}
		);
	}

	validateButton();

})( jQuery );
