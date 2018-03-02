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

    $('#nav-tab-widget-egoi-tags').click(function() {
        $('#tab-widget-new-tags').hide();
        $('#tab-widget-egoi-tags').show();
        $('#egoi_tag').val('');
        $(this).addClass('nav-tab-active');
        $('#nav-tab-widget-new-tags').removeClass('nav-tab-active');
    });

    $('#nav-tab-widget-new-tags').click(function() {
        $('#tab-widget-new-tags').show();
        $('#tab-widget-egoi-tags').hide();
        $('#e-goi-tags-widget').val('');
        $(this).addClass('nav-tab-active');
        $('#nav-tab-widget-egoi-tags').removeClass('nav-tab-active');
    });

    'use strict';

    new Clipboard('#e-goi_shortcode');

    var session_form = $('#session_form');

    // initialize class to parse URLs
    var urlObj = new URL(window.location.href);

    // Async fetch
    var page = urlObj.searchParams.get("page");
    if(typeof page != 'undefined'){
        if(page == 'egoi-4-wp-form'){

            //get E-goi lists
            var data_lists = {
                action: 'egoi_get_lists'
            };

            var select_lists_widget = $('#e-goi-list-widget');

            var current_lists = [];

            $(".loading_lists-widget").addClass('spin').show();
            var lists_count_widget = $('#e-goi-lists_ct_widget');

            $.post(url_egoi_script.ajaxurl, data_lists, function(response) {
                $(".loading_lists-widget").removeClass('spin').hide();
                current_lists = JSON.parse(response);

                if(current_lists.ERROR){
                    $('.e-goi-lists_not_found').show();

                    select_lists_widget.hide();

                }else{
                    select_lists_widget.show();

                    $('.e-goi-lists_not_found').hide();

                    $.each(current_lists, function(key, val) {

                        if(typeof val.listnum != 'undefined') {
                            var field_text = jQuery('<option />').html(val.title).text();

                            select_lists_widget.append($('<option />').val(val.listnum).text(field_text));

                            if(lists_count_widget.text() === val.listnum){
                                select_lists_widget.val(val.listnum);

                            }

                        }
                    });

                }
            });

            //get E-goi tags
            getTags();
        }
    }

});

function getTags(){
    var data = {
        action: 'egoi_get_tags'
    }

    var select_tags = jQuery('#e-goi-tags-widget');

    var tags = [];

    jQuery(".loading_tags-widget").addClass('spin').show();
    var lists_count_tags = jQuery('#e-goi-tags_ct_widget');


    jQuery.post(url_egoi_script.ajaxurl, data, function(response) {
        tags = JSON.parse(response);
        jQuery(".loading_tags-widget").removeClass('spin').hide();


        if(tags.ERROR){
            jQuery('.egoi-tags_not_found').show();
            select_tags.hide();

        }else{

            select_tags.show();

            jQuery('.e-goi-tags_not_found').hide();

            jQuery.each(tags['TAG_LIST'], function(key, val) {

                if(typeof val.ID != 'undefined') {
                    var field_text = jQuery('<option />').html(val.NAME).text();

                    select_tags.append(jQuery('<option />').val(val.ID).text(field_text));

                    if(lists_count_tags.text() === val.ID){
                        select_tags.val(val.ID);

                    }
                }
            });
        }
    });
}