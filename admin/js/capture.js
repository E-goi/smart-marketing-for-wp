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

    /* Clipboard */
/*
    let code = $('#smsnf-af-shortcode');
    new Clipboard('#smsnf-af-shortcode');
    code.click(function() {
        $(this).attr('data-tooltip', $(this).attr('msg-after'));
    });

    code.mouseout(function() {
        let div = $(this);
        let timer = setTimeout(function() {
            div.attr('data-tooltip', div.attr('msg-before'));
            clearTimeout(timer);
        }, 300);
    });
*/

    let shortcodes = $('.shortcode.-copy');

    $.each(shortcodes, (k, v) => {
        let el = $(v);
        new Clipboard(v);

        if ( el.is('div') ) {
            el.click(() => {
                el.attr('data-tooltip', el.attr('data-after'));
            });

            el.mouseout(() => {
                let timer = setTimeout(function() {
                    el.attr('data-tooltip', el.attr('data-before'));
                    clearTimeout(timer);
                }, 500);
            });
        }

        if ( el.is('button') ) {
            let tooltip = el.closest('.tooltip');
            el.click(() => {
                tooltip.attr('data-tooltip', tooltip.attr('data-after'));
            });

            el.mouseout(() => {
                let timer = setTimeout(function() {
                    tooltip.attr('data-tooltip', tooltip.attr('data-before'));
                    clearTimeout(timer);
                }, 500);
            });
        }
    });

    /* Modals */
    $('a[data-modal]').click(function(e) {
        let id = $(this).attr('data-modal');
        $(`#${id}`).addClass('active');
    });

    $('.modal a[href="#close"]').click(function() {
        $(this).closest('.modal').removeClass('active');
    });
    /* Notifications */
    let notifications = $('.smsnf-notification');

    notifications.fadeIn();

    setTimeout(() => {notifications.fadeOut();}, 5000);

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
    let select_lists = $('#list_to_subscribe');
    let select_forms = $('#form_list');
    let select_tags = $('#form_tag');
    let select_lang = $('#form_lang');
    let list_id = select_lists.attr('data-egoi-list');
    let form_id = select_forms.attr('data-egoi-form');
    let tag_id = select_tags.attr('data-egoi-tag');
    let lang_id = select_lang.attr('data-egoi-lang');
    
    /* Get lists from e-goi */
    if (select_lists.length) {
        console.log('ajax - get lists');
        $.post(url_egoi_script.ajaxurl, {action: 'egoi_get_lists'}, function(response) {
            let lists = JSON.parse(response);

            $.each(lists, function(key, val) {
                if(typeof val.listnum != 'undefined') {
                    select_lists.append(`<option value="${val.listnum}">${val.title}</option>`);
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

            $.post(url_egoi_script.ajaxurl, {action: 'get_form_from_list', listID: list_id})
            .done(function(response) {
                let forms = JSON.parse(response);
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

    /* Get lang from e-goi */
    if (select_lists.length && select_lang.length) {
        function get_list_lang_from_egoi(list_id) {
            if (list_id == '') {
                return;
            }

            $('#form_lang_wrapper').slideDown();

            console.log('ajax - get lang');
            $.post(url_egoi_script.ajaxurl, {action: 'egoi_get_lists'}, function(response) {
                let langs = JSON.parse(response);
                let idiomas = [];

                $.each(langs, function(key, val) {
                    if (val.listnum != list_id) return;

                    idiomas.push(val.idioma);

                    $.each(val.idiomas_extra, function(key, val) {
                        idiomas.push(val);
                    });
                });

                select_lang.find("option").remove();

                $.each(idiomas, function(key, val) {
                    select_lang.append(`<option value="${val}">${val}</option>`);
                });

                select_lang.prop('disabled', false);
                select_lang.val(lang_id == '' ? idiomas[0] : lang_id);
                
            });
        }

        get_list_lang_from_egoi(list_id);

        select_lists.change(function() {
            select_lang.prop('disabled', true);
            lang_id = '';
            console.log($(this).find('option:checked').val());
            get_list_lang_from_egoi($(this).find('option:checked').val());
        });
    }

    /* Simple Forms */
    let sf_btns = $('button', '#sf-btns');
    let sf_name = $('#sf-btn-name');
    let sf_email = $('#sf-btn-email');
    let sf_phone = $('#sf-btn-phone');
    let sf_submit = $('#sf-btn-submit');
    let sf_html = $('#sf-code');
    let sf_labels = {
        name : `[e_name]\n<p>\n  <label for="egoi_name">${sf_name.attr('data-lable')}: </label>\n  <input type="text" name="egoi_name" id="egoi_name" />\n</p>\n[/e_name]\n`,
        email : `[e_email]\n<p>\n  <label for="egoi_email">${sf_email.attr('data-lable')}: </label>\n  <input type="email" name="egoi_email" id="egoi_email" />\n</p>\n[/e_email]\n`,
        phone : `[e_mobile]\n<p>\n  <label for="egoi_mobile">${sf_phone.attr('data-lable')}: </label>\n  <select name="egoi_country_code" id="egoi_country_code"></select><input type="text" name="egoi_mobile" id="egoi_mobile" />\n</p>\n[/e_mobile]\n`,
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
            add_html(sf_labels.phone);
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
        let html = sf_html.val();
        html += label;
        sf_html.val(html);
    }
    function remove_html(tag) {
        let html = sf_html.val();
        let start = `[e_${tag}]`;
        let end = `[/e_${tag}]`;
        let first_char = html.indexOf(start);
        let last_char = html.indexOf(end) + end.length + 1;

        sf_html.val(html.replace(html.substring(first_char, last_char), ''));
    }

    // validate inputs
    $('#smsnf-simple-forms-form').submit(function(e) {
        let form = $(this);
        let input;

        input = form.find('#list_to_subscribe');
        if (input.find('option:selected').val() == '') {
            input.addClass('error');
        }

        input = form.find('#form_lang');
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

        let txt = input.val();

        if (!(txt.includes('[e_submit]') && txt.includes('[/e_submit]'))) {
            sf_submit.addClass('error');
        }

        if (form.find('.error').length) {
            e.preventDefault();
        }
    });

    /* Advanced Forms */
    let type_form = $('#adv-forms-select-type');
    let radio_btns = $('input[name=type]', type_form);
    let radio_btn_checked = radio_btns.filter(":checked").val();
    let confirm_modal = $('#smsnf-confirm-modal');
    let confirm_btn = $('#confirm-btn', confirm_modal);
    let next_type;

    radio_btns.click(function() {
        next_type = $(this).val();

        let show_modal = (
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
        let form = $(this);
        let input;
        
        input = form.find('#form_name');
        if (input.val().trim() == '') {
            input.addClass('error');
        }

        input = form.find('#form_code');
        if (input.length && input.val().trim() == '') {
            input.addClass('error');
        }

        input = form.find('#list_to_subscribe');
        console.log( input.find('option:selected').val() );
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
        let form = $(this);
        let input;

        input = form.find('#list_to_subscribe');
        if (input.find('option:selected').val() == '') {
            input.addClass('error');
        }

        if (form.find('.error').length) {
            e.preventDefault();
        }
    });

    /* Form preview */
    let border_width = $('#form_border');
    let border_color = $('#form_border_color');
    let width = $('#form_width');
    let height = $('#form_height');
    let preview = $('#form-preview');
    let preview_w = $('#preview .width span');
    let preview_h = $('#preview .height span');

    function update_form() {
        let bw = border_width.val();
        let w = width.val();
        let h = height.val();

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
        let wrapper = $(v);
        let view = wrapper.find('.view');
        let input = wrapper.find('input');
        let select;

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

})})(jQuery);