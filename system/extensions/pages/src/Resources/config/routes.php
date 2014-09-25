<?php return [
	
	'%admin%' =>[
		'group' => [
			'pages' => ['controller' => '@pages\Admin::index'],
			'pages/create' => ['controller' => '@pages\Admin::create'],
			'pages/edit/{id}' => ['controller' => '@pages\Admin::edit'],
		]
	],
	
	'/{slug}' => [
		'controller' => '@pages\Pages::view',
		'requirements' => [
			//'slug' => "^(?!(?:backend|blog)(?:/|$)).*$"
			'slug' => "^(?!(?:"."%admin%"."|blog|)(?:/|$)).*$"
		]
	],
];