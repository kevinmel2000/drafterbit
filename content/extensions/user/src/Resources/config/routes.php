<?php return [
	'%admin%' =>[
		'subRoutes' => [
			'user' => [
				'subRoutes' => [
					'index' => ['controller' => '@user\User::index'],
					'create' => ['controller' => '@user\User::create'],
					'edit/{id}' => ['controller' => '@user\User::edit'],
		
					'group' => ['controller' => '@user\Group::index'],
					'group/create' => ['controller' => '@user\Group::create'],
					'group/edit/{id}' => ['controller' => '@user\Group::edit']
				]
			],
		],
	],
];