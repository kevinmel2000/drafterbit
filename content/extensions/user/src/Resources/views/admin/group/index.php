<?php $this->extend('@system/main-index'); ?>


<?php $this->start('action'); ?>
<a href="<?php echo admin_url('user/group/create'); ?>" class="btn btn-success btn-sm">
    <i class="fa fa-plus" style=""></i> New Group
</a>
<button class="btn btn-default btn-sm uncreate-action" type="submit" name="action" value="trash">
    <i class="fa fa-trash-o"></i> Trash
</button>
<?php $this->end(); ?>

<?php echo $groupTable; ?>

<?php $this->js('@user/js/group/index.js'); ?>