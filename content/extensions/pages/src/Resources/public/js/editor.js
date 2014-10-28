(function($, CKEDITOR){

	var form = $('#page-edit-form'),
		spinner = $('i.spinner'),
		id = $('input[name="id"]'),
		closeText = $('.dt-editor-close-text');

	form.ajaxForm({

		dataType: 'json',
		beforeSerialize: function() {
			// fixes ckeditor content
			for ( instance in CKEDITOR.instances ) {
			    CKEDITOR.instances[instance].updateElement();
			}
		},

		beforeSend: function(){
			spinner.removeClass('fa-check');
			spinner.addClass('fa-spin fa-spinner');
		},

		success:function(data){
			
			dirty = false;
			spinner.removeClass('fa-spin fa-spinner');
			spinner.addClass('fa-check');

			if(data.status == 'error') {
				data.status = 'danger';
			}

            $.notify(data.msg, data.status);

            if (data.id) {
                id.val(data.id);
            }

            closeText.text('Close');
		}
	});

    // check form before leaving page
    window.onbeforeunload = (function() {

        form.on('change', ':input', function() {
            dirty = true;
        });

        for ( instance in CKEDITOR.instances ) {
		    CKEDITOR.instances[instance].on('change', function(){
            	dirty = true;
        	});
		}

        return function(e) {
            if (dirty) return 'Discard unsaved changes ?';
        };

    })();

})(jQuery, CKEDITOR);