<div class="container-fluid">
	<div class="row row-content">

		<div class="col-md-2">
			<ul class="nav nav-pils nav-stacked">
				<li><a href="<?php echo admin_url('setting/themes')?>">Theme</a></li>
				<li><a href="<?php echo admin_url('setting/themes/widget')?>">Widget</a></li>
			</ul>
		</div>
		<div class="col-md-10">
			<?php foreach ($positions as $position): ?>
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<span><?php echo $position ?></span>
						</div>
						<div class="panel-body position-<?php echo $position ?>">

							<div class="panel panel-default">
								<a href="#" data-position="<?php echo $position ?>" data-toggle="modal" data-target=".widgets">Add Widget</i></a>
							</div>

							<?php foreach($widgets[$position] as $widget): ?>
							<div class="panel panel-default widget">
								<div class="panel-heading">
									<span><?php echo $widget->name; ?></span>
								</div>
								<div class="panel-body">
									<?php echo $widgetUIs[$widget->name];  ?>
								</div>
							</div>
							<?php endforeach;?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
<div class="modal fade widgets">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Select Widget</h4>
      </div>
      <div class="modal-body">
       	<ul>
       		<?php foreach($widg as $name => $widget): ?>
       		<li><a href="#" data-widget="<?php echo $name; ?>" class="widget-item"><?php echo $name; ?></a></li>
       		<?php endforeach; ?>
       	</ul>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
