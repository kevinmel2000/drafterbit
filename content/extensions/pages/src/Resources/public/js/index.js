(function($, drafTerbit) {

    drafTerbit.pages = {};

    if (window.location.hash == '') {
        window.location.hash = 'untrashed';
    }
    
    var urlHash = window.location.hash.replace('#','');

    $('.pages-status-filter option[value="'+urlHash+'"]').prop('selected', true);

    drafTerbit.pages.dt = $("#pages-data-table").dataTable(
        {
            ajax: {
                url: drafTerbit.adminUrl+"/pages/data/"+urlHash+".json",
            },
            "bFilter": true,
            "oLanguage": {
                "sLengthMenu": "Showing _MENU_ records per page",
                "sSearch": "_INPUT_",
            },
            "columnDefs": [
            {'orderable': false, 'searchable':false, 'targets':[0]}
            ]
        }
    );

    drafTerbit.replaceDTSearch(drafTerbit.pages.dt);

    // Checks
    $('#pages-checkall').checkAll({showIndeterminate:true});

    filterByStatus = function(status){

        var status = status || 'untrashed';

        drafTerbit.pages.dt.api().ajax.url(drafTerbit.adminUrl+"/pages/data/"+status+".json").load();
        window.location.hash = status;

        //refresh pages index form
    }

    // change trash, add restore button
    changeUncreateAction = function(s){
        if (s === 'trashed') {
            $('.uncreate-action').html('<i class="fa fa-trash-o"></i> Delete').val('delete');
            $('.uncreate-action').before('<button type="submit" name="action" value="restore" class="btn btn-sm btn-default pages-restore"><i class="fa fa-refresh"></i> Restore </button>');
        } else {
            $('.uncreate-action').html('<i class="fa fa-trash-o"></i> Trash').val('trash');
            $('.pages-restore').remove();
        }
    }

    changeUncreateAction(urlHash);
    
    $('#pages-index-form').ajaxForm(
        function(){
            var urlHash2 = window.location.hash.replace('#','');
            drafTerbit.pages.dt.api().ajax.url(drafTerbit.adminUrl+"/pages/data/"+urlHash2+".json").load();
        }
    );

    //status-filter
    $('.pages-status-filter').on(
        'change',
        function(){
            var s = $(this).val();
            filterByStatus(s);
            changeUncreateAction(s);
        }
    );

})(jQuery, drafTerbit);