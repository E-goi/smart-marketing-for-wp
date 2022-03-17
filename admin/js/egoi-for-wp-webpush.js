jQuery( document ).ready(
	function() {

		jQuery( "#save_webpush" ).click(
			function() {
				jQuery( "#form_webpush_code" ).submit();
			}
		);

		jQuery( "#edit_webpush" ).click(
			function() {
				jQuery( "#egoi_webpush_cod" ).show();
				jQuery( "#webpush_span" ).hide();
				jQuery( "#save_webpush" ).show();
				jQuery( "#edit_webpush" ).hide();
			}
		);

	}
);
