<div class="container">
	<div class="row">
		<div class="col-md-8">
		<div class="form-group">
		    <input name="title" type="text" class="form-control" id="post-title" placeholder="Title" value="<?php echo value('title', $title); ?>">
		 </div>

    	<div id="contents">
			 <div class="form-group">
				<?php echo wysiwyg('content', value('content', $content)); ?>
			 </div>
		 </div>

		</div>
		<div class="col-md-4">
		    <div class="form-group">
		    	<label>slug</label>
		    	<input name="slug" type="text" class="form-control" placeholder="Slug" value="<?php echo value('slug', $slug); ?>">
		 	</div>
		 	<div class="form-group">
		    	<label>Layout</label>
		    	<select name="template" type="text" class="post-setting-input form-control">
		    		<option value="default" <?php echo selected('template', 'draft',($template == 'draft') ) ?>>Default</option>
		    		<option value="blank" <?php echo selected('template', 'blank',($template == 'blank') ) ?>>Blank</option>
		    	</select>
		 	</div>
		</div>
	</div>

</div>