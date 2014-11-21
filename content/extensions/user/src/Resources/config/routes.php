<?php return [
	'%admin%' =>[
		'subRoutes' => [
			'user' => [
				'subRoutes' => [
					'index' => ['controller' => '@user\User::index'],
					'edit/{id}' => ['controller' => '@user\User::edit'],
					'index-action' => ['controller' => '@user\User::indexAction', 'csrf' => true],
					'save' => ['controller' => '@user\User::save', 'csrf' => true],
					'data/{status}.json' => ['controller' => '@user\User::filter'],
		
					'roles' => ['controller' => '@user\Roles::index'],
					'roles/index-action' => ['controller' => '@user\Roles::indexAction', 'csrf' => true],
					'roles/edit/{id}' => ['controller' => '@user\Roles::edit'],
					'roles/save' => ['controller' => '@user\Roles::save', 'csrf' => true]
				]
			],
		],
	],
];