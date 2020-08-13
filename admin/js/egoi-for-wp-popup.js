jQuery.fn.rotate = function(degrees) {
    jQuery(this).animate(
        { deg: degrees },
        {
            duration: 500,
            step: function(now) {
                jQuery(this).css({ transform: 'rotate(' + now + 'deg)' });
            }
        }
    );
    return jQuery(this);
};

jQuery(document).ready(function($) {
    $('.js-example-basic-multiple').select2({
        width: '400px',
        maxWidth: '400px',
        marginTop: '12px'
    });
    var editor;
    const TRIGGER_WITH_OPTION = ['delay'];

    //form logic
    $('#trigger').change(function(){
        checkTriggerOption();
    });

    $("#border_radius").on('change mousemove', function(){
        $("#border_range_label").html($(this).val() + "px")
    });

    function checkTriggerOption() {
        if(TRIGGER_WITH_OPTION.includes($('#trigger').val())){
            $('#trigger_option').show();
        }else{
            $('#trigger_option').hide();
        }
    }

    checkTriggerOption();

    if( $('#custom_css').length ) {
        if(wp.codeEditor == 'undefined' || wp.codeEditor == null){return;}
        var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
        editorSettings.codemirror = _.extend(
            {},
            editorSettings.codemirror,
            {
                mode: 'css',
            }
        );
        editor = wp.codeEditor.initialize( $('#custom_css'), editorSettings );
    }

    $("#smsnf-popup-form").submit(function (e) {
        //e.preventDefault();
        $('input[name="page_trigger[]"]').remove();

        var ids = getPageTriggerContent();

        ids.forEach((i) => {
            $("<input />").attr("type", "hidden")
                .attr("name", "page_trigger[]")
                .attr("value", i)
                .appendTo("#smsnf-popup-form");
        })
        return true;
    })

    function getPageTriggerContent(){//collect select items
        let ids = [];
        $("#page_trigger").find($('option')).each(function(index){
            if($(this).is(':disabled')){
                ids.push($(this).val());
            }
        })

        return ids;
    }

    /////image upload

    $('body').on('click', '.popup_remove_side_image', function(e){
        e.preventDefault();
        let selector = $( '.egoi-image-selector-preview' );
        selector.html('<i class="far fa-image" aria-hidden="true"></i><span>Upload Image</span>');
        selector.removeClass('egoi-image-selector-preview--selected');
        selector.css( 'background-image', `` );
        $('#side_image').val('');
        $( '#side_image' ).trigger('change');
    });


    var file_frame;
    var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
    var set_to_post_id = 1; // Set this

    $('.egoi-image-selector-preview').on('click', function( event ){

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            // Set the post ID to what we want
            file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
            // Open frame
            file_frame.open();
            return;
        } else {
            // Set the wp.media post id so the uploader grabs the ID we want when initialised
            wp.media.model.settings.post.id = set_to_post_id;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: jQuery( this ).data( 'uploader_title' ),
            button: {
                text: jQuery( this ).data( 'uploader_button_text' ),
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {

            attachment = file_frame.state().get('selection').first().toJSON();

            // Do something with attachment.id and/or attachment.url here
            let selector = $( '.egoi-image-selector-preview' );

            selector.html('<span class="dashicons dashicons-no popup_remove_side_image"></span>');
            selector.addClass('egoi-image-selector-preview--selected');
            selector.css( 'background-image', `url(${attachment.url})` );

            $( '#side_image' ).val( attachment.id );
            $( '#side_image' ).trigger('change');

            wp.media.model.settings.post.id = wp_media_post_id;
        });

        // Finally, open the modal
        file_frame.open();

        // Restore the main ID when the add media button is pressed
        $('a.add_media').on('click', function() {
            wp.media.model.settings.post.id = wp_media_post_id;
        });

    });

});

jQuery('.js-example-basic-multiple').on('select2:select', function (e) {
    var option = e.params.data.element.id;

    jQuery('#'+option).prop('disabled', true);

    jQuery(".js-example-basic-multiple").select2("destroy");
    jQuery(".js-example-basic-multiple").select2();

});

jQuery('.js-example-basic-multiple').on('select2:unselect', function (e) {
    var option = e.params.data.element.id;
    jQuery('#'+option).prop('disabled', false);


    setTimeout(function () {
        jQuery(".js-example-basic-multiple").select2("destroy");
        jQuery(".js-example-basic-multiple").select2();
    });


});
