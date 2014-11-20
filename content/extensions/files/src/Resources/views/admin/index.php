<?php $this->extend('@system/main'); ?>

<?php $this->css(':notify_css, @files/css/openfinder.css'); ?>

<div class="container">
	<div id="finder-container"></div>
</div>

<?php $this->js(':bootstrap_contextmenu, :jquery_form, :notify_js, @files/js/openfinder.js, @files/js/index.js'); ?>