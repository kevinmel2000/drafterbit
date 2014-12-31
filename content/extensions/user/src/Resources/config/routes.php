<?php return [
	'%admin%' =>[
		'subRoutes' => [
			'user' => [
				'subRoutes' => [
					'/'                  => ['controller' => '@user\User::index',       'access' => 'user.view'],
					'edit/{id}'          => ['controller' => '@user\User::edit',        'access' => 'user.view'],
					'index-action'       => ['controller' => '@user\User::indexAction', 'access' => 'user.delete', 'csrf' => true],
					'save'               => ['controller' => '@user\User::save', 		'access' => 'user.edit',  'csrf' => true],
					'data/{status}.json' => ['controller' => '@user\User::filter',		'access' => 'user.view'],
		
					'roles'               => ['controller' => '@user\Roles::index',  'access' => 'roles.view'],
					'roles/data/all.json' => ['controller' => '@user\Roles::filter', 'access' => 'roles.view'],
					'roles/edit/{id}'     => ['controller' => '@user\Roles::edit',   'access' => 'user.view'],
					'roles/save'          => ['controller' => '@user\Roles::save',        'access' => 'user.edit',    'csrf' => true],
					'roles/index-action'  => ['controller' => '@user\Roles::indexAction', 'access' => 'roles.delete', 'csrf' => true],
				]
			],
		],
	],


	//  @todo, crate option for user url
	'author/{username}' => [
		'controller' => '@user\Frontend::view'
	]
];