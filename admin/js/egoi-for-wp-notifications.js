(function( $ ) {
    'use strict';

    $( document ).ready(function() {
        var data = {
            'action' : 'smsnf_hide_notification'
        };

        $.post(ajax_object.ajax_url, data, function(response) {
            alert(response);
        });
    });

})( jQuery );