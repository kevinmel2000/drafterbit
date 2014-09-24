<?php

return [

/**
 *--------------------------------------------------------------------------
 * Asset Path
 *--------------------------------------------------------------------------
 *
 * Asset path, used by assetic as parameter for AssetFactory. Yes, Drafterbit\Framework
 * by default provides Assetic service for asset manager. You may or may not
 * use the assetic, it only being loaded if you do.
 */

'path' => 'system/vendor/web',

/**
 *--------------------------------------------------------------------------
 * The Assets
 *--------------------------------------------------------------------------
 *
 * These assets will be registered to assetic asset manager.
 */
'assets' => [

		'bootstrap_css' 		=> 'bootstrap/css/bootstrap.css',
		'bootstrap_js' 		=> 'bootstrap/js/bootstrap.js',
		'bootstrap_contextmenu' => 'bootstrap-contextmenu/bootstrap-contextmenu.js',
		'bootstrap_validator_css' => 'bootstrap-validator/css/bootstrapValidator.min.css',
		'bootstrap_validator_js' => 'bootstrap-validator/js/bootstrapValidator.min.js',
		
		'toastr_css' 		=> 'toastr/toastr.css',
		'toastr_js' 			=> 'toastr/toastr.min.js',
		'fontawesome'		=> 'fontawesome/css/font-awesome.css',
		'jquery' 			=> 'jquery/dist/jquery.min.js',
		'jquery_form' 		=> 'jquery-form/jquery.form.js',
		'bootstrap_datatables_css' 	=> 'bootstrap-datatables/bootstrap-datatables.css',
		'bootstrap_datatables_js' 	=> 'bootstrap-datatables/bootstrap-datatables.js',
		'datatables_js' 			=> 'datatables/media/js/jquery.dataTables.js',
		'jquery_check_all' 			=> 'jquery-check-all/jquery-check-all.js',
		'chosen_bootstrap_css' 		=> 'chosen-bootstrap/chosen-bootstrap.css',
		'chosen_css' 				=> 'chosen/chosen.css',
		'chosen_js' 				=> 'chosen/chosen.jquery.js',
		'magicsuggest_css' 		=> 'magicsuggest/magicsuggest.css',
		'magicsuggest_js' 		=> 'magicsuggest/magicsuggest.js',
		'jquery_ui_css' 		=> 'jquery-ui/css/bootstrap-lite/jquery-ui-1.10.4.custom.min.css',
		'jquery_ui_js' 			=> 'jquery-ui/js/jquery-ui-1.10.4.custom.min.js',
		'sticky_js' 			=> 'sticky/jquery.sticky.js'
]

];