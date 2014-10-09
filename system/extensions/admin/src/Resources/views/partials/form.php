<?php echo $header; ?>
<form id="<?php echo $id ?>-form" method="POST" action="<?php echo $action; ?>">
    <?php echo $toolbars ?>
    <?php echo $view ?>
<!-- /.container-fluid -->
	<input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>"/>
</form>