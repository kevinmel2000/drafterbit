<?php return [
	
	'%admin%' =>[
		'group' => [
			'pages/index' => ['controller' => '@pages\Admin::index'],
			'pages/create' => ['controller' => '@pages\Admin::create'],
			'pages/edit/{id}' => ['controller' => '@pages\Admin::edit'],
			'pages/data/{status}.json' => ['controller' => '@pages\Admin::json'],
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