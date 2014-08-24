//$('#post-add-form').ajaxForm(options);

var w = $('.content-inner').parent().width();
      $('.content-inner').width(w-100).css('float', 'left');
     // $('.ms-sel-ctn input').width(w-145);

$('.dropdown-menu').find('input, select, option').click(function (e) {
    e.stopPropagation();
});