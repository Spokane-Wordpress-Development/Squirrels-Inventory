var url_variables = url_variables || {};
var features = features || {};

(function($){

    if(url_variables.action == 'add' || (url_variables.action == 'edit' && $('.squirrels-feature-custom-option').length == 0))
    {
        addCustomFeatureOption();
    }

    $('#squirrels-feature-type').change(addCustomFeatureOption);

    $('body').on('click', '.squirrels-feature-custom-option-add', function(){
        //switch existing adds to removes
        changeCustomFeatureAddToRemove();

        //add new input
        addCustomFeatureOption();
    }).on('click', '.squirrels-feature-custom-option-remove', function(){
        $(this).closest('.squirrels-feature-custom-option').remove();
    }).on('keypress', '.squirrels-feature-custom-option-input', function(e){
        if(e.which == 13)
        {
            $('.squirrels-feature-custom-option-add').trigger('click');

            $('.squirrels-feature-custom-option').first().find('input').focus();
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

    function changeCustomFeatureAddToRemove()
    {
        $('.squirrels-feature-custom-option-add').each(function(){
            $(this).after(
                '<span class="squirrels-feature-custom-option-remove dashicons dashicons-dismiss"></span>'
            );

            $(this).remove();
        });
    }

    function addCustomFeatureOption()
    {
        if($('#squirrels-feature-type').val() == 1)
        {
            var isDefault = '';

            if($('.squirrels-feature-custom-option').length == 0)
            {
                isDefault = 'checked';
            }

            $('#squirrels-feature-custom-options').prepend(
                '<tr class="squirrels-feature-custom-option">' +
                '<th></th>' +
                '<td>' +
                    '<input type="radio" name="squirrels-feature-custom-option-default" ' + isDefault + '>' +
                    '<input type="text" class="squirrels-feature-custom-option-input" /><span class="squirrels-feature-custom-option-add dashicons dashicons-plus-alt"></span>' +
                '</td>' +
                '</tr>'
            );
        }
        else
        {
            $('.squirrels-feature-custom-option').remove();
        }
    }


/**********************************************************************************************************************/
//Add, Edit, Delete Auto Page

    $( '.squirrels-feature' ).change( function() {

        var feature = features[ $(this).val() ];

        var options = feature.options;

        var html = '';

        if(feature.is_true_false)
        {
            html = '<option value="1" selected>Yes</option><option value="0">No</option>';
        }
        else
        {
            for(var option in options)
            {
                if(options.hasOwnProperty(option))
                {
                    html += '<option value="' + options[option].position + '" ' + ( ( option.is_default ) ? 'selected' : '') + '>' + options[option].title + '</option>';
                }
            }
        }

        $(this).next().html(html);

    } ).trigger('change');

    $( '#squirrels-add-feature').click( function() {
        //TODO: Add code to add aditional feature inputs
    } );

    $( '#squirrels-inventory-add, #squirrels-inventory-edit' ).click( function() {

        var id = (typeof url_variables.id != 'undefined') ? url_variables.id : 0;

        var features = {};

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            dataType: 'JSON',
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
                features: features
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

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'squirrels_inventory_delete',
                id: url_variables.id
            },
            success: function(r)
            {
                if(r.success > 0)
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


})(jQuery);