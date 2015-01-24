<?php $this->extend('@system/main-index'); ?>

<?php $this->start('action'); ?>
	<?php if(has_permission('log.delete')): ?>
		<button class="btn btn-default btn-sm uncreate" type="submit" name="action" value="delete">
		    Delete
		</button>
		<button class="btn btn-default btn-sm uncreate" type="submit" name="action" value="clear">
		    Clear
		</button>
	<?php endif; ?>
<?php $this->end(); ?>

<?php echo $logTable; ?>

<?php $this->js('@system/js/log.js'); 