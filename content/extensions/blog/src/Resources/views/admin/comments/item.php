<p><?php echo $content ?></p>

<?php if ($deletedAt == '0000-00-00 00:00:00'): ?>
    <div class="comment-action">
        <?php if ($status != 2): ?>
        <a data-status="0" data-id="<?php echo $itemId ?>" style="display:<?php echo $display ?>" class="unapprove status" href="#">Pending</a>
        <a data-status="1" data-id="<?php echo $itemId ?>" style="display:<?php echo $display2 ?>" class="approve status" href="#">Approve</a>
        <a data-id="<?php echo $itemId ?>" data-post-id="<?php echo $postId ?>" class="reply" href="#">Reply</a>
        <a data-status="2" data-id="<?php echo $itemId ?>" class="spam status" href="#">Spam</a>
        <a data-id="<?php echo $itemId ?>" class="trash" href="#">Trash</a>
        <?php else: ?>
            <a data-status="0" data-id="<?php echo $itemId ?>" class="unspam status" href="#">Not Spam</a>
        <?php endif; ?>
    </div>
<?php endif; ?>