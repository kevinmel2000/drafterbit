(function($){

	var form = $('#roles-edit-form'),
		id = $('input[name="id"]');

	//form
	form.ajaxForm({
		success: function(data){
			
			dirty = false;

			if (data.id) {
                id.val(data.id);
            }

			$.notify(data.message, data.status);
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