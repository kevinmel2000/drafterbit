(function($, drafTerbit){

    $('#finder-container').finder(
        {
            url: drafTerbit.adminUrl+'/files/data',
            data: {
                csrf: drafTerbit.csrfToken,
            },
            permissions: {            	
                create: drafTerbit.permissions.files.create,
                move: drafTerbit.permissions.files.move,
                delete: drafTerbit.permissions.files.delete
            }
        }
    );

})(jQuery, drafTerbit);