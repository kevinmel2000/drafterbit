<?php return [
	'%admin%' =>[
		'group' => [
			'finder/browser' => ['controller' => '@finder\Admin::browser'],
			'finder/data' => ['controller' => '@finder\Admin::data']
		]
	]
];