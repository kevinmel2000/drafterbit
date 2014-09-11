<?php return [

ADMIN_BASE => [
	'group' => [
		'blog' => ['controller' => '@blog\Admin::index'],
		'blog/create' => ['controller' => '@blog\Admin::create'],
		'blog/edit/{id}' => ['controller' => '@blog\Admin::edit']
	]
],

'blog' => [
	'controller' => '@blog\Blog::index'
],
	
'blog/{yyyy}/{mm}/{slug}' => [
	'controller' => '@blog\Blog::view',
	'methods' => 'get',
	'requirements' => [
		'yyyy' => '\d{4}',
		'mm' => '\d{2}'
		]
	]

];