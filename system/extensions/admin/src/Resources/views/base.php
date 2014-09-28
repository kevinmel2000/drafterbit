<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title ?> | Drafterbit</title>
        <!-- Core CSS - Include with every page -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="<?php echo $stylesheetHref; ?>"/>
        <script>
        (function(){
          drafTerbit = {
            baseUrl: "<?php echo base_url() ?>",
            adminUrl: "<?php echo admin_url() ?>",

            //replace datatable search box;
            replaceDTSearch: function(dt) {
              $('.dataTables_filter').remove();
              //search filter
              $(document).on('keyup', "input[type=search]", function(){
                dt.fnFilter($(this).val());
              });
            }
          }
        })();
        </script>
    </head>

    <body>

            <?php echo $partials['nav']; ?>
  
            <div class="page-wrapper">
                <?php echo $content; ?>
            </div>
            <?php echo $partials['footer']; ?>
            <!-- /#page-wrapper -->

        <script src="<?php echo $scriptSrc;?>"></script>
        
        <?php if($messages): ?>
        <script>
                toastr.options = {
                  "closeButton": true,
                  "debug": false,
                  "positionClass": "toast-top-center",
                  "onclick": null,
                  "showDuration": "500",
                  "hideDuration": "100",
                  "timeOut": "5000",
                  "extendedTimeOut": "10000000",
                  "showEasing": "linear",
                  "hideEasing": "linear",
                  "showMethod": "fadeIn",
                  "hideMethod": "fadeOut"
                }
                <?php foreach( $messages as $message ): ?>
                    msg = "<?php echo addslashes($message['text']) ?>";
                    toastr.<?php echo $message['type'] ?>(msg, "<?php echo $message['title'] ?>");
                <?php endforeach; ?>
        </script>
        <?php endif;?>
</body>
</html>