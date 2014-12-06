<?php $this->extend('@system/main'); ?>

<div class="container">
	<div class="row row-content">

		<div class="col-md-2">
			<ul class="nav nav-pils nav-stacked">
				<li><a href="<?php echo admin_url('setting/themes')?>">Theme</a></li>
				<li><a href="<?php echo admin_url('setting/themes/widget')?>">Widget</a></li>
				<li><a href="<?php echo admin_url('setting/themes/menus')?>">Menus</a></li>
			</ul>
		</div>
		
		<div class="col-md-10">
			<?php echo $this->block('content'); ?>
		</div>
	</div>
</div>