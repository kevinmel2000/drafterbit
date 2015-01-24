(function($, drafTerbit){

    $('#finder-container').finder(
        {
            url: drafTerbit.adminUrl+'/files/data',
            data: {
                csrf: drafTerbit.csrfToken,
                permissions: {            	
	                upload: drafTerbit.permissions.files.upload,
	                delete: drafTerbit.permissions.files.delete,
	                move: drafTerbit.permissions.files.move
                }
            }
        }
    );

})(jQuery, drafTerbit);