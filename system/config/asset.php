<?php

return [

/**
 *--------------------------------------------------------------------------
 * Asset Path
 *--------------------------------------------------------------------------
 *
 * Asset path, used by assetic as parameter for AssetFactory. Yes, Partitur
 * by default provides Assetic service for asset manager. You may or may not
 * use the assetic, it only being loaded if you do.
 */

'path' => __DIR__.'/../plugins',

/**
 *--------------------------------------------------------------------------
 * The Assets
 *--------------------------------------------------------------------------
 *
 * These assets will be registered to assetic asset manager.
 */
'assets' => [

		'bootstrapcss' => 'bootstrap/css/bootstrap.css',
		'bootstrapjs' => 'bootstrap/js/bootstrap.js',
		'toastrcss' => 'toastr/toastr.css',
		'fontawesome' => 'fontawesome/css/font-awesome.css',
		'toastrjs' => 'toastr/toastr.min.js',
		'jquery' => 'jquery/dist/jquery.min.js',
		'jquery_form' => 'jquery-form/jquery.form.js',
		'bootstrap_datatables_css' => 'bootstrap-datatables/bootstrap-datatables.css',
		'bootstrap_datatables_js' => 'bootstrap-datatables/bootstrap-datatables.js',
		'datatables_js' => 'datatables/media/js/jquery.dataTables.js',
		'jquery_check_all' => 'jquery-check-all/jquery-check-all.js',
		'chosen_bootstrap_css' => 'chosen-bootstrap/chosen-bootstrap.css',
		'chosen_css' => 'chosen/chosen.css',
		'chosen_js' => 'chosen/chosen.jquery.js',
		'magicsuggest_css' => 'magicsuggest/magicsuggest.css',
		'magicsuggest_js' => 'magicsuggest/magicsuggest.js',
		'bootstrap_contextmenu' => 'bootstrap-contextmenu/bootstrap-contextmenu.js',
		'jquery_ui_css' => 'jquery-ui/css/bootstrap-lite/jquery-ui-1.10.4.custom.min.css',
		'jquery_ui_js' => 'jquery-ui/js/jquery-ui-1.10.4.custom.min.js',
		'sticky_js' => 'sticky/jquery.sticky.js'
]

];