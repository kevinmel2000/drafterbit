<?php return [
	
	'/{slug}' => [
		'controller' => '@pages\Pages::view',
		'requirements' => [
			//'slug' => "^(?!(?:backend|blog)(?:/|$)).*$"
			'slug' => "^(?!(?:".ADMIN_BASE.")(?:/|$)).*$"
		]
	]
];