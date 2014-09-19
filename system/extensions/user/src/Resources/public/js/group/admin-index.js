 (function($, drafterbit) {
      var dt =   $("#group-data-table").dataTable({
             "oLanguage": {
              "sLengthMenu": "Showing _MENU_ records per page",
              "sSearch": "Search: _INPUT_",
            },
          "columnDefs": [
            {'orderable': false, 'searchable':false, 'targets':[0]}
          ]
        });

        drafterbit.replaceDTSearch(dt);

        $('#groups').checkAll({showIndeterminate:true});
})(jQuery, drafterbit);