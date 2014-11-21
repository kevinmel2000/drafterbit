(function($, drafTerbit){

	$('.widget-item').on('click', function(e) {
		e.preventDefault();
		var a = e.target;
		var widget = $(a).data('widget');
		var pos = $(a).data('position');

		$('.widget-form-container .modal-content').load(
			[drafTerbit.adminUrl,'/setting/themes/widget/add/',widget,"?pos="+pos].join(''),
			function(){
				$('.widget-form-container').modal('show');
			}
		);

		$('.widgets').modal('hide');
	});

	$(document).on('click', '.widget .widget-remover', function(e){
		e.preventDefault();

		var id = $(e.currentTarget).data('id');

		$.post(drafTerbit.adminUrl+'/setting/themes/widget-remove/', {id:id})
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
			[drafTerbit.adminUrl,'/setting/themes/widget/edit/',id].join(''),
			function(){
				$('.widget-form-container').modal('show');
			}
		);
	});

	//form stuff
	$(document).on('submit', '.widget-edit-form', function(e){
		e.preventDefault();
		$(this).ajaxSubmit({
			url: drafTerbit.adminUrl+'/setting/themes/widget/save',
			success: function(res){
				$.notify(res.message, res.status);
			}
		});
	});

})(jQuery, drafTerbit);