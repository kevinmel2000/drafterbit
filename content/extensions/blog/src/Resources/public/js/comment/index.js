(function($, drafTerbit) {

    // datatables
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
        $('.approve:visible').parents('tr').addClass('warning');
        $('.unapprove:visible').parents('tr').removeClass('warning');
    }

    stylePendingRow();

    // listen
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
            $.notify('Comment marked as spam', 'warning');
        } else {        
            $(this).toggle();
            $(this).siblings('.unapprove, .approve').toggle();
        }

        stylePendingRow();
    });

    // listen
    $('.comment-action').on('click', '.trash', function(e){
        e.preventDefault();
        $.post(drafTerbit.adminUrl+'/comments/quick-trash',
            {
                id: $(this).data('id'),
                csrf: drafTerbit.csrfToken,
            },
            function(data){
                $.notify(data.msg, data.status);
            }
        );
        $(this).parents('tr').fadeOut('fast');
    });

    // listen
    $('.comment-action').on('click', '.reply', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var postId = $(this).data('post-id');
        var form = [
            '<div class="inline-form">',
            '<textarea style="width:100%" class="form-control">',
            '</textarea>',
            '<div style="margin-top:5px;">',
                '<button type="button" class="btn btn-xs btn-default inline-form-cancel">Cancel</button>',
                '<button type="button" class="btn btn-xs btn-primary pull-right inline-form-submit" data-post-id="'+postId+'" data-id="'+id+'">Submit</button>',
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

    // listen
    $(document).on('click', '.inline-form-submit', function(){
        
        var comment = $(this).parent().siblings('textarea').val();
        if(comment.trim() !== '') {
            $.post(drafTerbit.adminUrl+'/comments/quick-reply',
                {
                    parentId: $(this).data('id'),
                    postId: $(this).data('post-id'),
                    comment: comment,
                    csrf: drafTerbit.csrfToken,
                },
                function(data){
                    $.notify(data.msg, data.status);
                }
            );
        }

    });

})(jQuery, drafTerbit);