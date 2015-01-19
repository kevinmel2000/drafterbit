 (function($, drafTerbit) {

      drafTerbit.roles = {};

      drafTerbit.roles.dt =   $("#roles-data-table").dataTable(
          {
                "oLanguage": {
                    "sLengthMenu": "Showing _MENU_ records per page",
                    "sSearch": "Search: _INPUT_",
                },
                "columnDefs": [
                {'orderable': false, 'searchable':false, 'targets':[0]}
                ]
          }
      );

        drafTerbit.replaceDTSearch(drafTerbit.roles.dt);

        $('#roles-checkall').checkAll({showIndeterminate:true});

        $('#roles-index-form').ajaxForm(
            {
                dataType: 'json',
                beforeSend: function(){
                    if (confirm('Are you sure you want to delete those roles, this con not be undone ?')) {
                        return true;
                    } else {
                        return false;
                    }
                },
                success: function(res){
                    $.notify(res.message, res.status);
                      drafTerbit.roles.dt.api().ajax.url(drafTerbit.adminUrl+"/user/roles/data/all.json").load();
                }
            }
        );

})(jQuery, drafTerbit);