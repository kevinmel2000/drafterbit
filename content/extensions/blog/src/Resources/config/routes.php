<?php return [

'%admin%' => [
	'subRoutes' => [
		'blog' => ['controller' => '@blog\Admin::index'],
		'blog/create' => ['controller' => '@blog\Admin::create'],
		'blog/edit/{id}' => ['controller' => '@blog\Admin::edit'],
		'blog/comments' => ['controller' => '@blog\Comment::index'],

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
	],

'blog/comment/submit' => ['controller' => '@blog\Comment::submit', 'methods' => 'post'],

];