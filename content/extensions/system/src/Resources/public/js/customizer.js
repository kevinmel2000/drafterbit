(function($, drafTerbit) {

    $(window).on('beforeunload', function(e){
        $.ajax({
            type: 'post',
            async: false,
            url:drafTerbit.adminUrl+'/setting/themes/custom-preview?csrf='+drafTerbit.csrfToken,
            data:{endSession:1}
        });
    });

    $('.customizer-ajax-form').ajaxForm({
        dataType: 'json',
        success: function(res){
            if(res.url) {
                $('iframe').prop('src', res.url);
                console.log(res.url);
            }
        }
    });

    $(document).on('submit', '.menu-form',function(e){
        e.preventDefault();
        $(this).ajaxSubmit({
            dataType: 'json',
            success: function(res, a, b, form){
                
                if(!res.error) {

                    $(form).find('input[name="id"]').val(res.id);
                    
                    $.notify(res.message, 'success');
                    
                    var frames = document.getElementsByTagName('IFRAME');
                    frames[0].contentWindow.location.reload(true);
                }

            }
        });
    });

    $('iframe').on('load', function(e){
        var currentPreviewUrl = e.currentTarget.contentWindow.document.URL
        $('#customizer-form input[name="url"]').val(currentPreviewUrl);
        
        $(this).contents().find('a').on('click', function(e){
            if(e.currentTarget.href.indexOf(drafTerbit.baseUrl) == -1) {
                e.preventDefault();
                console.log('Can\'t load external url when customizing');
            }
        });        
    });

    $('iframe').contents().find('a').on('click', function(e){
        if(e.currentTarget.href.indexOf(drafTerbit.baseUrl) == -1) {
            e.preventDefault();
            console.log('Can\'t load external url while customizing');
        }
    });

    $('.widget-section').click(function(e){
        e.preventDefault();
        $('#widget-section').show();
        $('#menus-section').hide();
        $('.col-container').animate({marginLeft:"-300px"}, 300);
    });

    $('.menus-section').click(function(e){
        e.preventDefault();
        $('#widget-section').hide();
        $('#menus-section').show();
        $('.col-container').animate({marginLeft:"-300px"}, 300);
    });

    $('.widget-section-back').click(function(e){
        e.preventDefault();
        $('.col-container').animate({marginLeft:"0px"}, 300);
    });

    // menu type selectbox 
    $(document).on('change', '.menu-type', function(){
        var id = $(this).val();
        var parent = $(this).parent('.form-group');
        if(id == 1) {
            parent.siblings('.menu-type-page').hide();
            parent.siblings('.menu-type-link').show();
        } else if(id == 2) {
            parent.siblings('.menu-type-link').hide();
            parent.siblings('.menu-type-page').show();
        }
    });

    $(document).on('keyup', '.menu-label', function(){
        var val= $(this).val();

        if(val.trim() == '') {
            val = 'unlabeled';
        }

        $(this).closest('.panel-collapse').siblings('.panel-heading').find('a').text(val);;
    });

    // add menu
    $(document).on('click', '.menu-adder', function(e){
        e.preventDefault();
        var position = $(this).data('position');
        var theme = $(this).data('theme');
        var source   = $("#menu-item-template").html();
        var template = Handlebars.compile(source);
        var html    = template({
            position: position,
            theme: theme,
            id: Date.now(),
            formAction: drafTerbit.adminUrl+'/setting/themes/menus/save'
        });

        $(this).closest('.well').before(html);
    });

    //delete menu
    $(document).on('click', '.delete-menu-item', function(e){
        e.preventDefault();
        var id = $(this).closest('.menu-form').find('input[name="id"]').val();
        
        $.post(drafTerbit.adminUrl+'/setting/themes/menus/delete', {id:id});

        $(this).closest('.menu-item-container').fadeOut('fast');
        $(this).closest('.menu-item-container').remove();

        var frames = document.getElementsByTagName('IFRAME');
        frames[0].contentWindow.location.reload(true);
    });


})(jQuery,drafTerbit);