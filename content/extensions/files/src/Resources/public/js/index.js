(function($){

    $('#finder-container').finder({
        url: drafTerbit.adminUrl+'/files/data',
        data: {
            csrf: drafTerbit.csrfToken
        }
    });

})(jQuery);