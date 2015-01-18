<div class="">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo __('Recent Comments'); ?></h3>
        </div>
        <div class="panel-body">
         <?php if($comments): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th width="25%">Author</th>
                        <th>Comment</th>
                        <th width="20%">On</th>
                    </tr>
                </thead>
                <?php foreach ($comments as $comment): ?>
                    <tr role="row">
                        <td>
                            <img src="<?php echo gravatar_url($comment['email'], 40); ?>">
                            <?php echo $comment['name'] ?><br/>
                        </td>
                        <td><?php echo $comment['content']; ?></td>
                        <td><a href="<?php echo admin_url('blog/edit'.$comment['post_id']) ?>"><?php echo $comment['title'] ?></a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
         <?php else: ?>
            <?php echo __('No Recent Comment'); ?>.
         <?php endif ?>
        </div>
    </div>
</div>
