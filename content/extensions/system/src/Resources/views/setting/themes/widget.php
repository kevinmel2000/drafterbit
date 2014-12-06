<?php $this->extend('@system/base-theme'); ?>

<?php $this->css(':jquery_ui_css'); ?>

	<?php foreach ($positions as $position): ?>
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title"><?php echo $position ?></h4>
				</div>
				<div class="panel-body position-<?php echo $position ?>">
					<ul style="list-style:none;padding-left:0;">
						<?php foreach($widgets[$position] as $widget): ?>
						<li>
							<div class="panel panel-default">
							<div class="panel-body">
								<a href="#" class="registered-widget" data-id="<?php echo $widget->id; ?>" class="widget-item"><?php echo $widget->name; ?></a>
							</div>
							</div>
						</li>
						<?php endforeach;?>
						<li>
							<div class="panel panel-default">
							<div class="panel-body">
								<a href="#" class="" data-position="<?php echo $position ?>" data-toggle="modal" data-target=".widgets">
									<i class="fa fa-plus"></i> Add Widget
								</a>
							</div>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	<?php endforeach; ?>

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