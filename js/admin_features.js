var url_variables = url_variables || {};

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
})(jQuery);