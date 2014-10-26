<?php $this->extend('@system/main'); ?>

<div class="container">
	<div class="row">
		<div class="col-md-2">
			<ul class="nav nav-pills nav-stacked">
			  <li><a href="<?php echo admin_url('blog/create') ?>"><i class="fa fa-edit"></i> New Post</a></li>
			  <li><a href="<?php echo admin_url('pages/create') ?>"><i class="fa fa-laptop"></i> New Page</a></li>
			  <li><a href="<?php echo admin_url('files/index') ?>"><i class="fa fa-upload"></i> Upload</a></li>
			  <li><a href="<?php echo admin_url('user/index') ?>"><i class="fa fa-users"></i> Manage Users</a></li>
			</ul>
		</div>
		<div class="col-md-5">
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
		<div class="col-md-5">
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