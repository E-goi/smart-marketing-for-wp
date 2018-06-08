jQuery(document).ready(function() {
    jQuery(".post_cats_tags").hide();
    jQuery(".product_cats_tags").hide();

    var type = jQuery('input[type=radio][name=type]:checked').val();
    if (type == 'posts') {
        jQuery(".post_cats_tags").show();
    } else if (type == 'products') {
        jQuery(".product_cats_tags").show();
    }

    jQuery('input[type=radio][name=type]').change(function() {
        if (this.value == 'posts') {
            jQuery(".post_cats_tags").show();
            jQuery(".product_cats_tags").hide();
        } else if (this.value == 'products') {
            jQuery(".post_cats_tags").hide();
            jQuery(".product_cats_tags").show();
        }
    });
});

jQuery(".term").change(function() {
    var term_name = this.name;
    var term_id = this.value;

    if (term_name.indexOf('include') >= 0) {
        var term_change = term_name.replace('include', 'exclude');
    } else {
        var term_change = term_name.replace('exclude', 'include');
    }

    if(jQuery(this).is(":checked")) {
        jQuery("input[name='"+term_change+"'][value='"+term_id+"']").attr("disabled", true);
    } else {
        jQuery("input[name='"+term_change+"'][value='"+term_id+"']").removeAttr("disabled");
    }
});