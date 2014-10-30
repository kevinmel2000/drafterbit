<?php $this->extend('@system/main-index'); ?>

<?php $this->css('@blog/css/comment/index.css'); ?>

<?php $this->start('filter'); ?>
<div style="display:inline-block; float:right;margin-left:5px;">
    <select name="filter" class="form-control input-sm blog-status-filter">
            <option value="untrashed" <?php echo selected('status-filter', 'untrashed', $status == 'untrashed' ); ?> >- Status -</option>
            <option value="unpublished" <?php echo selected('status-filter', 'unpublished', $status == 'unpublished'); ?> >Aproved</option>
            <option value="published" <?php echo selected('status-filter', 'published', $status == 'published'); ?> >Pending</option>
            <option value="published" <?php echo selected('status-filter', 'published', $status == 'published'); ?> >Spam</option>
            <option value="trashed" <?php echo selected('status-filter', 'trashed', $status == 'trashed'); ?> >Trashed</option>
    </select>
</div>
<?php $this->end(); ?>

<?php $this->start('action'); ?>
<button class="btn btn-default btn-sm uncreate-action" type="submit" name="action" value="trash">
    <i class="fa fa-trash-o"></i> Trash
</button>
<?php $this->end(); ?>

<?php echo $commentsTable; ?>

<?php $this->js('@blog/js/comment/index.js'); ?>