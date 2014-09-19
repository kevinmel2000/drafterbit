(function($, drafterbit) {
      var dt =  $("#user-data-table").dataTable({
             "oLanguage": {
              "sLengthMenu": "Showing _MENU_ records per page",
              "sSearch": "Search: _INPUT_",
            },
          "columnDefs": [
            {'orderable': false, 'searchable':false, 'targets':[0]}
          ]
        });
  
    drafterbit.replaceDTSearch(dt);

    $('#user').checkAll({showIndeterminate:true});
})(jQuery, drafterbit);