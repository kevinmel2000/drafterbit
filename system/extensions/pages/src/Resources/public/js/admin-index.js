(function($, drafTerbit) {

    drafTerbit.pages = {};

    if(window.location.hash == '') {
        window.location.hash = 'untrashed';
    }
    
    var urlHash = window.location.hash.replace('#','');

    $('.pages-status-filter option[value="'+urlHash+'"]').prop('selected', true);

    drafTerbit.pages.dt = $("#pages-data-table").dataTable({
        ajax: {
            url: drafTerbit.adminUrl+"/pages/data/"+urlHash+".json",
            // type: 'post'
        },
        //"serverSide": true,
        "bFilter": true,
        "oLanguage": {
          "sLengthMenu": "Showing _MENU_ records per page",
          "sSearch": "_INPUT_",
        },
        "columnDefs": [
            {'orderable': false, 'searchable':false, 'targets':[0]}
        ]
    });

    drafTerbit.replaceDTSearch(drafTerbit.pages.dt);

    // Checks
    $('#pages-checkall').checkAll({showIndeterminate:true});

    filterByStatus = function(status){

        var status = status || 'untrashed';

        drafTerbit.pages.dt.api().ajax.url(drafTerbit.adminUrl+"/pages/data/"+status+".json").load();
        window.location.hash = status;
    }

    // @todo create restore button
    changeUncreateAction = function(s){
        if(s === 'trashed') {
            $('.uncreate-action').html('<i class="fa fa-trash-o"></i> Delete Permanently').val('delete');
        } else {
            $('.uncreate-action').html('<i class="fa fa-trash-o"></i> Trash').val('trash');
        }
    }

    changeUncreateAction(urlHash);

    //status-filter
    $('.pages-status-filter').on('change', function(){
        var s = $(this).val();
        filterByStatus(s);
        changeUncreateAction(s);
    });

})(jQuery, drafTerbit);