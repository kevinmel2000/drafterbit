<?php return [
	
	'%admin%' =>[
		'subRoutes' => [
			'pages' => [
				'subRoutes' => [
					'index' => ['controller' => '@pages\Backend::index'],
					'create' => ['controller' => '@pages\Backend::create'],
					'edit/{id}' => ['controller' => '@pages\Backend::edit'],
					'data/{status}.json' => ['controller' => '@pages\Backend::filter'],
					'save' => ['controller' => '@pages\Backend::save', 'csrf' => true]
				]
			]
		]
	]
];