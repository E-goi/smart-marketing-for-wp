(($) => { $(document).ready(function() {
    // don't submit forms on press enter key
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
    /* remove error class on click */
    $(document).on('click', '.error', function() {
        $(this).removeClass('error');
    });

    /* Tabs */
    $('.tab .tab-item a').click(function(e) {
        e.preventDefault();
        $('.tab-item, .tab-content, .smsnf-tab-content').removeClass('active');
        $('#' + $(this).attr('tab-target')).addClass('active');
        $(this).closest('li').addClass('active');
    });

    var shortcodes = $('.shortcode.-copy');

    $.each(shortcodes, (k, v) => {
        var el = $(v);
        new ClipboardJS(v);

        if ( el.is('div') ) {
            el.click(() => {
                el.attr('data-tooltip', el.attr('data-after'));
            });

            el.mouseout(() => {
                var timer = setTimeout(function() {
                    el.attr('data-tooltip', el.attr('data-before'));
                    clearTimeout(timer);
                }, 500);
            });
        }

        if ( el.is('button') ) {
            var tooltip = el.closest('.tooltip');
            el.click(() => {
                tooltip.attr('data-tooltip', tooltip.attr('data-after'));
            });

            el.mouseout(() => {
                var timer = setTimeout(function() {
                    tooltip.attr('data-tooltip', tooltip.attr('data-before'));
                    clearTimeout(timer);
                }, 500);
            });
        }
    });

    /* Modals */
    $('a[data-modal]').click(function(e) {
        var id = $(this).attr('data-modal');
        $(`#${id}`).addClass('active');
    });

    $('.modal a[href="#close"]').click(function() {
        $(this).closest('.modal').removeClass('active');
    });
    /* Notifications */
    var notifications = $('.smsnf-notification');

    if(!notifications.attr('lazy')){
        notifications.fadeIn();
        setTimeout(() => {notifications.fadeOut();}, 5000);
    }

    notifications.find('.close-btn').click(function() {
        notifications.fadeOut();
    });
    /* Help button */
    $("#smsnf-help-btn").click(function() {
        $("#smsnf-help").addClass("-open");
    });

    $("#smsnf-help .close-btn").click(function() {
        $("#smsnf-help").removeClass("-open");
    });

    /* ------------------------------------------------------- */
    var select_lists    = $('#list_to_subscribe');
    var select_forms    = $('#form_list');
    var select_tags     = $('#form_tag');
    var new_tag_submit  = $('#new_tag_submit');

    var list_id     = select_lists.attr('data-egoi-list');
    var form_id     = select_forms.attr('data-egoi-form');
    var tag_id      = select_tags.attr('data-egoi-tag');

    $(document).on('data-attribute-changed', function() {
        if (select_tags.attr('data-egoi-tag') != '') {
            select_tags.val(select_tags.attr('data-egoi-tag'));
        }else{
            select_tags.val(null);
        }
    });
    
    /* Get lists from e-goi */
    if (select_lists.length) {
        $.post(url_egoi_script.ajaxurl, {action: 'egoi_get_lists'}, function(response) {
            var lists = JSON.parse(response);

            $.each(lists, function(key, val) {
                if(typeof val['list_id'] != 'undefined') {
                    select_lists.append(`<option value="${ val['list_id']}">${ val['public_name']}</option>`);
                }
            });

            if (list_id != '') {
                select_lists.val(list_id);
            }
            select_lists.prop('disabled', false);
        });
    }

    /* Get forms from e-goi */
    if (select_lists.length && select_forms.length) {
        function get_forms_from_egoi(list_id, form_id) {
            if (list_id == '') {
                return;
            }

            $('#form_list_group').slideDown();
            $('#empty-forms').slideUp();

            $.post(url_egoi_script.ajaxurl, {action: 'efwp_get_form_from_list', listID: list_id})
            .done(function(response) {
                var forms = JSON.parse(response);
                select_forms.find("option").not(':first').remove();

                if (forms.ERROR == "FORMS_NOT_FOUND") {
                    $('#empty-forms').slideDown();
                } else {
                    $.each(forms, function(key, val) {
                        if(typeof val.id != 'undefined') {
                            select_forms.append(`<option value="${val.title}">${val.title}</option>`);
                        }
                    });
                }

                select_forms.val(form_id);
                select_forms.prop('disabled', false);
            })
            .fail(function(response) {
                console.log('Não foi possivel obter os formulários');
            });
        }

        get_forms_from_egoi(list_id, form_id);

        select_lists.change(() => {
            select_forms.prop('disabled', true);
            get_forms_from_egoi($(this).find('option:checked').val(), '');
        });
    }

    function get_list_tag_from_egoi() {

        $.post(url_egoi_script.ajaxurl, {action: 'egoi_get_tags'}, function(response) {
            var tags = JSON.parse(response);

            $.each(tags, function(key, val) {
                select_tags.append(`<option value="${val.tag_id}">${val.name}</option>`);
            });

            select_tags.prop('disabled', false);
            if (tag_id != '') {
                select_tags.val(tag_id);
            }
            //select_tags.val(tag_id == '' ? tag_id[0] : tag_id);

        });
    }
    get_list_tag_from_egoi();

    /* Simple Forms */
    var sf_btns = $('button', '#sf-btns');
    var sf_name = $('#sf-btn-name');
    var sf_email = $('#sf-btn-email');
    var sf_phone = $('#sf-btn-phone');
    var sf_submit = $('#sf-btn-submit');
    var sf_html = $('#sf-code');
    var sf_labels = {
        name : `[e_name]\n<p>\n  <label for="egoi_name">${sf_name.attr('data-lable')}: </label>\n  <input type="text" name="egoi_name" id="egoi_name" />\n</p>\n[/e_name]\n`,
        email : `[e_email]\n<p>\n  <label for="egoi_email">${sf_email.attr('data-lable')}: </label>\n  <input type="email" name="egoi_email" id="egoi_email" />\n</p>\n[/e_email]\n`,
        phone : `[e_mobile]\n<p>\n  <label for="egoi_mobile">${sf_phone.attr('data-lable')}: </label>\n  <select name="egoi_country_code" id="egoi_country_code" data-selected=""></select><input type="text" name="egoi_mobile" id="egoi_mobile" />\n</p>\n[/e_mobile]\n`,
        submit : `[e_submit]\n<p>\n  <button type="submit" id="egoi_submit_button">${sf_submit.attr('data-lable')}</button>\n</p>\n[/e_submit]\n`,
    };

    sf_btns.click(function() {
        $(this).toggleClass('active');
    });

    sf_name.click(function(e) {
        if($(this).hasClass('active')) {
            add_html(sf_labels.name);
        } else {
            remove_html('name');
        }
    });

    sf_email.click(function(e) {
        if($(this).hasClass('active')) {
            add_html(sf_labels.email);
        } else {
            remove_html('email');
        }
    });

    sf_phone.click(function(e) {
        if($(this).hasClass('active')) {
            add_html(sf_labels.phone.replace('data-selected=""', 'data-selected="'+defaultPrefix+'"'));
        } else {
            remove_html('mobile');
        }
    });

    sf_submit.click(function(e) {
        if($(this).hasClass('active')) {
            add_html(sf_labels.submit);
        } else {
            remove_html('submit');
        }
    });

    function add_html(label) {
        var html = sf_html.val();
        html += label;
        sf_html.val(html);
    }
    function remove_html(tag) {
        var html = sf_html.val();
        var start = `[e_${tag}]`;
        var end = `[/e_${tag}]`;
        var first_char = html.indexOf(start);
        var last_char = html.indexOf(end) + end.length + 1;

        sf_html.val(html.replace(html.substring(first_char, last_char), ''));
    }

    // validate inputs
    $('#smsnf-simple-forms-form').submit(function(e) {
        var form = $(this);
        var input;

        input = form.find('#list_to_subscribe');
        if (input.find('option:selected').val() == '') {
            input.addClass('error');
        }
        
        input = form.find('#form_name');
        if (input.val().trim() == '') {
            input.addClass('error');
        }
        
        input = form.find('#sf-code');
        if (input.val().trim() == '') {
            input.addClass('error');
        }

        var txt = input.val();

        if (!(txt.includes('[e_submit]') && txt.includes('[/e_submit]'))) {
            sf_submit.addClass('error');
        }

        if (form.find('.error').length) {
            e.preventDefault();
        }
    });

    /* Advanced Forms */
    var type_form = $('#adv-forms-select-type');
    var radio_btns = $('input[name=type]', type_form);
    var radio_btn_checked = radio_btns.filter(":checked").val();
    var confirm_modal = $('#smsnf-confirm-modal');
    var confirm_btn = $('#confirm-btn', confirm_modal);
    var next_type;

    radio_btns.click(function() {
        next_type = $(this).val();

        var show_modal = (
            radio_btn_checked == 'iframe' && (next_type == 'popup' || next_type == 'html')
            ||
            next_type == 'iframe' && (radio_btn_checked == 'popup' || radio_btn_checked == 'html')
        );

        if (show_modal) {
            confirm_modal.addClass('active');
            return false;
        } else {
            $(this).closest('form').submit();
        }
    });

    confirm_btn.click(function() {
        $("input[name='type'][value='"+next_type+"']").prop('checked', true);
        $(this).prop('disabled', true);
        type_form.submit();
    });

    // validate inputs
    $('#smsnf-adv-forms-form').submit(function(e) {
        var form = $(this);
        var input;
        
        input = form.find('#form_name');
        if (input.val().trim() == '') {
            input.addClass('error');
        }

        input = form.find('#form_code');
        if (input.length && input.val().trim() == '') {
            input.addClass('error');
        }

        input = form.find('#list_to_subscribe');
        if (input.length && input.find('option:selected').val() == '') {
            input.addClass('error');
        }

        input = form.find('#form_list');
        if (input.length && input.find('option:selected').val() == '') {
            input.addClass('error');
        }

        if (form.find('.error').length) {
            e.preventDefault();
        }
    });

    /* Subscriber Bar and Widget Options*/
    // validate inputs
    $('#smsnf-subscriber-bar, #smsnf-widget-options').submit(function(e) {
        var form = $(this);
        var input;

        input = form.find('#list_to_subscribe');
        if (input.find('option:selected').val() == '') {
            input.addClass('error');
        }

        if (form.find('.error').length) {
            e.preventDefault();
        }
    });

    /* Form preview */
    var border_width = $('#form_border');
    var border_color = $('#form_border_color');
    var width = $('#form_width');
    var height = $('#form_height');
    var preview = $('#form-preview');
    var preview_w = $('#preview .width span');
    var preview_h = $('#preview .height span');

    function update_form() {
        var bw = border_width.val();
        var w = width.val();
        var h = height.val();

        preview.css({
            'border-width': bw == '' ? 0 : bw,
            'border-color': border_color.val(),
            'width': w < 400 ? w : 400 + 'px',
            'height': h < 325 ? h : 325 + 'px',
        });

        preview_w.text(w + 'px');
        preview_h.text(h + 'px');
    }
    update_form();

    border_width.on('keyup change', update_form);
    border_color.on('keyup change', update_form);
    width.on('keyup', update_form);
    height.on('keyup change', update_form);
    $('.smsnf-input-group input.color').wpColorPicker({ change: update_form });

    $('.colorpicker-wrapper').each(function(k, v) {
        var wrapper = $(v);
        var view = wrapper.find('.view');
        var input = wrapper.find('input');
        var select;

        if ($('#smsnf-adv-forms-custom').length) {
            select = (hsb, hex, rgb) => {
                $(view).css('background-color', '#' + hex);
                input.val('#' + hex);
                update_form();
            };
        } else {
            select = (hsb, hex, rgb) => {
                $(view).css('background-color', '#' + hex);
                input.val('#' + hex);
            };
        }
        

        wrapper.ColorPicker({
            color: input.val(),
            onChange: select,
            onSubmit: select,
        });
    });

    new_tag_submit.on('click', function(event){
        event.preventDefault();
        var input = $('#new_tag_name');
        var load = $('#loading_add_tag');

        if(!validateFields([input])){
            return;
        }

        load.show();
        $(this).prop('disabled', true);

        var data = {
            action: 'egoi_add_tag',
            name:   input.val()
        };
        $.post(url_egoi_script.ajaxurl, data, function(response) {
            var tag = JSON.parse(response);
            select_tags.append(`<option value="${tag['tag_id']}">${tag['name']}</option>`);
            $(this).prop('disabled', false);
            select_tags.val(tag['tag_id']);
            $('#create-new-tag').hide();
        });


    });

    function validateFields(arr){
        var valid = true;
        arr.forEach(function (o,i) {
            if(o.val() == ''){
                valid = false;
                o.addClass('error');
                setTimeout(function () {
                    o.removeClass('error')
                }, 1000);
            }
        });
        return valid;
    }



})})(jQuery);