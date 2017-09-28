jQuery(document).ready(function($) {
	'use strict';

	var $context = $(document.getElementById('egoi4wp-admin'));
	$context.find('.color').wpColorPicker();

	$("#egoi_api_key_input").bind('input', function() {

		var btn_submit = $("#egoi_4_wp_login");
		btn_submit.prop('disabled', true);

		var key = $(this).val();
		if(key.length == 40){

			$("#load").addClass('spin').show();
			$("#api-save-text").hide();
			$.ajax({
			    type: 'POST',
			    data:({
			        key: key
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
			        	btn_submit.attr('disabled', false);
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
			$("#egoi_4_wp_login").attr('disabled', 'disabled');
			$("#valid").hide();
		}

	});

	// remove data from WP
	$('#egoi_remove_data').click(function() {

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
	$('.button-primary--custom-add').click(function(){
	  	$('#e-goi-create-list').show();
		$(this).hide();
	});

	$('.cancel-toggle').click(function(){
		$('#e-goi-create-list').hide();
		$('.button-primary--custom-add').show();
	});
});



