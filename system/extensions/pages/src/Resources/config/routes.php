<?php return [
	
	'%admin%' =>[
		'subRoutes' => [
			'pages' => [
				'subRoutes' => [
					'index' => ['controller' => '@pages\Admin::index'],
					'create' => ['controller' => '@pages\Admin::create'],
					'edit/{id}' => ['controller' => '@pages\Admin::edit'],
					'data/{status}.json' => ['controller' => '@pages\Admin::filter'],
					'save' => ['controller' => '@pages\Admin::save', 'csrf' => true]
				]
			]
		]
	],
	
	'/{slug}' => [
		'controller' => '@pages\Pages::view',
		'requirements' => [

			// @todo
			// @prototype  'slug' => "^(?!(?:backend|blog)(?:/|$)).*$"
			'slug' => "^(?!(?:"."%admin%"."|blog|)(?:/|$)).*$"
		]
	],
];