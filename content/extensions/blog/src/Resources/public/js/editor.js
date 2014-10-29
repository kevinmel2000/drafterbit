(function($, tagOptions, tags, CKEDITOR){

	var form = $('#post-edit-form'),
		spinner = $('i.spinner'),
		id = $('input[name="id"]'),
		closeText = $('.dt-editor-close-text');

	//magisuggest-ify tags
	var tagsInput = $('#tags').magicSuggest({
		name: 'tags',
		cls: 'tags-input',
		placeholder: 'add tags here',
		hideTrigger: true,
		toggleOnClick: true,
		maxSuggestions: 5,
		data: tagOptions,
		value: tags,
		highlight: false
	});

	// change dropdown default width and position
	// of magicsuggest
	$(tagsInput).on('expand', function(c){

	  pos = $('.ms-sel-ctn').find('input').position();
	  h = $('.ms-sel-ctn').find('input').height();

	   $('.ms-res-ctn').css({
	   		width: "auto",
	        position: "absolute",
	        top: (pos.top + h) + "px",
	        left: (pos.left) + "px"
	    });
	});

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

})(jQuery, tagOptions, tags, CKEDITOR);