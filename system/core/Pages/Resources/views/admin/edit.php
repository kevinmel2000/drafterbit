<div class="container">
	<div class="row row-content">
		<div class="clearfix col-md-12 content-full">

		    <div class="content-inner tab-content">
					<div class="form-group">
					    <input name="title" type="text" class="form-control input-lg" id="post-title" placeholder="Title" value="<?php echo value('title', $title); ?>">
					 </div>
		    	<div class="tab-pane active" id="contents">
					 <div class="form-group">
						<?php echo wysiwyg('content', value('content', $content)); ?>
					 </div>
				 </div>
		    	<div class="tab-pane" id="options">
				    <div class="form-group">
				    	<input name="slug" type="text" class="form-control" placeholder="Slug" value="<?php echo value('slug', $slug); ?>">
				 	</div>
				 	<div  class="form-group">
				    	<select name="template" type="text" class="post-setting-input form-control">
				    		<option value="default" <?php echo selected('template', 'draft',($template == 'draft') ) ?>>Default</option>
				    		<option value="blank" <?php echo selected('template', 'blank',($template == 'blank') ) ?>>Blank</option>
				    	</select>
				 	</div>

		    	</div>

			</div>
			
			<div class="sidebar-options">
				<ul class="nav nav-pills nav-stacked" role="tablist">
				  <li class="active"><a href="#contents" role="tab" data-toggle="tab"><i class="fa fa-edit"></i></a></li>
				  <li><a href="#options" role="tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
				</ul>
			</div>
		</div>
	</div>

</div>