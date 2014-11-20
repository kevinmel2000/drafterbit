 (function($){
  drafTerbit = {
    baseUrl: "<?php echo base_url() ?>",
    adminUrl: "<?php echo admin_url() ?>",
    csrfToken: "<?php echo csrf_token() ?>",

    //replace datatable search box;
    replaceDTSearch: function(dt) {
      $('.dataTables_filter').remove();


      $(document).on('keydown', "input[type=search]", function(e){
        var code = e.keyCode || e.which;
        if (code == 13) {
          e.preventDefault();
        }}
      );

      //search filter
      $(document).on('keyup', "input[type=search]", function(e){

        var val = $(this).val();
        dt.api().search($(this).val()).draw();
        
      });
    }
  }
})(jQuery);