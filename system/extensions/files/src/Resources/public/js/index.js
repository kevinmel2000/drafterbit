(function($){

	// file
	$(".file").contextmenu({
		onItem: function(c, e){
			e.preventDefault();

			switch($(e.target).attr('id')) {
				
				case 'rename':
					File.rename(c);
					break;
				case 'delete':
					alert('Coming soon !');
					break;
			}
		}
	});

	// handle click
	$(".file a").click(function(e){
		e.preventDefault();
		window.open($(this).attr('href'), '_blank', 'width=600;');
	});

	File = {
		rename: function(c){
			$(c).children('a').hide();
			name = $(c).children('a').text().trim();
			$(c).prepend('<form method="POST"><input class="new-file-name" name="new-file-name" type="text"/></form>');
			$('.new-file-name').val(name);
			$('.new-file-name').attr('size', name.length);
			$('.new-file-name').select();
			console.log(name);
		}
	}

	$(document).on('blur', 'input[name="new-file-name"]', function(){
    	$(this).parent('form').submit();
    });

    $(document).on('blur', 'input[name="new-folder-name"]', function(){
    	$('.creating form').hide();
    	$('.creating').prepend('creating...');
    	$(this).parent('form').submit();
    });
	
	newRow = '<tr>\n\
                <td><input id="files" type="checkbox"></th>\n\
                <td class="creating"><form method="POST"><input name="new-folder-name" type="text"></form></th>\n\
                <td>Folder</th>\n\
                <td>-</th>\n\
             </tr>';

    creating = false;
	$('#new-folder').click(function(e){
		e.preventDefault();
		
		if(!creating) {
			$('#files-data-table tbody').prepend(newRow);
			$('input[name="new-folder-name"]').focus();
			creating = true;
		}
	});
/*
	$('#new-folder-form').on('shown.bs.modal', function (e) {
		$('input[name="folder-name"]').select();
	})*/

	// datatables
    var table = $("#files-data-table").on('preXhr.dt', function ( e, settings, data ) {
        	data.relPath = 'test-rel/path'
    	}).dataTable({
        oLanguage: {
          sLengthMenu: "Showing _MENU_ records per page",
          sSearch: "Search: _INPUT_",
        },
      	columnDefs: [
        {orderable: false, searchable:false, targets:[0]}
      	],
      	//processing: true,
      	deferRender: true,
        //"serverSide": true,
        ajax: "/admin/files/data"
    });

    $('#files').checkAll({showIndeterminate:true});


})(jQuery);