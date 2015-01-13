<?php $this->extend('@system/main'); ?>

<div class="container">
    <div class="row">
        <?php foreach ($dashboardWidgets as $widget): ?>
            <?php echo $widget; ?>
        <?php endforeach; ?>
    </div>
</div>