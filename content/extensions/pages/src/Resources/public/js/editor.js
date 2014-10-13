(function($, CKEDITOR){

	$('#page-create-form').ajaxForm({

		beforeSerialize: function() {
			// fixes ckeditor content
			for ( instance in CKEDITOR.instances ) {
			    CKEDITOR.instances[instance].updateElement();
			}
		},

		success:function(){
			toastr['success']('Page Saved 1');
		}
	});

})(jQuery, CKEDITOR);