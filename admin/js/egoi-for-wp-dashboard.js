(function( $ ) {
    'use strict';

    $( document ).ready(function() {
        var data = {
            'action' : 'smsnf_get_blog_posts'
        };

        $.post(smsnf_dashboard_ajax_object.ajax_url, data, function(response) {
            var posts = jQuery.parseJSON(response);
            $.each(posts, function(key, value) {
                var excerpt = value.excerpt;
                excerpt = excerpt.replace('&hellip;', '...');
                $('.blog_post_'+key+'_link').attr('href', value.link);
                $('#blog_post_'+key+'_date').text(value.date);
                $('#blog_post_'+key+'_category').text(value.category);
                $('#blog_post_'+key+'_title').text(value.title);
                $('#blog_post_'+key+'_excerpt').text(excerpt);
            });
        });
    });

})( jQuery );