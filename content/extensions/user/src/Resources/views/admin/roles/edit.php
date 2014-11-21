<?php $this->extend('@system/main-edit'); ?>

<?php $this->start('action'); ?>
<button class="btn btn-success btn-sm" type="submit" name="action" value="update">
    <i class="fa fa-check"></i> Save
</button>
<a href="<?php echo admin_url('user/roles'); ?>" class="btn btn-default btn-sm">
    <i class="fa fa-times" style=""></i> Cancel
</a>
<?php $this->end(); ?>

<style>
	.permission {
		display:inline-block;
		width:auto !important;
		margin-left:20px;
	}
</style>

<div class="row row-content">
	<div class="col-md-6 content-full">
		<div class="form-group">
    		<label for="name" class="control-label">Group Name</label>
     		<input name="name" type="text" class="form-control" placeholder="Group Name" value="<?php echo value('name', $roleName); ?>">
		</div>
		<div class="form-group">
    		<label for="description" class="control-label">Description</label>
     		<textarea name="description" type="text" class="form-control" ><?php echo value('description', $description); ?></textarea>
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

<input type="hidden" name="id" value="<?php echo $roleId; ?>">

<?php $this->js('@user/js/roles/edit.js'); ?>
