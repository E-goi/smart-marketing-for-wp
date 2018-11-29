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
                console.log(response);
                alert(response);
            });

        });

    });

})( jQuery );