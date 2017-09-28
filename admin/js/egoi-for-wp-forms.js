jQuery(document).ready(function($) {
	
	'use strict';

	new Clipboard('#e-goi_shortcode');

	var session_form = $('#session_form');

	$('#rcv_e-goi_forms').text($('#ct_e-goi_forms').text());

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



	// alert change 
	$('#form_choice').change(function() {
		if(session_form.length){
			$('.cd-popup-trigger-change').trigger('click');
		}else{
			document.getElementById("e-goi-form-options").submit();
		}
	});

	// POPUP 
	//open popup
	$('.cd-popup-trigger-del').on('click', function(event){
		event.preventDefault();
		$('.cd-popup-del-form').addClass('is-visible');
	});

	$('.cd-popup-trigger-change').on('click', function(event){
		event.preventDefault();
		$('.cd-popup-change-form').addClass('is-visible');
	});
	
	//close popup
	$('.cd-popup').on('click', function(event){
		if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup') ) {
			event.preventDefault();
			$(this).removeClass('is-visible');
		}
	});

	$('.cd-popup').on('click', function(event){
		if( $(event.target).is('.cd-popup-close-btn') || $(event.target).is('.cd-popup') ) {
			event.preventDefault();
			$(this).removeClass('is-visible');
		}
	});

	//close popup when clicking the esc keyboard button
	$(document).keyup(function(event){
    	if(event.which=='27'){
    		$('.cd-popup').removeClass('is-visible');
	    }
    });

	// alert popup on change form type
	$('#change_form_req').click(function(){
		document.getElementById("e-goi-form-options").submit();
	});


	// OTHER THINGS

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
