jQuery(document).ready(function($) {
	'use strict';

	var $context = $(document.getElementById('egoi4wp-admin'));
	$context.find('.color').wpColorPicker();


	$("#egoi_api_key_input").on('input', function() {

		var btn_submit = $("#egoi_4_wp_login");
		btn_submit.prop('disabled', true);

		var key = $(this).val();
		if(key.length == 40){

			$("#load").addClass('spin').show();
			$("#api-save-text").hide();
			$.ajax({
			    type: 'POST',
			    data:({
			        egoi_key: key
			    }),
			    success:function(data, status) {
			        
			        if(status == '404'){
			        	$("#error").show();
			        	$("#valid").hide();
			        	$("#load").removeClass('spin').hide();
			        	$("#api-save-text").show();
			        }else{
			        	$("#valid").show();
			        	$("#error").hide();
			        	$("#load").removeClass('spin').hide();
			        	$("#api-save-text").show();
			        	btn_submit.prop('disabled', false);
			        }
			    },
			    error:function(status){
			    	if(status){
				    	$("#valid").hide();
				    	$("#error").show();
				    	$("#load").removeClass('spin').hide();
				    	$("#api-save-text").show();
				    }
			    }
			});

		}else{
			$("#egoi_4_wp_login").prop('disabled', true);
			$("#valid").hide();
		}

	});

	$('#egoi_4_wp_login').on('click', function(e){
		e.preventDefault();
		$(this).prop('disabled', true);
		$('form[name="egoi_apikey_form"]').submit();
	});

	$('#save_apikey').on('click', function(e){
		e.preventDefault();
		
		if($('#apikey').val() != $('#old_apikey').val()){
			var confirmation = confirm($('#confirm_text').text());
			if(confirmation){

				var data = {
		            action: 'apikey_changes'
		        };
		        
		        $.post(url_egoi_script.ajaxurl, data, function(response) {
		        	response = JSON.parse(response);
		        	if(response.result == 'ok'){
		            	$('form[name="egoi_apikey_form"]').submit();
		            }
		        });
				
			}else{
				return false;
			}
		}else{
			$('form[name="egoi_apikey_form"]').submit();
		}
	})

	// remove data from WP
	$('#egoi_remove_data').on('click', function() {

		var rmdata = $('input[name="egoi_data[remove]"]:checked').val();
		$('#load_data').show();

		$.ajax({
		    type: 'POST',
		    data:({
		        rmdata: rmdata
		    }),
		    success:function(data, status) {
		        $("#remove_valid").show();
		        $("#load_data").hide();
		        $("#error").hide();
		    },
		    error:function(status){
		    	if(status){
			    	$("#remove_valid").hide();
			    	$("#error").show();
			    }
		    }
		});
	});
  
	// Dropdown toggle
	$('.button-primary--custom-add').on('click', function(){
	  	$('#e-goi-create-list').show();
		$(this).hide();
	});

	$('.cancel-toggle').on('click', function(){
		$('#e-goi-create-list').hide();
		$('.button-primary--custom-add').show();
	});
});



