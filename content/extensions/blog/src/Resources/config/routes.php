<?php return [

'%admin%' => [
	'subRoutes' => [
		'blog' => ['controller' => '@blog\Blog::index'],
		'blog/create' => ['controller' => '@blog\Blog::create'],
		'blog/edit/{id}' => ['controller' => '@blog\Blog::edit'],
		'blog/comments' => ['controller' => '@blog\Comment::index'],

	]
],


'blog' => [
	'controller' => '@blog\Frontend::index'
],
	
'blog/{yyyy}/{mm}/{slug}' => [
	'controller' => '@blog\Frontend::view',
	'methods' => 'get',
	'requirements' => [
		'yyyy' => '\d{4}',
		'mm' => '\d{2}'
		]
	],

'blog/comment/submit' => ['controller' => '@blog\Comment::submit', 'methods' => 'post'],

];