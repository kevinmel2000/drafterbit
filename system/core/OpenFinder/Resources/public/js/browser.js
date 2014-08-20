(function($){

	// Helper function to get parameters from the query string.
	function getUrlParam(paramName)
	{
	  var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
	  var match = window.location.search.match(reParam) ;
	 
	  return (match && match.length > 1) ? match[1] : '' ;
	}
	var funcNum = getUrlParam('CKEditorFuncNum');

	ckeditorCallback = function(e){
		e.preventDefault( );
		url = $(e.currentTarget).attr('href');
		console.log(e.currentTarget);
		window.opener.CKEDITOR.tools.callFunction(funcNum, '/uploads/'+url);
		window.close();
	}

	$('#finder-container').openFinder({
		url: '/admin/open-finder/data',
		onISelect: ckeditorCallback
	});

})(jQuery);