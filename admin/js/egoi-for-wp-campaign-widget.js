// Email Campaing
jQuery( '#email_campaign_widget_modify_content' ).change(
	function() {
		if (jQuery( this ).is( ":checked" )) {
			  jQuery( '#email_campaign_widget_custom_contents' ).show();
		} else {
			jQuery( '#email_campaign_widget_custom_contents' ).hide();
		}
	}
);
if ( ! jQuery( "#email_campaign_widget" ).is( ":checked" )) {
	jQuery( '#email_campaign_widget_modify_content' ).prop( "disabled",true );
	jQuery( '#email_campaign_widget_modify_content' ).prop( "checked",false ).change();

	jQuery( '#email_campaign_widget_configuration' ).hide();
}

jQuery( "#email_campaign_widget" ).change(
	function() {
		if (jQuery( this ).is( ":checked" )) {
			  jQuery( '#email_campaign_widget_modify_content' ).prop( "disabled",false );
			  jQuery( '#email_campaign_widget_configuration' ).show();
		} else {
			jQuery( '#email_campaign_widget_modify_content' ).prop( "disabled",true );
			jQuery( '#email_campaign_widget_modify_content' ).prop( "checked",false ).change();
			jQuery( '#email_campaign_widget_configuration' ).hide();
		}

	}
)
jQuery( '#email_campaign_widget_modify_content' ).change();

// WebPush Campaign
jQuery( '#webpush_campaign_widget_modify_content' ).change(
	function() {
		if (jQuery( this ).is( ":checked" )) {
			  jQuery( '#webpush_campaign_widget_custom_contents' ).show();
		} else {
			jQuery( '#webpush_campaign_widget_custom_contents' ).hide();
		}
	}
);
if ( ! jQuery( "#webpush_campaign_widget" ).is( ":checked" )) {
	jQuery( '#webpush_campaign_widget_modify_content' ).prop( "disabled",true );
	jQuery( '#ewebpush_campaign_widget_modify_content' ).prop( "checked",false ).change();

	jQuery( '#webpush_campaign_widget_configuration' ).hide();
}

jQuery( "#webpush_campaign_widget" ).change(
	function() {
		if (jQuery( this ).is( ":checked" )) {
			  jQuery( '#webpush_campaign_widget_modify_content' ).prop( "disabled",false );
			  jQuery( '#webpush_campaign_widget_configuration' ).show();
		} else {
			jQuery( '#webpush_campaign_widget_modify_content' ).prop( "disabled",true );
			jQuery( '#webpush_campaign_widget_modify_content' ).prop( "checked",false ).change();
			jQuery( '#webpush_campaign_widget_configuration' ).hide();
		}

	}
)
jQuery( '#webpush_campaign_widget_modify_content' ).change();
