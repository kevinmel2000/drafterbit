<?php return [
	ADMIN_BASE =>[
		'group' => [
			'finder/browser' => ['controller' => '@finder\Admin::browser'],
			'finder/data' => ['controller' => '@finder\Admin::data']
		]
	]
];