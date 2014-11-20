(function(document){
	var as = document.getElementsByClassName('link-toggler');

	for(var i=0;i<as.length;i++) {
		as[i].addEventListener('click', function(e){
			e.preventDefault();
			var id = this.dataset.commentId;
			var el = document.getElementById('form-comment-'+id);

			if(el.style.display == 'block') {
				this.text = 'Reply';
				el.style.display = 'none';
			} else {
				this.text = 'Cancel';
				el.style.display = 'block';
			}
		});
	}
})(document);