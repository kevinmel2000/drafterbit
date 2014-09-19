(function($, drafterbit) {
        var dt = $("#page-data-table").dataTable({
            "bFilter": true,
             "oLanguage": {
              "sLengthMenu": "Showing _MENU_ records per page",
              "sSearch": "_INPUT_",
            },
          "columnDefs": [
            {'orderable': false, 'searchable':false, 'targets':[0]}
          ]
        });

    drafterbit.replaceDTSearch(dt);

    $('#page').checkAll({showIndeterminate:true});

})(jQuery, drafterbit);