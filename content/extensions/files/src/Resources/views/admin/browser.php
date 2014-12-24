<!DOCTYPE html>
<html>
<head>
	<title>Drafterbit File Browser</title>


	<?php $this->css(':bootstrap_css, :finder_css'); ?>
	<?php $this->css(':fontawesome', ':fontawesome'); ?>
	<?php echo  $this->block('css'); ?>

<style>
	.upload-btn {
		margin-left: 20px !important;
	}
</style>
</head>
<script>
drafTerbit = {
	baseUrl: "<?php echo base_url() ?>",
	adminUrl: "<?php echo admin_url() ?>"
}
</script>
<body>

<div id="finder-container"></div>

<?php $this->js(':jquery, :bootstrap_js, :bootstrap_contextmenu, :jquery_form, :finder_js, @files/js/browser.js'); ?>
<?php echo $this->block('js'); ?>
</body>
</html>