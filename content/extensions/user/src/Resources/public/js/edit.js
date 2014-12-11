(function($){
	$('#user-roles').chosen();

	var form = $('#user-edit-form'),
		id = $('input[name="id"]');

	//form
	$('#user_roles_chosen input', ':input').on('focus', function(){

	});

	// remove error message
	$(':input').on('focus', function(){
		$(this).closest('.form-group').find('.error-msg').remove();
		$(this).closest('.form-group').removeClass('has-error');
	});

	form.ajaxForm({
		success: function(data){
			
			dirty = false;

			if(data.error) {
				if(data.error.type == 'validation') {
					for(name in data.error.messages) {

						inputName = (name == 'roles') ? 'roles[]' : name;

						var inputCtn = $(':input[name="'+inputName+'"]').closest('.form-group');
						inputCtn.addClass('has-error');

						if(!inputCtn.children('.error-msg').length) {
							inputCtn.append('<span class="help-block error-msg">'+data.error.messages[name]+'</span>');
						}
					}
				}
			} else {

	            if (data.id) {
	                id.val(data.id);
            		
            		$.notify(data.message, data.status);
	            }
			}
		}
	});

	// check form before leaving page
    dirty = false;
    
    window.onbeforeunload = (function() {


        form.on('change', ':input', function() {
            dirty = true;
        });

        return function(e) {
            if (dirty) return 'Discard unsaved changes ?';
        };

    })();
})(jQuery);