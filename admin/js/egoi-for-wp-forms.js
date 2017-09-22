jQuery(document).ready(function($) {
	
	'use strict';

	var session_form = $('#session_form');

	//$('#tab-forms').show();

	$('#egoi4wp-form-hide').hide();
	$('#wp-form_content-editor-tools').append('<b>Editor</b>');
	var $context = $(document.getElementById('egoi4wp-form'));
	$context.find('.color').wpColorPicker();

	var $content = $(document.getElementById('tab-content'));
	var $appearance = $(document.getElementById('tab-appearance'));

	$appearance.hide();

	$('#nav-tab-content').click(function() {
		$content.show();
		$appearance.hide();
		$('#nav-tab-content').addClass('nav-tab-active');
		$('#nav-tab-appearance').removeClass('nav-tab-active');
	});

	$('#nav-tab-appearance').click(function() {
		$appearance.show();
		$content.hide();
		$('#nav-tab-appearance').addClass('nav-tab-active');
		$('#nav-tab-content').removeClass('nav-tab-active');
	});

	$('#close_egoi').click(function() {
		$('#TB_closeWindowButton').trigger("click");
	});

	$('#form_choice').change(function() {

		if(session_form.length){
			var confirm_var = confirm('Se mudar de formulário vai perder todos os dados.');

			if(confirm_var){
				$('#egoi4wp-form-hide').show();
				document.getElementById("e-goi-form-options").submit();
			}
			
			/*var e = document.getElementById('form_choice');
			var option = e.options[e.selectedIndex].value;


			if(option){
				$('#egoi4wp-form-hide').show();

				if(option == 'popup'){
					$('#help_popup').show();
					$('#help_html').hide();
					$('#help_iframe').hide();
				}else if(option == 'html'){
					$('#help_popup').hide();
					$('#help_iframe').hide();
					$('#help_html').show();
				}else if(option == 'iframe'){
					$('#help_popup').hide();
					$('#help_html').hide();
					$('#help_iframe').show();
				}
			}
			else{
				$('#egoi4wp-form-hide').hide();
			}*/
		}else{
			document.getElementById("e-goi-form-options").submit();
		}
	});


	$('#get_type_form').click(function() {
		$('#form_type').trigger("click");
	});

	// on change list
    $('#egoi4wp-lists').change(function() {
        var listID = $(this).val();
        var container = [];

        var block = $('#formid_egoi');

        var data = {
            action: 'get_form_from_list',
            listID: listID
        };
        
        $.post(url_egoi_script.ajaxurl, data, function(response) {
            
            block.show();
            $.each(JSON.parse(response), function(key, val) {
            	if(typeof val.id != 'undefined'){
                	block.append('<option value="' + val.id + ' - ' + val.url + '">' + val.title + '</option>');
          		}
          	});

          	$('#formid_egoi').trigger('change');
         });

    });


	// ---------FORM E-GOI ---
	$('#formid_egoi').change(function() {
		var e = document.getElementById('formid_egoi');
		var strUser = e.options[e.selectedIndex].value;
		var res = strUser.split(" - ");

		console.log(window.location.host);

		if(strUser != ''){
			$.ajax({
			    url: '//'+window.location.host+'/wp-content/plugins/smart-marketing-for-wp/admin/partials/custom/egoi-for-wp-form_egoi.php',
			    type: 'POST',
			    data:({
			        id: res[0],
			        url: res[1]
			    }),
			    success:function(data, status) {
			        $('#egoi_form_inter').html(data);
			        $('#form_egoint').trigger('click');

			        var TBwindow = $('#TB_window');
					$('#TB_ajaxContent').css('width', '700px');
			  		TBwindow.css('width', '730px');
			    },
			    error:function(status){
				    $("#valid").hide();
				    $("#error").show();
			    }
			});
		}

	});


    var url_string = window.location.href;
    var url = new URL(url_string);
    var c = url.searchParams.get("type");
    if (c != 'form') {
        $('#egoi4wp-form-hide').show();
    }else{
        $('#egoi4wp-form-hide').hide();
    }

});
