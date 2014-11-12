(function($, document){

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

			$.notify('Installation success !, redirecting to login page... ', 'success');
			setTimeout(function(){
				window.location.replace('/admin/login');
			}, 3000);
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

})(jQuery, document);