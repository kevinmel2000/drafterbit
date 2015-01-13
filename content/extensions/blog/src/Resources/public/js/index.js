(function($, drafTerbit) {

        drafTerbit.blog = {};

        if(window.location.hash == '') {
            window.location.hash = 'untrashed';
        }
        
        var urlHash = window.location.hash.replace('#','');

        $('.blog-status-filter option[value="'+urlHash+'"]').prop('selected', true);

        drafTerbit.blog.dt =  $("#posts-data-table").dataTable({
                         "oLanguage": {
                            //"sLengthMenu": "Showing _MENU_ records per page",
                            ///"sSearch": "Search this table: _INPUT_",
                        },
                    "columnDefs": [
                        {'orderable': false, 'searchable':false, 'targets':[0]}
                    ]
                });

        drafTerbit.replaceDTSearch(drafTerbit.blog.dt);

        $('#posts-checkall').checkAll({showIndeterminate:true});

        filterByStatus = function(status) {

            var status = status || 'untrashed';

            drafTerbit.blog.dt.api().ajax.url(drafTerbit.adminUrl+"/blog/data/"+status+".json").load();
            window.location.hash = status;
        }

        // change trash, add restore button
        changeUncreateAction = function(s){
            if(s === 'trashed') {
                $('.uncreate-action').html('<i class="fa fa-trash-o"></i> Delete').val('delete');
                $('.uncreate-action').before('<button type="submit" name="action" value="restore" class="btn btn-sm btn-default posts-restore"><i class="fa fa-refresh"></i> Restore </button>');
            } else {
                $('.uncreate-action').html('<i class="fa fa-trash-o"></i> Trash').val('trash');
                $('.posts-restore').remove();
            }
        }

        changeUncreateAction(urlHash);
        
        $('#posts-index-form').ajaxForm(function(response){
            if(response.error) {
                $.notify(response.error.message, 'error');
            }

            var urlHash2 = window.location.hash.replace('#','');
            drafTerbit.blog.dt.api().ajax.url(drafTerbit.adminUrl+"/blog/data/"+urlHash2+".json").load();
        });

        //status-filter
        $('.blog-status-filter').on('change', function(){
            var s = $(this).val();
            filterByStatus(s);
            changeUncreateAction(s);        
        });

})(jQuery, drafTerbit);