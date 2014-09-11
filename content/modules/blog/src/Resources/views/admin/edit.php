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
					<div class="form-group tags-input-wrapper">
					    <input id="tags" name="tags"/>
					 </div>
				 </div>
		    	<div class="tab-pane" id="options">
				    <div>
				    	<input name="slug" type="text" class="form-control" placeholder="Slug" value="<?php echo value('slug', $slug); ?>">
				 	</div>

		    	</div>
		    	<div class="tab-pane" id="images">
		    		Featured images:
		    	</div>

			</div>
			
			<div class="sidebar-options">
				<ul class="nav nav-pills nav-stacked" role="tablist">
				  <li class="active"><a href="#contents" role="tab" data-toggle="tab"><i class="fa fa-edit"></i></a></li>
				  <li><a href="#images" role="tab" data-toggle="tab"><i class="fa fa-image"></i></a></li>
				  <li><a href="#options" role="tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
				</ul>
			</div>
		</div>
	</div>

</div>
<script>
var tagOptions = <?php echo $tagOptions; ?>;
var tags = <?php echo json_encode(value('tags', $tags)); ?>
</script>