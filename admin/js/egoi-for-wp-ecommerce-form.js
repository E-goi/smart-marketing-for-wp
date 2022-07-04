jQuery( document ).ready(
	function() {
		(function ($) {
			var loader                = $( '#egoi-loader' );
			var ajaxObj               = egoi_config_ajax_object_ecommerce;
			var catalog_name          = $( '#catalog_name' );
			var catalog_language      = $( '#catalog_language' );
			var catalog_currency      = $( '#catalog_currency' );
			var create_catalog_button = $( '#create_catalog_button' );
			var catalog_tax 		  = $( '#catalog_tax' );

			// defaults
			var default_store_country  = $( '#default-store-country' );
			var default_store_currency = $( '#default-store-currency' );
			var default_tax			   = "0";

			var close = $( ".egoi-simple-close-x" );

			create_catalog_button.on(
				'click',
				function (e) {
					if ( ! valid( [catalog_name,catalog_language,catalog_currency] )  || !validTax(catalog_tax)) {
						e.preventDefault();
					} else {
						if ($( '#preventCatalogSubmit' ).length) {
							return;
						}
						$( '#form-create-catalog' ).submit();
					}
				}
			);

			close.on(
				'click',
				function () {
					$( $( $( this ).parent()[0] ).parent()[0] ).hide();
				}
			);

			getDtaAjax();
			function getDtaAjax(){
				loader.show();
				$.get(
					ajaxObj.ajax_url,
					{action: 'egoi_catalog_utilities'},
					function(response) {
						loader.hide();
						response = parseResponse( response );
						if (response === false) {
							return false;
						}
						populateCountry( response.countries );
						populateCurrencies( response.currencies );
						populateTax( response.tax );
					}
				);
			}

			function populateCurrencies(currencies){
				$.each(
					currencies,
					function () {
						if (this == default_store_currency.val()) {
							catalog_currency.append( $( "<option />" ).val( this ).text( this ).attr( "selected","selected" ) );
						} else {
							catalog_currency.append( $( "<option />" ).val( this ).text( this ) );
						}
					}
				);
			}

			function populateCountry(countries){
				$.each(
					countries,
					function () {
						if (this.value == default_store_country.val()) {
							catalog_language.append( $( "<option />" ).val( this.value ).text( this.name ).attr( "selected","selected" ) );
						} else {
							catalog_language.append( $( "<option />" ).val( this.value ).text( this.name ) );
						}
					}
				);
			}

			function populateTax(taxes){
				$.each(
					taxes,
					function () {
						if (this.tax_rate == default_tax) {
							catalog_tax.append( $( "<option />" ).val( this.tax_rate ).text( this.name + ' ('+ this.country + ' | ' + this.tax_rate + '%)' ).attr( "selected","selected" ) );
						} else {
							catalog_tax.append( $( "<option />" ).val( this.tax_rate ).text( this.name + ' ('+ this.country + ' | ' + this.tax_rate + '%)' ) );
						}
					}
				);
			}

			function parseResponse(response){
				response = jsonParserLit( response );
				if (typeof response.success != 'undefined' && response.success === false) {
					displayError( response.data );
					return false;
				}
				return response.data;
			}
			function displayError($message){
				alert( $message );
			}

			function jsonParserLit(data){
				if (typeof data == "string") {
					return JSON.parse( data );
				} else {
					return  data;
				}
			}

			function valid(obj){
				var flag = true;
				obj.forEach(
					function(element) {
						if (element.val() == '' || element.val() == 0 || element.val() == 'off') {
							flag = false;
							toggleError( element );
						}
					}
				);

				return flag;
			};

			function validTax(element){
				var flag = true;
					
					if (element.val() == '' || element.val() == 'off') {
						flag = false;
						toggleError( element );
					}

				return flag;
			}

			function toggleError(elm){
				elm.addClass( 'invalidjQuery' );
				setTimeout(
					function () {
						elm.removeClass( 'invalidjQuery' );
					},
					2000
				);
			};
		})( jQuery );
	}
);
