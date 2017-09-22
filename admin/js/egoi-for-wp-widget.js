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

});