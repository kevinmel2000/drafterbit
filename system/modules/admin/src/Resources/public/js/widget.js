(function($, drafterbit){

	$('.widget-item').on('click', function(e) {
		e.preventDefault();
		var a = e.target;
		var widget = $(a).data('widget');
		var pos = $(a).data('position');

		$('.widget-form-container .modal-content').load(
			[drafterbit.adminUrl,'/setting/themes/widget-add/',widget].join(''),
			function(){
				$('.widget-form-container').modal('show');
			}
		);

		$('.widgets').modal('hide');
	});

	$(document).on('click', '.widget .widget-remover', function(e){
		e.preventDefault();

		var id = $(e.currentTarget).data('id');

		$.post(drafterbit.adminUrl+'/setting/themes/widget-remove/', {id:id})
			.done(function(){
				$('#widget-'+id).remove();
			});
	});

	$('.widgets').on('show.bs.modal', function (e) {
  		var a = e.relatedTarget;
  		var pos = $(a).data('position');
  		$('.widget-item').data('position', pos);
	});

	$(document).on('click', '.registered-widget', function(e){
		e.preventDefault();

		var a = e.target;
		var id = $(a).data('id');

		$('.widget-form-container .modal-content').load(
			[drafterbit.adminUrl,'/setting/themes/widget-edit/',id].join(''),
			function(){
				$('.widget-form-container').modal('show');
			}
		);
	});

	//form stuff

})(jQuery, drafterbit);