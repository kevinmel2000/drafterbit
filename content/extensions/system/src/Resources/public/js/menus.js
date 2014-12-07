(function($, drafTerbit){

	//menu type
	$('input[name="type"]').change(function(){
		var id = $(this).val();
		toggleTypeSection(id);
	});


	var toggleTypeSection = function(id) {
		$('.type-section').hide()
		$('.type-section-'+id).show()
	}

})(jQuery, drafTerbit);