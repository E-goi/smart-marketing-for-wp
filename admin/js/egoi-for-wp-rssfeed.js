jQuery(document).ready(function() {

    jQuery('.js-example-basic-multiple').select2();

    jQuery(".cats_tags_titles").hide();
    jQuery(".post_cats_tags").hide();
    jQuery(".product_cats_tags").hide();

    var type = jQuery('input[type=radio][name=type]:checked').val();
    if (type == 'posts') {
        jQuery(".post_cats_tags").show();
        jQuery(".cats_tags_titles").show();
    } else if (type == 'products') {
        jQuery(".product_cats_tags").show();
        jQuery(".cats_tags_titles").show();
    }

    jQuery('input[type=radio][name=type]').change(function() {
        jQuery(".cats_tags_titles").show();
        if (this.value == 'posts') {
            jQuery(".post_cats_tags").show();
            jQuery(".product_cats_tags").hide();
        } else if (this.value == 'products') {
            jQuery(".post_cats_tags").hide();
            jQuery(".product_cats_tags").show();
        }
    });
});

jQuery('.js-example-basic-multiple').on('select2:select', function (e) {
    var option = e.params.data.element.id;

    if (option.indexOf('include') >= 0) {
        var option_change = option.replace('include', 'exclude');
    } else {
        var option_change = option.replace('exclude', 'include');
    }
    jQuery('#'+option_change).prop('disabled', true);

    jQuery(".js-example-basic-multiple").select2("destroy");
    jQuery(".js-example-basic-multiple").select2();

});

jQuery('.js-example-basic-multiple').on('select2:unselect', function (e) {
    var option = e.params.data.element.id;

    if (option.indexOf('include') >= 0) {
        var option_change = option.replace('include', 'exclude');
    } else {
        var option_change = option.replace('exclude', 'include');
    }
    jQuery('#'+option_change).prop('disabled', false);


    setTimeout(function () {
        jQuery(".js-example-basic-multiple").select2("destroy");
        jQuery(".js-example-basic-multiple").select2();
    });


});

jQuery(".copy_url").click(function () {
    var feed = jQuery(this).attr('data-rss-feed');
    var url = document.getElementById(feed);
    url.select();
    document.execCommand("copy");

    if (feed.indexOf("url") >= 0) {
        var copy_text = jQuery("#copy_text").text();
        var copied_text = jQuery("#copied_text").text();
        jQuery(".copy_url").each(function () {
            jQuery(this).html(copy_text).attr('style', 'width: 90px;');
        });
        jQuery(this).html(copied_text).css('color', '#1BDB49');
    } else if (feed.indexOf("input") >= 0) {
        jQuery(this).html("<i class=\"fas fa-check\"></i>").css('color', '#1BDB49');
    }
});