
(function($, drafTerbit) {
      var dt =  $("#post-data-table").dataTable({
             "oLanguage": {
              //"sLengthMenu": "Showing _MENU_ records per page",
              ///"sSearch": "Search this table: _INPUT_",
            },
          "columnDefs": [
            {'orderable': false, 'searchable':false, 'targets':[0]}
          ]
        });

    drafTerbit.replaceDTSearch(dt);

    $('#post').checkAll({showIndeterminate:true});
})(jQuery, drafTerbit);