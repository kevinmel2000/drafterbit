(function($, drafTerbit) {

    drafTerbit.users = {};

    if(window.location.hash == '') {
        window.location.hash = 'all';
    }

    $('.users-status-filter option[value="'+urlHash+'"]').prop('selected', true);

    
    var urlHash = window.location.hash.replace('#','');

      drafTerbit.users.dt =  $("#users-data-table").dataTable({
             "oLanguage": {
              "sLengthMenu": "Showing _MENU_ records per page",
              "sSearch": "Search: _INPUT_",
            },
          "columnDefs": [
            {'orderable': false, 'searchable':false, 'targets':[0]}
          ]
        });
  
    drafTerbit.replaceDTSearch(drafTerbit.users.dt);

    $('#users-checkall').checkAll({showIndeterminate:true});

    filterByStatus = function(status){

        var status = status || 'all';

        drafTerbit.users.dt.api().ajax.url(drafTerbit.adminUrl+"/user/data/"+status+".json").load();
        window.location.hash = status;
    }

    $('#users-index-form').ajaxForm(function(){
        var urlHash2 = window.location.hash.replace('#','');
        drafTerbit.users.dt.api().ajax.url(drafTerbit.adminUrl+"/user/data/"+urlHash2+".json").load();
    });

    //status-filter
    $('.users-status-filter').on('change', function(){
        var s = $(this).val();
        filterByStatus(s);
    });


})(jQuery, drafTerbit);