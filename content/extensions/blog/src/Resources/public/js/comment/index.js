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

    // style all pending
    var stylePendingRow = function(){
        $('.approve:visible').parents('tr').css({backgroundColor:'#F2DEDE'});
        $('.unapprove:visible').parents('tr').css({backgroundColor:'#FFF'});
    }

    stylePendingRow();

    $('.comment-action').on('click', '.status', function(e){
        e.preventDefault();

        var status = $(this).data('status');

        $.post(drafTerbit.adminUrl+'/comments/status',
            {
                id: $(this).data('id'),
                status: status,
                csrf: drafTerbit.csrfToken,
            },
            function(){

            }
        );

        if (status == 2) {
            $(this).parents('tr').fadeOut('fast');
        } else {        
            $(this).toggle();
            $(this).siblings('.unapprove, .approve').toggle();
        }

        stylePendingRow();
    });

    $('.comment-action').on('click', '.reply', function(e){
        e.preventDefault();
        
        var form = [
            '<div class="inline-form">',
            '<textarea style="width:100%" class="form-control">',
            '</textarea>',
            '<div style="margin-top:5px;">',
                '<button type="button" class="btn btn-xs btn-default inline-form-cancel">Cancel</button>',
                '<button type="button" class="btn btn-xs btn-primary pull-right inline-form-submit">Submit</button>',
            '</div>',
            '</div>'
        ].join('');

        if(!$(this).data('append')) {        
            $(this).parents('td').append(form);
            $(this).data('append', true);
        } else {
            $(this).parents('td').children('.inline-form').toggle();
        }
    });

    $(document).on('click', '.inline-form-cancel', function(){
        $(this).parents('.inline-form').hide(); 
    });

    $(document).on('click', '.inline-form-submit', function(){
        
        var comment = $(this).parent().siblings('textarea').val();
        if(comment.trim() !== '') {
            $.post(drafTerbit.adminUrl+'/comments/quick-reply',
                {
                    id: $(this).data('id'),
                    comment: comment,
                    csrf: drafTerbit.csrfToken,
                },
                function(){

                }
            );
        }

    });

})(jQuery, drafTerbit);