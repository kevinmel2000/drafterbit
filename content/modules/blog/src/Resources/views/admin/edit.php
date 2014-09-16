<div class="container">
	<div class="row">
		<div class="clearfix col-md-9">
			<div class="form-group">
				    <input name="title" type="text" class="form-control input-lg" id="post-title" placeholder="Title" value="<?php echo value('title', $title); ?>">
				 </div>
			 <div class="form-group">
				<?php echo wysiwyg('content', value('content', $content)); ?>
			 </div>
		</div>
		<div class="clearfix col-md-3">
		    <div class="form-group" >
		    	<input name="slug" type="text" class="form-control" placeholder="Slug" value="<?php echo value('slug', $slug); ?>">
		 	</div>
			<div class="form-group tags-input-wrapper">
				<label>Tags</label>
			    <input placeholder="Tags" id="tags" name="tags"/>
			 </div>
		</div>
	</div>

</div>
<script>
var tagOptions = <?php echo $tagOptions; ?>;
var tags = <?php echo json_encode(value('tags', $tags)); ?>
</script>