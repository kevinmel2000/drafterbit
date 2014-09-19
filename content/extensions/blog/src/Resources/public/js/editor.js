(function($, tagOptions, tags){

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

	// handle drodown on setting
	$('.dropdown-menu').find('input, select, option').click(function (e) {
		e.stopPropagation();
	});

})(jQuery, tagOptions, tags);