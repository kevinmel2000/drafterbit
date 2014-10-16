<?php $this->extend('@system/main-index'); ?>

<?php $this->start('action'); ?>
<button class="btn btn-default btn-sm uncreate" type="submit" name="action" value="delete">
    Delete
</button>
<button class="btn btn-default btn-sm uncreate" type="submit" name="action" value="clear">
    Clear
</button>
<?php $this->end(); ?>

<?php echo $cacheTable; ?>

<?php $this->js('@system/js/cache.js'); ?>