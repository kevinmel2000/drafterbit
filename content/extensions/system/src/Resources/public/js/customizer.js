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

    $('.menu-form').ajaxForm({
        dataType: 'json',
        success: function(res){
            
        }
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
    $('.menu-type').on('change', function(){
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

})(jQuery,drafTerbit);