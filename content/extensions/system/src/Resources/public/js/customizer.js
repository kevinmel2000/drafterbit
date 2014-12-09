(function($, drafTerbit) {

    $(window).on('beforeunload', function(){
        $.post(drafTerbit.adminUrl+'/setting/themes/custom-preview?csrf='+drafTerbit.csrfToken,{endSession:1});
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
            console.log('Can\'t load external url when customizing');
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

})(jQuery,drafTerbit);