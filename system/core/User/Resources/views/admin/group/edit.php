<style>
	.permission {
		display:inline-block;
		width:auto !important;
		margin-left:20px;
	}
</style>
<div class="container-fluid">
	<div class="row row-content">
		<div class="col-md-6 content-full">
			<div class="form-group">
	    		<label for="name" class="col-md-3 control-label">Group Name</label>
	    		<div class="col-md-9">
	     			<input name="name" type="text" class="form-control" placeholder="Group Name" value="<?php echo value('name', $groupName); ?>">
	    		</div>
			</div>
			<div class="form-group">
	    		<label for="description" class="col-md-3 control-label">Description</label>
	    		<div class="col-md-9">
	     			<textarea name="description" type="text" class="form-control" ><?php echo value('description', $description); ?></textarea>
	    		</div>
			</div>
		</div>

		<div class="col-md-6 content-full">
		<h3 style="">Permissions</h3>
		<?php foreach($permissions as $permission): ?>
			<div class="checkbox permission">
				<input <?php echo checked('permissions', $permission->id, in_array($permission->id, $permissionIds)); ?> type="checkbox" name="permissions[]" value="<?php echo $permission->id ?>" ><?php echo ucfirst($permission->label) ?>.
			</div>
		<?php endforeach;?>
		</div>
	</div>
</div>
