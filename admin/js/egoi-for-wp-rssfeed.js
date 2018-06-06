jQuery(document).ready(function() {
    jQuery(".post_cats_tags").hide();
    jQuery(".product_cats_tags").hide();

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