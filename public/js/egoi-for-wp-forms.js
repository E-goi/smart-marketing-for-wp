(function( $ ) {
    'use strict';

    $( document ).ready(function() {

        $('form[id^="easyform_"]').on("submit", function () {

            event.preventDefault();

            var fname = $(this).find('input[name^="fname_"]').val();
            var lname = $(this).find('input[name^="lname_"]').val();
            var email = $(this).find('input[name^="email_"]').val();
            var form_action = $(this).attr('action');
            var form_data = $(this).serialize();

            var data = {
                'action' : 'smsnf_save_advanced_form_subscriber',
                'form_data': form_data,
                'url': form_action,
                'form_id': this.id,
                'fname' : fname,
                'lname' : lname,
                'email' : email
            };
            
            $.post(ajax_object.ajax_url, data, function(response) {
                if(response == 200){
                    switch($(document).find('form[id^="easyform_"]').find('input[name^="lang"]').val()){
                        case 'en':
                            $(document).find('form[id^="easyform_"]').append("<div id=\"easyform_result\" class=\"egoi_simple_form_success_wrapper\" style=\"margin:10px 0px; padding:12px; color: #4F8A10; background-color: #DFF2BF \"> You have been registered successfully! </div>");
                            break;
                        case 'es':
                            $(document).find('form[id^="easyform_"]').append("<div id=\"easyform_result\" class=\"egoi_simple_form_success_wrapper\" style=\"margin:10px 0px; padding:12px; color: #4F8A10; background-color: #DFF2BF \"> ¡Te has registrado con éxito! </div>");
                            break;
                        default:
                            $(document).find('form[id^="easyform_"]').append("<div id=\"easyform_result\" class=\"egoi_simple_form_success_wrapper\" style=\"margin:10px 0px; padding:12px; color: #4F8A10; background-color: #DFF2BF \"> Foi registado com sucesso! </div>");
                            break;
                    }
                }else if(!response){
                    switch($(document).find('form[id^="easyform_"]').find('input[name^="lang"]').val()){
                        case 'en':
                            $(document).find('form[id^="easyform_"]').append("<div id=\"easyform_result\" class=\"egoi_simple_form_success_wrapper\" style=\"margin:10px 0px; padding:12px; color: #9F6000; background-color: #FFD2D2 \"> Error registering!  </div>");
                            break;
                        case 'es':
                            $(document).find('form[id^="easyform_"]').append("<div id=\"easyform_result\" class=\"egoi_simple_form_success_wrapper\" style=\"margin:10px 0px; padding:12px; color: #9F6000; background-color: #FFD2D2 \"> ¡Error al registrarse! </div>");
                            break;
                        default:
                            $(document).find('form[id^="easyform_"]').append("<div id=\"easyform_result\" class=\"egoi_simple_form_success_wrapper\" style=\"margin:10px 0px; padding:12px; color: #9F6000; background-color: #FFD2D2 \"> Erro ao registar! </div>");
                            break;
                    }
                }else{
                    $(document).find('form[id^="easyform_"]').append("<div id=\"easyform_result\" class=\"egoi_simple_form_success_wrapper\" style=\"margin:10px 0px; padding:12px; color: #4F8A10; background-color: #DFF2BF \">"+response+"</div>");
                }

                setTimeout(function() {
                    document.getElementById( 'easyform_result' ).style.display = "none";
                  }, 5000);

                $(document).find(':input').each(function(){
                    $(this).val('');
                });
            });

        });
    });

})( jQuery );