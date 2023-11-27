(function( $ ) {

	const checkmark = "✅";

	// required field for success next step
	const validStep = {
		subscribers: [
			"#list"
		],
		cs: [
			"#domain"
		],
		products: [
			"#catalog"
		],
		tweaks:[]
	}

	const tabsToSend = [ "subscribers", "cs", "tweaks"]

	$( document ).ready(
		function() {

			var modalClose               = $( ".egoi-modal-header>.close" );
			var nextButton               = $( '#next_step' );
			var steps                    = $( ".nav-link" );
			var loader                   = $( "#egoi-loader" );
			var createCatalogModalButton = $( "#create_catalog" );
			var createCatalogModal       = $( "#createCatalogModal" );
			var createCatalogButton      = $( "#create_catalog_button" )
			var force_catalog_glob       = $( "#force_catalog_glob" )
			var catalog_glob_status      = $( '#catalog_glob_status' );
			var catalog_selected         = $( "#catalog" );
			var list_selected            = $( '#list' )
			var role_select              = $( '#role' )
			var selected_catalog_input   = $( '#selected-import-catalog' );
			var loading_subs_import      = $( '#loading-subs-import' );
			var progressbar_subs_import  = $( '#progressbar-subs-import' );
			var count_users              = 0;
			var subs_progress            = $( "#subs-progress" );

			loader.show();

			modalClose.on(
				'click',
				(e) => {
                $( $( $( $( $( e.target ).parent()[0] ).parent()[0] ).parent()[0] ).parent()[0] ).modal( 'hide' );
				}
			);

			createCatalogModalButton.on(
				"click",
				function (e) {
					e.preventDefault();
					createCatalogModal.modal( "show" )
				}
			);

			catalog_glob_status.on(
				'change',
				(e) => {
                if ($( e.target ).val()) {
                    nextButton.trigger( 'click' )
                }
				}
			)

			createCatalogButton.on(
				"click",
				function (e) {
					e.preventDefault();

					let payload = {
						security: egoi_config_ajax_object_ecommerce.ajax_nonce,
						action:         'egoi_create_catalog',
					}
					let form    = $( '#form-create-catalog' ).serializeArray();

					for (let i = 0; i < form.length; i++) {
						if ( ! form[i].value) {
							return;
						}
						payload[form[i].name] = form[i].value
					}
					loader.show();

					$.post(
						egoi_config_ajax_object_ecommerce.ajax_url,
						payload,
						function(response) {
							loader.hide();
							if (catalog_selected.length) {
								catalog_selected.append( '<option selected value="' + response.data.catalog_id + '">' + response.data.catalog_name + '</option>' )
							}

							selected_catalog_input.val( response.data.catalog_id )
							$( '#display-selected' ).text( response.data.catalog_name )
							createCatalogModal.modal( "hide" )
							force_catalog_glob.attr( 'idgoi', response.data.catalog_id )
							force_catalog_glob.trigger( "click" );
						}
					);

				}
			);

			// next button click
			nextButton.on(
				"click",
				function () {
					let popNext = false;
					steps.each(
						(i,e) => {
							e       = $( e );
							if (popNext) {
								e.prop( "disabled", false );
								e.click();
								return false;
							}
							if (e.hasClass( "active" )) {
								let tab   = e[0].id.replaceAll( "v-pills-", "" ).replaceAll( "-tab", "" );
								let valid = validateForm( tab );
								if (valid) {
									// $("#form-" + tab).serializeArray()

									if (tabsToSend.includes( tab )) {
										// save step state with action: egoi_wizard_step
										saveStepWizard( tab,cleanFormObject( $( "#form-" + tab ).serializeArray() ) );
									}

									if ( ! e.html().includes( checkmark )) {
										e.append( "</span>" + checkmark + "</span>" )
										if (tab == tabsToSend[ tabsToSend.length - 1 ]) {
											// finish in last tab
											setTimeout(
											() => {
												window.location.href = "?page=egoi-4-wp-subscribers"
												},
											1000
											)
										}
									}
									popNext = true;
								}
							}
						}
					)
				}
			);

			function cleanFormObject(obj){
				let data             = {}
				obj.forEach(
					(o) => {
						o.name           = o.name.replaceAll( 'egoi_sync[','' ).replaceAll( ']','' )
						data[o.name] = o.value
					}
				)

				return data;
			}

			function setProgressBarPercent(progresss){
				if (progresss >= 100) {
					progresss = 100;
				}
				progressbar_subs_import.width( progresss + '%' );
			}

			function resetProgressBar(){
				setProgressBarPercent( 0 )
			}

			async function saveStepWizard(step, form){
				let data = {
					security:   egoi_config_ajax_object_core.ajax_nonce,
					action: 'egoi_wizard_step',
					step: step
				}

				Object.entries( form ).forEach(
					([key, value]) => {
						data[key] = value
					}
				)

				return $.post( egoi_config_ajax_object_core.ajax_url, data );
			}

			function startImport(){
				resetProgressBar();
				nextButton.attr( 'disabled', true )
				list_selected.attr( 'disabled', true )
				role_select.attr( 'disabled', true )
				loading_subs_import.css( 'display', 'block' )
				loader.show()
				formatSubProgress( 0 )
				continueImport( 0 )
			}

			function formatSubProgress(done){
				setProgressBarPercent( done / count_users * 100 )
				subs_progress.text( `${done} of ${count_users} subscribers` )
			}

			function continueImport(page){
				let data = {
					security:   egoi_config_ajax_object_core.ajax_nonce,
					action: 'egoi_synchronize_subs',
					page: page
				}
				$.post(
					egoi_config_ajax_object_core.ajax_url,
					data,
					function(response) {
						if ( ! response.success) {
							finishImport()
						}
						if (response.data && response.data.next) {
							formatSubProgress( response.data.done )
							continueImport( response.data.next )
						} else {
							finishImport()
						}
					}
				);
			}

			function finishImport(){
				nextButton.attr( 'disabled', false )
				list_selected.attr( 'disabled', false )
				role_select.attr( 'disabled', false )
				formatSubProgress( count_users )
				loader.hide()
				setTimeout(
					() => {
						nextButton.trigger( 'click' )
						setTimeout( () => {loading_subs_import.css( 'display', 'none' )}, 0 );
					},
					1000
				)
			}

			function validateForm(step){

				let valid       = true;
				validStep[step].forEach(
					(e) => {
						let element = $( e )

						switch (step) {
							case 'cs':
								if ( ! $( "input[name='egoi_sync[track]']" )[0].checked) {
									return;
								}
								break;
							case 'subscribers':
								if (list_selected.val() && loading_subs_import.css( 'display' ) === 'none') {
									saveStepWizard( step, {list: list_selected.val(), role: role_select.val()} ).then(
										(response) => {
											startImport();
										}
									)
									valid = false;
									return;
								}
								break;
							case 'products':
								if (catalog_selected.val() && ! catalog_glob_status.val() || catalog_selected.val() && catalog_selected.val() !== selected_catalog_input.val()) {
									selected_catalog_input.val( catalog_selected.val() )
									force_catalog_glob.attr( 'idgoi', catalog_selected.val() )
									force_catalog_glob.trigger( "click" );
									valid = false;
									return;
								}
								if (catalog_glob_status.val()) {
									return;// trigger
								}
								if( $("#force_catalog_glob").length == 0 ){
									valid = true;
									return;
								}
								break;
						}

						if ( ! element.val() || element.val() == '' || element.val() == 0 || element.val() == 'off') {
							valid = false;
							toggleError( element );
						}
					}
				);

				return valid;
			}

			role_select.on(
				'change',
				(e) => {
					e = $( e.target )
					countUsers( e.val() )
				}
			)

			list_selected.on(
				'change',
				() => {
					countUsers( role_select.val() )
				}
			)

			$.post(
				url_egoi_script.ajaxurl,
				{action: 'egoi_get_lists'},
				function(response) {
					loader.hide();
					var lists = JSON.parse( response );

					$.each(
						lists,
						function(key, val) {
							if (val['list_id']) {
								$( "#list" ).append( `<option value = "${val['list_id']}"> ${val['public_name']} </option>` );
							}
						}
					);

				}
			);

			function toggleError(elm){
				elm.addClass( 'invalidjQuery' );
				setTimeout(
					function () {
						elm.removeClass( 'invalidjQuery' );
					},
					2000
				);
			};

			function countUsers(role){
				loader.show()
				let data = {
					action: 'egoi_count_subs',
					security:   egoi_config_ajax_object_core.ajax_nonce,
					role: role
				}

				$.post(
					egoi_config_ajax_object_core.ajax_url,
					data,
					(response) => {
						loader.hide()
						if (response.data.wp) {
							count_users = response.data.wp
							formatSubProgress( 0 )
						}
					}
				)

			}

			countUsers( '' )

		}
	);

})( jQuery );
