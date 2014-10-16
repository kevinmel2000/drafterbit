<?php $this->extend('@system/main'); ?>

<?php $this->css(':jquery_ui_css'); ?>

<div class="container">
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
					<h4><?php echo $position ?>
						<a href="#" class="pull-right" data-position="<?php echo $position ?>" data-toggle="modal" data-target=".widgets">
							<small><i class="fa fa-plus"></i>Add Widget</small>
						</a>
					</h4>

					<div class="panel panel-default">
						<div class="panel-body position-<?php echo $position ?>">
							<?php if($widgets[$position]): ?>
							<ul>
								<?php foreach($widgets[$position] as $widget): ?>
								<li><a href="#" class="registered-widget" data-id="<?php echo $widget->id; ?>" class="widget-item"><?php echo $widget->name; ?></a></li>
								<?php endforeach;?>
							</ul>
							<?php endif; ?>
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
        <h4 class="modal-title">Available Widget</h4>
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

<div class="modal fade widget-form-container">
  <div class="modal-dialog">
    <div class="modal-content">
      //..
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php $this->js(':jquery_form, @system/js/widget.js'); ?>