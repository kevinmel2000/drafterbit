<?php

return [

/**
 *--------------------------------------------------------------------------
 * The Assets
 *--------------------------------------------------------------------------
 *
 * These assets will be registered to assetic asset manager.
 */
'assets' => [

		'bootstrap_css' 			=> '%path.vendor.asset%/bootstrap/dist/css/bootstrap.css',
		'bootstrap_js' 				=> '%path.vendor.asset%/bootstrap/dist/js/bootstrap.js',
		'bootstrap_contextmenu' 	=> '%path.vendor.asset%/bootstrap-contextmenu/bootstrap-contextmenu.js',
		'bootstrap_validator_css' 	=> '%path.vendor.asset%/bootstrapvalidator/dist/css/bootstrapValidator.min.css',
		'bootstrap_validator_js' 	=> '%path.vendor.asset%/bootstrapvalidator/dist/js/bootstrapValidator.min.js',
		
		'fontawesome'				=> '%path.vendor.asset%/fontawesome/css/font-awesome.css',
		'jquery' 					=> '%path.vendor.asset%/jquery/dist/jquery.min.js',
		'jquery_form' 				=> '%path.vendor.asset%/jquery-form/jquery.form.js',
		
		'bootstrap_datatables_css' 	=> '%path.system.asset%/bootstrap-datatables/bootstrap-datatables.css',
		'bootstrap_datatables_js' 	=> '%path.system.asset%/bootstrap-datatables/bootstrap-datatables.js',
		
		'notify_css' 				=> '%path.system.asset%/notify/notify.css',
		'notify_js' 				=> '%path.system.asset%/notify/notify.js',
		'jquery_check_all' 			=> '%path.system.asset%/jquery-check-all/jquery-check-all.js',

		'chosen_bootstrap_css' 		=> '%path.system.asset%/chosen-bootstrap/chosen-bootstrap.css',
		
		'datatables_js' 			=> '%path.vendor.asset%/datatables/media/js/jquery.dataTables.js',
		'chosen_css' 				=> '%path.vendor.asset%/chosen/chosen.css',
		'chosen_js' 				=> '%path.vendor.asset%/chosen/chosen.jquery.js',
		'magicsuggest_css' 			=> '%path.vendor.asset%/magicsuggest/magicsuggest.css',
		'magicsuggest_js' 			=> '%path.vendor.asset%/magicsuggest/magicsuggest.js',
		'jquery_ui_css' 			=> '%path.vendor.asset%/jquery-ui/css/bootstrap-lite/jquery-ui-1.10.4.custom.min.css',
		'jquery_ui_js' 				=> '%path.vendor.asset%/jquery-ui/js/jquery-ui-1.10.4.custom.min.js',
		'sticky_js' 				=> '%path.vendor.asset%/sticky/jquery.sticky.js'
]

];