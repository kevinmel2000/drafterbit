<?php $this->extend('@system/main'); ?>

<div class="container">
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Recent Activities</h3>
				</div>
				<div class="panel-body">
					<?php if($logs): ?>
					<table width="100%" class="table table-condensed">
					<thead>
						<tr>
							<th>Time</th>
							<th>Activity</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($logs as $log): ?>
						<tr>
							<td width="40%;"><?php echo date('d/m/Y H:i', $log->time); ?></td>
							<td><?php echo $log->formattedMsg; ?></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
					</table>
					<?php else: ?>
						<p><?php echo __('No recent activity') ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Site Info</h3>
				</div>
				<div class="panel-body">
					<ul class="list-group">
						<li class="list-group-item"><i class="fa fa-desktop"></i> OS <?php echo $os; ?></li>
						<li class="list-group-item"><i class="fa fa-gears"></i> PHP <?php echo phpversion(); ?></li>
						<li class="list-group-item"><i class="fa fa-database"></i> <?php echo $db; ?></li>
						<li class="list-group-item"><i class="fa fa-users"></i> User(s) : <?php echo $usersCount ?></li>
						<li class="list-group-item"><i class="fa fa-clock-o"></i> Time <?php echo date('H:i:s'); ?></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>