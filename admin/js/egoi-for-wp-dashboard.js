(function( $ ) {
    'use strict';

    $( document ).ready(function() {

        var data = {
            'action': 'smsnf_show_account_info'
        };
        $.post(smsnf_dashboard_ajax_object.ajax_url, data, function(response) {
            $('.smsnf-dashboard-account__content__table').append(response);
        });


        var data = {
            'action': 'smsnf_show_blog_posts'
        };
        $.post(smsnf_dashboard_ajax_object.ajax_url, data, function(response) {
            $('.smsnf-dashboard-blog-last-post').append(response);
        });


    });

})( jQuery );