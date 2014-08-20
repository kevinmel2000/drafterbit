<?php return [
	
	'post' => [
		'path' => '/blog/{yyyy}/{mm}/{slug}',
		'controller' => 'Blog::view@blog',
		'methods' => 'get',
		'requirements' => [
			'yyyy' => '\d{4}',
			'mm' => '\d{2}'
			]
	]

];