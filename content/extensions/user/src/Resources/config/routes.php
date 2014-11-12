<?php return [
	'%admin%' =>[
		'subRoutes' => [
			'user' => [
				'subRoutes' => [
					'index' => ['controller' => '@user\User::index'],
					'edit/{id}' => ['controller' => '@user\User::edit'],
					'save' => ['controller' => '@user\User::save', 'csrf' => true],
		
					'group' => ['controller' => '@user\Group::index'],
					'group/create' => ['controller' => '@user\Group::create'],
					'group/edit/{id}' => ['controller' => '@user\Group::edit']
				]
			],
		],
	],
];