var url_variables = url_variables || {};
var features = features || {};
var file_frame;

(function($){

    if(url_variables.action == 'add' || (url_variables.action == 'edit' && $('.squirrels-feature-custom-option').length == 0))
    {
        showCustomeFeatureOptionsTable();
    }

    $('#squirrels-feature-type').change(showCustomeFeatureOptionsTable);

    $('body')
        .on('click', '.squirrels-feature-custom-option-add', function(){

            //add new input
            addCustomFeatureOption();
        })
        .on('click', '.squirrels-feature-custom-option-remove', function(){

            $(this).closest('.squirrels-feature-custom-option').remove();
        })
        .on('keypress', '.squirrels-feature-custom-option-input', function(e){

            if(e.which == 13)
            {
                $('.squirrels-feature-custom-option-add').trigger('click');

                $('.squirrels-feature-custom-option').last().find('input[type=text]').focus();
            }
        });

    $('#squirrels-feature-add, #squirrels-feature-edit').click(function(){

        var title = $('#squirrels-feature-title').val();
        var option = $('#squirrels-feature-type').val();
        var customOptions = [];
        var id = (typeof url_variables.id != 'undefined') ? url_variables.id : 0;

        if(title.length == 0)
        {
            alert('You must enter a title for this feature.');
        }
        else
        {
            if(option == 1)
            {
                customOptions = compileOptions();
            }

            if(customOptions.length == 0 && option == 1)
            {
                alert('You must enter a custom option or select Yes/No as your option.');
            }
            else
            {
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'squirrels_feature_save',
                        id: id,
                        title: title,
                        option: option,
                        custom_options: customOptions
                    },
                    success: function(r)
                    {
                        if(r.success > 0)
                        {
                            location.href = '?page=squirrels_features';
                        }
                        else
                        {
                            alert('There\'s been an error.');
                        }
                    },
                    error: function()
                    {
                        alert('There\'s been an error.');
                    }
                });
            }
        }
    });

    $('#squirrels-feature-delete').click(function(){

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'squirrels_feature_delete',
                id: url_variables.id
            },
            success: function(r)
            {
                if(r.success > 0)
                {
                    location.href = '?page=squirrels_features';
                }
                else
                {
                    alert('There\'s been an error.');
                }
            },
            error: function()
            {
                alert('There\'s been an error.');
            }
        });

    });

    function compileOptions()
    {
        var customOptions = [];

        $('.squirrels-feature-custom-option').each(function(){
            var value = $(this).find('input[type="text"]').val();
            var isDefault = $(this).find('input[type="radio"]').is(':checked');

            if(value.length > 0)
            {
                customOptions.push({
                    value: value,
                    'is_default': isDefault
                });
            }
        });

        return customOptions;
    }

    function showCustomeFeatureOptionsTable()
    {
        if($('#squirrels-feature-type').val() == 1)
        {
            $('#squirrels-feature-custom-options-table').show();
        }
        else
        {
            $('#squirrels-feature-custom-options-table').hide();
        }
    }

    function addCustomFeatureOption()
    {
        $('#squirrels-feature-custom-options-wrapper').append(
            '<tr class="squirrels-feature-custom-option">' +
                '<td>' +
                    '<input type="radio" name="squirrels-feature-custom-option-default">' +
                '</td>' +
                '<td>' +
                    '<input type="text" class="squirrels-feature-custom-option-input" />' +
                '</td>' +
                '<td>' +
                    '<input class="submitdelete squirrels-feature-custom-option-remove" value="Remove" type="button" />' +
                '</td>' +
            '</tr>');
    }


/**********************************************************************************************************************/
//Add, Edit, Delete Auto Page

    $( '#pre-defined-feature-title' ).change( function() {

        var id = $(this).val();
        var value_select = $('#pre-defined-feature-value');
        value_select.empty();

        for (var f=0; f<feature_options.length; f++) {
            if (feature_options[f].id == id) {
                for (var o=0; o<feature_options[f].options.length; o++) {
                    value_select.append('<option value="'+feature_options[f].options[o]+'">'+feature_options[f].options[o]+'</option>')
                }
                break;
            }
        }

    } ).trigger('change');

    $('#submit-pre-defined-feature').click(function(){
        var feature_id = $('#pre-defined-feature-title').val();
        for (var f=0; f<feature_options.length; f++) {
            if (feature_options[f].id == feature_id) {
                var title = feature_options[f].title;
                break;
            }
        }
        var value = $('#pre-defined-feature-value').val();
        addFeature(feature_id, title, value);
    });

    $('#submit-new-feature').click(function(){
        var title_field = $('#new-feature-title');
        var value_field = $('#new-feature-value');
        var title = title_field.val();
        var value = value_field.val();
        if (title.length == 0) {
            alert('Please enter a title');
        } else if (value.length == 0) {
            alert('Please enter a value');
        } else {
            addFeature(0, title, value);
            title_field.val('');
            value_field.val('');
        }
    });

    $('#squirrels-feature-table').on('click', '.remove-feature', function(e){

        e.preventDefault();
        var index = $(this).data('index');
        for (var f=0; f<features.length; f++) {
            if (index == f.index) {
                features[f].remove = 1;
                break;
            }
        }
        $('#feature-'+index).remove();
    });

    $( '#squirrels-inventory-add, #squirrels-inventory-edit' ).click( function() {

        var id = (typeof url_variables.id != 'undefined') ? url_variables.id : 0;

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'squirrels_inventory_add',
                id: id,
                price: $('#squirrels_price').val(),
                type_id: $('#squirrels_auto_type').val(),
                model_id: $('#squirrels_vehicle').val(),
                new_make: $('#squirrels_new_make').val(),
                new_model: $('#squirrels_new_model').val(),
                inventory_number: $('#squirrels_inventory_number').val(),
                vin: $('#squirrels_vin').val(),
                year: $('#squirrels_year').val(),
                odometer_reading: $('#squirrels_odometer_reading').val(),
                is_visible: $('#squirrels_is_visible').val(),
                is_featured: $('#squirrels_is_featured').val(),
                description: $('#squirrels_description').val(),
                exterior: $('#squirrels_exterior').val(),
                interior: $('#squirrels_interior').val(),
                features: JSON.stringify(features),
                images: JSON.stringify(images)
            },
            success: function(r)
            {
                if(r != '0')
                {
                    location.href = '?page=squirrels_inventory';
                }
                else
                {
                    console.log(r);
                    alert('There\'s been an error.');
                }
            },
            error: function(x, y, z)
            {
                console.log(x.responseText);
                console.log(x);
                console.log(y);
                console.log(z);
                alert('There\'s been an error.');
            }
        });

    } );

    $( '#squirrels-inventory-delete' ).click( function() {

        var b = confirm('Are you sure you want to delete this item?');
        if (b) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'squirrels_inventory_delete',
                    id: url_variables.id
                },
                success: function (r) {
                    if(r != '0') {
                        location.href = '?page=squirrels_inventory';
                    }
                    else {
                        console.log(r);
                        alert('There\'s been an error.');
                    }
                },
                error: function (x, y, z) {
                    console.log(x.responseText);
                    console.log(x);
                    console.log(y);
                    console.log(z);
                    alert('There\'s been an error.');
                }
            });
        }
    } );

    $('#squirrels-upload-images').click(function(e){

        e.preventDefault();

        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            file_frame.open();
            return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Upload Images',
            button: {
                text: 'Save'
            },
            multiple: true  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {

            var selection = file_frame.state().get('selection');

            selection.map( function( attachment ) {

                attachment = attachment.toJSON();

                var add_image = true;
                for (var i=0; i<images.length; i++){
                    if (attachment.id == images[i].id){
                        add_image = false;
                        break;
                    }
                }

                if (add_image) {

                    $('#squirrels-images-admin').prepend('<div class="image-' + attachment.id + '"><img src="' + attachment.url + '" width="250"><br><span class="remove" data-id="' + attachment.id + '">remove</span> | <span class="default" data-id="' + attachment.id + '">make default</span></div>');
                    images.push({
                        id: 0,
                        media_id: attachment.id,
                        url: attachment.url,
                        def: 0
                    });
                }
            });
        });

        // Finally, open the modal
        file_frame.open();
    });

    var container = $('#squirrels-images-admin');

    container.on('click', 'span.remove', function(){
        var id = $(this).data('id');
        $('#squirrels-images-admin').find('.image-'+id).each(function(){
            $(this).remove();
        });
        var new_images = [];
        for (var i=0; i<images.length; i++){
            if (images[i].media_id != id) {
                new_images.push(images[i]);
            }
        }
        images = new_images;
    });

    container.on('click', 'span.default', function(){
        var id = $(this).data('id');
        var container = $('#squirrels-images-admin');
        container.find('div').each(function(){
            $(this).removeClass('default');
        });
        container.find('.image-'+id).addClass('default');
        for (var i=0; i<images.length; i++){
            if (images[i].media_id == id) {
                images[i].def = 1;
            } else {
                images[i].def = 0;
            }
        }
    });

})(jQuery);

function addFeature(feature_id, title, value) {

    var index = features.length;
    features.push({
        id: '',
        index: index,
        feature_id: feature_id,
        title: title,
        value: value,
        remove: 0
    });

    jQuery('#squirrels-feature-table').find('tbody').append('<tr id="feature-'+index+'"><td></td><td>'+title+'</td><td>'+value+'</td><td><input data-index="'+index+'" class="remove-feature button-secondary delete" value="Remove" type="button"></td></tr>')
}