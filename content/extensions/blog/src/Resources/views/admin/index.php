<?php $this->extend('@system/main-index'); ?>

<?php $this->start('filter'); ?>
<div style="display:inline-block; float:right;margin-left:5px;">
    <select name="filter" class="form-control input-sm blog-status-filter">
            <option value="untrashed" <?php echo selected('status-filter', 'untrashed', $status == 'untrashed' ); ?> >- Status -</option>
            <option value="unpublished" <?php echo selected('status-filter', 'unpublished', $status == 'unpublished'); ?> >Unpublished</option>
            <option value="published" <?php echo selected('status-filter', 'published', $status == 'published'); ?> >Published</option>
            <option value="trashed" <?php echo selected('status-filter', 'trashed', $status == 'trashed'); ?> >Trashed</option>
    </select>
</div>
<?php $this->end(); ?>

<?php $this->start('action'); ?>
<a href="<?php echo admin_url('blog/create') ?>" class="btn btn-success btn-sm">
    <i class="fa fa-plus" style=""></i> New Post
</a>

<button class="btn btn-default btn-sm uncreate-action" type="submit" name="action" value="trash">
    <i class="fa fa-trash-o"></i> Trash
</button>
<?php $this->end(); ?>

<?php echo $blogTable; ?>

<?php $this->js('@blog/js/index.js'); ?>