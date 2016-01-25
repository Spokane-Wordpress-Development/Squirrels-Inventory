(function($){

    $('#squirrels-make').change(function(){

        var make_id = $(this).val();
        var models = $('#squirrels-model');
        models.html('<option value="">Model (All)</option>');

        if (make_id.length > 0) {
            for (var m = 0; m < squirrels_models.length; m++) {
                if (squirrels_models[m].make_id == make_id) {
                    models.append('<option value="' + squirrels_models[m].id + '">' + squirrels_models[m].title + '</option>');
                }
            }
        }
    });

})(jQuery);