(function($, document, drafTerbit){

    $(document).on('click', '.begin-button', function(e){
        e.preventDefault();
        var a = e.target;
        var next = $(a).data('next');

        $('.install-section').hide();
        $(next).fadeIn();
    });

    $('#database-form').bootstrapValidator({
        fields: {
            'database[host]': {
                validators: {
                    notEmpty: {
                        message: 'Database host name required'
                    }
                }
            },
            'database[dbname]': {
                validators: {
                    notEmpty: {
                        message: 'Database name required'
                    }
                }
            },
            'database[user]': {
                validators: {
                    notEmpty: {
                        message: 'Database user name required'
                    }
                }
            },
        }
    });

    $('.static-form').ajaxForm({
        dataType: 'json',
        success: function(res, status, xhr, form){
            if(res.message == 'ok') {
                var next = form.data('next');
                $('.install-section').hide();
                $(next).fadeIn();
            
            } else if(res.config) {

                $('.config-textarea textarea').text(res.config);
                $('.config-textarea').modal('show');

            } else {
                
                $.notify(res.message, 'danger');
            }
        }
    });

    $('.install-form').ajaxForm({
        beforeSend: function(){
            $('.install-trapper').fadeIn();
        },
        success: function(res, status, xhr, form){    
            
            $('.install-trapper').fadeOut();

            if(res.message == 'ok') {
                $.notify('Installation success !, redirecting to login page... ', 'success');
                setTimeout(function(){
                    window.location.replace(drafTerbit.baseUrl+'admin/login');
                }, 3000);
            }
        }
    });

    function checkDatabase(config)
    {
        $.ajax({
            url: '/installer/check',
            type:'post',
            data: config,
            dataType: 'json',
            async: false,
            success: function(data){
                test = data.message
            }
        });

        return test;
    }

})(jQuery, document, drafTerbit);