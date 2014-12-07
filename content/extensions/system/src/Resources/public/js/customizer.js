(function($, drafTerbit) {
    
    $('iframe').on('load', function(e){
        console.log(e.currentTarget.contentWindow.document.URL);
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