(function($, drafTerbit){

	// Helper function to get parameters from the query string.
	function getUrlParam(paramName)
	{
	  var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
	  var match = window.location.search.match(reParam) ;
	 
	  return (match && match.length > 1) ? match[1] : '' ;
	}
	var funcNum = getUrlParam('CKEditorFuncNum');

	var ckeditorCallback;
	if(funcNum != '') {

		ckeditorCallback = function(e){
			e.preventDefault( );
			url = $(e.currentTarget).attr('href');
			console.log(e.currentTarget);
			// @todo create content path
			window.opener.CKEDITOR.tools.callFunction(funcNum, drafTerbit.baseUrl+'/content/files/'+url);
			window.close();
		}
	}

	$('#finder-container').openFinder({
		url: drafTerbit.adminUrl+'/files/data',
		onISelect: ckeditorCallback
	});

})(jQuery, drafTerbit);