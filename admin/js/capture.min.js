jQuery(document).ready(function($) {

    $(".nav-tab-addon").on("click", function () {
        activeConfigTab(this);

        var tab = $(".nav-tab-active").attr("id");
        var wrap = "#"+tab.substring(4);

        showConfigWrap(wrap);
    });

    function activeConfigTab(tag) {
        $(".nav-tab-addon").each(function () {
            $(this).attr("class", "nav-tab nav-tab-addon");
        });
        $(tag).attr("class", "nav-tab nav-tab-addon nav-tab-active");
    }

    function showConfigWrap(wrap) {
        $(".wrap-addon").each(function () {
            $(this).hide();
        });
        $(wrap).show();
    }

});