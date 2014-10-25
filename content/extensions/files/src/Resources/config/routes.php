<?php return [
	'%admin%' =>[
		'subRoutes' => [
			'files/index' => ['controller' => '@files\Admin::index'],
			'files/browser' => ['controller' => '@files\Admin::browser'],
			'files/data' => ['controller' => '@files\Admin::data']
		]
	]
];