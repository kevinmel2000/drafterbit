<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Recent</h3>
    </div>
    <div class="panel-body">
        <?php if ($logs) : ?>
        <table width="100%" class="table table-condensed">
        <thead>
            <tr>
                <th>Time</th>
                <th>Activity</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($logs as $log) : ?>
            <tr>
                <td width="40%;"><?php echo date('d/m/Y H:i', $log->time); ?></td>
                <td><?php echo $log->formattedMsg; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        </table>
        <?php else : ?>
            <p><?php echo __('No recent activity') ?></p>
        <?php endif; ?>
        <div>
            <a href="<?php echo admin_url('system/log') ?>" class="btn btn-sm pull-right">View More</a>
        </div>
    </div>
</div>