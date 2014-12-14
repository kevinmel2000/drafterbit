<?php return [
	
	'%admin%' =>[
		'subRoutes' => [
			'pages' => [
				'subRoutes' => [
					'/' => ['controller' => '@pages\Pages::index'],
					'edit/{id}' => ['controller' => '@pages\Pages::edit'],
					'data/{status}.json' => ['controller' => '@pages\Pages::filter'],
					'save' => ['controller' => '@pages\Pages::save', 'csrf' => true],
					'trash' => ['controller' => '@pages\Pages::trash', 'csrf' => true]
				]
			]
		]
	]
];