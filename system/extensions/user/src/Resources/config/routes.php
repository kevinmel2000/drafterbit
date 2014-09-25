<?php return [
	'%admin%' =>[
		'group' => [
			'user' => [
				'group' => [
					'index' => ['controller' => '@user\Admin::index'],
					'create' => ['controller' => '@user\Admin::create'],
					'edit/{id}' => ['controller' => '@user\Admin::edit'],
		
					'group' => ['controller' => '@user\Admin\Group::index'],
					'group/create' => ['controller' => '@user\Admin\Group::create'],
					'group/edit/{id}' => ['controller' => '@user\Admin\Group::edit']
				]
			],
		],
	],
];