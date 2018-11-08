(function( $ ) {
    'use strict';

    // Here's how you'd do this with jQuery


    $( document ).ready(function() {

        /*
        function sayHi() {
            var iframe = $('#egoi-window-widget-content-87a772d62e0383837711da05172057be iframe');
            console.log(iframe[0]);
            var elmnt = iframe[0].contentWindow.document.getElementById("fname_200");
            console.log(elmnt);

            elmnt.click(function () {
                console.log('test');
            });

        }
        setTimeout(sayHi, 5000);
        */

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
                alert(response);
            });

        });

    });


})( jQuery );