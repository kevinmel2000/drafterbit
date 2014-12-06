<?php $this->extend('@system/base-theme'); ?>

<?php if($positions): ?>
	<?php foreach ($positions as $position): ?>
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title"><?php echo $position ?></h4>
			</div>
			<div class="panel-body position-<?php echo $position ?>">

				<?php if($menus): ?>
					<ul style="list-style:none;padding-left:0;">
					<?php foreach ($menus[$position] as $menu): ?>
						<li>
							<div class="panel panel-default">
							<div class="panel-body">
								<a href="#" data-id="<?php echo $menu->id; ?>"><?php echo $menu->label; ?></a>
							</div>
							</div>
						</li>
					<?php endforeach; ?>

					<li>
						<div class="panel panel-default">
						<div class="panel-body">
							<a href="#" class="" data-position="<?php echo $position ?>" data-toggle="modal" data-target=".widgets">
								<i class="fa fa-plus"></i> Add Menu Item
							</a>
						</div>
						</div>
					</li>

					</ul>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<?php endforeach; ?>
<?php else: ?>
	<p>Current theme doesnt support any menu position</p>
<?php endif; ?>