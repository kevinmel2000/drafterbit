<?php $this->extend('@system/main-edit'); ?>

<?php $this->css(':magicsuggest_css, @blog/css/editor.css'); ?>

<?php $this->start('action'); ?>
<button class="btn btn-sm btn-success" type="submit" name="action" value="save">
	<i class="fa fa-check"></i> Save
</button>
<a class="btn btn-sm btn-default" href="<?php echo admin_url('blog') ?>">
	<i class="fa fa-times" style="color: #A94442;"></i> Cancel
</a>
<?php $this->end(); ?>

<div class="row">
	<div class="clearfix col-md-9">
		<div class="form-group">
			    <input name="title" type="text" class="form-control input-lg" id="post-title" placeholder="Title" value="<?php echo value('title', $postTitle); ?>">
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

<script>
var tagOptions = <?php echo $tagOptions; ?>;
var tags = <?php echo json_encode(value('tags', $tags)); ?>
</script>

<?php $this->js(':magicsuggest_js, @blog/js/editor.js'); ?>