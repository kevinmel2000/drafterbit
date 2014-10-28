(function($, CKEDITOR){

	var form = $('#page-create-form'), spinner = $('i.spinner');

	form.ajaxForm({

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

		success:function(){
			spinner.removeClass('fa-spin fa-spinner');
			spinner.addClass('fa-check');
                  $.notify('Page Saved', 'success');
		}
	});

})(jQuery, CKEDITOR);