//$('#post-add-form').ajaxForm(options);
$('.dropdown-menu').find('input, select, option').click(function (e) {
    e.stopPropagation();
});