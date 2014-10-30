<?php $this->extend('@system/main-index'); ?>

<?php $this->css('@blog/css/comment/index.css'); ?>

<?php $this->start('filter'); ?>
<div style="display:inline-block; float:right;margin-left:5px;">
    <select name="filter" class="form-control input-sm comments-status-filter">
            <option value="active" <?php echo selected('status-filter', 'active', $status == 'active' ); ?> >- Status -</option>
            <option value="approved" <?php echo selected('status-filter', 'approved', $status == 'approved'); ?> >Approved</option>
            <option value="pending" <?php echo selected('status-filter', 'pending', $status == 'pending'); ?> >Pending</option>
            <option value="spam" <?php echo selected('status-filter', 'spam', $status == 'spam'); ?> >Spam</option>
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