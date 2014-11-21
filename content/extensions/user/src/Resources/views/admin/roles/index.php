<?php $this->extend('@system/main-index'); ?>


<?php $this->start('action'); ?>
<a href="<?php echo admin_url('user/roles/edit/new'); ?>" class="btn btn-success btn-sm">
    <i class="fa fa-plus" style=""></i> <?php echo __('New Role') ?>
</a>
<button class="btn btn-default btn-sm uncreate-action" type="submit" name="action" value="delete">
    <i class="fa fa-trash-o"></i> <?php echo __('Delete'); ?>
</button>
<?php $this->end(); ?>

<?php echo $rolesTable; ?>

<?php $this->js('@user/js/roles/index.js'); ?>