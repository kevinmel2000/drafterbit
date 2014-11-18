<?php $this->extend('@system/main-index'); ?>

<?php $this->start('filter'); ?>
<div style="display:inline-block; float:right;margin-left:5px;">
    <select name="filter" class="form-control input-sm users-status-filter">
        <option value="all" selected >- Status -</option>
        <option value="active"  >Active</option>
        <option value="banned" >Banned</option>
    </select>
</div>
<?php $this->end(); ?>

<?php $this->start('action'); ?>
<a href="<?php echo admin_url('user/edit/new'); ?>" class="btn btn-success btn-sm">
    <i class="fa fa-plus" style=""></i> New User
</a>
<button class="btn btn-default btn-sm uncreate-action" type="submit" name="action" value="delete">
    <i class="fa fa-trash-o"></i> Delete
</button>
<?php $this->end(); ?>

<?php echo $usersTable; ?>

<?php $this->js('@user/js/index.js'); ?>