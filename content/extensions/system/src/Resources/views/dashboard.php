<?php $this->extend('@system/main'); ?>

<div class="container" id="panel-container">
	<div class="row">
	    <div class="panel-item col-md-6">
        <?php foreach ($left as $widget): ?>
            <?php echo $widget; ?>
        <?php 
endforeach; ?>
	    </div>
	    <div class="panel-item col-md-6">
        <?php foreach ($right as $widget): ?>
            <?php echo $widget; ?>
        <?php 
endforeach; ?>
	    </div>
	</div>
</div>