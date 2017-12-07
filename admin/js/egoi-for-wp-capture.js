jQuery(document).ready(function($) {
    
	'use strict';

	window.tabs = function(method, ids){
		
		var option = 'nav-tab ';
		
		switch(method) {
			case 'preview_bar':
				$('#egoi-bar-preview').show();

				$('#tab-settings').hide();
				$('#tab-appearance').hide();
				$('#tab-messages').hide();

				$('#nav-tab-settings').attr('class', 'nav-tab-settings');
				$('#nav-tab-appearance').attr('class', 'nav-tab-appearance');
				$('#nav-tab-messages').attr('class', 'nav-tab-messages');
				
				$('#nav-tab-preview').addClass('nav-tab-active');
				break;

			case 'show_forms':
				$('#tab-forms').show();
				$('#tab-simple-forms').hide();
				$('#tab-main-bar').hide();
				$('#tab-widget').hide();

				$('#nav-tab-simple-forms').attr('class', option + 'nav-tab-simple-forms');
				$('#nav-tab-main-bar').attr('class', option + 'nav-tab-main-bar');
				$('#nav-tab-widget').attr('class', option + 'nav-tab-widget');
				
				$('#nav-tab-forms').addClass('nav-tab-active');
				break;
				
			case 'show_simple_forms':
				$('#tab-simple-forms').show();
				$('#tab-forms').hide();
				$('#tab-main-bar').hide();
				$('#tab-widget').hide();

				$('#nav-tab-forms').attr('class', option + 'nav-tab-forms');
				$('#nav-tab-main-bar').attr('class', option + 'nav-tab-main-bar');
				$('#nav-tab-widget').attr('class', option + 'nav-tab-widget');
				
				$('#nav-tab-simple-forms').addClass('nav-tab-active');
				break;

			case 'show_bar':
				$('#tab-main-bar').show();
				$('#tab-forms').hide();
				$('#tab-simple-forms').hide();
				$('#tab-widget').hide();

				$('#nav-tab-simple-forms').attr('class', option + 'nav-tab-simple-forms');
				$('#nav-tab-forms').attr('class', option + 'nav-tab-forms');
				$('#nav-tab-widget').attr('class', option + 'nav-tab-widget');
				
				$('#nav-tab-main-bar').addClass('nav-tab-active');
				break;

			case 'show_widget':
				$('#tab-widget').show();
				$('#tab-forms').hide();
				$('#tab-simple-forms').hide();
				$('#tab-main-bar').hide();

				$('#nav-tab-simple-forms').attr('class', option + 'nav-tab-simple-forms');
				$('#nav-tab-forms').attr('class', option + 'nav-tab-forms');
				$('#nav-tab-main-bar').attr('class', option + 'nav-tab-main-bar');
				
				$('#nav-tab-widget').addClass('nav-tab-active');
				break;

			case 'show_options':
				$('#tab-forms-options').show();
				$('#tab-forms-appearance').hide();

				$('#nav-tab-forms-appearance').attr('class', 'nav-tab-forms');
				$('#nav-tab-forms-options').addClass('nav-tab-active');
				break;

			case 'show_appearance':
				$('#tab-forms-options').hide();
				$('#tab-forms-appearance').show();

				$('#nav-tab-forms-options').attr('class', 'nav-tab-forms');
				$('#nav-tab-forms-appearance').addClass('nav-tab-active');
				break;

			default:    
				$('#'+ids[3]).show();
				$('#'+ids[3]).addClass('tab-active');
				$('#'+ids[0]).addClass('nav-tab-active');

				$('#egoi-bar-preview').hide();

				$('#'+ids[4]).hide();
				$('#'+ids[4]).attr('class', 'tab');
				$('#'+ids[1]).attr('class', ids[1]);

				$('#'+ids[5]).hide();
				$('#'+ids[5]).attr('class', 'tab');
				$('#'+ids[2]).attr('class', ids[2]);

				$('#nav-tab-preview').attr('class', 'nav-tab-preview');
				break;
		} 
		return false;
	};

});