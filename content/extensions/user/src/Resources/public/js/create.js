(function($){
	$('#user-group').chosen();

	var form = $('#user-create-form');
	//form
	$('#user-create-form').ajaxForm(function(data){
		if(data.status == 'error') {
			data.status = 'danger';
		}
		$.notify(data.message, data.status);
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