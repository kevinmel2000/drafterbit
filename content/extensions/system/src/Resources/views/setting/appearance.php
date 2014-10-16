<?php $this->extend('@system/main'); ?>

<div class="container">
	<div class="row row-content">

		<div class="col-md-2">
			<ul class="nav nav-pils nav-stacked">
				<li><a href="<?php echo admin_url('setting/themes')?>">Theme</a></li>
				<li><a href="<?php echo admin_url('setting/themes/widget')?>">Widget</a></li>
			</ul>
		</div>
		<div class="col-md-10">
		<?php foreach ($themes as $theme => $prop): ?>
			<div class="col-md-4">
				<div class="well well-sm clearfix">
				<div style="height:160px;overflow:hidden;"><img width="100%;" src="<?php echo base_url(app('dir.content').'/themes/'.$prop['id'].'/screenshot.png') ?>"></div>
					<h4><?php echo $prop['name'] ?></h4>

					<?php  if($currentTheme == $prop['id']): ?>
					<a disabled type="submit" class="btn btn-xs btn-default pull-right">Active</a>
					<?php else:?>
					<form method="POST">
					<input type="hidden" name="theme" value="<?php echo $prop['id']; ?>">
					<input type="submit" value="Activate" class="btn btn-xs btn-success pull-right">
					</form>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
		</div>
	</div>
</div>