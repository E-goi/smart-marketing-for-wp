(function( $ ) {
	'use strict';

	$( document ).ready(
		function() {

			$.post(
				smsnf_dashboard_ajax_object.ajax_url,
				{
					'action': 'smsnf_show_account_info_ajax'
				},
				function(response) {
					var output = jQuery.parseJSON( response.data );
					if (output.notifications.upgrade) {
						$( '#notification_upgrade_account' ).removeClass( 'd-none' );
					}
					if (output.notifications.limit) {
						$( '#notification_account_limit' ).removeClass( 'd-none' );
					}
					$( '#account_content_loading' ).remove();
					$( '.smsnf-dashboard-account__content__table' ).append( output.account );
				}
			);

			$.post(
				smsnf_dashboard_ajax_object.ajax_url,
				{
					'action': 'smsnf_show_blog_posts'
				},
				function(response) {
					$( '#blog_posts_content_loading' ).remove();
					$( '.smsnf-dashboard-blog-last-post' ).append( response );
				}
			);

			$.post(
				smsnf_dashboard_ajax_object.ajax_url,
				{
					'action': 'smsnf_show_last_campaigns_reports'
				},
				function(response) {
					var output = jQuery.parseJSON( response );
					$( '#last_email_campaign_loading' ).remove();
					$( '.smsnf-dashboard-last-email-campaign' ).append( output.email );
					$( '#last_sms_campaign_loading' ).remove();
					$( '.smsnf-dashboard-last-sms-campaign' ).append( output.sms_premium );
				}
			);

		}
	);

})( jQuery );
