(function($, drafTerbit) {

    drafTerbit.comments = {};

    if (window.location.hash == '') {
        window.location.hash = 'active';
    }
    
    var urlHash = window.location.hash.replace('#','');

    $('.comments-status-filter option[value="'+urlHash+'"]').prop('selected', true);

    // datatables
    drafTerbit.comments.dt =  $("#comments-data-table").dataTable(
        {
            "oLanguage": {
                //"sLengthMenu": "Showing _MENU_ records per page",
                ///"sSearch": "Search this table: _INPUT_",
            },
            "columnDefs": [
            {'orderable': false, 'searchable':false, 'targets':[0]}
            ]
        }
    );

    drafTerbit.replaceDTSearch(drafTerbit.comments.dt);

    $('#comments-checkall').checkAll({showIndeterminate:true});

    // style all pending
    var stylePendingRow = function(){
        $(document).find('.approve:visible').parents('tr').addClass('warning');
        $(document).find('.unapprove:visible').parents('tr').removeClass('warning');
    }

    stylePendingRow();

    // listen
    $(document).on(
        'click',
        '.comment-action .status',
        function(e){
            e.preventDefault();

            var status = $(this).data('status');

            $.post(
                drafTerbit.adminUrl+'/comments/status',
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
            } else if ($(this).hasClass('unspam')) {
                $(this).parents('tr').fadeOut('fast');
                $.notify('Comment marked as not spam', 'warning');
            } else {
                $(this).toggle();
                $(this).siblings('.unapprove, .approve').toggle();
            }

            stylePendingRow();
        }
    );

    // listen
    $(document).on(
        'click',
        '.comment-action .trash',
        function(e){
            e.preventDefault();
            $.post(
                drafTerbit.adminUrl+'/comments/quick-trash',
                {
                    id: $(this).data('id'),
                    csrf: drafTerbit.csrfToken,
                },
                function(data){
                    $.notify(data.msg, data.status);
                }
            );
            $(this).parents('tr').fadeOut('fast');
        }
    );

    // listen
    $(document).on(
        'click',
        '.comment-action .reply',
        function(e){
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

            if (!$(this).data('append')) {
                $(this).parents('td').append(form);
                $(this).data('append', true);
            } else {
                $(this).parents('td').children('.inline-form').toggle();
            }
        }
    );

    $(document).on(
        'click',
        '.inline-form-cancel',
        function(){
            $(this).parents('.inline-form').hide();
        }
    );

    // listen
    $(document).on(
        'click',
        '.inline-form-submit',
        function(){
        
            var comment = $(this).parent().siblings('textarea').val();
            if (comment.trim() !== '') {
                $.post(
                    drafTerbit.adminUrl+'/comments/quick-reply',
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

        }
    );

    filterByStatus = function(status){

        var status = status || 'active';

        drafTerbit.comments.dt.api().ajax.url(drafTerbit.adminUrl+"/comments/data/"+status+".json").load(
            function(){

                window.location.hash = status;
                stylePendingRow();
            }
        );
        
        //refresh pages index form
    }

    filterByStatus();

    // change trash, add restore button
    changeUncreateAction = function(s){
        if (s === 'trashed') {
            $('.uncreate-action').html('<i class="fa fa-trash-o"></i> Delete').val('delete');
            $('.uncreate-action').before('<button type="submit" name="action" value="restore" class="btn btn-sm btn-default comments-restore"><i class="fa fa-refresh"></i> Restore </button>');
        } else {
            $('.uncreate-action').html('<i class="fa fa-trash-o"></i> Trash').val('trash');
            $('.comments-restore').remove();
        }
    }

    changeUncreateAction(urlHash);

    //status-filter
    $('.comments-status-filter').on(
        'change',
        function(){
            var s = $(this).val();
            filterByStatus(s);
            changeUncreateAction(s);
        }
    );

    $('#comments-index-form').ajaxForm(
        function(){
            var urlHash2 = window.location.hash.replace('#','');
            drafTerbit.comments.dt.api().ajax.url(drafTerbit.adminUrl+"/comments/data/"+urlHash2+".json").load();
        }
    );

})(jQuery, drafTerbit);