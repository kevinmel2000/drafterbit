<?php return [
	
	'%admin%' =>[
		'group' => [
			'pages/index/{status}' => ['controller' => '@pages\Admin::index', 'defaults' => ['status' => 'untrashed']],
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