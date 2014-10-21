
(function($, drafTerbit) {
      var dt =  $("#comments-data-table").dataTable({
            "oLanguage": {
              //"sLengthMenu": "Showing _MENU_ records per page",
              ///"sSearch": "Search this table: _INPUT_",
            },
          "columnDefs": [
            {'orderable': false, 'searchable':false, 'targets':[0]}
          ]
        });

    drafTerbit.replaceDTSearch(dt);

    $('#comments-checkall').checkAll({showIndeterminate:true});
})(jQuery, drafTerbit);