(function($){
    $('form').ajaxForm(
        {
            success: function(res){
                if (res.error) {
                    $.notify(res.message, 'error');
                } else {
                    window.location.replace(res.next);
                }
            }
        }
    );
    
})(jQuery);