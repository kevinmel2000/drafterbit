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

    $('#pages-checkall').checkAll({showIndeterminate:true});

})(jQuery, drafTerbit);