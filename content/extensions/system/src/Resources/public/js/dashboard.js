(function($){
	
	$("#panel-container ul").gridster({
		widget_margins: [0, 0],
		widget_base_dimensions: [200, 200],
		resize: {
            enabled: true,
            max_size: [4, 4],
            min_size: [1, 1]
         }
	}); 

})(jQuery);