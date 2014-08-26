<?php return [
	
	'page' => [
		'path' => '/{slug}',
		'controller' => 'Pages::view@pages',
		'requirements' => [
			//'slug' => "^(?!(?:backend|blog)(?:/|$)).*$"
			'slug' => "^(?!(?:".ADMIN_BASE.")(?:/|$)).*$"
		]
	]
];