<?php

require_once plugin_dir_path( __FILE__ ) . '../../../includes/class-egoi-for-wp-popup.php';

?>
<div style="display: flex;justify-content: space-between;align-items: center;flex-direction: row;margin-bottom: 12px;">
	<div>
		<h3><?php _e( 'Preview', 'egoi-fo-wp' ); ?></h3>
		<p class="subtitle-preview"><?php _e( 'The popup\'s final look might change', 'egoi-fo-wp' ); ?></p>
	</div>
	<div>
		<?php echo getLoader( 'egoi-preview-loader', true, true, 32 ); ?>
	</div>
</div>
<iframe id="popup_preview" style="height: calc(100% - 40px);width: 100%;" ></iframe>
<script>
	jQuery(document).ready(function($) {

		var orig = $.fn.css;
		$.fn.css = function() {
			var result = orig.apply(this, arguments);
			$(this).trigger('stylechanged');
			return result;
		}

		const EGOI_POPUP_CONTENT = 'content';
		const EGOI_POPUP_PAGES_TRIGGER = 'page_trigger';

		var scopeAjaxSync;
		var ajaxObj = egoi_config_ajax_object_capture;
		var iframe = $('#popup_preview');
		var loader = $('#egoi-preview-loader');
		var firstLoad = true;
		var colorpicker_timeout;
		var input_timeout;

		$("#smsnf-popup-form input").change(function() {
			updateView();
		});


		$("#smsnf-popup-form select").change(function() {
			clearTimeout(input_timeout);
			input_timeout = setTimeout(function () {
				updateView();
			},500);
		});

		$("#smsnf-popup-form label").on('click', function() {
			updateView();
		});

		$(".colorpicker-wrapper div").on('stylechanged', function () {
			clearTimeout(colorpicker_timeout);
			colorpicker_timeout = setTimeout(function () {
				updateView();
			},500);
		});

		$("#smsnf-popup-form input").on('keyup', function () {
			clearTimeout(input_timeout);
			input_timeout = setTimeout(function () {
				updateView();
			},500);
		});


		function scheduleViewUpdate(){
			if(typeof scopeAjaxSync != "undefined")
				return;
			updateView();
		}

		function initView(){
			if(tinymce.get(EGOI_POPUP_CONTENT) == 'undefined' || tinymce.get(EGOI_POPUP_CONTENT) == null){
				setTimeout(function () {
					initView();
				},1000);
				return;
			}

			tinymce.get(EGOI_POPUP_CONTENT).on('change', function(e) {
				setTimeout(function () {
					updateView();
				},500);
			});

			setInterval(scheduleViewUpdate(),1000);
			updateView();
		}

		function getFormData(){
			let form_data = $("#smsnf-popup-form").serializeArray();
			form_data.push({
				name: EGOI_POPUP_CONTENT,
				value: tinymce.get(EGOI_POPUP_CONTENT).getContent()
			});
			//form_data.push({
			//    name: EGOI_POPUP_CSS,
			//    value: tinymce.get(EGOI_POPUP_CSS).getContent()
			//});
			form_data.push({
				name: EGOI_POPUP_PAGES_TRIGGER,
				value: getPageTriggerContent()
			});
			return form_data;
		}

		function updateView(){

			if(tinymce.get(EGOI_POPUP_CONTENT) == 'undefined'){
				setTimeout(function () {
					updateView();
				},1000);
				return;
			}

			let form_data = getFormData();

			var data = {
				security:       ajaxObj.ajax_nonce,
				action:         'egoi_preview_popup',
				data:           form_data
			};

			if(firstLoad){ data.first_time = true; }

			if(typeof scopeAjaxSync != "undefined")
				scopeAjaxSync.abort();
			loader.show();
			scopeAjaxSync = $.post(ajaxObj.ajax_url, data, function(response) {
				firstLoad = false;
				loader.hide();
				if(response === false)
					return false;

				iframe.attr('src', 'data:text/html;charset=utf-8,' +
					encodeURIComponent( // Escape for URL formatting
						response
					).replace(/[!'()*]/g, escape).replace(/\\"/g, escape));
				return true;
			});
		}

		function getPageTriggerContent(){//collect select items
			let ids = [];
			$("#page_trigger").find($('option')).each(function(index){
				if($(this).is(':disabled')){
					ids.push($(this).val());
				}
			})

			return ids;
		}

		function parseResponse(response){
			response = jsonParserLit(response);
			if(typeof response.success != 'undefined' && response.success===false){
				return false;
			}
			return response.data;
		}

		function jsonParserLit(data){
			if(typeof data == "string")
				return JSON.parse(data);
			else
				return  data;
		}

		initView();
	});
</script>
