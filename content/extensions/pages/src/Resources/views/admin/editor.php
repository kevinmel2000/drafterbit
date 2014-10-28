<?php $this->extend('@system/main-edit'); ?>

<?php $this->css('@pages/css/editor.css'); ?>

<?php $this->start('action'); ?>
<button class="btn btn-sm btn-success" type="submit" name="action" value="save">
	<i class="fa fa-check spinner"></i> Save
</button>
<a class="btn btn-sm btn-default" href="<?php echo admin_url('pages/index') ?>">
	<i class="fa fa-times" style="color: #A94442;"></i> Cancel
</a>
<?php $this->end(); ?>

<div class="row">
	<div class="col-md-9">
		<div class="form-group">
		    <input required name="title" type="text" class="form-control input-lg" id="post-title" placeholder="Title" value="<?php echo value('title', $pageTitle); ?>">
		 </div>
		 <div class="form-group">
			<?php echo wysiwyg('content', value('content', $content)); ?>
		 </div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
	    	<input name="slug" type="text" class="form-control" placeholder="Slug" value="<?php echo value('slug', $slug); ?>">
	 	</div>
	    <div class="form-group">
	    	<label>Status</label>
	    	<select name="status" type="text" class="form-control" value="<?php echo value('slug', $slug); ?>">
	    		<option <?php echo selected('status', 1, $status == 1) ?> value="1" >Published</option>
	    		<option <?php echo selected('status', 0, $status == 0) ?> value="0" >Unpublished</option>
	    	</select>
	 	</div>
	 	<div class="form-group">
	    	<label>Layout</label>
	    	<?php echo input_select('layout', $layoutOptions, value('layout', $layout), 'class="form-control"'); ?>
	 	</div>
	</div>
</div>

<?php $this->js('@pages/js/editor.js'); ?>