(function($){
	$('.widget-item').on('click', function(e) {
		e.preventDefault();
		var a = e.target;
		var widget = $(a).data('widget');
		var pos = $(a).data('position');

		$.get('/backend/setting/themes/widget-add/'+widget, {pos: pos})
			.done(function(data){
				$('.position-'+pos).children().first().after(data);
			});

		$('.widgets').modal('hide');
	});

	$(document).on('click', '.widget .widget-remover', function(e){
		e.preventDefault();

		var id = $(e.currentTarget).data('id');

		$.post('/backend/setting/themes/widget-remove/', {id:id})
			.done(function(){
				$('#widget-'+id).remove();
			});
	});

	$('.widgets').on('show.bs.modal', function (e) {
  		var a = e.relatedTarget;
  		var pos = $(a).data('position');
  		$('.widget-item').data('position', pos);
	});

})(jQuery);