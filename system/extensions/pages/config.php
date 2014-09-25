<?php return [

	'name' => 'pages',
	'menus' => [
			[ 'id'=>'pages', 'parent' =>'content', 'label' => 'Pages', 'href' => 'pages'],
	],
	'permissions' => [
		'page.view' => 'view page',
		'page.add' => 'add page',
		'page.edit' => 'edit page',
		'page.delete' => 'delete page',
	]
];