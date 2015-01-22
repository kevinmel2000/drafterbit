(function($){

  $('body').hide();
  NProgress.start();
  
  window.onunload = function(){
    $('body').hide();
  };

  $(window).load(function(){
    $('body').fadeIn('fast');
    NProgress.done();
  });

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