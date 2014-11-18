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
		
					'group' => ['controller' => '@user\Group::index'],
					'group/create' => ['controller' => '@user\Group::create'],
					'group/edit/{id}' => ['controller' => '@user\Group::edit']
				]
			],
		],
	],
];