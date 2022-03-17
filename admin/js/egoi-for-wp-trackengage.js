(function( $ ) {

    const FIELDS = ['domain', 'track'];
    var loader = $("#egoi-loader");
    var domain = $("#domain");
    var track = $("#track");
    var domain_group = $("#domain_group")
    var notification = $(".smsnf-notification")

    track.on('change', (e) => {
        if(!e.target.checked){
            domain_group.hide()
        }else{
            domain_group.show()
        }
    })

    $("form").on('submit', (e) => {
        e.preventDefault();
        loader.show()
        let form = $(e.target).serializeArray();
        let data = {
            security:   egoi_config_ajax_object_core.ajax_nonce,
            action:     'egoi_wizard_step',
            step:       'cs'
        }

        form.forEach( (el) => {
            if( !FIELDS.includes(el.name) ) {
                return true;
            }
            data[el.name] = el.value
        })

        if(!data['track']){
            data['track'] = 0
            data['domain'] = ''
        }

        jQuery.post(egoi_config_ajax_object_core.ajax_url, data, function(response) {
            loader.hide()
            if(!response.success){
                //show error here
                setMessage(response.data, 'Error', 'error')
                return;
            }

            setMessage(response.data)

            if(!data['track']){
                domain.attr('disabled', false)
            }else{
                domain.attr('disabled', true)
            }

        });

    })

    function setMessage(content, title = '', type='success'){
        //notification.children()[1]//h2
        $(notification.children()[2]).text(content)//p
        notification.fadeIn()
        setTimeout( () => { location.reload() }, 1000)
        setTimeout( () => {notification.fadeOut() }, 5000)
    }


})( jQuery );