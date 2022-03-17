(function( $ ) {
	'use strict';

	$( document ).ready(
		function() {

			$( '.hide-notification-button' ).on(
				'click',
				function () {

					var notification = {
						div: $( this ).closest( '.column' ),
						type: $( this ).data( 'notification' ),
					};

					var data = {
						'action' : 'smsnf_hide_notification',
						'notification': notification.type
					};

					$.post(
						smsnf_notifications_ajax_object.ajax_url,
						data,
						function() {
							notification.div.slideUp( 150 );
						}
					);

				}
			);

		}
	);

})( jQuery );
