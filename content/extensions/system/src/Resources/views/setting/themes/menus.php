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
							<a href="#" class="" data-position="<?php echo $position ?>" data-toggle="modal" data-target="#menu-item-add">
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

<div class="modal fade" id="menu-item-add">
  <div class="modal-dialog">
    <div class="modal-content">
      	<form action="<?php echo admin_url('setting/themes/menus/save'); ?>" method="post" class="ajax-form">
   			<div class="modal-body">
   				<div class="form-group">
   					<label>Label</label>
   					<input class="form-control" name="label" placeholder="Label">
   				</div>
   				 <div class="form-group">
   					<label>Type</label>
   					<div class="radio">
   						<label><input type="radio" name="type" value="page">Page</label>
   						<label><input type="radio" name="type" value="link">Link</label>
   					</div>
   				</div>
   				 <div class="form-group type-section type-section-link">
   				 	<label>Link</label>
   					<input class="form-control" name="link" placeholder="Link">
   				</div>
   				<div class="form-group type-section type-section-page">
   				 	<label>Page</label>
   					<select name"page" class="form-control">
   						<?php foreach ($frontPageOptions as $id => $label): ?>
							<option value="<?php echo $id ?>"><?php echo $label; ?>
   						<?php endforeach ?>
   					</select>
   				</div>
   				<div>
   					<button class="btn btn-primary" type="submit" name="">submit</button>
   				<div>
   			</div>
      	</form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

	<?php endforeach; ?>
<?php else: ?>
	<p>Current theme doesn't support menu position</p>
<?php endif; ?>

<?php $this->js(':jquery_form, @system/js/menus.js'); ?>