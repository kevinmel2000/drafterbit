(function($, drafTerbit) {
    var dt = $("#pages-data-table").dataTable({
        "bFilter": true,
        "oLanguage": {
          "sLengthMenu": "Showing _MENU_ records per page",
          "sSearch": "_INPUT_",
        },
        "columnDefs": [
            {'orderable': false, 'searchable':false, 'targets':[0]}
        ]
    });

    drafTerbit.replaceDTSearch(dt);

    // Checks
    $('#pages-checkall').checkAll({showIndeterminate:true});

    //status-filter
    $('.pages-status-filter').on('change', function(){
        var val = $(this).val();
        window.location.replace(drafTerbit.adminUrl+'/pages/index/'+val);
    });

})(jQuery, drafTerbit);