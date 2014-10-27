<?php $this->extend('@system/main'); ?>

<?php $this->css('@files/css/openfinder.css'); ?>

<div class="container">
	<div id="finder-container"></div>
</div>

<?php $this->js(':bootstrap_contextmenu, :jquery_form, @files/js/openfinder.js, @files/js/index.js'); ?>