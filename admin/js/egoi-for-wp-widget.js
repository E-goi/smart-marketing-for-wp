jQuery(document).ready(function($) {

	$('#nav-tab-widget-settings').click(function() {
		$('#tab-widget-settings').show();
		$('#tab-widget-appearance').hide();
		$(this).addClass('nav-tab-active');
		$('#nav-tab-widget-appearance').removeClass('nav-tab-active');
	});

	$('#nav-tab-widget-appearance').click(function() {
		$('#tab-widget-appearance').show();
		$('#tab-widget-settings').hide();
		$(this).addClass('nav-tab-active');
		$('#nav-tab-widget-settings').removeClass('nav-tab-active');
	});

	'use strict';

	new Clipboard('#e-goi_shortcode');

	var session_form = $('#session_form');

	// initialize class to parse URLs
	var urlObj = new URL(window.location.href);
    
    // Async fetch
    var page = urlObj.searchParams.get("page");
	if(typeof page != 'undefined'){
		if(page == 'egoi-4-wp-form'){

			var data_lists = {
		        action: 'egoi_get_lists'
		    };

		    var select_lists_widget = $('#e-goi-list-widget');

		    var current_lists = [];

		    $(".loading_lists").addClass('spin').show();
		    var lists_count_widget = $('#e-goi-lists_ct_widget');
		    
		    $.post(url_egoi_script.ajaxurl, data_lists, function(response) {
			    $(".loading_lists").removeClass('spin').hide();
			    current_lists = JSON.parse(response);
				
				if(current_lists.ERROR){
					$('.e-goi-lists_not_found').show();

					select_lists_widget.hide();

				}else{
					select_lists_widget.show();
					
					$('.e-goi-lists_not_found').hide();

					$.each(current_lists, function(key, val) {
			        	
			        	if(typeof val.listnum != 'undefined') {
				        	select_lists_widget.append($('<option />').val(val.listnum).text(val.title));
				        	
				        	if(lists_count_widget.text() === val.listnum){
				        		select_lists_widget.val(val.listnum);

				        	}

			            }
			        });	
				    	
				}
			});
		}
	}

});